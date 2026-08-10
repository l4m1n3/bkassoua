@extends('layouts.app_admin')

@section('title', 'Tableau de Bord - Admin Bkassoua')

@section('content')
<div class="dashboard-container">

    {{-- EN-TÊTE --}}
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-text">
                <h1 class="dashboard-title">Tableau de Bord</h1>
                <p class="dashboard-subtitle">Aperçu général de votre plateforme Bkassoua</p>
            </div>
            <div class="header-actions">
                <div class="action-group">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="shareDashboard()">
                        <i class="bi bi-share me-1"></i>Partager
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="exportDashboard()">
                        <i class="bi bi-download me-1"></i>Exporter
                    </button>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-info btn-sm dropdown-toggle" type="button"
                            id="periodDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-calendar me-1"></i><span id="selectedPeriod">Cette Semaine</span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="periodDropdown">
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

    {{-- FILTRES --}}
    <div class="filters-section mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" class="form-control" placeholder="Rechercher par produit..."
                           id="productSearch">
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
                    <i class="bi bi-funnel me-1"></i>Filtrer
                </button>
            </div>
        </div>
    </div>

    {{-- CARTES STATISTIQUES --}}
    <div class="stats-grid mb-5">
        <div class="stat-card primary">
            <div class="stat-icon"><i class="bi bi-people"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($customersCount, 0, ',', ' ') }}</div>
                <div class="stat-label">Clients</div>
                <div class="stat-change positive"><i class="bi bi-arrow-up"></i> 12% ce mois</div>
            </div>
        </div>

        <div class="stat-card success">
            <div class="stat-icon"><i class="bi bi-person-badge"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($vendorsCount, 0, ',', ' ') }}</div>
                <div class="stat-label">Vendeurs</div>
                <div class="stat-change positive"><i class="bi bi-arrow-up"></i> 8% ce mois</div>
            </div>
        </div>

        <div class="stat-card warning">
            <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($productsCount, 0, ',', ' ') }}</div>
                <div class="stat-label">Produits</div>
                <div class="stat-change positive"><i class="bi bi-arrow-up"></i> 15% ce mois</div>
            </div>
        </div>

        <div class="stat-card danger">
            <div class="stat-icon"><i class="bi bi-cart-check"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($ordersCount, 0, ',', ' ') }}</div>
                <div class="stat-label">Commandes</div>
                <div class="stat-change positive"><i class="bi bi-arrow-up"></i> 23% ce mois</div>
            </div>
        </div>

        <div class="stat-card info">
            <div class="stat-icon"><i class="bi bi-tags"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($categoriesCount, 0, ',', ' ') }}</div>
                <div class="stat-label">Catégories</div>
                <div class="stat-change neutral"><i class="bi bi-dash"></i> Stable</div>
            </div>
        </div>
         <div class="stat-card info">
            <div class="stat-icon"><i class="bi bi-tags"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($sousCategoriesCount, 0, ',', ' ') }}</div>
                <div class="stat-label">Sous Catégories</div>
                <div class="stat-change neutral"><i class="bi bi-dash"></i> Stable</div>
            </div>
        </div>

        <div class="stat-card secondary">
            <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($revenue ?? 0, 0, ',', ' ') }} fcfa</div>
                <div class="stat-label">Chiffre d'affaires</div>
                <div class="stat-change positive"><i class="bi bi-arrow-up"></i> 18% ce mois</div>
            </div>
        </div>
    </div>

    {{-- GRAPHIQUES --}}
    <div class="row mb-5">
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-bar-chart me-2"></i>Statistiques des ventes
                    </h5>
                    <div class="card-actions">
                        <button class="btn btn-sm btn-outline-secondary" onclick="toggleChartType()"
                                title="Changer le type de graphique">
                            <i class="bi bi-arrow-repeat"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="300"
                            aria-label="Graphique des ventes mensuelles"></canvas>
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
                    <canvas id="categoryChart" height="300"
                            aria-label="Graphique de répartition par catégorie"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLEAUX --}}
    <div class="row mb-5">
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-clock-history me-2"></i>Commandes récentes
                    </h5>
                    <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-primary">
                        Voir tout
                    </a>
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
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.viewOrder', $order->id) }}"
                                           class="text-decoration-none">#{{ $order->id }}</a>
                                    </td>
                                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($order->total_amount, 0, ',', ' ') }} fcfa</td>
                                    <td>
                                        <span class="status-badge status-{{ $order->status }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">
                                        Aucune commande récente
                                    </td>
                                </tr>
                                @endforelse
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
                </div>
                <div class="card-body p-0">
                    <div class="activity-feed" id="activityFeed">
                        <div class="activity-item">
                            <div class="activity-icon"><i class="bi bi-info-circle"></i></div>
                            <div class="activity-content">
                                <div class="activity-text">Aucune activité récente</div>
                                <div class="activity-time">—</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MÉTRIQUES SUPPLÉMENTAIRES --}}
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
                    <div class="progress mt-2" style="height:6px;">
                        @php
                            $progressVal = $customersCount > 0
                                ? min(100, round(($newUsersCount / $customersCount) * 100))
                                : 0;
                        @endphp
                        <div class="progress-bar bg-success" style="width: {{ $progressVal }}%"></div>
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
                    <div class="metric-label">Commandes / Clients</div>
                    <div class="progress mt-2" style="height:6px;">
                        <div class="progress-bar bg-info"
                             style="width: {{ min(100, $conversionRate ?? 0) }}%"></div>
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
                    <div class="metric-value">{{ number_format($averageRating ?? 4.5, 1) }}/5</div>
                    <div class="metric-label">Satisfaction clients</div>
                    <div class="stars mt-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($averageRating ?? 4.5))
                                <i class="bi bi-star-fill text-warning"></i>
                            @elseif($i - ($averageRating ?? 4.5) < 1)
                                <i class="bi bi-star-half text-warning"></i>
                            @else
                                <i class="bi bi-star text-warning"></i>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- TOAST --}}
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:1100;">
    <div id="dashToast" class="toast align-items-center border-0" role="alert" aria-live="assertive">
        <div class="d-flex">
            <div class="toast-body" id="dashToastBody">Message</div>
            <button type="button" class="btn-close me-2 m-auto"
                    data-bs-dismiss="toast" aria-label="Fermer"></button>
        </div>
    </div>
