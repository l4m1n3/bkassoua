@extends('layouts.slaves')

@section('title', $product->name . ' - Bkassoua')

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
                    {{-- Lien "Tous les produits" --}}
                    <div class="category-item">
                        <a href="{{ route('shop') }}" class="category-btn text-decoration-none">
                            <span class="category-icon-placeholder">
                                <i class="bi bi-grid"></i>
                            </span>
                            <span class="category-name">Tous les produits</span>
                        </a>
                    </div>

                    @forelse($categories as $category)
                    <div class="category-item">

                        {{-- Bouton catégorie principale --}}
                        <button
                            class="category-btn {{ $category->sousCat->isNotEmpty() ? 'has-children' : '' }} {{ isset($product->category) && $product->category->id === $category->id ? 'active-category' : '' }}"
                            type="button"
                            @if($category->sousCat->isNotEmpty())
                                data-bs-toggle="collapse"
                                data-bs-target="#cat{{ $category->id }}"
                                aria-expanded="{{ isset($product->category) && $product->category->id === $category->id ? 'true' : 'false' }}"
                                aria-controls="cat{{ $category->id }}"
                            @else
                                onclick="window.location='/shop/{{ $category->slug }}'"
                            @endif
                        >
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
                                <i class="bi bi-chevron-right category-arrow {{ isset($product->category) && $product->category->id === $category->id ? 'rotated' : '' }}"></i>
                            @endif
                        </button>

                        {{-- Sous-catégories — ouvertes si c'est la catégorie du produit courant --}}
                        @if($category->sousCat->isNotEmpty())
                        <div
                            class="collapse sub-categories {{ isset($product->category) && $product->category->id === $category->id ? 'show' : '' }}"
                            id="cat{{ $category->id }}"
                        >
                            <ul class="sub-list">
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
                                        class="sub-link {{ isset($product->sousCat) && $product->sousCat->id === $subCategory->id ? 'active-sub' : '' }}"
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

            <!-- Bannière promo -->
            <div class="sidebar mt-4 text-white"
                 style="background: linear-gradient(135deg, #1780d6, #e76f51);">
                <div class="text-center">
                    <i class="bi bi-truck display-6 mb-3"></i>
                    <h6>Livraison Rapide</h6>
                </div>
            </div>
        </div>
        {{-- /Sidebar --}}


        <!-- ===================== CONTENU PRINCIPAL ===================== -->
        <div class="col-lg-9 col-md-8">

            <!-- Fil d'ariane -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none">Accueil</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('shop') }}" class="text-decoration-none">Boutique</a>
                    </li>
                    @if($product->category)
                    <li class="breadcrumb-item">
                        <a href="{{ route('shop.category', $product->category->slug) }}" class="text-decoration-none">
                            {{ $product->category->name }}
                        </a>
                    </li>
                    @endif
                    @if($product->sousCat)
                    <li class="breadcrumb-item">
                        <a href="{{ route('shop.category', [$product->category->slug ?? 'all', 'sub' => $product->sousCat->slug]) }}" class="text-decoration-none">
                            {{ $product->sousCat->name }}
                        </a>
                    </li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ \Illuminate\Support\Str::limit($product->name, 30) }}
                    </li>
                </ol>
            </nav>

            <!-- Message flash -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- ---- Détail produit ---- -->
            <div class="product-detail-card bg-white rounded shadow-lg overflow-hidden mb-5">
                <div class="row g-0">

                    <!-- Galerie images -->
                    @php
                        $images    = $product->images;
                        $mainImage = $product->mainImage ?? $images->first();
                    @endphp

                    <div class="col-lg-6">
                        <div class="product-gallery p-4">

                            {{-- Image principale --}}
                            <div class="main-image position-relative mb-4">
                                <img
                                    src="{{ $mainImage ? asset('storage/' . $mainImage->path) : asset('images/default-product.jpg') }}"
                                    alt="{{ $product->name }}"
                                    class="img-fluid rounded"
                                    id="mainImage"
                                    style="width:100%; height:380px; object-fit:cover;"
                                >

                                @if($product->discount > 0)
                                <span class="position-absolute top-0 start-0 m-3 px-3 py-1 rounded-pill text-white fw-bold"
                                      style="background:#e76f51; font-size:.8rem;">
                                    -{{ $product->discount }}%
                                </span>
                                @endif

                                @if(isset($product->is_new) && $product->is_new)
                                <span class="position-absolute top-0 start-0 m-3 px-3 py-1 rounded-pill text-white fw-bold"
                                      style="background:#1780d6; font-size:.8rem; margin-top:50px !important;">
                                    Nouveau
                                </span>
                                @endif
                            </div>

                            {{-- Miniatures --}}
                            @if($images->count() > 1)
                            <div class="thumbnail-gallery d-flex gap-3 justify-content-center flex-wrap">
                                @foreach($images->take(5) as $index => $image)
                                <img
                                    src="{{ asset('storage/' . $image->path) }}"
                                    alt="Miniature {{ $index + 1 }}"
                                    class="img-thumbnail cursor-pointer {{ $index === 0 ? 'active' : '' }}"
                                    style="width:72px; height:72px; object-fit:cover; cursor:pointer;"
                                    onclick="changeImage(this)"
                                >
                                @endforeach
                            </div>
                            @endif

                        </div>
                    </div>

                    <!-- Informations produit -->
                    <div class="col-lg-6">
                        <div class="product-info p-4 p-lg-5">

                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    {{-- Catégorie / sous-catégorie --}}
                                    <div class="mb-2">
                                        @if($product->sousCat)
                                            <span class="badge bg-light text-primary border border-primary">
                                                {{ $product->sousCat->name }}
                                            </span>
                                        @elseif($product->category)
                                            <span class="badge bg-light text-primary border border-primary">
                                                {{ $product->category->name }}
                                            </span>
                                        @endif
                                    </div>
                                    <h1 class="product-title mb-2" style="font-size:1.6rem;">{{ $product->name }}</h1>
                                    <div class="d-flex align-items-center gap-1 text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= 4 ? 'bi-star-fill' : 'bi-star-half' }}" style="font-size:.9rem;"></i>
                                        @endfor
                                        <span class="text-muted small ms-2">(47 avis)</span>
                                    </div>
                                </div>
                                <button class="btn btn-outline-danger wishlist-btn border-0 fs-4">
                                    <i class="bi bi-heart"></i>
                                </button>
                            </div>

                            <!-- Prix -->
                            <div class="product-pricing mb-4">
                                @if($product->discount > 0)
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <span class="h2 text-primary mb-0">
                                        {{ number_format($product->price * (1 - $product->discount / 100), 0, ',', ' ') }} FCFA
                                    </span>
                                    <del class="text-muted">{{ number_format($product->price, 0, ',', ' ') }} FCFA</del>
                                    <span class="badge bg-danger">-{{ $product->discount }}%</span>
                                </div>
                                @else
                                <span class="h3 text-primary">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
                                @endif
                            </div>

                            <!-- Description -->
                            <p class="text-muted mb-4" style="line-height:1.7;">{{ $product->description }}</p>

                            <!-- Tailles -->
                            @if(isset($product->sizes) && count($product->sizes) > 0)
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Taille</label>
                                <div class="d-flex gap-2 flex-wrap">
                                    @foreach($product->sizes as $size)
                                    <input type="radio" class="btn-check" name="size"
                                           id="size{{ $loop->index }}" value="{{ $size }}">
                                    <label class="btn btn-outline-secondary" for="size{{ $loop->index }}">
                                        {{ $size }}
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Couleurs -->
                            @if(isset($product->colors) && count($product->colors) > 0)
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Couleur</label>
                                <div class="d-flex gap-3">
                                    @foreach($product->colors as $color)
                                    <div
                                        class="color-option rounded-circle border border-3 border-white shadow-sm {{ $loop->first ? 'active' : '' }}"
                                        style="background-color:{{ $color }}; width:40px; height:40px; cursor:pointer;"
                                        onclick="selectColor(this)"
                                    ></div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Quantité -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Quantité</label>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="input-group" style="width:140px;">
                                        <button class="btn btn-outline-secondary" type="button"
                                                onclick="decreaseQuantity()">−</button>
                                        <input type="number" class="form-control text-center"
                                               id="quantityDisplay" value="1" min="1"
                                               max="{{ $product->stock_quantity }}" readonly>
                                        <button class="btn btn-outline-secondary" type="button"
                                                onclick="increaseQuantity()">+</button>
                                    </div>
                                    <small class="text-muted">
                                        @if($product->stock_quantity > 0)
                                            <i class="bi bi-check-circle-fill text-success me-1"></i>
                                            {{ $product->stock_quantity }} en stock
                                        @else
                                            <i class="bi bi-x-circle-fill text-danger me-1"></i>
                                            Rupture de stock
                                        @endif
                                    </small>
                                </div>
                            </div>

                            <!-- Bouton ajout panier -->
                            <form id="addToCartForm" class="d-inline-block w-100">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" id="quantityInput" value="1">
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-lg w-100 add-to-cart-btn"
                                    {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}
                                >
                                    <i class="bi bi-cart-plus me-2"></i>
                                    {{ $product->stock_quantity > 0 ? 'Ajouter au panier' : 'Indisponible' }}
                                </button>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
            {{-- /Détail produit --}}


            <!-- ---- Produits similaires ---- -->
            @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
            <section class="mb-5">
                <div class="section-title mb-4">
                    <h2>Produits similaires</h2>
                    <p class="text-muted">Dans la même catégorie</p>
                </div>
                <div class="products-grid">
                    @foreach($relatedProducts as $related)
                    <div class="product-card fade-in">
                        <div class="product-image">
                            <img
                                src="{{ $related->mainImage ? asset('storage/' . $related->mainImage->path) : asset('images/default-product.jpg') }}"
                                alt="{{ $related->name }}"
                                loading="lazy"
                            >
                            @if($related->discount > 0)
                                <span class="product-badge">-{{ $related->discount }}%</span>
                            @endif
                            <div class="product-actions">
                                <button class="action-btn" title="Favoris">
                                    <i class="bi bi-heart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-category">{{ $related->sousCat->name ?? $related->category->name ?? '' }}</div>
                            <h3 class="product-title">{{ $related->name }}</h3>
                            <div class="product-price">
                                @if($related->discount > 0)
                                    <span class="current-price">
                                        {{ number_format($related->price * (1 - $related->discount / 100), 0, ',', ' ') }} fcfa
                                    </span>
                                    <span class="original-price">
                                        {{ number_format($related->price, 0, ',', ' ') }} fcfa
                                    </span>
                                @else
                                    <span class="current-price">
                                        {{ number_format($related->price, 0, ',', ' ') }} fcfa
                                    </span>
                                @endif
                            </div>
                            <div class="d-flex gap-2 mt-3">
                                <a href="{{ route('shop.detail', $related->id) }}"
                                   class="btn btn-outline-primary flex-fill d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-eye"></i>
                                    <span>Voir détails</span>
                                </a>
                                <button class="btn btn-primary add-to-cart-related"
                                        data-product-id="{{ $related->id }}">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

        </div>
        {{-- /Contenu principal --}}

    </div>
