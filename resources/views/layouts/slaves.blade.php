<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bkassoua - @yield('title', 'Accueil')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
    <style>
        :root {
            --primary: #1780d6;
            --secondary: #f4a261;
            --accent: #e76f51;
            --light: #f8f9fa;
            --dark: #264653;
        }

        body {
            font-family: "Poppins", sans-serif;
            background-color: var(--light);
        }

        /* Secondary Navbar (Logo + Search) */
        .secondary-navbar {
            background-color: rgba(248, 249, 250, 0.95);
            padding: 10px 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: background-color 0.3s ease;
        }

        .secondary-navbar.scrolled {
            background-color: rgba(248, 249, 250, 1);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .secondary-navbar .navbar-brand img {
            height: 40px;
            transition: transform 0.3s;
        }

        .secondary-navbar .navbar-brand img:hover {
            transform: scale(1.1);
        }

        .secondary-navbar form {
            max-width: 500px;
            flex-grow: 1;
        }

        .secondary-navbar .form-control {
            border-radius: 5px 0 0 5px;
            transition: border-color 0.3s;
        }

        .secondary-navbar .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 5px rgba(231, 111, 81, 0.3);
        }

        .secondary-navbar .btn-outline-primary {
            border-radius: 0 5px 5px 0;
            border-color: var(--primary);
            color: var(--primary);
            transition: background-color 0.3s, color 0.3s;
        }

        .secondary-navbar .btn-outline-primary:hover {
            background-color: var(--secondary);
            color: var(--dark);
        }

        /* Primary Navbar */
        .navbar {
            background-color: var(--primary);
            padding: 10px 0;
            position: fixed;
            top: 60px;
            /* Adjust for secondary navbar height */
            width: 100%;
            z-index: 1020;
            transition: background-color 0.3s ease;
        }

        .navbar.scrolled {
            background-color: rgba(44, 74, 110, 0.9);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

        .navbar .bi {
            font-size: 1.5rem;
            color: white;
            transition: color 0.3s;
        }

        .navbar .bi:hover {
            color: var(--secondary);
        }

        .nav-item.disabled .nav-link {
            color: #ffc107 !important;
            cursor: not-allowed;
        }

        /* Cart Table */
        .table {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background-color: var(--primary);
            color: white;
        }

        .table tbody tr {
            transition: background-color 0.3s;
        }

        .table tbody tr:hover {
            background-color: var(--light);
        }

        .table td {
            vertical-align: middle;
        }

        .table input[type="number"] {
            max-width: 80px;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        .table input[type="number"]:focus {
            border-color: var(--accent);
            box-shadow: 0 0 5px rgba(231, 111, 81, 0.3);
        }

        .table .btn-outline-danger {
            border-color: var(--accent);
            color: var(--accent);
            transition: background-color 0.3s, color 0.3s;
        }

        .table .btn-outline-danger:hover {
            background-color: var(--accent);
            color: white;
        }

        /* Checkout Form */
        #checkout {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        #checkout h3 {
            color: var(--primary);
            font-weight: 600;
        }

        #checkout .form-label {
            color: var(--dark);
            font-weight: 500;
        }

        #checkout .form-control,
        #checkout .form-select {
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        #checkout .form-control:focus,
        #checkout .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 5px rgba(231, 111, 81, 0.3);
        }

        #checkout .btn-success {
            background-color: var(--primary);
            border: none;
            padding: 10px 20px;
            font-weight: 500;
            transition: background-color 0.3s, transform 0.3s;
        }

        #checkout .btn-success:hover {
            background-color: var(--accent);
            transform: translateY(-2px);
        }

        .trust-badge img {
            height: 40px;
            margin-right: 10px;
            transition: transform 0.3s;
        }

        .trust-badge img:hover {
            transform: scale(1.1);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                url("https://via.placeholder.com/1920x600") center/cover no-repeat;
            height: 600px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section h1 {
            font-weight: 600;
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .hero-section p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
        }

        .hero-section .btn {
            background-color: var(--secondary);
            border: none;
            padding: 12px 30px;
            font-weight: 500;
            transition: background-color 0.3s, transform 0.3s;
        }

        .hero-section .btn:hover {
            background-color: var(--accent);
            transform: translateY(-3px);
        }

        /* Sidebar */
        .sidebar {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .sidebar h5 {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .accordion-button {
            color: var(--primary);
            font-weight: 500;
            background-color: transparent;
        }

        .accordion-button:not(.collapsed) {
            color: var(--primary);
            background-color: var(--light);
        }

        .accordion-body a {
            color: var(--dark);
            text-decoration: none;
            padding: 5px 0;
            display: block;
            transition: color 0.3s;
        }

        .accordion-body a:hover {
            color: var(--accent);
        }

        .form-label {
            color: var(--primary);
            font-weight: 500;
        }

        /* Product Cards */
        .product-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .product-card img {
            aspect-ratio: 4/3;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .product-card:hover img {
            transform: scale(1.05);
        }

        .product-card .card-body {
            padding: 1.5rem;
        }

        .product-card .card-title {
            color: var(--dark);
            font-weight: 500;
        }

        .product-card .card-text {
            color: var(--accent);
            font-weight: 600;
        }

        .product-card .btn {
            border-color: var(--primary);
            color: var(--primary);
            transition: background-color 0.3s, color 0.3s;
        }

        .product-card .btn:hover {
            background-color: var(--primary);
            color: white;
        }

        /* Category Cards */
        .category-card {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s;
        }

        .category-card:hover {
            transform: scale(1.05);
        }

        .category-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .category-card:hover img {
            transform: scale(1.1);
        }

        .category-card .title {
            position: absolute;
            bottom: 15px;
            left: 15px;
            color: white;
            background: rgba(0, 0, 0, 0.7);
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: 500;
        }

        /* Contact Section */
        .contact-section {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .contact-section h2 {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .contact-section p {
            color: var(--dark);
            line-height: 1.6;
        }

        .contact-section form {
            max-width: 500px;
        }

        .contact-section .form-control,
        .contact-section .form-select {
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        .contact-section .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 5px rgba(231, 111, 81, 0.3);
        }

        .contact-section .btn-primary {
            background-color: var(--secondary);
            border: none;
            padding: 10px 20px;
            font-weight: 500;
            transition: background-color 0.3s, transform 0.3s;
        }

        .contact-section .btn-primary:hover {
            background-color: var(--accent);
            transform: translateY(-2px);
        }

        /* Contact Info */
        .contact-info h5 {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .contact-info p {
            color: var(--dark);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .contact-info .bi {
            font-size: 1.2rem;
            color: var(--accent);
            margin-right: 10px;
        }

        .map-placeholder {
            height: 300px;
            background: #e9ecef;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark);
            font-weight: 500;
            transition: transform 0.3s;
        }

        .map-placeholder:hover {
            transform: scale(1.02);
        }

        /* Footer */
        footer {
            background-color: #1780d6;
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
            .secondary-navbar {
                padding: 8px 0;
            }

            .secondary-navbar .navbar-brand img {
                height: 30px;
            }

            .secondary-navbar form {
                max-width: 100%;
                margin: 5px 0;
            }

            .navbar {
                top: 50px;
            }

            .hero-section {
                height: 400px;
            }

            .hero-section h1 {
                font-size: 2rem;
            }

            .hero-section p {
                font-size: 1rem;
            }

            .sidebar {
                margin-bottom: 30px;
            }

            .container.mt-5.pt-5 {
                margin-top: 120px !important;
                /* Adjust for dual navbars */
            }
        }
    </style>
</head>

<body>
    <!-- Secondary Navbar (Logo + Search) -->
    <nav class="secondary-navbar">
        <div class="container">
            <div class="d-flex align-items-center w-100">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('assets/img/logo_nav.png') }}" alt="Bkassoua" />
                </a>
                <form class="d-flex ms-auto" action="{{ route('search') }}" method="GET">
                    <input class="form-control me-2" type="search" name="query" placeholder="Rechercher"
                        aria-label="Search" />
                    <button class="btn btn-outline-primary" type="submit">Rechercher</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Primary Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('shop') ? 'active' : '' }}"
                            href="{{ route('shop') }}">Boutique</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">À Propos</a>
                    </li>
                    @auth
                        @if (Auth::user()->role === 'vendor' && isset($vendor) && $vendor->status == 'active')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('vendor.dashboard') }}">Ma boutique</a>
                            </li>
                        @elseif (Auth::user()->role === 'vendor' && isset($vendor) && $vendor->status == 'inactive')
                            <li class="nav-item">
                                <a class="nav-link text-warning disabled" href="#">Ma boutique (En cours de
                                    validation)</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('vendor.register') }}">Devenir vendeur</a>
                            </li>
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('wishlist') }}"><i class="bi bi-heart"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart') }}"><i class="bi bi-cart"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.edit') }}"><i class="bi bi-person"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu Principal -->
    <div class="container-fluid mt-5 pt-5">
        <div class="row">
            <!-- Contenu Accueil -->
            <div class="col-12">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-white">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5>À Propos</h5>
                    <p>
                        Bkassoua est votre destination pour les dernières tendances en
                        mode. Découvrez nos collections uniques et élégantes.
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Liens Utiles</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Politique de Confidentialité</a></li>
                        <li><a href="#">Conditions d'Utilisation</a></li>
                        <li><a href="{{ route('contact') }}">Nous Contacter</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Newsletter</h5>
                    <form class="newsletter-form d-flex">
                        <input type="email" class="form-control" placeholder="Votre email" required />
                        <button class="btn btn-primary" type="submit">S'abonner</button>
                    </form>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-twitter"></i></a>
                    </div>
                </div>
            </div>
            <hr class="bg-light" />
            <p class="text-center mb-0">© 2025 Bkassoua. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Scroll effect for both navbars
        window.addEventListener("scroll", () => {
            const secondaryNavbar = document.querySelector(".secondary-navbar");
            const primaryNavbar = document.querySelector(".navbar");
            if (window.scrollY > 50) {
                secondaryNavbar.classList.add("scrolled");
                primaryNavbar.classList.add("scrolled");
            } else {
                secondaryNavbar.classList.remove("scrolled");
                primaryNavbar.classList.remove("scrolled");
            }
        });
    </script>
</body>

</html>
