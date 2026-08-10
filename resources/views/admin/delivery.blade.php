@extends('layouts.app_admin')

@section('title', 'Gestion des Prix de Livraison')

@section('actions')
    <button class="btn btn-admin btn-admin-primary" data-bs-toggle="modal" data-bs-target="#modalNouvelleRegion">
        <i class="bi bi-plus-circle me-2"></i>Nouvelle région
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


{{-- Tableau --}}
<div class="admin-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="bi bi-truck me-2 text-primary"></i>Régions de livraison
        </h5>
        <span class="badge bg-primary rounded-pill">{{ $totalRegions ?? 0 }} régions</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Région</th>
                        <th>Frais de livraison</th>
                        <th style="width:120px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveryRegions as $region)
                    <tr>
                        <td class="text-muted small">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="region-icon">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <span class="fw-600">{{ $region->name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="fee-badge">
                                {{ number_format($region->fee, 0, ',', ' ') }} FCFA
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                {{-- Modifier --}}
                                <button class="btn-action btn-action-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditerRegion{{ $region->id }}"
                                        data-id="{{ $region->id }}"
                                        data-name="{{ $region->name }}"
                                        data-fee="{{ $region->fee }}"
                                        title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                {{-- Supprimer --}}
                                <form method="POST"
                                      action="{{ route('admin.delivery.destroy', $region->id) }}"
                                      onsubmit="return confirm('Supprimer la région « {{ $region->name }} » ?')">
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
                                <i class="bi bi-geo-alt" style="font-size:3rem;opacity:0.3"></i>
                                <p class="mt-3 text-muted">Aucune région de livraison configurée</p>
                                <button class="btn btn-admin btn-admin-primary btn-sm mt-2"
                                        data-bs-toggle="modal" data-bs-target="#modalNouvelleRegion">
                                    <i class="bi bi-plus me-1"></i>Ajouter une région
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
{{-- Modal : Nouvelle région                                        --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalNouvelleRegion" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-700">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>Nouvelle région
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.delivery.store') }}">
                @csrf
                <div class="modal-body pt-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-600">
                                Nom de la région <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control"
                                   placeholder="Ex: Niamey, Agadez, Zinder..."
                                   value="{{ old('name') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-600">
                                Frais de livraison <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" name="fee" class="form-control"
                                       placeholder="Ex: 1500" min="0" step="50"
                                       value="{{ old('fee') }}" required>
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
{{-- Modal : Éditer région                                          --}}
{{-- ============================================================ --}}
@forelse($deliveryRegions as $region)
<div class="modal fade" id="modalEditerRegion{{ $region->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-700">
                    <i class="bi bi-pencil me-2 text-primary"></i>Modifier la région
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.delivery.update', $region->id) }}" id="formEditerRegion">
                @csrf @method('PUT')
                <div class="modal-body pt-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-600">
                                Nom de la région <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" value="{{ $region->name }}" id="editName"
                                   class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-600">
                                Frais de livraison <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" name="fee" id="editFee"
                                       value="{{ $region->fee }}" class="form-control" min="0" step="50" required>
                                <span class="input-group-text">FCFA</span>
                            </div>
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
@empty
@endforelse
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

{{-- ============================================================ --}}
{{-- Script                                                         --}}
{{-- ============================================================ --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalEditerRegion{{ $region->id }}');

    modalEl.addEventListener('show.bs.modal', function (event) {
        const btn  = event.relatedTarget;
        const id   = btn.dataset.id;
        const name = btn.dataset.name ?? '';
        const fee  = btn.dataset.fee  ?? '';

        document.getElementById('editName').value = name;
        document.getElementById('editFee').value  = fee;

        document.getElementById('formEditerRegion').action =
            '{{ route("admin.delivery.update", ":id") }}'.replace(':id', id);
    });
});
</script>
@endpush

@endsection