{{-- @extends('layouts.app_admin')

@section('content')
    <div class="container mt-4 mb-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <button type="button" class="btn btn-primary mt-4 mb-4" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fa fa-plus"></i> Ajouter une catégorie
        </button>

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Slug</th>
                        <th scope="col">Image</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $categorie)
                        <tr>
                            <td>{{ $categorie->id }}</td>
                            <td>{{ $categorie->name }}</td>
                            <td>{{ $categorie->slug }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $categorie->image) }}" alt="Image catégorie" width="50" class="rounded shadow">
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateCategoryModal{{ $categorie->id }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.categories.destroy', $categorie->id) }}" class="btn btn-sm btn-danger"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Modal modification catégorie -->
                        <div class="modal fade" id="updateCategoryModal{{ $categorie->id }}" tabindex="-1" aria-labelledby="updateCategoryModalLabel{{ $categorie->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Modifier la catégorie</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('admin.categories.update', $categorie->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label for="name{{ $categorie->id }}" class="form-label">Nom de la catégorie</label>
                                                <input type="text" name="name" class="form-control" id="name{{ $categorie->id }}" value="{{ $categorie->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="image{{ $categorie->id }}" class="form-label">Image</label>
                                                <input type="file" name="image" class="form-control" id="image{{ $categorie->id }}">
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/' . $categorie->image) }}" alt="Image catégorie" width="100" class="rounded shadow">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Valider</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal ajout catégorie -->
        <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter une catégorie</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom de la catégorie</label>
                                <input type="text" name="name" class="form-control" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" class="form-control" id="image" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">Ajouter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}
@extends('layouts.app_admin')

@section('title', 'Gestion des Catégories - Admin Bkassoua')

