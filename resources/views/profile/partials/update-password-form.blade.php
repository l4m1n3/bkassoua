<section class="password-form-section">
    <div class="form-header mb-4">
        <h2 class="form-title mb-2">
            <i class="bi bi-shield-lock me-2"></i>
            Mise à jour du mot de passe
        </h2>
        <p class="form-subtitle text-muted">
            Assurez-vous d'utiliser un mot de passe long et sécurisé pour protéger votre compte.
        </p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="password-form">
        @csrf
        @method('put')

        <div class="row">
            <div class="col-md-12">
                <div class="form-group mb-4">
                    <label for="current_password" class="form-label fw-semibold">
                        <i class="bi bi-key me-1"></i>
                        Mot de passe actuel
                    </label>
                    <div class="password-input-group">
                        <input type="password" class="form-control form-control-lg @error('current_password') is-invalid @enderror" 
                               id="current_password" name="current_password" required 
                               placeholder="Entrez votre mot de passe actuel">
                        <button type="button" class="btn btn-outline-secondary toggle-password" 
                                data-target="current_password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <div class="invalid-feedback d-block">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-4">
                    <label for="password" class="form-label fw-semibold">
                        <i class="bi bi-key-fill me-1"></i>
                        Nouveau mot de passe
                    </label>
                    <div class="password-input-group">
                        <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                               id="password" name="password" required 
                               placeholder="Nouveau mot de passe">
                        <button type="button" class="btn btn-outline-secondary toggle-password" 
                                data-target="password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                    <div class="password-strength mt-2">
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar" id="password-strength-bar"></div>
                        </div>
                        <small class="text-muted" id="password-strength-text">Force du mot de passe</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">
                        <i class="bi bi-key-fill me-1"></i>
                        Confirmer le mot de passe
                    </label>
                    <div class="password-input-group">
                        <input type="password" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" 
                               id="password_confirmation" name="password_confirmation" required 
                               placeholder="Confirmer le mot de passe">
                        <button type="button" class="btn btn-outline-secondary toggle-password" 
                                data-target="password_confirmation">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <div class="invalid-feedback d-block">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Conseils de sécurité -->
        <div class="security-tips alert alert-info">
            <h6 class="alert-heading mb-2">
                <i class="bi bi-info-circle me-2"></i>Conseils de sécurité
            </h6>
            <ul class="mb-0 small">
                <li>Utilisez au moins 8 caractères</li>
                <li>Combinez lettres, chiffres et caractères spéciaux</li>
                <li>Évitez les mots de passe courants</li>
            </ul>
        </div>

        <!-- Actions du formulaire -->
        <div class="form-actions mt-4 pt-3 border-top">
            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-primary btn-lg" id="submit-password">
                    <i class="bi bi-shield-check me-2"></i>
                    Mettre à jour le mot de passe
                </button>

                @if (session('status') === 'password-updated')
                    <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Mot de passe mis à jour avec succès
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>
        </div>
    </form>
</section>

<style>
.password-form-section {
    padding: 0;
}

.password-input-group {
    position: relative;
}

.password-input-group .form-control {
    padding-right: 50px;
}

.password-input-group .toggle-password {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: transparent;
}

.security-tips {
    border-left: 4px solid var(--info);
    background: rgba(13, 110, 253, 0.05);
}

.security-tips ul {
    list-style: none;
    padding-left: 0;
}

.security-tips li {
    position: relative;
    padding-left: 1.5rem;
    margin-bottom: 0.25rem;
}

.security-tips li:before {
    content: "✓";
    position: absolute;
    left: 0;
    color: var(--success);
    font-weight: bold;
}

/* Barre de force du mot de passe */
.progress {
    background-color: var(--gray-light);
}

.progress-bar {
    transition: width 0.3s ease;
}

/* Responsive */
@media (max-width: 768px) {
    .form-actions .d-flex {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });

    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('password-strength-bar');
    const strengthText = document.getElementById('password-strength-text');

    if (passwordInput && strengthBar && strengthText) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            
            strengthBar.style.width = strength.percentage + '%';
            strengthBar.className = 'progress-bar ' + strength.class;
            strengthText.textContent = strength.text;
            strengthText.className = 'text-muted ' + strength.textClass;
        });
    }

    function calculatePasswordStrength(password) {
        let score = 0;
        
        // Longueur minimale
        if (password.length >= 8) score += 25;
        if (password.length >= 12) score += 15;
        
        // Complexité
        if (/[a-z]/.test(password)) score += 10;
        if (/[A-Z]/.test(password)) score += 10;
        if (/[0-9]/.test(password)) score += 10;
        if (/[^a-zA-Z0-9]/.test(password)) score += 10;
        
        // Variations
        if (password.length >= 6 && /[a-z]/.test(password) && /[A-Z]/.test(password)) score += 10;
        if (password.length >= 8 && /[0-9]/.test(password)) score += 10;
        if (password.length >= 10 && /[^a-zA-Z0-9]/.test(password)) score += 10;

        // Limiter à 100%
        score = Math.min(score, 100);

        if (score < 40) {
            return {
                percentage: score,
                class: 'bg-danger',
                text: 'Faible',
                textClass: 'text-danger'
            };
        } else if (score < 70) {
            return {
                percentage: score,
                class: 'bg-warning',
                text: 'Moyen',
                textClass: 'text-warning'
            };
        } else {
            return {
                percentage: score,
                class: 'bg-success',
                text: 'Fort',
                textClass: 'text-success'
            };
        }
    }
});
</script>