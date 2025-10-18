@extends('layouts.app_admin')

@section('title', 'Tableau de Bord - Admin Bkassoua')

@section('content')
<div class="dashboard-container">
    <!-- En-tête du Dashboard -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-text">
                <h1 class="dashboard-title">Tableau de Bord</h1>
                <p class="dashboard-subtitle">Aperçu général de votre plateforme Bkassoua</p>
            </div>
            <div class="header-actions">
                <div class="action-group">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="shareDashboard()">
                        <i class="bi bi-share me-2"></i>Partager
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="exportDashboard()">
                        <i class="bi bi-download me-2"></i>Exporter
                    </button>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-info btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-calendar me-2"></i>Cette Semaine
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="selectPeriod('this_week')">Cette Semaine</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectPeriod('last_week')">Semaine dernière</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectPeriod('this_month')">Ce mois</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectPeriod('last_month')">Mois dernier</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectPeriod('this_quarter')">Ce trimestre</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres rapides -->
    <div class="filters-section mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" class="form-control" placeholder="Rechercher par produit..." id="productSearch">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="categoryFilter">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="statusFilter">
                    <option value="">Tous les statuts</option>
                    <option value="active">Actif</option>
                    <option value="inactive">Inactif</option>
                    <option value="pending">En attente</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" onclick="applyFilters()">
                    <i class="bi bi-funnel me-2"></i>Filtrer
                </button>
            </div>
        </div>
    </div>

    <!-- Cartes statistiques principales -->
    <div class="stats-grid mb-5">
        <div class="stat-card primary">
            <div class="stat-icon">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $customersCount }}</div>
                <div class="stat-label">Clients</div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up"></i> 12% ce mois
                </div>
            </div>
        </div>

        <div class="stat-card success">
            <div class="stat-icon">
                <i class="bi bi-person-badge"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $vendorsCount }}</div>
                <div class="stat-label">Vendeurs</div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up"></i> 8% ce mois
                </div>
            </div>
        </div>

        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $productsCount }}</div>
                <div class="stat-label">Produits</div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up"></i> 15% ce mois
                </div>
            </div>
        </div>

        <div class="stat-card danger">
            <div class="stat-icon">
                <i class="bi bi-cart-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $ordersCount }}</div>
                <div class="stat-label">Commandes</div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up"></i> 23% ce mois
                </div>
            </div>
        </div>

        <div class="stat-card info">
            <div class="stat-icon">
                <i class="bi bi-tags"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $categoriesCount }}</div>
                <div class="stat-label">Catégories</div>
                <div class="stat-change neutral">
                    <i class="bi bi-dash"></i> Stable
                </div>
            </div>
        </div>

        <div class="stat-card secondary">
            <div class="stat-icon">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($revenue ?? 0, 0, ',', ' ') }} fcfa</div>
                <div class="stat-label">Chiffre d'affaires</div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up"></i> 18% ce mois
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et métriques -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-bar-chart me-2"></i>Statistiques des ventes
                    </h5>
                    <div class="card-actions">
                        <button class="btn btn-sm btn-outline-secondary" onclick="toggleChartType()">
                            <i class="bi bi-arrow-repeat"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-pie-chart me-2"></i>Répartition par catégorie
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableaux rapides -->
    <div class="row mb-5">
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-clock-history me-2"></i>Commandes récentes
                    </h5>
                    <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($order->total_amount, 0, ',', ' ') }} fcfa</td>
                                    <td>
                                        <span class="status-badge status-{{ $order->status }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-activity me-2"></i>Activité récente
                    </h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="activity-feed">
                        {{-- @foreach($recentActivities as $activity)
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="bi bi-{{ $activity->icon }}"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-text">{{ $activity->description }}</div>
                                <div class="activity-time">{{ $activity->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @endforeach --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Métriques supplémentaires -->
    <div class="row">
        <div class="col-md-4">
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-person-plus me-2"></i>Nouveaux utilisateurs
                    </h5>
                </div>
                <div class="card-body">
                    <div class="metric-value">{{ $newUsersCount ?? 0 }}</div>
                    <div class="metric-label">Ce mois</div>
                    <div class="progress mt-2">
                        {{-- <div class="progress-bar bg-success" style="width: {{ ($newUsersCount / max($customersCount, 1)) * 100 }}%"></div> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-cart-plus me-2"></i>Taux de conversion
                    </h5>
                </div>
                <div class="card-body">
                    <div class="metric-value">{{ $conversionRate ?? 0 }}%</div>
                    <div class="metric-label">Visites vers commandes</div>
                    <div class="progress mt-2">
                        <div class="progress-bar bg-info" style="width: {{ $conversionRate ?? 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-star me-2"></i>Note moyenne
                    </h5>
                </div>
                <div class="card-body">
                    <div class="metric-value">{{ $averageRating ?? '4.5' }}/5</div>
                    <div class="metric-label">Satisfaction clients</div>
                    <div class="stars mt-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= ($averageRating ?? 4.5) ? '-fill' : '' }} text-warning"></i>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notifications -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="successToast" class="toast" role="alert">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle me-2"></i>
            <strong class="me-auto">Succès</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">Action réalisée avec succès</div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les graphiques
    initCharts();
    
    // Afficher les données en temps réel
    updateRealTimeData();
    
    // Configurer les mises à jour automatiques
    setInterval(updateRealTimeData, 30000); // Mise à jour toutes les 30 secondes
});

function initCharts() {
    // Graphique des ventes
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Ventes (fcfa)',
                data: [1200000, 1900000, 1500000, 2200000, 1800000, 2500000, 3000000, 2800000, 3200000, 3500000, 4000000, 4500000],
                borderColor: '#1780d6',
                backgroundColor: 'rgba(23, 128, 214, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Ventes: ' + context.parsed.y.toLocaleString() + ' fcfa';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' fcfa';
                        }
                    }
                }
            }
        }
    });

    // Graphique des catégories
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['Vêtements', 'Accessoires', 'Chaussures', 'Bijoux', 'Autres'],
            datasets: [{
                data: [35, 25, 20, 15, 5],
                backgroundColor: [
                    '#1780d6',
                    '#f4a261',
                    '#e76f51',
                    '#28a745',
                    '#6c757d'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    window.salesChart = salesChart;
    window.categoryChart = categoryChart;
}

function toggleChartType() {
    const chart = window.salesChart;
    const newType = chart.config.type === 'line' ? 'bar' : 'line';
    chart.config.type = newType;
    chart.update();
    
    showToast('Graphique changé en ' + (newType === 'line' ? 'ligne' : 'barres'));
}

function applyFilters() {
    const search = document.getElementById('productSearch').value;
    const category = document.getElementById('categoryFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    // Simuler l'application des filtres
    const filters = {
        search: search,
        category: category,
        status: status
    };
    
    console.log('Filtres appliqués:', filters);
    showToast('Filtres appliqués avec succès');
    
    // Ici, vous ajouteriez un appel AJAX pour actualiser les données
}

function selectPeriod(period) {
    const periods = {
        'this_week': 'Cette Semaine',
        'last_week': 'Semaine dernière',
        'this_month': 'Ce mois',
        'last_month': 'Mois dernier',
        'this_quarter': 'Ce trimestre'
    };
    
    showToast('Période sélectionnée: ' + periods[period]);
    
    // Ici, vous ajouteriez la logique pour charger les données de la période sélectionnée
}

function shareDashboard() {
    // Simulation de partage
    if (navigator.share) {
        navigator.share({
            title: 'Tableau de Bord Bkassoua',
            text: 'Consultez les statistiques de Bkassoua',
            url: window.location.href
        });
    } else {
        showToast('Lien copié dans le presse-papier');
        // Fallback pour copier le lien
        navigator.clipboard.writeText(window.location.href);
    }
}

function exportDashboard() {
    showToast('Export du tableau de bord en cours...');
    // Simulation d'export
    setTimeout(() => {
        showToast('Tableau de bord exporté avec succès');
    }, 2000);
}

function updateRealTimeData() {
    // Simulation de mise à jour des données en temps réel
    const elements = document.querySelectorAll('.stat-value');
    elements.forEach(element => {
        const current = parseInt(element.textContent.replace(/\D/g, ''));
        const variation = Math.floor(Math.random() * 10) - 2; // Variation aléatoire
        const newValue = Math.max(0, current + variation);
        
        // Animation du changement
        element.style.transform = 'scale(1.1)';
        setTimeout(() => {
            element.style.transform = 'scale(1)';
        }, 300);
    });
}

function showToast(message, type = 'success') {
    const toast = new bootstrap.Toast(document.getElementById('successToast'));
    const toastBody = document.querySelector('#successToast .toast-body');
    
    toastBody.textContent = message;
    toast.show();
}

// Gestion des événements clavier
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey || e.metaKey) {
        switch(e.key) {
            case 'e':
                e.preventDefault();
                exportDashboard();
                break;
            case 'f':
                e.preventDefault();
                document.getElementById('productSearch').focus();
                break;
        }
    }
});
</script>

