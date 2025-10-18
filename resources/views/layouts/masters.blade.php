@extends('layouts.slaves')

@section('title', 'Accueil')

@section('content')
<!-- Hero Section améliorée -->
<section @class(['hero-section', 'mb-5'])>
    <div @class(['hero-content'])>
        <h1 @class(['hero-title'])>Découvrez les Dernières Tendances</h1>
        <p @class(['hero-subtitle'])>Explorez notre nouvelle collection de mode élégante et abordable</p>
        <div @class(['hero-buttons'])>
            <a href="{{ route('shop') }}" @class(['btn', 'btn-primary'])>
                <i @class(['bi', 'bi-bag', 'me-2'])></i> Acheter Maintenant
            </a>
            <a href="#new-arrivals" @class(['btn', 'btn-outline-light'])>
                <i @class(['bi', 'bi-arrow-down', 'me-2'])></i> Découvrir
            </a>
        </div>
    </div>
    
    <!-- Indicateurs de statistiques -->
    <div @class(['hero-stats'])>
        <div @class(['stat-item'])>
            <div @class(['stat-number'])>500+</div>
            <div @class(['stat-label'])>Produits</div>
        </div>
        <div @class(['stat-item'])>
            <div @class(['stat-number'])>95%</div>
            <div @class(['stat-label'])>Clients Satisfaits</div>
        </div>
        <div @class(['stat-item'])>
            <div @class(['stat-number'])>24h</div>
            <div @class(['stat-label'])>Livraison Express</div>
        </div>
    </div>
</section>