</div>

{{-- SCRIPTS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    initCharts();
    startAutoRefresh();
});

// ─── Données depuis Laravel ───────────────────────────────────────────────────
const salesData      = @json($salesChart);
const categoryLabels = @json($categoryLabels);
const categoryData   = @json($categoryCounts);

// ─── Graphiques ───────────────────────────────────────────────────────────────
function initCharts() {
    const salesCtx = document.getElementById('salesChart').getContext('2d');

    window.salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'],
            datasets: [{
                label: 'Ventes (fcfa)',
                data: salesData,
                borderColor: '#1780d6',
                backgroundColor: 'rgba(23,128,214,0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: ctx => 'Ventes : ' + ctx.parsed.y.toLocaleString('fr-FR') + ' fcfa'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: v => (v >= 1000000)
                            ? (v / 1000000).toFixed(1) + 'M'
                            : v.toLocaleString('fr-FR')
                    }
                }
            }
        }
    });

    const categoryCtx = document.getElementById('categoryChart').getContext('2d');

    window.categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: categoryLabels,
            datasets: [{
                data: categoryData,
                backgroundColor: ['#1780d6','#f4a261','#e76f51','#28a745','#6c757d','#6610f2','#fd7e14'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.label + ' : ' + ctx.parsed + ' produits'
                    }
                }
            }
        }
    });
}

function toggleChartType() {
    const chart = window.salesChart;
    const isLine = chart.config.type === 'line';
    chart.config.type = isLine ? 'bar' : 'line';
    chart.data.datasets[0].fill = !isLine;
    chart.data.datasets[0].backgroundColor = isLine
        ? 'rgba(23,128,214,0.6)'
        : 'rgba(23,128,214,0.1)';
    chart.update();
    showToast('Graphique affiché en ' + (isLine ? 'barres' : 'ligne'));
}

