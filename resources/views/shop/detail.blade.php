{{-- @extends('layouts.slaves')

@section('content')
    <style>
        <style> :root {
            --primary: #1780d6;
            --secondary: #f4a261;
            --accent: #e76f51;
            --light: #f8f9fa;
            --dark: #264653;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
        }

        /* Navbar */
        .navbar {
            background-color: var(--primary);
            transition: background-color 0.3s ease;
        }

        .navbar.scrolled {
            background-color: rgba(44, 74, 110, 0.9);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand img {
            height: 50px;
            transition: transform 0.3s;
        }

        .navbar-brand img:hover {
            transform: scale(1.1);
        }

        .nav-link {
            color: white !important;
            font-weight: 400;
            margin: 0 10px;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: var(--secondary) !important;
        }

        .nav-link.active {
            font-weight: 600;
        }

        .navbar form {
            max-width: 400px;
        }

        .navbar .btn-outline-light {
            border-color: white;
            color: white;
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar .btn-outline-light:hover {
            background-color: var(--secondary);
            color: var(--dark);
        }

        .navbar .bi {
            font-size: 1.5rem;
            color: white;
            transition: color 0.3s;
        }

        .navbar .bi:hover {
            color: var(--secondary);
        }

        /* Sidebar */
        .sidebar {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .sidebar h5 {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .accordion-button {
            color: var(--primary);
            font-weight: 500;
            background-color: transparent;
        }

        .accordion-button:not(.collapsed) {
            color: var(--primary);
            background-color: var(--light);
        }

        .accordion-body a {
            color: var(--dark);
            text-decoration: none;
            padding: 5px 0;
            display: block;
            transition: color 0.3s;
        }

        .accordion-body a:hover {
            color: var(--accent);
        }

        .form-label {
            color: var(--primary);
            font-weight: 500;
        }

        .form-select,
        .form-range {
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        .form-select:focus,
        .form-range:focus {
            border-color: var(--accent);
            box-shadow: 0 0 5px rgba(231, 111, 81, 0.3);
        }

        /* Product Details */
        #mainImage {
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: opacity 0.3s;
        }

        .thumbnail img {
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 5px;
            transition: border-color 0.3s, transform 0.3s;
        }

        .thumbnail img:hover,
        .thumbnail img.active {
            border-color: var(--accent);
            transform: scale(1.05);
        }

        .product-details h2 {
            color: var(--dark);
            font-weight: 600;
        }

        .product-details .text-muted {
            color: var(--accent);
            font-weight: 600;
            font-size: 1.25rem;
        }

        .product-details p {
            color: var(--dark);
            line-height: 1.6;
        }

        .product-details .form-select,
        .product-details .form-control {
            max-width: 150px;
            border-radius: 5px;
        }

        .product-details .form-select:focus,
        .product-details .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 5px rgba(231, 111, 81, 0.3);
        }

        .product-details .btn-primary {
            background-color: var(--secondary);
            border: none;
            padding: 10px 20px;
            font-weight: 500;
            transition: background-color 0.3s, transform 0.3s;
        }

        .product-details .btn-primary:hover {
            background-color: var(--accent);
            transform: translateY(-2px);
        }

        .product-details .btn-outline-primary {
            border-color: var(--primary);
            color: var(--primary);
            padding: 10px 20px;
            transition: background-color 0.3s, color 0.3s;
        }

        .product-details .btn-outline-primary:hover {
            background-color: var(--primary);
            color: white;
        }

        .product-details .reviews p {
            color: var(--dark);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .product-details .reviews p::before {
            content: '⭐';
            margin-right: 5px;
        }

        /* Related Products */
        .product-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .product-card img {
            aspect-ratio: 4/3;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .product-card:hover img {
            transform: scale(1.05);
        }

        .product-card .card-body {
            padding: 1.5rem;
        }

        .product-card .card-title {
            color: var(--dark);
            font-weight: 500;
        }

        .product-card .card-text {
            color: var(--accent);
            font-weight: 600;
        }
    </style>
    @if (session('success'))
        <div @class(['alert', 'alert-success'])>{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div @class(['alert', 'alert-danger'])>{{ session('error') }}</div>
    @endif
    <!-- Contenu Principal -->
    <div @class(['container-fluid', 'pt-5'])>
        <div @class(['row'])>
            <!-- Sidebar -->
            <div @class(['col-md-3'])>
                <div @class(['sidebar'])>
                    <h5>Catégories</h5>
                    <div @class(['accordion']) id="categoryAccordion">
                        <div @class(['accordion-item'])>
                            <h2 @class(['accordion-header']) id="headingOne">
                                <button @class(['accordion-button']) type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Vêtements
                                </button>
                            </h2>
                            <div id="collapseOne" @class(['accordion-collapse', 'collapse', 'show']) aria-labelledby="headingOne"
                                data-bs-parent="#categoryAccordion">
                                <div @class(['accordion-body'])>
                                    <a href="#" @class(['d-block'])>Robes</a>
                                    <a href="#" @class(['d-block'])>Hauts</a>
                                    <a href="#" @class(['d-block'])>Pantalons</a>
                                </div>
                            </div>
                        </div>
                        <div @class(['accordion-item'])>
                            <h2 @class(['accordion-header']) id="headingTwo">
                                <button @class(['accordion-button', 'collapsed']) type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Accessoires
                                </button>
                            </h2>
                            <div id="collapseTwo" @class(['accordion-collapse', 'collapse']) aria-labelledby="headingTwo"
                                data-bs-parent="#categoryAccordion">
                                <div @class(['accordion-body'])>
                                    <a href="#" @class(['d-block'])>Sacs</a>
                                    <a href="#" @class(['d-block'])>Bijoux</a>
                                    <a href="#" @class(['d-block'])>Chaussures</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h5>Filtres</h5>
                    <div @class(['mb-3'])>
                        <label for="priceRange" @class(['form-label'])>Prix</label>
                        <input type="range" @class(['form-range']) id="priceRange" min="0" max="100">
                    </div>
                </div>
            </div>
            <!-- Détails du Produit -->
            <div @class(['col-md-9', 'product-details'])>
                <div @class(['row'])>
                    <div @class(['col-md-6'])>
                        <img src="{{ asset('storage/' . $product->image) }}" @class(['img-fluid', 'mb-3']) alt="Robe d'Été"
                            id="mainImage">
                        <div @class(['d-flex', 'gap-2'])>
                            <div @class(['thumbnail'])>
                                <img src="{{ asset('storage/' . $product->image) }}" @class(['img-fluid', 'active']) alt="Thumbnail 1"
                                    onclick="changeImage(this)">
                            </div>
                            <div @class(['thumbnail'])>
                                <img src="{{ asset('storage/' . $product->image) }}" @class(['img-fluid']) alt="Thumbnail 2"
                                    onclick="changeImage(this)">
                            </div>
                            <div @class(['thumbnail'])>
                                <img src="{{ asset('storage/' . $product->image) }}" @class(['img-fluid']) alt="Thumbnail 3"
                                    onclick="changeImage(this)">
                            </div>
                        </div>
                    </div>
                    <div @class(['col-md-6'])>
                        <h2>{{ $product->name }}</h2>
                        <p @class(['text-muted'])>{{ $product->price }}&nbsp;fcfa</p>
                        <p>{{ $product->description }}</p>
                        <div @class(['mb-3'])>
                            <label for="quantity" @class(['form-label'])>Quantité</label>
                            <input type="number" @class(['form-control']) id="quantity" value="1" min="1">
                        </div>
                        <div @class(['d-flex', 'gap-2', 'mb-3'])>
                            <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <button type="submit" @class(['btn', 'btn-primary', 'px-3'])><i @class(['fa', 'fa-shopping-cart', 'mr-1'])></i> Ajouter
                            au panier</button>
                        <input type="hidden" id="quantity" @class(['form-control', 'bg-secondary', 'text-center']) value="1"
                            readonly min="1" max="{{ $product->stock_quantity }}" name="quantity" />
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                    </form>
                            <button @class(['btn', 'btn-outline-primary'])><i @class(['bi', 'bi-heart'])></i> Liste de Souhaits</button>
                        </div>
                        <hr>
                        <h5>Avis Clients</h5>
                        <div @class(['reviews'])>
                            <p>★★★★☆ Excellent produit ! - Marie D.</p>
                            <p>★★★★★ Super confortable. - Sophie L.</p>
                        </div>
                    </div>
                </div>
                <!-- Produits Similaires -->
                <h3 @class(['mt-5'])>Vous Aimerez Aussi</h3>
                <div @class(['row', 'row-cols-1', 'row-cols-md-3', 'g-4'])>
                    <div @class(['col'])>
                        <div @class(['card', 'product-card', 'h-100'])>
                            <img src="https://via.placeholder.com/400x300" @class(['card-img-top']) alt="Haut en Coton">
                            <div @class(['card-body'])>
                                <h5 @class(['card-title'])>Haut en Coton</h5>
                                <p @class(['card-text'])>24,99 €</p>
                                <a href="#" @class(['btn', 'btn-outline-primary'])>Voir Détails</a>
                            </div>
                        </div>
                    </div>
                    <div @class(['col'])>
                        <div @class(['card', 'product-card', 'h-100'])>
                            <img src="https://via.placeholder.com/400x300" @class(['card-img-top']) alt="Pantalon Chino">
                            <div @class(['card-body'])>
                                <h5 @class(['card-title'])>Pantalon Chino</h5>
                                <p @class(['card-text'])>34,99 €</p>
                                <a href="#" @class(['btn', 'btn-outline-primary'])>Voir Détails</a>
                            </div>
                        </div>
                    </div>
                    <div @class(['col'])>
                        <div @class(['card', 'product-card', 'h-100'])>
                            <img src="https://via.placeholder.com/400x300" @class(['card-img-top']) alt="Sac Tote">
                            <div @class(['card-body'])>
                                <h5 @class(['card-title'])>Sac Tote</h5>
                                <p @class(['card-text'])>44,99 €</p>
                                <a href="#" @class(['btn', 'btn-outline-primary'])>Voir Détails</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}
@extends('layouts.slaves')

@section('title', $product->name . ' - Bkassoua')

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
                
                <div @class(['filter-section'])>
                    <div @class(['filter-options'])>
                        <div @class(['filter-option'])>
                            <a href="{{ route('shop') }}" @class(['d-flex', 'align-items-center', 'justify-content-between', 'text-decoration-none', 'text-dark'])>
                                <span>Tous les produits</span>
                                <i @class(['bi', 'bi-arrow-right', 'text-muted'])></i>
                            </a>
                        </div>
                        @foreach($categories as $category)
                        <div @class(['filter-option'])>
                            <a href="/shop/{{ $category->slug }}" @class(['d-flex', 'align-items-center', 'justify-content-between', 'text-decoration-none', 'text-dark'])>
                                <span>{{ $category->name }}</span>
                                <i @class(['bi', 'bi-arrow-right', 'text-muted'])></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Bannière livraison -->
                <div @class(['promo-banner', 'mt-4', 'p-3', 'rounded', 'text-white', 'text-center']) 
                     style="background: linear-gradient(135deg, var(--primary), var(--accent));">
                    <i @class(['bi', 'bi-truck', 'display-6', 'mb-2'])></i>
                    <h6 @class(['mb-2'])>Livraison Express</h6>
                    <p @class(['small', 'mb-0'])>Sous 24-48h</p>
                </div>
            </div>

            <!-- Produits récents -->
            <div @class(['sidebar', 'mt-4'])>
                <h5 @class(['sidebar-title'])>
                    <i @class(['bi', 'bi-clock'])></i>
                    Récemment consultés
                </h5>
                <div @class(['recent-products'])>
                    {{-- @foreach($recentProducts as $recentProduct)
                    <div @class(['recent-product', 'd-flex', 'align-items-center', 'mb-3'])>
                        <img src="{{ asset('storage/' . $recentProduct->image) }}" 
                             alt="{{ $recentProduct->name }}" 
                             @class(['recent-product-image', 'rounded', 'me-3'])>
                        <div>
                            <div @class(['fw-semibold', 'small'])>{{ \Illuminate\Support\Str::limit($recentProduct->name, 25) }}</div>
                            <div @class(['text-primary', 'fw-bold', 'small'])>{{ number_format($recentProduct->price, 0, ',', ' ') }} fcfa</div>
                        </div>
                    </div>
                    @endforeach --}}
                </div>
            </div>
        </div>

        <!-- Détails du produit améliorés -->
        <div @class(['col-lg-9', 'col-md-8'])>
            <!-- Fil d'Ariane -->
            <nav aria-label="breadcrumb" @class(['mb-4'])>
                <ol @class(['breadcrumb'])>
                    <li @class(['breadcrumb-item'])><a href="{{ route('home') }}" @class(['text-decoration-none'])>Accueil</a></li>
                    <li @class(['breadcrumb-item'])><a href="{{ route('shop') }}" @class(['text-decoration-none'])>Boutique</a></li>
                    <li @class(['breadcrumb-item'])><a href="/shop/{{ $product->category->slug ?? 'all' }}" @class(['text-decoration-none'])>{{ $product->category->name ?? 'Tous' }}</a></li>
                    <li @class(['breadcrumb-item', 'active'])>{{ \Illuminate\Support\Str::limit($product->name, 30) }}</li>
                </ol>
            </nav>

            <!-- Alertes -->
            @if (session('success'))
                <div @class(['alert', 'alert-success', 'alert-dismissible', 'fade', 'show']) role="alert">
                    <i @class(['bi', 'bi-check-circle', 'me-2'])></i>{{ session('success') }}
                    <button type="button" @class(['btn-close']) data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div @class(['alert', 'alert-danger', 'alert-dismissible', 'fade', 'show']) role="alert">
                    <i @class(['bi', 'bi-exclamation-triangle', 'me-2'])></i>{{ session('error') }}
                    <button type="button" @class(['btn-close']) data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div @class(['product-detail-card', 'bg-white', 'rounded', 'shadow-lg'])>
                <div @class(['row', 'g-0'])>
                    <!-- Gallery Produit -->
                    <div @class(['col-lg-6'])>
                        <div @class(['product-gallery', 'p-4'])>
                            <div @class(['main-image', 'mb-4'])>
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     @class(['img-fluid', 'rounded']) 
                                     id="mainImage">
                                @if($product->discount > 0)
                                <span @class(['product-badge', 'discount'])>-{{ $product->discount }}%</span>
                                @endif
                                @if($product->is_new)
                                <span @class(['product-badge', 'new'])>Nouveau</span>
                                @endif
                            </div>
                            <div @class(['thumbnail-gallery', 'd-flex', 'gap-3', 'justify-content-center'])>
                                @for($i = 0; $i < 3; $i++)
                                <div @class(['thumbnail'])>
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }} - Vue {{ $i+1 }}" 
                                         @class(['img-fluid', 'rounded', 'cursor-pointer'])
                                         onclick="changeImage(this)">
                                </div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <!-- Informations Produit -->
                    <div @class(['col-lg-6'])>
                        <div @class(['product-info', 'p-4'])>
                            <!-- En-tête produit -->
                            <div @class(['product-header', 'mb-4'])>
                                <div @class(['d-flex', 'justify-content-between', 'align-items-start'])>
                                    <div>
                                        <h1 @class(['product-title', 'mb-2'])>{{ $product->name }}</h1>
                                        <div @class(['product-rating', 'mb-3'])>
                                            <div @class(['stars'])>
                                                <i @class(['bi', 'bi-star-fill'])></i>
                                                <i @class(['bi', 'bi-star-fill'])></i>
                                                <i @class(['bi', 'bi-star-fill'])></i>
                                                <i @class(['bi', 'bi-star-fill'])></i>
                                                <i @class(['bi', 'bi-star-half'])></i>
                                            </div>
                                            <span @class(['rating-count', 'text-muted', 'ms-2'])>({{ rand(15, 80) }} avis)</span>
                                        </div>
                                    </div>
                                    <button @class(['btn', 'btn-outline-primary', 'btn-sm', 'wishlist-btn'])>
                                        <i @class(['bi', 'bi-heart'])></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Prix -->
                            <div @class(['product-pricing', 'mb-4'])>
                                @if($product->discount > 0)
                                <div @class(['d-flex', 'align-items-center', 'gap-3'])>
                                    <span @class(['current-price', 'h3', 'text-primary'])>{{ number_format($product->price * (1 - $product->discount/100), 0, ',', ' ') }} fcfa</span>
                                    <span @class(['original-price', 'text-muted', 'text-decoration-line-through'])>{{ number_format($product->price, 0, ',', ' ') }} fcfa</span>
                                    <span @class(['discount-badge', 'bg-accent', 'text-white', 'px-2', 'py-1', 'rounded', 'small'])>Économisez {{ $product->discount }}%</span>
                                </div>
                                @else
                                <span @class(['current-price', 'h3', 'text-primary'])>{{ number_format($product->price, 0, ',', ' ') }} fcfa</span>
                                @endif
                            </div>

                            <!-- Description -->
                            <div @class(['product-description', 'mb-4'])>
                                <p @class(['text-muted'])>{{ $product->description }}</p>
                            </div>

                            <!-- Options -->
                            <div @class(['product-options', 'mb-4'])>
                                @if($product->sizes && count($product->sizes) > 0)
                                <div @class(['size-options', 'mb-3'])>
                                    <label @class(['form-label', 'fw-semibold'])>Taille :</label>
                                    <div @class(['d-flex', 'flex-wrap', 'gap-2'])>
                                        @foreach($product->sizes as $size)
                                        <div @class(['form-check'])>
                                            <input @class(['form-check-input']) type="radio" name="size" id="size{{ $size }}" value="{{ $size }}">
                                            <label @class(['form-check-label', 'size-option']) for="size{{ $size }}">
                                                {{ $size }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if($product->colors && count($product->colors) > 0)
                                <div @class(['color-options', 'mb-3'])>
                                    <label @class(['form-label', 'fw-semibold'])>Couleur :</label>
                                    <div @class(['d-flex', 'flex-wrap', 'gap-2'])>
                                        @foreach($product->colors as $color)
                                        <div @class(['color-option', 'rounded']) 
                                             style="background-color: {{ $color }}; width: 30px; height: 30px;"
                                             title="{{ $color }}"
                                             onclick="selectColor(this, '{{ $color }}')"></div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <!-- Quantité -->
                                <div @class(['quantity-selector', 'mb-4'])>
                                    <label @class(['form-label', 'fw-semibold'])>Quantité :</label>
                                    <div @class(['d-flex', 'align-items-center', 'gap-3'])>
                                        <div @class(['input-group', 'quantity-input']) style="max-width: 150px;">
                                            <button @class(['btn', 'btn-outline-secondary']) type="button" onclick="decreaseQuantity()">-</button>
                                            <input type="number" @class(['form-control', 'text-center']) id="quantity" value="1" min="1" max="{{ $product->stock_quantity }}">
                                            <button @class(['btn', 'btn-outline-secondary']) type="button" onclick="increaseQuantity()">+</button>
                                        </div>
                                        <small @class(['text-muted'])>{{ $product->stock_quantity }} disponible(s)</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div @class(['product-actions', 'mb-4'])>
                                <div @class(['d-flex', 'gap-3', 'flex-wrap'])>
                                    <form action="{{ route('cart.add') }}" method="POST" @class(['flex-fill'])>
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" id="quantityInput" value="1">
                                        <button type="submit" @class(['btn', 'btn-primary', 'btn-lg', 'w-100', 'add-to-cart-btn'])>
                                            <i @class(['bi', 'bi-cart-plus', 'me-2'])></i>Ajouter au panier
                                        </button>
                                    </form>
                                    <button @class(['btn', 'btn-outline-primary', 'btn-lg', 'wishlist-btn'])>
                                        <i @class(['bi', 'bi-heart'])></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Informations supplémentaires -->
                            {{-- <div @class(['product-meta'])>
                                <div @class(['row', 'text-center'])>
                                    <div @class(['col-4'])>
                                        <i @class(['bi', 'bi-truck', 'text-primary', 'd-block', 'mb-2'])></i>
                                        <small @class(['text-muted'])>Livraison gratuite</small>
                                    </div>
                                    <div @class(['col-4'])>
                                        <i @class(['bi', 'bi-arrow-left-right', 'text-primary', 'd-block', 'mb-2'])></i>
                                        <small @class(['text-muted'])>Retours sous 30 jours</small>
                                    </div>
                                    <div @class(['col-4'])>
                                        <i @class(['bi', 'bi-shield-check', 'text-primary', 'd-block', 'mb-2'])></i>
                                        <small @class(['text-muted'])>Paiement sécurisé</small>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Détails et Avis -->
            <div @class(['product-tabs', 'mt-5'])>
                <ul @class(['nav', 'nav-tabs']) id="productTabs" role="tablist">
                    <li @class(['nav-item']) role="presentation">
                        <button @class(['nav-link', 'active']) id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">
                            Description
                        </button>
                    </li>
                    <li @class(['nav-item']) role="presentation">
                        <button @class(['nav-link']) id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab">
                            Spécifications
                        </button>
                    </li>
                    <li @class(['nav-item']) role="presentation">
                        <button @class(['nav-link']) id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                            Avis ({{ rand(15, 80) }})
                        </button>
                    </li>
                </ul>
                <div @class(['tab-content', 'bg-white', 'p-4', 'rounded-bottom', 'shadow-sm']) id="productTabsContent">
                    <div @class(['tab-pane', 'fade', 'show', 'active']) id="description" role="tabpanel">
                        <p>{{ $product->description }}</p>
                        {{-- <div @class(['row', 'mt-4'])>
                            <div @class(['col-md-6'])>
                                <h6>Caractéristiques</h6>
                                <ul @class(['list-unstyled'])>
                                    <li><i @class(['bi', 'bi-check', 'text-success', 'me-2'])></i> Matière de haute qualité</li>
                                    <li><i @class(['bi', 'bi-check', 'text-success', 'me-2'])></i> Confort optimal</li>
                                    <li><i @class(['bi', 'bi-check', 'text-success', 'me-2'])></i> Lavable en machine</li>
                                </ul>
                            </div>
                            <div @class(['col-md-6'])>
                                <h6>Entretien</h6>
                                <ul @class(['list-unstyled'])>
                                    <li><i @class(['bi', 'bi-info-circle', 'text-primary', 'me-2'])></i> Lavage à 30°C</li>
                                    <li><i @class(['bi', 'bi-info-circle', 'text-primary', 'me-2'])></i> Ne pas blanchir</li>
                                    <li><i @class(['bi', 'bi-info-circle', 'text-primary', 'me-2'])></i> Repassage à basse température</li>
                                </ul>
                            </div>
                        </div> --}}
                    </div>
                    <div @class(['tab-pane', 'fade']) id="specifications" role="tabpanel">
                        <div @class(['row'])>
                            <div @class(['col-md-6'])>
                                <table @class(['table', 'table-borderless'])>
                                    <tr>
                                        <td @class(['fw-semibold'])>Catégorie</td>
                                        <td>{{ $product->category->name ?? 'Non catégorisé' }}</td>
                                    </tr>
                                    <tr>
                                        <td @class(['fw-semibold'])>Marque</td>
                                        <td>Bkassoua</td>
                                    </tr>
                                    <tr>
                                        <td @class(['fw-semibold'])>Matière</td>
                                        <td>Coton 100%</td>
                                    </tr>
                                </table>
                            </div>
                            <div @class(['col-md-6'])>
                                <table @class(['table', 'table-borderless'])>
                                    <tr>
                                        <td @class(['fw-semibold'])>Référence</td>
                                        <td>BK{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    </tr>
                                    <tr>
                                        <td @class(['fw-semibold'])>Stock</td>
                                        <td>{{ $product->stock_quantity }} unités</td>
                                    </tr>
                                    <tr>
                                        <td @class(['fw-semibold'])>Condition</td>
                                        <td>Neuf</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div @class(['tab-pane', 'fade']) id="reviews" role="tabpanel">
                        <div @class(['row'])>
                            <div @class(['col-md-4'])>
                                <div @class(['text-center', 'mb-4'])>
                                    <div @class(['display-6', 'text-primary', 'mb-2'])>4.5/5</div>
                                    <div @class(['stars', 'mb-2'])>
                                        <i @class(['bi', 'bi-star-fill'])></i>
                                        <i @class(['bi', 'bi-star-fill'])></i>
                                        <i @class(['bi', 'bi-star-fill'])></i>
                                        <i @class(['bi', 'bi-star-fill'])></i>
                                        <i @class(['bi', 'bi-star-half'])></i>
                                    </div>
                                    <small @class(['text-muted'])>Basé sur {{ rand(15, 80) }} avis</small>
                                </div>
                            </div>
                            <div @class(['col-md-8'])>
                                @for($i = 0; $i < 3; $i++)
                                <div @class(['review-item', 'mb-4', 'pb-4', 'border-bottom'])>
                                    <div @class(['d-flex', 'justify-content-between', 'mb-2'])>
                                        <div @class(['fw-semibold'])>Utilisateur {{ $i+1 }}</div>
                                        <div @class(['stars', 'small'])>
                                            <i @class(['bi', 'bi-star-fill'])></i>
                                            <i @class(['bi', 'bi-star-fill'])></i>
                                            <i @class(['bi', 'bi-star-fill'])></i>
                                            <i @class(['bi', 'bi-star-fill'])></i>
                                            {{-- <i @class(['bi', 'bi-star{{', '$i', '==', '1', '?', ''-half'', ':', '($i', '==', '2', '?', '''', ':', ''-fill')', '}}'])></i> --}}
                                        </div>
                                    </div>
                                    <p @class(['text-muted', 'mb-2'])>"{{ ['Excellent produit, je recommande !', 'Très bon rapport qualité-prix', 'Correct mais pourrait être amélioré'][$i] }}"</p>
                                    <small @class(['text-muted'])>Posté le {{ now()->subDays(rand(1, 30))->format('d/m/Y') }}</small>
                                </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produits similaires -->
            {{-- <section @class(['mt-5'])>
                <div @class(['section-title'])>
                    <h2>Produits Similaires</h2>
                    <p @class(['text-muted'])>Découvrez d'autres articles qui pourraient vous plaire</p>
                </div>
                
                <div @class(['products-grid'])>
                    @foreach($relatedProducts as $relatedProduct)
                    <div @class(['product-card', 'fade-in'])>
                        <div @class(['product-image'])>
                            <img src="{{ asset('storage/' . $relatedProduct->image) }}" alt="{{ $relatedProduct->name }}">
                            @if($relatedProduct->discount > 0)
                            <span @class(['product-badge'])>-{{ $relatedProduct->discount }}%</span>
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
                            <div @class(['product-category'])>{{ $relatedProduct->category->name ?? 'Similaire' }}</div>
                            <h3 @class(['product-title'])>{{ $relatedProduct->name }}</h3>
                            
                            <div @class(['product-rating'])>
                                <div @class(['stars'])>
                                    <i @class(['bi', 'bi-star-fill'])></i>
                                    <i @class(['bi', 'bi-star-fill'])></i>
                                    <i @class(['bi', 'bi-star-fill'])></i>
                                    <i @class(['bi', 'bi-star-fill'])></i>
                                    <i @class(['bi', 'bi-star'])></i>
                                </div>
                                <span @class(['rating-count'])>({{ rand(10, 50) }})</span>
                            </div>
                            
                            <div @class(['product-price'])>
                                @if($relatedProduct->discount > 0)
                                <span @class(['current-price'])>{{ number_format($relatedProduct->price * (1 - $relatedProduct->discount/100), 0, ',', ' ') }} fcfa</span>
                                <span @class(['original-price'])>{{ number_format($relatedProduct->price, 0, ',', ' ') }} fcfa</span>
                                @else
                                <span @class(['current-price'])>{{ number_format($relatedProduct->price, 0, ',', ' ') }} fcfa</span>
                                @endif
                            </div>
                            
                            <div @class(['d-flex', 'gap-2', 'mt-3'])>
                                <a href="{{ route('shop.detail', $relatedProduct->id) }}" 
                                   @class(['btn', 'btn-outline-primary', 'flex-fill', 'd-flex', 'align-items-center', 'justify-content-center', 'gap-2'])>
                                    <i @class(['bi', 'bi-eye'])></i>
                                    <span>Voir détails</span>
                                </a>
                                <button @class(['btn', 'btn-primary', 'add-to-cart']) data-product-id="{{ $relatedProduct->id }}">
                                    <i @class(['bi', 'bi-cart-plus'])></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section> --}}
        </div>
    </div>
</div>

<!-- Scripts pour la page détail produit -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la galerie d'images
    function changeImage(element) {
        const mainImage = document.getElementById('mainImage');
        const thumbnails = document.querySelectorAll('.thumbnail img');
        
        // Mettre à jour l'image principale
        mainImage.src = element.src;
        
        // Mettre à jour les classes actives
        thumbnails.forEach(thumb => thumb.classList.remove('active'));
        element.classList.add('active');
    }

    // Gestion de la quantité
    function increaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        const maxQuantity = parseInt(quantityInput.getAttribute('max'));
        const currentValue = parseInt(quantityInput.value);
        
        if (currentValue < maxQuantity) {
            quantityInput.value = currentValue + 1;
            updateQuantityInput();
        }
    }

    function decreaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
            updateQuantityInput();
        }
    }

    function updateQuantityInput() {
        const quantityInput = document.getElementById('quantity');
        const hiddenInput = document.getElementById('quantityInput');
        hiddenInput.value = quantityInput.value;
    }

    // Sélection de couleur
    function selectColor(element, color) {
        const colorOptions = document.querySelectorAll('.color-option');
        colorOptions.forEach(option => option.classList.remove('active', 'border', 'border-2', 'border-primary'));
        element.classList.add('active', 'border', 'border-2', 'border-primary');
    }

    // Animation d'ajout au panier
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const originalText = button.innerHTML;
            button.innerHTML = '<i @class(['bi', 'bi-check-lg', 'me-2'])></i>Ajouté !';
            button.classList.add('btn-success');
            button.classList.remove('btn-primary');
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('btn-success');
                button.classList.add('btn-primary');
            }, 2000);
        });
    });

    // Gestion des wishlists
    document.querySelectorAll('.wishlist-btn').forEach(button => {
        button.addEventListener('click', function() {
            this.classList.toggle('active');
            if (this.classList.contains('active')) {
                this.innerHTML = '<i @class(['bi', 'bi-heart-fill', 'text-danger'])></i>';
            } else {
                this.innerHTML = '<i @class(['bi', 'bi-heart'])></i>';
            }
        });
    });

    // Animation des éléments au défilement
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.product-card').forEach(card => {
        observer.observe(card);
    });

    // Initialiser la quantité
    updateQuantityInput();
    document.getElementById('quantity').addEventListener('change', updateQuantityInput);
});
</script>

<style>
/* Styles spécifiques à la page détail produit */
.product-detail-card {
    border-radius: var(--border-radius);
    overflow: hidden;
}

.product-gallery .main-image {
    position: relative;
}

.product-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    z-index: 2;
}

.product-badge.discount {
    background: var(--accent);
    color: white;
}

.product-badge.new {
    background: var(--primary);
    color: white;
}

.thumbnail-gallery .thumbnail img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border: 2px solid transparent;
    transition: var(--transition);
    cursor: pointer;
}

.thumbnail-gallery .thumbnail img:hover,
.thumbnail-gallery .thumbnail img.active {
    border-color: var(--primary);
    transform: scale(1.05);
}

.product-title {
    font-weight: 700;
    color: var(--dark);
    line-height: 1.2;
}

.current-price {
    font-weight: 700;
}

.original-price {
    font-size: 1.1rem;
}

.discount-badge {
    font-size: 0.8rem;
    font-weight: 600;
}

.size-option {
    padding: 8px 16px;
    border: 1px solid var(--gray-light);
    border-radius: 8px;
    cursor: pointer;
    transition: var(--transition);
}

.size-option:hover {
    border-color: var(--primary);
}

.form-check-input:checked + .size-option {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.quantity-input .btn {
    width: 40px;
}

.quantity-input .form-control {
    border-left: none;
    border-right: none;
}

.recent-product-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
}

.nav-tabs .nav-link {
    color: var(--dark);
    font-weight: 500;
    border: none;
    padding: 12px 24px;
}

.nav-tabs .nav-link.active {
    color: var(--primary);
    border-bottom: 3px solid var(--primary);
    background: transparent;
}

.product-tabs .tab-content {
    border: 1px solid var(--gray-light);
    border-top: none;
    border-radius: 0 0 var(--border-radius) var(--border-radius);
}

.review-item:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}

/* Responsive */
@media (max-width: 768px) {
    .product-detail-card .row {
        flex-direction: column;
    }
    
    .thumbnail-gallery {
        flex-wrap: wrap;
    }
    
    .product-actions .d-flex {
        flex-direction: column;
    }
    
    .product-actions .btn {
        width: 100%;
        margin-bottom: 10px;
    }
    
    .nav-tabs .nav-link {
        padding: 10px 15px;
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .product-title {
        font-size: 1.5rem;
    }
    
    .current-price {
        font-size: 1.5rem;
    }
    
    .quantity-input {
        max-width: 120px !important;
    }
}
</style>
@endsection