</div>


<!-- Toast panier -->
<div id="cartToast" class="cart-toast">
    <div class="cart-toast-content">
        <i class="bi bi-check-circle-fill text-success me-2"></i>
        <span id="toastMessage">Produit ajouté au panier !</span>
    </div>
    <div class="cart-toast-progress"></div>
</div>


<!-- ===================== SCRIPTS ===================== -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ---- Rotation flèche collapse (catégorie active dès le chargement) ---- */
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function (btn) {
        const targetId = btn.getAttribute('data-bs-target');
        if (!targetId) return;
        const collapseEl = document.querySelector(targetId);
        if (!collapseEl) return;

        // Initialiser la flèche si le collapse est déjà ouvert (catégorie active)
        if (collapseEl.classList.contains('show')) {
            const arrow = btn.querySelector('.category-arrow');
            if (arrow) arrow.style.transform = 'rotate(90deg)';
        }

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

    /* ---- Toast ---- */
    const toast        = document.getElementById('cartToast');
    const toastMessage = document.getElementById('toastMessage');

    function showToast(message) {
        toastMessage.textContent = message || "Produit ajouté au panier !";
        toast.classList.add('show');
        const bar = toast.querySelector('.cart-toast-progress');
        bar.style.animation = 'none';
        bar.offsetHeight; // reflow
        bar.style.animation = 'toastProgress 3.5s linear forwards';
        setTimeout(() => toast.classList.remove('show'), 3500);
    }

    /* ---- Galerie ---- */
    window.changeImage = function (el) {
        document.getElementById('mainImage').src = el.src;
        document.querySelectorAll('.thumbnail-gallery img')
            .forEach(img => img.classList.remove('active'));
        el.classList.add('active');
    };

    /* ---- Quantité ---- */
    window.increaseQuantity = function () {
        const input = document.getElementById('quantityDisplay');
        if (parseInt(input.value) < parseInt(input.max)) {
            input.value = parseInt(input.value) + 1;
            document.getElementById('quantityInput').value = input.value;
        }
    };
    window.decreaseQuantity = function () {
        const input = document.getElementById('quantityDisplay');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
            document.getElementById('quantityInput').value = input.value;
        }
    };

    /* ---- Couleur ---- */
    window.selectColor = function (el) {
        document.querySelectorAll('.color-option').forEach(c => c.classList.remove('active'));
        el.classList.add('active');
    };

    /* ---- Wishlist ---- */
    document.querySelectorAll('.wishlist-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            this.classList.toggle('active');
            this.innerHTML = this.classList.contains('active')
                ? '<i class="bi bi-heart-fill"></i>'
                : '<i class="bi bi-heart"></i>';
        });
    });

    /* ---- Ajout au panier (formulaire principal) ---- */
    function addToCart(productId, quantity, button) {
        const originalHTML = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Ajout...';

        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', quantity);

        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || "Ajouté au panier !");
                document.querySelectorAll('#cart-count')
                    .forEach(el => { if (data.cart_count) el.textContent = data.cart_count; });
                button.innerHTML = '<i class="bi bi-check me-2"></i> Ajouté !';
                button.classList.replace('btn-primary', 'btn-success');
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.classList.replace('btn-success', 'btn-primary');
                    button.disabled = false;
                }, 2000);
            } else {
                throw new Error(data.message);
            }
        })
        .catch(err => {
            showToast("Erreur : " + (err.message || "Impossible d'ajouter"));
            button.innerHTML = originalHTML;
            button.disabled = false;
        });
    }

    document.getElementById('addToCartForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const productId = this.querySelector('[name="product_id"]').value;
        const quantity  = document.getElementById('quantityInput').value;
        addToCart(productId, quantity, this.querySelector('.add-to-cart-btn'));
    });

    /* ---- Ajout au panier produits similaires ---- */
    document.querySelectorAll('.add-to-cart-related').forEach(function (btn) {
        btn.addEventListener('click', function () {
            addToCart(this.getAttribute('data-product-id'), 1, this);
        });
    });

    /* ---- Animation scroll produits similaires ---- */
    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.1 });
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
.category-btn:hover,
.category-btn.active-category {
    background: var(--gray-light, #f0f0f0);
    color: var(--primary, #1780d6);
}
.category-thumb {
    width: 28px; height: 28px;
    object-fit: cover; border-radius: 4px; flex-shrink: 0;
}
.category-icon-placeholder {
    width: 28px; height: 28px;
    display: flex; align-items: center; justify-content: center;
    background: var(--gray-light, #f0f0f0);
    border-radius: 4px; flex-shrink: 0;
    font-size: .85rem; color: var(--secondary, #888);
}
.category-name { flex: 1; font-weight: 500; }
.category-arrow {
    font-size: .75rem; color: var(--secondary, #888);
    transition: transform .25s ease; flex-shrink: 0;
}
.category-arrow.rotated { transform: rotate(90deg); }

/* ---- Sous-catégories ---- */
.sub-list { list-style: none; margin: 0; padding: 4px 0 8px 36px; }
.sub-item  { margin-bottom: 1px; }
.sub-link  {
    display: flex; align-items: center; gap: 4px;
    padding: 6px 10px; font-size: .85rem;
    color: var(--secondary, #666); text-decoration: none;
    border-radius: var(--border-radius, 8px);
    transition: background .15s, color .15s, padding-left .15s;
}
.sub-link:hover,
.sub-link.active-sub {
    background: var(--gray-light, #f0f0f0);
    color: var(--primary, #1780d6);
    padding-left: 14px;
}
.sub-link.active-sub {
    border-left: 3px solid var(--primary, #1780d6);
    padding-left: 11px;
    font-weight: 500;
}
.sub-link .bi-dot { font-size: 1.2rem; line-height: 1; flex-shrink: 0; }

/* ---- Galerie ---- */
.cursor-pointer { cursor: pointer; }
.thumbnail-gallery img.active { border: 3px solid var(--primary, #1780d6) !important; }
.color-option.active {
    transform: scale(1.2);
    box-shadow: 0 0 0 3px white, 0 0 0 6px var(--primary, #1780d6);
}
.wishlist-btn.active i { color: #dc3545 !important; }

/* ---- Toast ---- */
.cart-toast {
    position: fixed; bottom: 30px; right: 30px; min-width: 300px;
    background: #1a1a1a; color: #fff; border-radius: 12px;
    padding: 16px 24px;
    box-shadow: 0 10px 30px rgba(0,0,0,.3);
    transform: translateX(400px); opacity: 0; visibility: hidden;
    transition: all .4s cubic-bezier(.68,-.55,.265,1.55);
    z-index: 9999;
}
.cart-toast.show { transform: translateX(0); opacity: 1; visibility: visible; }
.cart-toast-progress {
    position: absolute; bottom: 0; left: 0; height: 4px;
    background: #00d4aa; width: 100%;
    animation: toastProgress 3.5s linear forwards;
}
@keyframes toastProgress { from { width: 100%; } to { width: 0%; } }
</style>

@endsection