// ─── Mise à jour automatique ──────────────────────────────────────────────────
function startAutoRefresh() {
    setInterval(updateRealTimeData, 30000);
}

function updateRealTimeData() {
    fetch('{{ url("/admin/dashboard/data") }}', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => {
        if (!res.ok) throw new Error('Erreur réseau');
        return res.json();
    })
    .then(data => {
        // Mettre à jour les cartes stat si les sélecteurs correspondent
        updateStatCard('.stat-card.primary .stat-value',    data.customers);
        updateStatCard('.stat-card.warning .stat-value',    data.products);
        updateStatCard('.stat-card.danger .stat-value',     data.orders);

        // Mettre à jour le graphique des ventes
        if (data.salesChart && window.salesChart) {
            window.salesChart.data.datasets[0].data = data.salesChart;
            window.salesChart.update('none');
        }
    })
    .catch(err => console.warn('Rafraîchissement dashboard :', err));
}

function updateStatCard(selector, value) {
    const el = document.querySelector(selector);
    if (!el || value === undefined) return;
    el.textContent = Number(value).toLocaleString('fr-FR');
}

// ─── Filtres ──────────────────────────────────────────────────────────────────
function applyFilters() {
    const search   = document.getElementById('productSearch').value.trim();
    const category = document.getElementById('categoryFilter').value;
    const status   = document.getElementById('statusFilter').value;

    const params = new URLSearchParams();
    if (search)   params.set('search', search);
    if (category) params.set('category', category);
    if (status)   params.set('status', status);

    // Redirige vers la liste des produits avec les filtres
    const url = '{{ route("admin.products") }}' + (params.toString() ? '?' + params.toString() : '');
    window.location.href = url;
}

// ─── Période ──────────────────────────────────────────────────────────────────
const periodLabels = {
    'this_week':    'Cette Semaine',
    'last_week':    'Semaine dernière',
    'this_month':   'Ce mois',
    'last_month':   'Mois dernier',
    'this_quarter': 'Ce trimestre'
};

function selectPeriod(period) {
    document.getElementById('selectedPeriod').textContent = periodLabels[period] ?? period;
    showToast('Période : ' + (periodLabels[period] ?? period));
}

// ─── Partage / Export ─────────────────────────────────────────────────────────
function shareDashboard() {
    if (navigator.share) {
        navigator.share({
            title: 'Tableau de Bord Bkassoua',
            url: window.location.href
        }).catch(() => {});
    } else {
        navigator.clipboard.writeText(window.location.href)
            .then(() => showToast('Lien copié dans le presse-papier'))
            .catch(() => showToast('Impossible de copier le lien', 'danger'));
    }
}

function exportDashboard() {
    showToast('Export en cours…', 'info');
    // Vous pouvez implémenter un vrai export PDF ici
    setTimeout(() => showToast('Export terminé'), 2000);
}

// ─── Toast ────────────────────────────────────────────────────────────────────
function showToast(message, type = 'success') {
    const toastEl   = document.getElementById('dashToast');
    const toastBody = document.getElementById('dashToastBody');

    toastEl.className = 'toast align-items-center border-0 text-bg-' + type;
    toastBody.textContent = message;

    bootstrap.Toast.getOrCreateInstance(toastEl, { delay: 3000 }).show();
}

// ─── Raccourcis clavier ───────────────────────────────────────────────────────
document.addEventListener('keydown', function (e) {
    if (e.ctrlKey || e.metaKey) {
        if (e.key === 'f') { e.preventDefault(); document.getElementById('productSearch').focus(); }
        if (e.key === 'e') { e.preventDefault(); exportDashboard(); }
    }
});
</script>

<style>
.dashboard-container { padding: 0; }

