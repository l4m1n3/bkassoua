@extends('layouts.app_admin')

@section('title', 'Quartiers & Tarifs de Livraison')

@section('actions')
    <button class="btn btn-admin btn-admin-outline me-2" data-bs-toggle="modal" data-bs-target="#modalNouveauQuartier">
        <i class="bi bi-plus-circle me-2"></i>Nouveau quartier
    </button>
    <button class="btn btn-admin btn-admin-primary" data-bs-toggle="modal" data-bs-target="#modalNouveauTarif">
        <i class="bi bi-plus-circle me-2"></i>Nouveau tarif
    </button>
@endsection

@section('content')

{{-- Alertes --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ============================================================ --}}
{{-- Section : Quartiers                                            --}}
{{-- ============================================================ --}}
<div class="admin-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="bi bi-signpost-2 me-2 text-primary"></i>Quartiers
        </h5>
        <span class="badge bg-primary rounded-pill">{{ $totalZones ?? 0 }} quartiers</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Quartier</th>
                        <th style="width:120px">Statut</th>
                        <th style="width:120px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($zones as $zone)
                    <tr>
                        <td class="text-muted small">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="region-icon">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <span class="fw-600">{{ $zone->name }}</span>
                            </div>
                        </td>
                        <td>
                            @if($zone->is_active)
                                <span class="status-badge status-delivered">Actif</span>
                            @else
                                <span class="status-badge status-cancelled">Inactif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn-action btn-action-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditerQuartier{{ $zone->id }}"
                                        title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST"
                                      action="{{ route('admin.package-delivery-zone.destroy', $zone->id) }}"
                                      onsubmit="return confirm('Supprimer le quartier « {{ $zone->name }} » ?')">
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
                        <td colspan="4" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-signpost-2" style="font-size:3rem;opacity:0.3"></i>
                                <p class="mt-3 text-muted">Aucun quartier configuré</p>
                                <button class="btn btn-admin btn-admin-primary btn-sm mt-2"
                                        data-bs-toggle="modal" data-bs-target="#modalNouveauQuartier">
                                    <i class="bi bi-plus me-1"></i>Ajouter un quartier
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- Section : Grille tarifaire                                     --}}
{{-- ============================================================ --}}
<div class="admin-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="bi bi-cash-coin me-2 text-primary"></i>Grille tarifaire
        </h5>
        <span class="badge bg-primary rounded-pill">{{ $totalPrices ?? 0 }} tarifs</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Départ</th>
                        <th>Arrivée</th>
                        <th>Taille du colis</th>
                        <th>Prix</th>
                        <th style="width:120px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($zonePrices as $price)
                    <tr>
                        <td class="text-muted small">{{ $loop->iteration }}</td>
                        <td><span class="fw-600">{{ $price->fromZone->name ?? '—' }}</span></td>
                        <td>
                            <i class="bi bi-arrow-right text-muted me-1"></i>
                            <span class="fw-600">{{ $price->toZone->name ?? '—' }}</span>
                        </td>
                        <td>
                            @php
                                $sizeLabels = ['small' => 'Petit colis', 'medium' => 'Colis moyen', 'large' => 'Gros colis'];
                            @endphp
                            <span class="size-badge">{{ $sizeLabels[$price->package_size] ?? $price->package_size }}</span>
                        </td>
                        <td>
                            <span class="fee-badge">{{ number_format($price->price, 0, ',', ' ') }} FCFA</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn-action btn-action-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditerTarif{{ $price->id }}"
                                        title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST"
                                      action="{{ route('admin.package-delivery-zone-price.destroy', $price->id) }}"
                                      onsubmit="return confirm('Supprimer ce tarif ?')">
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
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-cash-coin" style="font-size:3rem;opacity:0.3"></i>
                                <p class="mt-3 text-muted">Aucun tarif configuré</p>
                                <button class="btn btn-admin btn-admin-primary btn-sm mt-2"
                                        data-bs-toggle="modal" data-bs-target="#modalNouveauTarif">
                                    <i class="bi bi-plus me-1"></i>Ajouter un tarif
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- Modal : Nouveau quartier                                       --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalNouveauQuartier" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-700">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>Nouveau quartier
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.package-delivery-zone.store') }}">
                @csrf
                <div class="modal-body pt-3">
                    <label class="form-label fw-600">Nom du quartier <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control"
                           placeholder="Ex: Plateau, Yantala, Lazaret..."
                           value="{{ old('name') }}" required>
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

