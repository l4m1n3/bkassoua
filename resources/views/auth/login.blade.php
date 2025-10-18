{{-- <x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="phone_number" :value="__('Numero telephone')" />
            <x-text-input id="phone_number" class="block mt-1 w-full" type="number" name="phone_number" :value="old('phone_number')"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Se rappeler') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}">
                    {{ __('Mot de passe oublié?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Connexion') }}
            </x-primary-button>
        </div>
    </form>

    
</x-guest-layout> --}}


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Bkassoua</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1780d6;
            --primary-dark: #1269b3;
            --primary-light: #e3f2fd;
            --secondary: #f4a261;
            --accent: #e76f51;
            --light: #f8f9fa;
            --dark: #264653;
            --gray: #6c757d;
            --gray-light: #e9ecef;
            --success: #28a745;
            --border-radius: 12px;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", sans-serif;
            background: linear-gradient(135deg, var(--primary-light) 0%, #ffffff 50%, var(--primary-light) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .login-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            min-height: 600px;
        }

        .login-hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-50px, -50px) rotate(360deg); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            height: 80px;
            margin-bottom: 1rem;
        }

        .hero-title {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .hero-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin-top: 2rem;
        }

        .features-list li {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        .features-list li i {
            background: rgba(255, 255, 255, 0.2);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .login-form-section {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-title {
            font-weight: 700;
            color: var(--dark);
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: var(--gray);
            font-size: 1rem;
        }

        .login-form {
            max-width: 400px;
            margin: 0 auto;
            width: 100%;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            border: 2px solid var(--gray-light);
            border-radius: var(--border-radius);
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: var(--transition);
            background: var(--light);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(23, 128, 214, 0.1);
            background: white;
        }

        .form-control.is-invalid {
            border-color: var(--accent);
        }

        .invalid-feedback {
            display: block;
            color: var(--accent);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray);
            cursor: pointer;
            transition: var(--transition);
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .form-check {
            display: flex;
            align-items: center;
        }

        .form-check-input {
            margin-right: 0.5rem;
        }

        .form-check-label {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .forgot-password {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .forgot-password:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .btn-login {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            transition: var(--transition);
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(23, 128, 214, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .divider {
            text-align: center;
            margin: 2rem 0;
            position: relative;
            color: var(--gray);
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--gray-light);
        }

        .divider span {
            background: white;
            padding: 0 1rem;
            position: relative;
        }

        .register-link {
            text-align: center;
            margin-top: 2rem;
        }

        .register-link p {
            color: var(--gray);
            margin-bottom: 0.5rem;
        }

        .btn-register {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            border-radius: var(--border-radius);
            padding: 0.75rem 2rem;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-register:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(23, 128, 214, 0.3);
        }

        .alert {
            border-radius: var(--border-radius);
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success);
            border-left: 4px solid var(--success);
        }

        .alert-danger {
            background: rgba(231, 111, 81, 0.1);
            color: var(--accent);
            border-left: 4px solid var(--accent);
        }

        /* Animation pour le fade-in */
        .fade-in {
            animation: fadeInUp 0.6s ease forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .login-card {
                flex-direction: column;
                min-height: auto;
            }
            
            .login-hero {
                padding: 2rem;
                text-align: center;
            }
            
            .login-form-section {
                padding: 2rem;
            }
            
            .hero-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 10px;
            }
            
            .login-hero,
            .login-form-section {
                padding: 1.5rem;
            }
            
            .form-title {
                font-size: 1.75rem;
            }
            
            .hero-title {
                font-size: 1.75rem;
            }
            
            .form-options {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card d-flex">
            <!-- Section Hero (côté gauche) -->
            <div class="col-lg-6 login-hero">
                <div class="hero-content">
                    <div class="logo-container">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Bkassoua" class="logo">
                    </div>
                    <h1 class="hero-title">Bienvenue sur Bkassoua</h1>
                    <p class="hero-subtitle">
                        Rejoignez notre communauté et découvrez les dernières tendances de la mode africaine. 
                        Une expérience shopping unique vous attend.
                    </p>
                    
                    <ul class="features-list">
                        <li class="fade-in">
                            <i class="bi bi-truck"></i>
                            Livraison rapide et sécurisée
                        </li>
                        <li class="fade-in" style="animation-delay: 0.1s;">
                            <i class="bi bi-shield-check"></i>
                            Paiement 100% sécurisé
                        </li>
                        <li class="fade-in" style="animation-delay: 0.2s;">
                            <i class="bi bi-arrow-left-right"></i>
                            Retours faciles sous 30 jours
                        </li>
                        <li class="fade-in" style="animation-delay: 0.3s;">
                            <i class="bi bi-headset"></i>
                            Support client dédié
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Section Formulaire (côté droit) -->
            <div class="col-lg-6 login-form-section">
                <div class="form-header">
                    <h2 class="form-title">Connexion</h2>
                    <p class="form-subtitle">Accédez à votre compte Bkassoua</p>
                </div>

                <!-- Alertes -->
                @if(session('success'))
                    <div class="alert alert-success fade-in">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger fade-in">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form class="login-form" action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    <div class="form-group fade-in">
                        <label for="phone_number" class="form-label">
                            <i class="bi bi-phone me-1"></i>
                            Numéro de téléphone
                        </label>
                        <input type="text" 
                               class="form-control @error('phone_number') is-invalid @enderror" 
                               id="phone_number" 
                               name="phone_number" 
                               value="{{ old('phone_number') }}"
                               placeholder="Exemple: 90 00 00 00" 
                               required>
                        @error('phone_number')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group fade-in" style="animation-delay: 0.1s;">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock me-1"></i>
                            Mot de passe
                        </label>
                        <div class="position-relative">
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Votre mot de passe" 
                                   required>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="bi bi-eye" id="password-toggle-icon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-options fade-in" style="animation-delay: 0.2s;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Se souvenir de moi
                            </label>
                        </div>
                        <a href="{{ route('password.request') }}" class="forgot-password">
                            Mot de passe oublié ?
                        </a>
                    </div>

                    <div class="fade-in" style="animation-delay: 0.3s;">
                        <button type="submit" class="btn-login">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Se connecter
                        </button>
                    </div>
                </form>

                <div class="divider fade-in" style="animation-delay: 0.4s;">
                    <span>Nouveau sur Bkassoua ?</span>
                </div>

                <div class="register-link fade-in" style="animation-delay: 0.5s;">
                    <p>Créez votre compte et commencez votre shopping</p>
                    <a href="{{ route('register') }}" class="btn-register">
                        <i class="bi bi-person-plus me-2"></i>
                        Créer un compte
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }

        // Format phone number input
        document.getElementById('phone_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                value = value.match(/.{1,2}/g).join(' ');
            }
            e.target.value = value;
        });

        // Animation au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const fadeElements = document.querySelectorAll('.fade-in');
            fadeElements.forEach((element, index) => {
                element.style.animationDelay = (index * 0.1) + 's';
            });
        });

        // Validation du formulaire
        document.querySelector('.login-form').addEventListener('submit', function(e) {
            const phoneNumber = document.getElementById('phone_number').value;
            const password = document.getElementById('password').value;
            
            if (!phoneNumber || !password) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires.');
                return false;
            }
            
            // Validation basique du numéro de téléphone (8 chiffres minimum)
            const cleanPhone = phoneNumber.replace(/\s/g, '');
            if (cleanPhone.length < 8) {
                e.preventDefault();
                alert('Veuillez entrer un numéro de téléphone valide.');
                return false;
            }
            
            return true;
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
