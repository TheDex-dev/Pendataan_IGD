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
    $('#kategori, #jenis_kelamin').on('change', function() {
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
                
                $('#loading').addClass('d-none');
            },
            error: function(xhr) {
                $('#loading').addClass('d-none');
                alert('Terjadi kesalahan saat memuat data');
            }
        });
    }

    }
});    // Example: Load escorts via Sanctum API
    function loadEscortsViaAPI(filters = {}) {
        if (typeof sanctumAuth !== 'undefined' && sanctumAuth.authenticated) {
            $('#loading').removeClass('d-none');
            
            sanctumAuth.getEscorts(filters)
                .then(response => {
                    console.log('Escorts from API:', response);
                    $('#loading').addClass('d-none');
                    
                    // You can process the API response here
                    // For now, we'll just log it and show a success message
                    if (response.data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'API Data Loaded',
                            text: `Loaded ${response.data.length} escorts via Sanctum API`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                })
                .catch(error => {
                    $('#loading').addClass('d-none');
                    console.error('Failed to load escorts via API:', error);
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'API Error',
                        text: 'Failed to load data via API: ' + error.message
                    });
                });
        }
    }



    // Function to check Sanctum authentication status
    function checkSanctumStatus() {
        if (typeof sanctumAuth !== 'undefined') {
            sanctumAuth.checkAuthStatus()
                .then(response => {
                    const status = `
                        <div class="alert alert-info">
                            <h6>Sanctum Authentication Status:</h6>
                            <ul class="mb-0">
                                <li><strong>Authenticated:</strong> ${response.authenticated ? 'Yes' : 'No'}</li>
                                <li><strong>User:</strong> ${response.user ? response.user.name + ' (' + response.user.email + ')' : 'Not logged in'}</li>
                                <li><strong>CSRF Token:</strong> ${response.csrf_token ? 'Present' : 'Missing'}</li>
                                <li><strong>Session ID:</strong> ${sanctumAuth.sessionId || 'N/A'}</li>
                            </ul>
                        </div>
                    `;
                    $('#api-status').html(status);
                })
                .catch(error => {
                    const status = `
                        <div class="alert alert-danger">
                            <h6>Authentication Check Failed:</h6>
                            <p class="mb-0">${error.message}</p>
                        </div>
                    `;
                    $('#api-status').html(status);
                });
        } else {
            $('#api-status').html(`
                <div class="alert alert-warning">
                    <p class="mb-0">Sanctum authentication helper not loaded.</p>
                </div>
            `);
        }
    }
});
</script>
@endpush
@endsection