@extends('layouts.slaves')

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
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <!-- Contenu Principal -->
    <div class="container-fluid pt-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="sidebar">
                    <h5>Catégories</h5>
                    <div class="accordion" id="categoryAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Vêtements
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                data-bs-parent="#categoryAccordion">
                                <div class="accordion-body">
                                    <a href="#" class="d-block">Robes</a>
                                    <a href="#" class="d-block">Hauts</a>
                                    <a href="#" class="d-block">Pantalons</a>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Accessoires
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                data-bs-parent="#categoryAccordion">
                                <div class="accordion-body">
                                    <a href="#" class="d-block">Sacs</a>
                                    <a href="#" class="d-block">Bijoux</a>
                                    <a href="#" class="d-block">Chaussures</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h5>Filtres</h5>
                    <div class="mb-3">
                        <label for="priceRange" class="form-label">Prix</label>
                        <input type="range" class="form-range" id="priceRange" min="0" max="100">
                    </div>
                </div>
            </div>
            <!-- Détails du Produit -->
            <div class="col-md-9 product-details">
                <div class="row">
                    <div class="col-md-6">
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid mb-3" alt="Robe d'Été"
                            id="mainImage">
                        <div class="d-flex gap-2">
                            <div class="thumbnail">
                                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid active" alt="Thumbnail 1"
                                    onclick="changeImage(this)">
                            </div>
                            <div class="thumbnail">
                                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="Thumbnail 2"
                                    onclick="changeImage(this)">
                            </div>
                            <div class="thumbnail">
                                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="Thumbnail 3"
                                    onclick="changeImage(this)">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h2>{{ $product->name }}</h2>
                        <p class="text-muted">{{ $product->price }}&nbsp;fcfa</p>
                        <p>{{ $product->description }}</p>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantité</label>
                            <input type="number" class="form-control" id="quantity" value="1" min="1">
                        </div>
                        <div class="d-flex gap-2 mb-3">
                            <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary px-3"><i class="fa fa-shopping-cart mr-1"></i> Ajouter
                            au panier</button>
                        <input type="hidden" id="quantity" class="form-control bg-secondary text-center" value="1"
                            readonly min="1" max="{{ $product->stock_quantity }}" name="quantity" />
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                    </form>
                            <button class="btn btn-outline-primary"><i class="bi bi-heart"></i> Liste de Souhaits</button>
                        </div>
                        <hr>
                        <h5>Avis Clients</h5>
                        <div class="reviews">
                            <p>★★★★☆ Excellent produit ! - Marie D.</p>
                            <p>★★★★★ Super confortable. - Sophie L.</p>
                        </div>
                    </div>
                </div>
                <!-- Produits Similaires -->
                <h3 class="mt-5">Vous Aimerez Aussi</h3>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <div class="col">
                        <div class="card product-card h-100">
                            <img src="https://via.placeholder.com/400x300" class="card-img-top" alt="Haut en Coton">
                            <div class="card-body">
                                <h5 class="card-title">Haut en Coton</h5>
                                <p class="card-text">24,99 €</p>
                                <a href="#" class="btn btn-outline-primary">Voir Détails</a>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card product-card h-100">
                            <img src="https://via.placeholder.com/400x300" class="card-img-top" alt="Pantalon Chino">
                            <div class="card-body">
                                <h5 class="card-title">Pantalon Chino</h5>
                                <p class="card-text">34,99 €</p>
                                <a href="#" class="btn btn-outline-primary">Voir Détails</a>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card product-card h-100">
                            <img src="https://via.placeholder.com/400x300" class="card-img-top" alt="Sac Tote">
                            <div class="card-body">
                                <h5 class="card-title">Sac Tote</h5>
                                <p class="card-text">44,99 €</p>
                                <a href="#" class="btn btn-outline-primary">Voir Détails</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
