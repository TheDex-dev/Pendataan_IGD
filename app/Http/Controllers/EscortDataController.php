<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EscortModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class EscortDataController extends Controller
{
    /**
     * Display the form for creating new escort data
     */
    public function index()
    {
        // Store form access in session for tracking
        Session::put('form_accessed_at', now());
        Session::put('form_user_ip', request()->ip());
        
        // Get flash messages from session
        $successMessage = Session::get('escort_success');
        $errorMessage = Session::get('escort_error');
        
        return view('form.index', compact('successMessage', 'errorMessage'));
    }
    
    /**
     * Display the dashboard with escort data and statistics
     */
    public function dashboard(Request $request)
    {
        // Store dashboard access in session
        Session::put('dashboard_accessed_at', now());
        Session::put('dashboard_filters', $request->only(['search', 'kategori', 'jenis_kelamin']));
        
        $query = EscortModel::query();
        
        // Handle search functionality with session persistence
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            Session::put('last_search', $search);
            
            $query->where(function($q) use ($search) {
                $q->where('nama_pengantar', 'like', "%{$search}%")
                  ->orWhere('nama_pasien', 'like', "%{$search}%")
                  ->orWhere('nomor_hp', 'like', "%{$search}%")
                  ->orWhere('plat_nomor', 'like', "%{$search}%")
                  ->orWhere('kategori_pengantar', 'like', "%{$search}%");
            });
        } elseif (!$request->has('search') && Session::has('last_search')) {
            // Restore previous search from session if no new search
            $search = Session::get('last_search');
            $query->where(function($q) use ($search) {
                $q->where('nama_pengantar', 'like', "%{$search}%")
                  ->orWhere('nama_pasien', 'like', "%{$search}%")
                  ->orWhere('nomor_hp', 'like', "%{$search}%")
                  ->orWhere('plat_nomor', 'like', "%{$search}%")
                  ->orWhere('kategori_pengantar', 'like', "%{$search}%");
            });
        }
        
        // Handle filter by category with session persistence
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_pengantar', $request->kategori);
            Session::put('last_kategori_filter', $request->kategori);
        }
        
        // Handle filter by gender with session persistence
        if ($request->has('jenis_kelamin') && $request->jenis_kelamin != '') {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
            Session::put('last_gender_filter', $request->jenis_kelamin);
        }
        
        // Order by latest first
        $query->orderBy('created_at', 'desc');
        
        // Get page size from session or default
        $pageSize = Session::get('dashboard_page_size', 15);
        $escorts = $query->paginate($pageSize)->appends($request->query());
        
        // Get cached or fresh statistics
        $stats = Cache::remember('escort_stats', 300, function() {
            return [
                'total' => EscortModel::count(),
                'today' => EscortModel::whereDate('created_at', today())->count(),
                'this_week' => EscortModel::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'this_month' => EscortModel::whereMonth('created_at', now()->month)->count(),
            ];
        });
        
        // Handle AJAX requests with session data
        if ($request->ajax()) {
            // Store AJAX request in session for debugging
            Session::put('last_ajax_request', [
                'timestamp' => now(),
                'filters' => $request->all(),
                'results_count' => $escorts->count()
            ]);
            
            return response()->json([
                'status' => 'success',
                'html' => view('partials.escort-table', compact('escorts'))->render(),
                'pagination' => $escorts->links()->render(),
                'stats' => $stats,
                'session_id' => Session::getId(),
                'filters_applied' => Session::get('dashboard_filters', [])
            ]);
        }
        
        // Get recent form submissions from session
        $recentSubmissions = Session::get('recent_submissions', []);
        
        return view('dashboard', compact('escorts', 'stats', 'recentSubmissions'));
    }
    
    /**
     * Store new escort data with enhanced session handling
     */
    public function store(Request $request)
    {
        // Start session tracking for this submission
        $submissionId = uniqid('sub_');
        Session::put("submission_{$submissionId}_started", now());
        Session::put("submission_{$submissionId}_ip", $request->ip());
        Session::put("submission_{$submissionId}_user_agent", $request->userAgent());
        
        try {
            // Enhanced validation with custom messages
            $validatedData = $request->validate([
                'kategori_pengantar' => 'required|in:Polisi,Ambulans,Perorangan',
                'nama_pengantar' => 'required|string|max:255|min:3',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'nomor_hp' => 'required|string|max:20|min:10|regex:/^[0-9+\-\s]+$/',
                'plat_nomor' => 'required|string|max:20|min:3',
                'nama_pasien' => 'required|string|max:255|min:3',
                'foto_pengantar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
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
            ]);
            
            // Handle file upload with session tracking
            if ($request->hasFile('foto_pengantar')) {
                $file = $request->file('foto_pengantar');
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                
                // Store file info in session
                Session::put("submission_{$submissionId}_file", [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'stored_name' => $filename
                ]);
                
                $file->storeAs('public/uploads', $filename);
                $validatedData['foto_pengantar'] = 'uploads/' . $filename;
            }
            
            // Add submission tracking data
            $validatedData['submission_id'] = $submissionId;
            $validatedData['submitted_from_ip'] = $request->ip();
            
            // Create the escort record
            $escort = EscortModel::create($validatedData);
            
            // Store success in session
            Session::put("submission_{$submissionId}_completed", now());
            Session::put("submission_{$submissionId}_escort_id", $escort->id);
            
            // Add to recent submissions list
            $recentSubmissions = Session::get('recent_submissions', []);
            array_unshift($recentSubmissions, [
                'id' => $escort->id,
                'nama_pengantar' => $escort->nama_pengantar,
                'nama_pasien' => $escort->nama_pasien,
                'kategori' => $escort->kategori_pengantar,
                'submitted_at' => now(),
                'submission_id' => $submissionId
            ]);
            
            // Keep only last 10 submissions in session
            $recentSubmissions = array_slice($recentSubmissions, 0, 10);
            Session::put('recent_submissions', $recentSubmissions);
            
            // Flash success message
            Session::flash('escort_success', 'Data escort berhasil ditambahkan! ID: ' . $escort->id);
            
            // Clear cache
            Cache::forget('escort_stats');
            
            // Log successful submission
            Log::info('Escort data submitted successfully', [
                'submission_id' => $submissionId,
                'escort_id' => $escort->id,
                'ip' => $request->ip(),
                'category' => $escort->kategori_pengantar
            ]);
            
            // Return appropriate response based on request type
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data escort berhasil ditambahkan!',
                    'data' => $escort,
                    'submission_id' => $submissionId,
                    'redirect' => route('form.index')
                ], 201);
            }
            
            return redirect()->route('form.index')->with('success', 'Data escort berhasil ditambahkan!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Store validation errors in session
            Session::put("submission_{$submissionId}_failed", now());
            Session::put("submission_{$submissionId}_errors", $e->errors());
            
            Session::flash('escort_error', 'Validasi gagal: ' . implode(', ', array_flatten($e->errors())));
            
            Log::warning('Escort submission validation failed', [
                'submission_id' => $submissionId,
                'errors' => $e->errors(),
                'ip' => $request->ip()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors(),
                    'submission_id' => $submissionId
                ], 422);
            }
            
            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            // Store general error in session
            Session::put("submission_{$submissionId}_error", now());
            Session::put("submission_{$submissionId}_error_message", $e->getMessage());
            
            Session::flash('escort_error', 'Gagal menambahkan data: ' . $e->getMessage());
            
            Log::error('Escort submission failed', [
                'submission_id' => $submissionId,
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menambahkan data escort',
                    'error' => $e->getMessage(),
                    'submission_id' => $submissionId
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * Get submission details from session
     */
    public function getSubmissionDetails($submissionId)
    {
        $details = [
            'started' => Session::get("submission_{$submissionId}_started"),
            'completed' => Session::get("submission_{$submissionId}_completed"),
            'failed' => Session::get("submission_{$submissionId}_failed"),
            'ip' => Session::get("submission_{$submissionId}_ip"),
            'user_agent' => Session::get("submission_{$submissionId}_user_agent"),
            'file_info' => Session::get("submission_{$submissionId}_file"),
            'escort_id' => Session::get("submission_{$submissionId}_escort_id"),
            'errors' => Session::get("submission_{$submissionId}_errors"),
        ];
        
        return response()->json($details);
    }
    
    /**
     * Clear old session data (can be called via scheduled job)
     */
    public function clearOldSessionData()
    {
        $sessionKeys = array_keys(Session::all());
        $cleared = 0;
        
        foreach ($sessionKeys as $key) {
            if (strpos($key, 'submission_') === 0) {
                $timestamp = Session::get($key);
                if ($timestamp && now()->diffInHours($timestamp) > 24) {
                    Session::forget($key);
                    $cleared++;
                }
            }
        }
        
        return response()->json(['cleared' => $cleared]);
    }
}