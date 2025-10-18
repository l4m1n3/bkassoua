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
                    Filtres & Catégories
                </h5>
                
                <!-- Catégories -->
                <div @class(['filter-section'])>
                    <div @class(['filter-title'])>
                        <span>Catégories</span>
                    </div> 
                    <div @class(['accordion']) id="categoryAccordion">
                        <div @class(['accordion-item', 'border-0', 'mb-2'])>
                            <h2 @class(['accordion-header']) id="headingOne">
                                <button @class(['accordion-button', 'py-2', 'rounded']) type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <i @class(['bi', 'bi-grid', 'me-2'])></i> Vêtements
                                </button>
                            </h2>
                            <div id="collapseOne" @class(['accordion-collapse', 'collapse', 'show']) aria-labelledby="headingOne"
                                data-bs-parent="#categoryAccordion">
                                <div @class(['accordion-body', 'pt-2', 'pb-1'])>
                                    <div @class(['filter-options'])>
                                        <div @class(['filter-option'])>
                                            <input type="checkbox" id="category-dresses" @class(['form-check-input'])>
                                            <label for="category-dresses" @class(['form-check-label'])>Robes</label>
                                        </div>
                                        <div @class(['filter-option'])>
                                            <input type="checkbox" id="category-tops" @class(['form-check-input'])>
                                            <label for="category-tops" @class(['form-check-label'])>Hauts</label>
                                        </div>
                                        <div @class(['filter-option'])>
                                            <input type="checkbox" id="category-pants" @class(['form-check-input'])>
                                            <label for="category-pants" @class(['form-check-label'])>Pantalons</label>
                                        </div>
                                        <div @class(['filter-option'])>
                                            <input type="checkbox" id="category-skirts" @class(['form-check-input'])>
                                            <label for="category-skirts" @class(['form-check-label'])>Jupes</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div @class(['accordion-item', 'border-0', 'mb-2'])>
                            <h2 @class(['accordion-header']) id="headingTwo">
                                <button @class(['accordion-button', 'collapsed', 'py-2', 'rounded']) type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <i @class(['bi', 'bi-bag', 'me-2'])></i> Accessoires
                                </button>
                            </h2>
                            <div id="collapseTwo" @class(['accordion-collapse', 'collapse']) aria-labelledby="headingTwo"
                                data-bs-parent="#categoryAccordion">
                                <div @class(['accordion-body', 'pt-2', 'pb-1'])>
                                    <div @class(['filter-options'])>
                                        <div @class(['filter-option'])>
                                            <input type="checkbox" id="category-bags" @class(['form-check-input'])>
                                            <label for="category-bags" @class(['form-check-label'])>Sacs</label>
                                        </div>
                                        <div @class(['filter-option'])>
                                            <input type="checkbox" id="category-jewelry" @class(['form-check-input'])>
                                            <label for="category-jewelry" @class(['form-check-label'])>Bijoux</label>
                                        </div>
                                        <div @class(['filter-option'])>
                                            <input type="checkbox" id="category-shoes" @class(['form-check-input'])>
                                            <label for="category-shoes" @class(['form-check-label'])>Chaussures</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtre Prix -->
                <div @class(['filter-section'])>
                    <div @class(['filter-title'])>
                        <span>Prix</span>
                        <button @class(['btn', 'btn-sm', 'btn-link', 'p-0', 'text-decoration-none', 'text-primary'])>Réinitialiser</button>
                    </div>
                    <input type="range" @class(['form-range', 'mb-3']) id="priceRange" min="0" max="100000" step="1000" value="50000">
                    <div @class(['price-inputs'])>
                        <input type="number" @class(['price-input']) placeholder="Min" value="0">
                        <input type="number" @class(['price-input']) placeholder="Max" value="50000">
                    </div>
                    <div @class(['price-display', 'mt-2'])>
                        <small @class(['text-muted'])>Prix: 0 - 50,000 fcfa</small>
                    </div>
                </div>

                <!-- Filtre Taille -->
                <div @class(['filter-section'])>
                    <div @class(['filter-title'])>Taille</div>
                    <div @class(['d-flex', 'flex-wrap', 'gap-2'])>
                        <div @class(['filter-option'])>
                            <input @class(['form-check-input']) type="checkbox" id="sizeS">
                            <label @class(['form-check-label']) for="sizeS">S</label>
                        </div>
                        <div @class(['filter-option'])>
                            <input @class(['form-check-input']) type="checkbox" id="sizeM">
                            <label @class(['form-check-label']) for="sizeM">M</label>
                        </div>
                        <div @class(['filter-option'])>
                            <input @class(['form-check-input']) type="checkbox" id="sizeL">
                            <label @class(['form-check-label']) for="sizeL">L</label>
                        </div>
                        <div @class(['filter-option'])>
                            <input @class(['form-check-input']) type="checkbox" id="sizeXL">
                            <label @class(['form-check-label']) for="sizeXL">XL</label>
                        </div>
                    </div>
                </div>

                <!-- Filtre Couleur -->
                <div @class(['filter-section'])>
                    <div @class(['filter-title'])>Couleur</div>
                    <div @class(['color-options'])>
                        <div @class(['color-option']) style="background-color: #000;" title="Noir"></div>
                        <div @class(['color-option']) style="background-color: #fff; border: 1px solid #ddd;" title="Blanc"></div>
                        <div @class(['color-option']) style="background-color: #dc2626;" title="Rouge"></div>
                        <div @class(['color-option']) style="background-color: #2563eb;" title="Bleu"></div>
                        <div @class(['color-option']) style="background-color: #16a34a;" title="Vert"></div>
                        <div @class(['color-option']) style="background-color: #f59e0b;" title="Jaune"></div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div @class(['d-grid', 'gap-2', 'mt-4'])>
                    <button @class(['btn', 'btn-primary'])>
                        <i @class(['bi', 'bi-funnel', 'me-2'])></i>Appliquer les filtres
                    </button>
                    <button @class(['btn', 'btn-outline-secondary'])>
                        <i @class(['bi', 'bi-arrow-clockwise', 'me-2'])></i>Tout effacer
                    </button>
                </div>
            </div>

            <!-- Bannière promotionnelle -->
            <div @class(['sidebar', 'mt-4', 'text-white']) style="background: linear-gradient(135deg, var(--primary), var(--accent));">
                <div @class(['text-center'])>
                    <i @class(['bi', 'bi-truck', 'display-6', 'mb-3'])></i>
                    <h6>Livraison Gratuite</h6>
                    <p @class(['small', 'mb-0'])>À partir de 50,000 fcfa d'achat</p>
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
    // --- Gestion des filtres de couleur ---
    document.querySelectorAll('.color-option').forEach(option => {
        option.addEventListener('click', function () {
            this.classList.toggle('active');
        });
    });

    // --- Mise à jour de l'affichage du prix ---
    const priceRange = document.getElementById('priceRange');
    const minPriceInput = document.querySelector('.price-inputs input:first-child');
    const maxPriceInput = document.querySelector('.price-inputs input:last-child');
    const priceDisplay = document.querySelector('.price-display small');

    function updatePriceDisplay() {
        const min = minPriceInput.value || 0;
        const max = maxPriceInput.value || 50000;
        priceDisplay.textContent = `Prix: ${parseInt(min).toLocaleString()} - ${parseInt(max).toLocaleString()} FCFA`;
        priceRange.value = max;
    }

    if (priceRange) {
        priceRange.addEventListener('input', function () {
            maxPriceInput.value = this.value;
            updatePriceDisplay();
        });
    }

    minPriceInput.addEventListener('input', updatePriceDisplay);
    maxPriceInput.addEventListener('input', updatePriceDisplay);

    // --- Boutons de vue (grille/liste) ---
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

    // --- Ajout au panier ---
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');
            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : null;

            if (!csrfToken) {
                console.error('❌ CSRF token manquant. Vérifie la balise <meta name="csrf-token"> dans le <head>.');
                return;
            }

            // Animation d’ajout
            button.innerHTML = '<i class="bi bi-check-lg"></i>';
            button.classList.add('btn-success');
            button.classList.remove('btn-primary');

            // Envoi AJAX
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
                        console.log("✅ Produit ajouté au panier avec succès");
                        button.innerHTML = '<i class="bi bi-check-circle"></i> Ajouté !';
                        button.classList.add("btn-success");

                        if (data.cart_count && document.getElementById("cart-count")) {
                            document.getElementById("cart-count").textContent = data.cart_count;
                        }
                    } else {
                        throw new Error(data.message || "Erreur inconnue lors de l’ajout au panier.");
                    }
                })
                .catch(error => {
                    console.error("❌ Erreur lors de l’ajout au panier :", error.message);
                    button.innerHTML = '<i class="bi bi-cart-plus"></i> Ajouter au panier';
                    button.classList.remove("btn-success");
                    button.classList.add("btn-primary");
                })
                .finally(() => {
                    // Réinitialiser après 2 secondes
                    setTimeout(() => {
                        button.innerHTML = '<i class="bi bi-cart-plus"></i> Ajouter au panier';
                        button.classList.remove('btn-success');
                        button.classList.add('btn-primary');
                    }, 2000);
                });
        });
    });

    // --- Animation au défilement ---
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.product-card').forEach(card => {
        observer.observe(card);
    });
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