<div @class(['container'])>
    <div @class(['row'])>
        <!-- Sidebar améliorée -->
        <div @class(['col-lg-3', 'col-md-4', 'mb-4'])>
            <div @class(['sidebar'])>
                <h5 @class(['sidebar-title'])>
                    <i @class(['bi', 'bi-list-ul'])></i>
                    Catégories
                </h5>
                
                <div @class(['filter-section'])>
                    <div @class(['filter-options'])>
                        @foreach ($categories as $categorie)
                        <div @class(['filter-option'])>
                            <a href="/shop/{{ $categorie->slug }}" @class(['d-flex', 'align-items-center', 'justify-content-between', 'text-decoration-none', 'text-dark'])>
                                <span>{{ $categorie->name }}</span>
                                <i @class(['bi', 'bi-chevron-right', 'text-muted'])></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Bannière promotionnelle -->
                <div @class(['promo-banner', 'mt-4', 'p-3', 'rounded', 'text-white', 'text-center']) 
                     style="background: linear-gradient(135deg, var(--primary), var(--accent));">
                    <i @class(['bi', 'bi-lightning', 'display-6', 'mb-2'])></i>
                    <h6 @class(['mb-2'])>Soldes d'Été</h6>
                    <p @class(['small', 'mb-2'])>Jusqu'à -50% sur toute la collection</p>
                    <a href="{{ route('shop') }}" @class(['btn', 'btn-sm', 'btn-light'])>Profiter</a>
                </div>
            </div>

            <!-- Services -->
            <div @class(['sidebar', 'mt-4'])>
                <h5 @class(['sidebar-title'])>
                    <i @class(['bi', 'bi-shield-check'])></i>
                    Nos Services
                </h5>
                <div @class(['service-list'])>
                    <div @class(['service-item', 'd-flex', 'align-items-center', 'mb-3'])>
                        <i @class(['bi', 'bi-truck', 'text-primary', 'me-3'])></i>
                        <div>
                            <div @class(['fw-semibold'])>Livraison Rapide</div>
                            <small @class(['text-muted'])>Sous 24-48h</small>
                        </div>
                    </div>
                    <div @class(['service-item', 'd-flex', 'align-items-center', 'mb-3'])>
                        <i @class(['bi', 'bi-arrow-left-right', 'text-primary', 'me-3'])></i>
                        <div>
                            <div @class(['fw-semibold'])>Retours Faciles</div>
                            <small @class(['text-muted'])>30 jours pour changer d'avis</small>
                        </div>
                    </div>
                    <div @class(['service-item', 'd-flex', 'align-items-center'])>
                        <i @class(['bi', 'bi-lock', 'text-primary', 'me-3'])></i>
                        <div>
                            <div @class(['fw-semibold'])>Paiement Sécurisé</div>
                            <small @class(['text-muted'])>Cryptage SSL</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu principal amélioré -->
        <div @class(['col-lg-9', 'col-md-8'])>
            <!-- Section Nouveautés -->
            <section id="new-arrivals" @class(['mb-5'])>
                <div @class(['section-title'])>
                    <h2>Nouveautés</h2>
                    <p @class(['text-muted'])>Découvrez les dernières arrivées</p>
                </div>
                
                <div @class(['products-grid'])>
                    @foreach ($productsThisWeeks as $productsThisWeek)
                    <div @class(['product-card', 'fade-in'])>
                        <div @class(['product-image'])>
                            <img src="{{ $productsThisWeek->image ? asset('storage/' . $productsThisWeek->image) : asset('images/default-product.jpg') }}" 
                                 alt="{{ $productsThisWeek->name }}">
                            <span @class(['product-badge', 'new'])>Nouveau</span>
                            <div @class(['product-actions'])>
                                <button @class(['action-btn']) title="Ajouter aux favoris">
                                    <i @class(['bi', 'bi-heart'])></i>
                                </button>
                                <button @class(['action-btn']) title="Vue rapide">
                                    <i @class(['bi', 'bi-eye'])></i>
                                </button>
                            </div>
                        </div>
                        <div @class(['product-info'])>
                            <div @class(['product-category'])>{{ $productsThisWeek->category->name ?? 'Nouveauté' }}</div>
                            <h3 @class(['product-title'])>{{ $productsThisWeek->name }}</h3>
                            
                            <div @class(['product-rating'])>
                                <div @class(['stars'])>
                                    <i @class(['bi', 'bi-star-fill'])></i>
                                    <i @class(['bi', 'bi-star-fill'])></i>
                                    <i @class(['bi', 'bi-star-fill'])></i>
                                    <i @class(['bi', 'bi-star-fill'])></i>
                                    <i @class(['bi', 'bi-star'])></i>
                                </div>
                                <span @class(['rating-count'])>({{ rand(5, 20) }})</span>
                            </div>
                            
                            <div @class(['product-price'])>
                                <span @class(['current-price'])>{{ number_format($productsThisWeek->price, 0, ',', ' ') }} fcfa</span>
                            </div>
                            
                            <div @class(['d-flex', 'gap-2', 'mt-3'])>
                                <a href="{{ route('shop.detail', $productsThisWeek->id) }}" 
                                   @class(['btn', 'btn-outline-primary', 'flex-fill', 'd-flex', 'align-items-center', 'justify-content-center', 'gap-2'])>
                                    <i @class(['bi', 'bi-eye'])></i>
                                    <span>Voir détails</span>
                                </a>
                                <button @class(['btn', 'btn-primary', 'add-to-cart']) data-product-id="{{ $productsThisWeek->id }}">
                                    <i @class(['bi', 'bi-cart-plus'])></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div @class(['text-center', 'mt-4'])>
                    <a href="{{ route('shop') }}" @class(['btn', 'btn-outline-primary'])>
                        Voir tous les nouveaux produits <i @class(['bi', 'bi-arrow-right', 'ms-2'])></i>
                    </a>
                </div>
            </section>

            <!-- Section Meilleures Ventes -->
            <section @class(['mb-5', 'py-5', 'bg-light', 'rounded'])>
                <div @class(['container'])>
                    <div @class(['section-title'])>
                        <h2>Meilleures Ventes</h2>
                        <p @class(['text-muted'])>Les produits préférés de nos clients</p>
                    </div>
                    
                    <div @class(['products-grid'])>
                        @foreach ($popularProducts as $popularProduct)
                        <div @class(['product-card', 'fade-in'])>
                            <div @class(['product-image'])>
                                <img src="{{ $popularProduct->image ? asset('storage/' . $popularProduct->image) : asset('images/default-product.jpg') }}" 
                                     alt="{{ $popularProduct->name }}">
                                <span @class(['product-badge'])>Populaire</span>
                                <div @class(['product-actions'])>
                                    <button @class(['action-btn']) title="Ajouter aux favoris">
                                        <i @class(['bi', 'bi-heart'])></i>
                                    </button>
                                    <button @class(['action-btn']) title="Vue rapide">
                                        <i @class(['bi', 'bi-eye'])></i>
                                    </button>
                                </div>
                            </div>
                            <div @class(['product-info'])>
                                <div @class(['product-category'])>{{ $popularProduct->category->name ?? 'Best-seller' }}</div>
                                <h3 @class(['product-title'])>{{ $popularProduct->name }}</h3>
                                
                                <div @class(['product-rating'])>
                                    <div @class(['stars'])>
                                        <i @class(['bi', 'bi-star-fill'])></i>
                                        <i @class(['bi', 'bi-star-fill'])></i>
                                        <i @class(['bi', 'bi-star-fill'])></i>
                                        <i @class(['bi', 'bi-star-fill'])></i>
                                        <i @class(['bi', 'bi-star-half'])></i>
                                    </div>
                                    <span @class(['rating-count'])>({{ rand(20, 100) }})</span>
                                </div>
                                
                                <div @class(['product-price'])>
                                    <span @class(['current-price'])>{{ number_format($popularProduct->price, 0, ',', ' ') }} fcfa</span>
                                </div>
                                
                                <div @class(['d-flex', 'gap-2', 'mt-3'])>
                                    <a href="{{ route('shop.detail', $popularProduct->id) }}" 
                                       @class(['btn', 'btn-outline-primary', 'flex-fill', 'd-flex', 'align-items-center', 'justify-content-center', 'gap-2'])>
                                        <i @class(['bi', 'bi-eye'])></i>
                                        <span>Voir détails</span>
                                    </a>
                                    <button @class(['btn', 'btn-primary', 'add-to-cart']) data-product-id="{{ $popularProduct->id }}">
                                        <i @class(['bi', 'bi-cart-plus'])></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Section Catégories améliorée -->
            <section @class(['mb-5'])>
                <div @class(['section-title'])>
                    <h2>Parcourir par Catégorie</h2>
                    <p @class(['text-muted'])>Trouvez ce qui vous correspond</p>
                </div>
                
                <div @class(['categories-grid'])>
                    @foreach ($categories as $categorie)
                    <a href="/shop/{{ $categorie->slug }}" @class(['category-card', 'text-decoration-none'])>
                        <div @class(['category-image'])>
                            <img src="{{ asset('storage/' . $categorie->image) }}" alt="{{ $categorie->name }}">
                            <div @class(['category-overlay'])>
                                <h5 @class(['category-title'])>{{ $categorie->name }}</h5>
                                <span @class(['category-link'])>Explorer <i @class(['bi', 'bi-arrow-right'])></i></span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </section>

            <!-- Section Avantages -->
            <section @class(['mb-5'])>
                <div @class(['row', 'g-4'])>
                    <div @class(['col-md-4'])>
                        <div @class(['feature-card', 'text-center', 'p-4', 'rounded'])>
                            <i @class(['bi', 'bi-star', 'feature-icon'])></i>
                            <h5 @class(['mt-3'])>Qualité Premium</h5>
                            <p @class(['text-muted'])>Des matériaux de haute qualité pour un confort optimal</p>
                        </div>
                    </div>
                    <div @class(['col-md-4'])>
                        <div @class(['feature-card', 'text-center', 'p-4', 'rounded'])>
                            <i @class(['bi', 'bi-truck', 'feature-icon'])></i>
                            <h5 @class(['mt-3'])>Livraison Rapide</h5>
                            <p @class(['text-muted'])>Expédition sous 24h et livraison gratuite dès 50,000 fcfa</p>
                        </div>
                    </div>
                    <div @class(['col-md-4'])>
                        <div @class(['feature-card', 'text-center', 'p-4', 'rounded'])>
                            <i @class(['bi', 'bi-headset', 'feature-icon'])></i>
                            <h5 @class(['mt-3'])>Support 7j/7</h5>
                            <p @class(['text-muted'])>Notre équipe est là pour vous accompagner</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- Scripts pour la page d'accueil -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des statistiques du hero
    function animateStats() {
        const stats = document.querySelectorAll('.stat-number');
        stats.forEach(stat => {
            const target = parseInt(stat.textContent);
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    stat.textContent = target + (stat.textContent.includes('+') ? '+' : '');
                    clearInterval(timer);
                } else {
                    stat.textContent = Math.floor(current) + (stat.textContent.includes('+') ? '+' : '');
                }
            }, 40);
        });
    }

    // Observer pour l'animation des statistiques
    const heroObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateStats();
                heroObserver.unobserve(entry.target);
            }
        });
    });

    const heroSection = document.querySelector('.hero-section');
    if (heroSection) {
        heroObserver.observe(heroSection);
    }

    // Animation des cartes produits
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

    document.querySelectorAll('.product-card, .feature-card').forEach(card => {
        observer.observe(card);
    });

    // Ajout au panier
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const button = this;
            
            // Animation d'ajout
            button.innerHTML = '<i @class(['bi', 'bi-check-lg'])></i>';
            button.classList.add('btn-success');
            button.classList.remove('btn-primary');
            
            // Réinitialiser après 2 secondes
            setTimeout(() => {
                button.innerHTML = '<i @class(['bi', 'bi-cart-plus'])></i>';
                button.classList.remove('btn-success');
                button.classList.add('btn-primary');
            }, 2000);
            
            // Ici, vous pouvez ajouter l'appel AJAX pour ajouter au panier
            console.log('Produit ajouté au panier:', productId);
        });
    });

    // Smooth scroll pour les liens d'ancrage
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>

