<section class="profile-form-section">
    <div class="form-header mb-4">
        <h2 class="form-title mb-2">
            <i class="bi bi-person-badge me-2"></i>
            Informations du profil
        </h2>
        <p class="form-subtitle text-muted">
            Mettez à jour les informations de votre profil et votre adresse e-mail.
        </p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="profile-form">
        @csrf
        @method('patch')

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-4">
                    <label for="name" class="form-label fw-semibold">
                        <i class="bi bi-person me-1"></i>
                        Nom et Prénom
                    </label>
                    <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $user->name) }}" 
                           required autofocus placeholder="Votre nom complet">
                    @error('name')
                        <div class="invalid-feedback d-block">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-4">
                    <label for="email" class="form-label fw-semibold">
                        <i class="bi bi-envelope me-1"></i>
                        Adresse email
                    </label>
                    <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email', $user->email) }}" 
                           required placeholder="votre@email.com">
                    @error('email')
                        <div class="invalid-feedback d-block">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Vérification email -->
        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="email-verification-alert alert alert-warning mt-4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-3 fs-5"></i>
                    <div>
                        <h6 class="alert-heading mb-1">Email non vérifié</h6>
                        <p class="mb-2">Votre adresse e-mail n'est pas vérifiée.</p>
                        <button form="send-verification" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-send me-1"></i>Renvoyer l'email de vérification
                        </button>
                    </div>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success mt-3 mb-0">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Un nouveau lien de vérification a été envoyé à votre adresse e-mail.
                    </div>
                @endif
            </div>
        @endif

        <!-- Actions du formulaire -->
        <div class="form-actions mt-4 pt-3 border-top">
            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle me-2"></i>
                    Enregistrer les modifications
                </button>

                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Modifications enregistrées avec succès
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>
        </div>
    </form>
</section>

<style>
.profile-form-section {
    padding: 0;
}

.form-title {
    font-weight: 600;
    color: var(--dark);
    font-size: 1.5rem;
}

.form-subtitle {
    font-size: 0.95rem;
}

.profile-form .form-control-lg {
    padding: 0.75rem 1rem;
    font-size: 1rem;
    border: 2px solid var(--gray-light);
    transition: var(--transition);
}

.profile-form .form-control-lg:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.2rem rgba(23, 128, 214, 0.1);
}

.email-verification-alert {
    border-left: 4px solid var(--warning);
    background: rgba(255, 193, 7, 0.05);
}

.form-actions {
    border-color: var(--gray-light) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .form-actions .d-flex {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .form-actions .alert {
        width: 100%;
    }
}
</style>