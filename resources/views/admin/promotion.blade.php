@extends('layouts.app_admin')

@section('title', '')

@section('content')

<div class="categories-container">
 <div class="page-header"> 
        <div class="header-content">
            <div class="header-text">
                <h1 class="page-title">Gestion des promotions</h1>
                <p class="page-subtitle">Organisez et gérez les promotions de votre plateforme</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPromotionModal">
                    <i class="bi bi-plus-circle me-2"></i>Nouvelle promotion
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
                {{-- <div class="stat-value">{{ $totalSubCategories ?? $Souscategories->count() }}</div> --}}
                <div class="stat-label">Total promotions</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-success">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="stat-content">
                {{-- <div class="stat-value">{{ $totalProducts ?? 0 }}</div> --}}
                <div class="stat-label">Sous catégories associées</div>
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
                <span class="text-muted">{{ $promotions->count() }} promotion(s)</span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($promotions->isEmpty())
                <div class="empty-state text-center py-5">
                    <i class="bi bi-tags display-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">Aucune promotion trouvée</h4>
                    <p class="text-muted mb-4">Commencez par créer votre première promotion.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPromotionModal">
                        <i class="bi bi-plus-circle me-2"></i>Créer une promotion
                    </button>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th>Promotion</th>
                                <th>Porcentage</th>
                                <th>Sous-catégorie</th>
                                <th>Debut</th>
                                <th>Fin</th>
                                <th>Statut</th>
                                <th>Création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($promotions as $promotion)
                            <tr class="promotion-row" data-promotion-id="{{ $promotion->id }}">
                                <td>
                                    <div class="promotion-id">#{{ $promotion->id }}</div>
                                </td>
                                <td>
                                    <div class="category-info">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <div class="category-name">{{ $promotion->title }}</div>
                                                <small class="text-muted">Dernière modif: {{ $promotion->updated_at->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code class="slug-text">{{ $promotion->discount_percentage }}</code>
                                </td>
                                <td>
                                    <div class="parent-category">
                                        @if($promotion->sousCat)
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-folder me-1"></i>
                                                {{ $promotion->sousCat->name }}
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
                                            {{ $promotion->start_date->format('d/m/Y')?? 0 }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="products-count">
                                        <span class="badge bg-light text-dark">
                                            <i class="bi bi-box me-1"></i>
                                            {{ $promotion->end_date->format('d/m/Y')?? 0 }}
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
                                        <div>{{ $promotion->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $promotion->created_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewSubCategoryModal{{ $promotion->id }}"
                                                title="Voir les détails">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        
                                        <button class="btn btn-sm btn-outline-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#updateSubCategoryModal{{ $promotion->id }}"
                                                title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <button class="btn btn-sm btn-danger" 
                                                onclick="confirmDeleteSub({{ $promotion->id }}, '{{ $promotion->name }}')"
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


{{-- ============================================================
     MODAL — AJOUTER UNE PROMOTION
     ============================================================ --}}
<div class="modal fade" id="addPromotionModal" tabindex="-1"
     aria-labelledby="addPromotionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="addPromotionModalLabel">
                        <i class="bi bi-plus-circle-fill text-primary me-2"></i>Nouvelle promotion
                    </h5>
                    <p class="text-muted small mb-0">
                        Remplissez les informations pour créer une nouvelle promotion
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('promotions.store') }}" method="POST">
                @csrf

                <div class="modal-body pt-3">
                    <div class="row g-3">

                        {{-- Titre --}}
                        <div class="col-12">
                            <label for="add_title" class="form-label fw-semibold">
                                Titre de la promotion <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="add_title"
                                   name="title"
                                   value="{{ old('title') }}"
                                   placeholder="Ex : Soldes d'été 2025"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Pourcentage --}}
                        <div class="col-md-6">
                            <label for="add_discount_percentage" class="form-label fw-semibold">
                                Pourcentage de réduction (%)
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number"
                                       class="form-control @error('discount_percentage') is-invalid @enderror"
                                       id="add_discount_percentage"
                                       name="discount_percentage"
                                       value="{{ old('discount_percentage') }}"
                                       min="1" max="100" step="0.01"
                                       placeholder="Ex : 20"
                                       required>
                                <span class="input-group-text">
                                    <i class="bi bi-percent"></i>
                                </span>
                                @error('discount_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Sous-catégorie --}}
                        <div class="col-md-6">
                            <label for="add_sous_categorie_id" class="form-label fw-semibold">
                                Sous-catégorie associée
                            </label>
                            <select class="form-select @error('sous_categorie_id') is-invalid @enderror"
                                    id="add_sous_categorie_id"
                                    name="sous_cat_id">
                                <option value="">— Aucune —</option>
                                @foreach($sousCategories ?? [] as $sousCat)
                                    <option value="{{ $sousCat->id }}"
                                        {{ old('sous_categorie_id') == $sousCat->id ? 'selected' : '' }}>
                                        {{ $sousCat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sous_categorie_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Date de début --}}
                        <div class="col-md-6">
                            <label for="add_start_date" class="form-label fw-semibold">
                                Date de début <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   id="add_start_date"
                                   name="start_date"
                                   value="{{ old('start_date') }}"
                                   required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Date de fin --}}
                        <div class="col-md-6">
                            <label for="add_end_date" class="form-label fw-semibold">
                                Date de fin <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   id="add_end_date"
                                   name="end_date"
                                   value="{{ old('end_date') }}"
                                   required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Créer la promotion
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


