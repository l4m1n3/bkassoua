@extends('layouts.slaves')

@section('title', 'Tableau de Bord Vendeur - ' . $vendor->store_name)

@section('content')
<div @class(['container-fluid', 'py-4'])>
    <!-- Header amélioré -->
    <div @class(['vendor-header', 'text-center', 'mb-5'])>
        <div @class(['vendor-avatar', 'mb-3'])>
            <div @class(['avatar-placeholder', 'bg-primary', 'text-white', 'rounded-circle', 'd-inline-flex', 'align-items-center', 'justify-content-center']) 
                 style="width: 80px; height: 80px; font-size: 2rem;">
                <i @class(['bi', 'bi-shop'])></i>
            </div>
        </div>
        <h1 @class(['vendor-title', 'mb-2'])>{{ $vendor->store_name }}</h1>
        <p @class(['vendor-subtitle', 'text-muted'])>Bienvenue sur votre tableau de bord</p>
        <div @class(['vendor-stats', 'd-flex', 'justify-content-center', 'gap-4', 'mt-4'])>
            <div @class(['stat-card', 'text-center'])>
                <div @class(['stat-number', 'text-primary'])>{{ $products->count() }}</div>
                <div @class(['stat-label'])>Produits</div>
            </div>
            <div @class(['stat-card', 'text-center'])>
                <div @class(['stat-number', 'text-success'])>{{ $totalSales ?? 0 }}</div>
                <div @class(['stat-label'])>Ventes</div>
            </div>
            <div @class(['stat-card', 'text-center'])>
                <div @class(['stat-number', 'text-warning'])>{{ $vendor->created_at->diffForHumans() }}</div>
                <div @class(['stat-label'])>Membre depuis</div>
            </div>
        </div>
    </div>

    <!-- Alertes améliorées -->
    @if (session('success'))
        <div @class(['alert', 'alert-success', 'alert-dismissible', 'fade', 'show', 'd-flex', 'align-items-center']) role="alert">
            <i @class(['bi', 'bi-check-circle-fill', 'me-2'])></i>
            <div>{{ session('success') }}</div>
            <button type="button" @class(['btn-close', 'ms-auto']) data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div @class(['alert', 'alert-danger', 'alert-dismissible', 'fade', 'show']) role="alert">
            <i @class(['bi', 'bi-exclamation-triangle-fill', 'me-2'])></i>
            <div>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
            <button type="button" @class(['btn-close', 'ms-auto']) data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div @class(['row'])>
        <!-- Sidebar de navigation -->
        <div @class(['col-lg-3', 'col-md-4', 'mb-4'])>
            <div @class(['vendor-sidebar'])>
                <div @class(['sidebar-section'])>
                    <h5 @class(['sidebar-title'])>
                        <i @class(['bi', 'bi-speedometer2', 'me-2'])></i>
                        Navigation
                    </h5>
                    <div @class(['nav-links'])>
                        <a href="#products" @class(['nav-link', 'active'])>
                            <i @class(['bi', 'bi-grid', 'me-2'])></i>
                            Mes Produits
                        </a>
                        <a href="#orders" @class(['nav-link'])>
                            <i @class(['bi', 'bi-receipt', 'me-2'])></i>
                            Commandes
                        </a>
                        <a href="#analytics" @class(['nav-link'])>
                            <i @class(['bi', 'bi-graph-up', 'me-2'])></i>
                            Analytics
                        </a>
                        <a href="#settings" @class(['nav-link'])>
                            <i @class(['bi', 'bi-gear', 'me-2'])></i>
                            Paramètres
                        </a>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div @class(['sidebar-section', 'mt-4'])>
                    <h5 @class(['sidebar-title'])>
                        <i @class(['bi', 'bi-lightning', 'me-2'])></i>
                        Actions Rapides
                    </h5>
                    <div @class(['quick-actions'])>
                        <button @class(['btn', 'btn-primary', 'w-100', 'mb-2']) data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i @class(['bi', 'bi-plus-circle', 'me-2'])></i>Nouveau Produit
                        </button>
                        <button @class(['btn', 'btn-outline-primary', 'w-100', 'mb-2'])>
                            <i @class(['bi', 'bi-eye', 'me-2'])></i>Voir Boutique
                        </button>
                        <button @class(['btn', 'btn-outline-secondary', 'w-100'])>
                            <i @class(['bi', 'bi-download', 'me-2'])></i>Exporter
                        </button>
                    </div>
                </div>

                <!-- Status de la boutique -->
                <div @class(['sidebar-section', 'mt-4'])>
                    <h5 @class(['sidebar-title'])>
                        <i @class(['bi', 'bi-info-circle', 'me-2'])></i>
                        Statut Boutique
                    </h5>
                    <div @class(['store-status'])>
                        <div @class(['status-item', 'd-flex', 'justify-content-between', 'align-items-center', 'mb-2'])>
                            <span>Statut:</span>
                            <span @class(['badge', 'bg-success'])>Active</span>
                        </div>
                        <div @class(['status-item', 'd-flex', 'justify-content-between', 'align-items-center', 'mb-2'])>
                            <span>Produits actifs:</span>
                            <span @class(['fw-semibold'])>{{ $products->where('is_active', true)->count() }}</span>
                        </div>
                        <div @class(['status-item', 'd-flex', 'justify-content-between', 'align-items-center'])>
                            <span>Note moyenne:</span>
                            <div @class(['stars', 'small'])>
                                <i @class(['bi', 'bi-star-fill', 'text-warning'])></i>
                                <i @class(['bi', 'bi-star-fill', 'text-warning'])></i>
                                <i @class(['bi', 'bi-star-fill', 'text-warning'])></i>
                                <i @class(['bi', 'bi-star-fill', 'text-warning'])></i>
                                <i @class(['bi', 'bi-star-half', 'text-warning'])></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div @class(['col-lg-9', 'col-md-8'])>
            <!-- Section Produits -->
            <section id="products" @class(['vendor-section'])>
                <div @class(['section-header', 'd-flex', 'justify-content-between', 'align-items-center', 'mb-4'])>
                    <div>
                        <h2 @class(['section-title', 'mb-1'])>Mes Produits</h2>
                        <p @class(['section-subtitle', 'text-muted'])>Gérez votre inventaire</p>
                    </div>
                    <button type="button" @class(['btn', 'btn-primary']) data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i @class(['bi', 'bi-plus-circle', 'me-2'])></i>Ajouter un produit
                    </button>
                </div>

                @if ($products->isEmpty())
                    <div @class(['empty-state', 'text-center', 'py-5'])>
                        <i @class(['bi', 'bi-inbox', 'display-1', 'text-muted'])></i>
                        <h4 @class(['mt-3', 'text-muted'])>Aucun produit trouvé</h4>
                        <p @class(['text-muted'])>Commencez par ajouter votre premier produit à votre boutique.</p>
                        <button @class(['btn', 'btn-primary', 'mt-3']) data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i @class(['bi', 'bi-plus-circle', 'me-2'])></i>Ajouter mon premier produit
                        </button>
                    </div>
                @else
                    <div @class(['products-grid'])>
                        @foreach ($products as $product)
                            <div @class(['product-card', 'vendor-product-card'])>
                                <div @class(['product-image'])>
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-product.jpg') }}"
                                         alt="{{ $product->name }}">
                                    <div @class(['product-status'])>
                                       <span @class(['badge', 'bg-success' => $product->is_active, 'bg-secondary' => ! $product->is_active])>
                                            {{ $product->is_active ? 'Actif' : 'Inactif' }}
                                        </span> </div>
                                    <div @class(['product-actions'])>
                                        <button @class(['action-btn']) data-bs-toggle="modal" data-bs-target="#modalDetail{{ $product->id }}" title="Voir détails">
                                            <i @class(['bi', 'bi-eye'])></i>
                                        </button>
                                        <button @class(['action-btn']) data-bs-toggle="modal" data-bs-target="#modalUpdate{{ $product->id }}" title="Modifier">
                                            <i @class(['bi', 'bi-pencil'])></i>
                                        </button>
                                    </div>
                                </div>
                                <div @class(['product-info'])>
                                    <h3 @class(['product-title'])>{{ $product->name }}</h3>
                                    <p @class(['product-description', 'text-muted'])>{{ Str::limit($product->description, 80) }}</p>
                                    <div @class(['product-meta', 'd-flex', 'justify-content-between', 'align-items-center'])>
                                        <div @class(['product-price', 'text-primary', 'fw-bold'])>
                                            {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                        </div>
                                        <div @class(['product-stock', 'text-muted'])>
                                            <i @class(['bi', 'bi-box', 'me-1'])></i>{{ $product->stock_quantity }}
                                        </div>
                                    </div>
                                    <div @class(['product-footer', 'mt-3'])>
                                        <form action="{{ route('vendor.products.delete', $product->id) }}" method="POST" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');" @class(['d-inline'])>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" @class(['btn', 'btn-outline-danger', 'btn-sm'])>
                                                <i @class(['bi', 'bi-trash', 'me-1'])></i>Supprimer
                                            </button>
                                        </form>
                                        <div @class(['product-category', 'small', 'text-muted'])>
                                            {{ $product->category->name ?? 'Non catégorisé' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Détails Produit -->
                            <div @class(['modal', 'fade']) id="modalDetail{{ $product->id }}" tabindex="-1"
                                aria-labelledby="detailLabel{{ $product->id }}" aria-hidden="true">
                                <div @class(['modal-dialog', 'modal-lg'])>
                                    <div @class(['modal-content'])>
                                        <div @class(['modal-header'])>
                                            <h5 @class(['modal-title']) id="detailLabel{{ $product->id }}">
                                                <i @class(['bi', 'bi-info-circle', 'me-2'])></i>Détails du produit
                                            </h5>
                                            <button type="button" @class(['btn-close']) data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div @class(['modal-body'])>
                                            <div @class(['row'])>
                                                <div @class(['col-md-6'])>
                                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-product.jpg') }}"
                                                         alt="{{ $product->name }}" @class(['img-fluid', 'rounded'])>
                                                </div>
                                                <div @class(['col-md-6'])>
                                                    <h4>{{ $product->name }}</h4>
                                                    <p @class(['text-muted'])>{{ $product->description }}</p>
                                                    <div @class(['product-details'])>
                                                        <div @class(['detail-item'])>
                                                            <strong>Prix:</strong> 
                                                            <span @class(['text-primary'])>{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
                                                        </div>
                                                        <div @class(['detail-item'])>
                                                            <strong>Stock:</strong> 
                                                            <span @class(['text-success' => $product->stock_quantity > 0, 'text-danger' => $product->stock_quantity <= 0])>
                                                                {{ $product->stock_quantity }} unités
                                                            </span>
                                                        </div>
                                                        <div @class(['detail-item'])>
                                                            <strong>Catégorie:</strong> 
                                                            <span @class(['badge', 'bg-light', 'text-dark'])>{{ $product->category->name ?? 'N/A' }}</span>
                                                        </div>
                                                        <div @class(['detail-item'])>
                                                            <strong>Statut:</strong> 
                                                          <span @class(['badge', $product->is_active ? 'bg-success' : 'bg-secondary'])>
                                                                {{ $product->is_active ? 'Actif' : 'Inactif' }}
                                                            </span>
                                                        </div>
                                                        <div @class(['detail-item'])>
                                                            <strong>Créé le:</strong> 
                                                            <span>{{ $product->created_at->format('d/m/Y') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div @class(['modal-footer'])>
                                            <button type="button" @class(['btn', 'btn-secondary']) data-bs-dismiss="modal">Fermer</button>
                                            <button type="button" @class(['btn', 'btn-primary']) data-bs-toggle="modal" data-bs-target="#modalUpdate{{ $product->id }}">
                                                <i @class(['bi', 'bi-pencil', 'me-1'])></i>Modifier
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Modification Produit -->
                            {{-- <div @class(['modal', 'fade']) id="modalUpdate{{ $product->id }}" tabindex="-1"
                                aria-labelledby="updateLabel{{ $product->id }}" aria-hidden="true">
                                <div @class(['modal-dialog', 'modal-lg'])>
                                    <div @class(['modal-content'])>
                                        <div @class(['modal-header'])>
                                            <h5 @class(['modal-title']) id="updateLabel{{ $product->id }}">
                                                <i @class(['bi', 'bi-pencil', 'me-2'])></i>Modifier le produit
                                            </h5>
                                            <button type="button" @class(['btn-close']) data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div @class(['modal-body'])>
                                            <form action="{{ route('vendor.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div @class(['row'])>
                                                    <div @class(['col-md-6'])>
                                                        <div @class(['mb-3'])>
                                                            <label @class(['form-label', 'fw-semibold'])>Nom du produit</label>
                                                           <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')]) 
                                                                name="name" 
                                                                value="{{ old('name', $product->name) }}" 
                                                                required>
                                                            @error('name')
                                                                <div @class(['invalid-feedback'])>{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div @class(['mb-3'])>
                                                            <label @class(['form-label', 'fw-semibold'])>Description</label>
                                                            <textarea @class(['form-control', '@error('description')', 'is-invalid', '@enderror']) name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                                                            @error('description')
                                                                <div @class(['invalid-feedback'])>{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div @class(['mb-3'])>
                                                            <label @class(['form-label', 'fw-semibold'])>Prix (FCFA)</label>
                                                            <input type="number" @class(['form-control', '@error('price')', 'is-invalid', '@enderror'])
                                                                name="price" value="{{ old('price', $product->price) }}" required>
                                                            @error('price')
                                                                <div @class(['invalid-feedback'])>{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div @class(['col-md-6'])>
                                                        <div @class(['mb-3'])>
                                                            <label @class(['form-label', 'fw-semibold'])>Quantité en stock</label>
                                                            <input type="number" @class(['form-control', '@error('stock_quantity')', 'is-invalid', '@enderror'])
                                                                name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                                                            @error('stock_quantity')
                                                                <div @class(['invalid-feedback'])>{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div @class(['mb-3'])>
                                                            <label @class(['form-label', 'fw-semibold'])>Image du produit</label>
                                                            <input type="file" @class(['form-control', '@error('image')', 'is-invalid', '@enderror']) name="image">
                                                            @error('image')
                                                                <div @class(['invalid-feedback'])>{{ $message }}</div>
                                                            @enderror
                                                            <div @class(['mt-2'])>
                                                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-product.jpg') }}"
                                                                    @class(['img-thumbnail']) style="max-height: 100px;">
                                                            </div>
                                                        </div>
                                                        <div @class(['mb-3'])>
                                                            <label @class(['form-label', 'fw-semibold'])>Catégorie</label>
                                                            <select name="category_id" @class(['form-control', '@error('category_id')', 'is-invalid', '@enderror']) required>
                                                                <option value="">--- Choisir une catégorie ---</option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}"
                                                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                                        {{ $category->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('category_id')
                                                                <div @class(['invalid-feedback'])>{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div @class(['mb-3', 'form-check', 'form-switch'])>
                                                            <input @class(['form-check-input']) type="checkbox" name="is_active"
                                                                id="isActive{{ $product->id }}" value="1"
                                                                {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                                            <label @class(['form-check-label']) for="isActive{{ $product->id }}">Produit actif</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div @class(['modal-footer'])>
                                                    <button type="button" @class(['btn', 'btn-secondary']) data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" @class(['btn', 'btn-primary'])>
                                                        <i @class(['bi', 'bi-check-lg', 'me-1'])></i>Modifier le produit
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            <div @class(['modal', 'fade']) id="modalUpdate{{ $product->id }}" tabindex="-1"
     aria-labelledby="updateLabel{{ $product->id }}" aria-hidden="true">
    <div @class(['modal-dialog', 'modal-lg'])>
        <div @class(['modal-content'])>
            <div @class(['modal-header'])>
                <h5 @class(['modal-title']) id="updateLabel{{ $product->id }}">
                    <i @class(['bi', 'bi-pencil', 'me-2'])></i>Modifier le produit
                </h5>
                <button type="button" @class(['btn-close']) data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div @class(['modal-body'])>
                <form action="{{ route('vendor.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div @class(['row'])>
                        <div @class(['col-md-6'])>
                            <div @class(['mb-3'])>
                                <label @class(['form-label', 'fw-semibold'])>Nom du produit</label>
                                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')]) 
                                       name="name" 
                                       value="{{ old('name', $product->name) }}" 
                                       required>
                                @error('name')
                                    <div @class(['invalid-feedback'])>{{ $message }}</div>
                                @enderror
                            </div>
                            <div @class(['mb-3'])>
                                <label @class(['form-label', 'fw-semibold'])>Description</label>
                                <textarea @class(['form-control', 'is-invalid' => $errors->has('description')]) 
                                          name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div @class(['invalid-feedback'])>{{ $message }}</div>
                                @enderror
                            </div>
                            <div @class(['mb-3'])>
                                <label @class(['form-label', 'fw-semibold'])>Prix (FCFA)</label>
                                <input type="number" @class(['form-control', 'is-invalid' => $errors->has('price')])
                                       name="price" value="{{ old('price', $product->price) }}" required>
                                @error('price')
                                    <div @class(['invalid-feedback'])>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div @class(['col-md-6'])>
                            <div @class(['mb-3'])>
                                <label @class(['form-label', 'fw-semibold'])>Quantité en stock</label>
                                <input type="number" @class(['form-control', 'is-invalid' => $errors->has('stock_quantity')])
                                       name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                                @error('stock_quantity')
                                    <div @class(['invalid-feedback'])>{{ $message }}</div>
                                @enderror
                            </div>
                            <div @class(['mb-3'])>
                                <label @class(['form-label', 'fw-semibold'])>Image du produit</label>
                                <input type="file" @class(['form-control', 'is-invalid' => $errors->has('image')]) name="image">
                                @error('image')
                                    <div @class(['invalid-feedback'])>{{ $message }}</div>
                                @enderror
                                <div @class(['mt-2'])>
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-product.jpg') }}"
                                         @class(['img-thumbnail']) style="max-height: 100px;">
                                </div>
                            </div>
                            <div @class(['mb-3'])>
                                <label @class(['form-label', 'fw-semibold'])>Catégorie</label>
                                <select name="category_id" @class(['form-control', 'is-invalid' => $errors->has('category_id')]) required>
                                    <option value="">--- Choisir une catégorie ---</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div @class(['invalid-feedback'])>{{ $message }}</div>
                                @enderror
                            </div>
                            <div @class(['mb-3', 'form-check', 'form-switch'])>
                                <input @class(['form-check-input']) type="checkbox" name="is_active"
                                       id="isActive{{ $product->id }}" value="1"
                                       {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <label @class(['form-check-label']) for="isActive{{ $product->id }}">Produit actif</label>
                            </div>
                        </div>
                    </div>
                    <div @class(['modal-footer'])>
                        <button type="button" @class(['btn', 'btn-secondary']) data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" @class(['btn', 'btn-primary'])>
                            <i @class(['bi', 'bi-check-lg', 'me-1'])></i>Modifier le produit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div @class(['d-flex', 'justify-content-center', 'mt-5'])>
                        {{ $products->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>

<!-- Modal Ajout Produit -->
{{-- <div @class(['modal', 'fade']) id="addProductModal" tabindex="-1" aria-labelledby="addProductLabel" aria-hidden="true">
    <div @class(['modal-dialog', 'modal-lg'])>
        <div @class(['modal-content'])>
            <div @class(['modal-header'])>
                <h5 @class(['modal-title']) id="addProductLabel">
                    <i @class(['bi', 'bi-plus-circle', 'me-2'])></i>Ajouter un produit
                </h5>
                <button type="button" @class(['btn-close']) data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div @class(['modal-body'])>
                <form action="{{ route('vendor.user.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div @class(['row'])>
                        <div @class(['col-md-6'])>
                            <div @class(['mb-3'])>
                                <label @class(['form-label', 'fw-semibold'])>Nom du produit</label>
                                <input type="text" @class(['form-control', '@error('name')', 'is-invalid', '@enderror'])
                                    name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div @class(['invalid-feedback'])>{{ $message }}</div>
                                @enderror
                            </div>
                            <div @class(['mb-3'])>
                                <label @class(['form-label', 'fw-semibold'])>Description</label>
                                <textarea @class(['form-control', '@error('description')', 'is-invalid', '@enderror']) name="description" rows="4" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div @class(['invalid-feedback'])>{{ $message }}</div>
                                @enderror
                            </div>
                            <div @class(['mb-3'])>
                                <label @class(['form-label', 'fw-semibold'])>Prix (FCFA)</label>
                                <input type="number" @class(['form-control', '@error('price')', 'is-invalid', '@enderror'])
                                    name="price" value="{{ old('price') }}" required>
                                @error('price')
                                    <div @class(['invalid-feedback'])>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div @class(['col-md-6'])>
                            <div @class(['mb-3'])>
                                <label @class(['form-label', 'fw-semibold'])>Quantité en stock</label>
                                <input type="number" @class(['form-control', '@error('stock_quantity')', 'is-invalid', '@enderror'])
                                    name="stock_quantity" value="{{ old('stock_quantity') }}" required>
                                @error('stock_quantity')
                                    <div @class(['invalid-feedback'])>{{ $message }}</div>
                                @enderror
                            </div>
                            <div @class(['mb-3'])>
                                <label @class(['form-label', 'fw-semibold'])>Image du produit</label>
                                <input type="file" @class(['form-control', '@error('image')', 'is-invalid', '@enderror']) name="image">
                                @error('image')
                                    <div @class(['invalid-feedback'])>{{ $message }}</div>
                                @enderror
                            </div>
                            <div @class(['mb-3'])>
                                <label @class(['form-label', 'fw-semibold'])>Catégorie</label>
                                <select name="category_id" @class(['form-control', '@error('category_id')', 'is-invalid', '@enderror']) required>
                                    <option value="">--- Choisir une catégorie ---</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div @class(['invalid-feedback'])>{{ $message }}</div>
                                @enderror
                            </div>
                            <div @class(['mb-3', 'form-check', 'form-switch'])>
                                <input @class(['form-check-input']) type="checkbox" name="is_active" id="isActiveAdd"
                                    value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                <label @class(['form-check-label']) for="isActiveAdd">Produit actif</label>
                            </div>
                        </div>
                    </div>
                    <div @class(['modal-footer'])>
                        <button type="button" @class(['btn', 'btn-secondary']) data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" @class(['btn', 'btn-primary'])>
                            <i @class(['bi', 'bi-plus-circle', 'me-1'])></i>Ajouter le produit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}
<div @class(['modal', 'fade']) id="addProductModal" tabindex="-1" aria-labelledby="addProductLabel" aria-hidden="true">
    <div @class(['modal-dialog', 'modal-lg'])>
        <div @class(['modal-content'])>
            <div @class(['modal-header'])>
                <h5 @class(['modal-title']) id="addProductLabel">
                    <i @class(['bi', 'bi-plus-circle', 'me-2'])></i>Ajouter un produit
                </h5>
                <button type="button" @class(['btn-close']) data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div @class(['modal-body'])>
                <form action="{{ route('vendor.user.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div @class(['row'])>
                        <div @class(['col-md-6'])>
                            <div @class(['mb-3'])>
                                <label @class(['form-label', 'fw-semibold'])>Nom du produit</label>
                                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')])
                                       name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div @class(['invalid-feedback'])>{{ $message }}</div>
                                @enderror
                            </div>
                            <div @class(['mb-3'])>
                                <label @class(['form-label', 'fw-semibold'])>Description</label>
                                <textarea @class(['form-control', 'is-invalid' => $errors->has('description')]) 
                                          name="description" rows="4" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div @class(['invalid-feedback'])>{{ $message }}</div>
                                @enderror
                            </div>
                            <div @class(['mb-3'])>
                                <label @class(['form-label', 'fw-semibold'])>Prix (FCFA)</label>
                                <input type="number" @class(['form-control', 'is-invalid' => $errors->has('price')])
                                       name="price" value="{{ old('price') }}" required>
                                @error('price')
                                    <div @class(['invalid-feedback'])>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div @class(['col-md-6'])>
                            <div @class(['mb-3'])>
                                <label @class(['form-label', 'fw-semibold'])>Quantité en stock</label>
                                <input type="number" @class(['form-control', 'is-invalid' => $errors->has('stock_quantity')])
                                       name="stock_quantity" value="{{ old('stock_quantity') }}" required>
                                @error('stock_quantity')
                                    <div @class(['invalid-feedback'])>{{ $message }}</div>
                                @enderror
                            </div>
                            <div @class(['mb-3'])>
                                <label @class(['form-label', 'fw-semibold'])>Image du produit</label>
                                <input type="file" @class(['form-control', 'is-invalid' => $errors->has('image')]) name="image">
                                @error('image')
                                    <div @class(['invalid-feedback'])>{{ $message }}</div>
                                @enderror
                            </div>
                            <div @class(['mb-3'])>
                                <label @class(['form-label', 'fw-semibold'])>Catégorie</label>
                                <select name="category_id" @class(['form-control', 'is-invalid' => $errors->has('category_id')]) required>
                                    <option value="">--- Choisir une catégorie ---</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div @class(['invalid-feedback'])>{{ $message }}</div>
                                @enderror
                            </div>
                            <div @class(['mb-3', 'form-check', 'form-switch'])>
                                <input @class(['form-check-input']) type="checkbox" name="is_active" id="isActiveAdd"
                                       value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                <label @class(['form-check-label']) for="isActiveAdd">Produit actif</label>
                            </div>
                        </div>
                    </div>
                    <div @class(['modal-footer'])>
                        <button type="button" @class(['btn', 'btn-secondary']) data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" @class(['btn', 'btn-primary'])>
                            <i @class(['bi', 'bi-plus-circle', 'me-1'])></i>Ajouter le produit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
/* Styles spécifiques au tableau de bord vendeur */
.vendor-header {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    padding: 3rem 2rem;
    border-radius: var(--border-radius);
    margin-bottom: 2rem;
}

.vendor-title {
    font-weight: 700;
    font-size: 2.5rem;
}

.vendor-subtitle {
    opacity: 0.9;
    font-size: 1.1rem;
}

.vendor-stats {
    margin-top: 2rem;
}

.stat-card {
    padding: 1rem 2rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
    margin-top: 0.5rem;
}

.vendor-sidebar {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--shadow);
    position: sticky;
    top: 100px;
}

.sidebar-section {
    margin-bottom: 2rem;
}

.sidebar-section:last-child {
    margin-bottom: 0;
}

.sidebar-title {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 1rem;
    font-size: 1rem;
}

.nav-links .nav-link {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    color: var(--dark);
    text-decoration: none;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    transition: var(--transition);
}

.nav-links .nav-link:hover,
.nav-links .nav-link.active {
    background: var(--primary-light);
    color: var(--primary);
}

.quick-actions .btn {
    margin-bottom: 0.5rem;
}

.store-status .status-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--gray-light);
}

.store-status .status-item:last-child {
    border-bottom: none;
}

.vendor-section {
    margin-bottom: 3rem;
}

.section-header {
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--gray-light);
}

.section-title {
    font-weight: 700;
    color: var(--dark);
}

.section-subtitle {
    font-size: 0.9rem;
}

.vendor-product-card {
    background: white;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
    height: 100%;
}

.vendor-product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.vendor-product-card .product-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.vendor-product-card .product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.vendor-product-card:hover .product-image img {
    transform: scale(1.05);
}

.vendor-product-card .product-status {
    position: absolute;
    top: 10px;
    left: 10px;
}

.vendor-product-card .product-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    flex-direction: column;
    gap: 5px;
    opacity: 0;
    transform: translateX(10px);
    transition: var(--transition);
}

.vendor-product-card:hover .product-actions {
    opacity: 1;
    transform: translateX(0);
}

.vendor-product-card .action-btn {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--dark);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: var(--transition);
}

.vendor-product-card .action-btn:hover {
    background: var(--primary);
    color: white;
    transform: scale(1.1);
}

.vendor-product-card .product-info {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    height: calc(100% - 200px);
}

.vendor-product-card .product-title {
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.vendor-product-card .product-description {
    font-size: 0.9rem;
    flex-grow: 1;
    margin-bottom: 1rem;
}

.vendor-product-card .product-meta {
    margin-bottom: 1rem;
}

.vendor-product-card .product-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
}

.empty-state {
    background: white;
    border-radius: var(--border-radius);
    padding: 3rem 2rem;
    box-shadow: var(--shadow);
}

.product-details .detail-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--gray-light);
}

.product-details .detail-item:last-child {
    border-bottom: none;
}

/* Responsive */
@media (max-width: 768px) {
    .vendor-header {
        padding: 2rem 1rem;
    }
    
    .vendor-title {
        font-size: 2rem;
    }
    
    .vendor-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .stat-card {
        padding: 0.5rem 1rem;
    }
    
    .vendor-product-card .product-footer {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .vendor-product-card .product-footer .btn {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .vendor-title {
        font-size: 1.5rem;
    }
    
    .section-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .products-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des statistiques
    function animateStats() {
        const stats = document.querySelectorAll('.stat-number');
        stats.forEach(stat => {
            const target = parseInt(stat.textContent) || 0;
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    stat.textContent = target;
                    clearInterval(timer);
                } else {
                    stat.textContent = Math.floor(current);
                }
            }, 40);
        });
    }

    // Observer pour l'animation des statistiques
    const headerObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateStats();
                headerObserver.unobserve(entry.target);
            }
        });
    });

    const vendorHeader = document.querySelector('.vendor-header');
    if (vendorHeader) {
        headerObserver.observe(vendorHeader);
    }

    // Gestion de la navigation dans la sidebar
    document.querySelectorAll('.nav-links .nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.nav-links .nav-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                targetSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Animation des cartes produits
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

    document.querySelectorAll('.vendor-product-card').forEach(card => {
        observer.observe(card);
    });

    // Gestion des modales
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('shown.bs.modal', function() {
            const inputs = this.querySelectorAll('input, textarea, select');
            if (inputs.length > 0) {
                inputs[0].focus();
            }
        });
    });
});
</script>
@endsection