@extends('layouts.app_admin')

@section('title', '')

@section('content')

<div class="categories-container">
 <div class="page-header">
        <div class="header-content">
            <div class="header-text">
                <h1 class="page-title">Gestion des Partenariats</h1>
                <p class="page-subtitle">Organisez et gérez les partenariats de votre plateforme</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPartnerModal">
                    <i class="bi bi-plus-circle me-2"></i>Nouveau partenariat
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
                <div class="stat-label">Total partenariats</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-success">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Partenariats actifs</div>
            </div>
        </div>
    </div>

    <!-- Liste des partenaires -->
    <div class="admin-card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="bi bi-grid me-2"></i>Liste des partenaires
            </h5>
            <div class="card-actions">
                <span class="text-muted">{{ $ads->count() }} partenariat(s)</span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($ads->isEmpty())
                <div class="empty-state text-center py-5">
                    <i class="bi bi-tags display-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">Aucun partenaire trouvée</h4>
                    <p class="text-muted mb-4">Commencez par créer votre premier partenaire.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPartnerModal">
                        <i class="bi bi-plus-circle me-2"></i>Créer un partenaire
                    </button>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th>Nom Partenaire</th>
                                <th>Description Partenairiat</th>
                                <th>Logo</th>
                                <th>Statut</th>
                                <th>Création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ads as $ad)
                            <tr class="promotion-row" data-promotion-id="{{ $ad->id }}">
                                <td>
                                    <div class="promotion-id">#{{ $ad->id }}</div>
                                </td>
                                <td>
                                    <div class="category-info">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <div class="category-name">{{ $ad->title }}</div>
                                                <small class="text-muted">Dernière modif: {{ $ad->updated_at->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="slug-text" style="margin: 0;">
                                        {{ \Illuminate\Support\Str::limit($ad->description, 20) }}
                                    </p>
                                </td>
                                <td>
                                    @if($ad->image_url)
                                        <img src="{{ asset('' . $ad->image_url) }}" alt="Logo {{ $ad->title }}" class="ad-logo" style="max-height:50px; border-radius:6px; border:1px solid #dee2e6;">
                                    @else
                                        <span class="text-muted">Aucun logo</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge {{ $ad->is_active ? 'status-active' : 'status-inactive' }}">
                                        <i class="bi {{ $ad->is_active ? 'bi-check-circle' : 'bi-dash-circle' }} me-1"></i>
                                        {{ $ad->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="date-info">
                                        <div>{{ $ad->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $ad->created_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewPartnerModal{{ $ad->id }}"
                                                title="Voir les détails">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <button class="btn btn-sm btn-outline-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#updateSubCategoryModal{{ $ad->id }}"
                                                title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <button class="btn btn-sm btn-danger"
                                                onclick="confirmDeleteSub({{ $ad->id }}, '{{ $ad->title }}')"
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
     MODAL — AJOUTER UN PARTENAIRE
     ============================================================ --}}
<div class="modal fade" id="addPartnerModal" tabindex="-1"
     aria-labelledby="addPartnerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="addPartnerModalLabel">
                        <i class="bi bi-plus-circle-fill text-primary me-2"></i>Nouveau partenariat
                    </h5>
                    <p class="text-muted small mb-0">
                        Remplissez les informations pour créer un nouveau partenariat
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body pt-3">
                    <div class="row g-3">

                        {{-- Nom du partenaire --}}
                        <div class="col-12">
                            <label for="add_title" class="form-label fw-semibold">
                                Nom du partenaire <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="add_title"
                                   name="title"
                                   value="{{ old('title') }}"
                                   placeholder="Ex : Acme Corporation"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <label for="add_description" class="form-label fw-semibold">
                                Description du partenariat
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="add_description"
                                      name="description"
                                      rows="3"
                                      placeholder="Décrivez la nature du partenariat...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- URL du site --}}
                        <div class="col-md-8">
                            <label for="add_url" class="form-label fw-semibold">
                                Site web du partenaire
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                                <input type="url"
                                       class="form-control @error('url') is-invalid @enderror"
                                       id="add_url"
                                       name="url"
                                       value="{{ old('url') }}"
                                       placeholder="https://www.exemple.com">
                                @error('url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Statut --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Statut</label>
                            <div class="d-flex flex-column gap-2 mt-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                           name="is_active" id="add_active_yes" value="1"
                                           {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="add_active_yes">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-check-circle me-1"></i>Actif
                                        </span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                           name="is_active" id="add_active_no" value="0"
                                           {{ old('is_active') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="add_active_no">
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                            <i class="bi bi-dash-circle me-1"></i>Inactif
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Logo --}}
                        <div class="col-12">
                            <label for="add_image" class="form-label fw-semibold">Logo du partenaire</label>
                            <input type="file"
                                   class="form-control @error('image_url') is-invalid @enderror"
                                   id="add_image"
                                   name="image"
                                   accept="image/*"
                                   onchange="previewImage(this, 'add_preview')">
                            @error('image_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2" id="add_preview_wrapper" style="display:none;">
                                <img id="add_preview" src="#" alt="Aperçu logo"
                                     style="max-height:80px; border-radius:6px; border:1px solid #dee2e6;">
                            </div>
                            <div class="form-text">Formats acceptés : JPG, PNG, SVG, WEBP. Max 2 Mo.</div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Créer le partenariat
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


{{-- ============================================================
     MODAUX — MODIFIER UN PARTENAIRE (un modal par entrée)
     ============================================================ --}}
@foreach ($ads as $ad)
<div class="modal fade" id="updateSubCategoryModal{{ $ad->id }}" tabindex="-1"
     aria-labelledby="updatePartnerLabel{{ $ad->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="updatePartnerLabel{{ $ad->id }}">
                        <i class="bi bi-pencil-fill text-warning me-2"></i>Modifier le partenariat
                    </h5>
                    <p class="text-muted small mb-0">
                        Modification de : <strong>{{ $ad->title }}</strong>
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.ads.update', $ad->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-body pt-3">
                    <div class="row g-3">

                        {{-- Nom du partenaire --}}
                        <div class="col-12">
                            <label for="edit_title_{{ $ad->id }}" class="form-label fw-semibold">
                                Nom du partenaire <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="edit_title_{{ $ad->id }}"
                                   name="title"
                                   value="{{ $ad->title }}"
                                   required>
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <label for="edit_desc_{{ $ad->id }}" class="form-label fw-semibold">
                                Description du partenariat
                            </label>
                            <textarea class="form-control"
                                      id="edit_desc_{{ $ad->id }}"
                                      name="description"
                                      rows="3">{{ $ad->description ?? '' }}</textarea>
                        </div>

                        {{-- URL du site --}}
                        <div class="col-md-8">
                            <label for="edit_url_{{ $ad->id }}" class="form-label fw-semibold">
                                Site web du partenaire
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                                <input type="url"
                                       class="form-control"
                                       id="edit_url_{{ $ad->id }}"
                                       name="url"
                                       value="{{ $ad->url ?? '' }}"
                                       placeholder="https://www.exemple.com">
                            </div>
                        </div>

                        {{-- Statut --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Statut</label>
                            <div class="d-flex flex-column gap-2 mt-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                           name="is_active"
                                           id="edit_active_yes_{{ $ad->id }}"
                                           value="1"
                                           {{ $ad->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edit_active_yes_{{ $ad->id }}">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-check-circle me-1"></i>Actif
                                        </span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                           name="is_active"
                                           id="edit_active_no_{{ $ad->id }}"
                                           value="0"
                                           {{ !$ad->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edit_active_no_{{ $ad->id }}">
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                            <i class="bi bi-dash-circle me-1"></i>Inactif
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Logo actuel + nouveau --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Logo du partenaire</label>

                            @if($ad->image_url)
                                <div class="mb-2 d-flex align-items-center gap-3">
                                    <img src="{{ asset('' . $ad->image_url) }}"
                                         alt="Logo actuel"
                                         style="max-height:60px; border-radius:6px; border:1px solid #dee2e6;">
                                    <span class="text-muted small">Logo actuel</span>
                                </div>
                            @endif

                            <input type="file"
                                   class="form-control"
                                   id="edit_image_{{ $ad->id }}"
                                   name="image"
                                   accept="image/*"
                                   onchange="previewImage(this, 'edit_preview_{{ $ad->id }}')">

                            <div class="mt-2" id="edit_preview_wrapper_{{ $ad->id }}" style="display:none;">
                                <img id="edit_preview_{{ $ad->id }}" src="#" alt="Aperçu nouveau logo"
                                     style="max-height:80px; border-radius:6px; border:1px solid #dee2e6;">
                            </div>
                            <div class="form-text">Laissez vide pour conserver le logo actuel. Max 2 Mo.</div>
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


{{-- ============================================================
     SCRIPT — Aperçu image avant upload
     ============================================================ --}}
@push('scripts')
<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const wrapper = document.getElementById(previewId.replace('preview', 'preview_wrapper'));
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            if (wrapper) wrapper.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

@endsection