{{-- ============================================================ --}}
{{-- Modals : Éditer quartier (une par quartier)                   --}}
{{-- ============================================================ --}}
@foreach($zones as $zone)
<div class="modal fade" id="modalEditerQuartier{{ $zone->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-700">
                    <i class="bi bi-pencil me-2 text-primary"></i>Modifier le quartier
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.package-delivery-zone.update', $zone->id) }}">
                @csrf @method('PUT')
                <div class="modal-body pt-3">
                    <div class="mb-3">
                        <label class="form-label fw-600">Nom du quartier <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ $zone->name }}" class="form-control" required>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                               id="active{{ $zone->id }}" {{ $zone->is_active ? 'checked' : '' }}>
                        <label class="form-check-label fw-600" for="active{{ $zone->id }}">Quartier actif</label>
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
@endforeach

{{-- ============================================================ --}}
{{-- Modal : Nouveau tarif                                          --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalNouveauTarif" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-700">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>Nouveau tarif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.package-delivery-zone-price.store') }}">
                @csrf
                <div class="modal-body pt-3">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-600">Quartier de départ <span class="text-danger">*</span></label>
                            <select name="from_zone_id" class="form-select" required>
                                <option value="">Choisir...</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-600">Quartier d'arrivée <span class="text-danger">*</span></label>
                            <select name="to_zone_id" class="form-select" required>
                                <option value="">Choisir...</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-600">Taille du colis <span class="text-danger">*</span></label>
                            <select name="package_size" class="form-select" required>
                                <option value="small">Petit colis</option>
                                <option value="medium">Colis moyen</option>
                                <option value="large">Gros colis</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-600">Prix <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="price" class="form-control"
                                       placeholder="Ex: 1000" min="0" step="50" required>
                                <span class="input-group-text">FCFA</span>
                            </div>
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

{{-- ============================================================ --}}
{{-- Modals : Éditer tarif (un par tarif)                           --}}
{{-- ============================================================ --}}
@foreach($zonePrices as $price)
<div class="modal fade" id="modalEditerTarif{{ $price->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-700">
                    <i class="bi bi-pencil me-2 text-primary"></i>Modifier le tarif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.package-delivery-zone-price.update', $price->id) }}">
                @csrf @method('PUT')
                <div class="modal-body pt-3">
                    <p class="text-muted mb-3">
                        <i class="bi bi-info-circle me-1"></i>
                        {{ $price->fromZone->name ?? '—' }} → {{ $price->toZone->name ?? '—' }}
                        ({{ $sizeLabels[$price->package_size] ?? $price->package_size }})
                    </p>
                    <label class="form-label fw-600">Prix <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" name="price" value="{{ $price->price }}"
                               class="form-control" min="0" step="50" required>
                        <span class="input-group-text">FCFA</span>
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
@endforeach

{{-- ============================================================ --}}
{{-- Styles                                                         --}}
{{-- ============================================================ --}}
<style>
.fw-600 { font-weight: 600; }
.fw-700 { font-weight: 700; }

.region-icon {
    width: 34px; height: 34px;
    border-radius: 8px;
    background: var(--primary-light);
    color: var(--primary);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.95rem; flex-shrink: 0;
}

.fee-badge {
    background: rgba(40, 167, 69, 0.1);
    color: var(--success);
    padding: 0.3rem 0.75rem;
    border-radius: 50px;
    font-size: 0.82rem;
    font-weight: 600;
}

.size-badge {
    background: rgba(108, 99, 255, 0.1);
    color: #6c63ff;
    padding: 0.3rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-badge {
    display: inline-block;
    padding: 0.3rem 0.75rem;
    border-radius: 50px;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.3px;
}
.status-pending    { background: rgba(255, 193, 7, 0.15);  color: #b8860b; }
.status-accepted,
.status-picked_up,
.status-in_transit { background: rgba(67, 127, 255, 0.15); color: var(--primary); }
.status-delivered  { background: rgba(40, 167, 69, 0.15);  color: var(--success); }
.status-cancelled  { background: rgba(220, 53, 69, 0.15);  color: var(--danger); }

.btn-action {
    width: 32px; height: 32px;
    border: none; border-radius: 8px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 0.85rem; cursor: pointer;
    transition: var(--transition); padding: 0;
}
.btn-action-edit   { background: var(--primary-light); color: var(--primary); }
.btn-action-delete { background: rgba(220, 53, 69, 0.1); color: var(--danger); }
.btn-action:hover  { opacity: 0.8; transform: scale(1.1); }

.table thead th {
    background: var(--primary-light);
    color: var(--primary);
    font-weight: 600; border: none;
    padding: 1rem 1.5rem;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.table tbody td { padding: 0.9rem 1.5rem; vertical-align: middle; }
.table-hover tbody tr:hover { background: #f8fbff; }
</style>

@endsection