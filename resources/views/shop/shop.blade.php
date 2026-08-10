@extends('layouts.slaves')

@section('title', 'Boutique')

@section('content')
<div class="container-fluid py-4">
    <div class="row">

        <!-- ===================== SIDEBAR ===================== -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="sidebar">
                <h5 class="sidebar-title">
                    <i class="bi bi-filter-circle me-2"></i>
                    Catégories
                </h5>

                <div class="filter-section">
                    @forelse($categories as $category)
                    <div class="category-item">

                        {{-- Bouton catégorie principale --}}
                        <button
                            class="category-btn {{ $category->sousCat->isNotEmpty() ? 'has-children' : '' }}"
                            type="button"
                            @if($category->sousCat->isNotEmpty())
                                data-bs-toggle="collapse"
                                data-bs-target="#cat{{ $category->id }}"
                                aria-expanded="false"
                                aria-controls="cat{{ $category->id }}"
                            @endif
                            onclick="{{ $category->sousCat->isEmpty() ? "window.location='/shop/{$category->slug}'" : '' }}"
                        >
                            {{-- Icône ou miniature --}}
                            @if($category->image)
                                <img
                                    src="{{ asset('public/storage/' . $category->image) }}"
                                    alt="{{ $category->name }}"
                                    class="category-thumb"
                                >
                            @else
                                <span class="category-icon-placeholder">
                                    <i class="bi bi-tag"></i>
                                </span>
                            @endif

                            <span class="category-name">{{ $category->name }}</span>

                            @if($category->sousCat->isNotEmpty())
                                <i class="bi bi-chevron-right category-arrow"></i>
                            @endif
                        </button>

                        {{-- Sous-catégories --}}
                        @if($category->sousCat->isNotEmpty())
                        <div class="collapse sub-categories" id="cat{{ $category->id }}">
                            <ul class="sub-list">
                                {{-- Lien "Tous" pour la catégorie --}}
                                <li class="sub-item">
                                    <a href="{{ route('shop.category', $category->slug) }}" class="sub-link">
                                        <i class="bi bi-grid-3x3-gap" style="font-size:.8rem"></i>
                                        Tous — {{ $category->name }}
                                    </a>
                                </li>
                                @foreach($category->sousCat as $subCategory)
                                <li class="sub-item">
                                    <a
                                        href="{{ route('shop.category', [$category->slug, 'sub' => $subCategory->slug]) }}"
                                        class="sub-link"
                                    >
                                        <i class="bi bi-dot"></i>
                                        {{ $subCategory->name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                    </div>
                    @empty
                    <p class="text-muted small px-2">Aucune catégorie disponible</p>
                    @endforelse
                </div>
            </div>

            <!-- Bannière promotionnelle -->
            <div class="sidebar mt-4 text-white"
                 style="background: linear-gradient(135deg, var(--primary), var(--accent));">
                <div class="text-center">
                    <i class="bi bi-truck display-6 mb-3"></i>
                    <h6>Livraison Rapide</h6>
                    <p class="small mb-0">Commandez maintenant</p>
                </div>
            </div>
        </div>
        {{-- /Sidebar --}}


        <!-- ===================== CONTENU PRINCIPAL ===================== -->
        <div class="col-lg-9 col-md-8">

            <!-- En-tête -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Tous les Produits</h2>
                    <p class="text-muted mb-0">
                        Affichage de <strong>{{ $products->total() }}</strong>
                        produit{{ $products->total() > 1 ? 's' : '' }}
                    </p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <!-- Boutons vue grille / liste -->
                    <div class="d-none d-md-block">
                        <div class="btn-group" role="group">
                            <button type="button" id="viewGrid" class="btn btn-outline-primary active" title="Grille">
                                <i class="bi bi-grid"></i>
                            </button>
                            <button type="button" id="viewList" class="btn btn-outline-primary" title="Liste">
                                <i class="bi bi-list"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Tri -->
                    <select class="form-select sort-select w-auto" id="sortSelect">
                        <option value="popular">Trier par : Popularité</option>
                        <option value="price_asc">Prix : Croissant</option>
                        <option value="price_desc">Prix : Décroissant</option>
                        <option value="newest">Nouveautés</option>
                        <option value="best_seller">Meilleures ventes</option>
                    </select>
                </div>
            </div>

            <!-- Grille de produits -->
            @if ($products->isEmpty())
            <div class="empty-state text-center py-5">
                <i class="bi bi-search display-1 text-muted"></i>
                <h4 class="mt-3 text-muted">Aucun produit trouvé</h4>
                <p class="text-muted">Essayez de modifier vos critères de recherche ou vos filtres.</p>
                <a href="{{ route('shop') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser les filtres
                </a>
            </div>

            @else
            <div class="products-grid" id="productsGrid">
                @foreach ($products as $product)
                <div class="product-card fade-in">
                    <div class="product-image">
                        <img
                            src="{{ $product->mainImage
                                ? asset('storage/' . $product->mainImage->path)
                                : asset('images/default-product.jpg') }}"
                            alt="{{ $product->name }}"
                            loading="lazy"
                        >
                        @if($product->discount > 0)
                            <span class="product-badge">-{{ $product->discount }}%</span>
                        @endif
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
                        <div class="product-category">{{ $product->sousCat->name ?? 'Non catégorisé' }}</div>
                        <h3 class="product-title">{{ $product->name }}</h3>

                        <div class="product-rating">
                            <div class="stars">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-half"></i>
                            </div>
                            <span class="rating-count">({{ rand(10, 50) }})</span>
                        </div>

                        <div class="product-price">
                            @if($product->discount > 0)
                                <span class="current-price">
                                    {{ number_format($product->price * (1 - $product->discount / 100), 0, ',', ' ') }} fcfa
                                </span>
                                <span class="original-price">
                                    {{ number_format($product->price, 0, ',', ' ') }} fcfa
                                </span>
                            @else
                                <span class="current-price">
                                    {{ number_format($product->price, 0, ',', ' ') }} fcfa
                                </span>
                            @endif
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('shop.detail', $product->id) }}"
                               class="btn btn-outline-primary flex-fill d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-eye"></i>
                                <span class="d-none d-sm-inline">Voir détails</span>
                            </a>
                            <button class="btn btn-primary add-to-cart" data-product-id="{{ $product->id }}">
                                <i class="bi bi-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination Laravel -->
            <div class="mt-5 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
            @endif

        </div>
        {{-- /Contenu principal --}}

    </div>
