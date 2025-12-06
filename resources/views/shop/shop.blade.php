@extends('layouts.slaves')

@section('title', 'Boutique')

@section('content')
<div @class(['container-fluid', 'py-4'])>
    <div @class(['row'])>
        <!-- Sidebar améliorée -->
        <div @class(['col-lg-3', 'col-md-4', 'mb-4'])>
            <div @class(['sidebar'])>
                <h5 @class(['sidebar-title'])>
                    <i @class(['bi', 'bi-filter-circle'])></i>
                 Catégories
                </h5>
                
                <!-- Catégories -->
                <div @class(['filter-section'])>
                     @foreach ($categories as $categorie)
                        <div @class(['filter-option'])>
                            <a href="/shop/{{ $categorie->slug }}" @class(['d-flex', 'align-items-center', 'justify-content-between', 'text-decoration-none', 'text-dark'])>
                                <span>{{ $categorie->name }}</span>
                                {{-- <i @class(['bi', 'bi-chevron-right', 'text-muted'])></i> --}}
                            </a>
                        </div>
                        @endforeach
                </div>
            </div>

            <!-- Bannière promotionnelle -->
            <div @class(['sidebar', 'mt-4', 'text-white']) style="background: linear-gradient(135deg, var(--primary), var(--accent));">
                <div @class(['text-center'])>
                    <i @class(['bi', 'bi-truck', 'display-6', 'mb-3'])></i>
                    <h6>Livraison Rapide</h6>
                    <p @class(['small', 'mb-0'])></p>
                </div>
            </div>
        </div>

        <!-- Grille des Produits améliorée -->
        <div @class(['col-lg-9', 'col-md-8'])>
            <!-- En-tête amélioré -->
            <div @class(['d-flex', 'justify-content-between', 'align-items-center', 'mb-4'])>
                <div>
                    <h2 @class(['mb-1'])>Tous les Produits</h2>
                    <p @class(['text-muted', 'mb-0'])>
                        Affichage de <strong>{{ $products->count() }}</strong> 
                        produit{{ $products->count() > 1 ? 's' : '' }}
                    </p>
                </div>
                <div @class(['d-flex', 'align-items-center', 'gap-3'])>
                    <!-- Boutons de vue -->
                    <div @class(['d-none', 'd-md-block'])>
                        <div @class(['btn-group']) role="group">
                            <button type="button" @class(['btn', 'btn-outline-primary', 'active'])>
                                <i @class(['bi', 'bi-grid'])></i>
                            </button>
                            <button type="button" @class(['btn', 'btn-outline-primary'])>
                                <i @class(['bi', 'bi-list'])></i>
                            </button>
                        </div>
                    </div>
                    <!-- Sélecteur de tri -->
                    <select @class(['form-select', 'sort-select', 'w-auto'])>
                        <option>Trier par : Popularité</option>
                        <option>Prix : Croissant</option>
                        <option>Prix : Décroissant</option>
                        <option>Nouveautés</option>
                        <option>Meilleures ventes</option>
                    </select>
                </div>
            </div>

            <!-- Grille de produits améliorée -->
            @if ($products->isEmpty())
                <div @class(['empty-state', 'text-center', 'py-5'])>
                    <i @class(['bi', 'bi-search', 'display-1', 'text-muted'])></i>
                    <h4 @class(['mt-3', 'text-muted'])>Aucun produit trouvé</h4>
                    <p @class(['text-muted'])>Essayez de modifier vos critères de recherche ou vos filtres.</p>
                    <button @class(['btn', 'btn-primary', 'mt-3'])>
                        <i @class(['bi', 'bi-arrow-clockwise', 'me-2'])></i>Réinitialiser les filtres
                    </button>
                </div>
            @else
                <div @class(['products-grid'])>
                    @foreach ($products as $product)
                        <div @class(['product-card', 'fade-in'])>
                            <div @class(['product-image'])>
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                @if($product->discount > 0)
                                    <span @class(['product-badge'])>-{{ $product->discount }}%</span>
                                @endif
                                <div @class(['product-actions'])>
                                    <button @class(['action-btn']) title="Ajouter aux favoris">
                                        <i @class(['bi', 'bi-heart'])></i>
                                    </button>
                                    <button @class(['action-btn']) title="Vue rapide">
                                        <i @class(['bi', 'bi-eye'])></i>
                                    </button>
                                </div>
                            </div>
                            <div @class(['product-info'])>
                                <div @class(['product-category'])>{{ $product->category->name ?? 'Non catégorisé' }}</div>
                                <h3 @class(['product-title'])>{{ $product->name }}</h3>
                                
                                <!-- Évaluation -->
                                <div @class(['product-rating'])>
                                    <div @class(['stars'])>
                                        <i @class(['bi', 'bi-star-fill'])></i>
                                        <i @class(['bi', 'bi-star-fill'])></i>
                                        <i @class(['bi', 'bi-star-fill'])></i>
                                        <i @class(['bi', 'bi-star-fill'])></i>
                                        <i @class(['bi', 'bi-star-half'])></i>
                                    </div>
                                    <span @class(['rating-count'])>({{ rand(10, 50) }})</span>
                                </div>
                                
                                <!-- Prix -->
                                <div @class(['product-price'])>
                                    @if($product->discount > 0)
                                        <span @class(['current-price'])>{{ number_format($product->price * (1 - $product->discount/100), 0, ',', ' ') }} fcfa</span>
                                        <span @class(['original-price'])>{{ number_format($product->price, 0, ',', ' ') }} fcfa</span>
                                    @else
                                        <span @class(['current-price'])>{{ number_format($product->price, 0, ',', ' ') }} fcfa</span>
                                    @endif
                                </div>
                                
                                <!-- Actions -->
                                <div @class(['d-flex', 'gap-2', 'mt-3'])>
                                    <a href="{{ route('shop.detail', $product->id) }}" 
                                       @class(['btn', 'btn-outline-primary', 'flex-fill', 'd-flex', 'align-items-center', 'justify-content-center', 'gap-2'])>
                                        <i @class(['bi', 'bi-eye'])></i>
                                        <span @class(['d-none', 'd-sm-inline'])>Voir détails</span>
                                    </a>
                                    <button @class(['btn', 'btn-primary', 'add-to-cart']) data-product-id="{{ $product->id }}">
                                        <i @class(['bi', 'bi-cart-plus'])></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation" @class(['mt-5'])>
                    <ul @class(['pagination', 'justify-content-center'])>
                        <li @class(['page-item', 'disabled'])>
                            <a @class(['page-link']) href="#" tabindex="-1" aria-disabled="true">
                                <i @class(['bi', 'bi-chevron-left'])></i> Précédent
                            </a>
                        </li>
                        <li @class(['page-item', 'active'])><a @class(['page-link']) href="#">1</a></li>
                        <li @class(['page-item'])><a @class(['page-link']) href="#">2</a></li>
                        <li @class(['page-item'])><a @class(['page-link']) href="#">3</a></li>
                        <li @class(['page-item'])>
                            <a @class(['page-link']) href="#">
                                Suivant <i @class(['bi', 'bi-chevron-right'])></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            @endif
        </div>
    </div>
