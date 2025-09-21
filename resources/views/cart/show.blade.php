@extends('layouts.slaves')

@section('content')
    {{-- <!-- Page Header Start -->
    <div class="container-fluid bg-secondary">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Panier</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="">Home</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Panier</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Cart Start -->
    <div class="container-fluid pt-3">
        <div class="row px-xl-5">
            <div class="col-lg-8 table-responsive mb-5">
                <table class="table table-bordered text-center mb-0">
                    <thead class="bg-secondary text-dark">
                        <tr>
                            <th>Products</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @foreach ($carts as $cart)
                            <tr>
                                <td class="align-middle"><img src="img/product-1.jpg" alt="" style="width: 50px;">
                                    {{ $cart->product->name }}</td>
                                <td class="align-middle">{{ $cart->product->price }}</td>
                                <td class="align-middle">
                                    <div class="input-group quantity mx-auto" style="width: 100px;">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-primary btn-minus">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control form-control-sm bg-secondary text-center"
                                            value="{{ $cart->quantity }}">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-primary btn-plus">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    {{ $cart->product->price * $cart->quantity }}

                                </td>
                                <td class="align-middle"><button class="btn btn-sm btn-primary"><i
                                            class="fa fa-times"></i></button></td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <div class="col-lg-4">
                <form class="mb-5" action="">
                    <div class="input-group">
                        <input type="text" class="form-control p-4" placeholder="Coupon Code">
                        <div class="input-group-append">
                            <button class="btn btn-primary"> Coupon</button>
                        </div>
                    </div>
                </form>
                <div class="card border-secondary mb-5">
                    <div class="card-header bg-secondary border-0">
                        <h4 class="font-weight-semi-bold m-0">{{ 'Résumé du panier' }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 pt-1">
                            <h6 class="font-weight-medium">Subtotal</h6>
                            <h6 class="font-weight-medium">{{ $total }}&nbsp;fcfa</h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Livraison</h6>
                            <h6 class="font-weight-medium">{{ 1000 }}&nbsp;fcfa</h6>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <div class="d-flex justify-content-between mt-2">
                            <h5 class="font-weight-bold">Total</h5>
                            <h5 class="font-weight-bold">{{ $total + 1000 }}&nbsp;fcfa</h5>
                        </div>
                        @if (!$carts->isEmpty())
                            <form action="{{ route('cart.checkout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    Passer la commande ({{ $total + 1000 }}&nbsp;fcfa)
                                </button>
                            </form>
                        @endif
                        <!-- <button class="btn btn-block btn-primary my-3 py-3">Commander</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart End --> --}}
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
                    <hr />
                    <h5>Filtres</h5>
                    <div class="mb-3">
                        <label for="priceRange" class="form-label">Prix</label>
                        <input type="range" class="form-range" id="priceRange" min="0" max="100" />
                    </div>
                    <div class="mb-3">
                        <label for="size" class="form-label">Taille</label>
                        <select class="form-select" id="size">
                            <option>Toutes</option>
                            <option>S</option>
                            <option>M</option>
                            <option>L</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- Panier et Paiement -->
            <div class="col-md-9">
                <h2>Votre Panier</h2>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                          @foreach ($carts as $cart)
                        <tr>
                            <td> {{ $cart->product->name }}</td>
                            <td> {{ $cart->product->price }}&nbsp;fcfa</td>
                            <td>
                                <input type="number" class="form-control" value="1" min="1"
                                    onchange="updateTotal(this)" />
                            </td>
                            <td class="item-total"> {{ $cart->product->price * $cart->quantity }}&nbsp;fcfa</td>
                            <td>
                                <button class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-end">
                    <h5 id="cart-total">Livraison : {{ 1000 }}&nbsp;fcfa</h5>
                    <h4 id="cart-total">Total : {{ $total+1000 }}&nbsp;fcfa</h4>
                    <a href="#checkout" class="btn btn-primary">Passer à la Caisse</a>
                </div>
                <!-- Formulaire de Paiement -->
                <div class="mt-5" id="checkout">
                    <h3>Paiement</h3>
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom Complet</label>
                            <input type="text" class="form-control" id="name" required />
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse de Livraison</label>
                            <input type="text" class="form-control" id="address" required />
                        </div>
                        <div class="mb-3">
                            <label for="payment" class="form-label">Méthode de Paiement</label>
                            <select class="form-select" id="payment">
                                <option>Carte de Crédit</option>
                                <option>PayPal</option>
                                <option>Virement Bancaire</option>
                            </select>
                        </div>
                        <div class="d-flex gap-2 mb-3 trust-badge">
                            <img src="https://via.placeholder.com/100x30" alt="Paiement Sécurisé" />
                            <img src="https://via.placeholder.com/100x30" alt="Retours Gratuits" />
                        </div>
                        <button type="submit" class="btn btn-success">
                            Confirmer la Commande
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
