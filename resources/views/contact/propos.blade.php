@extends('layouts.slaves')

@section('title', 'Nous Contacter - Bkassoua')

@section('content')

<!-- Hero -->
<div class="contact-hero">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-5 fw-bold text-white mb-4">Contactez-Nous</h1>
                <p class="lead text-white mb-0">
                    Une question, un projet ou besoin d'assistance ? Notre équipe est là pour vous accompagner.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Contenu principal -->
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">

            <!-- Alertes -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-5" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="row g-5">

                <!-- ---- Formulaire ---- -->
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
                                            <input
                                                type="text"
                                                class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name"
                                                placeholder="Votre nom complet"
                                                value="{{ old('name') }}"
                                                required
                                            >
                                            <label for="name">
                                                <i class="bi bi-person me-2"></i>Nom Complet
                                            </label>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input
                                                type="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email"
                                                placeholder="votre@email.com"
                                                value="{{ old('email') }}"
                                                required
                                            >
                                            <label for="email">
                                                <i class="bi bi-envelope me-2"></i>Adresse E-mail
                                            </label>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input
                                                type="text"
                                                class="form-control @error('subject') is-invalid @enderror"
                                                id="subject" name="subject"
                                                placeholder="Sujet de votre message"
                                                value="{{ old('subject') }}"
                                                required
                                            >
                                            <label for="subject">
                                                <i class="bi bi-chat-text me-2"></i>Sujet du message
                                            </label>
                                            @error('subject')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea
                                                class="form-control @error('message') is-invalid @enderror"
                                                id="message" name="message"
                                                placeholder="Décrivez votre demande..."
                                                style="height:150px"
                                                required
                                            >{{ old('message') }}</textarea>
                                            <label for="message">
                                                <i class="bi bi-pencil me-2"></i>Votre Message
                                            </label>
                                            @error('message')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-text">Décrivez-nous votre projet ou votre question en détail.</div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   id="newsletter" name="newsletter"
                                                   {{ old('newsletter') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="newsletter">
                                                Je souhaite recevoir les actualités et offres spéciales de Bkassoua
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-lg w-100" id="submitButton">
                                            <i class="bi bi-send me-2"></i>
                                            <span class="submit-text">Envoyer le message</span>
                                            <span class="spinner-border spinner-border-sm ms-2 d-none"
                                                  role="status" id="submitSpinner">
                                                <span class="visually-hidden">Envoi en cours...</span>
                                            </span>
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- ---- Coordonnées ---- -->
                <div class="col-lg-5">
                    <div class="contact-info-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="bi bi-info-circle me-2"></i>Nos Coordonnées
                            </h3>
                            <p class="card-subtitle">Plusieurs façons de nous contacter</p>
                        </div>
                        <div class="card-body">

                            <div class="contact-methods">
                                <div class="contact-method">
                                    <div class="method-icon bg-primary">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <div class="method-content">
                                        <h6>Notre Adresse</h6>
                                        <p class="mb-0">Niamey, Niger</p>
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
                                        <p class="mb-0">+227 XX XX XX XX</p>
                                        <small class="text-muted">Lun-Ven : 9h - 18h</small>
                                    </div>
                                </div>
                                <div class="contact-method">
                                    <div class="method-icon bg-warning">
                                        <i class="bi bi-whatsapp"></i>
                                    </div>
                                    <div class="method-content">
                                        <h6>WhatsApp</h6>
                                        <p class="mb-0">+227 XX XX XX XX</p>
                                        <small class="text-muted">Support instantané</small>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Horaires -->
                            <div class="business-hours">
                                <h6 class="fw-semibold mb-3">
                                    <i class="bi bi-clock me-2"></i>Horaires d'Ouverture
                                </h6>
                                <div class="hours-list">
                                    <div class="hour-item">
                                        <span>Lundi - Vendredi</span>
                                        <span class="text-primary fw-semibold">9h00 - 18h00</span>
                                    </div>
                                    <div class="hour-item">
                                        <span>Samedi</span>
                                        <span class="text-primary fw-semibold">10h00 - 16h00</span>
                                    </div>
                                    <div class="hour-item">
                                        <span>Dimanche</span>
                                        <span class="text-muted">Fermé</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Réseaux sociaux -->
                            <div class="mt-4">
                                <h6 class="fw-semibold mb-3">
                                    <i class="bi bi-share me-2"></i>Suivez-nous
                                </h6>
                                <div class="d-flex gap-3">
                                    <a href="#" class="social-link facebook"><i class="bi bi-facebook"></i></a>
                                    <a href="#" class="social-link instagram"><i class="bi bi-instagram"></i></a>
                                    <a href="#" class="social-link twitter"><i class="bi bi-twitter-x"></i></a>
                                    <a href="#" class="social-link whatsapp"><i class="bi bi-whatsapp"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carte -->
                    <div class="map-card mt-4">
                        <div class="card-header px-3 py-2">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-map me-2"></i>Nous Trouver
                            </h6>
                        </div>
                        <div class="card-body p-0 position-relative">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126094.42865725625!2d2.0353737!3d13.5115963!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x11f67e8b2f2b75cf%3A0x4e3afe64af1d7a59!2sNiamey%2C%20Niger!5e0!3m2!1sfr!2sfr!4v1700000000000"
                                width="100%" height="220"
                                style="border:0; display:block; border-radius: 0 0 12px 12px;"
                                allowfullscreen loading="lazy">
                            </iframe>
                            <div class="map-overlay">
                                <a href="https://maps.google.com/?q=Niamey,Niger" target="_blank"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-arrow-up-right-square me-1"></i>Google Maps
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ---- FAQ ---- -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="faq-section">
                        <div class="text-center mb-5">
                            <h2 class="fw-bold">Questions Fréquentes</h2>
                            <p class="text-muted fs-5">Retrouvez les réponses aux questions les plus courantes</p>
                        </div>
                        <div class="accordion" id="faqAccordion">

                            <div class="accordion-item">
                                <h3 class="accordion-header">
                                    <button class="accordion-button" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq1">
                                        <i class="bi bi-question-circle me-2"></i>
                                        Quel est le délai de livraison ?
                                    </button>
                                </h3>
                                <div id="faq1" class="accordion-collapse collapse show"
                                     data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Les délais de livraison varient entre 24h et 72h selon votre localisation à Niamey. Pour les villes de l'intérieur, comptez 3 à 5 jours ouvrés.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq2">
                                        <i class="bi bi-question-circle me-2"></i>
                                        Comment puis-je retourner un article ?
                                    </button>
                                </h3>
                                <div id="faq2" class="accordion-collapse collapse"
                                     data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Vous avez 7 jours pour retourner un article non utilisé dans son emballage d'origine. Contactez notre support via WhatsApp ou email pour initier la procédure.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq3">
                                        <i class="bi bi-question-circle me-2"></i>
                                        Quels modes de paiement acceptez-vous ?
                                    </button>
                                </h3>
                                <div id="faq3" class="accordion-collapse collapse"
                                     data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Nous acceptons le paiement à la livraison (cash), le Mobile Money (Orange Money, Airtel Money) et le virement bancaire.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq4">
                                        <i class="bi bi-question-circle me-2"></i>
                                        Comment devenir vendeur sur Bkassoua ?
                                    </button>
                                </h3>
                                <div id="faq4" class="accordion-collapse collapse"
                                     data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Créez un compte, puis rendez-vous dans la section "Devenir vendeur". Remplissez le formulaire d'inscription et notre équipe validera votre demande sous 48h.
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


<!-- ===================== SCRIPTS ===================== -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ---- Spinner sur submit (soumission réelle, pas simulée) ---- */
    const contactForm   = document.getElementById('contactForm');
    const submitButton  = document.getElementById('submitButton');
    const submitSpinner = document.getElementById('submitSpinner');
    const submitText    = document.querySelector('.submit-text');

    if (contactForm) {
        contactForm.addEventListener('submit', function () {
            submitText.textContent = 'Envoi en cours...';
            submitSpinner.classList.remove('d-none');
            submitButton.disabled = true;
            // Le formulaire se soumet normalement (POST vers ContactController)
        });
    }

    /* ---- Animation d'entrée des méthodes de contact ---- */
    document.querySelectorAll('.contact-method').forEach(function (el, i) {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        setTimeout(function () {
            el.style.transition = 'opacity .5s ease, transform .5s ease';
            el.style.opacity    = '1';
            el.style.transform  = 'translateY(0)';
        }, i * 120);
    });

});
</script>


