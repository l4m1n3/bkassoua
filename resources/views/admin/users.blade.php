@extends('layouts.app_admin')

@section('content')
    <div class="col-10 mt-5">
        <h2 class="text-center mb-4">Liste des Utilisateurs Inscrits</h2>
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nom et Prénom</th>
                        <th scope="col">Email</th>
                        <th scope="col">Téléphone</th>
                        <th scope="col">Adresse</th>
                        <th scope="col">Rôle</th>
                        <th scope="col">Statut</th>
                        {{-- <th scope="col">Actions</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone_number ?? 'Non disponible' }}</td>
                            <td>{{ $user->address ?? 'Non disponible' }}</td>
                            <td>
                                <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td>
                                <span
                                    class="badge 
                                    {{ $user->status === 'pending' ? 'bg-warning' : '' }}
                                    {{ $user->status === 'active' ? 'bg-success' : '' }}
                                    {{ $user->status === 'suspended' ? 'bg-danger' : '' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            {{-- <td>
                                @if ($user->status == 'pending' || $user->status == 'suspended')
                                    <a href="#" class="btn btn-success btn-sm" title="Activer l'utilisateur">
                                        <i class="fas fa-user-check"></i>Activer
                                    </a>
                                @else
                                    <a href="#" class="btn btn-danger btn-sm" title="Suspendre l'utilisateur">
                                        <i class="fas fa-user-minus"></i>Suspendre
                                    </a>
                                @endif
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <hr class="my-5">

        <h2 class="text-center mb-4">Section Vendeurs</h2>
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nom et Prénom</th>
                        <th scope="col">Email</th>
                        <th scope="col">Téléphone</th>
                        <th scope="col">Adresse</th>
                        <th scope="col">Rôle</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vendors as $vendor)
                        <tr>
                            <td>{{ $vendor->user->id }}</td>
                            <td>{{ $vendor->user->name }}</td>
                            <td>{{ $vendor->user->email }}</td>
                            <td>{{ $vendor->user->phone_number ?? 'Non disponible' }}</td>
                            <td>{{ $vendor->user->address ?? 'Non disponible' }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($vendor->user->role) }}</span>
                            </td>
                            <td>
                                <span
                                    class="badge 
                                    {{ $vendor->status === 'inactive' ? 'bg-warning' : '' }}
                                    {{ $vendor->status === 'active' ? 'bg-success' : '' }}">
                                    {{ ucfirst($vendor->status) }}
                                </span>
                            </td>
                            <td>
                                @if ($vendor->status == 'inactive' || $vendor->status == 'inactive')
                                    <a href="{{ route('admin.changeVendorStatus', $vendor->id) }}"
                                        class="btn btn-success btn-sm" title="Activer le vendeur">
                                        <i class="fas fa-user-check"></i>Activer
                                    </a>
                                @else
                                    <a href="{{ route('admin.changeVendorStatus', $vendor->id) }}"
                                        class="btn btn-danger btn-sm" title="Suspendre le vendeur">
                                        <i class="fas fa-user-minus"></i>Suspendre
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
{{-- 
@extends('layouts.app_admin')

@section('content')
    <div class="container col-10 mt-4">
        <h2 class="text-center mb-4">Liste des utilisateurs inscrits</h2>
        <div class="row">
            @foreach ($users as $user)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-user-circle fa-3x text-secondary"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">{{ $user->name }}</h5>
                                    <p class="text-muted mb-0">{{ $user->email }}</p>
                                    <p class="text-muted mb-0"><i class="fas fa-phone-alt"></i>
                                        {{ $user->phone_number ?? 'No Phone' }}</p>
                                </div>
                            </div>
                            <hr>
                            <p><strong>Adresse :</strong> {{ $user->address ?? 'No Address' }}</p>
                            <p><strong>Role :</strong> <span class="badge bg-info">{{ ucfirst($user->role) }}</span></p>
                            <p><strong>Status :</strong>
                                <span
                                    class="badge {{ $user->status === 'active' ? 'bg-success' : ($user->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </p>
                            <div class="d-flex justify-content-between">
                                @if ($user->status == 'pending' || $user->status == 'suspended')
                                    <a href="#" class="btn btn-success btn-sm"><i class="fas fa-user-check"></i>
                                        Activer</a>
                                @else
                                    <a href="#" class="btn btn-danger btn-sm"><i class="fas fa-user-minus"></i>
                                        Suspendre</a>
                                @endif
                                <a href="#" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Modifier</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection --}}
