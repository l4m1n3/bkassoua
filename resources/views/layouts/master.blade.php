<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'B KASSOUA') }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="{{asset('assets/img/logo.png')}}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{asset('assets/lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row bg-secondary py-2 px-xl-5">
            <div class="col-lg-6 d-none d-lg-block">
                <div class="d-inline-flex align-items-center">
                    <a class="text-dark" href="">FAQs</a>
                    <span class="text-muted px-2">|</span>
                    <a class="text-dark" href="">Aide</a> 
                    <span class="text-muted px-2">|</span> 
                    <a class="text-dark" href="">Support</a>
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    <a class="text-dark px-2" href="">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a class="text-dark px-2" href="">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a class="text-dark px-2" href="">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a class="text-dark px-2" href="">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a class="text-dark pl-2" href="">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="row align-items-center py-3 px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a href="{{route('home')}}" class="text-decoration-none">
                    <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1">B</span>Kassoua</h1>
                </a>
            </div>
            <div class="col-lg-6 col-6 text-left">
                <form action="{{ route('search') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="query" class="form-control" placeholder="Rechercher ds produits">
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-3 col-6 text-right">
                <a href="{{ route('cart') }}" class="btn border">
                    <i class="fas fa-shopping-cart text-primary"></i>
                    <span class="badge">0</span>
                </a>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid mb-5">
        <div class="row border-top px-xl-5">
            <!-- category  -->
            <div class="col-lg-3 d-none d-lg-block">
                <a class="btn shadow-none d-flex align-items-center justify-content-between bg-primary text-white w-100" data-toggle="collapse" href="#navbar-vertical" style="height: 65px; margin-top: -1px; padding: 0 30px;">
                    <h6 class="m-0">Categories</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse show navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0" id="navbar-vertical">
                    <div class="navbar-nav w-100 overflow-hidden" style="height: 410px">
                        @foreach ($categories as $categorie)
                        <a href="" class="nav-item nav-link">{{$categorie->name}}</a>
                        @endforeach
                    </div>
                </nav>
            </div>
            <!-- fin category -->
            <div class="col-lg-9">
                <!-- debut menu -->
                <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                    <a href="{{ route('home') }}" class="text-decoration-none d-block d-lg-none">
                        <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1">B</span>Kassoua</h1>
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">

                         <div class="navbar-nav mr-auto py-0">
                            <a href="{{ route('home') }}" class="nav-item nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a>
                            <a href="{{ route('shop') }}" class="nav-item nav-link {{ request()->routeIs('shop') ? 'active' : '' }}">Marketplace</a>
                            {{-- <a href="{{ route('contact') }}" class="nav-item nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a> --}}
                            @auth
                                @if (Auth::user()->role === 'vendor' && $vendor->status == 'active')
                                    <a href="{{ route('vendor.dashboard') }}" class="nav-item nav-link">Ma boutique</a>
                                @elseif (Auth::user()->role === 'vendor' && $vendor->status == 'inactive')
                                    <a href="#" class="nav-item text-warning nav-link disabled">Ma boutique(En cours de validation)</a>
                                @else
                                <a href="{{ route('vendor.register') }}" class="nav-item nav-link">Devenir vendeur</a>
                                @endif
                            @endauth
                        </div>
                        <div class="navbar-nav ml-auto py-0">
                            @auth
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                                        {{ Auth::user()->name }}
                                    </a>
                                    <div class="dropdown-menu rounded-0 m-0">
                                        <a href="{{ route('profile.edit') }}" class="dropdown-item">Profil</a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Déconnexion</button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="btn nav-item nav-link">Se connecter</a>
                                <a href="{{ route('register') }}" class="btn nav-item nav-link">S'inscrire</a>
                            @endauth
                        </div>
                    </div>
                </nav>
                <!-- fin menu  -->
                <!-- debut caroussel -->
                <div id="header-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active" style="height: 410px;">
                            <img class="img-fluid" src="{{asset('assets/img/4.webp')}}" alt="Image">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h4 class="text-light text-uppercase font-weight-medium mb-3"></h4>
                                    <h3 class="display-4 text-white font-weight-semi-bold mb-4"></h3>
                                    <a href="{{route('shop')}}" class="btn btn-light py-2 px-3">Visiter maintenant</a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item" style="height: 410px;">
                            <img class="img-fluid" src="{{asset('assets/img/3.webp')}}" alt="Image">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h4 class="text-light text-uppercase font-weight-medium mb-3"></h4>
                                    <h3 class="display-4 text-white font-weight-semi-bold mb-4"></h3>
                                    <a href="{{route('shop')}}" class="btn btn-light py-2 px-3">Visiter maintenant</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#header-carousel" data-slide="prev">
                        <div class="btn btn-dark" style="width: 45px; height: 45px;">
                            <span class="carousel-control-prev-icon mb-n2"></span>
                        </div>
                    </a>
                    <a class="carousel-control-next" href="#header-carousel" data-slide="next">
                        <div class="btn btn-dark" style="width: 45px; height: 45px;">
                            <span class="carousel-control-next-icon mb-n2"></span>
                        </div>
                    </a>
                </div>
                <!-- fin caroussel  -->
            </div>
        </div>
    </div>
    <!-- Navbar End -->


    <!-- Featured Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5 pb-3">
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fa fa-check text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">Quality Product</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fa fa-shipping-fast text-primary m-0 mr-2"></h1>
                    <h5 class="font-weight-semi-bold m-0">Livraison</h5>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                    <h1 class="fa fa-phone-volume text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0">24/7 Support</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- Featured End -->


    <!-- Categories Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5 pb-3">
        @foreach ($categories as $categorie)
            <div class="col-lg-4 col-md-6 pb-1">
                <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
                    <p class="text-right">{{ $categorie->products_count }} Products</p>
                    <a href="/shop/{{ $categorie->slug }}" class="cat-img position-relative overflow-hidden mb-3">
                        <img class="img-fluid" src="{{ asset('storage/' . $categorie->image) }}" alt="">
                    </a>
                    <h5 class="font-weight-semi-bold m-0">{{ $categorie->name }}</h5>
                </div>
            </div>
        @endforeach
        </div>
    </div>
    <!-- Categories End -->


    <!-- Offer Start -->
    <!-- <div class="container-fluid offer pt-5">
        <div class="row px-xl-5">
            @foreach($promotions as $promotion)
            <div class="col-md-6 pb-4">
                <div class="position-relative bg-secondary text-center text-md-right text-white mb-2 py-5 px-5">
                    <img src="img/offer-1.png" alt="">
                    <div class="position-relative" style="z-index: 1;">
                        <h5 class="text-uppercase text-primary mb-3">{{$promotion->discount_percentage}}% sur les categories</h5>
                        <h1 class="mb-4 font-weight-semi-bold">{{$promotion->category->name}}</h1>
                        <a href="" class="btn btn-outline-primary py-md-2 px-md-3">Shop Now</a>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="col-md-6 pb-4">
                <div class="position-relative bg-secondary text-center text-md-left text-white mb-2 py-5 px-5">
                    <img src="img/offer-2.png" alt="">
                    <div class="position-relative" style="z-index: 1;">
                        <h5 class="text-uppercase text-primary mb-3">20% off the all order</h5>
                        <h1 class="mb-4 font-weight-semi-bold">Winter Collection</h1>
                        <a href="" class="btn btn-outline-primary py-md-2 px-md-3">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Offer Start -->
<div id="offerCarousel" class="carousel slide container-fluid offer pt-5" data-ride="carousel">
    <div class="carousel-inner">
        @foreach($promotions as $index => $promotion)
        <div class="carousel-item @if($index === 0) active @endif">
            <div class="row px-xl-5">
                <div class="col-md-12 pb-4">
                    <div class="position-relative bg-secondary text-center text-md-right text-white mb-2 py-5 px-5">
                        {{-- <img src="{{asset('assets/img/offer-1.png')}}" alt="Promotion Image" class="img-fluid"> --}}
                        <div class="position-relative" style="z-index: 1;">
                            <h5 class="text-uppercase text-primary mb-3">{{$promotion->discount_percentage}}% sur les catégories</h5>
                            <h1 class="mb-4 font-weight-semi-bold">{{$promotion->category->name}}</h1>
                            <a href="#" class="btn btn-outline-primary py-md-2 px-md-3">Shop Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <!-- Carousel controls -->
    <a class="carousel-control-prev" href="#offerCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#offerCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<!-- Offer End -->

<script>
    // Active le défilement automatique avec un intervalle de 5 secondes (5000 ms)
    $('#offerCarousel').carousel({
        interval: 3000,
        ride: 'carousel'
    });
</script>


    <!-- Offer End -->


    <!-- Products Start -->
    <div class="container-fluid pt-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">Produits populaires</span></h2>
        </div>
        <div class="row px-xl-5 pb-3">
            @foreach($popularProducts as $popularProduct)
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="card product-item border-0 mb-4">
                    <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                        <img  class="card-img-top img-fluid w-100" style="height: 300px; width: 100%; object-fit: cover;" 
                        alt="Product Image" src="{{ $popularProduct->image ? asset('storage/' . $popularProduct->image) : asset('images/default-product.jpg') }}" alt="">
                    </div>
                    <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                        <h6 class="text-truncate mb-3">{{$popularProduct->name}}</h6>
                        <div class="d-flex justify-content-center">
                            <h6>{{$popularProduct->price}}</h6><h6 class="text-muted ml-2"><del>{{$popularProduct->price}}</del></h6>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between bg-light border">
                        <a href="{{ route('shop.detail',$popularProduct->id)  }}" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>Voir Detail</a>
                        {{-- <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a> --}}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <!-- Products End -->
    <!-- Subscribe Start -->
    <div class="container-fluid bg-secondary my-5">
        <div class="row justify-content-md-center py-5 px-xl-5">
            <div class="col-md-6 col-12 py-5">
                <div class="text-center mb-2 pb-2">
                    <h2 class="section-title px-5 mb-3"><span class="bg-secondary px-2">Rester connecté</span></h2>
                    <p>Amet lorem at rebum amet dolores. Elitr lorem dolor sed amet diam labore at justo ipsum eirmod duo labore labore.</p>
                </div>
                <form action="">
                    <div class="input-group">
                        <input type="text" class="form-control border-white p-4" placeholder="Numero de telephone">
                        <div class="input-group-append">
                            <button class="btn btn-primary px-4">Souscrire</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Subscribe End -->


    <!-- Products Start -->
    <div class="container-fluid pt-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">Nouveaux Produits</span></h2>
        </div>
       
        <div class="row px-xl-5 pb-3">
        @foreach($productsThisWeeks as $productsThisWeek)
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="card product-item border-0 mb-4">
                    <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                        <img  class="card-img-top img-fluid w-100" style="height: 300px; width: 100%; object-fit: cover;"
                         alt="Product Image" src="{{ $productsThisWeek->image ? asset('storage/' . $productsThisWeek->image) : asset('images/default-product.jpg') }}" alt="">
                    </div>
                    <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                        <h6 class="text-truncate mb-3">{{$productsThisWeek->name}}</h6>
                        <div class="d-flex justify-content-center">
                            <h6>{{$productsThisWeek->price}}</h6><h6 class="text-muted ml-2"><del>{{$productsThisWeek->price}}</del></h6>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between bg-light border">
                        <a href="{{ route('shop.detail',$productsThisWeek->id)  }}" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>Voir Detail</a>
                        {{-- <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a> --}}
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </div>
    <!-- Products End -->

        @include('includes.footer')

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('assets/lib/easing/easing.min.js')}}"></script>
    <script src="{{asset('assets/lib/owlcarousel/owl.carousel.min.js')}}"></script>

    <!-- Contact Javascript File -->
    <script src="{{asset('assets/mail/jqBootstrapValidation.min.js')}}"></script>
    <script src="{{asset('assets/mail/contact.js')}}"></script>

    <!-- Template Javascript -->
    <script src="{{asset('assets/js/main.js')}}"></script>
</body>

</html>