<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register - Pendataan IGD</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        /* Menggunakan font Inter */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; /* light gray background */
        }
        /* Custom styling untuk card border kiri biru seperti contoh Laravel blade */
        .login-card-custom {
            border-left: 5px solid #4e73df; /* blue-500 equivalent */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
        }
        .form-input-group {
            display: flex;
            align-items: center;
        }
        .input-group-text {
            background-color: #e5e7eb; /* gray-200 */
            border-top-left-radius: 0.5rem;
            border-bottom-left-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db; /* gray-300 */
            border-right: none;
        }
        .form-control {
            border-left: none;
            flex-grow: 1;
        }
        .loading-spinner {
            display: none;
        }
        .btn.loading .btn-text {
            display: none;
        }
        .btn.loading .loading-spinner {
            display: inline-block;
        }
        /* Custom focus state */
        .form-control:focus {
            outline: none;
            border-color: #4e73df;
            box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.25);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md">
        <div id="login-card" class="bg-white rounded-xl overflow-hidden login-card-custom transition-all duration-300">

            <!-- Header Form -->
            <div class="p-6 border-b border-gray-200 text-center">
                <img src="{{ asset('Rumah_Sakit_Indriati.webp') }}" 
                     alt="Logo" 
                     class="logo mx-auto mb-3 h-10 object-contain rounded-lg">
                <h4 id="card-title" class="text-xl font-bold text-gray-800 mb-1">
                    Pendataan IGD: Silakan Masuk
                </h4>
                <p id="card-subtitle" class="text-sm text-gray-500">
                    Sistem Pendataan Pengantar Pasien IGD
                </p>
            </div>
            
            <div class="p-6">

                <!-- Alert Section -->
                @if(session('success'))
                    <div class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                        <i class="fas fa-check-circle mr-2"></i>
                        <strong>{{ session('success') }}</strong>
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>{{ $errors->first() }}</strong>
                    </div>
                @endif

                <!-- LOGIN FORM SECTION -->
                <div id="login-section" class="space-y-4">
                    <form id="loginForm" method="POST" action="{{ route('loginuser.login') }}">
                        @csrf
                        
                        <!-- Input Nama Lengkap (ID Pengguna) -->
                        <div>
                            <label for="login-name" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-user mr-1"></i> Nama Lengkap (ID Pengguna)
                            </label>
                            <div class="form-input-group">
                                <div class="input-group-text">
                                    <i class="fas fa-user text-gray-500"></i>
                                </div>
                                <input type="text" 
                                       class="form-control w-full px-3 py-2 border border-gray-300 rounded-r-lg focus:ring-blue-500 focus:border-blue-500" 
                                       id="login-name" 
                                       name="name" 
                                       value="{{ old('name') }}"
                                       placeholder="Masukkan Nama Lengkap Anda" 
                                       required>
                            </div>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Input Password (Nomor HP) -->
                        <div>
                            <label for="login-password" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-lock mr-1"></i> Password
                            </label>
                            <div class="form-input-group">
                                <div class="input-group-text">
                                    <i class="fas fa-lock text-gray-500"></i>
                                </div>
                                <input type="password" 
                                       class="form-control w-full px-3 py-2 border border-gray-300 rounded-r-lg focus:ring-blue-500 focus:border-blue-500" 
                                       id="login-password" 
                                       name="password" 
                                       placeholder="Masukkan Nomor HP Anda sebagai password" 
                                       required>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Checkbox Remember Me -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded" id="remember" name="remember">
                                <label class="ml-2 block text-sm text-gray-900" for="remember">
                                    Ingat saya
                                </label>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit" class="btn w-full flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-150 ease-in-out" id="loginBtn">
                            <span class="btn-text">
                                <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                            </span>
                            <span class="loading-spinner">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Memproses...
                            </span>
                        </button>
                    </form>
                </div>

                <!-- REGISTRATION FORM SECTION -->
                <div id="register-section" class="space-y-4 hidden">
                    <form id="registerForm" method="POST" action="{{ route('loginuser.register') }}">
                        @csrf
                        
                        <!-- Input Nama -->
                        <div>
                            <label for="register-name" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-user mr-1"></i> Nama Lengkap (ID Pengguna)
                            </label>
                            <div class="form-input-group">
                                <div class="input-group-text">
                                    <i class="fas fa-user text-gray-500"></i>
                                </div>
                                <input type="text" 
                                       class="form-control w-full px-3 py-2 border border-gray-300 rounded-r-lg focus:ring-blue-500 focus:border-blue-500" 
                                       id="register-name" 
                                       name="name" 
                                       value="{{ old('name') }}"
                                       placeholder="Cth: Budi Santoso" 
                                       required>
                            </div>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Input Nomor HP (Akan menjadi Password juga) -->
                        <div>
                            <label for="register-phone" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-phone mr-1"></i> Nomor HP (Password)
                            </label>
                            <div class="form-input-group">
                                <div class="input-group-text">
                                    <i class="fas fa-phone text-gray-500"></i>
                                </div>
                                <input type="tel" 
                                       class="form-control w-full px-3 py-2 border border-gray-300 rounded-r-lg focus:ring-blue-500 focus:border-blue-500" 
                                       id="register-phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}"
                                       placeholder="Cth: 081234567890" 
                                       required>
                            </div>
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tombol Submit Register -->
                        <button type="submit" class="btn w-full flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-150 ease-in-out" id="registerBtn">
                            <span class="btn-text">
                                <i class="fas fa-user-plus mr-2"></i> Daftar Akun
                            </span>
                            <span class="loading-spinner">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Memproses...
                            </span>
                        </button>
                    </form>
                </div>

                <!-- Switch Link -->
                <div class="text-center mt-6 text-sm">
                    <span id="switch-text" class="text-gray-600">Belum punya akun?</span>
                    <a id="switch-link" href="#" class="text-blue-600 hover:text-blue-800 font-medium ml-1" onclick="switchMode(event, 'register')">
                        Daftar di sini.
                    </a>
                </div>

                <!-- Informasi Sistem -->
                <div class="mt-6 pt-4 border-t border-gray-200 text-center text-xs text-gray-500">
                    <h6><i class="fas fa-info-circle mr-1"></i> Informasi Sistem</h6>
                    <p>Sistem Pendataan Pengantar Pasien IGD<br>Rumah Sakit Indrianti - 2024</p>
                </div>

            </div>
        </div>
    </div>

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
                loginSection.classList.add('hidden');
                registerSection.classList.remove('hidden');
                cardTitle.textContent = 'Registrasi Akun Baru';
                switchText.textContent = 'Sudah punya akun?';
                switchLink.textContent = 'Masuk di sini.';
                switchLink.onclick = (e) => switchMode(e, 'login');
            } else { // mode === 'login'
                loginSection.classList.remove('hidden');
                registerSection.classList.add('hidden');
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
        });
    </script>
</body>
</html>
