{{-- @extends('layouts.app_admin')

@section('content')
    <div class="col-9  mt-4 mb-4 ml-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h2 class="text-center">Section categories</h2>
        <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal"
        data-bs-target="#addCategoryModal">
            Ajouter une catégorie
        </button>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-sm">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Categorie</th>
                        <th scope="col">Slug</th>
                        <th scope="col">Image</th>
                        <th scope="col">#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $categorie)
                        <tr>
                            <td>{{ $categorie->id }}</td>
                            <td>{{ $categorie->name }}</td> <!-- Afficher le nom et prénom de l'utilisateur -->
                            <td>{{ $categorie->slug }}</td> <!-- Afficher l'email de l'utilisateur -->
                            <td><img src="{{ asset('storage/' . $categorie->image) }}" alt="categorie Image" width="50">
                            </td>
                            <td>
                                <a href="" class="btn btn-secondary" data-bs-toggle="modal"
                                    data-bs-target="#updateCategoryModal{{ $categorie->id }}"><i class="fa fa-pencil"></i></a>
                                <a href="{{ route('admin.categories.destroy', $categorie->id) }}" class="btn btn-danger"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                                    <span class="fa fa-trash"></span>
                                </a>
                            </td>
                        </tr>
                        <!-- Modal modifcation de catégorie -->
                        <div class="modal fade" id="updateCategoryModal{{ $categorie->id }}" tabindex="-1"
                            aria-labelledby="addCategoryModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addCategoryModalLabel{{ $categorie->id }}">Modifier une catégorie</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Formulaire d'modifcation de catégorie -->
                                        <form action="{{ route('admin.categories.update', $categorie->id ) }}" method="POST"
                                            id="addCategoryForm" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label for="nom" class="form-label">Nom de la catégorie</label>
                                                <input type="text" name="name" class="form-control" id="nom" value="{{ $categorie->name }}"
                                                    required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="nom" class="form-label">Image</label>
                                                <input type="file" name="image" class="form-control" id="image"
                                                    required value="{{$categorie->image}}">
                                                    <img src="{{ asset('storage/' . $categorie->image) }}" alt="categorie Image" width="300">

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Valider</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </tbody>
            </table>

            <!-- Modal d'ajout de catégorie -->
            <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addCategoryModalLabel">Ajouter une catégorie</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Formulaire d'ajout de catégorie -->
                            <form action="{{ route('admin.categories.store') }}" method="POST" id="addCategoryForm"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom de la catégorie</label>
                                    <input type="text" name="name" class="form-control" id="nom" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Image</label>
                                    <input type="file" name="image" class="form-control" id="image" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-primary">Ajouter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <hr>
    </div>
@endsection
  --}}
  @extends('layouts.app_admin')

@section('content')
    <div class="container mt-4 mb-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <button type="button" class="btn btn-primary mt-4 mb-4" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fa fa-plus"></i> Ajouter une catégorie
        </button>

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Slug</th>
                        <th scope="col">Image</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $categorie)
                        <tr>
                            <td>{{ $categorie->id }}</td>
                            <td>{{ $categorie->name }}</td>
                            <td>{{ $categorie->slug }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $categorie->image) }}" alt="Image catégorie" width="50" class="rounded shadow">
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateCategoryModal{{ $categorie->id }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.categories.destroy', $categorie->id) }}" class="btn btn-sm btn-danger"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Modal modification catégorie -->
                        <div class="modal fade" id="updateCategoryModal{{ $categorie->id }}" tabindex="-1" aria-labelledby="updateCategoryModalLabel{{ $categorie->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Modifier la catégorie</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('admin.categories.update', $categorie->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label for="name{{ $categorie->id }}" class="form-label">Nom de la catégorie</label>
                                                <input type="text" name="name" class="form-control" id="name{{ $categorie->id }}" value="{{ $categorie->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="image{{ $categorie->id }}" class="form-label">Image</label>
                                                <input type="file" name="image" class="form-control" id="image{{ $categorie->id }}">
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/' . $categorie->image) }}" alt="Image catégorie" width="100" class="rounded shadow">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Valider</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal ajout catégorie -->
        <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter une catégorie</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom de la catégorie</label>
                                <input type="text" name="name" class="form-control" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" class="form-control" id="image" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">Ajouter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
