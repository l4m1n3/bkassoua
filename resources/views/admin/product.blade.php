@extends('layouts.app_admin')

@section('title', 'Gestion des Produits - Admin Bkassoua')

@section('content')
<div class="products-container">
    <!-- En-tête de la page -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-text">
                <h1 class="page-title">Gestion des Produits</h1>
                <p class="page-subtitle">Gérez l'ensemble des produits de votre plateforme</p>
            </div>
            <div class="header-actions">
                <a href="" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Nouveau produit
                </a>
                <button class="btn btn-outline-primary" onclick="exportProducts()">
                    <i class="bi bi-download me-2"></i>Exporter
                </button>
                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filtersModal">
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
                    <input type="text" class="form-control" placeholder="Rechercher un produit..." id="searchInput" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="categoryFilter" onchange="applyFilters()">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="visibilityFilter" onchange="applyFilters()">
                    <option value="">Tous</option>
                    <option value="1" {{ request('visible') == '1' ? 'selected' : '' }}>Visible</option>
                    <option value="0" {{ request('visible') == '0' ? 'selected' : '' }}>Masqué</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="stockFilter" onchange="applyFilters()">
                    <option value="">Stock</option>
                    <option value="in_stock" {{ request('stock') == 'in_stock' ? 'selected' : '' }}>En stock</option>
                    <option value="low_stock" {{ request('stock') == 'low_stock' ? 'selected' : '' }}>Stock faible</option>
                    <option value="out_of_stock" {{ request('stock') == 'out_of_stock' ? 'selected' : '' }}>Rupture</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-outline-secondary w-100" onclick="resetFilters()" title="Réinitialiser">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques des produits -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon bg-primary">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalProducts ?? 0 }}</div>
                <div class="stat-label">Total produits</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-success">
                <i class="bi bi-eye"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $visibleProducts ?? 0 }}</div>
                <div class="stat-label">Produits visibles</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-warning">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $lowStockProducts ?? 0 }}</div>
                <div class="stat-label">Stock faible</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-danger">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $outOfStockProducts ?? 0 }}</div>
                <div class="stat-label">En rupture</div>
            </div>
        </div>
    </div>

    <!-- Tableau des produits -->
    <div class="admin-card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="bi bi-grid me-2"></i>Liste des produits
            </h5>
            <div class="card-actions">
                <span class="text-muted">{{ $products->total() }} produit(s) trouvé(s)</span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($products->isEmpty())
                <div class="empty-state text-center py-5">
                    <i class="bi bi-box display-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">Aucun produit trouvé</h4>
                    <p class="text-muted mb-4">Aucun produit ne correspond à vos critères de recherche.</p>
                    <a href="{{ route('products.create') }}" class="btn btn-primary me-2">
                        <i class="bi bi-plus-circle me-2"></i>Créer un produit
                    </a>
                    <button class="btn btn-outline-primary" onclick="resetFilters()">
                        <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser les filtres
                    </button>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="50">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>Produit</th>
                                <th>Vendeur</th>
                                <th>Prix</th>
                                <th>Stock</th>
                                <th>Catégorie</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                            <tr class="product-row" data-product-id="{{ $product->id }}">
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input product-checkbox" type="checkbox" value="{{ $product->id }}">
                                    </div>
                                </td>
                                <td>
                                    <div class="product-info">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('/storage/' . $product->image) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="product-image me-3">
                                            <div>
                                                <div class="product-name">{{ $product->name }}</div>
                                                <small class="text-muted text-truncate d-block" style="max-width: 200px;">
                                                    {{ $product->description }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="vendor-info">
                                        <div class="vendor-name">{{ $product->vendor ? $product->vendor->store_name : 'Aucun vendeur' }}</div>
                                        <small class="text-muted">{{ $product->vendor ? $product->vendor->user->email : '' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="price">{{ number_format($product->price, 0, ',', ' ') }} FCFA</div>
                                </td>
                                <td>
                                    <div class="stock-info">
                                        <div class="stock-amount {{ $product->stock_quantity == 0 ? 'text-danger' : ($product->stock_quantity <= 10 ? 'text-warning' : 'text-success') }}">
                                            {{ $product->stock_quantity }}
                                        </div>
                                        @if($product->stock_quantity <= 10 && $product->stock_quantity > 0)
                                            <small class="text-warning d-block">Stock faible</small>
                                        @elseif($product->stock_quantity == 0)
                                            <small class="text-danger d-block">Rupture</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="category-badge">
                                        {{ $product->category ? $product->category->name : 'Sans catégorie' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="visibility-badge {{ $product->is_visible ? 'visible' : 'hidden' }}">
                                        <i class="bi bi-{{ $product->is_visible ? 'eye' : 'eye-slash' }} me-1"></i>
                                        {{ $product->is_visible ? 'Visible' : 'Masqué' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <button class="btn btn-sm btn-outline-secondary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#productDetailModal{{ $product->id }}"
                                                title="Voir les détails">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <button class="btn btn-sm {{ $product->is_visible ? 'btn-warning' : 'btn-success' }}" 
                                                onclick="toggleVisibility({{ $product->id }})"
                                                title="{{ $product->is_visible ? 'Masquer' : 'Rendre visible' }}">
                                            <i class="bi bi-{{ $product->is_visible ? 'eye-slash' : 'eye' }}"></i>
                                        </button>

                                        <button class="btn btn-sm btn-danger" 
                                                onclick=""
                                                title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
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
        @if(!$products->isEmpty())
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
                        <option value="make_visible">Rendre visible</option>
                        <option value="make_hidden">Masquer</option>
                        <option value="update_category">Changer catégorie</option>
                        <option value="delete">Supprimer</option>
                    </select>
                    <button class="btn btn-sm btn-primary" onclick="applyBulkAction()">Appliquer</button>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="pagination-section mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Affichage de {{ $products->firstItem() }} à {{ $products->lastItem() }} sur {{ $products->total() }} produits
            </div>
            <nav>
                {{ $products->links() }}
            </nav>
        </div>
    </div>
    @endif
</div>

<!-- Modals pour les détails des produits -->
@foreach ($products as $product)
<div class="modal fade" id="productDetailModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle me-2"></i>Détails du produit
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="product-image-large text-center mb-4">
                            <img src="{{ asset('/storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid rounded shadow">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="product-details">
                            <h4 class="product-title mb-3">{{ $product->name }}</h4>
                            
                            <div class="info-grid mb-4">
                                <div class="info-item">
                                    <label>Prix</label>
                                    <div class="price-large">{{ number_format($product->price, 0, ',', ' ') }} FCFA</div>
                                </div>
                                <div class="info-item">
                                    <label>Stock</label>
                                    <div>
                                        <span class="stock-badge {{ $product->stock_quantity == 0 ? 'out-of-stock' : ($product->stock_quantity <= 10 ? 'low-stock' : 'in-stock') }}">
                                            {{ $product->stock_quantity }} unités
                                        </span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <label>Statut</label>
                                    <div>
                                        <span class="visibility-badge {{ $product->is_visible ? 'visible' : 'hidden' }}">
                                            {{ $product->is_visible ? 'Visible' : 'Masqué' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <label>Catégorie</label>
                                    <div>{{ $product->category ? $product->category->name : 'Non catégorisé' }}</div>
                                </div>
                            </div>

                            <div class="description-section mb-4">
                                <h6 class="section-title">Description</h6>
                                <p class="description-text">{{ $product->description }}</p>
                            </div>

                            <div class="vendor-section">
                                <h6 class="section-title">Informations vendeur</h6>
                                <div class="vendor-details">
                                    <div class="vendor-name">{{ $product->vendor ? $product->vendor->store_name : 'Aucun vendeur' }}</div>
                                    @if($product->vendor)
                                        <div class="vendor-contact">
                                            <small class="text-muted">{{ $product->vendor->user->email }}</small>
                                            @if($product->vendor->phone_number)
                                                <small class="text-muted d-block">{{ $product->vendor->phone_number }}</small>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <a href="" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Modifier
                </a>
                <button type="button" class="btn {{ $product->is_visible ? 'btn-warning' : 'btn-success' }}" 
                        onclick="toggleVisibility({{ $product->id }})">
                    <i class="bi bi-{{ $product->is_visible ? 'eye-slash' : 'eye' }} me-2"></i>
                    {{ $product->is_visible ? 'Masquer' : 'Rendre visible' }}
                </button>
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
                            <label class="form-label">Prix minimum</label>
                            <input type="number" class="form-control" name="min_price" placeholder="0" value="{{ request('min_price') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Prix maximum</label>
                            <input type="number" class="form-control" name="max_price" placeholder="1000000" value="{{ request('max_price') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Stock minimum</label>
                            <input type="number" class="form-control" name="min_stock" placeholder="0" value="{{ request('min_stock') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Date de création (début)</label>
                            <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Date de création (fin)</label>
                            <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
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
    const productCheckboxes = document.querySelectorAll('.product-checkbox');

    selectAllCheckbox.addEventListener('change', function() {
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
        selectAllFooter.checked = selectAllCheckbox.checked;
    });

    selectAllFooter.addEventListener('change', function() {
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllFooter.checked;
        });
        selectAllCheckbox.checked = selectAllFooter.checked;
    });

    // Application des filtres
    window.applyFilters = function() {
        const search = document.getElementById('searchInput').value;
        const category = document.getElementById('categoryFilter').value;
        const visibility = document.getElementById('visibilityFilter').value;
        const stock = document.getElementById('stockFilter').value;
        
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (category) params.append('category', category);
        if (visibility) params.append('visible', visibility);
        if (stock) params.append('stock', stock);
        
        window.location.href = '{{ route('admin.products') }}?' + params.toString();
    };

    // Réinitialisation des filtres
    window.resetFilters = function() {
        window.location.href = '{{ route('admin.products') }}';
    };

    // Export des produits
    window.exportProducts = function() {
        showToast('Export des produits en cours...', 'info');
        // Implémentation réelle de l'export
    };

    // Actions sur les produits
    window.toggleVisibility = function(productId) {
        if (confirm('Êtes-vous sûr de vouloir changer la visibilité de ce produit ?')) {
            // Simulation de changement de visibilité
            fetch(`/admin/products/${productId}/toggle-visibility`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                showToast('Visibilité mise à jour avec succès', 'success');
                setTimeout(() => location.reload(), 1000);
            })
            .catch(error => {
                showToast('Erreur lors de la mise à jour', 'error');
            });
        }
    };

    window.deleteProduct = function(productId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce produit ? Cette action est irréversible.')) {
            // Simulation de suppression
            fetch(`/admin/products/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                showToast('Produit supprimé avec succès', 'success');
                setTimeout(() => location.reload(), 1000);
            })
            .catch(error => {
                showToast('Erreur lors de la suppression', 'error');
            });
        }
    };

    // Actions groupées
    window.applyBulkAction = function() {
        const selectedProducts = Array.from(document.querySelectorAll('.product-checkbox:checked'))
            .map(checkbox => checkbox.value);
        
        const action = document.getElementById('bulkAction').value;
        
        if (selectedProducts.length === 0) {
            showToast('Veuillez sélectionner au moins un produit', 'warning');
            return;
        }
        
        if (!action) {
            showToast('Veuillez sélectionner une action', 'warning');
            return;
        }
        
        if (confirm(`Appliquer l'action "${action}" sur ${selectedProducts.length} produit(s) ?`)) {
            // Simulation d'action groupée
            showToast(`Action appliquée sur ${selectedProducts.length} produit(s)`, 'success');
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
        
        window.location.href = '{{ route('admin.products') }}?' + params.toString();
    };
});

// Fonction utilitaire pour les notifications
function showToast(message, type = 'info') {
    // Implémentation des toasts (à adapter selon votre système)
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show`;
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>

<style>
.products-container {
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
    justify-content: space-between;
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

.product-row:hover {
    background-color: var(--light);
}

.product-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
}

.product-image-large img {
    max-width: 100%;
    height: auto;
    border-radius: 12px;
}

.product-name {
    font-weight: 600;
    color: var(--dark);
}

.vendor-info .vendor-name {
    font-weight: 500;
    color: var(--dark);
}

.price {
    font-weight: 600;
    color: var(--primary);
}

.price-large {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary);
}

.stock-info .stock-amount {
    font-weight: 600;
    font-size: 1.1rem;
}

.category-badge {
    background: rgba(23, 128, 214, 0.1);
    color: var(--primary);
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 500;
}

.visibility-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

.visibility-badge.visible {
    background: rgba(40, 167, 69, 0.1);
    color: var(--success);
    border: 1px solid rgba(40, 167, 69, 0.2);
}

.visibility-badge.hidden {
    background: rgba(108, 117, 125, 0.1);
    color: var(--gray);
    border: 1px solid rgba(108, 117, 125, 0.2);
}

.stock-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
}

.stock-badge.in-stock {
    background: rgba(40, 167, 69, 0.1);
    color: var(--success);
    border: 1px solid rgba(40, 167, 69, 0.2);
}

.stock-badge.low-stock {
    background: rgba(255, 193, 7, 0.1);
    color: var(--warning);
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.stock-badge.out-of-stock {
    background: rgba(220, 53, 69, 0.1);
    color: var(--danger);
    border: 1px solid rgba(220, 53, 69, 0.2);
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

.info-grid {
    display: grid;
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
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

.section-title {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--gray-light);
}

.description-text {
    color: var(--gray);
    line-height: 1.6;
}

.vendor-details .vendor-name {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.25rem;
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
    
    .action-buttons .btn {
        padding: 0.5rem;
    }
}
</style>
@endsection