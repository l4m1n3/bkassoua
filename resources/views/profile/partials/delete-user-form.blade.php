<section class="delete-account-section">
    <div class="danger-header mb-4">
        <h2 class="danger-title mb-2 text-danger">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Suppression du compte
        </h2>
        <p class="danger-subtitle text-muted">
            Une fois votre compte supprimé, toutes vos données seront définitivement effacées.
            Cette action est irréversible.
        </p>
    </div>

    <div class="danger-content bg-light rounded p-4 mb-4">
        <div class="d-flex align-items-start">
            <i class="bi bi-info-circle-fill text-primary me-3 mt-1"></i>
            <div>
                <h6 class="mb-2">Avant de supprimer votre compte :</h6>
                <ul class="mb-0 small text-muted">
                    <li>Téléchargez toutes les données que vous souhaitez conserver</li>
                    <li>Annulez tous les abonnements ou services actifs</li>
                    <li>Vos commandes en cours seront annulées</li>
                    <li>Cette action ne peut pas être annulée</li>
                </ul>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-outline-danger btn-lg w-100" 
            data-bs-toggle="modal" data-bs-target="#confirmAccountDeletion">
        <i class="bi bi-trash3 me-2"></i>
        Supprimer mon compte
    </button>

    <!-- Modal de confirmation -->
    <div class="modal fade" id="confirmAccountDeletion" tabindex="-1" 
         aria-labelledby="confirmAccountDeletionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmAccountDeletionLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Confirmer la suppression
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form method="post" action="{{ route('profile.destroy') }}" id="deleteAccountForm">
                        @csrf
                        @method('delete')

                        <div class="warning-alert alert alert-warning mb-4">
                            <div class="d-flex">
                                <i class="bi bi-exclamation-triangle-fill me-3 fs-5"></i>
                                <div>
                                    <h6 class="alert-heading mb-1">Attention ! Action irréversible</h6>
                                    <p class="mb-0">Êtes-vous certain de vouloir supprimer votre compte ? Toutes vos données seront définitivement perdues.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <p class="text-muted mb-3">
                                Pour confirmer la suppression, veuillez saisir votre mot de passe :
                            </p>
                            <div class="form-group">
                                <label for="delete_password" class="form-label fw-semibold">Mot de passe</label>
                                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                       id="delete_password" name="password" 
                                       placeholder="Votre mot de passe actuel" required>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-footer border-top-0 pt-0">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-2"></i>Annuler
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash3 me-2"></i>Supprimer définitivement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.delete-account-section {
    padding: 0;
}

.danger-title {
    font-weight: 600;
    font-size: 1.5rem;
}

.danger-subtitle {
    font-size: 0.95rem;
}

.danger-content {
    border-left: 4px solid var(--primary);
}

.danger-content ul {
    list-style: none;
    padding-left: 0;
}

.danger-content li {
    position: relative;
    padding-left: 1.5rem;
    margin-bottom: 0.5rem;
}

.danger-content li:before {
    content: "•";
    position: absolute;
    left: 0.5rem;
    color: var(--primary);
    font-weight: bold;
}

.warning-alert {
    border-left: 4px solid var(--warning);
    background: rgba(255, 193, 7, 0.05);
}

/* Animation pour le modal */
.modal-content {
    border-radius: var(--border-radius);
}

.modal-header {
    border-radius: var(--border-radius) var(--border-radius) 0 0;
}

/* Responsive */
@media (max-width: 576px) {
    .modal-dialog {
        margin: 1rem;
    }
    
    .danger-content .d-flex {
        flex-direction: column;
        text-align: center;
    }
    
    .danger-content .bi {
        margin-bottom: 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteForm = document.getElementById('deleteAccountForm');
    const deleteButton = document.querySelector('[data-bs-target="#confirmAccountDeletion"]');
    
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            const password = document.getElementById('delete_password').value;
            
            if (!password) {
                e.preventDefault();
                alert('Veuillez saisir votre mot de passe pour confirmer la suppression.');
                return;
            }
            
            if (!confirm('Êtes-vous ABSOLUMENT CERTAIN de vouloir supprimer votre compte ? Cette action est IRREVERSIBLE.')) {
                e.preventDefault();
            }
        });
    }
    
    // Reset form when modal is closed
    const modal = document.getElementById('confirmAccountDeletion');
    modal.addEventListener('hidden.bs.modal', function() {
        if (deleteForm) {
            deleteForm.reset();
        }
    });
});
</script>