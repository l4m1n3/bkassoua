{{-- @extends('layouts.slaves')

@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">{{ $vendor->store_name }}</h1>
            <p class="font-size-lg text-dark">Bienvenue sur votre tableau de bord</p>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Shop Product Start -->
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary mb-4 col-3" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Ajouter un produit
    </button>
    <ul>
        @if ($products->isEmpty())
            <p>Aucun produit trouvé.</p>
        @else
            <div class="col-12">
                <div class="row pb-1">
                    @foreach ($products as $product)
                        <div class="card border-secondary ml-3 mt-3" style="width: 18rem;">

                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-product.jpg') }}"
                            class="card-img-top img-fluid" style="height: 300px; width: 100%; object-fit: cover;" alt="Product Image">

                            <div class="card-body">
                                <h5 class="card-title"> {{ $product->name }}</h5>
                                <p class="card-text"> {{ $product->description }}</p>
                                <h6 class="card-title"> {{ $product->price }}&nbsp;FCFA</h6>
                            </div>
                            <div class="card-footer">
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-primary mr-2" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $product->id }}">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <button class="btn btn-primary mr-2" data-bs-toggle="modal" data-bs-target="#modalUpdate{{ $product->id }}">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </button>
                                <form action="{{ route('vendor.products.delete', $product->id) }}" method="POST" 
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>

                            </div>
                        </div>
                        <!-- Modal detail-->
                        <div class="modal fade " id="modalDetail{{ $product->id }}" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Detail produit</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            @csrf

                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1">Nom du produit</span>
                                                <input type="text" class="form-control" name="name"
                                                    aria-label="Username" aria-describedby="product name"
                                                    value="{{ $product->name }}" disabled>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label class="input-group-text" for="">Description du
                                                    produits</label><br>
                                                <textarea name="description" class="form-control" id="" cols="15" rows="3" disabled>
                                                    {{ $product->description }}</textarea>
                                            </div>

                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1">Prix</span>
                                                <input type="number" name="price" id="price" class="form-control"
                                                    value="{{ $product->price }}" disabled>
                                            </div>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Quantité</span>
                                                <input type="number" name="stock_quantity" id="stock_quantity"
                                                    class="form-control" value="{{ $product->stock_quantity }}" disabled>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label class="input-group-text" for="inputGroupFile01">Image du
                                                    produit</label>
                                                <input type="file" name="image" id="image" class="form-control"
                                                    id="inputGroupFile01">
                                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-product.jpg') }}"
                                                    class="card-img-top" />
                                            </div>
                                            <div class="input-group mb-3">
                                                <select name="category_id" id="" class="form-control" disabled>
                                                    <option value=""> ---Choisir Categorie---</option>

                                                    <option value="{{ $product->category_id }}" selected>
                                                        {{ $product->category->name }}</option>
                                                </select>
                                            </div>
                                            <div class="input-group mb-3 ml-4 form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_active"
                                                    role="switch" id="flexSwitchCheckChecked"
                                                    value="{{ $product->is_active }}" disabled
                                                    {{ $product->is_active ? 'checked' : '' }}>
                                                <label for="is_active">Afficher
                                                    sur
                                                    la marketplace</label>
                                            </div>

                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Update-->
                        <div class="modal fade " id="modalUpdate{{ $product->id }}" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modifier produit</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('vendor.products.update', $product->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1">Nom du produit</span>
                                                <input type="text" class="form-control" name="name"
                                                    aria-label="Username" aria-describedby="product name"
                                                    value="{{ old('name', $product->name) }}">
                                            </div>
                                            <div class="input-group mb-3">
                                                <label class="input-group-text" for="">Description du
                                                    produits</label><br>
                                                <textarea name="description" class="form-control" id="" cols="15" rows="3">
                                                    {{ old('description', $product->description) }}</textarea>
                                            </div>

                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1">Prix</span>
                                                <input type="number" name="price" id="price" class="form-control"
                                                    value="{{ old('price', $product->price) }}">
                                            </div>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Quantité</span>
                                                <input type="number" name="stock_quantity" id="stock_quantity"
                                                    class="form-control"
                                                    value="{{ old('stock_quantity', $product->stock_quantity) }}">
                                            </div>
                                            <div class="input-group mb-3">
                                                <label class="input-group-text" for="inputGroupFile01">Image du
                                                    produit</label>
                                                <input type="file" name="image" id="image" class="form-control"
                                                    id="inputGroupFile01">
                                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-product.jpg') }}"
                                                    class="card-img-top" />
                                            </div>
                                            <div class="input-group mb-3">
                                                <select name="category_id" id="" class="form-control">
                                                    <option value="{{ $product->category->category_id }}" selected>
                                                        {{ $product->category->name }}</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="input-group mb-3 ml-4 form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_active"
                                                    role="switch" id="flexSwitchCheckChecked"
                                                    value="{{ $product->is_active }}"
                                                    {{ $product->is_active ? 'checked' : '' }}>
                                                <label for="is_active">Afficher
                                                    sur
                                                    la marketplace</label>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">modifier le
                                                    produit</button>
                                            </div>

                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <!-- Affichage de la pagination -->
                <div class="d-flex justify-content-center">
                    {{ $products->links() }} <!-- Liens de pagination -->
                </div>
            </div>
        @endif

        <h2>Commandes</h2>
        <ul>
            {{-- @foreach ($orders as $order)
                <li>Commande #{{ $order->id }} - Statut: {{ $order->status }}</li>
            @endforeach 
        </ul>

        <!-- Affichage de la pagination pour les commandes -->
        {{-- <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div> 

        <!-- Modal -->
        <div class="modal fade " id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Ajout produit</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('vendor.user.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Nom du produit</span>
                                <input type="text" class="form-control" name="name" aria-label="Username"
                                    aria-describedby="product name" required>
                            </div>
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="">Description du produits</label><br>
                                <textarea name="description" class="form-control" id="" cols="15" rows="3" required></textarea>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Prix</span>
                                <input type="number" name="price" id="price" class="form-control" required>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text">Quantité</span>
                                <input type="number" name="stock_quantity" id="stock_quantity" class="form-control"
                                    required>
                            </div>
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="inputGroupFile01">Image du produit</label>
                                <input type="file" name="image" id="image" class="form-control"
                                    id="inputGroupFile01">
                            </div>
                            <div class="input-group mb-3">
                                <select name="category_id" id="" class="form-control" required>
                                    <option value=""> ---Choisir Categorie---</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group mb-3 ml-4 form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" role="switch"
                                    id="flexSwitchCheckChecked" value="1" checked> <label for="is_active">Afficher
                                    sur
                                    la marketplace</label>
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter le produit</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

    @endsection --}}
@extends('layouts.slaves')

@section('title', 'Tableau de Bord Vendeur')

@section('content')
    <div class="container mt-5 pt-5">
        <!-- Page Header -->
        <div class="vendor-header text-center mb-5">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">{{ $vendor->store_name }}</h1>
            <p class="font-size-lg">Bienvenue sur votre tableau de bord</p>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Products Section -->
        <h2 class="mb-4">Vos Produits</h2>
        <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addProductModal">
            Ajouter un produit
        </button>
        @if ($products->isEmpty())
            <p class="text-muted">Aucun produit trouvé.</p>
        @else
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($products as $product)
                    <div class="col">
                        <div class="vendor-card card h-100">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-product.jpg') }}"
                                class="card-img-top" alt="{{ $product->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                                <h6 class="card-title">{{ number_format($product->price) }} FCFA</h6>
                            </div>
                            <div class="card-footer d-flex gap-2">
                                <button class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalDetail{{ $product->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalUpdate{{ $product->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('vendor.products.delete', $product->id) }}" method="POST"
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Modal -->
                    <div class="modal fade" id="modalDetail{{ $product->id }}" tabindex="-1"
                        aria-labelledby="detailLabel{{ $product->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="detailLabel{{ $product->id }}">Détails du produit
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Nom :</strong> {{ $product->name }}</p>
                                    <p><strong>Description :</strong> {{ $product->description }}</p>
                                    <p><strong>Prix :</strong> {{ number_format($product->price) }} FCFA</p>
                                    <p><strong>Quantité :</strong> {{ $product->stock_quantity }}</p>
                                    <p><strong>Catégorie :</strong> {{ $product->category->name }}</p>
                                    <p><strong>Statut :</strong> {{ $product->is_active ? 'Actif' : 'Inactif' }}</p>

                                    <p> <label class="input-group-text" for="inputGroupFile01">Image du
                                            produit</label>
                                        {{-- <input type="file" name="image" id="image" class="form-control"
                                            id="inputGroupFile01"> --}}
                                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-product.jpg') }}"
                                            class="card-img-top" />

                                        {{-- <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-product.jpg') }}"
                                        alt="{{ $product->name }}"> --}}
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Update Modal -->
                    <div class="modal fade" id="modalUpdate{{ $product->id }}" tabindex="-1"
                        aria-labelledby="updateLabel{{ $product->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="updateLabel{{ $product->id }}">Modifier le produit
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('vendor.products.update', $product->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label class="form-label">Nom du produit</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                name="name" value="{{ old('name', $product->name) }}">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Prix</label>
                                            <input type="number"
                                                class="form-control @error('price') is-invalid @enderror" name="price"
                                                value="{{ old('price', $product->price) }}">
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Quantité</label>
                                            <input type="number"
                                                class="form-control @error('stock_quantity') is-invalid @enderror"
                                                name="stock_quantity"
                                                value="{{ old('stock_quantity', $product->stock_quantity) }}">
                                            @error('stock_quantity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        {{-- <div class="input-group mb-3">
                                                <label class="input-group-text" for="inputGroupFile01">Image du
                                                    produit</label>
                                                <input type="file" name="image" id="image" class="form-control"
                                                    id="inputGroupFile01">
                                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-product.jpg') }}"
                                                    class="card-img-top" />
                                            </div> --}}
                                        <div class="mb-3">
                                            <label class="input-group-text" for="inputGroupFile01">Image du
                                                produit</label>
                                            <input type="file"
                                                class="form-control @error('image') is-invalid @enderror" name="image">
                                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-product.jpg') }}"
                                                class="card-img-top" />
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Catégorie</label>
                                            <select name="category_id"
                                                class="form-control @error('category_id') is-invalid @enderror">
                                                <option value="">--- Choisir une catégorie ---</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active"
                                                id="isActive{{ $product->id }}" value="1"
                                                {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="isActive{{ $product->id }}">Afficher
                                                sur la marketplace</label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Modifier le produit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        @endif

        <!-- Orders Section -->
        {{-- <h2 class="mt-5 mb-4">Vos Commandes</h2>
        @if ($orders->isEmpty())
            <p class="text-muted">Aucune commande trouvée.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th># Commande</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Montant</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>{{ $order->user->name ?? 'N/A' }}</td>
                                <td>{{ number_format($order->total_amount) }} FCFA</td>
                                <td>{{ $order->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        @endif --}}

        <!-- Add Product Modal -->
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addProductLabel">Ajouter un produit</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('vendor.user.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nom du produit</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Prix</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                    name="price" value="{{ old('price') }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Quantité</label>
                                <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                    name="stock_quantity" value="{{ old('stock_quantity') }}" required>
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Image du produit</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    name="image">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catégorie</label>
                                <select name="category_id"
                                    class="form-control @error('category_id') is-invalid @enderror" required>
                                    <option value="">--- Choisir une catégorie ---</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActiveAdd"
                                    value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActiveAdd">Afficher sur la marketplace</label>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">Ajouter le produit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
