<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Laws of UX Applied */
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Fitts's Law: Larger clickable areas */
        .btn-primary {
            min-height: 48px;
            min-width: 200px;
        }
        
        /* Hick's Law: Minimize choices */
        .login-container {
            max-width: 400px;
        }
        
        /* Miller's Law: Chunking information */
        .section-spacing {
            margin-bottom: 20px;
        }
        
        /* Jakob's Law: Familiar patterns */
        input:focus {
            outline: none;
            border-color: #0056b3;
            box-shadow: 0 0 0 3px rgba(0, 86, 179, 0.1);
        }
        
        /* Von Restorff Effect: Make primary action stand out */
        .google-btn {
            background: linear-gradient(135deg, #4285f4 0%, #34a853 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .google-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(66, 133, 244, 0.3);
        }
        
        /* Serial Position Effect: Important info at top and bottom */
        /* Aesthetic-Usability Effect: Beautiful and functional */
        .card {
            backdrop-filter: blur(24px);
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        /* Progressive Disclosure: Show manual login on demand */
        .manual-login {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .manual-login.show {
            max-height: 400px;
        }
        
        /* Law of Common Region: Group related elements */
        .input-group {
            position: relative;
        }
        
        /* Law of Proximity: Related items close together */
        .form-field + .form-field {
            margin-top: 20px;
        }
        
        /* Feedback visibility (System Status) */
        .input-feedback {
            min-height: 20px;
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <!-- Background Image with Overlay -->
    <div class="fixed inset-0 z-0">
        <!-- Gambar Gedung Politala sebagai Background -->
        <!-- Fallback: Gradient background jika gambar belum ada -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 bg-cover bg-center bg-no-repeat" 
             style="background-image: url('{{ asset('images/gedung-politala.jpg') }}');">
        </div>
        
        <!-- Dark Overlay untuk readability dan contrast -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/85 via-indigo-900/80 to-purple-900/85"></div>
        
        <!-- Animated Pattern Overlay -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>
        
        <!-- Floating Elements untuk aesthetic -->
        <div class="absolute top-20 left-20 w-32 h-32 bg-white/5 rounded-full blur-3xl animate-float-slow"></div>
        <div class="absolute bottom-20 right-20 w-40 h-40 bg-primary-400/10 rounded-full blur-3xl animate-float-slower"></div>
        <div class="absolute top-1/2 left-1/3 w-24 h-24 bg-secondary-400/10 rounded-full blur-2xl animate-float"></div>
    </div>
    
    <!-- Content with higher z-index -->
    <div class="relative z-10 w-full max-w-md">
    
    <!-- Law of Symmetry: Balanced layout -->
    <div class="login-container w-full">
        
        <!-- Card with proper hierarchy (Visual Hierarchy Law) -->
        <div class="card rounded-2xl shadow-2xl p-6 space-y-5 max-h-[90vh] overflow-y-auto">
            
            <!-- Law of Proximity: Logo + Title grouped -->
            <div class="text-center mb-4">
                <!-- Logo (Recognition over Recall) -->
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/logo/politala.png') }}" 
                         alt="Logo Politala" 
                         class="h-16 w-auto object-contain animate-fade-in">
                </div>
                
                <!-- Title (Clear Hierarchy) -->
                <h1 class="text-xl font-bold text-gray-900 mb-1">
                    SMART PBL
                </h1>
                <p class="text-xs text-gray-600">
                    Politeknik Negeri Tanah Laut
                </p>
            </div>

            <!-- Feedback Messages (System Status Visibility) -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            @if(session('error'))
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg animate-slide-in">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                    </svg>
                    <p class="text-sm text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg animate-slide-in">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                    <p class="text-sm text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <!-- Primary Action (Von Restorff Effect + Fitts's Law) -->
            <div class="mb-4">
                <a href="{{ route('auth.google') }}" 
                   class="google-btn w-full flex items-center justify-center gap-3 text-white font-semibold py-3 px-5 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 group">
                    <!-- Google Icon -->
                    <svg class="w-6 h-6 bg-white rounded p-1" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span class="group-hover:translate-x-1 transition-transform duration-300">Login Google</span>
                    <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                
                <!-- Affordance: Show that there's an alternative -->
                <p class="mt-2 text-center text-xs text-gray-500">
                    Login cepat dengan akun Google Politala
                </p>
            </div>

            <!-- Progressive Disclosure: Alternative login -->
            <div class="text-center">
                <button 
                    type="button"
                    onclick="toggleManualLogin()"
                    class="text-sm text-gray-600 hover:text-primary-600 font-medium transition-colors duration-200 flex items-center justify-center gap-2 mx-auto group">
                    <span>Gunakan email & password</span>
                    <svg class="w-4 h-4 transform group-hover:translate-y-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>

            <!-- Manual Login (Hidden by default - Progressive Disclosure) -->
            <div id="manualLogin" class="manual-login">
                <!-- Divider -->
                <div class="relative mb-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="bg-white px-4 text-xs text-gray-500 uppercase tracking-wider">Atau</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email (Law of Common Region) -->
                    <div class="form-field">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Email
                        </label>
                        <div class="input-group">
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                required 
                                autofocus 
                                autocomplete="username"
                                placeholder=""
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 placeholder-gray-400"
                            />
                        </div>
                        <div class="input-feedback text-red-600">
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>
                    </div>

                    <!-- Password (Law of Common Region) -->
                    <div class="form-field">
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-sm font-semibold text-gray-700">
                                Password
                            </label>
                            <a href="/lupa-password" 
                               class="text-xs text-blue-600 hover:text-blue-800 underline font-medium transition-colors duration-200"
                               style="display: inline-block;">
                                Lupa password?
                            </a>
                        </div>
                        <div class="input-group relative">
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full px-4 py-2.5 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 placeholder-gray-400"
                            />
                            <!-- Toggle Show Password Button -->
                            <button 
                                type="button"
                                onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors"
                                title="Tampilkan/Sembunyikan Password">
                                <!-- Eye Icon (Show) -->
                                <svg id="eyeOpen" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <!-- Eye Slash Icon (Hide) - Default -->
                                <svg id="eyeClosed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        <div class="input-feedback text-red-600">
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>
                    </div>

                    <!-- Remember Me (Law of Proximity) -->
                    <div class="flex items-center">
                        <input 
                            id="remember_me" 
                            type="checkbox" 
                            name="remember"
                            class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 focus:ring-2 transition-colors duration-200"
                        />
                        <label for="remember_me" class="ml-2 text-sm text-gray-700 select-none cursor-pointer">
                            Ingat saya
                        </label>
                    </div>

                    <!-- Submit Button (Fitts's Law + Von Restorff Effect) -->
                    <button 
                        type="submit"
                        class="btn-primary w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3.5 px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 group">
                        <span>Masuk</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>
            </div>

        </div>

        <!-- Footer (Serial Position Effect - Remember last thing) -->
        <div class="mt-6 text-center">
            <p class="text-xs text-white/80 font-medium drop-shadow-lg">
                © {{ date('Y') }} Politeknik Negeri Tanah Laut
            </p>
        </div>
    </div>
    
    </div><!-- Close wrapper -->

    <script>
        // Progressive Disclosure
        function toggleManualLogin() {
            const manualLogin = document.getElementById('manualLogin');
            manualLogin.classList.toggle('show');
        }

        // Toggle Show/Hide Password
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            }
        }

        // Keyboard Accessibility (Accessibility Law)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('manualLogin').classList.remove('show');
            }
        });
    </script>

    <style>
        /* Animations (Aesthetic-Usability Effect) */
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slide-in {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out;
        }

        .animate-slide-in {
            animation: slide-in 0.4s ease-out;
        }

        /* Loading state feedback (System Status Visibility) */
        button[type="submit"]:active {
            transform: scale(0.98);
        }

        /* Focus indicators for accessibility */
        *:focus-visible {
            outline: 2px solid #0056b3;
            outline-offset: 2px;
        }
        
        /* Floating Animations untuk background elements */
        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0);
            }
            33% {
                transform: translateY(-20px) translateX(10px);
            }
            66% {
                transform: translateY(10px) translateX(-10px);
            }
        }
        
        @keyframes float-slow {
            0%, 100% {
                transform: translateY(0) translateX(0) scale(1);
            }
            50% {
                transform: translateY(-30px) translateX(20px) scale(1.1);
            }
        }
        
        @keyframes float-slower {
            0%, 100% {
                transform: translateY(0) translateX(0) rotate(0deg);
            }
            50% {
                transform: translateY(20px) translateX(-30px) rotate(180deg);
            }
        }
        
        .animate-float {
            animation: float 8s ease-in-out infinite;
        }
        
        .animate-float-slow {
            animation: float-slow 12s ease-in-out infinite;
        }
        
        .animate-float-slower {
            animation: float-slower 15s ease-in-out infinite;
        }
        
        /* Custom Scrollbar untuk card */
        .card::-webkit-scrollbar {
            width: 6px;
        }
        
        .card::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }
        
        .card::-webkit-scrollbar-thumb {
            background: rgba(99, 102, 241, 0.3);
            border-radius: 10px;
        }
        
        .card::-webkit-scrollbar-thumb:hover {
            background: rgba(99, 102, 241, 0.5);
        }
    </style>
</body>
</html>
