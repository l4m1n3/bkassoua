@extends('layouts.slaves')

@section('title', 'Nous Contacter - Bkassoua')

@section('content')
<!-- Hero Section Contact -->
<div class="contact-hero">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-5 fw-bold text-white mb-4">Contactez-Nous</h1>
                <p class="lead text-white mb-0">Une question, un projet ou besoin d'assistance ? Notre équipe est là pour vous accompagner.</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Contact Section -->
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-5" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-5" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Veuillez corriger les erreurs suivantes :
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-5">
                <!-- Contact Form -->
                <div class="col-lg-7">
                    <div class="contact-form-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="bi bi-envelope-paper me-2"></i>Envoyez-nous un message
                            </h3>
                            <p class="card-subtitle">Nous vous répondrons dans les plus brefs délais</p>
                        </div>
                        <div class="card-body">
                            <form action="" method="POST" id="contactForm">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="name" name="name" 
                                                   placeholder="Votre nom complet" value="{{ old('name') }}" required>
                                            <label for="name">
                                                <i class="bi bi-person me-2"></i>Nom Complet
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   placeholder="votre@email.com" value="{{ old('email') }}" required>
                                            <label for="email">
                                                <i class="bi bi-envelope me-2"></i>Adresse E-mail
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="subject" name="subject" 
                                                   placeholder="Sujet de votre message" value="{{ old('subject') }}" required>
                                            <label for="subject">
                                                <i class="bi bi-chat-text me-2"></i>Sujet du message
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea class="form-control" id="message" name="message" 
                                                      placeholder="Décrivez votre demande..." style="height: 150px" required>{{ old('message') }}</textarea>
                                            <label for="message">
                                                <i class="bi bi-pencil me-2"></i>Votre Message
                                            </label>
                                        </div>
                                        <div class="form-text">Décrivez-nous votre projet ou votre question en détail.</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter">
                                            <label class="form-check-label" for="newsletter">
                                                Je souhaite recevoir les actualités et offres spéciales de Bkassoua
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-lg w-100" id="submitButton">
                                            <i class="bi bi-send me-2"></i>
                                            <span class="submit-text">Envoyer le message</span>
                                            <div class="spinner-border spinner-border-sm ms-2 d-none" role="status" id="submitSpinner">
                                                <span class="visually-hidden">Envoi en cours...</span>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-lg-5">
                    <div class="contact-info-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="bi bi-info-circle me-2"></i>Nos Coordonnées
                            </h3>
                            <p class="card-subtitle">Plusieurs façons de nous contacter</p>
                        </div>
                        <div class="card-body">
                            <!-- Contact Methods -->
                            <div class="contact-methods">
                                <div class="contact-method">
                                    <div class="method-icon bg-primary">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <div class="method-content">
                                        <h6>Notre Adresse</h6>
                                        <p class="mb-0">123 Rue de la Mode, 75001 Paris, France</p>
                                    </div>
                                </div>

                                <div class="contact-method">
                                    <div class="method-icon bg-success">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <div class="method-content">
                                        <h6>Email</h6>
                                        <p class="mb-0">support@bkassoua.com</p>
                                        <small class="text-muted">Réponse sous 24h</small>
                                    </div>
                                </div>

                                <div class="contact-method">
                                    <div class="method-icon bg-info">
                                        <i class="bi bi-phone"></i>
                                    </div>
                                    <div class="method-content">
                                        <h6>Téléphone</h6>
                                        <p class="mb-0">+33 1 23 45 67 89</p>
                                        <small class="text-muted">Lun-Ven: 9h-18h</small>
                                    </div>
                                </div>

                                <div class="contact-method">
                                    <div class="method-icon bg-warning">
                                        <i class="bi bi-whatsapp"></i>
                                    </div>
                                    <div class="method-content">
                                        <h6>WhatsApp</h6>
                                        <p class="mb-0">+33 6 12 34 56 78</p>
                                        <small class="text-muted">Support instantané</small>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Business Hours -->
                            <div class="business-hours">
                                <h6 class="section-title">
                                    <i class="bi bi-clock me-2"></i>Nos Horaires d'Ouverture
                                </h6>
                                <div class="hours-list">
                                    <div class="hour-item">
                                        <span>Lundi - Vendredi</span>
                                        <span class="text-primary">9h00 - 18h00</span>
                                    </div>
                                    <div class="hour-item">
                                        <span>Samedi</span>
                                        <span class="text-primary">10h00 - 16h00</span>
                                    </div>
                                    <div class="hour-item">
                                        <span>Dimanche</span>
                                        <span class="text-muted">Fermé</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Links -->
                            <div class="social-links mt-4">
                                <h6 class="section-title">
                                    <i class="bi bi-share me-2"></i>Suivez-nous
                                </h6>
                                <div class="d-flex gap-3">
                                    <a href="#" class="social-link facebook">
                                        <i class="bi bi-facebook"></i>
                                    </a>
                                    <a href="#" class="social-link instagram">
                                        <i class="bi bi-instagram"></i>
                                    </a>
                                    <a href="#" class="social-link twitter">
                                        <i class="bi bi-twitter"></i>
                                    </a>
                                    <a href="#" class="social-link linkedin">
                                        <i class="bi bi-linkedin"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Map Section -->
                    <div class="map-card mt-4">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-map me-2"></i>Nous Trouver
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="map-container">
                                <!-- Google Maps Embed -->
                                <iframe 
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.9916256937595!2d2.292292615509614!3d48.85837007928746!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e2964e34e2d%3A0x8ddca9ee380ef7e0!2sTour%20Eiffel!5e0!3m2!1sfr!2sfr!4v1633013467022!5m2!1sfr!2sfr" 
                                    width="100%" 
                                    height="250" 
                                    style="border:0; border-radius: 0 0 8px 8px;" 
                                    allowfullscreen="" 
                                    loading="lazy">
                                </iframe>
                                <div class="map-overlay">
                                    <a href="https://goo.gl/maps/example" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-arrow-up-right-square me-1"></i>Ouvrir dans Google Maps
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="faq-section">
                        <div class="text-center mb-5">
                            <h2 class="section-title">Questions Fréquentes</h2>
                            <p class="section-subtitle">Retrouvez les réponses aux questions les plus courantes</p>
                        </div>
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h3 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        <i class="bi bi-question-circle me-2"></i>
                                        Quel est le délai de livraison ?
                                    </button>
                                </h3>
                                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Les délais de livraison varient entre 2 et 5 jours ouvrés en France métropolitaine. Pour les commandes internationales, comptez 7 à 14 jours ouvrés.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        <i class="bi bi-question-circle me-2"></i>
                                        Comment puis-je retourner un article ?
                                    </button>
                                </h3>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Vous avez 30 jours pour retourner un article non utilisé. Connectez-vous à votre compte, allez dans "Mes commandes" et suivez la procédure de retour.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                        <i class="bi bi-question-circle me-2"></i>
                                        Acceptez-vous les commandes internationales ?
                                    </button>
                                </h3>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Oui, nous livrons dans toute l'Europe. Les frais de port et délais de livraison varient selon le pays de destination.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.contact-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    padding: 6rem 0 4rem;
    position: relative;
    overflow: hidden;
}

