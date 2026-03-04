@extends('layouts.slaves')

@section('title', $product->name . ' - Bkassoua')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="sidebar bg-white rounded shadow-sm p-4">
                <h5 class="sidebar-title mb-4"><i class="bi bi-filter-circle me-2"></i>Catégories</h5>
                <div class="filter-options">
                    <div class="filter-option mb-2">
                        <a href="{{ route('shop') }}" class="d-flex align-items-center justify-content-between text-decoration-none text-dark">
                            <span>Tous les produits</span>
                            <i class="bi bi-arrow-right text-muted"></i>
                        </a>
                    </div>
                    @foreach($categories as $category)
                    <div class="filter-option mb-2">
                        <a href="/shop/{{ $category->slug }}" class="d-flex align-items-center justify-content-between text-decoration-none text-dark">
                            <span>{{ $category->name }}</span>
                            <i class="bi bi-arrow-right text-muted"></i>
                        </a>
                    </div>
                    @endforeach
                </div>

                <div class="promo-banner mt-4 p-4 rounded text-white text-center" style="background: linear-gradient(135deg, #1780d6, #e76f51);">
                    <i class="bi bi-truck display-6 mb-2"></i>
                    <h6>Livraison Rapide</h6>
                    {{-- <p class="small mb-0">Sous 1h-2h</p> --}}
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="col-lg-9 col-md-8">
            <!-- Fil d'ariane -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('shop') }}" class="text-decoration-none">Boutique</a></li>
                    <li class="breadcrumb-item"><a href="/shop/{{ $product->category->slug ?? 'all' }}" class="text-decoration-none">{{ $product->category->name ?? 'Tous' }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ \Illuminate\Support\Str::limit($product->name, 30) }}</li>
                </ol>
            </nav>

            <!-- Messages flash -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Détail produit -->
            <div class="product-detail-card bg-white rounded shadow-lg overflow-hidden">
                <div class="row g-0">
                    <!-- Galerie -->
                    @php
                        $images = $product->images;
                        $mainImage = $product->mainImage ?? $images->first();
                    @endphp

                    <div class="col-lg-6">
                        <div class="product-gallery p-4">

                            {{-- IMAGE PRINCIPALE --}}
                            <div class="main-image position-relative mb-4">
                                <img 
                                    src="{{ $mainImage ? asset('storage/' . $mainImage->path) : asset('images/default-product.jpg') }}"
                                    alt="{{ $product->name }}"
                                    class="img-fluid rounded"
                                    id="mainImage"
                                >

                                @if($product->discount > 0)
                                    <span class="product-badge discount position-absolute top-0 start-0 m-3 px-3 py-1 rounded-pill text-white fw-bold" style="background:#e76f51;">
                                        -{{ $product->discount }}%
                                    </span>
                                @endif

                                @if($product->is_new)
                                    <span class="product-badge new position-absolute top-0 start-0 m-3 px-3 py-1 rounded-pill text-white fw-bold" style="background:#1780d6; margin-top: 50px !important;">
                                        Nouveau
                                    </span>
                                @endif
                            </div>

                            {{-- MINIATURES --}}
                            <div class="thumbnail-gallery d-flex gap-3 justify-content-center">
                                @foreach($images->take(4) as $index => $image)
                                    <img 
                                        src="{{ asset('storage/' . $image->path) }}"
                                        alt="Miniature {{ $index + 1 }}"
                                        class="img-thumbnail cursor-pointer {{ $index === 0 ? 'active' : '' }}"
                                        style="width:80px; height:80px; object-fit:cover;"
                                        onclick="changeImage(this)"
                                    >
                                @endforeach
                            </div>

                        </div>
                    </div>
                    <!-- Infos produit -->
                    <div class="col-lg-6">
                        <div class="product-info p-4 p-lg-5">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div>
                                    <h1 class="product-title mb-2">{{ $product->name }}</h1>
                                    <div class="d-flex align-items-center gap-2 text-warning">
                                        @for($i = 1; $i <= 5; $i++) <i class="bi {{ $i <= 4 ? 'bi-star-fill' : 'bi-star-half' }}"></i> @endfor
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
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="h2 text-primary mb-0">{{ number_format($product->price * (1 - $product->discount/100), 0, ',', ' ') }} FCFA</span>
                                        <del class="text-muted">{{ number_format($product->price, 0, ',', ' ') }} FCFA</del>
                                        <span class="badge bg-danger">-{{ $product->discount }}%</span>
                                    </div>
                                @else
                                    <span class="h3 text-primary">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
                                @endif
                            </div>

                            <p class="text-muted mb-4">{{ $product->description }}</p>

                            <!-- Options -->
                            @if($product->sizes && count($product->sizes) > 0)
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Taille</label>
                                <div class="d-flex gap-2 flex-wrap">
                                    @foreach($product->sizes as $size)
                                        <input type="radio" class="btn-check" name="size" id="size{{ $loop->index }}" value="{{ $size }}">
                                        <label class="btn btn-outline-secondary" for="size{{ $loop->index }}">{{ $size }}</label>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if($product->colors && count($product->colors) > 0)
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Couleur</label>
                                <div class="d-flex gap-3">
                                    @foreach($product->colors as $color)
                                        <div class="color-option rounded-circle border border-3 border-white shadow-sm {{ $loop->first ? 'active' : '' }}"
                                             style="background-color: {{ $color }}; width:40px; height:40px; cursor:pointer;"
                                             onclick="selectColor(this)"></div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Quantité -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Quantité</label>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="input-group" style="width: 140px;">
                                        <button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity()">−</button>
                                        <input type="number" class="form-control text-center" id="quantityDisplay" value="1" min="1" max="{{ $product->stock_quantity }}" readonly>
                                        <button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity()">+</button>
                                    </div>
                                    <small class="text-muted">{{ $product->stock_quantity }} en stock</small>
                                </div>
                            </div>

                            <!-- Boutons d'action -->
                            <form id="addToCartForm" class="d-inline-block w-100">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" id="quantityInput" value="1">
                                <button type="submit" class="btn btn-primary btn-lg w-100 add-to-cart-btn">
                                    <i class="bi bi-cart-plus me-2"></i> Ajouter au panier
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast personnalisé -->
<div id="cartToast" class="cart-toast">
    <div class="cart-toast-content">
        <i class="bi bi-check-circle-fill text-success me-2"></i>
        <span id="toastMessage">Produit ajouté au panier !</span>
    </div>
    <div class="cart-toast-progress"></div>
