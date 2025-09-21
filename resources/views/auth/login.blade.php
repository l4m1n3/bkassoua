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
<html lang="zxx">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Links Of CSS File -->
    <link rel="stylesheet" href="{{ asset('assetss/css/sidebar-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('assetss/css/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assetss/css/apexcharts.css') }}">
    <link rel="stylesheet" href="{{ asset('assetss/css/prism.css') }}">
    <link rel="stylesheet" href="{{ asset('assetss/css/rangeslider.css') }}">
    <link rel="stylesheet" href="{{ asset('assetss/css/sweetalert.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetss/css/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('assetss/css/google-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('assetss/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('assetss/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetss/css/fullcalendar.main.css') }}">
    <link rel="stylesheet" href="{{ asset('assetss/css/style.css') }}">

    <!-- Favicon -->
    <link href="{{ asset('assets/img/logo.png') }}" rel="icon">
    <!-- Title -->
    <title>B Kassoua - Login</title>
</head>

<body class="boxed-size bg-white">
    <!-- Start Preloader Area -->
    <div class="preloader" id="preloader">
        <div class="preloader">
            <div class="waviy position-relative">
                <span class="d-inline-block">B</span>
                <span class="d-inline-block">K</span>
                <span class="d-inline-block">A</span>
                <span class="d-inline-block">S</span>
                <span class="d-inline-block">S</span>
                <span class="d-inline-block">O</span>
                <span class="d-inline-block">U</span>
                <span class="d-inline-block">A</span>
            </div>
        </div>
    </div>
    <!-- End Preloader Area -->

    <!-- Start Main Content Area -->
    <div class="container">
        <div class="main-content d-flex flex-column p-0">
            <div class="m-auto m-1230">
                <div class="row align-items-center">
                    <div class="col-lg-6 d-none d-lg-block">
                        <img src="{{ asset('assets/img/logo.png') }}" class="rounded-3" alt="login">

                    </div>
                    <div class="col-lg-6">
                        <div class="mw-480 ms-lg-auto">
                            <h3 class="fs-28 mb-2">Bienvenue sur B Kassoua!</h3>
                            <form action="{{ route('login') }}" method="POST">
                                @csrf
                                <div class="form-floating mb-3 mt-3">
                                    <input type="text" class="form-control" name="phone_number" id="email"
                                        placeholder="Exemple 90 00 00 00" name="phone_number">
                                    <label for="email">Numero Telephone</label>
                                </div>
                                <div class="form-floating mt-3 mb-3">
                                    <input type="text" class="form-control" name="password" id="pwd"
                                        placeholder="Enter password" name="password">
                                    <label for="pwd">Password</label>
                                </div>
                                <div class="form-floating mb-3 mt-3">
                                    <a href="forget-password.html"
                                        class="text-decoration-none text-primary fw-semibold">Forgot Password?</a>
                                </div>
                                <div class="form-floating mb-3 mt-3">

                                    <button type="submit" class="btn btn-primary fw-medium py-2 px-3 w-100">
                                        <div class="d-flex align-items-center justify-content-center py-1">
                                            <i class="material-symbols-outlined text-white fs-20 me-2">Login</i>
                                            <span>Connecter</span>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <p>Don’t have an account. <a href="{{ route('register') }}"
                                            class="fw-medium text-primary text-decoration-none">Register</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Start Main Content Area -->

    <button class="theme-settings-btn p-0 border-0 bg-transparent position-absolute" style="right: 30px; bottom: 30px;"
        type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling"
        aria-controls="offcanvasScrolling">
        <i class="material-symbols-outlined bg-primary wh-35 lh-35 text-white rounded-1" data-bs-toggle="tooltip"
            data-bs-placement="left" data-bs-title="Click On Theme Settings">settings</i>
    </button>

    <!-- Start Theme Setting Area -->
    <div class="offcanvas offcanvas-end bg-white" data-bs-scroll="true" data-bs-backdrop="true" tabindex="-1"
        id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel"
        style="box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;">
        <div class="offcanvas-header bg-body-bg py-3 px-4">
            <h5 class="offcanvas-title fs-18" id="offcanvasScrollingLabel">Theme Settings</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <div class="mb-4 pb-2">
                <h4 class="fs-15 fw-semibold border-bottom pb-2 mb-3">RTL / LTR</h4>
                <div class="settings-btn rtl-btn">
                    <label id="switch" class="switch">
                        <input type="checkbox" onchange="toggleTheme()" id="slider">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
            <div class="mb-4 pb-2">
                <h4 class="fs-15 fw-semibold border-bottom pb-2 mb-3">Container Style Fluid / Boxed</h4>
                <button class="boxed-style settings-btn fluid-boxed-btn" id="boxed-style">
                    Click To <span class="fluid">Fluid</span> <span class="boxed">Boxed</span>
                </button>
            </div>
            <div class="mb-4 pb-2">
                <h4 class="fs-15 fw-semibold border-bottom pb-2 mb-3">Only Sidebar Light / Dark</h4>
                <button class="sidebar-light-dark settings-btn sidebar-dark-btn" id="sidebar-light-dark">
                    Click To <span class="dark1">Dark</span> <span class="light1">Light</span>
                </button>
            </div>
            <div class="mb-4 pb-2">
                <h4 class="fs-15 fw-semibold border-bottom pb-2 mb-3">Only Header Light / Dark</h4>
                <button class="header-light-dark settings-btn header-dark-btn" id="header-light-dark">
                    Click To <span class="dark2">Dark</span> <span class="light2">Light</span>
                </button>
            </div>
            <div class="mb-4 pb-2">
                <h4 class="fs-15 fw-semibold border-bottom pb-2 mb-3">Only Footer Light / Dark</h4>
                <button class="footer-light-dark settings-btn footer-dark-btn" id="footer-light-dark">
                    Click To <span class="dark3">Dark</span> <span class="light3">Light</span>
                </button>
            </div>
            <div class="mb-4 pb-2">
                <h4 class="fs-15 fw-semibold border-bottom pb-2 mb-3">Card Style Radius / Square</h4>
                <button class="card-radius-square settings-btn card-style-btn" id="card-radius-square">
                    Click To <span class="square">Square</span> <span class="radius">Radius</span>
                </button>
            </div>
            <div class="mb-4 pb-2">
                <h4 class="fs-15 fw-semibold border-bottom pb-2 mb-3">Card Style BG White / Gray</h4>
                <button class="card-bg settings-btn card-bg-style-btn" id="card-bg">
                    Click To <span class="white">White</span> <span class="gray">Gray</span>
                </button>
            </div>
        </div>
    </div>
    <!-- End Theme Setting Area -->

    <button class="switch-toggle settings-btn dark-btn p-0 bg-transparent position-absolute top-0 d-none"
        id="switch-toggle">
        <span class="dark"><i class="material-symbols-outlined">light_mode</i></span>
        <span class="light"><i class="material-symbols-outlined">dark_mode</i></span>
    </button>

    <!-- Link Of JS File -->
    <script src="{{ asset('assetss/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assetss/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('assetss/js/dragdrop.js') }}"></script>
    <script src="{{ asset('assetss/js/rangeslider.min.js') }}"></script>
    <script src="{{ asset('assetss/js/sweetalert.js') }}"></script>
    <script src="{{ asset('assetss/js/quill.min.js') }}"></script>
    <script src="{{ asset('assetss/js/data-table.js') }}"></script>
    <script src="{{ asset('assetss/js/prism.js') }}"></script>
    <script src="{{ asset('assetss/js/clipboard.min.js') }}"></script>
    <script src="{{ asset('assetss/js/feather.min.js') }}"></script>
    <script src="{{ asset('assetss/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assetss/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assetss/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assetss/js/fullcalendar.main.js') }}"></script>
    <script src="{{ asset('assetss/js/custom/apexcharts.js') }}"></script>
    <script src="{{ asset('assetss/js/custom/custom.js') }}"></script>
