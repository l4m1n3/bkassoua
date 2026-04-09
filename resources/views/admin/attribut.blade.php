@extends('layouts.app_admin')

@section('title', 'Attributs des sous-catégories')

@section('actions')
    <button class="btn btn-admin btn-admin-primary" data-bs-toggle="modal" data-bs-target="#modalNouvelAttribut">
        <i class="bi bi-plus-circle me-2"></i>Nouvel attribut
    </button>
@endsection

@section('content')

{{-- Stats --}}
<div class="stats-grid mb-4">
    <div class="stat-card">
        <div class="stat-icon"><i class="bi bi-sliders"></i></div>
        <div class="stat-value">{{ $totalAttributs ?? 0 }}</div>
        <div class="stat-label">Total attributs</div>
    </div>
    <div class="stat-card success">
        <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
        <div class="stat-value">{{ $attributsActifs ?? 0 }}</div>
        <div class="stat-label">Attributs actifs</div>
    </div>
    <div class="stat-card warning">
        <div class="stat-icon"><i class="bi bi-diagram-2"></i></div>
        <div class="stat-value">{{ $sousCategoriesCount ?? 0 }}</div>
        <div class="stat-label">Sous-catégories</div>
    </div>
    <div class="stat-card danger">
        <div class="stat-icon"><i class="bi bi-x-circle"></i></div>
        <div class="stat-value">{{ $attributsInactifs ?? 0 }}</div>
        <div class="stat-label">Attributs inactifs</div>
    </div>
</div>

{{-- Filtres --}}
<div class="admin-card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-500 text-muted small">Rechercher</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                           placeholder="Nom d'attribut..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-500 text-muted small">Sous-catégorie</label>
                <select name="sous_categorie" class="form-select">
                    <option value="">Toutes les sous-catégories</option>
                    @foreach($sousCategories ?? [] as $sc)
                        <option value="{{ $sc->id }}" {{ request('sous_categorie') == $sc->id ? 'selected' : '' }}>
                            {{ $sc->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-admin btn-admin-primary w-100">
                    <i class="bi bi-funnel me-1"></i>Filtrer
                </button>
                <a href="" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tableau --}}
