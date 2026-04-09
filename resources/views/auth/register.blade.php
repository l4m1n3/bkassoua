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
                        <img src="{{ asset('public/assets/img/logo_nav.png') }}" alt="Bkassoua" @class(['logo'])>
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

<form class="register-form" method="POST" action="{{ route('register.store') }}">
    @csrf

    <!-- Nom -->
    <div class="form-group">
        <label for="name">Nom complet</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror"
               id="name" name="name" value="{{ old('name') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
<input type="hidden" name="type" value="register">
    <!-- Email -->
    <div class="form-group">
        <label for="email">Adresse email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror"
               id="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
 
    <!-- Phone -->
    <div class="form-group">
        <label for="phone_number">Téléphone</label>
        <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
               id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
        @error('phone_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Adresse -->
    <div class="form-group">
        <label for="address">Adresse</label>
        <input type="text" class="form-control @error('address') is-invalid @enderror"
               id="address" name="address" value="{{ old('address') }}" required>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror"
               id="password" name="password" required>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Confirmation -->
    <div class="form-group">
        <label for="password_confirmation">Confirmer mot de passe</label>
        <input type="password" class="form-control" name="password_confirmation" required>
    </div>

    <!-- CGU -->
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="terms" required>
        <label class="form-check-label">J'accepte les conditions générales</label>
    </div>

    <button type="submit" class="btn-register">
        <i class="bi bi-person-plus me-2"></i>Créer mon compte
    </button>
</form>

        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registerForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');
    const alertContainer = document.getElementById('alertContainer');
    const otpSection = document.getElementById('otpSection');
    const otpInput = document.getElementById('otp');
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const phoneInput = document.getElementById('phone_number');

    // Formatage du numéro de téléphone (8 chiffres)
    phoneInput.addEventListener('input', function (e) {
        let v = e.target.value.replace(/\D/g, '').substring(0, 8);
        if (v.length > 0) {
            v = v.match(/.{1,2}/g).join('');
        }
        e.target.value = v;
    });
});
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>