<!-- ===================== STYLES ===================== -->
<style>
/* ---- Hero ---- */
.contact-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent, #e76f51) 100%);
    padding: 6rem 0 4rem;
    position: relative;
    overflow: hidden;
}
.contact-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.08'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.min-vh-50 { min-height: 40vh; }

/* ---- Cards ---- */
.contact-form-card,
.contact-info-card,
.map-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,.08);
    overflow: hidden;
}
.contact-form-card .card-header,
.contact-info-card .card-header,
.map-card .card-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-bottom: 1px solid rgba(0,0,0,.05);
    padding: 1.25rem 1.75rem;
}
.contact-form-card .card-body,
.contact-info-card .card-body {
    padding: 2rem;
}
.card-title   { font-weight: 600; color: var(--dark, #1a1a2e); margin-bottom: .25rem; }
.card-subtitle{ color: #888; margin-bottom: 0; font-size: .9rem; }

/* ---- Formulaire ---- */
.form-floating .form-control {
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}
.form-floating .form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 .2rem rgba(23,128,214,.12);
}

/* ---- Méthodes de contact ---- */
.contact-methods { display: flex; flex-direction: column; gap: 1.25rem; }
.contact-method  { display: flex; align-items: flex-start; gap: 1rem; }
.method-icon {
    width: 46px; height: 46px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.2rem; flex-shrink: 0;
}
.method-content h6 { font-weight: 600; color: var(--dark, #1a1a2e); margin-bottom: .2rem; }
.method-content p  { color: #666; }

/* ---- Horaires ---- */
.hours-list { display: flex; flex-direction: column; gap: .5rem; }
.hour-item  {
    display: flex; justify-content: space-between; align-items: center;
    padding: .5rem 0;
    border-bottom: 1px solid rgba(0,0,0,.05);
}
.hour-item:last-child { border-bottom: none; }

/* ---- Réseaux sociaux ---- */
.social-link {
    width: 40px; height: 40px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: white; text-decoration: none;
    transition: transform .2s ease, opacity .2s;
}
.social-link:hover       { transform: translateY(-3px); opacity: .9; }
.social-link.facebook    { background: #3b5998; }
.social-link.instagram   { background: linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888); }
.social-link.twitter     { background: #000; }
.social-link.whatsapp    { background: #25d366; }

/* ---- Carte ---- */
.map-overlay {
    position: absolute; top: 10px; right: 10px;
}

/* ---- FAQ ---- */
.faq-section {
    background: white; border-radius: 12px; padding: 3rem;
    box-shadow: 0 4px 20px rgba(0,0,0,.08);
}
.accordion-button { font-weight: 500; padding: 1.25rem 1.5rem; }
.accordion-button:not(.collapsed) { background: var(--primary, #1780d6); color: white; }
.accordion-button:not(.collapsed)::after { filter: invert(1); }
.accordion-body   { padding: 1.25rem 1.5rem; line-height: 1.7; }

/* ---- Responsive ---- */
@media (max-width: 768px) {
    .contact-hero { padding: 4rem 0 2rem; }
    .contact-form-card .card-body,
    .contact-info-card .card-body { padding: 1.25rem; }
    .faq-section { padding: 1.5rem; }
    .map-overlay { position: static; text-align: center; padding: .75rem; }
}
@media (max-width: 576px) {
    .contact-method { flex-direction: column; align-items: center; text-align: center; }
    .hour-item { flex-direction: column; text-align: center; gap: .25rem; }
}
</style>

@endsection