<div class="admin-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title">
            <i class="bi bi-sliders me-2 text-primary"></i>Liste des attributs
        </h5>
        {{-- <span class="badge bg-primary rounded-pill">{{ $attributes->total() ?? 0 }} attributs</span> --}}
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Nom de l'attribut</th>
                        {{-- <th>Sous-catégorie</th> --}}
                        <th>Type</th>
                        <th>Valeurs</th>
                        <th>Statut</th>
                        <th style="width:140px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attributes ?? [] as $attribut)
                    <tr>
                        <td class="text-muted small">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="attr-icon">
                                    <i class="bi bi-tag"></i>
                                </div>
                                <div>
                                    <div class="fw-600">{{ $attribut->name }}</div>
                                    <div class="text-muted small">{{ $attribut->slug }}</div>
                                </div>
                            </div>
                        </td>
                        {{-- <td>
                            <span class="badge bg-light text-dark border">
                                {{ $attribut->sousCategorie->name ?? '—' }}
                            </span>
                        </td> --}}
                        <td>
                            <span class="type-badge type-{{ $attribut->type ?? 'texte' }}">
                                {{ ucfirst($attribut->type ?? 'Texte') }}
                            </span>
                        </td>
                        <td>
                            <div class="valeurs-preview">
                                @foreach(($attribut->options->pluck('value') ?? []) as $valeur)
                                    <span class="valeur-pill">{{ $valeur }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            @if(($attribut->statut ?? 'actif') === 'actif')
                                <span class="status-badge status-active">
                                    <i class="bi bi-circle-fill me-1" style="font-size:0.5rem"></i>Actif
                                </span>
                            @else
                                <span class="status-badge status-inactive">
                                    <i class="bi bi-circle-fill me-1" style="font-size:0.5rem"></i>Inactif
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn-action btn-action-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditerAttribut"
                                        data-id="{{ $attribut->id }}"
                                        data-nom="{{ $attribut->nom }}"
                                        title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn-action btn-action-view" title="Voir les valeurs">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <form method="POST" action=""
                                      onsubmit="return confirm('Supprimer cet attribut ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-action-delete" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-sliders"></i>
                                <p>Aucun attribut trouvé</p>
                                <button class="btn btn-admin btn-admin-primary btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#modalNouvelAttribut">
                                    <i class="bi bi-plus me-1"></i>Créer le premier attribut
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(isset($attributs) && $attributs->hasPages())
    <div class="card-footer bg-white d-flex justify-content-between align-items-center px-4 py-3">
        <span class="text-muted small">
            Affichage de {{ $attributs->firstItem() }} à {{ $attributs->lastItem() }}
            sur {{ $attributs->total() }} attributs
        </span>
        {{ $attributs->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- Modal : Nouvel attribut --}}
<div class="modal fade" id="modalNouvelAttribut" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-700">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>Nouvel attribut
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.attributes.store') }}">
                @csrf
                <div class="modal-body pt-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-600">Nom de l'attribut <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Ex: Couleur, Taille..." required>
                        </div>
                        {{-- <div class="col-md-6">
                            <label class="form-label fw-600">Sous-catégorie <span class="text-danger">*</span></label>
                            <select name="sous_cat_id" class="form-select" required>
                                <option value="">Sélectionner une sous-catégorie</option>
                                @foreach($sousCategories ?? [] as $sc)
                                    <option value="{{ $sc->id }}">{{ $sc->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="col-md-6">
                            <label class="form-label fw-600">Type d'attribut</label>
                            <select name="type" class="form-select" id="typeAttribut">
                                <option value="texte">Texte</option>
                                <option value="couleur">Couleur</option>
                                <option value="nombre">Nombre</option>
                                <option value="booleen">Oui / Non</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-600">Valeurs possibles
                                <span class="text-muted small fw-400">(séparées par une virgule)</span>
                            </label>
                            <input type="text" name="value" class="form-control"
                                   placeholder="Ex: Rouge, Vert, Bleu  ou  S, M, L, XL">
                            <div class="form-text">Laissez vide si les valeurs sont libres.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-admin btn-admin-primary">
                        <i class="bi bi-check-circle me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal : Éditer attribut --}}
<div class="modal fade" id="modalEditerAttribut" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-700">
                    <i class="bi bi-pencil me-2 text-primary"></i>Modifier l'attribut
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" id="formEditerAttribut">
                @csrf @method('PUT')
                <div class="modal-body pt-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-600">Nom de l'attribut <span class="text-danger">*</span></label>
                            <input type="text" name="nom" id="editNom" class="form-control" value="{{ '' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600">Sous-catégorie <span class="text-danger">*</span></label>
                            <select name="sous_categorie_id" id="editSousCategorie" class="form-select" required>
                                <option value="">Sélectionner une sous-catégorie</option>
                                @foreach($sousCategories ?? [] as $sc)
                                    <option value="{{ $sc->id }}">{{ $sc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600">Type d'attribut</label>
                            <select name="type" id="editType" class="form-select">
                                <option value="texte">Texte</option>
                                <option value="couleur">Couleur</option>
                                <option value="nombre">Nombre</option>
                                <option value="booleen">Oui / Non</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600">Statut</label>
                            <select name="statut" id="editStatut" class="form-select">
                                <option value="actif">Actif</option>
                                <option value="inactif">Inactif</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-600">Valeurs possibles
                                <span class="text-muted small fw-400">(séparées par une virgule)</span>
                            </label>
                            <input type="text" name="valeurs" id="editValeurs" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-600">Description</label>
                            <textarea name="description" id="editDescription" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-admin btn-admin-primary">
                        <i class="bi bi-check-circle me-2"></i>Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.attr-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: var(--primary-light);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.fw-600 { font-weight: 600; }
.fw-700 { font-weight: 700; }
.fw-500 { font-weight: 500; }

.type-badge {
    padding: 0.25rem 0.65rem;
    border-radius: 50px;
    font-size: 0.78rem;
    font-weight: 600;
}
.type-texte   { background: #e3f2fd; color: #1780d6; }
.type-couleur { background: #fce4ec; color: #e91e63; }
.type-nombre  { background: #e8f5e9; color: #2e7d32; }
.type-booleen { background: #fff3e0; color: #e65100; }

.valeurs-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    max-width: 200px;
}

.valeur-pill {
    background: var(--gray-light);
    color: var(--dark);
    border-radius: 50px;
    padding: 0.15rem 0.55rem;
    font-size: 0.75rem;
    font-weight: 500;
}

.btn-action {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    cursor: pointer;
    transition: var(--transition);
    padding: 0;
}
.btn-action-edit   { background: #e3f2fd; color: #1780d6; }
.btn-action-view   { background: #e8f5e9; color: #2e7d32; }
.btn-action-delete { background: #fdecea; color: #dc3545; }
.btn-action:hover  { opacity: 0.8; transform: scale(1.1); }

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    color: var(--gray);
}
.empty-state i {
    font-size: 3rem;
    opacity: 0.3;
}
.empty-state p { margin: 0; font-size: 1rem; }

.admin-table .table thead th,
.table thead th {
    background: var(--primary-light);
    color: var(--primary);
    font-weight: 600;
    border: none;
    padding: 1rem 1.5rem;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.table tbody td { padding: 0.9rem 1.5rem; vertical-align: middle; }
.table-hover tbody tr:hover { background: #f8fbff; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Pré-remplissage du modal d'édition
    document.querySelectorAll('[data-bs-target="#modalEditerAttribut"]').forEach(btn => {
        btn.addEventListener('click', function () {
            const id  = this.dataset.id;
            const nom = this.dataset.nom;
            document.getElementById('editNom').value = nom ?? '';
            document.getElementById('formEditerAttribut').action =
                `/admin/attributes/${id}`;
        });
    });
});
</script>

@endsection