.contact-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.min-vh-50 {
    min-height: 50vh;
}

.contact-form-card,
.contact-info-card,
.map-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: none;
    overflow: hidden;
}

.contact-form-card .card-header,
.contact-info-card .card-header,
.map-card .card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    padding: 1.5rem 2rem;
}

.contact-form-card .card-body,
.contact-info-card .card-body {
    padding: 2rem;
}

.card-title {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.card-subtitle {
    color: var(--gray);
    margin-bottom: 0;
}

.form-floating {
    margin-bottom: 1rem;
}

.form-floating .form-control {
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    padding: 1rem 0.75rem;
    height: calc(3.5rem + 2px);
}

.form-floating label {
    padding: 1rem 0.75rem;
    color: var(--gray);
}

.form-floating .form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.2rem rgba(var(--primary-rgb), 0.1);
}

.contact-methods {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.contact-method {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.method-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.method-content h6 {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.25rem;
}

.method-content p {
    margin-bottom: 0.25rem;
    color: var(--gray);
}

.section-title {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

.hours-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.hour-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.hour-item:last-child {
    border-bottom: none;
}

.social-links {
    text-align: center;
}

.social-link {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: transform 0.2s ease;
}

.social-link:hover {
    transform: translateY(-2px);
}

.social-link.facebook { background: #3b5998; }
.social-link.instagram { background: #e4405f; }
.social-link.twitter { background: #1da1f2; }
.social-link.linkedin { background: #0077b5; }

.map-container {
    position: relative;
}

.map-overlay {
    position: absolute;
    top: 10px;
    right: 10px;
}

.faq-section {
    background: white;
    border-radius: 12px;
    padding: 3rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.section-title {
    font-weight: 700;
    color: var(--dark);
}

.section-subtitle {
    color: var(--gray);
    font-size: 1.1rem;
}

.accordion-button {
    font-weight: 500;
    padding: 1.25rem 1.5rem;
    border: none;
    background: #f8f9fa;
}

.accordion-button:not(.collapsed) {
    background: var(--primary);
    color: white;
}

.accordion-body {
    padding: 1.5rem;
    background: white;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
}

/* Responsive Design */
@media (max-width: 768px) {
    .contact-hero {
        padding: 4rem 0 2rem;
    }
    
    .contact-form-card .card-body,
    .contact-info-card .card-body {
        padding: 1.5rem;
    }
    
    .faq-section {
        padding: 2rem 1.5rem;
    }
    
    .map-overlay {
        position: relative;
        top: 0;
        right: 0;
        text-align: center;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.9);
    }
}

@media (max-width: 576px) {
    .contact-method {
        flex-direction: column;
        text-align: center;
    }
    
    .method-icon {
        align-self: center;
    }
    
    .hour-item {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const submitButton = document.getElementById('submitButton');
    const submitSpinner = document.getElementById('submitSpinner');
    const submitText = document.querySelector('.submit-text');

    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            // Show loading state
            submitText.textContent = 'Envoi en cours...';
            submitSpinner.classList.remove('d-none');
            submitButton.disabled = true;

            // Simulate form submission (remove this in production)
            setTimeout(() => {
                submitText.textContent = 'Message envoyé !';
                submitSpinner.classList.add('d-none');
                
                // Reset form after success
                setTimeout(() => {
                    contactForm.reset();
                    submitText.textContent = 'Envoyer le message';
                    submitButton.disabled = false;
                }, 2000);
            }, 2000);
        });
    }

    // Add animation to contact methods
    const contactMethods = document.querySelectorAll('.contact-method');
    contactMethods.forEach((method, index) => {
        method.style.opacity = '0';
        method.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            method.style.transition = 'all 0.5s ease';
            method.style.opacity = '1';
            method.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endsection