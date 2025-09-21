@extends('layouts.slaves')

@section('content')
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
            <!-- Grille des Produits -->
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Tous les Produits</h2>
                    <select class="form-select w-auto">
                        <option>Trier par : Popularité</option>
                        <option>Prix : Croissant</option>
                        <option>Prix : Décroissant</option>
                    </select>
                </div>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @if ($products->isEmpty())
                        <div class="col-12 text-center">
                            <h4>No products found.</h4>
                        </div>
                    @else
                        @foreach ($products as $product)
                            <div class="col">
                                <div class="card product-card h-100">
                                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top"
                                        alt="Robe d'Été">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->name }}</h5>
                                        <p class="card-text">{{ $product->price }}&nbsp;fcfa</p>
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('shop.detail', $product->id) }}"
                                                class="btn btn-outline-primary">Voir
                                                Détails</a>
                                            <button class="btn btn-primary"><i class="bi bi-cart-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <div class="col-12 pb-1">

                        <nav aria-label="Page navigation">
                            {{ $products->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop End -->
@endsection