{{-- ============================================================
     MODAUX — MODIFIER UNE PROMOTION (un modal par promotion)
     ============================================================ --}}
@foreach ($promotions as $promotion)
<div class="modal fade" id="updateSubCategoryModal{{ $promotion->id }}" tabindex="-1"
     aria-labelledby="updatePromotionLabel{{ $promotion->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="updatePromotionLabel{{ $promotion->id }}">
                        <i class="bi bi-pencil-fill text-warning me-2"></i>Modifier la promotion
                    </h5>
                    <p class="text-muted small mb-0">
                        Modification de : <strong>{{ $promotion->title }}</strong>
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('promotions.update', $promotion->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body pt-3">
                    <div class="row g-3">

                        {{-- Titre --}}
                        <div class="col-12">
                            <label for="edit_title_{{ $promotion->id }}" class="form-label fw-semibold">
                                Titre de la promotion <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="edit_title_{{ $promotion->id }}"
                                   name="title"
                                   value="{{ $promotion->title }}"
                                   required>
                        </div>

                        {{-- Pourcentage --}}
                        <div class="col-md-6">
                            <label for="edit_discount_{{ $promotion->id }}" class="form-label fw-semibold">
                                Pourcentage de réduction (%)
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number"
                                       class="form-control"
                                       id="edit_discount_{{ $promotion->id }}"
                                       name="discount_percentage"
                                       value="{{ $promotion->discount_percentage }}"
                                       min="1" max="100" step="0.01"
                                       required>
                                <span class="input-group-text">
                                    <i class="bi bi-percent"></i>
                                </span>
                            </div>
                        </div>

                        {{-- Sous-catégorie --}}
                        <div class="col-md-6">
                            <label for="edit_sous_cat_{{ $promotion->id }}" class="form-label fw-semibold">
                                Sous-catégorie associée
                            </label>
                            <select class="form-select"
                                    id="edit_sous_cat_{{ $promotion->id }}"
                                    name="sous_cat_id">
                                <option value="">— Aucune —</option>
                                @foreach($sousCategories ?? [] as $sousCat)
                                    <option value="{{ $sousCat->id }}"
                                        {{ $promotion->sous_categorie_id == $sousCat->id ? 'selected' : '' }}>
                                        {{ $sousCat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date de début --}}
                        <div class="col-md-6">
                            <label for="edit_start_{{ $promotion->id }}" class="form-label fw-semibold">
                                Date de début <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   class="form-control"
                                   id="edit_start_{{ $promotion->id }}"
                                   name="start_date"
                                   value="{{ $promotion->start_date->format('Y-m-d') }}"
                                   required>
                        </div>

                        {{-- Date de fin --}}
                        <div class="col-md-6">
                            <label for="edit_end_{{ $promotion->id }}" class="form-label fw-semibold">
                                Date de fin <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   class="form-control"
                                   id="edit_end_{{ $promotion->id }}"
                                   name="end_date"
                                   value="{{ $promotion->end_date->format('Y-m-d') }}"
                                   required>
                        </div>

                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-warning text-white">
                        <i class="bi bi-pencil-fill me-1"></i>Enregistrer les modifications
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endforeach

@endsection