</div>

<!-- Scripts supplémentaires pour la page boutique -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* -----------------------------
       Gestion des filtres de couleur
    ------------------------------ */
    document.querySelectorAll('.color-option').forEach(option => {
        option.addEventListener('click', function () {
            this.classList.toggle('active');
        });
    });

    /* -----------------------------
       Mise à jour de l'affichage du prix (protégée)
    ------------------------------ */
    const priceRange = document.getElementById('priceRange');
    const minPriceInput = document.querySelector('.price-inputs input:first-child');
    const maxPriceInput = document.querySelector('.price-inputs input:last-child');
    const priceDisplay = document.querySelector('.price-display small');

    function updatePriceDisplay() {
        if (!minPriceInput || !maxPriceInput || !priceDisplay) return;

        const min = minPriceInput.value || 0;
        const max = maxPriceInput.value || 50000;

        priceDisplay.textContent = `Prix: ${parseInt(min).toLocaleString()} - ${parseInt(max).toLocaleString()} FCFA`;
    }

    // Range slider protégé
    if (priceRange && maxPriceInput) {
        priceRange.addEventListener('input', function () {
            maxPriceInput.value = this.value;
            updatePriceDisplay();
        });
    }

    // Inputs protégés
    if (minPriceInput && maxPriceInput) {
        minPriceInput.addEventListener('input', updatePriceDisplay);
        maxPriceInput.addEventListener('input', updatePriceDisplay);
    }

    /* -----------------------------
       Boutons de vue Grille / Liste
    ------------------------------ */
    const viewGridBtn = document.querySelector('.btn-group .btn:first-child');
    const viewListBtn = document.querySelector('.btn-group .btn:last-child');
    const productsGrid = document.querySelector('.products-grid');

    if (viewGridBtn && viewListBtn && productsGrid) {
        viewGridBtn.addEventListener('click', function () {
            this.classList.add('active');
            viewListBtn.classList.remove('active');
            productsGrid.classList.remove('list-view');
        });

        viewListBtn.addEventListener('click', function () {
            this.classList.add('active');
            viewGridBtn.classList.remove('active');
            productsGrid.classList.add('list-view');
        });
    }

    /* -----------------------------
       Ajout au panier
    ------------------------------ */
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!csrfToken) {
                console.error('❌ CSRF token manquant.');
                return;
            }

            button.innerHTML = '<i class="bi bi-check-lg"></i>';
            button.classList.add('btn-success');
            button.classList.remove('btn-primary');

            fetch('{{ route("cart.add") }}', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1,
                }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.innerHTML = '<i class="bi bi-check-circle"></i> Ajouté !';
                        button.classList.add("btn-success");

                        if (data.cart_count && document.getElementById("cart-count")) {
                            document.getElementById("cart-count").textContent = data.cart_count;
                        }
                    } else {
                        throw new Error(data.message || "Erreur inconnue.");
                    }
                })
                .catch(error => {
                    console.error("❌ Erreur :", error.message);
                    button.innerHTML = '<i class="bi bi-cart-plus"></i>';
                    button.classList.remove("btn-success");
                    button.classList.add("btn-primary");
                })
                .finally(() => {
                    setTimeout(() => {
                        button.innerHTML = '<i class="bi bi-cart-plus"></i>';
                        button.classList.remove('btn-success');
                        button.classList.add('btn-primary');
                    }, 2000);
                });
        });
    });

    /* -----------------------------
       Animation au scroll
    ------------------------------ */
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    document.querySelectorAll('.product-card').forEach(card => observer.observe(card));

});
</script>



