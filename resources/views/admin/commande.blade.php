@extends('layouts.app_admin')

@section('title', 'Gestion des Commandes - Admin Bkassoua')

@section('content')
<div class="orders-container">
    <!-- En-tête de la page -->
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
                    <input type="text" class="form-control" placeholder="Rechercher une commande..." id="searchInput" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="statusFilter" onchange="applyFilters()">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>En traitement</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Livrée</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="paymentFilter" onchange="applyFilters()">
                    <option value="">Tous les paiements</option>
                    <option value="paid" {{ request('payment') == 'paid' ? 'selected' : '' }}>Payé</option>
                    <option value="pending" {{ request('payment') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="failed" {{ request('payment') == 'failed' ? 'selected' : '' }}>Échoué</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                    <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser
                </button>
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques des commandes -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon bg-primary">
                <i class="bi bi-cart"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalOrders ?? 0 }}</div>
                <div class="stat-label">Total commandes</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-warning">
                <i class="bi bi-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $pendingOrders ?? 0 }}</div>
                <div class="stat-label">En attente</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-info">
                <i class="bi bi-truck"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $processingOrders ?? 0 }}</div>
                <div class="stat-label">En traitement</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $deliveredOrders ?? 0 }}</div>
                <div class="stat-label">Livrées</div>
            </div>
        </div>
    </div>

    <!-- Tableau des commandes -->
    <div class="admin-card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="bi bi-list-ul me-2"></i>Liste des commandes
            </h5>
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
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>Commande</th>
                                <th>Client</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Paiement</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                            <tr class="order-row" data-order-id="{{ $order->id }}">
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input order-checkbox" type="checkbox" value="{{ $order->id }}">
                                    </div>
                                </td>
                                <td>
                                    <div class="order-info">
                                        <div class="order-id">#{{ $order->id }}</div>
                                        <small class="text-muted">{{ $order->items_count }} article(s)</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="customer-info">
                                        <div class="customer-name">{{ $order->user->name }}</div>
                                        <small class="text-muted">{{ $order->user->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="amount">{{ number_format($order->total_amount, 0, ',', ' ') }} fcfa</div>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $order->status }}">
                                        <i class="bi bi-circle-fill me-1"></i>
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($order->payment)
                                        <span class="payment-badge payment-{{ $order->payment->status }}">
                                            <i class="bi bi-{{ $order->payment->status == 'paid' ? 'check-circle' : 'clock' }} me-1"></i>
                                            {{ ucfirst($order->payment->status) }}
                                        </span>
                                    @else
                                        <span class="payment-badge payment-none">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Non payé
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="date-info">
                                        <div>{{ $order->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#orderDetailModal{{ $order->id }}"
                                                title="Voir les détails">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        
                                        @if($order->status == 'pending')
                                            @if($order->payment && $order->payment->status === 'pending')
                                                <button class="btn btn-sm btn-success" 
                                                        onclick="validatePayment({{ $order->id }})"
                                                        title="Valider le paiement">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            @endif
                                            
                                            <button class="btn btn-sm btn-danger" 
                                                    onclick="cancelOrder({{ $order->id }})"
                                                    title="Annuler la commande">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @endif

                                        @if($order->status == 'processing')
                                            <button class="btn btn-sm btn-info" 
                                                    onclick="shipOrder({{ $order->id }})"
                                                    title="Marquer comme expédiée">
                                                <i class="bi bi-truck"></i>
                                            </button>
                                        @endif

                                        @if($order->status == 'shipped')
                                            <button class="btn btn-sm btn-success" 
                                                    onclick="deliverOrder({{ $order->id }})"
                                                    title="Marquer comme livrée">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Actions groupées -->
        @if(!$orders->isEmpty())
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAllFooter">
                    <label class="form-check-label" for="selectAllFooter">
                        Sélectionner tout
                    </label>
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
            <nav>
                {{ $orders->links() }}
            </nav>
        </div>
    </div>
    @endif
</div>

<!-- Modals pour les détails des commandes -->
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
                    <!-- Informations client -->
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
                                    <label>Adresse</label>
                                    <div>{{ $order->user->address ?? 'Non renseignée' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations commande -->
                    <div class="col-md-6">
                        <div class="info-section">
                            <h6 class="section-title">
                                <i class="bi bi-info-circle me-2"></i>Informations commande
                            </h6>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Date de commande</label>
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
                                    <label>Total</label>
                                    <div class="amount-large">{{ number_format($order->total_amount, 0, ',', ' ') }} fcfa</div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations paiement -->
                        @if($order->payment)
                        <div class="info-section mt-4">
                            <h6 class="section-title">
                                <i class="bi bi-credit-card me-2"></i>Informations paiement
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
                                    <div>{{ $order->payment->method ?? 'Non spécifiée' }}</div>
                                </div>
                                <div class="info-item">
                                    <label>Référence</label>
                                    <div>{{ $order->payment->reference ?? 'Non disponible' }}</div>
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

                <!-- Articles commandés -->
                <div class="info-section mt-4">
                    <h6 class="section-title">
                        <i class="bi bi-box-seam me-2"></i>Articles commandés
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Prix unitaire</th>
                                    <th>Quantité</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="product-info">
                                            <div class="product-name">{{ $item->product->name }}</div>
                                            <small class="text-muted">Ref: {{ $item->product->id }}</small>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item->price, 0, ',', ' ') }} fcfa</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->quantity * $item->price, 0, ',', ' ') }} fcfa</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan="3" class="text-end"><strong>Total commande :</strong></td>
                                    <td><strong>{{ number_format($order->total_amount, 0, ',', ' ') }} fcfa</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
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
                    <button type="button" class="btn btn-info" onclick="shipOrder({{ $order->id }})">
                        <i class="bi bi-truck me-2"></i>Marquer comme expédiée
                    </button>
                @endif

                @if($order->status == 'shipped')
                    <button type="button" class="btn btn-success" onclick="deliverOrder({{ $order->id }})">
                        <i class="bi bi-check-circle me-2"></i>Marquer comme livrée
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Filtres avancés -->
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
                            <input type="date" class="form-control" name="start_date">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Date de fin</label>
                            <input type="date" class="form-control" name="end_date">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Montant minimum</label>
                            <input type="number" class="form-control" name="min_amount" placeholder="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Montant maximum</label>
                            <input type="number" class="form-control" name="max_amount" placeholder="1000000">
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
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la sélection multiple
    const selectAllCheckbox = document.getElementById('selectAll');
    const selectAllFooter = document.getElementById('selectAllFooter');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');

    selectAllCheckbox.addEventListener('change', function() {
        orderCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
        selectAllFooter.checked = selectAllCheckbox.checked;
    });

    selectAllFooter.addEventListener('change', function() {
        orderCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllFooter.checked;
        });
        selectAllCheckbox.checked = selectAllFooter.checked;
    });

    // Application des filtres
    window.applyFilters = function() {
        const search = document.getElementById('searchInput').value;
        const status = document.getElementById('statusFilter').value;
        const payment = document.getElementById('paymentFilter').value;
        
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (status) params.append('status', status);
        if (payment) params.append('payment', payment);
        
        window.location.href = '{{ route('admin.orders') }}?' + params.toString();
    };

    // Réinitialisation des filtres
    window.resetFilters = function() {
        window.location.href = '{{ route('admin.orders') }}';
    };

    // Export des commandes
    window.exportOrders = function() {
        // Simulation d'export
        showToast('Export des commandes en cours...', 'info');
        setTimeout(() => {
            showToast('Export terminé avec succès', 'success');
        }, 2000);
    };

    // Actions sur les commandes
    window.validatePayment = function(orderId) {
        if (confirm('Êtes-vous sûr de vouloir valider ce paiement ?')) {
            // Simulation de validation
            showToast('Paiement validé avec succès', 'success');
        }
    };

    window.cancelOrder = function(orderId) {
        if (confirm('Êtes-vous sûr de vouloir annuler cette commande ?')) {
            // Simulation d'annulation
            showToast('Commande annulée avec succès', 'success');
        }
    };

    window.shipOrder = function(orderId) {
        if (confirm('Marquer cette commande comme expédiée ?')) {
            // Simulation d'expédition
            showToast('Commande marquée comme expédiée', 'success');
        }
    };

    window.deliverOrder = function(orderId) {
        if (confirm('Marquer cette commande comme livrée ?')) {
            // Simulation de livraison
            showToast('Commande marquée comme livrée', 'success');
        }
    };

    // Actions groupées
    window.applyBulkAction = function() {
        const selectedOrders = Array.from(document.querySelectorAll('.order-checkbox:checked'))
            .map(checkbox => checkbox.value);
        
        const action = document.getElementById('bulkAction').value;
        
        if (selectedOrders.length === 0) {
            showToast('Veuillez sélectionner au moins une commande', 'warning');
            return;
        }
        
        if (!action) {
            showToast('Veuillez sélectionner une action', 'warning');
            return;
        }
        
        if (confirm(`Appliquer l'action "${action}" sur ${selectedOrders.length} commande(s) ?`)) {
            // Simulation d'action groupée
            showToast(`Action appliquée sur ${selectedOrders.length} commande(s)`, 'success');
        }
    };

    // Filtres avancés
    window.applyAdvancedFilters = function() {
        const form = document.getElementById('advancedFilters');
        const formData = new FormData(form);
        const params = new URLSearchParams();
        
        for (let [key, value] of formData) {
            if (value) params.append(key, value);
        }
        
        window.location.href = '{{ route('admin.orders') }}?' + params.toString();
    };
});