</div>


<!-- ===================== SCRIPTS ===================== -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ---- Rotation flèche collapse Bootstrap ---- */
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function (btn) {
        const targetId = btn.getAttribute('data-bs-target');
        if (!targetId) return;
        const collapseEl = document.querySelector(targetId);
        if (!collapseEl) return;

        collapseEl.addEventListener('show.bs.collapse', function () {
            const arrow = btn.querySelector('.category-arrow');
            if (arrow) arrow.style.transform = 'rotate(90deg)';
            btn.style.background = 'var(--gray-light, #f0f0f0)';
            btn.style.color = 'var(--primary, #6c63ff)';
        });
        collapseEl.addEventListener('hide.bs.collapse', function () {
            const arrow = btn.querySelector('.category-arrow');
            if (arrow) arrow.style.transform = 'rotate(0deg)';
            btn.style.background = '';
            btn.style.color = '';
        });
    });

    /* ---- Vue Grille / Liste ---- */
    const gridBtn    = document.getElementById('viewGrid');
    const listBtn    = document.getElementById('viewList');
    const gridEl     = document.getElementById('productsGrid');

    if (gridBtn && listBtn && gridEl) {
        gridBtn.addEventListener('click', function () {
            gridBtn.classList.add('active');
            listBtn.classList.remove('active');
            gridEl.classList.remove('list-view');
        });
        listBtn.addEventListener('click', function () {
            listBtn.classList.add('active');
            gridBtn.classList.remove('active');
            gridEl.classList.add('list-view');
        });
    }

    /* ---- Tri ---- */
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', this.value);
            window.location.href = url.toString();
        });

        // Pré-sélectionner la valeur courante
        const currentSort = new URL(window.location.href).searchParams.get('sort');
        if (currentSort) sortSelect.value = currentSort;
    }

    /* ---- Ajout au panier ---- */
    document.querySelectorAll('.add-to-cart').forEach(function (button) {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!csrfToken) {
                console.error('CSRF token manquant.');
                return;
            }

            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 }),
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    btn.innerHTML = '<i class="bi bi-check-circle"></i>';
                    btn.classList.replace('btn-primary', 'btn-success');

                    const cartCount = document.getElementById('cart-count');
                    if (data.cart_count && cartCount) {
                        cartCount.textContent = data.cart_count;
                    }
                } else {
                    throw new Error(data.message || 'Erreur inconnue.');
                }
            })
            .catch(err => {
                console.error('Erreur :', err.message);
                btn.innerHTML = '<i class="bi bi-cart-plus"></i>';
                btn.classList.replace('btn-success', 'btn-primary');
            })
            .finally(() => {
                setTimeout(() => {
                    btn.innerHTML = '<i class="bi bi-cart-plus"></i>';
                    btn.classList.replace('btn-success', 'btn-primary');
                    btn.disabled = false;
                }, 2000);
            });
        });
    });

    /* ---- Animation scroll ---- */
    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) entry.target.classList.add('visible');
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    document.querySelectorAll('.product-card').forEach(c => observer.observe(c));

});
</script>


