@extends('layouts.app_admin')

@section('content')
<main class="col-md-12 col-lg-12 px-md-4 mt-4">
    <!-- En-tête du Dashboard -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2 mt-4">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="shareDashboard()">Partager</button>
                <button type="button" class="btn btn-sm btn-outline-success" onclick="exportDashboard()">Exporter</button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <span data-feather="calendar"></span> Cette Semaine
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="selectPeriod('This week')">Cette Semaine</a></li>
                    <li><a class="dropdown-item" href="#" onclick="selectPeriod('Last week')">Semaine dernière</a></li>
                    <li><a class="dropdown-item" href="#" onclick="selectPeriod('This month')">Ce mois</a></li>
                    <li><a class="dropdown-item" href="#" onclick="selectPeriod('Last month')">Mois dernier</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Filtres supplémentaires -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Rechercher par produit" id="productSearch" onkeyup="filterProducts()">
                <button class="btn btn-outline-secondary" type="button" onclick="filterProducts()">Rechercher</button>
            </div>
        </div>
    </div>

    <!-- Cartes statistiques avec icônes et badges -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card border-primary mb-4 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-2x text-primary mb-3"></i>
                    <h6 class="card-title">Clients</h6>
                    <p class="card-text">{{ $customersCount }}</p>
                    {{-- <span class="badge bg-info">Mise à jour</span> --}}
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-success mb-4 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-user-tie fa-2x text-success mb-3"></i>
                    <h6 class="card-title">Admins</h6>
                    <p class="card-text">{{ $vendorsCount }}</p>
                    {{-- <span class="badge bg-success">Actif</span> --}}
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-warning mb-4 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-box-open fa-2x text-warning mb-3"></i>
                    <h6 class="card-title">Produits</h6>
                    <p class="card-text">{{ $productsCount }}</p>
                    {{-- <span class="badge bg-warning">Nouveaux</span> --}}
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-danger mb-4 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-shopping-cart fa-2x text-danger mb-3"></i>
                    <h6 class="card-title">Commandes</h6>
                    <p class="card-text">{{ $ordersCount }}</p>
                    {{-- <span class="badge bg-danger">Urgent</span> --}}
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-info mb-4 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-list-ul fa-2x text-info mb-3"></i>
                    <h6 class="card-title">Catégories</h6>
                    <p class="card-text">{{ $categoriesCount }}</p>
                    {{-- <span class="badge bg-info">Populaire</span> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique dynamique -->
    <canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas>

    <!-- Notifications (toasts) -->
    <div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="notificationToast" class="toast align-items-center text-bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Nouvelle commande urgente !
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Historique des activités -->
    <div class="mt-4">
        <h5>Historique des Activités</h5>
        <ul class="list-group">
            <li class="list-group-item">Création de produit - 12/02/2025</li>
            <li class="list-group-item">Commande validée - 13/02/2025</li>
            <li class="list-group-item">Nouvel utilisateur inscrit - 14/02/2025</li>
        </ul>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique interactif avec Chart.js
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Clients', 'Admins', 'Produits', 'Commandes', 'Catégories'],
            datasets: [{
                label: 'Statistiques',
                data: [{{ $customersCount }}, {{ $vendorsCount }}, {{ $productsCount }}, {{ $ordersCount }}, {{ $categoriesCount }}],
                backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)'],
                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Filtrer les produits (fonction à compléter selon le backend ou les données)
    function filterProducts() {
        const searchQuery = document.getElementById('productSearch').value.toLowerCase();
        alert('Filtrer les produits avec le terme: ' + searchQuery);
        // Ajouter ici la logique pour filtrer les produits selon le terme recherché
    }

    // Toast notification
    setTimeout(() => {
        var toast = new bootstrap.Toast(document.getElementById('notificationToast'));
        toast.show();
    }, 1000); // Afficher le toast après 1 seconde

    // Autres fonctions (Partager, Exporter, Sélectionner la période)
    function shareDashboard() {
        alert('Sharing the dashboard...');
        // Logique pour partager le tableau de bord
    }

    function exportDashboard() {
        alert('Exporting the dashboard...');
        // Logique pour exporter le tableau de bord
    }

    function selectPeriod(period) {
        alert('You selected: ' + period);
        // Logique pour filtrer selon la période sélectionnée
    }
</script>
@endsection
{{-- @extends('layouts.app_admin')

@section('content')
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <!-- En-tête du Dashboard -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="shareDashboard()">Partager</button>
                <button type="button" class="btn btn-sm btn-outline-success" onclick="exportDashboard()">Exporter</button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <span data-feather="calendar"></span> Cette Semaine
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="selectPeriod('This week')">Cette Semaine</a></li>
                    <li><a class="dropdown-item" href="#" onclick="selectPeriod('Last week')">Semaine dernière</a></li>
                    <li><a class="dropdown-item" href="#" onclick="selectPeriod('This month')">Ce mois</a></li>
                    <li><a class="dropdown-item" href="#" onclick="selectPeriod('Last month')">Mois dernier</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Filtres supplémentaires -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Rechercher par produit" id="productSearch" onkeyup="filterProducts()">
                <button class="btn btn-outline-secondary" type="button" onclick="filterProducts()">Rechercher</button>
            </div>
        </div>
    </div>

    <!-- Cartes statistiques avec icônes et badges -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card border-primary mb-4 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-2x text-primary mb-3"></i>
                    <h6 class="card-title">Clients</h6>
                    <p class="card-text">{{ $customersCount }}</p>
                    <span class="badge bg-info">Mise à jour</span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-success mb-4 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-user-tie fa-2x text-success mb-3"></i>
                    <h6 class="card-title">Admins</h6>
                    <p class="card-text">{{ $vendorsCount }}</p>
                    <span class="badge bg-success">Actif</span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-warning mb-4 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-box-open fa-2x text-warning mb-3"></i>
                    <h6 class="card-title">Produits</h6>
                    <p class="card-text">{{ $productsCount }}</p>
                    <span class="badge bg-warning">Nouveaux</span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-danger mb-4 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-shopping-cart fa-2x text-danger mb-3"></i>
                    <h6 class="card-title">Commandes</h6>
                    <p class="card-text">{{ $ordersCount }}</p>
                    <span class="badge bg-danger">Urgent</span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-info mb-4 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-list-ul fa-2x text-info mb-3"></i>
                    <h6 class="card-title">Catégories</h6>
                    <p class="card-text">{{ $categoriesCount }}</p>
                    <span class="badge bg-info">Populaire</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique principal -->
    <canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas>

    <!-- Graphique des utilisateurs du mois -->
    <canvas class="my-4 w-100" id="usersMonthChart" width="900" height="380"></canvas>

    <!-- Graphique des commandes de la semaine -->
    <canvas class="my-4 w-100" id="ordersWeekChart" width="900" height="380"></canvas>

    <!-- Notifications (toasts) -->
    <div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="notificationToast" class="toast align-items-center text-bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Nouvelle commande urgente !
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Historique des activités -->
    <div class="mt-4">
        <h5>Historique des Activités</h5>
        <ul class="list-group">
            <li class="list-group-item">Création de produit - 12/02/2025</li>
            <li class="list-group-item">Commande validée - 13/02/2025</li>
            <li class="list-group-item">Nouvel utilisateur inscrit - 14/02/2025</li>
        </ul>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique interactif avec Chart.js
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Clients', 'Admins', 'Produits', 'Commandes', 'Catégories'],
            datasets: [{
                label: 'Statistiques',
                data: [{{ $customersCount }}, {{ $vendorsCount }}, {{ $productsCount }}, {{ $ordersCount }}, {{ $categoriesCount }}],
                backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)'],
                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique des utilisateurs du mois
    const usersMonthCtx = document.getElementById('usersMonthChart').getContext('2d');
    const usersMonthChart = new Chart(usersMonthCtx, {
        type: 'line',
        data: {
            labels: ['Semaine 1', 'Semaine 2', 'Semaine 3', 'Semaine 4'],
            datasets: [{
                label: 'Utilisateurs du mois',
                data: [{{ $usersWeek1 }}, {{ $usersWeek2 }}, {{ $usersWeek3 }}, {{ $usersWeek4 }}], // Remplacer ces variables par vos données
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique des commandes de la semaine
    const ordersWeekCtx = document.getElementById('ordersWeekChart').getContext('2d');
    const ordersWeekChart = new Chart(ordersWeekCtx, {
        type: 'bar',
        data: {
            labels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'],
            datasets: [{
                label: 'Commandes de la semaine',
                data: [{{ $ordersMonday }}, {{ $ordersTuesday }}, {{ $ordersWednesday }}, {{ $ordersThursday }}, {{ $ordersFriday }}, {{ $ordersSaturday }}, {{ $ordersSunday }}], // Remplacer par les données réelles
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection --}}
