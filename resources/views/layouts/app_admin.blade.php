<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Admin Dashboard for Bkassoua">
    <meta name="author" content="Bkassoua">
    <link href="{{ asset('assets/img/logo.png') }}" rel="icon">
    <title>Admin-Bkassoua</title>

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Dans le <head> -->
    <script src="{{ asset('assets/dist/js/bootstrap.bundle.min.js') }}" defer></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #007bff;
            --dark-color: #343a40;
            --light-dark: #495057;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding-top: 56px;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--dark-color);
            color: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            z-index: 1030;
        }

        .sidebar-brand {
            padding: 1rem 1.5rem;
            font-size: 1.2rem;
            display: block;
            color: white;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1.5rem;
            margin: 0.2rem 0;
            border-radius: 0;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: var(--primary-color);
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }

        .sidebar .submenu {
            padding-left: 2.5rem;
            background-color: var(--light-dark);
        }

        /* Navbar */
        .navbar {
            background-color: var(--dark-color);
            padding: 0.5rem 1rem;
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            z-index: 1020;
        }

        /* Main content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: calc(100vh - 56px);
        }

        /* Mobile styles */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .navbar {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <a href="#" class="sidebar-brand d-block text-center py-3 mb-4">
            B Kassoua
        </a>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}"
                    href="{{ route('admin.orders') }}">
                    <i class="fas fa-file-invoice"></i> Commandes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}"
                    href="/admin/products">
                    <i class="fas fa-box"></i> Produits
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"
                    href="{{ route('admin.users') }}">
                    <i class="fas fa-users"></i> Clients/Vendeurs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}"
                    href="{{ route('admin.categories') }}">
                    <i class="fas fa-tags"></i> Catégories
                </a>
            </li>
        </ul>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand navbar-dark">
        <button class="btn btn-link text-white d-lg-none" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>

        <ul class="navbar-nav ms-auto">
            @auth
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
            @else
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt me-1"></i> Connexion
                </a>
            </li>
            @endauth
        </ul>
    </nav>

    <!-- Main content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Bootstrap Bundle with Popper -->
    <script src="{{ asset('assets/dist/js/bootstrap.bundle.min.js') }}"></script>
    <!-- OU en fin de <body> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebarToggle');

            if (window.innerWidth < 992 &&
                !sidebar.contains(event.target) &&
                event.target !== toggleBtn &&
                !toggleBtn.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });
    </script>
</body>

</html>