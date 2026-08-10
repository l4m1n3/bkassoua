@extends('layouts.slaves')

@section('title', $category->name)

@section('content')
<div class="container py-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop') }}">Boutique</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('shop.category', $category->slug) }}">{{ $category->name }}</a>
            </li>
            @if($activeSubCat)
            <li class="breadcrumb-item active">{{ $activeSubCat->name }}</li>
            @endif
        </ol>
    </nav>

    <div class="row">

        {{-- Sidebar sous-catégories --}}
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="sidebar">
                <h5 class="sidebar-title">
                    <i class="bi bi-list-ul"></i>
                    {{ $category->name }}
                </h5>

                {{-- Lien "Tous les produits" --}}
                <a href="{{ route('shop.category', $category->slug) }}"
                   class="sub-all-link {{ !$activeSubCat ? 'active' : '' }}">
                    <i class="bi bi-grid-3x3-gap me-2"></i>
                    Tous les produits
                </a>

                @forelse($category->sousCat as $sub)
                <a href="{{ route('shop.category', [$category->slug, 'sub' => $sub->slug]) }}"
                   class="sub-all-link {{ $activeSubCat?->id === $sub->id ? 'active' : '' }}">
                    <i class="bi bi-dot" style="font-size:1.3rem"></i>
                    {{ $sub->name }}
                </a>
                @empty
                <p class="text-muted small px-2 mt-2">Aucune sous-catégorie</p>
                @endforelse
            </div>
        </div>

        {{-- Contenu principal --}}
        <div class="col-lg-9 col-md-8">

            {{-- Titre section --}}
            <div class="section-title mb-4">
                <h2>{{ $activeSubCat ? $activeSubCat->name : $category->name }}</h2>
                <p class="text-muted">{{ $products->total() }} produit(s) trouvé(s)</p>
            </div>

            {{-- Grille produits --}}
            @if($products->isNotEmpty())
            <div class="products-grid">
                @foreach($products as $product)
                <div class="product-card fade-in">
                    <div class="product-image">
                        <img
                            src="{{ $product->image ? asset('public/storage/' . $product->image) : asset('images/default-product.jpg') }}"
                            alt="{{ $product->name }}"
                        >
                        <div class="product-actions">
                            <button class="action-btn" title="Ajouter aux favoris">
                                <i class="bi bi-heart"></i>
                            </button>
                            <button class="action-btn" title="Vue rapide">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-category">
                            {{ $activeSubCat ? $activeSubCat->name : $category->name }}
                        </div>
                        <h3 class="product-title">{{ $product->name }}</h3>
                        <div class="product-price">
                            <span class="current-price">
                                {{ number_format($product->price, 0, ',', ' ') }} fcfa
                            </span>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('shop.detail', $product->id) }}"
                               class="btn btn-outline-primary flex-fill d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-eye"></i>
                                <span>Voir détails</span>
                            </a>
                            <button class="btn btn-primary add-to-cart"
                                    data-product-id="{{ $product->id }}">
                                <i class="bi bi-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-5 d-flex justify-content-center">
                {{ $products->links() }}
            </div>

            @else
            {{-- Aucun produit --}}
            <div class="text-center py-5">
                <i class="bi bi-box-seam" style="font-size:3rem;color:var(--primary)"></i>
                <h5 class="mt-3">Aucun produit disponible</h5>
                <p class="text-muted">Revenez bientôt, de nouveaux produits arrivent.</p>
                <a href="{{ route('shop') }}" class="btn btn-outline-primary mt-2">
                    <i class="bi bi-arrow-left me-2"></i> Retour à la boutique
                </a>
            </div>
            @endif

        </div>
    </div>
</div>

<style>
.sub-all-link {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    font-size: .9rem;
    color: var(--dark, #1a1a2e);
    text-decoration: none;
    border-radius: var(--border-radius, 8px);
    transition: background .15s, color .15s;
    margin-bottom: 2px;
}
.sub-all-link:hover,
.sub-all-link.active {
    background: var(--gray-light, #f0f0f0);
    color: var(--primary, #6c63ff);
    font-weight: 500;
}
.sub-all-link.active {
    border-left: 3px solid var(--primary, #6c63ff);
    padding-left: 9px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.add-to-cart').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-product-id');
            this.innerHTML = '<i class="bi bi-check-lg"></i>';
            this.classList.replace('btn-primary', 'btn-success');
            setTimeout(() => {
                this.innerHTML = '<i class="bi bi-cart-plus"></i>';
                this.classList.replace('btn-success', 'btn-primary');
            }, 2000);
            console.log('Ajout panier:', id);
        });
    });
});
</script>

@endsection