<style>
.dashboard-container {
    padding: 0;
}

.dashboard-header {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow);
}

.header-content {
    display: flex;
    justify-content: between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.dashboard-title {
    font-weight: 700;
    color: var(--dark);
    margin: 0;
    font-size: 2rem;
}

.dashboard-subtitle {
    color: var(--gray);
    margin: 0.5rem 0 0 0;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-wrap: wrap;
}

.action-group {
    display: flex;
    gap: 0.5rem;
}

.filters-section {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--shadow);
}

.search-box {
    position: relative;
}

.search-box i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray);
}

.search-box .form-control {
    padding-left: 2.5rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--shadow);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: var(--transition);
    border-left: 4px solid var(--primary);
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

.stat-card.info {
    border-left-color: var(--primary);
}

.stat-card.secondary {
    border-left-color: var(--gray);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-card .stat-icon {
    background: var(--primary);
}

.stat-card.success .stat-icon {
    background: var(--success);
}

.stat-card.warning .stat-icon {
    background: var(--warning);
}

.stat-card.danger .stat-icon {
    background: var(--danger);
}

.stat-card.info .stat-icon {
    background: var(--primary);
}

.stat-card.secondary .stat-icon {
    background: var(--gray);
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--dark);
    line-height: 1;
}

.stat-label {
    color: var(--gray);
    font-weight: 500;
    margin: 0.25rem 0;
}

