@extends('layout.app')

@section('title', 'Login - Pendataan IGD')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    /* Override layout constraints for login page */
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }

    /* Full screen centered container */
    .login-page {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
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
    .border-left-primary {
        border-left: 4px solid #4e73df !important;
    }
    
    .login-container {
        max-width: 420px;
        width: 90%;
        margin: 0 auto;
    }

    .login-card {
        background: #fff;
        border-radius: 0.35rem;
        box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.25);
        border: 1px solid #e3e6f0;
        overflow: hidden;
        transform: translateY(-20px);
        animation: slideDown 0.6s ease-out forwards;
    }

    @keyframes slideDown {
        to {
            transform: translateY(0);
        }
    }

    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        padding: 1.5rem 1.25rem;
        text-align: center;
    }

    .card-body {
        padding: 2rem 1.25rem;
    }

    .login-title {
        color: #5a5c69;
        font-weight: 600;
        font-size: 1.5rem;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .login-subtitle {
        color: #858796;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        margin-bottom: 0;
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

    .form-control {
        display: block;
        width: 100%;
        padding: 0.75rem;
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

    .form-control:focus {
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
        padding: 0.75rem;
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
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.35rem;
        border: 1px solid transparent;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        user-select: none;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        width: 100%;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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

    .form-check {
        display: block;
        min-height: 1.5rem;
        padding-left: 1.5rem;
        margin-bottom: 0.125rem;
    }

    .form-check-input {
        width: 1rem;
        height: 1rem;
        margin-top: 0.25rem;
        margin-left: -1.5rem;
        vertical-align: top;
        background-color: #fff;
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;
        border: 1px solid #d1d3e2;
        border-radius: 0.25rem;
    }

    .form-check-input:checked {
        background-color: #4e73df;
        border-color: #4e73df;
    }

    .form-check-label {
        color: #5a5c69;
        font-size: 0.875rem;
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

    .alert {
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 0.35rem;
        font-size: 0.875rem;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .system-info {
        text-align: center;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e3e6f0;
    }

    .system-info h6 {
        color: #5a5c69;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .system-info p {
        color: #858796;
        font-size: 0.75rem;
        margin: 0;
    }
</style>
@endpush

@section('content')
<div class="login-page">
    <div class="login-container">
        <div class="login-card">
            <div class="card-header border-left-primary">
                <h4 class="login-title">
                    <i class="fas fa-hospital"></i>
                    Pendataan IGD
                </h4>
                <p class="login-subtitle">Silakan masuk untuk melanjutkan</p>
            </div>
            
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Login gagal!</strong>
                        @foreach($errors->all() as $error)
                            <br>{{ $error }}
                        @endforeach
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ session('error') }}
                    </div>
                @endif

            <form method="POST" action="{{ route('login.process') }}" id="loginForm">
                @csrf
                
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <div class="input-group">
                        <div class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="Masukkan email Anda" 
                               required 
                               autofocus>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <div class="input-group">
                        <div class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Masukkan password Anda" 
                               required>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Ingat saya
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" id="loginBtn">
                    <span class="btn-text">
                        <i class="fas fa-sign-in-alt"></i> Masuk
                    </span>
                    <span class="loading-spinner">
                        <i class="fas fa-spinner fa-spin"></i> Memproses...
                    </span>
                </button>
            </form>

            <div class="system-info">
                <h6><i class="fas fa-info-circle"></i> Informasi Sistem</h6>
                <p>Sistem Pendataan IGD - {{ date('Y') }}</p>
                <p>Untuk bantuan teknis, hubungi administrator sistem</p>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form submission handling
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $('#loginBtn');
        const form = this;
        
        // Show loading state
        submitBtn.addClass('loading').prop('disabled', true);
        
        // Submit form after a brief delay to show loading state
        setTimeout(function() {
            form.submit();
        }, 500);
    });

    // Real-time validation feedback
    $('input[required]').on('blur', function() {
        const field = $(this);
        const value = field.val().trim();
        
        if (!value) {
            field.addClass('is-invalid');
        } else {
            field.removeClass('is-invalid');
        }
    });

    // Email validation
    $('#email').on('input', function() {
        const email = $(this).val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            $(this).addClass('is-invalid');
        } else if (email) {
            $(this).removeClass('is-invalid');
        }
    });

    // Auto-focus first input if no autofocus
    if (!$('[autofocus]').length) {
        $('#email').focus();
    }
});
</script>
@endpush