@section('content')
<div class="categories-container">
    <!-- En-tête de la page -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-text">
                <h1 class="page-title">Gestion des Catégories</h1>
                <p class="page-subtitle">Organisez et gérez les catégories de votre plateforme</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="bi bi-plus-circle me-2"></i>Nouvelle catégorie
                </button>
                <button class="btn btn-outline-primary" onclick="exportCategories()">
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
                <div class="stat-value">{{ $totalCategories ?? $categories->count() }}</div>
                <div class="stat-label">Total catégories</div>
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
                <div class="stat-value">{{ $activeCategories ?? $categories->count() }}</div>
                <div class="stat-label">Catégories actives</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-warning">
                <i class="bi bi-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $recentCategories ?? 0 }}</div>
                <div class="stat-label">Ajoutées ce mois</div>
            </div>
        </div>
    </div>

    <!-- Tableau des catégories -->
    <div class="admin-card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="bi bi-grid me-2"></i>Liste des catégories
            </h5>
            <div class="card-actions">
                <span class="text-muted">{{ $categories->count() }} catégorie(s)</span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($categories->isEmpty())
                <div class="empty-state text-center py-5">
                    <i class="bi bi-tags display-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">Aucune catégorie trouvée</h4>
                    <p class="text-muted mb-4">Commencez par créer votre première catégorie.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="bi bi-plus-circle me-2"></i>Créer une catégorie
                    </button>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th>Catégorie</th>
                                <th>Slug</th>
                                <th>Produits</th>
                                <th>Statut</th>
                                <th>Création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                            <tr class="category-row" data-category-id="{{ $category->id }}">
                                <td>
                                    <div class="category-id">#{{ $category->id }}</div>
                                </td>
                                <td>
                                    <div class="category-info">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $category->image) }}" 
                                                 alt="{{ $category->name }}" 
                                                 class="category-image me-3">
                                            <div>
                                                <div class="category-name">{{ $category->name }}</div>
                                                <small class="text-muted">Dernière modif: {{ $category->updated_at->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code class="slug-text">{{ $category->slug }}</code>
                                </td>
                                <td>
                                    <div class="products-count">
                                        <span class="badge bg-light text-dark">
                                            <i class="bi bi-box me-1"></i>
                                            {{ $category->products_count ?? 0 }}
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
                                        <div>{{ $category->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $category->created_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewCategoryModal{{ $category->id }}"
                                                title="Voir les détails">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        
                                        <button class="btn btn-sm btn-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#updateCategoryModal{{ $category->id }}"
                                                title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <button class="btn btn-sm btn-danger" 
                                                onclick="confirmDelete({{ $category->id }}, '{{ $category->name }}')"
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

<!-- Modal Ajout Catégorie -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Nouvelle catégorie
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" id="addCategoryForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom de la catégorie <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="name" required 
                                       placeholder="Ex: Électronique, Mode, Maison...">
                                <div class="form-text">Le nom doit être unique et descriptif.</div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control" id="description" rows="3" 
                                          placeholder="Description optionnelle de la catégorie..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="image" class="form-label">Image de la catégorie <span class="text-danger">*</span></label>
                                <div class="image-upload-container">
                                    <div class="image-preview mb-3 text-center" id="imagePreview">
                                        <i class="bi bi-image display-1 text-muted"></i>
                                        <div class="mt-2 text-muted">Aperçu de l'image</div>
                                    </div>
                                    <input type="file" name="image" class="form-control" id="image" required 
                                           accept="image/*" onchange="previewImage(this, 'imagePreview')">
                                </div>
                                <div class="form-text">Format: JPG, PNG, WEBP. Max: 2MB</div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" form="addCategoryForm" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Créer la catégorie
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modals pour chaque catégorie -->
@foreach ($categories as $category)
<!-- Modal Détails Catégorie -->
<div class="modal fade" id="viewCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle me-2"></i>Détails de la catégorie
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="category-image-large mb-4">
                            <img src="{{ asset('storage/' . $category->image) }}" 
                                 alt="{{ $category->name }}" 
                                 class="img-fluid rounded shadow">
                        </div>
                        <h4>{{ $category->name }}</h4>
                        <code class="text-muted">{{ $category->slug }}</code>
                    </div>
                    <div class="col-md-8">
                        <div class="info-section">
                            <h6 class="section-title">Informations générales</h6>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>ID</label>
                                    <div>#{{ $category->id }}</div>
                                </div>
                                <div class="info-item">
                                    <label>Produits associés</label>
                                    <div>
                                        <span class="badge bg-primary">
                                            {{ $category->products_count ?? 0 }} produit(s)
                                        </span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <label>Date de création</label>
                                    <div>{{ $category->created_at->format('d/m/Y à H:i') }}</div>
                                </div>
                                <div class="info-item">
                                    <label>Dernière modification</label>
                                    <div>{{ $category->updated_at->format('d/m/Y à H:i') }}</div>
                                </div>
                            </div>
                        </div>
                        
                        @if($category->description)
                        <div class="info-section mt-4">
                            <h6 class="section-title">Description</h6>
                            <p class="mb-0">{{ $category->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-warning" 
                        data-bs-toggle="modal" 
                        data-bs-target="#updateCategoryModal{{ $category->id }}"
                        data-bs-dismiss="modal">
                    <i class="bi bi-pencil me-2"></i>Modifier
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modification Catégorie -->
<div class="modal fade" id="updateCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                    <i class="bi bi-pencil me-2"></i>Modifier la catégorie
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" id="updateCategoryForm{{ $category->id }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name{{ $category->id }}" class="form-label">Nom de la catégorie <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="name{{ $category->id }}" 
                                       value="{{ $category->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="description{{ $category->id }}" class="form-label">Description</label>
                                <textarea name="description" class="form-control" id="description{{ $category->id }}" rows="3">{{ $category->description ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="image{{ $category->id }}" class="form-label">Image de la catégorie</label>
                                <div class="image-upload-container">
                                    <div class="image-preview mb-3 text-center" id="imagePreview{{ $category->id }}">
                                        <img src="{{ asset('storage/' . $category->image) }}" 
                                             alt="{{ $category->name }}" 
                                             class="img-fluid rounded shadow">
                                        <div class="mt-2 text-muted">Image actuelle</div>
                                    </div>
                                    <input type="file" name="image" class="form-control" id="image{{ $category->id }}" 
                                           accept="image/*" onchange="previewImage(this, 'imagePreview{{ $category->id }}')">
                                </div>
                                <div class="form-text">Laissez vide pour conserver l'image actuelle.</div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" form="updateCategoryForm{{ $category->id }}" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i>Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle me-2"></i>Confirmation de suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer la catégorie <strong id="deleteCategoryName"></strong> ?</p>
                <p class="text-danger mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Cette action est irréversible. Tous les produits associés seront affectés.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="#" class="btn btn-danger" id="confirmDeleteButton">
                    <i class="bi bi-trash me-2"></i>Supprimer
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la prévisualisation d'image
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

    // Confirmation de suppression
    window.confirmDelete = function(categoryId, categoryName) {
        document.getElementById('deleteCategoryName').textContent = categoryName;
        document.getElementById('confirmDeleteButton').href = "{{ route('admin.categories.destroy', '') }}/" + categoryId;
        new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
    };

    // Export des catégories
    window.exportCategories = function() {
        showToast('Export des catégories en cours...', 'info');
        // Implémentation réelle de l'export
    };

    // Génération automatique du slug
    document.getElementById('name')?.addEventListener('input', function(e) {
        const slugInput = document.getElementById('slug');
        if (slugInput) {
            slugInput.value = e.target.value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-');
        }
    });
});

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