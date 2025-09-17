<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Kategori</th>
                <th scope="col">Nama Pengantar</th>
                <th scope="col">Jenis Kelamin</th>
                <th scope="col">No. HP</th>
                <th scope="col">Plat Nomor</th>
                <th scope="col">Nama Pasien</th>
                <th scope="col">Tanggal Dibuat</th>
                <th scope="col">Foto</th>
            </tr>
        </thead>
        <tbody>
            @forelse($escorts as $index => $escort)
                <tr>
                    <td>{{ ($escorts->currentPage() - 1) * $escorts->perPage() + $index + 1 }}</td>
                    <td>
                        <span class="badge 
                            @if($escort->kategori_pengantar == 'Polisi') bg-primary
                            @elseif($escort->kategori_pengantar == 'Ambulans') bg-danger
                            @else bg-secondary
                            @endif">
                            {{ $escort->kategori_pengantar }}
                        </span>
                    </td>
                    <td>{{ $escort->nama_pengantar }}</td>
                    <td>{{ $escort->jenis_kelamin }}</td>
                    <td>
                        <a href="tel:{{ $escort->nomor_hp }}" class="text-decoration-none">
                            {{ $escort->nomor_hp }}
                        </a>
                    </td>
                    <td>
                        <code>{{ $escort->plat_nomor }}</code>
                    </td>
                    <td>{{ $escort->nama_pasien }}</td>
                    <td>
                        <small>
                            {{ $escort->created_at->format('d/m/Y H:i') }}<br>
                            <span class="text-muted">{{ $escort->created_at->diffForHumans() }}</span>
                        </small>
                    </td>
                    <td>
                        @if($escort->foto_pengantar)
                            <button type="button" class="btn btn-sm btn-outline-info" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#photoModal{{ $escort->id }}">
                                <i class="fas fa-image"></i> Lihat
                            </button>
                            
                            <!-- Photo Modal -->
                            <div class="modal fade" id="photoModal{{ $escort->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Foto Pengantar - {{ $escort->nama_pengantar }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ asset('storage/' . $escort->foto_pengantar) }}" 
                                                 class="img-fluid rounded" 
                                                 alt="Foto {{ $escort->nama_pengantar }}"
                                                 style="max-height: 500px;">
                                        </div>
                                        <div class="modal-footer">
                                            <a href="{{ asset('storage/' . $escort->foto_pengantar) }}" 
                                               target="_blank" class="btn btn-primary">
                                                <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                                            </a>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <span class="text-muted">
                                <i class="fas fa-image-slash"></i> Tidak ada foto
                            </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center py-4">
                        <div class="text-muted">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p class="mb-0">Tidak ada data escort yang ditemukan</p>
                            <small>Coba ubah filter pencarian atau tunggu respon dari server</small>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($escorts->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="text-muted small">
            Menampilkan {{ $escorts->firstItem() ?? 0 }} sampai {{ $escorts->lastItem() ?? 0 }} 
            dari {{ $escorts->total() }} hasil
        </div>
    </div>
@endif