</body>

</html>
{{-- <!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bkassoua - Connexion / Inscription</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #2c6e49;
      --secondary: #f4a261;
      --accent: #e76f51;
      --light: #f8f9fa;
      --dark: #264653;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--light);
    }

    /* Navbar */
    .navbar {
      background-color: var(--primary);
      transition: background-color 0.3s ease;
    }
    .navbar.scrolled {
      background-color: rgba(44, 110, 73, 0.9);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .navbar-brand img {
      height: 50px;
      transition: transform 0.3s;
    }
    .navbar-brand img:hover {
      transform: scale(1.1);
    }
    .nav-link {
      color: white !important;
      font-weight: 400;
      margin: 0 10px;
      transition: color 0.3s;
    }
    .nav-link:hover {
      color: var(--secondary) !important;
    }
    .nav-link.active {
      font-weight: 600;
    }
    .navbar form {
      max-width: 400px;
    }
    .navbar .btn-outline-light {
      border-color: white;
      color: white;
      transition: background-color 0.3s, color 0.3s;
    }
    .navbar .btn-outline-light:hover {
      background-color: var(--secondary);
      color: var(--dark);
    }
    .navbar .bi {
      font-size: 1.5rem;
      color: white;
      transition: color 0.3s;
    }
    .navbar .bi:hover {
      color: var(--secondary);
    }

    /* Auth Section */
    .auth-section {
      background-color: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      max-width: 500px;
      margin: 0 auto;
      margin-top: 100px;
      margin-bottom: 50px;
    }
    .auth-section h2 {
      color: var(--primary);
      font-weight: 600;
      text-align: center;
      margin-bottom: 1.5rem;
    }
    .auth-toggle {
      display: flex;
      justify-content: center;
      margin-bottom: 1.5rem;
    }
    .auth-toggle button {
      flex: 1;
      padding: 10px;
      font-weight: 500;
      border: none;
      background-color: var(--light);
      color: var(--dark);
      transition: background-color 0.3s, color 0.3s;
    }
    .auth-toggle button.active {
      background-color: var(--secondary);
      color: white;
    }
    .auth-toggle button:hover {
      background-color: var(--accent);
      color: white;
    }
    .auth-form {
      display: none;
    }
    .auth-form.active {
      display: block;
    }
    .auth-form .form-control {
      border-radius: 5px;
      transition: border-color 0.3s;
    }
    .auth-form .form-control:focus {
      border-color: var(--accent);
      box-shadow: 0 0 5px rgba(231, 111, 81, 0.3);
    }
    .auth-form .btn-primary {
      background-color: var(--secondary);
      border: none;
      width: 100%;
      padding: 10px;
      font-weight: 500;
      transition: background-color 0.3s, transform 0.3s;
    }
    .auth-form .btn-primary:hover {
      background-color: var(--accent);
      transform: translateY(-2px);
    }
    .auth-form .error {
      color: var(--accent);
      font-size: 0.9rem;
      display: none;
      margin-top: 0.25rem;
    }
    .auth-form .form-check {
      margin-top: 1rem;
    }
    .auth-form .form-check-label {
      color: var(--dark);
    }
    .auth-form a {
      color: var(--primary);
      text-decoration: none;
      transition: color 0.3s;
    }
    .auth-form a:hover {
      color: var(--accent);
    }

    /* Footer */
    footer {
      background-color: var(--dark);
      padding: 3rem 0;
    }
    footer a {
      color: var(--secondary);
      text-decoration: none;
      transition: color 0.3s;
    }
    footer a:hover {
      color: var(--accent);
    }
    .social-icons .bi {
      font-size: 1.5rem;
      margin: 0 10px;
      color: white;
      transition: color 0.3s;
    }
    .social-icons .bi:hover {
      color: var(--secondary);
    }
    .newsletter-form input {
      border-radius: 5px 0 0 5px;
      border: none;
    }
    .newsletter-form button {
      border-radius: 0 5px 5px 0;
      background-color: var(--secondary);
      border: none;
      transition: background-color 0.3s;
    }
    .newsletter-form button:hover {
      background-color: var(--accent);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .auth-section {
        padding: 20px;
        margin-top: 80px;
      }
      .auth-toggle button {
        font-size: 0.9rem;
        padding: 8px;
      }
      .navbar form {
        margin: 10px 0;
      }
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  {{-- <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
      <a class="navbar-brand" href="index.html"><img src="https://via.placeholder.com/100x40" alt="Bkassoua"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link" href="index.html">Accueil</a></li>
          <li class="nav-item"><a class="nav-link" href="products.html">Boutique</a></li>
          <li class="nav-item"><a class="nav-link" href="about.html">À Propos</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
        </ul>
        <form class="d-flex flex-grow-1 mx-3">
          <input class="form-control me-2" type="search" placeholder="Rechercher" aria-label="Search">
          <button class="btn btn-outline-light" type="submit">Rechercher</button>
        </form>
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-heart"></i></a></li>
          <li class="nav-item"><a class="nav-link" href="cart.html"><i class="bi bi-cart"></i></a></li>
          <li class="nav-item"><a class="nav-link active" href="auth.html"><i class="bi bi-person"></i></a></li>
        </ul>
      </div>
    </div>
  </nav> --}}

  <!-- Auth Section -->
  <div class="auth-section">
    <h2>Connexion / Inscription</h2>
    <div class="auth-toggle">
      <button class="active" onclick="showForm('login')">Connexion</button>
      <button onclick="showForm('register')">Inscription</button>
    </div>
    <!-- Login Form -->
    <form id="login-form" class="auth-form active" onsubmit="return validateLogin(event)">
      <div class="mb-3">
        <label for="login-email" class="form-label">Adresse E-mail</label>
        <input type="email" class="form-control" id="login-email" required>
        <div class="error" id="login-email-error">Veuillez entrer une adresse e-mail valide.</div>
      </div>
      <div class="mb-3">
        <label for="login-password" class="form-label">Mot de Passe</label>
        <input type="password" class="form-control" id="login-password" required>
        <div class="error" id="login-password-error">Le mot de passe est requis.</div>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="remember-me">
        <label class="form-check-label" for="remember-me">Se souvenir de moi</label>
      </div>
      <button type="submit" class="btn btn-primary mt-3">Se Connecter</button>
      <p class="text-center mt-3"><a href="#">Mot de passe oublié ?</a></p>
    </form>
    <!-- Register Form -->
    <form id="register-form" class="auth-form" onsubmit="return validateRegister(event)">
      <div class="mb-3">
        <label for="register-name" class="form-label">Nom Complet</label>
        <input type="text" class="form-control" id="register-name" required>
        <div class="error" id="register-name-error">Le nom est requis.</div>
      </div>
      <div class="mb-3">
        <label for="register-email" class="form-label">Adresse E-mail</label>
        <input type="email" class="form-control" id="register-email" required>
        <div class="error" id="register-email-error">Veuillez entrer une adresse e-mail valide.</div>
      </div>
      <div class="mb-3">
        <label for="register-password" class="form-label">Mot de Passe</label>
        <input type="password" class="form-control" id="register-password" required>
        <div class="error" id="register-password-error">Le mot de passe doit contenir au moins 6 caractères.</div>
      </div>
      <div class="mb-3">
        <label for="register-confirm-password" class="form-label">Confirmer le Mot de Passe</label>
        <input type="password" class="form-control" id="register-confirm-password" required>
        <div class="error" id="register-confirm-password-error">Les mots de passe ne correspondent pas.</div>
      </div>
      <button type="submit" class="btn btn-primary mt-3">S'Inscrire</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Navbar scroll effect
    window.addEventListener('scroll', () => {
      const navbar = document.querySelector('.navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });

    // Toggle between login and register forms
    function showForm(formId) {
      document.querySelectorAll('.auth-form').forEach(form => form.classList.remove('active'));
      document.querySelectorAll('.auth-toggle button').forEach(btn => btn.classList.remove('active'));
      document.getElementById(`${formId}-form`).classList.add('active');
      document.querySelector(`button[onclick="showForm('${formId}')"]`).classList.add('active');
    }

    // Validate login form
    function validateLogin(event) {
      event.preventDefault();
      const email = document.getElementById('login-email').value;
      const password = document.getElementById('login-password').value;
      const emailError = document.getElementById('login-email-error');
      const passwordError = document.getElementById('login-password-error');
      let isValid = true;

      emailError.style.display = 'none';
      passwordError.style.display = 'none';

      if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        emailError.style.display = 'block';
        isValid = false;
      }
      if (!password) {
        passwordError.style.display = 'block';
        isValid = false;
      }

      if (isValid) {
        // Replace with backend API call for login
        alert('Connexion en cours (simulation). Intégrez une requête API vers /api/login ici.');
        // Example: POST /api/login with { email, password }
        // On success, redirect to account.html
        // window.location.href = 'account.html';
      }
      return isValid;
    }

    // Validate register form
    function validateRegister(event) {
      event.preventDefault();
      const name = document.getElementById('register-name').value;
      const email = document.getElementById('register-email').value;
      const password = document.getElementById('register-password').value;
      const confirmPassword = document.getElementById('register-confirm-password').value;
      const nameError = document.getElementById('register-name-error');
      const emailError = document.getElementById('register-email-error');
      const passwordError = document.getElementById('register-password-error');
      const confirmPasswordError = document.getElementById('register-confirm-password-error');
      let isValid = true;

      nameError.style.display = 'none';
      emailError.style.display = 'none';
      passwordError.style.display = 'none';
      confirmPasswordError.style.display = 'none';

      if (!name) {
        nameError.style.display = 'block';
        isValid = false;
      }
      if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        emailError.style.display = 'block';
        isValid = false;
      }
      if (!password || password.length < 6) {
        passwordError.style.display = 'block';
        isValid = false;
      }
      if (password !== confirmPassword) {
        confirmPasswordError.style.display = 'block';
        isValid = false;
      }

      if (isValid) {
        // Replace with backend API call for registration
        alert('Inscription en cours (simulation). Intégrez une requête API vers /api/register ici.');
        // Example: POST /api/register with { name, email, password }
        // On success, redirect to account.html or login
        // window.location.href = 'account.html';
      }
      return isValid;
    }
  </script>
</body>
</html> --}}
