<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bkassoua - @yield('title', 'Accueil')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        :root {
            --primary: #1780d6;
            --primary-dark: #1269b3;
            --primary-light: #e3f2fd;
            --secondary: #f4a261;
            --secondary-dark: #e69150;
            --accent: #e76f51;
            --accent-dark: #d45a3d;
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
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }

        /* Header amélioré avec navigation unifiée */
        .main-header {
            background: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            transition: var(--transition);
        }

        .main-header.scrolled {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }

        .header-container {
            display: flex;
            align-items: center;
            padding: 12px 0;
        }

        .logo-container {
            flex: 0 0 auto;
        }

        .logo {
            height: 45px;
            transition: var(--transition);
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .search-container {
            flex: 1;
            max-width: 600px;
            margin: 0 30px;
            position: relative;
        }

        .search-form {
            display: flex;
            width: 100%;
        }

        .search-input {
            border-radius: 50px 0 0 50px;
            border: 2px solid var(--gray-light);
            border-right: none;
            padding: 12px 20px;
            font-size: 15px;
            transition: var(--transition);
            width: 100%;
        }

        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(23, 128, 214, 0.1);
            outline: none;
        }

        .search-button {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 0 50px 50px 0;
            padding: 0 25px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .search-button:hover {
            background: var(--primary-dark);
        }

        .nav-container {
            flex: 0 0 auto;
        }

        .main-nav {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link {
            color: var(--dark);
            text-decoration: none;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 50px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary);
            background: var(--primary-light);
        }

        .nav-icon {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            transition: var(--transition);
        }

        .nav-icon:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--accent);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Mobile menu */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--dark);
            cursor: pointer;
        }

        /* Main content area */
        .main-content {
            margin-top: 100px;
            min-height: calc(100vh - 300px);
        }

        /* Hero Section améliorée */
        .hero-section {
            background: linear-gradient(135deg, rgba(23, 128, 214, 0.9) 0%, rgba(244, 162, 97, 0.8) 100%), 
                        url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
            height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
            border-radius: var(--border-radius);
            margin-bottom: 40px;
        }

        .hero-content {
            max-width: 800px;
            padding: 0 20px;
            z-index: 2;
        }

        .hero-title {
            font-weight: 700;
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .hero-subtitle {
            font-size: 1.25rem;
            margin-bottom: 2.5rem;
            opacity: 0.9;
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 7px 15px rgba(23, 128, 214, 0.3);
        }

        .btn-outline-light {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-outline-light:hover {
            background: white;
            color: var(--dark);
            transform: translateY(-3px);
        }

        /* Section Titles */
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .section-title h2 {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1rem;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--primary);
            margin: 0 auto;
            border-radius: 2px;
        }

        /* Product Cards améliorées */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .product-card {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            position: relative;
            overflow: hidden;
            height: 250px;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--accent);
            color: white;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            z-index: 2;
        }

        .product-actions {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            opacity: 0;
            transform: translateX(10px);
            transition: var(--transition);
        }

        .product-card:hover .product-actions {
            opacity: 1;
            transform: translateX(0);
        }

        .action-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }

        .action-btn:hover {
            background: var(--primary);
            color: white;
            transform: scale(1.1);
        }

        .product-info {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-category {
            color: var(--gray);
            font-size: 13px;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .product-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark);
            line-height: 1.4;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 15px;
        }

        .stars {
            color: var(--secondary);
        }

        .rating-count {
            color: var(--gray);
            font-size: 14px;
        }

        .product-price {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: auto;
        }

        .current-price {
            font-weight: 700;
            font-size: 18px;
            color: var(--primary);
        }

        .original-price {
            text-decoration: line-through;
            color: var(--gray);
            font-size: 14px;
        }

        .add-to-cart {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
            cursor: pointer;
            margin-top: 15px;
            width: 100%;
            justify-content: center;
        }

        .add-to-cart:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Sidebar améliorée */
        .sidebar {
            background: white;
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 30px;
            position: sticky;
            top: 120px;
        }

        .sidebar-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--gray-light);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-section {
            margin-bottom: 25px;
        }

        .filter-title {
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price-inputs {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .price-input {
            flex: 1;
            border: 1px solid var(--gray-light);
            border-radius: 8px;
            padding: 10px;
            font-size: 14px;
        }

        .filter-options {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .filter-option {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .filter-option input {
            width: 18px;
            height: 18px;
        }

        .color-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .color-option {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid transparent;
            transition: var(--transition);
        }

        .color-option.active {
            border-color: var(--dark);
            transform: scale(1.1);
        }

        /* Footer amélioré */
        .main-footer {
            background: var(--dark);
            color: white;
            padding: 60px 0 30px;
            margin-top: 60px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-column h5 {
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-column h5::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background: var(--primary);
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-links a:hover {
            color: white;
            transform: translateX(5px);
        }

        .footer-links a i {
            font-size: 14px;
        }

        .newsletter-form {
            display: flex;
            margin-top: 15px;
        }

        .newsletter-input {
            flex: 1;
            border: none;
            border-radius: 50px 0 0 50px;
            padding: 12px 20px;
            font-size: 14px;
        }

        .newsletter-button {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 0 50px 50px 0;
            padding: 0 20px;
            cursor: pointer;
            transition: var(--transition);
        }

        .newsletter-button:hover {
            background: var(--primary-dark);
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-link {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: var(--transition);
        }

        .social-link:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .header-container {
                flex-wrap: wrap;
            }
            
            .search-container {
                order: 3;
                margin: 15px 0 0;
                max-width: 100%;
            }
            
            .mobile-menu-btn {
                display: block;
                margin-left: auto;
            }
            
            .main-nav {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                flex-direction: column;
                padding: 20px;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
                border-radius: 0 0 var(--border-radius) var(--border-radius);
            }
            
            .main-nav.active {
                display: flex;
            }
            
            .nav-link {
                width: 100%;
                justify-content: flex-start;
            }
            
            .hero-title {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                height: 50vh;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 250px;
            }
            
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }

        @media (max-width: 576px) {
            .main-content {
                margin-top: 80px;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
            }
            
            .price-inputs {
                flex-direction: column;
            }
        }

        /* Loading animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.6s ease forwards;
        }

        /* Utility classes */
        .text-primary { color: var(--primary) !important; }
        .bg-primary { background-color: var(--primary) !important; }
        .text-accent { color: var(--accent) !important; }
        .bg-accent { background-color: var(--accent) !important; }
        .rounded-lg { border-radius: var(--border-radius) !important; }
        .shadow-lg { box-shadow: var(--shadow) !important; }
    </style>
</head>

<body>
    <!-- Header unifié -->
    <header class="main-header">
        <div class="container">
            <div class="header-container">
                <div class="logo-container">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('assets/img/logo_nav.png') }}" alt="Bkassoua" class="logo" />
                    </a>
                </div>
                
                <div class="search-container">
                    <form class="search-form" action="{{ route('search') }}" method="GET">
                        <input type="search" name="query" class="search-input" placeholder="Rechercher des produits..." />
                        <button type="submit" class="search-button">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
                
                <button class="mobile-menu-btn">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="nav-container">
                    <nav class="main-nav">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house"></i> Accueil
                        </a>
                        <a class="nav-link {{ request()->routeIs('shop') ? 'active' : '' }}" href="{{ route('shop') }}">
                            <i class="bi bi-shop"></i> Boutique
                        </a>
                        <a class="nav-link" href="{{ route('about') }}">
                            <i class="bi bi-info-circle"></i> À Propos
                        </a>
                        
                        @auth
                            @if (Auth::user()->role === 'vendor' && isset($vendor) && $vendor->status == 'active')
                                <a class="nav-link" href="{{ route('vendor.dashboard') }}">
                                    <i class="bi bi-bag"></i> Ma boutique
                                </a>
                            @elseif (Auth::user()->role === 'vendor' && isset($vendor) && $vendor->status == 'inactive')
                                <a class="nav-link text-warning disabled" href="#">
                                    <i class="bi bi-clock"></i> En validation
                                </a>
                            @else
                                <a class="nav-link" href="{{ route('vendor.register') }}">
                                    <i class="bi bi-person-plus"></i> Devenir vendeur
                                </a>
                            @endif
                        @endauth
                        
                        <a class="nav-icon" href="{{ route('wishlist') }}">
                            <i class="bi bi-heart"></i>
                            <span class="badge">3</span>
                        </a>
                        
                        <a class="nav-icon" href="{{ route('cart') }}">
                            <i class="bi bi-cart"></i>
                            <span class="badge">2</span>
                        </a>
                        
                        <a class="nav-icon" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person"></i>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenu Principal -->
    <main class="main-content">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h5>À Propos de Bkassoua</h5>
                    <p>Votre destination de confiance pour la mode tendance et abordable. Découvrez nos collections uniques et élégantes.</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h5>Liens Rapides</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}"><i class="bi bi-chevron-right"></i> Accueil</a></li>
                        <li><a href="{{ route('shop') }}"><i class="bi bi-chevron-right"></i> Boutique</a></li>
                        <li><a href="{{ route('about') }}"><i class="bi bi-chevron-right"></i> À Propos</a></li>
                        <li><a href="{{ route('contact') }}"><i class="bi bi-chevron-right"></i> Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h5>Informations</h5>
                    <ul class="footer-links">
                        <li><a href="#"><i class="bi bi-chevron-right"></i> Politique de Confidentialité</a></li>
                        <li><a href="#"><i class="bi bi-chevron-right"></i> Conditions d'Utilisation</a></li>
                        <li><a href="#"><i class="bi bi-chevron-right"></i> Livraison & Retours</a></li>
                        <li><a href="#"><i class="bi bi-chevron-right"></i> FAQ</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h5>Newsletter</h5>
                    <p>Abonnez-vous pour recevoir nos offres exclusives et les dernières tendances.</p>
                    <form class="newsletter-form">
                        <input type="email" class="newsletter-input" placeholder="Votre adresse email" required />
                        <button type="submit" class="newsletter-button">
                            <i class="bi bi-send"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 Bkassoua. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
            document.querySelector('.main-nav').classList.toggle('active');
        });

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.main-header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.addEventListener('DOMContentLoaded', function() {
            const elementsToAnimate = document.querySelectorAll('.product-card, .section-title');
            elementsToAnimate.forEach(el => {
                observer.observe(el);
            });
        });

        // Price range filter
        const priceRange = document.getElementById('priceRange');
        if (priceRange) {
            const minPrice = document.getElementById('minPrice');
            const maxPrice = document.getElementById('maxPrice');
            
            priceRange.addEventListener('input', function() {
                const value = this.value;
                maxPrice.textContent = value + ' fcfa';
            });
        }

        // Color filter selection
        document.querySelectorAll('.color-option').forEach(option => {
            option.addEventListener('click', function() {
                this.classList.toggle('active');
            });
        });
    </script>
</body>
</html>