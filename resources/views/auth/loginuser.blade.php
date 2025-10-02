<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register - Pendataan IGD</title>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    
    {{-- Font Awesome for Icons --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        /* Menggunakan font Inter */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Animated background particles */
        body::before {
            content: '';
            position: fixed;
            width: 200%;
            height: 200%;
            top: -50%;
            left: -50%;
            z-index: 0;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 1px, transparent 1px),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 1px, transparent 1px),
                radial-gradient(circle at 40% 20%, rgba(255, 255, 255, 0.08) 2px, transparent 2px);
            background-size: 80px 80px, 120px 120px, 100px 100px;
            animation: drift 60s linear infinite;
        }
        
        @keyframes drift {
            from { transform: translate(0, 0); }
            to { transform: translate(-50px, -50px); }
        }
        
        /* Modern card styling */
        .login-card-custom {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 
                0 1.25rem 3.75rem rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.1) inset;
            position: relative;
            z-index: 1;
            animation: slideUp 0.6s ease-out;
            max-width: 28rem;
            width: 100%;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Logo container with glow effect */
        .logo-container {
            position: relative;
            display: inline-block;
        }
        
        .logo {
            filter: drop-shadow(0 4px 12px rgba(102, 126, 234, 0.4));
            transition: transform 0.3s ease;
            height: 4rem;
            object-fit: contain;
        }
        
        .logo:hover {
            transform: scale(1.05);
        }
        
        /* Modern input group styling */
        .input-group-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            width: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .input-group:focus-within .input-group-text {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .form-control {
            border-left: none;
            border: 2px solid #e5e7eb;
            background: #f9fafb;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.1);
        }
        
        /* Modern button styling */
        .btn-modern {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            padding: 0.875rem 1rem;
            font-weight: 600;
        }
        
        .btn-modern::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-modern:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-modern:active {
            transform: scale(0.98);
        }
        
        .btn-modern span {
            position: relative;
            z-index: 1;
        }
        
        /* Gradient buttons */
        .btn-gradient-purple {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        
        .btn-gradient-purple:hover {
            background: linear-gradient(135deg, #5568d3 0%, #65408b 100%);
            color: white;
        }
        
        .btn-gradient-green {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            border: none;
            color: white;
        }
        
        .btn-gradient-green:hover {
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
            color: white;
        }
        
        /* Loading animation */
        .loading-spinner {
            display: none !important;
        }
        
        .btn.loading .btn-text {
            display: none !important;
        }
        
        .btn.loading .loading-spinner {
            display: flex !important;
        }
        
        .btn.loading {
            pointer-events: none;
            opacity: 0.8;
        }
        
        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Switch link animation */
        .switch-link {
            position: relative;
            text-decoration: none;
            font-weight: 700;
        }
        
        .switch-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: width 0.3s ease;
        }
        
        .switch-link:hover::after {
            width: 100%;
        }
        
        /* Alert animations */
        .alert-animated {
            animation: alertSlide 0.4s ease-out;
            border-left-width: 4px;
        }
        
        @keyframes alertSlide {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-success {
            background: linear-gradient(to right, #d1fae5 0%, #ecfdf5 100%);
            border-left-color: #10b981 !important;
            color: #065f46;
        }
        
        .alert-danger {
            background: linear-gradient(to right, #fee2e2 0%, #fef2f2 100%);
            border-left-color: #ef4444 !important;
            color: #991b1b;
        }
        
        /* Form section transition */
        .form-section {
            animation: fadeIn 0.4s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        /* Checkbox styling */
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        /* Icon badge styling */
        .icon-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            background: linear-gradient(to right, #f3f4f6 0%, #e5e7eb 100%);
            border-radius: 9999px;
        }
        
        /* Label styling */
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .form-label i {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div id="login-card" class="card login-card-custom border-0 rounded-4 overflow-hidden">

                    <!-- Header Form -->
                    <div class="card-header bg-transparent border-0 p-4 p-md-5 text-center">
                        <div class="logo-container mb-3">
                            <img src="{{ asset('Rumah_Sakit_Indriati.webp') }}" 
                                 alt="Logo" 
                                 class="logo mx-auto">
                        </div>
                        <h4 id="card-title" class="h3 fw-bold gradient-text mb-2">
                            Pendataan IGD: Silakan Masuk
                        </h4>
                        <p id="card-subtitle" class="text-muted small mb-0">
                            Sistem Pendataan Pengantar Pasien IGD
                        </p>
                    </div>
                    
                    <div class="card-body p-4 p-md-5 pt-0">

                        <!-- Alert Section -->
                        @if(session('success'))
                            <div class="alert alert-success alert-animated d-flex align-items-center mb-4" role="alert">
                                <i class="fas fa-check-circle fs-5 me-3"></i>
                                <strong>{{ session('success') }}</strong>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-animated d-flex align-items-center mb-4" role="alert">
                                <i class="fas fa-exclamation-triangle fs-5 me-3"></i>
                                <strong>{{ $errors->first() }}</strong>
                            </div>
                        @endif

                        <!-- LOGIN FORM SECTION -->
                        <div id="login-section" class="form-section">
                            <form id="loginForm" method="POST" action="{{ route('loginuser.login') }}">
                                @csrf
                                
                                <!-- Input Nama Lengkap (ID Pengguna) -->
                                <div class="mb-4">
                                    <label for="login-name" class="form-label">
                                        <i class="fas fa-user me-2"></i>Nama Lengkap
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text rounded-start-3">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control rounded-end-3" 
                                               id="login-name" 
                                               name="name" 
                                               value="{{ old('name') }}"
                                               placeholder="Masukkan nama lengkap Anda" 
                                               required>
                                    </div>
                                    @error('name')
                                        <div class="text-danger small mt-2">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Input Password (Nomor HP) -->
                                <div class="mb-4">
                                    <label for="login-password" class="form-label">
                                        <i class="fas fa-lock me-2"></i>Password (Nomor HP)
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text rounded-start-3">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control rounded-end-3" 
                                               id="login-password" 
                                               name="password" 
                                               placeholder="Masukkan nomor HP Anda" 
                                               required>
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-2">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Checkbox Remember Me -->
                                <div class="form-check mb-4">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label fw-medium" for="remember">
                                        <i class="fas fa-bookmark me-1 small"></i>Ingat saya
                                    </label>
                                </div>

                                <!-- Tombol Submit -->
                                <button type="submit" class="btn btn-modern btn-gradient-purple w-100 rounded-3 shadow-lg" id="loginBtn">
                                    <span class="btn-text d-flex align-items-center justify-content-center">
                                        <i class="fas fa-sign-in-alt me-2"></i> Masuk
                                    </span>
                                    <span class="loading-spinner d-flex align-items-center justify-content-center">
                                        <i class="fas fa-spinner fa-spin me-2"></i> Memproses...
                                    </span>
                                </button>
                            </form>
                        </div>

                        <!-- REGISTRATION FORM SECTION -->
                        <div id="register-section" class="form-section d-none">
                            <form id="registerForm" method="POST" action="{{ route('loginuser.register') }}">
                                @csrf
                                
                                <!-- Input Nama -->
                                <div class="mb-4">
                                    <label for="register-name" class="form-label">
                                        <i class="fas fa-user me-2"></i>Nama Lengkap
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text rounded-start-3">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control rounded-end-3" 
                                               id="register-name" 
                                               name="name" 
                                               value="{{ old('name') }}"
                                               placeholder="Contoh: Budi Santoso" 
                                               required>
                                    </div>
                                    @error('name')
                                        <div class="text-danger small mt-2">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Input Nomor HP (Akan menjadi Password juga) -->
                                <div class="mb-4">
                                    <label for="register-phone" class="form-label">
                                        <i class="fas fa-phone me-2"></i>Nomor HP (Password)
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text rounded-start-3">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        <input type="tel" 
                                               class="form-control rounded-end-3" 
                                               id="register-phone" 
                                               name="phone" 
                                               value="{{ old('phone') }}"
                                               placeholder="Contoh: 081234567890" 
                                               required>
                                    </div>
                                    @error('phone')
                                        <div class="text-danger small mt-2">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Tombol Submit Register -->
                                <button type="submit" class="btn btn-modern btn-gradient-green w-100 rounded-3 shadow-lg" id="registerBtn">
                                    <span class="btn-text d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user-plus me-2"></i> Daftar Akun
                                    </span>
                                    <span class="loading-spinner d-flex align-items-center justify-content-center">
                                        <i class="fas fa-spinner fa-spin me-2"></i> Memproses...
                                    </span>
                                </button>
                            </form>
                        </div>

                        <!-- Switch Link -->
                        <div class="text-center mt-4 small">
                            <span id="switch-text" class="text-muted fw-medium">Belum punya akun?</span>
                            <a id="switch-link" href="#" class="switch-link gradient-text ms-1" onclick="switchMode(event, 'register')">
                                Daftar di sini
                            </a>
                        </div>

                        <!-- Informasi Sistem -->
                        <div class="mt-4 pt-4 border-top text-center">
                            <div class="icon-badge d-inline-flex">
                                <i class="fas fa-hospital me-2" style="color: #667eea;"></i>
                                <span class="small text-muted fw-medium">Rumah Sakit Indriati © 2025</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS Bundle (includes Popper) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    
    <script>
        // Fungsi untuk beralih antara mode Login dan Register
        function switchMode(event, mode) {
            event.preventDefault(); // Mencegah link pindah halaman
            const loginSection = document.getElementById('login-section');
            const registerSection = document.getElementById('register-section');
            const switchText = document.getElementById('switch-text');
            const switchLink = document.getElementById('switch-link');
            const cardTitle = document.getElementById('card-title');

            if (mode === 'register') {
                loginSection.classList.add('d-none');
                registerSection.classList.remove('d-none');
                cardTitle.textContent = 'Registrasi Akun Baru';
                switchText.textContent = 'Sudah punya akun?';
                switchLink.textContent = 'Masuk di sini.';
                switchLink.onclick = (e) => switchMode(e, 'login');
            } else { // mode === 'login'
                loginSection.classList.remove('d-none');
                registerSection.classList.add('d-none');
                cardTitle.textContent = 'Pendataan IGD: Silakan Masuk';
                switchText.textContent = 'Belum punya akun?';
                switchLink.textContent = 'Daftar di sini.';
                switchLink.onclick = (e) => switchMode(e, 'register');
            }
        }

        // Inisialisasi: set mode default ke 'login' saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            // Inisialisasi tombol switch
            document.getElementById('switch-link').onclick = (e) => switchMode(e, 'register');
            
            // Handle form submissions with loading animation
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const loginBtn = document.getElementById('loginBtn');
            const registerBtn = document.getElementById('registerBtn');
            
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    // Add loading class to button
                    loginBtn.classList.add('loading');
                    loginBtn.disabled = true;
                });
            }
            
            if (registerForm) {
                registerForm.addEventListener('submit', function(e) {
                    // Add loading class to button
                    registerBtn.classList.add('loading');
                    registerBtn.disabled = true;
                });
            }
        });
    </script>
</body>
</html>