.stat-change {
    font-size: 0.8rem;
    font-weight: 600;
}

.stat-change.positive {
    color: var(--success);
}

.stat-change.negative {
    color: var(--danger);
}

.stat-change.neutral {
    color: var(--gray);
}

.activity-feed {
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--gray-light);
    transition: var(--transition);
}

.activity-item:hover {
    background: var(--light);
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--primary-light);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-text {
    color: var(--dark);
    margin-bottom: 0.25rem;
}

.activity-time {
    color: var(--gray);
    font-size: 0.8rem;
}

.metric-value {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary);
    line-height: 1;
}

.metric-label {
    color: var(--gray);
    font-size: 0.9rem;
    margin-top: 0.5rem;
}

.stars {
    display: flex;
    gap: 0.1rem;
}

.status-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-pending {
    background: rgba(255, 193, 7, 0.1);
    color: var(--warning);
}

.status-completed {
    background: rgba(40, 167, 69, 0.1);
    color: var(--success);
}

.status-cancelled {
    background: rgba(220, 53, 69, 0.1);
    color: var(--danger);
}

.status-processing {
    background: rgba(23, 128, 214, 0.1);
    color: var(--primary);
}

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .header-actions {
        width: 100%;
        justify-content: space-between;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-card {
        flex-direction: column;
        text-align: center;
    }
    
    .dashboard-title {
        font-size: 1.5rem;
    }
}
</style>
@endsection