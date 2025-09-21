@extends('layouts.slaves')

@section('content')
    <!-- Contenu Principal -->
    <div class="container-fluid mt-3 pt-5">
        <div class="row">
            <!-- Contenu Contact -->
            <div class="col-12 contact-section">
                <h2>Nous Contacter</h2>
                <p>Vous avez une question ou besoin d’assistance ? Remplissez le formulaire ci-dessous ou contactez-nous
                    directement.</p>
                <div class="row">
                    <div class="col-md-6">
                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom Complet</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse E-mail</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Envoyer</button>
                        </form>
                    </div>
                    <div class="col-md-6 contact-info">
                        <h5>Nos Coordonnées</h5>
                        <p><i class="bi bi-geo-alt"></i> 123 Rue de la Mode, 75001 Paris, France</p>
                        <p><i class="bi bi-envelope"></i> support@bkassoua.com</p>
                        <p><i class="bi bi-phone"></i> +33 1 23 45 67 89</p>
                        <h5>Nos Horaires</h5>
                        <p>Lundi - Vendredi : 9h00 - 18h00<br>Samedi : 10h00 - 16h00</p>
                        <h5>Nous Trouver</h5>
                        <div class="map-placeholder">
                            <p class="text-muted">[Insérer une carte Google Maps ici]</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
