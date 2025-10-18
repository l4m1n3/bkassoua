@extends('layouts.slaves')

@section('title', 'Mon Profil')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-6">
            <!-- En-tête du profil -->
            <div class="profile-header text-center mb-5">
                <div class="profile-avatar mb-3">
                    <div class="avatar-placeholder bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 100px; height: 100px; font-size: 2.5rem;">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
                <h1 class="profile-title mb-2">Mon Profil</h1>
                <p class="profile-subtitle text-muted">Gérez vos informations personnelles et votre sécurité</p>
            </div>

            <!-- Section informations du profil -->
            <div class="profile-section card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h3 class="mb-0">
                        <i class="bi bi-person-gear me-2"></i>
                        Informations du profil
                    </h3>
                </div>
                <div class="card-body p-4">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Section mot de passe -->
            <div class="profile-section card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h3 class="mb-0">
                        <i class="bi bi-shield-lock me-2"></i>
                        Sécurité du compte
                    </h3>
                </div>
                <div class="card-body p-4">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Section déconnexion -->
            <div class="profile-section card border-0 shadow-sm">
                <div class="card-header bg-light py-3">
                    <h4 class="mb-0 text-dark">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        Session
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Se déconnecter</h5>
                            <p class="text-muted mb-0">Fermez votre session en toute sécurité</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Section suppression du compte (optionnelle) -->
            <div class="profile-section card border-0 shadow-sm mt-4">
                <div class="card-header bg-light py-3">
                    <h4 class="mb-0 text-dark">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Zone de danger
                    </h4>
                </div>
                <div class="card-body p-4">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles spécifiques à la page profil */
.profile-header {
    padding: 2rem 0;
}

.profile-title {
    font-weight: 700;
    color: var(--dark);
    font-size: 2.25rem;
}

.profile-subtitle {
    font-size: 1.1rem;
}

.profile-section {
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.profile-section:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.avatar-placeholder {
    transition: var(--transition);
}

.avatar-placeholder:hover {
    transform: scale(1.05);
}

/* Responsive */
@media (max-width: 768px) {
    .profile-title {
        font-size: 1.75rem;
    }
    
    .profile-section .card-body {
        padding: 1.5rem;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}
</style>
@endsection