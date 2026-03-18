@extends('layouts.app_admin')

@section('title', 'Gestion des sous-catégories - Admin Bkassoua')

@section('content')
<div class="categories-container">
    <!-- En-tête de la page -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-text">
                <h1 class="page-title">Gestion des Sous-Catégories</h1>
                <p class="page-subtitle">Organisez et gérez les sous-catégories de votre plateforme</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubCategoryModal">
                    <i class="bi bi-plus-circle me-2"></i>Nouvelle sous-catégorie
                </button>
                <button class="btn btn-outline-primary" onclick="exportSubCategories()">
                    <i class="bi bi-download me-2"></i>Exporter
                </button>
            </div>
        </div>
    </div>

    <!-- Alertes -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Veuillez corriger les erreurs suivantes :
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Cartes de statistiques -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon bg-primary">
                <i class="bi bi-tags"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalSubCategories ?? $Souscategories->count() }}</div>
                <div class="stat-label">Total sous-catégories</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-success">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalProducts ?? 0 }}</div>
                <div class="stat-label">Produits associés</div>
            </div> 
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-info">
                <i class="bi bi-eye"></i>
            </div>
            <div class="stat-content">
                {{-- <div class="stat-value">{{ $activeSubCategories ?? $subcategories->count() }}</div> --}}
                <div class="stat-label">Sous-catégories actives</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-warning">
                <i class="bi bi-clock"></i>
            </div>
            <div class="stat-content">
                {{-- <div class="stat-value">{{ $recentSubCategories ?? 0 }}</div> --}}
                <div class="stat-label">Ajoutées ce mois</div>
            </div>
        </div>
    </div>

    <!-- Liste des sous-catégories -->
    <div class="admin-card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="bi bi-grid me-2"></i>Liste des sous-catégories
            </h5>
            <div class="card-actions">
                <span class="text-muted">{{ $Souscategories->count() }} sous-catégorie(s)</span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($Souscategories->isEmpty())
                <div class="empty-state text-center py-5">
                    <i class="bi bi-tags display-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">Aucune sous-catégorie trouvée</h4>
                    <p class="text-muted mb-4">Commencez par créer votre première sous-catégorie.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubCategoryModal">
                        <i class="bi bi-plus-circle me-2"></i>Créer une sous-catégorie
                    </button>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th>Sous-catégorie</th>
                                <th>Slug</th>
                                <th>Catégorie parente</th>
                                <th>Produits</th>
                                <th>Statut</th>
                                <th>Création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Souscategories as $subcategory)
                            <tr class="category-row" data-subcategory-id="{{ $subcategory->id }}">
                                <td>
                                    <div class="category-id">#{{ $subcategory->id }}</div>
                                </td>
                                <td>
                                    <div class="category-info">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $subcategory->image) }}" 
                                                 alt="{{ $subcategory->name }}" 
                                                 class="category-image me-3">
                                            <div>
                                                <div class="category-name">{{ $subcategory->name }}</div>
                                                <small class="text-muted">Dernière modif: {{ $subcategory->updated_at->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code class="slug-text">{{ $subcategory->slug }}</code>
                                </td>
                                <td>
                                    <div class="parent-category">
                                        @if($subcategory->category)
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-folder me-1"></i>
                                                {{ $subcategory->category->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="products-count">
                                        <span class="badge bg-light text-dark">
                                            <i class="bi bi-box me-1"></i>
                                            {{ $subcategory->products_count ?? 0 }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-active">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Active
                                    </span>
                                </td>
                                <td>
                                    <div class="date-info">
                                        <div>{{ $subcategory->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $subcategory->created_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewSubCategoryModal{{ $subcategory->id }}"
                                                title="Voir les détails">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        
                                        <button class="btn btn-sm btn-outline-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#updateSubCategoryModal{{ $subcategory->id }}"
                                                title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <button class="btn btn-sm btn-danger" 
                                                onclick="confirmDeleteSub({{ $subcategory->id }}, '{{ $subcategory->name }}')"
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
    </div>
</div>

<!-- Modal Ajout Sous-Catégorie -->
<div class="modal fade" id="addSubCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Nouvelle sous-catégorie
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data" id="addSubCategoryForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom de la sous-catégorie <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="name" required 
                                       placeholder="Ex: Smartphones, Robes, Canapés...">
                                <div class="form-text">Le nom doit être unique et descriptif.</div>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Catégorie parente <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-select" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">La catégorie à laquelle cette sous-catégorie appartient.</div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control" id="description" rows="3" 
                                          placeholder="Description optionnelle de la sous-catégorie..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="image" class="form-label">Image de la sous-catégorie <span class="text-danger">*</span></label>
                                <div class="image-upload-container">
                                    <div class="image-preview mb-3 text-center" id="imagePreviewAdd">
                                        <i class="bi bi-image display-1 text-muted"></i>
                                        <div class="mt-2 text-muted">Aperçu de l'image</div>
                                    </div>
                                    <input type="file" name="image" class="form-control" id="image" required 
                                           accept="image/*" onchange="previewImage(this, 'imagePreviewAdd')">
                                </div>
                                <div class="form-text">Format: JPG, PNG, WEBP. Max: 2MB</div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" form="addSubCategoryForm" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Créer la sous-catégorie
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modals pour chaque sous-catégorie -->
@foreach ($Souscategories as $subcategory)
<!-- Modal Détails Sous-Catégorie -->
<div class="modal fade" id="viewSubCategoryModal{{ $subcategory->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle me-2"></i>Détails de la sous-catégorie
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="category-image-large mb-4">
                            <img src="{{ asset('storage/' . $subcategory->image) }}" 
                                 alt="{{ $subcategory->name }}" 
                                 class="img-fluid rounded shadow">
                        </div>
                        <h4>{{ $subcategory->name }}</h4>
                        <code class="text-muted">{{ $subcategory->slug }}</code>
                    </div>
                    <div class="col-md-8">
                        <div class="info-section">
                            <h6 class="section-title">Informations générales</h6>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>ID</label>
                                    <div>#{{ $subcategory->id }}</div>
                                </div>
                                <div class="info-item">
                                    <label>Catégorie parente</label>
                                    <div>
                                        @if($subcategory->category)
                                            <span class="badge bg-primary">{{ $subcategory->category->name }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="info-item">
                                    <label>Produits associés</label>
                                    <div>
                                        <span class="badge bg-primary">
                                             {{ $subcategory->products_count ?? 0 }} produit(s)
                                        </span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <label>Date de création</label>
                                    <div>{{ $subcategory->created_at->format('d/m/Y à H:i') }}</div>
                                </div>
                                <div class="info-item">
                                    <label>Dernière modification</label>
                                    <div>{{ $subcategory->updated_at->format('d/m/Y à H:i') }}</div>
                                </div>
                            </div>
                        </div>
                        
                        @if($subcategory->description)
                        <div class="info-section mt-4">
                            <h6 class="section-title">Description</h6>
                            <p class="mb-0">{{ $subcategory->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-warning" 
                        data-bs-toggle="modal" 
                        data-bs-target="#updateSubCategoryModal{{ $subcategory->id }}"
                        data-bs-dismiss="modal">
                    <i class="bi bi-pencil me-2"></i>Modifier
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modification Sous-Catégorie -->
<div class="modal fade" id="updateSubCategoryModal{{ $subcategory->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                    <i class="bi bi-pencil me-2"></i>Modifier la sous-catégorie
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data" id="updateSubCategoryForm{{ $subcategory->id }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name{{ $subcategory->id }}" class="form-label">Nom de la sous-catégorie <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="name{{ $subcategory->id }}" 
                                       value="{{ $subcategory->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="category_id{{ $subcategory->id }}" class="form-label">Catégorie parente <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id{{ $subcategory->id }}" class="form-select" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $subcategory->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="description{{ $subcategory->id }}" class="form-label">Description</label>
                                <textarea name="description" class="form-control" id="description{{ $subcategory->id }}" rows="3">{{ $subcategory->description ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="image{{ $subcategory->id }}" class="form-label">Image de la sous-catégorie</label>
                                <div class="image-upload-container">
                                    <div class="image-preview mb-3 text-center" id="imagePreview{{ $subcategory->id }}">
                                        <img src="{{ asset('storage/' . $subcategory->image) }}" 
                                             alt="{{ $subcategory->name }}" 
                                             class="img-fluid rounded shadow">
                                        <div class="mt-2 text-muted">Image actuelle</div>
                                    </div>
                                    <input type="file" name="image" class="form-control" id="image{{ $subcategory->id }}" 
                                           accept="image/*" onchange="previewImage(this, 'imagePreview{{ $subcategory->id }}')">
                                </div>
                                <div class="form-text">Laissez vide pour conserver l'image actuelle.</div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" form="updateSubCategoryForm{{ $subcategory->id }}" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i>Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteSubConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle me-2"></i>Confirmation de suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer la sous-catégorie <strong id="deleteSubCategoryName"></strong> ?</p>
                <p class="text-danger mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Cette action est irréversible. Tous les produits associés seront affectés.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteSubCategoryForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prévisualisation d'image
    window.previewImage = function(input, previewId) {
        const preview = document.getElementById(previewId);
        const file = input.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <img src="${e.target.result}" class="img-fluid rounded shadow" style="max-height: 200px;">
                    <div class="mt-2 text-muted">Nouvel aperçu</div>
                `;
            }
            reader.readAsDataURL(file);
        }
    };

    // Génération automatique du slug pour le champ name du modal d'ajout
    const nameInput = document.getElementById('name');
    if (nameInput) {
        nameInput.addEventListener('input', function(e) {
            // Si vous avez un champ slug, décommentez
            // const slugInput = document.getElementById('slug');
            // if (slugInput) {
            //     slugInput.value = e.target.value
            //         .toLowerCase()
            //         .trim()
            //         .replace(/[^a-z0-9\s-]/g, '')
            //         .replace(/\s+/g, '-');
            // }
        });
    }

    // Export des sous-catégories
    window.exportSubCategories = function() {
        showToast('Export des sous-catégories en cours...', 'info');
        // Implémentation réelle de l'export
    };
});

// Confirmation de suppression
window.confirmDeleteSub = function(subcategoryId, subcategoryName) {
    document.getElementById('deleteSubCategoryName').textContent = subcategoryName;
    let form = document.getElementById('deleteSubCategoryForm');
    form.action = `/admin/subcategories/${subcategoryId}`; // À adapter selon vos routes
    new bootstrap.Modal(document.getElementById('deleteSubConfirmModal')).show();
};

// Fonction utilitaire pour les notifications
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.top = '20px';
    toast.style.right = '20px';
    toast.style.zIndex = '9999';
    toast.innerHTML = `
        <i class="bi bi-${type === 'success' ? 'check-circle' : 'info'}-fill me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>

<style>
/* Les mêmes styles que pour les catégories, à copier si nécessaire */
.categories-container {
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

.category-row:hover {
    background-color: var(--light);
}

.category-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
}

.category-image-large img {
    max-width: 100%;
    height: auto;
    border-radius: 12px;
}

.category-name {
    font-weight: 600;
    color: var(--dark);
}

.category-id {
    font-weight: 600;
    color: var(--primary);
}

.slug-text {
    background: var(--light);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.85rem;
    color: var(--gray);
}

.products-count .badge {
    font-size: 0.8rem;
    padding: 0.35rem 0.75rem;
}

.status-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

.status-active {
    background: rgba(40, 167, 69, 0.1);
    color: var(--success);
    border: 1px solid rgba(40, 167, 69, 0.2);
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

.image-upload-container {
    border: 2px dashed var(--gray-light);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    text-align: center;
}

.image-preview {
    min-height: 120px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.image-preview img {
    max-height: 200px;
    object-fit: cover;
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
    
    .action-buttons .btn {
        padding: 0.5rem;
    }
}
</style>
@endsection