@extends('layouts.app_admin')

@section('title', 'Produits de ' . $vendor->store_name)

@section('actions')
    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour aux utilisateurs
    </a>
@endsection

@section('content')

{{-- Alertes --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Infos vendeur --}}
<div class="admin-card mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            {{-- Logo ou initiales --}}
            <div class="vendor-avatar">
                @if($vendor->logo)
                    <img src="{{ Storage::url($vendor->logo) }}"
                         alt="{{ $vendor->store_name }}"
                         class="rounded-circle"
                         style="width:60px;height:60px;object-fit:cover;">
                @else
                    <div class="avatar-initials">
                        {{ strtoupper(substr($vendor->store_name ?? 'V', 0, 2)) }}
                    </div>
                @endif
            </div>

            <div class="flex-grow-1">
                <h5 class="mb-1 fw-700">{{ $vendor->store_name ?? 'Sans nom' }}</h5>
                <div class="text-muted small">
                    <i class="bi bi-person me-1"></i>{{ $vendor->user->name }}
                    &nbsp;·&nbsp;
                    <i class="bi bi-envelope me-1"></i>{{ $vendor->user->email }}
                    @if($vendor->user->phone_number)
                        &nbsp;·&nbsp;
                        <i class="bi bi-telephone me-1"></i>{{ $vendor->user->phone_number }}
                    @endif
                </div>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <span class="stat-pill">
                    <i class="bi bi-box-seam me-1"></i>
                    {{ $products->total() }} produit(s)
                </span>
                <span class="status-badge {{ $vendor->status === 'active' ? 'status-active' : 'status-inactive' }}">
                    <i class="bi bi-circle-fill me-1" style="font-size:0.5rem"></i>
                    {{ ucfirst($vendor->status) }}
                </span>
            </div>
        </div>
    </div>
</div>

{{-- Tableau produits --}}
<div class="admin-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="bi bi-box-seam me-2 text-primary"></i>
            Produits de la boutique
        </h5>
        <span class="badge bg-primary rounded-pill">{{ $products->total() }} produits</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Produit</th>
                        <th>Sous-catégorie</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Statut</th>
                        <th>Ajouté le</th>
                        <th style="width:100px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="text-muted small">{{ $loop->iteration }}</td>

                        {{-- Image + Nom --}}
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($product->images->isNotEmpty())
                                    <img src="{{ Storage::url($product->images->firstWhere('is_main', true)?->path ?? $product->images->first()->path) }}"
                                         alt="{{ $product->name }}"
                                         class="product-thumb">
                                @else
                                    <div class="product-thumb-placeholder">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-600">{{ $product->name }}</div>
                                    <div class="text-muted small text-truncate" style="max-width:200px">
                                        {{ $product->description }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Sous-catégorie --}}
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $product->sousCat->name ?? '—' }}
                            </span>
                        </td>

                        {{-- Prix --}}
                        <td>
                            <span class="fw-600 text-primary">
                                {{ number_format($product->price, 0, ',', ' ') }} FCFA
                            </span>
                        </td>

                        {{-- Stock --}}
                        <td>
                            @if($product->stock_quantity <= 0)
                                <span class="stock-badge stock-empty">Épuisé</span>
                            @elseif($product->stock_quantity <= 5)
                                <span class="stock-badge stock-low">{{ $product->stock_quantity }} restants</span>
                            @else
                                <span class="stock-badge stock-ok">{{ $product->stock_quantity }}</span>
                            @endif
                        </td>

                        {{-- Statut --}}
                        <td>
                            @if($product->is_active)
                                <span class="status-badge status-active">
                                    <i class="bi bi-circle-fill me-1" style="font-size:0.5rem"></i>Actif
                                </span>
                            @else
                                <span class="status-badge status-inactive">
                                    <i class="bi bi-circle-fill me-1" style="font-size:0.5rem"></i>Inactif
                                </span>
                            @endif
                        </td>

                        {{-- Date --}}
                        <td class="text-muted small">
                            {{ $product->created_at->format('d/m/Y') }}
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.products.show', $product->id) }}"
                                   class="btn-action btn-action-view" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form method="POST"
                                      action=""
                                      onsubmit="return confirm('Supprimer « {{ $product->name }} » ?')">
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
                        <td colspan="8" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-box-seam" style="font-size:3rem;opacity:0.3"></i>
                                <p class="mt-3 text-muted">Ce vendeur n'a aucun produit</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
    <div class="card-footer bg-white d-flex justify-content-between align-items-center px-4 py-3">
        <span class="text-muted small">
            Affichage de {{ $products->firstItem() }} à {{ $products->lastItem() }}
            sur {{ $products->total() }} produits
        </span>
        {{ $products->withQueryString()->links() }}
    </div>
    @endif
</div>

<style>
.fw-600 { font-weight: 600; }
.fw-700 { font-weight: 700; }

.avatar-initials {
    width: 60px; height: 60px;
    border-radius: 50%;
    background: var(--primary-light);
    color: var(--primary);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; font-weight: 700;
    flex-shrink: 0;
}

.stat-pill {
    background: var(--primary-light);
    color: var(--primary);
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.82rem;
    font-weight: 600;
}

.product-thumb {
    width: 48px; height: 48px;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;
    border: 1px solid var(--gray-light);
}

.product-thumb-placeholder {
    width: 48px; height: 48px;
    border-radius: 8px;
    background: var(--gray-light);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}

.stock-badge {
    padding: 0.25rem 0.65rem;
    border-radius: 50px;
    font-size: 0.78rem;
    font-weight: 600;
}
.stock-ok    { background: rgba(40,167,69,0.1);  color: var(--success); }
.stock-low   { background: rgba(255,193,7,0.1);  color: #856404; }
.stock-empty { background: rgba(220,53,69,0.1);  color: var(--danger); }

.btn-action {
    width: 32px; height: 32px;
    border: none; border-radius: 8px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 0.85rem; cursor: pointer;
    transition: var(--transition); padding: 0;
    text-decoration: none;
}
.btn-action-view   { background: rgba(40,167,69,0.1);  color: var(--success); }
.btn-action-delete { background: rgba(220,53,69,0.1);  color: var(--danger); }
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
.table tbody td { padding: 0.85rem 1.5rem; vertical-align: middle; }
.table-hover tbody tr:hover { background: #f8fbff; }
</style>

@endsection