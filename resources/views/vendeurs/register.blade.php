@extends('layouts.slaves')

@section('title', 'Devenir Vendeur - Bkassoua')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-6">
            <!-- En-tête de la page -->
            <div class="page-header text-center mb-5">
                <div class="header-icon mb-4">
                    <div class="icon-wrapper bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-shop-window text-white" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <h1 class="page-title mb-3">Devenir Vendeur sur Bkassoua</h1>
                <p class="page-subtitle text-muted">
                    Rejoignez notre marketplace et commencez à vendre vos produits dès aujourd'hui
                </p>
            </div>

            <!-- Carte du formulaire -->
            <div class="vendor-registration-card bg-white rounded shadow-lg">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h2 class="mb-0">
                        <i class="bi bi-person-plus me-2"></i>
                        Demande d'inscription vendeur
                    </h2>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('vendor.store') }}" method="POST" enctype="multipart/form-data" id="vendorForm">
                        @csrf
                        
                        <!-- Informations de base -->
                        <div class="form-section mb-5">
                            <h4 class="section-title mb-4">
                                <i class="bi bi-info-circle text-primary me-2"></i>
                                Informations de votre boutique
                            </h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-4">
                                        <label for="store_name" class="form-label fw-semibold">
                                            Nom du magasin <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="store_name" id="store_name" 
                                               value="{{ old('store_name') }}" 
                                               class="form-control form-control-lg @error('store_name') is-invalid @enderror" 
                                               placeholder="Entrez le nom de votre boutique" required>
                                        @error('store_name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label for="address" class="form-label fw-semibold">
                                    Adresse complète <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="address" id="address" 
                                       value="{{ old('address') }}" 
                                       class="form-control form-control-lg @error('address') is-invalid @enderror" 
                                       placeholder="Adresse de votre boutique" required>
                                @error('address')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label for="store_description" class="form-label fw-semibold">
                                    Description de la boutique <span class="text-danger">*</span>
                                </label>
                                <textarea name="store_description" id="store_description" 
                                          class="form-control @error('store_description') is-invalid @enderror" 
                                          rows="5" 
                                          placeholder="Décrivez votre boutique, vos produits, votre philosophie..." required>{{ old('store_description') }}</textarea>
                                <div class="form-text">Cette description sera visible par les clients sur votre page boutique.</div>
                                @error('store_description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Logo de la boutique -->
                        <div class="form-section mb-5">
                            <h4 class="section-title mb-4">
                                <i class="bi bi-image text-primary me-2"></i>
                                Identité visuelle
                            </h4>
                            <div class="form-group">
                                <label class="form-label fw-semibold">Logo de la boutique</label>
                                <div class="file-upload-area">
                                    <div class="upload-container text-center p-4 border rounded">
                                        <input type="file" name="logo" id="logo" 
                                               class="file-input @error('logo') is-invalid @enderror" 
                                               accept="image/jpeg,image/png,image/jpg,image/gif">
                                        <div class="upload-placeholder">
                                            <i class="bi bi-cloud-upload display-4 text-muted mb-3"></i>
                                            <h5 class="text-muted">Glissez-déposez votre logo ici</h5>
                                            <p class="text-muted mb-3">ou</p>
                                            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('logo').click()">
                                                <i class="bi bi-folder2-open me-2"></i>Parcourir les fichiers
                                            </button>
                                            <p class="small text-muted mt-2">Formats supportés: JPEG, PNG, JPG, GIF (max. 5MB)</p>
                                        </div>
                                        <div class="upload-preview d-none">
                                            <img id="logo-preview" class="preview-image rounded" alt="Aperçu du logo">
                                            <div class="preview-actions mt-3">
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="removeImage()">
                                                    <i class="bi bi-trash me-1"></i>Supprimer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @error('logo')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informations supplémentaires -->
                        <div class="form-section mb-5">
                            <h4 class="section-title mb-4">
                                <i class="bi bi-shield-check text-primary me-2"></i>
                                Conditions et validation
                            </h4>
                            <div class="agreement-section bg-light rounded p-4">
                                <div class="form-check mb-3">
                                    <input class="form-check-input @error('terms') is-invalid @enderror" 
                                           type="checkbox" name="terms" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        J'accepte les <a href="#" class="text-primary">conditions générales d'utilisation</a> 
                                        et la <a href="#" class="text-primary">politique de confidentialité</a> de Bkassoua
                                    </label>
                                    @error('terms')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input @error('compliance') is-invalid @enderror" 
                                           type="checkbox" name="compliance" id="compliance" required>
                                    <label class="form-check-label" for="compliance">
                                        Je certifie que tous les produits que je vends respectent les lois en vigueur 
                                        et les politiques de Bkassoua
                                    </label>
                                    @error('compliance')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="form-section text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3 submit-btn">
                                <i class="bi bi-send-check me-2"></i>
                                Soumettre ma demande
                            </button>
                            <p class="text-muted mt-3 small">
                                Votre demande sera examinée sous 24-48 heures. Vous recevrez un email de confirmation.
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informations supplémentaires -->
            <div class="additional-info mt-5">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="info-card text-center p-4 rounded">
                            <i class="bi bi-graph-up-arrow text-primary display-6 mb-3"></i>
                            <h5>Augmentez vos ventes</h5>
                            <p class="text-muted mb-0">Accédez à des milliers de clients potentiels</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card text-center p-4 rounded">
                            <i class="bi bi-shield-check text-primary display-6 mb-3"></i>
                            <h5>Paiements sécurisés</h5>
                            <p class="text-muted mb-0">Transactions protégées et garanties</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card text-center p-4 rounded">
                            <i class="bi bi-headset text-primary display-6 mb-3"></i>
                            <h5>Support dédié</h5>
                            <p class="text-muted mb-0">Notre équipe vous accompagne</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('logo');
    const uploadContainer = document.querySelector('.upload-container');
    const uploadPlaceholder = document.querySelector('.upload-placeholder');
    const uploadPreview = document.querySelector('.upload-preview');
    const previewImg = document.getElementById('logo-preview');

    // Gestion du drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadContainer.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadContainer.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadContainer.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        uploadContainer.classList.add('highlight');
    }

    function unhighlight() {
        uploadContainer.classList.remove('highlight');
    }

    // Gestion du drop
    uploadContainer.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        handleFiles(files);
    }

    // Gestion du changement de fichier
    fileInput.addEventListener('change', function(e) {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        const file = files[0];
        
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                uploadPlaceholder.classList.add('d-none');
                uploadPreview.classList.remove('d-none');
            };
            
            reader.readAsDataURL(file);
        } else if (file) {
            showError('Veuillez sélectionner un fichier image valide (JPEG, PNG, JPG, GIF)');
        }
    }

    function removeImage() {
        fileInput.value = '';
        uploadPreview.classList.add('d-none');
        uploadPlaceholder.classList.remove('d-none');
    }

    function showError(message) {
        // Créer une alerte temporaire
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
        alertDiv.innerHTML = `
            <i class="bi bi-exclamation-triangle me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.file-upload-area').appendChild(alertDiv);
        
        // Supprimer l'alerte après 5 secondes
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

    // Validation du formulaire
    const form = document.getElementById('vendorForm');
    form.addEventListener('submit', function(e) {
        const terms = document.getElementById('terms');
        const compliance = document.getElementById('compliance');
        
        if (!terms.checked || !compliance.checked) {
            e.preventDefault();
            showError('Veuillez accepter toutes les conditions pour continuer');
        }
    });

    // Animation des cartes d'information
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

    document.querySelectorAll('.info-card').forEach(card => {
        observer.observe(card);
    });
});
</script>

<style>
/* Styles spécifiques à la page d'inscription vendeur */
.page-header {
    padding: 2rem 0;
}

.page-title {
    font-weight: 700;
    color: var(--dark);
    font-size: 2.5rem;
}

.page-subtitle {
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
}

.vendor-registration-card {
    border: none;
    border-radius: var(--border-radius);
    overflow: hidden;
}

.vendor-registration-card .card-header {
    border-bottom: none;
}

.form-section {
    padding: 1.5rem 0;
    border-bottom: 1px solid var(--gray-light);
}

.form-section:last-of-type {
    border-bottom: none;
}

.section-title {
    font-weight: 600;
    color: var(--dark);
    font-size: 1.25rem;
}

.form-control-lg {
    padding: 0.75rem 1rem;
    font-size: 1rem;
}

.file-upload-area {
    position: relative;
}

.upload-container {
    border: 2px dashed var(--gray-light);
    transition: var(--transition);
    background: var(--light);
    cursor: pointer;
}

.upload-container:hover,
.upload-container.highlight {
    border-color: var(--primary);
    background: rgba(23, 128, 214, 0.05);
}

.file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.upload-placeholder {
    transition: var(--transition);
}

.preview-image {
    max-width: 200px;
    max-height: 150px;
    object-fit: contain;
}

.agreement-section {
    border-left: 4px solid var(--primary);
}

.submit-btn {
    min-width: 200px;
    transition: var(--transition);
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(23, 128, 214, 0.3);
}

.info-card {
    background: white;
    box-shadow: var(--shadow);
    transition: var(--transition);
    border: 1px solid var(--gray-light);
}

.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* Animation pour le fade-in */
.fade-in {
    animation: fadeInUp 0.6s ease forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .vendor-registration-card .card-body {
        padding: 2rem;
    }
    
    .form-section {
        padding: 1rem 0;
    }
    
    .additional-info .row {
        flex-direction: column;
    }
    
    .info-card {
        margin-bottom: 1rem;
    }
}

@media (max-width: 576px) {
    .page-title {
        font-size: 1.75rem;
    }
    
    .vendor-registration-card .card-body {
        padding: 1.5rem;
    }
    
    .submit-btn {
        width: 100%;
    }
}
</style>
@endsection