{{-- @extends('layouts.app_admin')

@section('content')
    <div class="col-9">
        <h2 class="text-center">Section Produits</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-sm">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Vendeurs</th>
                        <th scope="col">Produit</th>
                        <th scope="col">Description</th>
                        <th scope="col">Prix</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Image</th>
                        <th scope="col">Categorie</th>
                        <th scope="col">Visible</th>
                        <th scope="col">#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->vendor ? $product->vendor->store_name : 'No Vendor' }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->stock_quantity }}</td>
                        <td><img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" width="50"></td>
                        <td>{{ $product->category ? $product->category->name : 'No Category' }}</td> <!-- Afficher la catégorie -->
                        <td>{{ $product->is_visible ? 'Oui' : 'Non' }}</td>
                        <td>
                            <a href="{{ route('products.edit', $product->id) }}">Edit</a>
                            <!-- Ajouter d'autres liens pour supprimer ou visualiser -->
                        </td>
                    </tr>
                @endforeach
                
                </tbody>
            </table>

            <!-- Lien de pagination -->
            <div class="d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection --}}
@extends('layouts.app_admin')

@section('content')
    <div class="col-10">
        <h2 class="text-center mb-4">Section Produits</h2>
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Vendeur</th>
                        <th scope="col">Produit</th>
                        <th scope="col">Description</th>
                        <th scope="col">Prix</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Image</th>
                        <th scope="col">Catégorie</th>
                        <th scope="col">Visible</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->vendor ? $product->vendor->store_name : 'Aucun vendeur' }}</td>
                            <td>{{ $product->name }}</td>
                            <td class="text-truncate" style="max-width: 150px;">{{ $product->description }}</td>
                            <td>{{ number_format($product->price, 2) }} FCFA</td>
                            <td>{{ $product->stock_quantity }}</td>
                            <td>
                                <img src="{{ asset('/storage/' . $product->image) }}" alt="Product Image" width="50"
                                    class="rounded shadow">
                            </td>
                            <td>{{ $product->category ? $product->category->name : 'Sans catégorie' }}</td>
                            <td>
                                <span class="badge {{ $product->is_visible ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->is_visible ? 'Oui' : 'Non' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