<!-- ===================== STYLES ===================== -->
<style>
/* ---- Sidebar catégories ---- */
.category-item { margin-bottom: 2px; }

.category-btn {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 12px;
    border: none;
    background: transparent;
    border-radius: var(--border-radius, 8px);
    cursor: pointer;
    font-size: .9rem;
    color: var(--dark, #1a1a2e);
    text-align: left;
    transition: background .2s, color .2s;
}
.category-btn:hover {
    background: var(--gray-light, #f0f0f0);
    color: var(--primary, #6c63ff);
}

.category-thumb {
    width: 28px;
    height: 28px;
    object-fit: cover;
    border-radius: 4px;
    flex-shrink: 0;
}
.category-icon-placeholder {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--gray-light, #f0f0f0);
    border-radius: 4px;
    flex-shrink: 0;
    font-size: .85rem;
    color: var(--secondary, #888);
}
.category-name { flex: 1; font-weight: 500; }
.category-arrow {
    font-size: .75rem;
    color: var(--secondary, #888);
    transition: transform .25s ease;
    flex-shrink: 0;
}

/* ---- Sous-catégories ---- */
.sub-list {
    list-style: none;
    margin: 0;
    padding: 4px 0 8px 36px;
}
.sub-item { margin-bottom: 1px; }
.sub-link {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 6px 10px;
    font-size: .85rem;
    color: var(--secondary, #666);
    text-decoration: none;
    border-radius: var(--border-radius, 8px);
    transition: background .15s, color .15s, padding-left .15s;
}
.sub-link:hover {
    background: var(--gray-light, #f0f0f0);
    color: var(--primary, #6c63ff);
    padding-left: 14px;
}
.sub-link .bi-dot { font-size: 1.2rem; line-height: 1; flex-shrink: 0; }

/* ---- Empty state ---- */
.empty-state {
    background: white;
    border-radius: var(--border-radius);
    padding: 3rem 2rem;
    box-shadow: var(--shadow);
}

/* ---- Vue Liste ---- */
.products-grid.list-view { grid-template-columns: 1fr; gap: 15px; }
.products-grid.list-view .product-card { flex-direction: row; height: auto; }
.products-grid.list-view .product-image { width: 200px; height: 150px; flex-shrink: 0; }
.products-grid.list-view .product-info {
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.products-grid.list-view .product-actions {
    opacity: 1;
    transform: translateX(0);
    flex-direction: row;
    top: 10px;
    right: 10px;
}

/* ---- Hover produits ---- */
.product-card:hover .product-image img { transform: scale(1.05); }
.product-card:hover .product-title { color: var(--primary); }

/* ---- Responsive ---- */
@media (max-width: 768px) {
    .products-grid.list-view .product-card { flex-direction: column; }
    .products-grid.list-view .product-image { width: 100%; height: 200px; }
    .d-flex.gap-2 .btn span { display: none; }
}
</style>

@endsection