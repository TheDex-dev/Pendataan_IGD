@extends('layout.app')

@section('title','Formulir Pengantar Pasien')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    .text-xs {
        font-size: 0.75rem;
    }
    .text-gray-800 {
        color: #5a5c69 !important;
    }
    .text-gray-300 {
        color: #dddfeb !important;
    }
    .border-left-primary {
        border-left: 4px solid #4e73df !important;
    }
    
    .form-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    .form-card {
        background: #fff;
        border-radius: 0.35rem;
        padding: 0;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border: 1px solid #e3e6f0;
        margin-bottom: 2rem;
    }

    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        padding: 0.75rem 1.25rem;
        border-radius: calc(0.35rem - 1px) calc(0.35rem - 1px) 0 0;
    }

    .card-body {
        padding: 1.25rem;
    }

    .form-title {
        color: #5a5c69;
        font-weight: 600;
        font-size: 1.375rem;
        margin: 0;
    }

    .form-subtitle {
        color: #858796;
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
        margin-top: 0.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #5a5c69;
        font-size: 0.875rem;
    }

    .form-control, .form-select {
        display: block;
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 400;
        line-height: 1.5;
        color: #6e707e;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus, .form-select:focus {
        color: #6e707e;
        background-color: #fff;
        border-color: #bac8f3;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .form-control::placeholder {
        color: #858796;
        opacity: 1;
    }

    .input-group {
        position: relative;
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
        width: 100%;
    }

    .input-group-text {
        display: flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 400;
        line-height: 1.5;
        color: #6e707e;
        text-align: center;
        white-space: nowrap;
        background-color: #f8f9fc;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
    }

    .input-group > .form-control {
        position: relative;
        flex: 1 1 auto;
        width: 1%;
        min-width: 0;
    }

    .input-group > .input-group-text + .form-control {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .input-group > .input-group-text {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .btn-primary {
        color: #fff;
        background-color: #4e73df;
        border-color: #4e73df;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.35rem;
        border: 1px solid transparent;
        font-weight: 400;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        user-select: none;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        width: 100%;
    }

    .btn-primary:hover {
        color: #fff;
        background-color: #2e59d9;
        border-color: #2653d4;
    }

    .btn-primary:focus {
        color: #fff;
        background-color: #2e59d9;
        border-color: #2653d4;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.5);
    }

    .btn-primary:disabled {
        color: #fff;
        background-color: #4e73df;
        border-color: #4e73df;
        opacity: 0.65;
    }

    .file-input-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .file-input {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .file-input-display {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        border: 2px dashed #d1d3e2;
        border-radius: 0.35rem;
        background: #f8f9fc;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-input-display:hover {
        border-color: #4e73df;
        background: #eaecf4;
    }

    .file-input-display.has-file {
        border-style: solid;
        border-color: #1cc88a;
        background: #f0fff4;
    }

    .file-icon {
        font-size: 1.25rem;
        margin-right: 0.75rem;
        color: #858796;
    }

    .file-text {
        flex: 1;
        color: #858796;
        font-size: 0.875rem;
    }

    .file-input-display.has-file .file-text {
        color: #1cc88a;
    }

    .file-input-display.processing {
        border-color: #4e73df;
        background: #f0f4ff;
    }

    .file-input-display.processing .file-text {
        color: #4e73df;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .text-muted {
        color: #6c757d !important;
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -0.75rem;
        margin-left: -0.75rem;
    }

    .form-row > .col {
        flex-basis: 0;
        flex-grow: 1;
        max-width: 100%;
        padding-right: 0.75rem;
        padding-left: 0.75rem;
    }

    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }

    @media (max-width: 767.98px) {
        .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .form-card {
            margin: 1rem;
        }
    }

    .loading-spinner {
        display: none;
    }

    .btn-primary.loading .btn-text {
        display: none;
    }

    .btn-primary.loading .loading-spinner {
        display: inline-block;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="form-container">
        <div class="card shadow mb-4">
            <div class="card-header py-3 border-left-primary">
                <h6 class="m-0 font-weight-bold text-primary form-title">
                    <i class="fas fa-user-plus"></i> Form Data Pengantar Pasien
                </h6>
                <p class="form-subtitle">Silakan lengkapi data pengantar pasien di bawah ini</p>
            </div>
            <div class="card-body">
                <!-- Session-based success/error messages -->
                @if(isset($successMessage) && $successMessage)
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ $successMessage }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(isset($errorMessage) && $errorMessage)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> {{ $errorMessage }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" id="escortForm" enctype="multipart/form-data">
                    
                    <div class="form-group">
                        <label for="kategori_pengantar" class="form-label">
                            <i class="fas fa-tags"></i> Kategori Pengantar
                        </label>
                        <select class="form-select" id="kategori_pengantar" name="kategori_pengantar" required>
                            <option value="">Pilih kategori pengantar...</option>
                            <option value="Polisi">Polisi</option>
                            <option value="Ambulans">Ambulans</option>
                            <option value="Perorangan">Perorangan</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_pengantar" class="form-label">
                                    <i class="fas fa-user"></i> Nama Pengantar
                                </label>
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="nama_pengantar" name="nama_pengantar" 
                                           placeholder="Masukkan nama lengkap" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jenis_kelamin" class="form-label">
                                    <i class="fas fa-venus-mars"></i> Jenis Kelamin
                                </label>
                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih jenis kelamin...</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nomor_hp" class="form-label">
                                    <i class="fas fa-phone"></i> Nomor HP
                                </label>
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <input type="tel" class="form-control" id="nomor_hp" name="nomor_hp" 
                                           placeholder="Contoh: 08123456789" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="plat_nomor" class="form-label">
                                    <i class="fas fa-car"></i> Plat Nomor
                                </label>
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <i class="fas fa-car"></i>
                                    </div>
                                    <input type="text" class="form-control" id="plat_nomor" name="plat_nomor" 
                                           placeholder="Contoh: B 1234 ABC" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nama_pasien" class="form-label">
                            <i class="fas fa-user-injured"></i> Nama Pasien
                        </label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fas fa-user-injured"></i>
                            </div>
                            <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" 
                                   placeholder="Masukkan nama lengkap pasien" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="foto_pengantar" class="form-label">
                            <i class="fas fa-camera"></i> Foto Pengantar <span class="text-danger">*</span>
                        </label>
                        <div class="file-input-wrapper">
                            <input type="file" class="file-input" id="foto_pengantar" name="foto_pengantar" 
                                   accept="image/*">
                            <div class="file-input-display">
                                <i class="fas fa-cloud-upload-alt file-icon"></i>
                                <span class="file-text">Klik untuk memilih foto atau drag & drop</span>
                            </div>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            Maksimal 2MB. Format yang didukung: JPG, PNG, GIF
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="btn-text">
                            <i class="fas fa-paper-plane"></i> Kirim Data
                        </span>
                        <span class="loading-spinner">
                            <i class="fas fa-spinner fa-spin"></i> Memproses...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function() {
        // File input handling with base64 encoding
        let selectedImageBase64 = null;
        let selectedImageInfo = null;
        
        $('#foto_pengantar').on('change', function() {
            const file = this.files[0];
            const display = $('.file-input-display');
            const text = $('.file-text');
            
            if (file) {
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format File Tidak Valid',
                        text: 'Harap pilih file gambar dengan format JPG, PNG, atau GIF.',
                        confirmButtonColor: '#dc3545'
                    });
                    this.value = '';
                    display.removeClass('has-file');
                    text.text('Klik untuk memilih foto atau drag & drop');
                    selectedImageBase64 = null;
                    selectedImageInfo = null;
                    return;
                }
                
                // Validate file size (2MB max)
                const maxSize = 2 * 1024 * 1024; // 2MB in bytes
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran File Terlalu Besar',
                        text: 'Ukuran file maksimal adalah 2MB. Silakan pilih file yang lebih kecil.',
                        confirmButtonColor: '#dc3545'
                    });
                    this.value = '';
                    display.removeClass('has-file');
                    text.text('Klik untuk memilih foto atau drag & drop');
                    selectedImageBase64 = null;
                    selectedImageInfo = null;
                    return;
                }
                
                // Show loading state
                display.addClass('processing').removeClass('has-file');
                text.html('<i class="fas fa-spinner fa-spin"></i> Memproses gambar...');
                
                // Convert to base64
                const reader = new FileReader();
                reader.onload = function(e) {
                    selectedImageBase64 = e.target.result;
                    selectedImageInfo = {
                        name: file.name,
                        size: file.size,
                        type: file.type,
                        lastModified: file.lastModified
                    };
                    
                    display.removeClass('processing').addClass('has-file');
                    text.html(`<i class="fas fa-check-circle text-success"></i> File dipilih: ${file.name} (${formatFileSize(file.size)})`);
                    
                    console.log('Image encoded to base64, size:', selectedImageBase64.length, 'characters');
                };
                
                reader.onerror = function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memproses File',
                        text: 'Terjadi kesalahan saat memproses file gambar. Silakan coba lagi.',
                        confirmButtonColor: '#dc3545'
                    });
                    display.removeClass('processing has-file');
                    text.text('Klik untuk memilih foto atau drag & drop');
                    selectedImageBase64 = null;
                    selectedImageInfo = null;
                };
                
                reader.readAsDataURL(file);
            } else {
                display.removeClass('has-file');
                text.text('Klik untuk memilih foto atau drag & drop');
                selectedImageBase64 = null;
                selectedImageInfo = null;
            }
        });
        
        // Helper function to format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Drag and drop functionality with base64 support
        $('.file-input-display').on('dragover', function(e) {
            e.preventDefault();
            $(this).css('border-color', '#4e73df');
        });

        $('.file-input-display').on('dragleave', function(e) {
            e.preventDefault();
            $(this).css('border-color', '#d1d3e2');
        });

        $('.file-input-display').on('drop', function(e) {
            e.preventDefault();
            $(this).css('border-color', '#d1d3e2');
            
            const files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                const file = files[0];
                
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format File Tidak Valid',
                        text: 'Harap pilih file gambar dengan format JPG, PNG, atau GIF.',
                        confirmButtonColor: '#dc3545'
                    });
                    return;
                }
                
                // Validate file size (2MB max)
                const maxSize = 2 * 1024 * 1024; // 2MB in bytes
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran File Terlalu Besar',
                        text: 'Ukuran file maksimal adalah 2MB. Silakan pilih file yang lebih kecil.',
                        confirmButtonColor: '#dc3545'
                    });
                    return;
                }
                
                // Create a FileList-like object and assign to input
                const dt = new DataTransfer();
                dt.items.add(file);
                $('#foto_pengantar')[0].files = dt.files;
                $('#foto_pengantar').trigger('change');
            }
        });

        // Phone number formatting
        $('#nomor_hp').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            if (value.startsWith('0')) {
                value = value;
            } else if (value.startsWith('62')) {
                value = '0' + value.substring(2);
            }
            $(this).val(value);
        });

        // Plate number formatting
        $('#plat_nomor').on('input', function() {
            let value = $(this).val().toUpperCase();
            $(this).val(value);
        });

        // Form submission with base64 image encoding
        $('#escortForm').on('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = $('#submitBtn');
            
            // Validate required image
            if (!selectedImageBase64) {
                Swal.fire({
                    icon: 'error',
                    title: 'Foto Wajib Diisi',
                    text: 'Silakan pilih foto pengantar terlebih dahulu.',
                    confirmButtonColor: '#dc3545'
                });
                return;
            }
            
            // Prepare form data
            const formData = {
                kategori_pengantar: $('#kategori_pengantar').val(),
                nama_pengantar: $('#nama_pengantar').val(),
                jenis_kelamin: $('#jenis_kelamin').val(),
                nomor_hp: $('#nomor_hp').val(),
                plat_nomor: $('#plat_nomor').val(),
                nama_pasien: $('#nama_pasien').val(),
                foto_pengantar_base64: selectedImageBase64,
                foto_pengantar_info: selectedImageInfo
            };
            
            // Store form submission attempt in localStorage for tracking
            const submissionAttempt = {
                timestamp: new Date().toISOString(),
                category: formData.kategori_pengantar,
                name: formData.nama_pengantar,
                patient: formData.nama_pasien,
                image_size: selectedImageBase64 ? selectedImageBase64.length : 0
            };
            localStorage.setItem('lastSubmissionAttempt', JSON.stringify(submissionAttempt));
            
            // Disable button and show loading
            submitBtn.addClass('loading').prop('disabled', true);
            
            // Show loading alert
            Swal.fire({
                title: 'Memproses Data',
                text: 'Mohon tunggu sebentar...',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: '/api/escort',
                type: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                processData: false,
                success: function (response) {
                    submitBtn.removeClass('loading').prop('disabled', false);
                    
                    // Store successful submission data
                    const submissionSuccess = {
                        timestamp: new Date().toISOString(),
                        submission_id: response.submission_id,
                        escort_id: response.data ? response.data.id : null,
                        session_id: response.session_id,
                        message: response.message
                    };
                    localStorage.setItem('lastSuccessfulSubmission', JSON.stringify(submissionSuccess));
                    
                    // Update submission counter
                    let submissionCount = parseInt(localStorage.getItem('submissionCount') || '0');
                    localStorage.setItem('submissionCount', (submissionCount + 1).toString());
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        html: `
                            <div>
                                <p>${response.message || 'Data berhasil disimpan'}</p>
                                ${response.submission_id ? `<small class="text-muted">ID Pengiriman: ${response.submission_id}</small>` : ''}
                                ${response.data && response.data.id ? `<br><small class="text-muted">ID Escort: ${response.data.id}</small>` : ''}
                            </div>
                        `,
                        confirmButtonText: 'OK',
                        timer: 5000,
                        timerProgressBar: true,
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    }).then(() => {
                        // Reset form
                        $('#escortForm')[0].reset();
                        $('.file-input-display').removeClass('has-file');
                        $('.file-text').text('Klik untuk memilih foto atau drag & drop');
                        
                        // Clear form validation states
                        $('.form-control, .form-select').removeClass('is-valid is-invalid');
                        
                        // Reset image variables
                        selectedImageBase64 = null;
                        selectedImageInfo = null;
                        
                        // Focus first input for next entry
                        $('#kategori_pengantar').focus();
                    });
                    
                    // Optional: Show submission statistics
                    console.log('Submission Statistics:', {
                        total_submissions: submissionCount + 1,
                        session_id: response.session_id,
                        api_submissions_count: response.meta ? response.meta.api_submissions_count : null,
                        image_encoded_size: formData.foto_pengantar_base64.length
                    });
                },
                error: function (xhr) {
                    submitBtn.removeClass('loading').prop('disabled', false);
                    
                    // Store error details
                    const submissionError = {
                        timestamp: new Date().toISOString(),
                        status: xhr.status,
                        response: xhr.responseJSON,
                        submission_id: xhr.responseJSON ? xhr.responseJSON.submission_id : null
                    };
                    localStorage.setItem('lastSubmissionError', JSON.stringify(submissionError));
                    
                    let errorMessage = 'Terjadi kesalahan saat memproses data';
                    let errorDetails = '';
                    
                    if (xhr.status === 422) {
                        // Validation errors
                        const errors = xhr.responseJSON.errors;
                        let errorList = '';
                        for (let field in errors) {
                            errorList += `• ${errors[field].join(', ')}\n`;
                        }
                        errorMessage = 'Validasi data gagal';
                        errorDetails = errorList;
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        html: `
                            <div>
                                <p>${errorMessage}</p>
                                ${errorDetails ? `<div class="mt-2"><small>${errorDetails.replace(/\n/g, '<br>')}</small></div>` : ''}
                                ${xhr.responseJSON && xhr.responseJSON.submission_id ? 
                                    `<div class="mt-2"><small class="text-muted">ID Error: ${xhr.responseJSON.submission_id}</small></div>` : ''}
                            </div>
                        `,
                        confirmButtonText: 'Coba Lagi',
                        confirmButtonColor: '#dc3545',
                        showClass: {
                            popup: 'animate__animated animate__shakeX'
                        }
                    });
                    
                    // Highlight validation errors
                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        Object.keys(xhr.responseJSON.errors).forEach(field => {
                            $(`[name="${field}"]`).addClass('is-invalid');
                        });
                    }
                }
            });
        });

        // Add session stats display (for debugging)
        function showSessionStats() {
            $.get('/api/session-stats')
                .done(function(response) {
                    console.log('Session Statistics:', response);
                })
                .fail(function() {
                    console.log('Could not retrieve session stats');
                });
        }

        // Auto-check session stats every 30 seconds (optional)
        if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
            setInterval(showSessionStats, 30000);
        }

        // Real-time validation feedback
        $('input[required], select[required]').on('blur', function() {
            const field = $(this);
            if (!field.val()) {
                field.css('border-color', '#dc3545');
            } else {
                field.css('border-color', '#28a745');
            }
        });

        $('input[required], select[required]').on('input change', function() {
            const field = $(this);
            if (field.val()) {
                field.css('border-color', '#28a745');
            }
        });
    });
</script>
@endpush