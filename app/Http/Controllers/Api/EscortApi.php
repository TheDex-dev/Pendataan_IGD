<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EscortModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EscortApi extends Controller
{
    /**
     * Display a listing of the resource with session integration.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            // Track API access in session
            Session::put('api_last_accessed', now());
            Session::put('api_access_count', Session::get('api_access_count', 0) + 1);
            
            $query = EscortModel::query();
            
            // Apply filters if provided
            if ($request->has('kategori') && $request->kategori) {
                $query->where('kategori_pengantar', $request->kategori);
            }
            
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }
            
            if ($request->has('jenis_kelamin') && $request->jenis_kelamin) {
                $query->where('jenis_kelamin', $request->jenis_kelamin);
            }
            
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_pengantar', 'like', "%{$search}%")
                      ->orWhere('nama_pasien', 'like', "%{$search}%")
                      ->orWhere('nomor_hp', 'like', "%{$search}%")
                      ->orWhere('plat_nomor', 'like', "%{$search}%");
                });
            }
            
            $perPage = $request->get('per_page', 15);
            $escorts = $query->orderBy('created_at', 'desc')->paginate($perPage);
            
            // Store API response stats in session
            Session::put('api_last_result_count', $escorts->count());
            Session::put('api_last_total', $escorts->total());
            
            return response()->json([
                'status' => 'success',
                'message' => 'Escorts retrieved successfully',
                'data' => $escorts,
                'session_id' => Session::getId(),
                'meta' => [
                    'current_page' => $escorts->currentPage(),
                    'total_pages' => $escorts->lastPage(),
                    'per_page' => $escorts->perPage(),
                    'total' => $escorts->total(),
                    'api_access_count' => Session::get('api_access_count', 0)
                ]
            ], 200);
        } catch (\Exception $e) {
            // Log error and store in session
            Log::error('API Index Error', [
                'error' => $e->getMessage(),
                'session_id' => Session::getId(),
                'request' => $request->all()
            ]);
            
            Session::put('api_last_error', [
                'message' => $e->getMessage(),
                'timestamp' => now(),
                'endpoint' => 'index'
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve escorts',
                'error' => $e->getMessage(),
                'session_id' => Session::getId()
            ], 500);
        }
    }

    /**
     * Store a newly created resource with enhanced session integration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Generate unique submission ID for tracking
        $submissionId = 'api_' . uniqid();
        Session::put("api_submission_{$submissionId}_started", now());
        Session::put("api_submission_{$submissionId}_ip", $request->ip());
        Session::put("api_submission_{$submissionId}_user_agent", $request->userAgent());
        
        DB::beginTransaction();
        
        try {
            // Enhanced validation with custom messages
            $validatedData = $request->validate([
                'kategori_pengantar' => 'required|in:Polisi,Ambulans,Perorangan',
                'nama_pengantar' => 'required|string|max:255|min:3',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'nomor_hp' => 'required|string|max:20|min:10|regex:/^[0-9+\-\s]+$/',
                'plat_nomor' => 'required|string|max:20|min:3',
                'nama_pasien' => 'required|string|max:255|min:3',
                'foto_pengantar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'nullable|in:pending,verified,rejected'
            ], [
                'nama_pengantar.required' => 'Nama pengantar wajib diisi.',
                'nama_pengantar.min' => 'Nama pengantar minimal 3 karakter.',
                'nomor_hp.required' => 'Nomor HP wajib diisi.',
                'nomor_hp.regex' => 'Format nomor HP tidak valid.',
                'nomor_hp.min' => 'Nomor HP minimal 10 digit.',
                'plat_nomor.required' => 'Plat nomor wajib diisi.',
                'plat_nomor.min' => 'Plat nomor minimal 3 karakter.',
                'nama_pasien.required' => 'Nama pasien wajib diisi.',
                'nama_pasien.min' => 'Nama pasien minimal 3 karakter.',
                'foto_pengantar.image' => 'File harus berupa gambar.',
                'foto_pengantar.max' => 'Ukuran foto maksimal 2MB.',
                'status.in' => 'Status harus berupa pending, verified, atau rejected.',
            ]);
            
            // Handle file upload with session tracking
            if ($request->hasFile('foto_pengantar')) {
                $file = $request->file('foto_pengantar');
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                
                // Store file info in session
                Session::put("api_submission_{$submissionId}_file", [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'stored_name' => $filename
                ]);
                
                $file->storeAs('public/uploads', $filename);
                $validatedData['foto_pengantar'] = 'uploads/' . $filename;
            }
            
            // Add tracking data
            $validatedData['submission_id'] = $submissionId;
            $validatedData['submitted_from_ip'] = $request->ip();
            $validatedData['api_submission'] = true;
            
            // Set default status if not provided
            if (!isset($validatedData['status'])) {
                $validatedData['status'] = 'pending';
            }
            
            // Create escort record
            $escort = EscortModel::create($validatedData);
            
            // Update session with success data
            Session::put("api_submission_{$submissionId}_completed", now());
            Session::put("api_submission_{$submissionId}_escort_id", $escort->id);
            Session::increment('api_submissions_count');
            
            // Update recent API submissions in session
            $recentApiSubmissions = Session::get('recent_api_submissions', []);
            array_unshift($recentApiSubmissions, [
                'id' => $escort->id,
                'submission_id' => $submissionId,
                'nama_pengantar' => $escort->nama_pengantar,
                'nama_pasien' => $escort->nama_pasien,
                'kategori' => $escort->kategori_pengantar,
                'submitted_at' => now(),
                'ip' => $request->ip()
            ]);
            
            // Keep only last 20 API submissions in session
            $recentApiSubmissions = array_slice($recentApiSubmissions, 0, 20);
            Session::put('recent_api_submissions', $recentApiSubmissions);
            
            // Clear cache
            Cache::forget('escort_stats');
            
            // Commit transaction
            DB::commit();
            
            // Log successful submission
            Log::info('API Escort submission successful', [
                'submission_id' => $submissionId,
                'escort_id' => $escort->id,
                'ip' => $request->ip(),
                'category' => $escort->kategori_pengantar
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data escort berhasil ditambahkan melalui API',
                'data' => $escort,
                'submission_id' => $submissionId,
                'session_id' => Session::getId(),
                'meta' => [
                    'api_submissions_count' => Session::get('api_submissions_count', 0),
                    'timestamp' => now()
                ]
            ], 201);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            
            // Store validation errors in session
            Session::put("api_submission_{$submissionId}_failed", now());
            Session::put("api_submission_{$submissionId}_errors", $e->errors());
            
            Log::warning('API Escort validation failed', [
                'submission_id' => $submissionId,
                'errors' => $e->errors(),
                'ip' => $request->ip()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi data gagal',
                'errors' => $e->errors(),
                'submission_id' => $submissionId,
                'session_id' => Session::getId()
            ], 422);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            // Store general error in session
            Session::put("api_submission_{$submissionId}_error", now());
            Session::put("api_submission_{$submissionId}_error_message", $e->getMessage());
            
            Log::error('API Escort submission failed', [
                'submission_id' => $submissionId,
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data escort',
                'error' => $e->getMessage(),
                'submission_id' => $submissionId,
                'session_id' => Session::getId()
            ], 500);
        }
    }

    /**
     * Display the specified resource with session tracking.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $escort = EscortModel::findOrFail($id);
            
            // Track individual record access
            Session::put("escort_{$id}_last_viewed", now());
            Session::increment("escort_{$id}_view_count");
            
            // Add to recently viewed list
            $recentlyViewed = Session::get('recently_viewed_escorts', []);
            $recentlyViewed = array_filter($recentlyViewed, function($item) use ($id) {
                return $item['id'] != $id;
            });
            array_unshift($recentlyViewed, [
                'id' => $escort->id,
                'nama_pengantar' => $escort->nama_pengantar,
                'nama_pasien' => $escort->nama_pasien,
                'viewed_at' => now()
            ]);
            
            // Keep only last 10 viewed records
            $recentlyViewed = array_slice($recentlyViewed, 0, 10);
            Session::put('recently_viewed_escorts', $recentlyViewed);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Escort retrieved successfully',
                'data' => $escort,
                'session_id' => Session::getId(),
                'meta' => [
                    'view_count' => Session::get("escort_{$id}_view_count", 1),
                    'last_viewed' => Session::get("escort_{$id}_last_viewed")
                ]
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Track failed lookups
            Session::increment('api_failed_lookups');
            
            Log::warning('API Escort not found', [
                'escort_id' => $id,
                'ip' => request()->ip(),
                'session_id' => Session::getId()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Data escort tidak ditemukan',
                'session_id' => Session::getId()
            ], 404);
        } catch (\Exception $e) {
            Log::error('API Show Error', [
                'escort_id' => $id,
                'error' => $e->getMessage(),
                'session_id' => Session::getId()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data escort',
                'error' => $e->getMessage(),
                'session_id' => Session::getId()
            ], 500);
        }
    }

    /**
     * Update the specified resource with session tracking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        
        try {
            $escort = EscortModel::findOrFail($id);
            
            // Track update attempt
            $updateId = 'upd_' . uniqid();
            Session::put("api_update_{$updateId}_started", now());
            Session::put("api_update_{$updateId}_escort_id", $id);
            Session::put("api_update_{$updateId}_ip", $request->ip());
            
            $validatedData = $request->validate([
                'kategori_pengantar' => 'sometimes|required|in:Polisi,Ambulans,Perorangan',
                'nama_pengantar' => 'sometimes|required|string|max:255|min:3',
                'jenis_kelamin' => 'sometimes|required|in:Laki-laki,Perempuan',
                'nomor_hp' => 'sometimes|required|string|max:20|min:10',
                'plat_nomor' => 'sometimes|required|string|max:20|min:3',
                'nama_pasien' => 'sometimes|required|string|max:255|min:3',
                'foto_pengantar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'sometimes|required|in:pending,verified,rejected'
            ]);
            
            // Store original data for comparison
            $originalData = $escort->toArray();
            
            // Handle file upload
            if ($request->hasFile('foto_pengantar')) {
                $file = $request->file('foto_pengantar');
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/uploads', $filename);
                $validatedData['foto_pengantar'] = 'uploads/' . $filename;
            }
            
            $escort->update($validatedData);
            
            // Track successful update
            Session::put("api_update_{$updateId}_completed", now());
            Session::put("api_update_{$updateId}_changes", array_diff_assoc($validatedData, $originalData));
            Session::increment('api_updates_count');
            
            // Clear cache
            Cache::forget('escort_stats');
            
            DB::commit();
            
            Log::info('API Escort updated', [
                'update_id' => $updateId,
                'escort_id' => $id,
                'changes' => array_keys($validatedData),
                'ip' => $request->ip()
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data escort berhasil diperbarui',
                'data' => $escort->fresh(),
                'update_id' => $updateId,
                'session_id' => Session::getId(),
                'meta' => [
                    'updated_fields' => array_keys($validatedData),
                    'api_updates_count' => Session::get('api_updates_count', 0)
                ]
            ], 200);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Data escort tidak ditemukan',
                'session_id' => Session::getId()
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi data gagal',
                'errors' => $e->errors(),
                'session_id' => Session::getId()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('API Update Error', [
                'escort_id' => $id,
                'error' => $e->getMessage(),
                'session_id' => Session::getId()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data escort',
                'error' => $e->getMessage(),
                'session_id' => Session::getId()
            ], 500);
        }
    }

    /**
     * Remove the specified resource with session tracking.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $escort = EscortModel::findOrFail($id);
            
            // Store escort data before deletion
            $deletedData = $escort->toArray();
            $deleteId = 'del_' . uniqid();
            
            Session::put("api_delete_{$deleteId}_started", now());
            Session::put("api_delete_{$deleteId}_escort_data", $deletedData);
            Session::put("api_delete_{$deleteId}_ip", request()->ip());
            
            $escort->delete();
            
            // Track successful deletion
            Session::put("api_delete_{$deleteId}_completed", now());
            Session::increment('api_deletions_count');
            
            // Add to recently deleted list
            $recentlyDeleted = Session::get('recently_deleted_escorts', []);
            array_unshift($recentlyDeleted, [
                'id' => $deletedData['id'],
                'nama_pengantar' => $deletedData['nama_pengantar'],
                'nama_pasien' => $deletedData['nama_pasien'],
                'deleted_at' => now(),
                'delete_id' => $deleteId
            ]);
            
            // Keep only last 10 deleted records
            $recentlyDeleted = array_slice($recentlyDeleted, 0, 10);
            Session::put('recently_deleted_escorts', $recentlyDeleted);
            
            // Clear cache
            Cache::forget('escort_stats');
            
            DB::commit();
            
            Log::info('API Escort deleted', [
                'delete_id' => $deleteId,
                'escort_id' => $id,
                'escort_name' => $deletedData['nama_pengantar'],
                'ip' => request()->ip()
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data escort berhasil dihapus',
                'delete_id' => $deleteId,
                'session_id' => Session::getId(),
                'meta' => [
                    'api_deletions_count' => Session::get('api_deletions_count', 0)
                ]
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Data escort tidak ditemukan',
                'session_id' => Session::getId()
            ], 404);
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('API Delete Error', [
                'escort_id' => $id,
                'error' => $e->getMessage(),
                'session_id' => Session::getId()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data escort',
                'error' => $e->getMessage(),
                'session_id' => Session::getId()
            ], 500);
        }
    }
    
    /**
     * Update escort status with session tracking
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        DB::beginTransaction();
        
        try {
            $escort = EscortModel::findOrFail($id);
            
            // Track status update attempt
            $updateId = 'status_' . uniqid();
            Session::put("api_status_update_{$updateId}_started", now());
            Session::put("api_status_update_{$updateId}_escort_id", $id);
            Session::put("api_status_update_{$updateId}_ip", $request->ip());
            
            // Validate the status
            $request->validate([
                'status' => 'required|in:pending,verified,rejected'
            ]);
            
            $oldStatus = $escort->status;
            $newStatus = $request->status;
            
            // Update the escort status
            $escort->update([
                'status' => $newStatus
            ]);
            
            // Track successful status update
            Session::put("api_status_update_{$updateId}_completed", now());
            Session::put("api_status_update_{$updateId}_old_status", $oldStatus);
            Session::put("api_status_update_{$updateId}_new_status", $newStatus);
            Session::increment('api_status_updates_count');
            
            // Add to recent status updates in session
            $recentStatusUpdates = Session::get('recent_status_updates', []);
            array_unshift($recentStatusUpdates, [
                'escort_id' => $escort->id,
                'nama_pengantar' => $escort->nama_pengantar,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'updated_at' => now(),
                'update_id' => $updateId,
                'ip' => $request->ip()
            ]);
            
            // Keep only last 20 status updates in session
            $recentStatusUpdates = array_slice($recentStatusUpdates, 0, 20);
            Session::put('recent_status_updates', $recentStatusUpdates);
            
            // Clear cache
            Cache::forget('escort_stats');
            
            DB::commit();
            
            // Log the status change
            Log::info('API Escort status updated', [
                'update_id' => $updateId,
                'escort_id' => $escort->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'ip' => $request->ip()
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Status escort berhasil diperbarui',
                'data' => [
                    'escort_id' => $escort->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'status_display' => $escort->getStatusDisplayName(),
                    'badge_class' => $escort->getStatusBadgeClass()
                ],
                'update_id' => $updateId,
                'session_id' => Session::getId(),
                'meta' => [
                    'api_status_updates_count' => Session::get('api_status_updates_count', 0),
                    'timestamp' => now()
                ]
            ], 200);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Data escort tidak ditemukan',
                'session_id' => Session::getId()
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi status gagal',
                'errors' => $e->errors(),
                'session_id' => Session::getId()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('API Status Update Error', [
                'escort_id' => $id,
                'error' => $e->getMessage(),
                'session_id' => Session::getId()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui status escort',
                'error' => $e->getMessage(),
                'session_id' => Session::getId()
            ], 500);
        }
    }

    /**
     * Get session statistics and activity
     */
    public function getSessionStats()
    {
        return response()->json([
            'status' => 'success',
            'session_id' => Session::getId(),
            'stats' => [
                'api_access_count' => Session::get('api_access_count', 0),
                'api_submissions_count' => Session::get('api_submissions_count', 0),
                'api_updates_count' => Session::get('api_updates_count', 0),
                'api_status_updates_count' => Session::get('api_status_updates_count', 0),
                'api_deletions_count' => Session::get('api_deletions_count', 0),
                'api_failed_lookups' => Session::get('api_failed_lookups', 0),
                'last_accessed' => Session::get('api_last_accessed'),
                'last_result_count' => Session::get('api_last_result_count', 0),
                'last_total' => Session::get('api_last_total', 0)
            ],
            'recent_activity' => [
                'submissions' => Session::get('recent_api_submissions', []),
                'viewed' => Session::get('recently_viewed_escorts', []),
                'deleted' => Session::get('recently_deleted_escorts', []),
                'status_updates' => Session::get('recent_status_updates', [])
            ]
        ]);
    }

    /**
     * Get dashboard statistics for authenticated users
     */
    public function getDashboardStats()
    {
        try {
            $stats = [
                'total_escorts' => EscortModel::count(),
                'today_submissions' => EscortModel::whereDate('created_at', today())->count(),
                'this_week_submissions' => EscortModel::whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
                'this_month_submissions' => EscortModel::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'pending_count' => EscortModel::where('status', 'pending')->count(),
                'verified_count' => EscortModel::where('status', 'verified')->count(),
                'rejected_count' => EscortModel::where('status', 'rejected')->count(),
                'by_category' => EscortModel::selectRaw('kategori_pengantar, COUNT(*) as count')
                    ->groupBy('kategori_pengantar')
                    ->pluck('count', 'kategori_pengantar'),
                'by_status' => EscortModel::selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->pluck('count', 'status'),
                'recent_submissions' => EscortModel::latest()
                    ->limit(5)
                    ->select('id', 'nama_pengantar', 'nama_pasien', 'kategori_pengantar', 'status', 'created_at')
                    ->get()
            ];

            // Add session-specific stats
            $stats['session_info'] = [
                'session_id' => Session::getId(),
                'api_access_count' => Session::get('api_access_count', 0),
                'user_submissions' => Session::get('api_submissions_count', 0),
                'status_updates' => Session::get('api_status_updates_count', 0),
                'last_accessed' => Session::get('api_last_accessed')
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'Dashboard stats retrieved successfully',
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard Stats Error', [
                'error' => $e->getMessage(),
                'session_id' => Session::getId()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve dashboard stats',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