<style>
/* Styles spécifiques à la page d'accueil */
.hero-stats {
    position: absolute;
    bottom: 30px;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    gap: 40px;
    z-index: 2;
}

.stat-item {
    text-align: center;
    color: white;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.stat-number {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.875rem;
    opacity: 0.9;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.category-card {
    background: white;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
    display: block;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.category-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.category-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.category-card:hover .category-image img {
    transform: scale(1.1);
}

.category-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
    color: white;
    padding: 20px;
    transform: translateY(10px);
    transition: var(--transition);
}

.category-card:hover .category-overlay {
    transform: translateY(0);
}

.category-title {
    font-weight: 600;
    margin-bottom: 5px;
}

.category-link {
    font-size: 0.875rem;
    opacity: 0;
    transition: var(--transition);
}

.category-card:hover .category-link {
    opacity: 1;
}

.feature-card {
    background: white;
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.feature-icon {
    font-size: 2.5rem;
    color: var(--primary);
}

.promo-banner {
    transition: var(--transition);
}

.promo-banner:hover {
    transform: scale(1.02);
}

.service-list {
    padding: 0;
}

.service-item {
    padding: 10px 0;
    border-bottom: 1px solid var(--gray-light);
}

.service-item:last-child {
    border-bottom: none;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-stats {
        position: static;
        margin-top: 30px;
        gap: 20px;
    }
    
    .stat-number {
        font-size: 1.4rem;
    }
    
    .categories-grid {
        grid-template-columns: 1fr;
    }
    
    .feature-card {
        margin-bottom: 20px;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 2.2rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .hero-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .hero-buttons .btn {
        width: 100%;
        max-width: 250px;
        margin-bottom: 10px;
    }
}
</style>
@endsection