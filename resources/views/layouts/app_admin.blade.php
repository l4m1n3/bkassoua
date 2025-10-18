<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Admin Dashboard for Bkassoua">
    <meta name="author" content="Bkassoua">
    <link href="{{ asset('assets/img/logo.png') }}" rel="icon">
    <title>Admin - Bkassoua</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed: 80px;
            --primary: #1780d6;
            --primary-dark: #1269b3;
            --primary-light: #e3f2fd;
            --secondary: #f4a261;
            --accent: #e76f51;
            --dark: #1a1d23;
            --dark-light: #2a2f3d;
            --light: #f8f9fa;
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
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fb;
            color: var(--dark);
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--dark) 0%, var(--dark-light) 100%);
            color: white;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
            z-index: 1035;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar-brand {
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .brand-logo {
            height: 40px;
            margin-bottom: 0.5rem;
        }

        .brand-text {
            font-weight: 700;
            font-size: 1.3rem;
            color: white;
            transition: var(--transition);
        }

        .sidebar.collapsed .brand-text {
            opacity: 0;
            visibility: hidden;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            margin-bottom: 1.5rem;
        }

        .nav-section-title {
            padding: 0.5rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.5);
            letter-spacing: 0.5px;
            transition: var(--transition);
        }

        .sidebar.collapsed .nav-section-title {
            opacity: 0;
            visibility: hidden;
        }

        .nav-item {
            margin: 0.2rem 0.8rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: var(--border-radius);
            transition: var(--transition);
            position: relative;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .nav-link.active {
            color: white;
            background: var(--primary);
            box-shadow: 0 4px 15px rgba(23, 128, 214, 0.3);
        }

        .nav-link i {
            width: 20px;
            font-size: 1.1rem;
            margin-right: 12px;
            transition: var(--transition);
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        .nav-text {
            font-weight: 500;
            transition: var(--transition);
        }

        .sidebar.collapsed .nav-text {
            opacity: 0;
            visibility: hidden;
        }

        .nav-badge {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: var(--accent);
            color: white;
            border-radius: 50px;
            padding: 0.2rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            min-width: 20px;
            text-align: center;
        }

        .sidebar.collapsed .nav-badge {
            right: 0.5rem;
        }

        /* Navbar Styles */
        .navbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: 70px;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            z-index: 1030;
            transition: var(--transition);
            padding: 0 1.5rem;
        }

        .sidebar.collapsed ~ .navbar {
            left: var(--sidebar-collapsed);
        }

        .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            color: var(--dark);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: var(--transition);
        }

        .sidebar-toggle:hover {
            background: var(--gray-light);
        }

        .page-title {
            font-weight: 600;
            font-size: 1.5rem;
            color: var(--dark);
            margin: 0;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .navbar-item {
            position: relative;
        }

        .navbar-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--dark);
            text-decoration: none;
            padding: 0.5rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .navbar-link:hover {
            background: var(--gray-light);
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--accent);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: 70px;
            padding: 2rem;
            min-height: calc(100vh - 70px);
            transition: var(--transition);
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed);
        }

        .content-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
        }

        .breadcrumb-item a {
            color: var(--gray);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: var(--primary);
        }

        .content-actions {
            display: flex;
            gap: 1rem;
        }

        /* Cards */
        .admin-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            border: none;
            transition: var(--transition);
            margin-bottom: 1.5rem;
        }

        .admin-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid var(--gray-light);
            padding: 1.25rem 1.5rem;
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
        }

        .card-title {
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary);
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-card.success {
            border-left-color: var(--success);
        }

        .stat-card.warning {
            border-left-color: var(--warning);
        }

        .stat-card.danger {
            border-left-color: var(--danger);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-card .stat-icon {
            background: var(--primary-light);
            color: var(--primary);
        }

        .stat-card.success .stat-icon {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .stat-card.warning .stat-icon {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }

        .stat-card.danger .stat-icon {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            line-height: 1;
        }

        .stat-label {
            color: var(--gray);
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .stat-change {
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .stat-change.positive {
            color: var(--success);
        }

        .stat-change.negative {
            color: var(--danger);
        }

        /* Buttons */
        .btn-admin {
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: var(--transition);
            border: none;
        }

        .btn-admin-primary {
            background: var(--primary);
            color: white;
        }

        .btn-admin-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(23, 128, 214, 0.3);
        }

        /* Tables */
        .admin-table {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .admin-table .table {
            margin: 0;
        }

        .admin-table .table thead th {
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
            border: none;
            padding: 1rem 1.5rem;
        }

        .admin-table .table tbody td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border-color: var(--gray-light);
        }

        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-active {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }

        .status-inactive {
            background: rgba(108, 117, 125, 0.1);
            color: var(--gray);
        }

        /* Mobile Responsive */
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

            .sidebar.collapsed {
                transform: translateX(-100%);
            }

            .sidebar.collapsed.show {
                transform: translateX(0);
                width: var(--sidebar-width);
            }

            .sidebar.collapsed.show .brand-text,
            .sidebar.collapsed.show .nav-text,
            .sidebar.collapsed.show .nav-section-title {
                opacity: 1;
                visibility: visible;
            }

            .sidebar.collapsed.show .nav-link i {
                margin-right: 12px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .content-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .content-actions {
                width: 100%;
                justify-content: space-between;
            }
        }

        /* Dark overlay for mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1034;
            display: none;
        }

        .sidebar-overlay.show {
            display: block;
        }
    </style>
</head>

<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Bkassoua" class="brand-logo">
            <div class="brand-text">Bkassoua Admin</div>
        </div>

        <nav class="sidebar-nav">
            <!-- Main Navigation -->
            <div class="nav-section">
                <div class="nav-section-title">Principal</div>
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i>
                        <span class="nav-text">Tableau de bord</span>
                    </a>
                </div>
            </div>

            <!-- Gestion -->
            <div class="nav-section">
                <div class="nav-section-title">Gestion</div>
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}" 
                       href="{{ route('admin.orders') }}">
                        <i class="bi bi-cart-check"></i>
                        <span class="nav-text">Commandes</span>
                        <span class="nav-badge">5</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}" 
                       href="/admin/products">
                        <i class="bi bi-box-seam"></i>
                        <span class="nav-text">Produits</span>
                        <span class="nav-badge">12</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" 
                       href="{{ route('admin.users') }}">
                        <i class="bi bi-people"></i>
                        <span class="nav-text">Utilisateurs</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}" 
                       href="{{ route('admin.categories') }}">
                        <i class="bi bi-tags"></i>
                        <span class="nav-text">Catégories</span>
                    </a>
                </div>
            </div>

            <!-- Analytics -->
            <div class="nav-section">
                <div class="nav-section-title">Analytics</div>
                <div class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-graph-up"></i>
                        <span class="nav-text">Rapports</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-bar-chart"></i>
                        <span class="nav-text">Statistiques</span>
                    </a>
                </div>
            </div>

            <!-- Settings -->
            <div class="nav-section">
                <div class="nav-section-title">Paramètres</div>
                <div class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-gear"></i>
                        <span class="nav-text">Paramètres</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-bell"></i>
                        <span class="nav-text">Notifications</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-content">
            <div class="navbar-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">@yield('title', 'Tableau de bord')</h1>
            </div>

            <div class="navbar-right">
                <!-- Notifications -->
                <div class="navbar-item">
                    <a href="#" class="navbar-link">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge">3</span>
                    </a>
                </div>

                <!-- Messages -->
                <div class="navbar-item">
                    <a href="#" class="navbar-link">
                        <i class="bi bi-envelope"></i>
                        <span class="notification-badge">7</span>
                    </a>
                </div>

                <!-- User Menu -->
                @auth
                <div class="navbar-item dropdown">
                    <a href="#" class="navbar-link dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-person me-2"></i>Profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-gear me-2"></i>Paramètres
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @else
                <div class="navbar-item">
                    <a class="navbar-link" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Connexion
                    </a>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Content Header -->
        <div class="content-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="breadcrumb-item active">@yield('title', 'Tableau de bord')</li>
                </ol>
            </nav>
            <div class="content-actions">
                @yield('actions')
            </div>
        </div>

        <!-- Page Content -->
        @yield('content')
    </main>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            let isCollapsed = false;

            // Toggle sidebar on desktop
            sidebarToggle.addEventListener('click', function() {
                if (window.innerWidth >= 992) {
                    // Desktop: toggle collapsed state
                    isCollapsed = !isCollapsed;
                    sidebar.classList.toggle('collapsed', isCollapsed);
                } else {
                    // Mobile: toggle visibility
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                }
            });

            // Close sidebar when clicking overlay on mobile
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });

            // Auto-close sidebar on mobile when clicking a link
            if (window.innerWidth < 992) {
                document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                    link.addEventListener('click', function() {
                        sidebar.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                    });
                });
            }

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            });

            // Add active state to current page
            const currentPath = window.location.pathname;
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>

</html>