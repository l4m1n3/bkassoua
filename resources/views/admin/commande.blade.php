@extends('layouts.app_admin')

@section('title', 'Gestion des Commandes - Admin Bkassoua')

@section('content')
<div class="orders-container">

    <!-- En-tête -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-text">
                <h1 class="page-title">Gestion des Commandes</h1>
                <p class="page-subtitle">Suivez et gérez toutes les commandes de votre plateforme</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="exportOrders()">
                    <i class="bi bi-download me-2"></i>Exporter
                </button>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filtersModal">
                    <i class="bi bi-funnel me-2"></i>Filtres avancés
                </button>
            </div>
        </div>
    </div>

    <!-- Filtres rapides -->
    <div class="filters-section mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" class="form-control" placeholder="Rechercher une commande..."
                           id="searchInput" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="statusFilter" onchange="applyFilters()">
                    <option value="">Tous les statuts</option>
                    <option value="pending"    {{ request('status') == 'pending'    ? 'selected' : '' }}>En attente</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>En traitement</option>
                    <option value="shipped"    {{ request('status') == 'shipped'    ? 'selected' : '' }}>Expédiée</option>
                    <option value="delivered"  {{ request('status') == 'delivered'  ? 'selected' : '' }}>Livrée</option>
                    <option value="cancelled"  {{ request('status') == 'cancelled'  ? 'selected' : '' }}>Annulée</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="paymentFilter" onchange="applyFilters()">
                    <option value="">Tous les paiements</option>
                    <option value="paid"    {{ request('payment') == 'paid'    ? 'selected' : '' }}>Payé</option>
                    <option value="pending" {{ request('payment') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="failed"  {{ request('payment') == 'failed'  ? 'selected' : '' }}>Échoué</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                    <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon bg-primary"><i class="bi bi-cart"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalOrders ?? 0 }}</div>
                <div class="stat-label">Total commandes</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-warning"><i class="bi bi-clock"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $pendingOrders ?? 0 }}</div>
                <div class="stat-label">En attente</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-info"><i class="bi bi-truck"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $processingOrders ?? 0 }}</div>
                <div class="stat-label">En traitement</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-success"><i class="bi bi-check-circle"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $deliveredOrders ?? 0 }}</div>
                <div class="stat-label">Livrées</div>
            </div>
        </div>
    </div>

    <!-- Tableau des commandes -->
    <div class="admin-card">
        <div class="card-header">
            <h5 class="card-title"><i class="bi bi-list-ul me-2"></i>Liste des commandes</h5>
            <div class="card-actions">
                <span class="text-muted">{{ $orders->total() }} commande(s) trouvée(s)</span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($orders->isEmpty())
                <div class="empty-state text-center py-5">
                    <i class="bi bi-cart-x display-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">Aucune commande trouvée</h4>
                    <p class="text-muted mb-4">Aucune commande ne correspond à vos critères de recherche.</p>
                    <button class="btn btn-primary" onclick="resetFilters()">
                        <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser les filtres
                    </button>
                </div>
            @else
             <div class="toast-container position-fixed top-0 end-0 p-3" id="toastContainer" style="z-index: 9999;"></div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="ordersTable">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>Commande</th>
                                <th>Client</th>
                                <th>Vendeur</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Paiement</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                           <tbody id="ordersBody">
                            @include('admin.order_rows')
                        </tbody>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if(!$orders->isEmpty())
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAllFooter">
                    <label class="form-check-label" for="selectAllFooter">Sélectionner tout</label>
                </div>
                <div class="bulk-actions">
                    <select class="form-select form-select-sm me-2" id="bulkAction" style="width: auto;">
                        <option value="">Actions groupées</option>
                        <option value="processing">Marquer comme en traitement</option>
                        <option value="shipped">Marquer comme expédiée</option>
                        <option value="delivered">Marquer comme livrée</option>
                        <option value="cancelled">Annuler</option>
                    </select>
                    <button class="btn btn-sm btn-primary" onclick="applyBulkAction()">Appliquer</button>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="pagination-section mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Affichage de {{ $orders->firstItem() }} à {{ $orders->lastItem() }} sur {{ $orders->total() }} commandes
            </div>
            <nav>{{ $orders->links() }}</nav>
        </div>
    </div>
    @endif
</div>

{{-- ══════════════════════════════════════════════════════════════════
     MODALS DÉTAIL COMMANDE
══════════════════════════════════════════════════════════════════ --}}
@foreach ($orders as $order)
<div class="modal fade" id="orderDetailModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-receipt me-2"></i>Détails de la commande #{{ $order->id }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row">

                    {{-- Informations client --}}
                    <div class="col-md-6">
                        <div class="info-section">
                            <h6 class="section-title">
                                <i class="bi bi-person me-2"></i>Informations client
                            </h6>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Nom complet</label>
                                    <div>{{ $order->user->name }}</div>
                                </div>
                                <div class="info-item">
                                    <label>Email</label>
                                    <div>{{ $order->user->email }}</div>
                                </div>
                                <div class="info-item">
                                    <label>Téléphone</label>
                                    <div>{{ $order->user->phone_number ?? 'Non renseigné' }}</div>
                                </div>
                                <div class="info-item">
                                    <label>Région</label>
                                    <div>{{ $order->user?->address ?? 'Non renseignée' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Informations commande + paiement --}}
                    <div class="col-md-6">
                        <div class="info-section">
                            <h6 class="section-title">
                                <i class="bi bi-info-circle me-2"></i>Informations commande
                            </h6>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Date</label>
                                    <div>{{ $order->created_at->format('d/m/Y à H:i') }}</div>
                                </div>
                                <div class="info-item">
                                    <label>Statut</label>
                                    <div>
                                        <span class="status-badge status-{{ $order->status }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <label>Sous-total</label>
                                    <div>{{ number_format($order->subtotal ?? 0, 0, ',', ' ') }} fcfa</div>
                                </div>
                                <div class="info-item">
                                    <label>Livraison</label>
                                    <div>{{ number_format($order->shipping_cost ?? 0, 0, ',', ' ') }} fcfa</div>
                                </div>
                                <div class="info-item">
                                    <label>Total</label>
                                    <div class="amount-large">{{ number_format($order->payment->amount, 0, ',', ' ') }} fcfa</div>
                                </div>
                            </div>
                        </div>

                        @if($order->payment)
                        <div class="info-section mt-3">
                            <h6 class="section-title">
                                <i class="bi bi-credit-card me-2"></i>Paiement
                            </h6>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Statut</label>
                                    <div>
                                        <span class="payment-badge payment-{{ $order->payment->status }}">
                                            {{ ucfirst($order->payment->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <label>Méthode</label>
                                    <div>{{ $order->payment->payment_method ?? 'Non spécifiée' }}</div>
                                </div>
                                <div class="info-item">
                                    <label>Date</label>
                                    <div>{{ $order->payment->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- ── Articles commandés ── --}}
                <div class="info-section mt-4">
                    <h6 class="section-title">
                        <i class="bi bi-box-seam me-2"></i>
                        Articles commandés
                        <span class="badge bg-primary ms-2">{{ $order->items->count() }}</span>
                    </h6>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width:220px">Produit</th>
                                    <th style="min-width:180px">Attributs choisis</th>
                                    <th class="text-center">Prix unitaire</th>
                                    <th class="text-center">Qté</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                <tr>
                                    {{-- Produit --}}
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            {{-- Miniature --}}
                                            @php
                                                $mainImage = $item->product?->images?->firstWhere('is_main', true)
                                                          ?? $item->product?->images?->first();
                                            @endphp
                                            @if($mainImage)
                                                <img src="{{ asset('public/storage/' . $mainImage->path) }}"
                                                     alt="{{ $item->product->name }}"
                                                     class="product-thumb">
                                            @else
                                                <div class="product-thumb-placeholder">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-600">
                                                    {{ $item->product?->name ?? 'Produit supprimé' }}
                                                </div>
                                                <small class="text-muted">
                                                    Réf : {{ $item->product?->id ?? '—' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- ✅ Attributs sélectionnés --}}
                                    <td>
                                        @if($item->resolved_options && $item->resolved_options->isNotEmpty())
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($item->resolved_options as $option)
                                                    <span class="attribute-badge">
                                                        @if(!empty($option['attribute']))
                                                            <span class="attr-name">{{ $option['attribute'] }}</span>
                                                            <span class="attr-sep">:</span>
                                                        @endif
                                                        <span class="attr-value">{{ $option['value'] }}</span>
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="no-attr">
                                                <i class="bi bi-dash-circle me-1"></i>Aucun attribut
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        {{ number_format($item->price, 0, ',', ' ') }} fcfa
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary rounded-pill">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-600 text-primary">
                                        {{ number_format($item->quantity * $item->price, 0, ',', ' ') }} fcfa
                                    </td>
                                    
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan="4" class="text-end fw-600">Total commande :</td>
                                    <td class="text-end fw-600 text-primary">
                                        {{ number_format($order->total_amount, 0, ',', ' ') }} fcfa
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            {{-- fin modal-body --}}

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>

                @if($order->status == 'pending')
                    @if($order->payment && $order->payment->status === 'pending')
                        <button type="button" class="btn btn-success" onclick="validatePayment({{ $order->id }})">
                            <i class="bi bi-check-lg me-2"></i>Valider le paiement
                        </button>
                    @endif
                    <button type="button" class="btn btn-danger" onclick="cancelOrder({{ $order->id }})">
                        <i class="bi bi-x-lg me-2"></i>Annuler la commande
                    </button>
                @endif

                @if($order->status == 'processing')
                    <button type="button" class="btn btn-info" onclick="updateOrderStatus({{ $order->id }}, 'shipped')">
                        <i class="bi bi-truck me-2"></i>Marquer comme expédiée
                    </button>
                @endif

                @if($order->status == 'shipped')
                    <button type="button" class="btn btn-success" onclick="updateOrderStatus({{ $order->id }}, 'delivered')">
                        <i class="bi bi-check-circle me-2"></i>Marquer comme livrée
                    </button>
                @endif
            </div>

        </div>
    </div>
</div>
@endforeach

{{-- ══════════════════════════════════════════════════════════════════
     MODAL FILTRES AVANCÉS
══════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="filtersModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filtres avancés</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="advancedFilters">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Date de début</label>
                            <input type="date" class="form-control" name="start_date"
                                   value="{{ request('start_date') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Date de fin</label>
                            <input type="date" class="form-control" name="end_date"
                                   value="{{ request('end_date') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Montant minimum</label>
                            <input type="number" class="form-control" name="min_amount"
                                   placeholder="0" value="{{ request('min_amount') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Montant maximum</label>
                            <input type="number" class="form-control" name="max_amount"
                                   placeholder="1000000" value="{{ request('max_amount') }}">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="applyAdvancedFilters()">Appliquer</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const token = document.querySelector('meta[name="csrf-token"]').content;

    function csrfFetch(url, options = {}) {
        return fetch(url, {
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
            ...options,
        }).then(res => res.json());
    }
     // ── commande pooling 5 secondes ──────────────────────────────────────────────────────
     
      let lastId = {{ $orders->max('id') ?? 0 }};
    const ordersBody = document.querySelector('#ordersBody');

    // ====================== FETCH AUTOMATIQUE ======================
    function fetchOrders() {
        // console.log("🔄 Fetch... Last ID:", lastId);

        fetch(`orders/latest?last_id=${lastId}`)
            .then(res => res.json())
            .then(data => {
                // console.log("📦 DATA:", data);

                if (!data.html || data.html.trim() === '') return;

                const temp = document.createElement('tbody');
                temp.innerHTML = data.html;

                const rows = temp.querySelectorAll('tr');
                let newOrderCount = 0;

                rows.forEach(row => {
                    if (!document.getElementById(row.id)) {
                        ordersBody.prepend(row);
                        newOrderCount++;
                    }
                });

                // 🔔 Toast si nouvelles commandes
                if (newOrderCount > 0) {
                    showToast(`${newOrderCount} nouvelle(s) commande(s) reçue(s) 🛒`);
                }

                // 🔁 mise à jour lastId
                if (data.last_id) {
                    lastId = data.last_id;
                    // console.log("✅ lastId updated:", lastId);
                }
            })
            .catch(err => console.error(err));
    }

    setInterval(fetchOrders, 5000);

// ====================== AJOUT DYNAMIQUE DE LA NOUVELLE COMMANDE ======================
function prependNewOrderRow(order) {
    const tbody = document.querySelector('#ordersBody');
    if (!tbody) return;

    const row = document.createElement('tr');
    row.classList.add('order-row');
    row.id = `order-${order.id}`;
    row.setAttribute('data-order-id', order.id);

    row.innerHTML = `
        <td>
            <div class="form-check">
                <input class="form-check-input order-checkbox" type="checkbox" value="${order.id}">
            </div>
        </td>
        <td>
            <div class="order-info">
                <div class="order-id">#${order.id}</div>
                <small class="text-muted">${order.reference || 'CMD-' + order.id}</small>
            </div>
        </td>
        <td>
            <div class="customer-info">
                <div class="customer-name">${order.customer || 'Client inconnu'}</div>
            </div>
        </td>
        <td>
            <div class="amount">${formatMoney(order.total)} fcfa</div>
        </td>
        <td>
            <span class="status-badge status-${order.status}">
                <i class="bi bi-circle-fill me-1"></i>
                ${capitalize(order.status)}
            </span>
        </td>
        <td>
            <span class="payment-badge payment-${order.paymentStatus || 'pending'}">
                <i class="bi bi-${order.paymentStatus === 'paid' ? 'check-circle' : 'clock'} me-1"></i>
                ${capitalize(order.paymentStatus || 'En attente')}
            </span>
        </td>
        <td>
            <div class="date-info">
                <div>${new Date(order.created_at).toLocaleDateString('fr-FR')}</div>
                <small class="text-muted">${new Date(order.created_at).toLocaleTimeString('fr-FR', {hour:'2-digit', minute:'2-digit'})}</small>
            </div>
        </td>
        <td>
            <div class="action-buttons">
                <button class="btn btn-sm btn-outline-primary" 
                        onclick="viewOrder(${order.id})" title="Voir détails">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </td>
    `;

    tbody.prepend(row);
}

    // ── Filtres rapides ──────────────────────────────────────────────────────
    window.applyFilters = function () {
        const params = new URLSearchParams({
            search:  document.getElementById('searchInput').value,
            status:  document.getElementById('statusFilter').value,
            payment: document.getElementById('paymentFilter').value,
        });
        window.location.href = '?' + params.toString();
    };

    document.getElementById('searchInput')
        ?.addEventListener('keydown', e => { if (e.key === 'Enter') applyFilters(); });

    // ── Filtres avancés ──────────────────────────────────────────────────────
    window.applyAdvancedFilters = function () {
        const form   = document.getElementById('advancedFilters');
        const data   = new FormData(form);
        const params = new URLSearchParams({
            search:     document.getElementById('searchInput').value,
            status:     document.getElementById('statusFilter').value,
            payment:    document.getElementById('paymentFilter').value,
            start_date: data.get('start_date') ?? '',
            end_date:   data.get('end_date')   ?? '',
            min_amount: data.get('min_amount') ?? '',
            max_amount: data.get('max_amount') ?? '',
        });
        window.location.href = '?' + params.toString();
        bootstrap.Modal.getInstance(document.getElementById('filtersModal'))?.hide();
    };

    // ── Réinitialiser ────────────────────────────────────────────────────────
    window.resetFilters = function () {
        window.location.href = window.location.pathname;
    };

    // ── Export ───────────────────────────────────────────────────────────────
    window.exportOrders = function () {
        window.location.href = '/admin/orders/export?' + new URLSearchParams({
            search:  document.getElementById('searchInput').value,
            status:  document.getElementById('statusFilter').value,
            payment: document.getElementById('paymentFilter').value,
        }).toString();
    };

    // ── Sélectionner tout ────────────────────────────────────────────────────
    document.getElementById('selectAll')?.addEventListener('change', function () {
        document.querySelectorAll('.order-checkbox')
            .forEach(cb => cb.checked = this.checked);
    });

    document.getElementById('selectAllFooter')?.addEventListener('change', function () {
        document.querySelectorAll('.order-checkbox')
            .forEach(cb => cb.checked = this.checked);
        document.getElementById('selectAll').checked = this.checked;
    });

    // ── Annuler une commande ─────────────────────────────────────────────────
    window.cancelOrder = function (id) {
        if (!confirm('Annuler cette commande ?')) return;
        csrfFetch(`/admin/orders/${id}/cancel`, { method: 'POST' })
            .then(r => {
                showToast(r.message, r.success ? 'success' : 'error');
                if (r.success) location.reload();
            });
    };

    // ── Valider paiement ─────────────────────────────────────────────────────
    window.validatePayment = function (id) {
        if (!confirm('Valider cette commande ?')) return;
        csrfFetch(`/admin/orders/${id}/validate-payment`, { method: 'POST' })
            .then(r => {
                showToast(r.message, r.success ? 'success' : 'error');
                if (r.success) location.reload();
            });
    };

    // ── Changer statut ───────────────────────────────────────────────────────
    window.updateOrderStatus = function (orderId, status) {
        if (!confirm('Confirmer ce changement de statut ?')) return;
        csrfFetch(`/admin/orders/${orderId}/status`, {
            method: 'POST',
            body: JSON.stringify({ status }),
        })
        .then(r => {
            showToast(r.message, r.success ? 'success' : 'error');
            if (r.success) location.reload();
        })
        .catch(() => showToast('Erreur serveur', 'error'));
    };

    // ── Actions groupées ─────────────────────────────────────────────────────
    window.applyBulkAction = function () {
        const ids    = [...document.querySelectorAll('.order-checkbox:checked')].map(cb => cb.value);
        const action = document.getElementById('bulkAction').value;

        if (!ids.length || !action) {
            showToast('Sélection ou action manquante', 'warning');
            return;
        }

        csrfFetch('/admin/orders/bulk-action', {
            method: 'POST',
            body: JSON.stringify({ ids, action }),
        }).then(r => {
            showToast(r.message, r.success ? 'success' : 'error');
            if (r.success) location.reload();
        });
    };
});

// ====================== TOAST BOOTSTRAP ======================
function showToast(message) {
    const toastContainer = document.getElementById('toastContainer');
    const toastId = 'toast-' + Date.now();

    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-bg-success border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;

    toastContainer.insertAdjacentHTML('beforeend', toastHtml);

    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, { delay: 3500 });
    toast.show();

    toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());
}
</script>

<style>
/* ── Layout ──────────────────────────────────────────────────────────────── */
.orders-container { padding: 0; }

.page-header {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow);
}
.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}
.page-title   { font-weight:700; color:var(--dark); margin:0; font-size:2rem; }
.page-subtitle{ color:var(--gray); margin:.5rem 0 0; }
.header-actions { display:flex; gap:1rem; align-items:center; }

/* ── Filtres ─────────────────────────────────────────────────────────────── */
.filters-section {
    background:white;
    border-radius:var(--border-radius);
    padding:1.5rem;
    box-shadow:var(--shadow);
    margin-bottom:2rem;
}
.search-box { position:relative; }
.search-box i { position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--gray); }
.search-box .form-control { padding-left:2.5rem; }

