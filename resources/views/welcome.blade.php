{{-- 
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
		<title>Trezo - Bootstrap 5 Admin Dashboard Template</title>
    </head>
    <body class="boxed-size">
        <!-- Start Preloader Area -->
        <div class="preloader" id="preloader">
            <div class="preloader">
                <div class="waviy position-relative">
                    <span class="d-inline-block">T</span>
                    <span class="d-inline-block">R</span>
                    <span class="d-inline-block">E</span>
                    <span class="d-inline-block">Z</span>
                    <span class="d-inline-block">O</span>
                </div>
            </div>
        </div>
        <!-- End Preloader Area -->

        <!-- Start Main Content Area -->
        <div class="container-fluid">
            <div class="main-content d-flex flex-column">
              

                <div class="main-content-container overflow-hidden">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                        <h3 class="mb-0">Starter</h3>

                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb align-items-center mb-0 lh-1">
                                <li class="breadcrumb-item">
                                    <a href="#" class="d-flex align-items-center text-decoration-none">
                                        <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                                        <span class="text-secondary fw-medium hover">Dashboard</span>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <span class="fw-medium">Starter</span>
                                </li>
                            </ol>
                        </nav>
                    </div>

                    <div class="card bg-white border-0 rounded-3 mb-4">
                        <div class="card-body p-4 text-center">
                            <img src="{{asset('assetss/images/starter.png')}}" class="mb-4" alt="starter">
                            <h3 class="fs-4 mb-4 m-auto" style="max-width: 500px;">Create something beautiful, like a masterpiece or a really good sandwich.</h3>
                            <a href="index.html" class="btn btn-primary text-decoration-none py-2 px-3 fs-16 fw-medium">
                                <span class="d-inline-block py-1">Getting Started</span> 
                            </a>
                        </div>
                    </div>
                </div>

                <div class="flex-grow-1"></div>

                <!-- Start Footer Area -->
                <footer class="footer-area bg-white text-center rounded-top-7">
                    <p class="fs-14">© <span class="text-primary-div">Trezo</span> is Proudly Owned by <a href="https://envytheme.com/" target="_blank" class="text-decoration-none text-primary">EnvyTheme</a></p>
                </footer>
                <!-- End Footer Area -->
            </div>
        </div>
        <!-- Start Main Content Area -->

        <!-- Start Theme Setting Area -->
        <div class="offcanvas offcanvas-end bg-white" data-bs-scroll="true" data-bs-backdrop="true" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel" style="box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;">
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
</html> --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Landing Page - Compte à Rebours</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Arrière-plan en pleine page {{ asset('assets/img/logo.png') }}*/
        body {
            background: url('{{ asset('assets/img/banner.jpeg') }}') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
        }

        /* Ajout d'un overlay pour améliorer la lisibilité */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6); /* Effet sombre */
            z-index: 1;
        }

        .content {
            position: relative;
            z-index: 2;
        }

        .countdown {
            font-size: 2rem;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Overlay -->
    <div class="overlay"></div>

    <!-- Contenu principal -->
    <div class="content">
        <h1 class="mb-4">Lancement Prochainement 🚀</h1>
        <p class="lead">Notre site arrive bientôt ! Restez à l'écoute.</p>

        <div id="countdown" class="countdown my-4"></div>

        <a href="#" class="btn btn-light btn-lg mt-3">Soyez Notifié</a>
    </div>

    <!-- JavaScript pour le compte à rebours -->
    <script>
        function startCountdown(targetDate) {
            function updateCountdown() {
                const now = new Date().getTime();
                const distance = targetDate - now;

                if (distance < 0) {
                    document.getElementById("countdown").innerHTML = "C'est parti ! 🚀";
                    clearInterval(interval);
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("countdown").innerHTML = 
                    `${days}j ${hours}h ${minutes}m ${seconds}s`;
            }

            updateCountdown();
            const interval = setInterval(updateCountdown, 1000);
        }

        const launchDate = new Date("2025-03-01T00:00:00").getTime();
        startCountdown(launchDate);
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