// Fonction utilitaire pour les notifications
function showToast(message, type = 'info') {
    // Implémentation des toasts (à adapter selon votre système)
    console.log(`${type.toUpperCase()}: ${message}`);
    alert(message); // Remplacez par votre système de toast
}
</script>

<style>
.orders-container {
    padding: 0;
}

.page-header {
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

.page-title {
    font-weight: 700;
    color: var(--dark);
    margin: 0;
    font-size: 2rem;
}

.page-subtitle {
    color: var(--gray);
    margin: 0.5rem 0 0 0;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.filters-section {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--shadow);
    margin-bottom: 2rem;
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
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
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
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--dark);
    line-height: 1;
}

.stat-label {
    color: var(--gray);
    font-size: 0.9rem;
    margin-top: 0.25rem;
}

.order-row:hover {
    background-color: var(--light);
}

.order-info .order-id {
    font-weight: 600;
    color: var(--dark);
}

.customer-info .customer-name {
    font-weight: 500;
    color: var(--dark);
}

.amount {
    font-weight: 600;
    color: var(--dark);
}

.status-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

.status-pending {
    background: rgba(255, 193, 7, 0.1);
    color: var(--warning);
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.status-processing {
    background: rgba(23, 128, 214, 0.1);
    color: var(--primary);
    border: 1px solid rgba(23, 128, 214, 0.2);
}

.status-shipped {
    background: rgba(111, 66, 193, 0.1);
    color: #6f42c1;
    border: 1px solid rgba(111, 66, 193, 0.2);
}

.status-delivered {
    background: rgba(40, 167, 69, 0.1);
    color: var(--success);
    border: 1px solid rgba(40, 167, 69, 0.2);
}

.status-cancelled {
    background: rgba(220, 53, 69, 0.1);
    color: var(--danger);
    border: 1px solid rgba(220, 53, 69, 0.2);
}

.payment-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

.payment-paid {
    background: rgba(40, 167, 69, 0.1);
    color: var(--success);
    border: 1px solid rgba(40, 167, 69, 0.2);
}

.payment-pending {
    background: rgba(255, 193, 7, 0.1);
    color: var(--warning);
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.payment-failed {
    background: rgba(220, 53, 69, 0.1);
    color: var(--danger);
    border: 1px solid rgba(220, 53, 69, 0.2);
}

.payment-none {
    background: rgba(108, 117, 125, 0.1);
    color: var(--gray);
    border: 1px solid rgba(108, 117, 125, 0.2);
}

.date-info {
    font-size: 0.9rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.action-buttons .btn {
    padding: 0.25rem 0.5rem;
}

.empty-state {
    padding: 3rem 2rem;
}

.info-section {
    margin-bottom: 2rem;
}

.section-title {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--gray-light);
}

.info-grid {
    display: grid;
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: between;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--gray-light);
}

.info-item:last-child {
    border-bottom: none;
}

.info-item label {
    font-weight: 600;
    color: var(--dark);
    min-width: 120px;
}

.info-item div {
    color: var(--gray);
    text-align: right;
    flex: 1;
}

.amount-large {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary);
}

.product-info .product-name {
    font-weight: 500;
    color: var(--dark);
}

.bulk-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.pagination-section {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--shadow);
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
        grid-template-columns: 1fr 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .info-item {
        flex-direction: column;
        text-align: left;
    }
    
    .info-item div {
        text-align: left;
    }
}

@media (max-width: 576px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .filters-section .row {
        flex-direction: column;
    }
}
</style>
@endsection