/* ── Stats ───────────────────────────────────────────────────────────────── */
.stats-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(200px,1fr));
    gap:1rem;
    margin-bottom:2rem;
}
.stat-card {
    background:white;
    border-radius:var(--border-radius);
    padding:1.5rem;
    box-shadow:var(--shadow);
    display:flex;
    align-items:center;
    gap:1rem;
    transition:var(--transition);
}
.stat-card:hover { transform:translateY(-2px); box-shadow:0 5px 15px rgba(0,0,0,.1); }
.stat-icon {
    width:50px; height:50px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    color:white; font-size:1.25rem;
}
.stat-value { font-size:1.75rem; font-weight:700; color:var(--dark); line-height:1; }
.stat-label { color:var(--gray); font-size:.9rem; margin-top:.25rem; }

/* ── Tableau ─────────────────────────────────────────────────────────────── */
.order-row:hover { background-color:var(--light); }
.order-info .order-id { font-weight:600; color:var(--dark); }
.customer-info .customer-name { font-weight:500; color:var(--dark); }
.amount { font-weight:600; color:var(--dark); }
.fw-600 { font-weight:600; }

/* ── Statut badges ───────────────────────────────────────────────────────── */
.status-badge {
    padding:.35rem .75rem;
    border-radius:50px;
    font-size:.8rem; font-weight:600;
    display:inline-flex; align-items:center;
}
.status-pending    { background:rgba(255,193,7,.1);  color:var(--warning); border:1px solid rgba(255,193,7,.2); }
.status-processing { background:rgba(23,128,214,.1); color:var(--primary); border:1px solid rgba(23,128,214,.2); }
.status-shipped    { background:rgba(111,66,193,.1); color:#6f42c1;        border:1px solid rgba(111,66,193,.2); }
.status-delivered  { background:rgba(40,167,69,.1);  color:var(--success); border:1px solid rgba(40,167,69,.2); }
.status-cancelled  { background:rgba(220,53,69,.1);  color:var(--danger);  border:1px solid rgba(220,53,69,.2); }

/* ── Paiement badges ─────────────────────────────────────────────────────── */
.payment-badge {
    padding:.35rem .75rem;
    border-radius:50px;
    font-size:.8rem; font-weight:600;
    display:inline-flex; align-items:center;
}
.payment-paid    { background:rgba(40,167,69,.1);  color:var(--success); border:1px solid rgba(40,167,69,.2); }
.payment-pending { background:rgba(255,193,7,.1);  color:var(--warning); border:1px solid rgba(255,193,7,.2); }
.payment-failed  { background:rgba(220,53,69,.1);  color:var(--danger);  border:1px solid rgba(220,53,69,.2); }
.payment-none    { background:rgba(108,117,125,.1);color:var(--gray);    border:1px solid rgba(108,117,125,.2); }

/* ── Actions ─────────────────────────────────────────────────────────────── */
.action-buttons { display:flex; gap:.5rem; flex-wrap:wrap; }
.action-buttons .btn { padding:.25rem .5rem; }
.bulk-actions { display:flex; align-items:center; gap:.5rem; }
.date-info { font-size:.9rem; }

/* ── Modale info ─────────────────────────────────────────────────────────── */
.info-section { margin-bottom:2rem; }
.section-title {
    font-weight:600; color:var(--dark);
    margin-bottom:1rem; padding-bottom:.5rem;
    border-bottom:2px solid var(--gray-light);
    display:flex; align-items:center; gap:.5rem;
}
.info-grid { display:grid; gap:1rem; }
.info-item {
    display:flex; justify-content:space-between;
    padding:.5rem 0;
    border-bottom:1px solid var(--gray-light);
}
.info-item:last-child { border-bottom:none; }
.info-item label { font-weight:600; color:var(--dark); min-width:120px; }
.info-item div   { color:var(--gray); text-align:right; flex:1; }
.amount-large    { font-size:1.5rem; font-weight:700; color:var(--primary); }

/* ── Miniature produit ───────────────────────────────────────────────────── */
.product-thumb {
    width:52px; height:52px;
    object-fit:cover;
    border-radius:8px;
    flex-shrink:0;
    border:1px solid #e9ecef;
    transition:transform .2s;
}
.product-thumb:hover { transform:scale(1.08); }
.product-thumb-placeholder {
    width:52px; height:52px;
    border-radius:8px; flex-shrink:0;
    background:#f8f9fa;
    display:flex; align-items:center; justify-content:center;
    border:1px dashed #dee2e6;
}

/* ── ✅ Attributs badges ──────────────────────────────────────────────────── */
.attribute-badge {
    display:inline-flex;
    align-items:center;
    gap:2px;
    padding:3px 10px;
    border-radius:50px;
    background:rgba(23,128,214,.08);
    border:1px solid rgba(23,128,214,.25);
    font-size:.78rem;
    white-space:nowrap;
}
.attr-name  { font-weight:600; color:var(--primary); }
.attr-sep   { color:var(--gray); margin:0 1px; }
.attr-value { font-weight:500; color:var(--dark); }
.no-attr    { font-size:.82rem; color:var(--gray); font-style:italic; }

/* ── Pagination ──────────────────────────────────────────────────────────── */
.pagination-section {
    background:white;
    border-radius:var(--border-radius);
    padding:1.5rem;
    box-shadow:var(--shadow);
}

/* ── Toast animation ─────────────────────────────────────────────────────── */
@keyframes fadeInUp {
    from { opacity:0; transform:translateY(12px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ── Responsive ──────────────────────────────────────────────────────────── */
@media (max-width:768px) {
    .header-content   { flex-direction:column; align-items:flex-start; }
    .header-actions   { width:100%; justify-content:space-between; }
    .stats-grid       { grid-template-columns:1fr 1fr; }
    .action-buttons   { flex-direction:column; }
    .info-item        { flex-direction:column; text-align:left; }
    .info-item div    { text-align:left; }
}
@media (max-width:576px) {
    .stats-grid           { grid-template-columns:1fr; }
    .filters-section .row { flex-direction:column; }
}
</style>
@endsection