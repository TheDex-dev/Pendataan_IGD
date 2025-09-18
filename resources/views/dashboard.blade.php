@extends('layout.app')

@section('title', 'Dashboard - Pendataan IGD')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Dashboard Pendataan IGD</h1>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Escort</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-count">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="today-count">{{ $stats['today'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Menunggu</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="pending-count">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Terverifikasi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="verified-count">{{ $stats['verified'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter & Pencarian</h6>
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
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Escort</h6>
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
    
    // Status update functionality
    $(document).on('click', '.btn-status-update', function(e) {
        e.preventDefault();
        
        const escortId = $(this).data('escort-id');
        const newStatus = $(this).data('status');
        const statusText = $(this).data('status-text');
        
        // Show confirmation dialog
        if (confirm(`Apakah Anda yakin ingin mengubah status menjadi "${statusText}"?`)) {
            updateEscortStatus(escortId, newStatus, $(this));
        }
    });
    
    function updateEscortStatus(escortId, status, buttonElement) {
        // Show loading state
        const originalText = buttonElement.text();
        buttonElement.prop('disabled', true).text('Loading...');
        
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
                    
                    // Show success message
                    showAlert('success', response.message);
                    
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
                    showAlert('error', 'Gagal memperbarui status');
                    buttonElement.prop('disabled', false).text(originalText);
                }
            },
            error: function(xhr) {
                console.error('Status update failed:', xhr);
                showAlert('error', 'Terjadi kesalahan saat memperbarui status');
                buttonElement.prop('disabled', false).text(originalText);
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