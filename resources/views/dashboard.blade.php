@extends('layout.app')

@section('title', 'Dashboard IGD')

@push('styles')
<style>
    /* Custom styles for SweetAlert2 popups */
    .swal-wide {
        width: 600px !important;
        max-width: 90% !important;
    }
    
    .swal2-popup .table {
        margin-bottom: 0;
        font-size: 0.9rem;
    }
    
    .swal2-popup .table td {
        padding: 0.4rem;
        vertical-align: middle;
    }
    
    .swal2-popup .table td:first-child {
        width: 35%;
        color: #6c757d;
    }
    
    .text-left {
        text-align: left !important;
    }
    
    .swal2-popup .alert {
        margin-bottom: 0;
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h2 mb-0">Dashboard Pendataan IGD</h1>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4 g-3">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-gradient-primary text-white shadow-sm rounded-4 border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 rounded-circle bg-white bg-opacity-25 p-3">
                            <i class="fas fa-users fa-2x text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="card-title mb-1 text-white-50">Total Pengantar</h6>
                            <h2 class="mb-0 fw-bold" id="total-count">{{ $stats['total'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-gradient-info text-white shadow-sm rounded-4 border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 rounded-circle bg-white bg-opacity-25 p-3">
                            <i class="fas fa-calendar-day fa-2x text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="card-title mb-1 text-white-50">Hari Ini</h6>
                            <h2 class="mb-0 fw-bold" id="today-count">{{ $stats['today'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-gradient-warning text-white shadow-sm rounded-4 border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 rounded-circle bg-white bg-opacity-25 p-3">
                            <i class="fas fa-clock fa-2x text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="card-title mb-1 text-white-50">Menunggu</h6>
                            <h2 class="mb-0 fw-bold" id="pending-count">{{ $stats['pending'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-gradient-success text-white shadow-sm rounded-4 border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 rounded-circle bg-white bg-opacity-25 p-3">
                            <i class="fas fa-check-circle fa-2x text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="card-title mb-1 text-white-50">Terverifikasi</h6>
                            <h2 class="mb-0 fw-bold" id="verified-count">{{ $stats['verified'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">Filter & Pencarian</h6>
        </div>
        <div class="card-body">
            <form id="filter-form">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">Pencarian</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Cari nama, HP, plat nomor..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori" name="kategori">
                            <option value="">Semua Kategori</option>
                            <option value="Polisi" {{ request('kategori') == 'Polisi' ? 'selected' : '' }}>Polisi</option>
                            <option value="Ambulans" {{ request('kategori') == 'Ambulans' ? 'selected' : '' }}>Ambulans</option>
                            <option value="Perorangan" {{ request('kategori') == 'Perorangan' ? 'selected' : '' }}>Perorangan</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin">
                            <option value="">Semua</option>
                            <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-refresh"></i> Refresh
                        </a>
                        <button type="button" id="view-all-btn" class="btn btn-info me-2">
                            <i class="fas fa-list"></i> Lihat Semua
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">Data Escort</h6>
        </div>
        <div class="card-body">
            <div id="table-container">
                @include('partials.escort-table', ['escorts' => $escorts])
            </div>
            
            <!-- Pagination -->
            <div id="pagination-container" class="mt-3">
                {{ $escorts->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Loading Spinner -->
<div id="loading" class="d-none">
    <div class="d-flex justify-content-center">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
.border-left-primary {
    border-left: 4px solid #4e73df !important;
}
.border-left-success {
    border-left: 4px solid #1cc88a !important;
}
.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}
.border-left-info {
    border-left: 4px solid #36b9cc !important;
}
.text-xs {
    font-size: 0.75rem;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
.text-gray-300 {
    color: #dddfeb !important;
}
.table-responsive {
    max-height: 600px;
    overflow-y: auto;
}

.badge-warning {
    background-color: #f6c23e !important;
    color: #1f2937 !important;
}

.badge-success {
    background-color: #1cc88a !important;
    color: white !important;
}

.badge-danger {
    background-color: #e74a3b !important;
    color: white !important;
}

.btn-status-update {
    margin: 1px;
}

.btn-group .btn {
    margin-right: 2px;
}
</style>
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Load initial data
    loadData();

    // AJAX form submission
    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        loadData();
    });
    
    // Auto search on input change with delay
    let searchTimeout;
    $('#search').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            loadData();
        }, 500);
    });
    
    // Auto filter on select change
    $('#kategori, #jenis_kelamin, #status').on('change', function() {
        loadData();
    });
    
    // View All button handler
    $('#view-all-btn').on('click', function(e) {
        e.preventDefault();
        
        // Show confirmation dialog with SweetAlert2
        Swal.fire({
            title: 'Lihat Semua Data',
            html: `
                <div class="text-left">
                    <p>Anda akan melihat semua data escort dengan batas maksimal <strong>5000 record</strong>.</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 
                        <strong>Perhatian:</strong> Memuat data dalam jumlah besar dapat memerlukan waktu loading yang lebih lama.
                    </div>
                    <p>Apakah Anda yakin ingin melanjutkan?</p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#17a2b8',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Lihat Semua',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'swal-wide',
                content: 'text-left'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                loadAllData();
            }
        });
    });
    
    // Pagination click handler
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        if (url) {
            loadDataFromUrl(url);
        }
    });
    
    function loadData() {
        let formData = $('#filter-form').serialize();
        loadDataFromUrl('{{ route("dashboard") }}?' + formData);
    }
    
    function loadAllData() {
        // Show loading with progress indication
        Swal.fire({
            title: 'Memuat Semua Data...',
            html: `
                <div class="text-center">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Sedang memuat data, mohon tunggu...</p>
                    <small class="text-muted">Proses ini mungkin memerlukan beberapa detik</small>
                </div>
            `,
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
        
        let formData = $('#filter-form').serialize();
        let url = '{{ route("dashboard") }}?' + formData + '&view_all=true';
        
        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                $('#table-container').html(response.html);
                $('#pagination-container').html(response.pagination);
                
                // Update statistics
                $('#total-count').text(response.stats.total);
                $('#today-count').text(response.stats.today);
                $('#pending-count').text(response.stats.pending);
                $('#verified-count').text(response.stats.verified);
                
                // Show success message with record count
                Swal.fire({
                    icon: 'success',
                    title: 'Data Berhasil Dimuat!',
                    html: `
                        <div class="text-center">
                            <p>Menampilkan <strong>${response.record_count || 'semua'}</strong> record data escort</p>
                            ${response.is_limited ? '<small class="text-warning">Data dibatasi maksimal 5000 record untuk performa optimal</small>' : ''}
                        </div>
                    `,
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function(xhr) {
                console.error('Load all data failed:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memuat Data',
                    text: 'Terjadi kesalahan saat memuat semua data. Silakan coba lagi.',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    }
    
    function loadDataFromUrl(url) {
        $('#loading').removeClass('d-none');
        
        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                $('#table-container').html(response.html);
                $('#pagination-container').html(response.pagination);
                
                // Update statistics
                $('#total-count').text(response.stats.total);
                $('#today-count').text(response.stats.today);
                $('#pending-count').text(response.stats.pending);
                $('#verified-count').text(response.stats.verified);
                
                $('#loading').addClass('d-none');
            },
            error: function(xhr) {
                $('#loading').addClass('d-none');
                alert('Terjadi kesalahan saat memuat data');
            }
        });
    }
    
    // Status update functionality with SweetAlert2
    $(document).on('click', '.btn-status-update', function(e) {
        e.preventDefault();
        
        const escortId = $(this).data('escort-id');
        const newStatus = $(this).data('status');
        const statusText = $(this).data('status-text');
        const buttonElement = $(this);
        
            // Get escort data from the table row
        const row = buttonElement.closest('tr');
        const escortData = {
            kategori_pengantar: row.find('td:nth-child(2) span.kategori-badge').text().trim(),
            nama_pengantar: row.find('td:nth-child(3)').text().trim(),
            jenis_kelamin: row.find('td:nth-child(4)').text().trim(),
            nomor_hp: row.find('td:nth-child(5)').text().trim(),
            plat_nomor: row.find('td:nth-child(6)').text().trim(),
            nama_pasien: row.find('td:nth-child(7)').text().trim(),
            waktu_masuk: row.find('td:nth-child(9)').text().trim()
        };        // Determine color and icon based on status
        const statusConfig = {
            'verified': {
                confirmButtonColor: '#28a745',
                icon: 'success',
                title: 'Verifikasi Pengantar'
            },
            'rejected': {
                confirmButtonColor: '#dc3545',
                icon: 'warning',
                title: 'Tolak Pengantar'
            },
            'pending': {
                confirmButtonColor: '#ffc107',
                icon: 'question',
                title: 'Kembalikan ke Menunggu'
            }
        };
        
        // Show SweetAlert2 confirmation dialog with escort details
        Swal.fire({
            title: statusConfig[newStatus].title,
            html: `
                <div class="text-left">
                    <h6 class="mb-3">Detail Pengantar:</h6>
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Nama Pengantar</strong></td>
                            <td>: ${escortData.nama_pengantar}</td>
                        </tr>
                        <tr>
                            <td><strong>Kategori</strong></td>
                            <td>: ${escortData.kategori_pengantar}</td>
                        </tr>
                        <tr>
                            <td><strong>No. HP</strong></td>
                            <td>: ${escortData.nomor_hp}</td>
                        </tr>
                        <tr>
                            <td><strong>Plat Nomor</strong></td>
                            <td>: ${escortData.plat_nomor}</td>
                        </tr>
                        <tr>
                            <td><strong>Nama Pasien</strong></td>
                            <td>: ${escortData.nama_pasien}</td>
                        </tr>
                        <tr>
                            <td><strong>Waktu Masuk</strong></td>
                            <td>: ${escortData.waktu_masuk}</td>
                        </tr>
                    </table>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i> Apakah Anda yakin ingin mengubah status menjadi <strong>"${statusText}"</strong>?
                    </div>
                </div>
            `,
            icon: statusConfig[newStatus].icon,
            showCancelButton: true,
            confirmButtonColor: statusConfig[newStatus].confirmButtonColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Ubah Status',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'swal-wide',
                content: 'text-left'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                updateEscortStatus(escortId, newStatus, buttonElement);
            }
        });
    });
    
    function updateEscortStatus(escortId, status, buttonElement) {
        // Show loading state with SweetAlert2
        Swal.fire({
            title: 'Memproses...',
            text: 'Mohon tunggu sebentar',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: `/escorts/${escortId}/status`,
            type: 'PATCH',
            data: {
                status: status,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Update the status badge in the table
                    const statusBadge = $(`.status-badge-${escortId}`);
                    statusBadge.removeClass('badge-warning badge-success badge-danger')
                            .addClass(response.badge_class)
                            .text(response.status_display);
                    
                    // Update statistics
                    loadData();
                    
                    // Show success message with SweetAlert2
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    
                    // Update button states
                    $(`.btn-status-update[data-escort-id="${escortId}"]`).each(function() {
                        $(this).prop('disabled', false);
                        if ($(this).data('status') === status) {
                            $(this).addClass('btn-secondary').removeClass('btn-outline-success btn-outline-danger')
                                   .prop('disabled', true);
                        } else {
                            $(this).removeClass('btn-secondary').prop('disabled', false);
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Gagal memperbarui status',
                        confirmButtonColor: '#dc3545'
                    });
                }
            },
            error: function(xhr) {
                console.error('Status update failed:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Gagal memperbarui status pengantar. Silakan coba lagi.',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    }
    
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Remove existing alerts
        $('.alert').remove();
        
        // Add new alert at the top of the container
        $('.container-fluid').prepend(alertHtml);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            $('.alert').fadeOut();
        }, 5000);
    }
});
</script>
@endpush
@endsection