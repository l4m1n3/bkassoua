<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inscription - Bkassoua</title>
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
            --warning: #ffc107;
            --danger: #dc3545;
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

        .register-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .register-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            min-height: 700px;
        }

        .register-hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .register-hero::before {
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

        .register-form-section {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
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

        .register-form {
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

        .otp-section {
            background: var(--primary-light);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-left: 4px solid var(--primary);
        }

        .otp-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .otp-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0;
        }

        .btn-otp {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-otp:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-otp:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .otp-status {
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: block;
        }

        .text-success {
            color: var(--success);
        }

        .text-warning {
            color: var(--warning);
        }

        .text-danger {
            color: var(--danger);
        }

        .btn-register {
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

        .btn-register:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(23, 128, 214, 0.3);
        }

        .btn-register:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-register:active {
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

        .login-link {
            text-align: center;
            margin-top: 1rem;
        }

        .login-link p {
            color: var(--gray);
            margin-bottom: 0.5rem;
        }

        .btn-login {
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

        .btn-login:hover {
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

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
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

        /* Password strength indicator */
        .password-strength {
            margin-top: 0.5rem;
        }

        .strength-bar {
            height: 4px;
            background: var(--gray-light);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 0.25rem;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: var(--transition);
            border-radius: 2px;
        }

        .strength-text {
            font-size: 0.75rem;
            color: var(--gray);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .register-card {
                flex-direction: column;
                min-height: auto;
            }
            
            .register-hero {
                padding: 2rem;
                text-align: center;
            }
            
            .register-form-section {
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
            
            .register-hero,
            .register-form-section {
                padding: 1.5rem;
            }
            
            .form-title {
                font-size: 1.75rem;
            }
            
            .hero-title {
                font-size: 1.75rem;
            }
            
            .otp-section {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <div @class(['register-container'])>
        <div @class(['register-card', 'd-flex'])>
            <!-- Section Hero (côté gauche) -->
            <div @class(['col-lg-6', 'register-hero'])>
                <div @class(['hero-content'])>
                    <div @class(['logo-container'])>
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Bkassoua" @class(['logo'])>
                    </div>
                    <h1 @class(['hero-title'])>Rejoignez Bkassoua</h1>
                    <p @class(['hero-subtitle'])>
                        Créez votre compte et découvrez un univers de mode unique. 
                        Profitez d'une expérience shopping exceptionnelle avec des avantages exclusifs.
                    </p>
                    
                    <ul @class(['features-list'])>
                        <li @class(['fade-in'])>
                            <i @class(['bi', 'bi-lightning'])></i>
                            Inscription rapide et sécurisée
                        </li>
                        <li @class(['fade-in']) style="animation-delay: 0.1s;">
                            <i @class(['bi', 'bi-truck'])></i>
                            Livraison gratuite dès 50,000 fcfa
                        </li>
                        <li @class(['fade-in']) style="animation-delay: 0.2s;">
                            <i @class(['bi', 'bi-percent'])></i>
                            Offres exclusives et promotions
                        </li>
                        <li @class(['fade-in']) style="animation-delay: 0.3s;">
                            <i @class(['bi', 'bi-shield-check'])></i>
                            Protection de vos données personnelles
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Section Formulaire (côté droit) -->
            <div @class(['col-lg-6', 'register-form-section'])>
                <div @class(['form-header'])>
                    <h2 @class(['form-title'])>Créer un compte</h2>
                    <p @class(['form-subtitle'])>Rejoignez notre communauté en quelques étapes</p>
                </div>

                <!-- Alertes -->
                @if(session('success'))
                    <div @class(['alert', 'alert-success', 'fade-in'])>
                        <i @class(['bi', 'bi-check-circle-fill', 'me-2'])></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div @class(['alert', 'alert-danger', 'fade-in'])>
                        <i @class(['bi', 'bi-exclamation-triangle-fill', 'me-2'])></i>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form @class(['register-form']) method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf
                    
                    <div @class(['row'])>
                        <div @class(['col-md-12'])>
                            <div @class(['form-group', 'fade-in'])>
                                <label for="name" @class(['form-label'])>
                                    <i @class(['bi', 'bi-person', 'me-1'])></i>
                                    Nom complet
                                </label>
                                <input type="text" 
                                       @class(['form-control', '@error('name')', 'is-invalid', '@enderror']) 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}"
                                       placeholder="Votre nom et prénom" 
                                       required>
                                @error('name')
                                    <div @class(['invalid-feedback'])>
                                        <i @class(['bi', 'bi-exclamation-circle', 'me-1'])></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div @class(['row'])>
                        <div @class(['col-md-6'])>
                            <div @class(['form-group', 'fade-in']) style="animation-delay: 0.1s;">
                                <label for="email" @class(['form-label'])>
                                    <i @class(['bi', 'bi-envelope', 'me-1'])></i>
                                    Adresse email
                                </label>
                                <input type="email" 
                                       @class(['form-control', '@error('email')', 'is-invalid', '@enderror']) 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       placeholder="votre@email.com" 
                                       required>
                                @error('email')
                                    <div @class(['invalid-feedback'])>
                                        <i @class(['bi', 'bi-exclamation-circle', 'me-1'])></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div @class(['col-md-6'])>
                            <div @class(['form-group', 'fade-in']) style="animation-delay: 0.1s;">
                                <label for="phone_number" @class(['form-label'])>
                                    <i @class(['bi', 'bi-phone', 'me-1'])></i>
                                    Numéro de téléphone
                                </label>
                                <input type="text" 
                                       @class(['form-control', '@error('phone_number')', 'is-invalid', '@enderror']) 
                                       id="phone_number" 
                                       name="phone_number" 
                                       value="{{ old('phone_number') }}"
                                       placeholder="+227 XX XX XX XX" 
                                       required>
                                @error('phone_number')
                                    <div @class(['invalid-feedback'])>
                                        <i @class(['bi', 'bi-exclamation-circle', 'me-1'])></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div @class(['form-group', 'fade-in']) style="animation-delay: 0.2s;">
                        <label for="address" @class(['form-label'])>
                            <i @class(['bi', 'bi-geo-alt', 'me-1'])></i>
                            Adresse
                        </label>
                        <input type="text" 
                               @class(['form-control', '@error('address')', 'is-invalid', '@enderror']) 
                               id="address" 
                               name="address" 
                               value="{{ old('address') }}"
                               placeholder="Votre adresse complète" 
                               required>
                        @error('address')
                            <div @class(['invalid-feedback'])>
                                <i @class(['bi', 'bi-exclamation-circle', 'me-1'])></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div @class(['row'])>
                        <div @class(['col-md-6'])>
                            <div @class(['form-group', 'fade-in']) style="animation-delay: 0.3s;">
                                <label for="password" @class(['form-label'])>
                                    <i @class(['bi', 'bi-lock', 'me-1'])></i>
                                    Mot de passe
                                </label>
                                <div @class(['position-relative'])>
                                    <input type="password" 
                                           @class(['form-control', '@error('password')', 'is-invalid', '@enderror']) 
                                           id="password" 
                                           name="password" 
                                           placeholder="Votre mot de passe" 
                                           required>
                                    <button type="button" @class(['password-toggle']) onclick="togglePassword('password')">
                                        <i @class(['bi', 'bi-eye']) id="password-toggle-icon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div @class(['invalid-feedback'])>
                                        <i @class(['bi', 'bi-exclamation-circle', 'me-1'])></i>{{ $message }}
                                    </div>
                                @enderror
                                <div @class(['password-strength'])>
                                    <div @class(['strength-bar'])>
                                        <div @class(['strength-fill']) id="password-strength-bar"></div>
                                    </div>
                                    <div @class(['strength-text']) id="password-strength-text">Force du mot de passe</div>
                                </div>
                            </div>
                        </div>
                        <div @class(['col-md-6'])>
                            <div @class(['form-group', 'fade-in']) style="animation-delay: 0.3s;">
                                <label for="password_confirmation" @class(['form-label'])>
                                    <i @class(['bi', 'bi-lock-fill', 'me-1'])></i>
                                    Confirmer le mot de passe
                                </label>
                                <div @class(['position-relative'])>
                                    <input type="password" 
                                           @class(['form-control', '@error('password_confirmation')', 'is-invalid', '@enderror']) 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           placeholder="Confirmer le mot de passe" 
                                           required>
                                    <button type="button" @class(['password-toggle']) onclick="togglePassword('password_confirmation')">
                                        <i @class(['bi', 'bi-eye']) id="password-confirm-toggle-icon"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <div @class(['invalid-feedback'])>
                                        <i @class(['bi', 'bi-exclamation-circle', 'me-1'])></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section OTP (optionnelle) -->
                    <div @class(['otp-section', 'fade-in']) style="animation-delay: 0.4s;" id="otpSection">
                        <div @class(['otp-header'])>
                            <h6 @class(['otp-title'])>Vérification du numéro</h6>
                            <button type="button" @class(['btn-otp']) id="sendOtpBtn">
                                <i @class(['bi', 'bi-send']) id="sendOtpIcon"></i>
                                <span id="sendOtpText">Envoyer le code</span>
                                <span @class(['spinner-border', 'spinner-border-sm', 'd-none']) id="sendOtpSpinner"></span>
                            </button>
                        </div>
                        <div @class(['form-group'])>
                            <input type="text" 
                                   @class(['form-control']) 
                                   id="otp" 
                                   name="otp" 
                                   placeholder="Code de vérification (6 chiffres)"
                                   maxlength="6">
                            <span @class(['otp-status']) id="otpStatus"></span>
                            <input type="hidden" id="otpVerified" name="otp_verified" value="0">
                        </div>
                    </div>

                    <div @class(['form-group', 'fade-in']) style="animation-delay: 0.5s;">
                        <div @class(['form-check'])>
                            <input @class(['form-check-input']) type="checkbox" id="terms" name="terms" required>
                            <label @class(['form-check-label']) for="terms">
                                J'accepte les <a href="#" @class(['text-primary'])>conditions générales</a> 
                                et la <a href="#" @class(['text-primary'])>politique de confidentialité</a>
                            </label>
                        </div>
                    </div>

                    <div @class(['fade-in']) style="animation-delay: 0.6s;">
                        <button type="submit" @class(['btn-register']) id="submitBtn">
                            <i @class(['bi', 'bi-person-plus', 'me-2'])></i>
                            Créer mon compte
                        </button>
                    </div>
                </form>

                <div @class(['divider', 'fade-in']) style="animation-delay: 0.7s;">
                    <span>Déjà membre ?</span>
                </div>

                <div @class(['login-link', 'fade-in']) style="animation-delay: 0.8s;">
                    <p>Connectez-vous à votre compte existant</p>
                    <a href="{{ route('login') }}" @class(['btn-login'])>
                        <i @class(['bi', 'bi-box-arrow-in-right', 'me-2'])></i>
                        Se connecter
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(fieldId + '-toggle-icon');
            
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
            
            // Garde seulement les 8 derniers chiffres
            if (value.length > 8) {
                value = value.slice(-8);
            }

            // Appliquer le format lisible : XX XX XX XX
            let formatted = '';
            if (value.length > 0) {
                formatted = value.match(/.{1,2}/g)?.join(' ') || '';
            }

            // Ajouter l'indicatif +227
            e.target.value = '+227 ' + formatted.trim();
        });

        // Password strength indicator
        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            const strengthBar = document.getElementById('password-strength-bar');
            const strengthText = document.getElementById('password-strength-text');
            
            const strength = calculatePasswordStrength(password);
            
            strengthBar.style.width = strength.percentage + '%';
            strengthBar.style.backgroundColor = strength.color;
            strengthText.textContent = strength.text;
            strengthText.style.color = strength.color;
        });

        function calculatePasswordStrength(password) {
            let score = 0;
            
            // Longueur
            if (password.length >= 8) score += 25;
            if (password.length >= 12) score += 15;
            
            // Complexité
            if (/[a-z]/.test(password)) score += 10;
            if (/[A-Z]/.test(password)) score += 10;
            if (/[0-9]/.test(password)) score += 10;
            if (/[^a-zA-Z0-9]/.test(password)) score += 10;
            
            // Variations
            if (password.length >= 6 && /[a-z]/.test(password) && /[A-Z]/.test(password)) score += 10;
            if (password.length >= 8 && /[0-9]/.test(password)) score += 10;
            if (password.length >= 10 && /[^a-zA-Z0-9]/.test(password)) score += 10;

            score = Math.min(score, 100);

            if (score < 40) {
                return {
                    percentage: score,
                    color: '#dc3545',
                    text: 'Faible'
                };
            } else if (score < 70) {
                return {
                    percentage: score,
                    color: '#ffc107',
                    text: 'Moyen'
                };
            } else {
                return {
                    percentage: score,
                    color: '#28a745',
                    text: 'Fort'
                };
            }
        }

        // OTP functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sendOtpBtn = document.getElementById('sendOtpBtn');
            const sendOtpText = document.getElementById('sendOtpText');
            const sendOtpIcon = document.getElementById('sendOtpIcon');
            const sendOtpSpinner = document.getElementById('sendOtpSpinner');
            const otpStatus = document.getElementById('otpStatus');
            const otpInput = document.getElementById('otp');
            const otpVerifiedInput = document.getElementById('otpVerified');
            const submitBtn = document.getElementById('submitBtn');

            let otpCountdown;
            let remainingTime = 120;

            sendOtpBtn.addEventListener('click', async function() {
                const phoneNumber = document.getElementById('phone_number').value.replace(/\D/g, '');

                if (phoneNumber.length < 8) {
                    otpStatus.textContent = 'Veuillez entrer un numéro de téléphone valide';
                    otpStatus.className = 'otp-status text-danger';
                    return;
                }

                try {
                    sendOtpBtn.disabled = true;
                    sendOtpText.textContent = 'Envoi...';
                    sendOtpIcon.classList.add('d-none');
                    sendOtpSpinner.classList.remove('d-none');
                    otpStatus.textContent = '';

                    // Simuler l'envoi d'OTP (remplacer par votre API)
                    await new Promise(resolve => setTimeout(resolve, 2000));
                    
                    otpStatus.textContent = 'Code envoyé! Vérifiez votre téléphone.';
                    otpStatus.className = 'otp-status text-success';
                    startOtpCountdown();

                } catch (error) {
                    otpStatus.textContent = 'Erreur lors de l\'envoi du code';
                    otpStatus.className = 'otp-status text-danger';
                } finally {
                    sendOtpBtn.disabled = false;
                    sendOtpText.textContent = 'Renvoyer';
                    sendOtpIcon.classList.remove('d-none');
                    sendOtpSpinner.classList.add('d-none');
                }
            });

            // Vérification OTP en temps réel
            otpInput.addEventListener('input', function() {
                const otp = this.value.trim();
                if (otp.length === 6) {
                    // Simuler la vérification
                    setTimeout(() => {
                        if (otp === '123456') { // Code de test
                            otpVerifiedInput.value = '1';
                            otpStatus.textContent = 'Numéro vérifié avec succès!';
                            otpStatus.className = 'otp-status text-success';
                            clearInterval(otpCountdown);
                        } else {
                            otpStatus.textContent = 'Code incorrect. Essayez à nouveau.';
                            otpStatus.className = 'otp-status text-danger';
                        }
                    }, 1000);
                }
            });

            function startOtpCountdown() {
                clearInterval(otpCountdown);
                remainingTime = 120;
                updateCountdownDisplay();

                otpCountdown = setInterval(() => {
                    remainingTime--;
                    updateCountdownDisplay();

                    if (remainingTime <= 0) {
                        clearInterval(otpCountdown);
                        otpStatus.textContent = 'Le code a expiré. Veuillez en demander un nouveau.';
                        otpStatus.className = 'otp-status text-danger';
                        otpVerifiedInput.value = '0';
                    }
                }, 1000);
            }

            function updateCountdownDisplay() {
                const minutes = Math.floor(remainingTime / 60);
                const seconds = remainingTime % 60;
                otpStatus.textContent = `Code valide pour ${minutes}:${seconds.toString().padStart(2, '0')}`;
                otpStatus.className = 'otp-status text-warning';
            }

            // Animation au chargement
            const fadeElements = document.querySelectorAll('.fade-in');
            fadeElements.forEach((element, index) => {
                element.style.animationDelay = (index * 0.1) + 's';
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>