<style>
/* Styles supplémentaires pour la page boutique */
.empty-state {
    background: white;
    border-radius: var(--border-radius);
    padding: 3rem 2rem;
    box-shadow: var(--shadow);
}

.products-grid.list-view {
    grid-template-columns: 1fr;
    gap: 15px;
}

.products-grid.list-view .product-card {
    flex-direction: row;
    height: auto;
}

.products-grid.list-view .product-image {
    width: 200px;
    height: 150px;
    flex-shrink: 0;
}

.products-grid.list-view .product-info {
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.products-grid.list-view .product-title {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.products-grid.list-view .product-actions {
    opacity: 1;
    transform: translateX(0);
    flex-direction: row;
    top: 10px;
    right: 10px;
}

@media (max-width: 768px) {
    .products-grid.list-view .product-card {
        flex-direction: column;
    }
    
    .products-grid.list-view .product-image {
        width: 100%;
        height: 200px;
    }
    
    .d-flex.gap-2 .btn span {
        display: none;
    }
}

/* Animation de chargement pour les images */
.product-image {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
}

.product-image.loaded {
    background: none;
}

/* Badge nouveau produit */
.product-badge.new {
    background: var(--primary);
}

/* État hover amélioré pour les cartes */
.product-card:hover .product-image img {
    transform: scale(1.05);
}

.product-card:hover .product-title {
    color: var(--primary);
}
</style>
@endsection