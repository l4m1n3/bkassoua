@extends('layouts.app_admin')

@section('title', 'Gestion des Utilisateurs - Admin Bkassoua')

@section('content')
<div class="users-container">
    <!-- En-tête de la page -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-text">
                <h1 class="page-title">Gestion des Utilisateurs</h1>
                <p class="page-subtitle">Gérez les utilisateurs et vendeurs de votre plateforme</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-outline-primary" onclick="exportUsers()">
                    <i class="bi bi-download me-2"></i>Exporter
                </button>
                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filtersModal">
                    <i class="bi bi-funnel me-2"></i>Filtres avancés
                </button>
            </div>
        </div>
    </div>

    <!-- Filtres rapides -->
    <div class="filters-section mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" class="form-control" placeholder="Rechercher un utilisateur..." id="searchInput" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="roleFilter" onchange="applyFilters()">
                    <option value="">Tous les rôles</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Utilisateur</option>
                    <option value="vendor" {{ request('role') == 'vendor' ? 'selected' : '' }}>Vendeur</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="statusFilter" onchange="applyFilters()">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspendu</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                    <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser
                </button>
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon bg-primary">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
                <div class="stat-label">Total utilisateurs</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-success">
                <i class="bi bi-person-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $activeUsers ?? 0 }}</div>
                <div class="stat-label">Utilisateurs actifs</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-info">
                <i class="bi bi-shop"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalVendors ?? 0 }}</div>
                <div class="stat-label">Vendeurs</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-warning">
                <i class="bi bi-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $pendingUsers ?? 0 }}</div>
                <div class="stat-label">En attente</div>
            </div>
        </div>
    </div>

    <!-- Navigation par onglets -->
    <div class="tabs-section mb-4">
        <ul class="nav nav-tabs" id="usersTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                    <i class="bi bi-people me-2"></i>Utilisateurs
                    {{-- <span class="badge bg-primary ms-2">{{ $users->total() }}</span> --}}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="vendors-tab" data-bs-toggle="tab" data-bs-target="#vendors" type="button" role="tab">
                    <i class="bi bi-shop me-2"></i>Vendeurs
                    {{-- <span class="badge bg-info ms-2">{{ $vendors->total() }}</span> --}}
                </button>
            </li>
        </ul>
    </div>

    <!-- Contenu des onglets -->
    <div class="tab-content" id="usersTabsContent">
        <!-- Onglet Utilisateurs -->
        <div class="tab-pane fade show active" id="users" role="tabpanel">
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-people me-2"></i>Liste des utilisateurs
                    </h5>
                    <div class="card-actions">
                        {{-- <span class="text-muted">{{ $users->total() }} utilisateur(s) trouvé(s)</span> --}}
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($users->isEmpty())
                        <div class="empty-state text-center py-5">
                            <i class="bi bi-person-x display-1 text-muted"></i>
                            <h4 class="mt-3 text-muted">Aucun utilisateur trouvé</h4>
                            <p class="text-muted mb-4">Aucun utilisateur ne correspond à vos critères de recherche.</p>
                            <button class="btn btn-primary" onclick="resetFilters()">
                                <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser les filtres
                            </button>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th width="50">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAllUsers">
                                            </div>
                                        </th>
                                        <th>Utilisateur</th>
                                        <th>Contact</th>
                                        <th>Rôle</th>
                                        <th>Statut</th>
                                        <th>Inscription</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                    <tr class="user-row" data-user-id="{{ $user->id }}">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input user-checkbox" type="checkbox" value="{{ $user->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-name">{{ $user->name }}</div>
                                                <small class="text-muted">ID: {{ $user->id }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="contact-info">
                                                <div class="email">{{ $user->email }}</div>
                                                <small class="text-muted">{{ $user->phone_number ?? 'Téléphone non renseigné' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="role-badge role-{{ $user->role }}">
                                                <i class="bi bi-{{ $user->role === 'admin' ? 'shield' : ($user->role === 'vendor' ? 'shop' : 'person') }} me-1"></i>
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ $user->status }}">
                                                <i class="bi bi-circle-fill me-1"></i>
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="date-info">
                                                <div>{{ $user->created_at->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ $user->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#userDetailModal{{ $user->id }}"
                                                        title="Voir les détails">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                
                                                @if($user->status == 'pending' || $user->status == 'suspended')
                                                    <button class="btn btn-sm btn-success" 
                                                            onclick="activateUser({{ $user->id }})"
                                                            title="Activer l'utilisateur">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-warning" 
                                                            onclick="suspendUser({{ $user->id }})"
                                                            title="Suspendre l'utilisateur">
                                                        <i class="bi bi-pause"></i>
                                                    </button>
                                                @endif

                                                <button class="btn btn-sm btn-danger" 
                                                        onclick="deleteUser({{ $user->id }})"
                                                        title="Supprimer l'utilisateur">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Pagination Utilisateurs -->
                {{-- @if($users->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Affichage de {{ $users->firstItem() }} à {{ $users->lastItem() }} sur {{ $users->total() }} utilisateurs
                        </div>
                        <nav>
                            {{ $users->links() }}
                        </nav>
                    </div>
                </div>
                @endif --}}
            </div>
        </div>

        <!-- Onglet Vendeurs -->
        <div class="tab-pane fade" id="vendors" role="tabpanel">
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-shop me-2"></i>Liste des vendeurs
                    </h5>
                    <div class="card-actions">
                        <span class="text-muted">{{ $vendors->total() }} vendeur(s) trouvé(s)</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($vendors->isEmpty())
                        <div class="empty-state text-center py-5">
                            <i class="bi bi-shop display-1 text-muted"></i>
                            <h4 class="mt-3 text-muted">Aucun vendeur trouvé</h4>
                            <p class="text-muted mb-4">Aucun vendeur ne correspond à vos critères de recherche.</p>
                            <button class="btn btn-primary" onclick="resetFilters()">
                                <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser les filtres
                            </button>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th width="50">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAllVendors">
                                            </div>
                                        </th>
                                        <th>Vendeur</th>
                                        <th>Boutique</th>
                                        <th>Contact</th>
                                        <th>Statut</th>
                                        <th>Inscription</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vendors as $vendor)
                                    <tr class="vendor-row" data-vendor-id="{{ $vendor->id }}">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input vendor-checkbox" type="checkbox" value="{{ $vendor->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-name">{{ $vendor->user->name }}</div>
                                                <small class="text-muted">ID: {{ $vendor->user->id }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="store-info">
                                                <div class="store-name">{{ $vendor->store_name ?? 'Sans nom' }}</div>
                                                <small class="text-muted">{{ $vendor->products_count ?? 0 }} produits</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="contact-info">
                                                <div class="email">{{ $vendor->user->email }}</div>
                                                <small class="text-muted">{{ $vendor->user->phone_number ?? 'Téléphone non renseigné' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-badge status-{{ $vendor->status }}">
                                                <i class="bi bi-circle-fill me-1"></i>
                                                {{ ucfirst($vendor->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="date-info">
                                                <div>{{ $vendor->created_at->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ $vendor->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#vendorDetailModal{{ $vendor->id }}"
                                                        title="Voir les détails">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                
                                                @if($vendor->status == 'inactive')
                                                    <a href="{{ route('admin.changeVendorStatus', $vendor->id) }}" 
                                                       class="btn btn-sm btn-success" 
                                                       title="Activer le vendeur">
                                                        <i class="bi bi-check-lg"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('admin.changeVendorStatus', $vendor->id) }}" 
                                                       class="btn btn-sm btn-warning" 
                                                       title="Désactiver le vendeur">
                                                        <i class="bi bi-pause"></i>
                                                    </a>
                                                @endif

                                                <button class="btn btn-sm btn-info" 
                                                        onclick="viewVendorProducts({{ $vendor->id }})"
                                                        title="Voir les produits">
                                                    <i class="bi bi-box-seam"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Pagination Vendeurs -->
                @if($vendors->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Affichage de {{ $vendors->firstItem() }} à {{ $vendors->lastItem() }} sur {{ $vendors->total() }} vendeurs
                        </div>
                        <nav>
                            {{ $vendors->links() }}
                        </nav>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modals pour les détails des utilisateurs -->
@foreach ($users as $user)
<div class="modal fade" id="userDetailModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-person me-2"></i>Détails de l'utilisateur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-section">
                            <h6 class="section-title">Informations personnelles</h6>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Nom complet</label>
                                    <div>{{ $user->name }}</div>
                                </div>
                                <div class="info-item">
                                    <label>Email</label>
                                    <div>{{ $user->email }}</div>
                                </div>
                                <div class="info-item">
                                    <label>Téléphone</label>
                                    <div>{{ $user->phone_number ?? 'Non renseigné' }}</div>
                                </div>
                                <div class="info-item">
                                    <label>Adresse</label>
                                    <div>{{ $user->address ?? 'Non renseignée' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-section">
                            <h6 class="section-title">Informations compte</h6>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Rôle</label>
                                    <div>
                                        <span class="role-badge role-{{ $user->role }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <label>Statut</label>
                                    <div>
                                        <span class="status-badge status-{{ $user->status }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <label>Date d'inscription</label>
                                    <div>{{ $user->created_at->format('d/m/Y à H:i') }}</div>
                                </div>
                                <div class="info-item">
                                    <label>Dernière connexion</label>
                                    <div>{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Jamais' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                @if($user->status == 'pending' || $user->status == 'suspended')
                    <button type="button" class="btn btn-success" onclick="activateUser({{ $user->id }})">
                        <i class="bi bi-check-lg me-2"></i>Activer
                    </button>
                @else
                    <button type="button" class="btn btn-warning" onclick="suspendUser({{ $user->id }})">
                        <i class="bi bi-pause me-2"></i>Suspendre
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modals pour les détails des vendeurs -->
@foreach ($vendors as $vendor)
<div class="modal fade" id="vendorDetailModal{{ $vendor->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="bi bi-shop me-2"></i>Détails du vendeur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-section">
                            <h6 class="section-title">Informations vendeur</h6>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Nom de la boutique</label>
                                    <div>{{ $vendor->store_name ?? 'Non renseigné' }}</div>
                                </div>
                                <div class="info-item">
                                    <label>Propriétaire</label>
                                    <div>{{ $vendor->user->name }}</div>
                                </div>
                                <div class="info-item">
                                    <label>Email</label>
                                    <div>{{ $vendor->user->email }}</div>
                                </div>
                                <div class="info-item">
                                    <label>Téléphone</label>
                                    <div>{{ $vendor->user->phone_number ?? 'Non renseigné' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-section">
                            <h6 class="section-title">Statistiques</h6>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Statut</label>
                                    <div>
                                        <span class="status-badge status-{{ $vendor->status }}">
                                            {{ ucfirst($vendor->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <label>Produits</label>
                                    <div>{{ $vendor->products_count ?? 0 }}</div>
                                </div>
                                <div class="info-item">
                                    <label>Date d'inscription</label>
                                    <div>{{ $vendor->created_at->format('d/m/Y à H:i') }}</div>
                                </div>
                                <div class="info-item">
                                    <label>Note moyenne</label>
                                    <div>
                                        <span class="rating">
                                            <i class="bi bi-star-fill text-warning"></i>
                                            {{ $vendor->average_rating ?? '0.0' }}/5
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Adresse -->
                @if($vendor->user->address)
                <div class="info-section mt-4">
                    <h6 class="section-title">Adresse</h6>
                    <p class="mb-0">{{ $vendor->user->address }}</p>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                @if($vendor->status == 'inactive')
                    <a href="{{ route('admin.changeVendorStatus', $vendor->id) }}" class="btn btn-success">
                        <i class="bi bi-check-lg me-2"></i>Activer
                    </a>
                @else
                    <a href="{{ route('admin.changeVendorStatus', $vendor->id) }}" class="btn btn-warning">
                        <i class="bi bi-pause me-2"></i>Désactiver
                    </a>
                @endif
                <button type="button" class="btn btn-primary" onclick="viewVendorProducts({{ $vendor->id }})">
                    <i class="bi bi-box-seam me-2"></i>Voir les produits
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Filtres avancés -->
<div class="modal fade" id="filtersModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filtres avancés</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="advancedFilters">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Date d'inscription (début)</label>
                            <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Date d'inscription (fin)</label>
                            <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Dernière connexion</label>
                            <select class="form-select" name="last_login">
                                <option value="">Tous</option>
                                <option value="today" {{ request('last_login') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                                <option value="week" {{ request('last_login') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                                <option value="month" {{ request('last_login') == 'month' ? 'selected' : '' }}>Ce mois</option>
                                <option value="never" {{ request('last_login') == 'never' ? 'selected' : '' }}>Jamais connecté</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="applyAdvancedFilters()">Appliquer</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des onglets
    const usersTab = new bootstrap.Tab(document.getElementById('users-tab'));
    const vendorsTab = new bootstrap.Tab(document.getElementById('vendors-tab'));

    // Application des filtres
    window.applyFilters = function() {
        const search = document.getElementById('searchInput').value;
        const role = document.getElementById('roleFilter').value;
        const status = document.getElementById('statusFilter').value;
        
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (role) params.append('role', role);
        if (status) params.append('status', status);
        
        window.location.href = '{{ route('admin.users') }}?' + params.toString();
    };

    // Réinitialisation des filtres
    window.resetFilters = function() {
        window.location.href = '{{ route('admin.users') }}';
    };

    // Export des utilisateurs
    window.exportUsers = function() {
        showToast('Export des utilisateurs en cours...', 'info');
        // Implémentation réelle de l'export
    };

    // Actions sur les utilisateurs
    window.activateUser = function(userId) {
        if (confirm('Êtes-vous sûr de vouloir activer cet utilisateur ?')) {
            // Simulation d'activation
            showToast('Utilisateur activé avec succès', 'success');
        }
    };

    window.suspendUser = function(userId) {
        if (confirm('Êtes-vous sûr de vouloir suspendre cet utilisateur ?')) {
            // Simulation de suspension
            showToast('Utilisateur suspendu avec succès', 'success');
        }
    };

    window.deleteUser = function(userId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')) {
            // Simulation de suppression
            showToast('Utilisateur supprimé avec succès', 'success');
        }
    };

    // Actions sur les vendeurs
    window.viewVendorProducts = function(vendorId) {
        // Redirection vers la page des produits du vendeur
        window.location.href = `/admin/vendors/${vendorId}/products`;
    };

    // Filtres avancés
    window.applyAdvancedFilters = function() {
        const form = document.getElementById('advancedFilters');
        const formData = new FormData(form);
        const params = new URLSearchParams();
        
        for (let [key, value] of formData) {
            if (value) params.append(key, value);
        }
        
        window.location.href = '{{ route('admin.users') }}?' + params.toString();
    };
});

// Fonction utilitaire pour les notifications
function showToast(message, type = 'info') {
    // Implémentation des toasts (à adapter selon votre système)
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.top = '20px';
    toast.style.right = '20px';
    toast.style.zIndex = '9999';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>

<style>
.users-container {
    padding: 0;
}

.page-header {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-title {
    font-weight: 700;
    color: var(--dark);
    margin: 0;
    font-size: 2rem;
}

.page-subtitle {
    color: var(--gray);
    margin: 0.5rem 0 0 0;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.filters-section {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--shadow);
    margin-bottom: 2rem;
}

.search-box {
    position: relative;
}

.search-box i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray);
}

.search-box .form-control {
    padding-left: 2.5rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--shadow);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: var(--transition);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--dark);
    line-height: 1;
}

.stat-label {
    color: var(--gray);
    font-size: 0.9rem;
    margin-top: 0.25rem;
}

.tabs-section {
    background: white;
    border-radius: var(--border-radius);
    padding: 0 1rem;
    box-shadow: var(--shadow);
}

.nav-tabs .nav-link {
    border: none;
    padding: 1rem 1.5rem;
    color: var(--gray);
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    color: var(--primary);
    border-bottom: 3px solid var(--primary);
    background: transparent;
}

.user-row:hover,
.vendor-row:hover {
    background-color: var(--light);
}

.user-name {
    font-weight: 600;
    color: var(--dark);
}

.store-name {
    font-weight: 600;
    color: var(--info);
}

.contact-info .email {
    font-weight: 500;
    color: var(--dark);
}

.role-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

.role-user {
    background: rgba(108, 117, 125, 0.1);
    color: var(--gray);
    border: 1px solid rgba(108, 117, 125, 0.2);
}

.role-vendor {
    background: rgba(23, 162, 184, 0.1);
    color: var(--info);
    border: 1px solid rgba(23, 162, 184, 0.2);
}

.role-admin {
    background: rgba(220, 53, 69, 0.1);
    color: var(--danger);
    border: 1px solid rgba(220, 53, 69, 0.2);
}

.status-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

.status-active {
    background: rgba(40, 167, 69, 0.1);
    color: var(--success);
    border: 1px solid rgba(40, 167, 69, 0.2);
}

.status-pending {
    background: rgba(255, 193, 7, 0.1);
    color: var(--warning);
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.status-suspended {
    background: rgba(220, 53, 69, 0.1);
    color: var(--danger);
    border: 1px solid rgba(220, 53, 69, 0.2);
}

.status-inactive {
    background: rgba(108, 117, 125, 0.1);
    color: var(--gray);
    border: 1px solid rgba(108, 117, 125, 0.2);
}

.date-info {
    font-size: 0.9rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.action-buttons .btn {
    padding: 0.25rem 0.5rem;
}

.empty-state {
    padding: 3rem 2rem;
}

.info-section {
    margin-bottom: 2rem;
}

.section-title {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--gray-light);
}

.info-grid {
    display: grid;
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--gray-light);
}

.info-item:last-child {
    border-bottom: none;
}

.info-item label {
    font-weight: 600;
    color: var(--dark);
    min-width: 120px;
}

.info-item div {
    color: var(--gray);
    text-align: right;
    flex: 1;
}

.rating {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .header-actions {
        width: 100%;
        justify-content: space-between;
    }
    
    .stats-grid {
        grid-template-columns: 1fr 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .info-item {
        flex-direction: column;
        text-align: left;
    }
    
    .info-item div {
        text-align: left;
    }
}

@media (max-width: 576px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .filters-section .row {
        flex-direction: column;
    }
    
    .action-buttons .btn {
        padding: 0.5rem;
    }
    
    .nav-tabs .nav-link {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
    }
}
</style>
@endsection