</div>

<style>
    :root {
        --primary: #1780d6;
        --accent: #e76f51;
        --dark: #264653;
    }
    .cursor-pointer { cursor: pointer; }
    .thumbnail-gallery img.active { border: 3px solid var(--primary) !important; opacity: 1; }
    .color-option.active { transform: scale(1.2); box-shadow: 0 0 0 3px white, 0 0 0 6px var(--primary); }
    .wishlist-btn.active i { color: #dc3545 !important; }

    /* Toast */
    .cart-toast {
        position: fixed;
        bottom: 30px;
        right: 30px;
        min-width: 340px;
        background: #1a1a1a;
        color: #fff;
        border-radius: 12px;
        padding: 16px 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        transform: translateX(400px);
        opacity: 0;
        visibility: hidden;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 9999;
    }
    .cart-toast.show {
        transform: translateX(0);
        opacity: 1;
        visibility: visible;
    }
    .cart-toast-progress {
        position: absolute;
        bottom: 0; left: 0; height: 4px;
        background: #00d4aa;
        width: 100%;
        animation: toastProgress 3.5s linear forwards;
    }
    @keyframes toastProgress { from { width: 100%; } to { width: 0%; } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toast = document.getElementById('cartToast');
    const toastMessage = document.getElementById('toastMessage');

    function showToast(message = "Produit ajouté au panier !") {
        toastMessage.textContent = message;
        toast.classList.add('show');
        toast.querySelector('.cart-toast-progress').style.animation = 'none';
        toast.offsetHeight;
        toast.querySelector('.cart-toast-progress').style.animation = 'toastProgress 3.5s linear forwards';
        setTimeout(() => toast.classList.remove('show'), 3500);
    }

    // Galerie
    window.changeImage = function(el) {
        document.getElementById('mainImage').src = el.src;
        document.querySelectorAll('.thumbnail-gallery img').forEach(img => img.classList.remove('active'));
        el.classList.add('active');
    };

    // Quantité
    window.increaseQuantity = function() {
        const input = document.getElementById('quantityDisplay');
        if (parseInt(input.value) < parseInt(input.max)) {
            input.value = parseInt(input.value) + 1;
            document.getElementById('quantityInput').value = input.value;
        }
    };
    window.decreaseQuantity = function() {
        const input = document.getElementById('quantityDisplay');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
            document.getElementById('quantityInput').value = input.value;
        }
    };

    // Couleur
    window.selectColor = function(el) {
        document.querySelectorAll('.color-option').forEach(c => c.classList.remove('active'));
        el.classList.add('active');
    };

    // Wishlist
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            btn.classList.toggle('active');
            btn.innerHTML = btn.classList.contains('active') 
                ? '<i class="bi bi-heart-fill"></i>' 
                : '<i class="bi bi-heart"></i>';
        });
    });

    // AJOUT AU PANIER EN AJAX
    document.getElementById('addToCartForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const button = this.querySelector('.add-to-cart-btn');
        const originalHTML = button.innerHTML;

        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Ajout en cours...';

        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || "Ajouté au panier !");
                if (data.cart_count) {
                    document.querySelectorAll('#cart-count').forEach(el => el.textContent = data.cart_count);
                }
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
    });
});
</script>
@endsection