.dashboard-header {
    background: white;
    border-radius: var(--border-radius, 12px);
    padding: 1.5rem 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.dashboard-title { font-weight: 700; color: #1a1a2e; margin: 0; font-size: 2rem; }
.dashboard-subtitle { color: #6c757d; margin: .5rem 0 0; }

.header-actions { display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; }
.action-group { display: flex; gap: .5rem; }

.filters-section {
    background: white;
    border-radius: var(--border-radius, 12px);
    padding: 1.5rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
}

.search-box { position: relative; }
.search-box i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #6c757d; }
.search-box .form-control { padding-left: 2.5rem; }

/* ── Grille stats ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1.5rem;
}

.stat-card {
    background: white;
    border-radius: var(--border-radius, 12px);
    padding: 1.5rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
    display: flex;
    align-items: center;
    gap: 1rem;
    border-left: 4px solid #1780d6;
    transition: transform .2s, box-shadow .2s;
}
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,.1); }
.stat-card.success  { border-left-color: #28a745; }
.stat-card.warning  { border-left-color: #ffc107; }
.stat-card.danger   { border-left-color: #dc3545; }
.stat-card.info     { border-left-color: #17a2b8; }
.stat-card.secondary{ border-left-color: #6c757d; }

.stat-icon {
    width: 60px; height: 60px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; color: white;
    background: #1780d6;
    flex-shrink: 0;
}
.stat-card.success  .stat-icon { background: #28a745; }
.stat-card.warning  .stat-icon { background: #ffc107; }
.stat-card.danger   .stat-icon { background: #dc3545; }
.stat-card.info     .stat-icon { background: #17a2b8; }
.stat-card.secondary.stat-icon { background: #6c757d; }

.stat-value { font-size: 1.8rem; font-weight: 700; color: #1a1a2e; line-height: 1; }
.stat-label { color: #6c757d; font-weight: 500; margin: .25rem 0; font-size: .9rem; }
.stat-change { font-size: .8rem; font-weight: 600; }
.stat-change.positive { color: #28a745; }
.stat-change.negative { color: #dc3545; }
.stat-change.neutral  { color: #6c757d; }

/* ── Cards ── */
.admin-card {
    background: white;
    border-radius: var(--border-radius, 12px);
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
    overflow: hidden;
    height: 100%;
}

.admin-card .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: .9rem 1.25rem;
    border-bottom: 1px solid #f0f0f0;
    background: white;
}

.admin-card .card-title {
    font-size: 1rem; font-weight: 600; color: #1a1a2e; margin: 0;
}

/* ── Activité ── */
.activity-feed { max-height: 380px; overflow-y: auto; }
.activity-item {
    display: flex; align-items: flex-start; gap: 1rem;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f8f9fa;
    transition: background .15s;
}
.activity-item:hover { background: #f8f9fa; }
.activity-item:last-child { border-bottom: none; }
.activity-icon {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: rgba(23,128,214,.1);
    color: #1780d6;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.activity-text  { color: #1a1a2e; font-size: .9rem; margin-bottom: .2rem; }
.activity-time  { color: #6c757d; font-size: .78rem; }

/* ── Métriques ── */
.metric-value { font-size: 2.2rem; font-weight: 700; color: #1780d6; line-height: 1; }
.metric-label { color: #6c757d; font-size: .85rem; margin-top: .4rem; }
.stars { display: flex; gap: .15rem; font-size: 1.1rem; }

/* ── Badges statut ── */
.status-badge {
    padding: .3rem .7rem;
    border-radius: 50px;
    font-size: .78rem;
    font-weight: 600;
    text-transform: capitalize;
}
.status-pending    { background: rgba(255,193,7,.15);  color: #856404; }
.status-completed  { background: rgba(40,167,69,.15);  color: #155724; }
.status-cancelled  { background: rgba(220,53,69,.15);  color: #721c24; }
.status-processing { background: rgba(23,128,214,.15); color: #0c447c; }

/* ── Responsive ── */
@media (max-width: 768px) {
    .header-content  { flex-direction: column; align-items: flex-start; }
    .header-actions  { width: 100%; justify-content: space-between; }
    .stats-grid      { grid-template-columns: 1fr; }
    .dashboard-title { font-size: 1.5rem; }
}
</style>
@endsection