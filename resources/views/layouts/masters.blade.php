@extends('layouts.slaves')

@section('title', 'Accueil')

@section('content')

<!-- Hero Section -->
<section class="hero-section mb-5">
    <div class="hero-content">
        <h1 class="hero-title">Découvrez les Dernières Tendances</h1>
        <p class="hero-subtitle">Explorez notre nouvelle collection de mode élégante et abordable</p>
        <div class="hero-buttons">
            <a href="{{ route('shop') }}" class="btn btn-primary">
                <i class="bi bi-bag me-2"></i> Acheter Maintenant
            </a>
            <a href="#new-arrivals" class="btn btn-outline-light">
                <i class="bi bi-arrow-down me-2"></i> Découvrir
            </a>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="hero-stats">
        <div class="stat-item">
            <div class="stat-number">300+</div>
            <div class="stat-label">Produits</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">95%</div>
            <div class="stat-label">Clients Satisfaits</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">24h</div>
            <div class="stat-label">Livraison Express</div>
        </div>
    </div>
</section>

<div class="container">
    <div class="row">

        <!-- ===================== SIDEBAR ===================== -->
        <div class="col-lg-3 col-md-4 mb-4">

            <!-- Catégories -->
            <div class="sidebar">
                <h5 class="sidebar-title">
                    <i class="bi bi-list-ul"></i>
                    Catégories
                </h5>

                <div class="filter-section">
                    @forelse($categories as $category)
                    <div class="category-item">

                        {{-- Bouton de catégorie principale --}}
                        <button
                            class="category-btn {{ $category->sousCat->isNotEmpty() ? 'has-children' : '' }}"
                            type="button"
                            @if($category->sousCat->isNotEmpty())
                                data-bs-toggle="collapse"
                                data-bs-target="#cat{{ $category->id }}"
                                aria-expanded="false"
                                aria-controls="cat{{ $category->id }}"
                            @endif
                        >
                            {{-- Miniature image de catégorie (optionnelle) --}}
                            @if($category->image)
                                <img
                                    src="{{ asset('public/storage/' . $category->image) }}"
                                    alt="{{ $category->name }}"
                                    class="category-thumb"
                                >
                            @else
                                <span class="category-icon-placeholder">
                                    <i class="bi bi-tag"></i>
                                </span>
                            @endif

                            <span class="category-name">{{ $category->name }}</span>

                            @if($category->sousCat->isNotEmpty())
                                <i class="bi bi-chevron-right category-arrow"></i>
                            @endif
                        </button>

                        {{-- Sous-catégories (collapse Bootstrap) --}}
                        @if($category->sousCat->isNotEmpty())
                        <div class="collapse sub-categories" id="cat{{ $category->id }}">
                            <ul class="sub-list">
                                @foreach($category->sousCat as $subCategory)
                                <li class="sub-item">
                                    <a
                                        href="{{ route('shop', ['category' => $category->slug, 'sub' => $subCategory->slug]) }}"
                                        class="sub-link"
                                    >
                                        <i class="bi bi-dot"></i>
                                        {{ $subCategory->name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                    </div>
                    @empty
                    <p class="text-muted small px-2">Aucune catégorie disponible</p>
                    @endforelse
                </div>
            </div>

            <!-- Services -->
            <div class="sidebar mt-4">
                <h5 class="sidebar-title">
                    <i class="bi bi-shield-check"></i>
                    Nos Services
                </h5>
                <div class="service-list">
                    <div class="service-item d-flex align-items-center mb-3">
                        <i class="bi bi-truck text-primary me-3"></i>
                        <div>
                            <div class="fw-semibold">Livraison Rapide</div>
                        </div>
                    </div>
                    <div class="service-item d-flex align-items-center mb-3">
                        <i class="bi bi-shield-check text-primary me-3"></i>
                        <div>
                            <div class="fw-semibold">Paiement Sécurisé</div>
                        </div>
                    </div>
                    <div class="service-item d-flex align-items-center">
                        <i class="bi bi-headset text-primary me-3"></i>
                        <div>
                            <div class="fw-semibold">Support 7j/7</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        {{-- /Sidebar --}}


        <!-- ===================== CONTENU PRINCIPAL ===================== -->
        <div class="col-lg-9 col-md-8">

            <!-- Section Nouveautés -->
            <section id="new-arrivals" class="mb-5">
                <div class="section-title">
                    <h2>Nouveautés</h2>
                    <p class="text-muted">Découvrez les dernières arrivées</p>
                </div>

                <div class="products-grid">
                    @foreach ($productsThisWeeks as $productsThisWeek)
                    <div class="product-card fade-in">
                        <div class="product-image">
                            <img
                                src="{{ $productsThisWeek->image ? asset('public/storage/' . $productsThisWeek->image) : asset('images/default-product.jpg') }}"
                                alt="{{ $productsThisWeek->name }}"
                            >
                            <span class="product-badge new">Nouveau</span>
                            <div class="product-actions">
                                <button class="action-btn" title="Ajouter aux favoris">
                                    <i class="bi bi-heart"></i>
                                </button>
                                <button class="action-btn" title="Vue rapide">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-category">{{ $productsThisWeek->category->name ?? 'Nouveauté' }}</div>
                            <h3 class="product-title">{{ $productsThisWeek->name }}</h3>

                            <div class="product-rating">
                                <div class="stars">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star"></i>
                                </div>
                                <span class="rating-count">({{ rand(5, 20) }})</span>
                            </div>

                            <div class="product-price">
                                <span class="current-price">{{ number_format($productsThisWeek->price, 0, ',', ' ') }} fcfa</span>
                            </div>

                            <div class="d-flex gap-2 mt-3">
                                <a href="{{ route('shop.detail', $productsThisWeek->id) }}"
                                   class="btn btn-outline-primary flex-fill d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-eye"></i>
                                    <span>Voir détails</span>
                                </a>
                                <button class="btn btn-primary add-to-cart" data-product-id="{{ $productsThisWeek->id }}">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('shop') }}" class="btn btn-outline-primary">
                        Voir tous les nouveaux produits <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </section>

            <!-- Section Meilleures Ventes -->
            <section class="mb-5 py-5 bg-light rounded">
                <div class="container">
                    <div class="section-title">
                        <h2>Meilleures Ventes</h2>
                        <p class="text-muted">Les produits préférés de nos clients</p>
                    </div>

                    <div class="products-grid">
                        @foreach ($popularProducts as $popularProduct)
                        <div class="product-card fade-in">
                            <div class="product-image">
                                <img
                                    src="{{ $popularProduct->image ? asset('storage/' . $popularProduct->image) : asset('images/default-product.jpg') }}"
                                    alt="{{ $popularProduct->name }}"
                                >
                                <span class="product-badge">Populaire</span>
                                <div class="product-actions">
                                    <button class="action-btn" title="Ajouter aux favoris">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                    <button class="action-btn" title="Vue rapide">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="product-info">
                                <div class="product-category">{{ $popularProduct->category->name ?? 'Best-seller' }}</div>
                                <h3 class="product-title">{{ $popularProduct->name }}</h3>

                                <div class="product-rating">
                                    <div class="stars">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-half"></i>
                                    </div>
                                    <span class="rating-count">({{ rand(20, 100) }})</span>
                                </div>

                                <div class="product-price">
                                    <span class="current-price">{{ number_format($popularProduct->price, 0, ',', ' ') }} fcfa</span>
                                </div>

                                <div class="d-flex gap-2 mt-3">
                                    <a href="{{ route('shop.detail', $popularProduct->id) }}"
                                       class="btn btn-outline-primary flex-fill d-flex align-items-center justify-content-center gap-2">
                                        <i class="bi bi-eye"></i>
                                        <span>Voir détails</span>
                                    </a>
                                    <button class="btn btn-primary add-to-cart" data-product-id="{{ $popularProduct->id }}">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Section Catégories -->
            <section class="mb-5">
                <div class="section-title">
                    <h2>Parcourir par Catégorie</h2>
                    <p class="text-muted">Trouvez ce qui vous correspond</p>
                </div>

                <div class="categories-grid">
                    @foreach ($categories as $categorie)
                   <a href="{{ route('shop.category', $categorie->slug) }}" class="category-card text-decoration-none">
                        <div class="category-image">
                            <img src="{{ asset('storage/' . $categorie->image) }}" alt="{{ $categorie->name }}">
                            <div class="category-overlay">
                                <h5 class="category-title">{{ $categorie->name }}</h5>
                                <span class="category-link">Explorer <i class="bi bi-arrow-right"></i></span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </section>

            <!-- Section Avantages -->
            <section class="mb-5">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="feature-card text-center p-4 rounded">
                            <i class="bi bi-star feature-icon"></i>
                            <h5 class="mt-3">Qualité de service</h5>
                            <p class="text-muted">Des matériaux de haute qualité pour un confort optimal</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-card text-center p-4 rounded">
                            <i class="bi bi-headset feature-icon"></i>
                            <h5 class="mt-3">Support 7j/7</h5>
                            <p class="text-muted">Notre équipe est là pour vous accompagner</p>
                        </div>
                    </div>
                </div>
            </section>

        </div>
        {{-- /Contenu principal --}}

    </div>
</div>


<!-- ===================== SCRIPTS ===================== -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    // --- Rotation de la flèche des catégories au collapse Bootstrap ---
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const arrow = this.querySelector('.category-arrow');
            if (!arrow) return;
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            // Bootstrap met à jour aria-expanded après le clic, on anticipe
            arrow.style.transform = isExpanded ? 'rotate(0deg)' : 'rotate(90deg)';
        });

        // Synchroniser au chargement si un collapse est déjà ouvert
        const targetId = btn.getAttribute('data-bs-target');
        if (targetId) {
            const collapseEl = document.querySelector(targetId);
            if (collapseEl) {
                collapseEl.addEventListener('show.bs.collapse', function () {
                    btn.querySelector('.category-arrow') &&
                        (btn.querySelector('.category-arrow').style.transform = 'rotate(90deg)');
                });
                collapseEl.addEventListener('hide.bs.collapse', function () {
                    btn.querySelector('.category-arrow') &&
                        (btn.querySelector('.category-arrow').style.transform = 'rotate(0deg)');
                });
            }
        }
    });

    // --- Animation des statistiques hero ---
    function animateStats() {
        document.querySelectorAll('.stat-number').forEach(function (stat) {
            const raw = stat.textContent.trim();
            const hasPlus = raw.includes('+');
            const hasPercent = raw.includes('%');
            const hasH = raw.includes('h');
            const target = parseInt(raw);
            if (isNaN(target)) return;
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(function () {
                current += increment;
                if (current >= target) {
                    clearInterval(timer);
                    stat.textContent = target + (hasPlus ? '+' : hasPercent ? '%' : hasH ? 'h' : '');
                } else {
                    stat.textContent = Math.floor(current) + (hasPlus ? '+' : hasPercent ? '%' : hasH ? 'h' : '');
                }
            }, 40);
        });
    }

    const heroSection = document.querySelector('.hero-section');
    if (heroSection) {
        const heroObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    animateStats();
                    heroObserver.unobserve(entry.target);
                }
            });
        });
        heroObserver.observe(heroSection);
    }

    // --- Animation fade-in des cartes produits ---
    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    document.querySelectorAll('.product-card, .feature-card').forEach(function (card) {
        observer.observe(card);
    });

    // --- Ajout au panier ---
    document.querySelectorAll('.add-to-cart').forEach(function (button) {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');
            this.innerHTML = '<i class="bi bi-check-lg"></i>';
            this.classList.add('btn-success');
            this.classList.remove('btn-primary');

            setTimeout(() => {
                this.innerHTML = '<i class="bi bi-cart-plus"></i>';
                this.classList.remove('btn-success');
                this.classList.add('btn-primary');
            }, 2000);

            console.log('Produit ajouté au panier:', productId);
            // TODO: remplacer par un appel AJAX
        });
    });

    // --- Smooth scroll ancres ---
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

});
</script>


<!-- ===================== STYLES ===================== -->
<style>
/* ---- Hero stats ---- */
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
    text-shadow: 0 2px 4px rgba(0,0,0,.3);
}
.stat-number {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 5px;
}
.stat-label {
    font-size: .875rem;
    opacity: .9;
}

/* ---- Sidebar catégories ---- */
.category-item {
    margin-bottom: 2px;
}

.category-btn {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 12px;
    border: none;
    background: transparent;
    border-radius: var(--border-radius, 8px);
    cursor: pointer;
    font-size: .9rem;
    color: var(--dark, #1a1a2e);
    text-align: left;
    transition: background .2s, color .2s;
}
.category-btn:hover,
.category-btn[aria-expanded="true"] {
    background: var(--gray-light, #f0f0f0);
    color: var(--primary, #6c63ff);
}

.category-thumb {
    width: 28px;
    height: 28px;
    object-fit: cover;
    border-radius: 4px;
    flex-shrink: 0;
}
.category-icon-placeholder {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--gray-light, #f0f0f0);
    border-radius: 4px;
    flex-shrink: 0;
    font-size: .85rem;
    color: var(--secondary, #888);
}

.category-name {
    flex: 1;
    font-weight: 500;
}

.category-arrow {
    font-size: .75rem;
    color: var(--secondary, #888);
    transition: transform .25s ease;
    flex-shrink: 0;
}

/* ---- Sous-catégories ---- */
.sub-list {
    list-style: none;
    margin: 0;
    padding: 4px 0 8px 36px;
}
.sub-item {
    margin-bottom: 1px;
}
.sub-link {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 6px 10px;
    font-size: .85rem;
    color: var(--secondary, #666);
    text-decoration: none;
    border-radius: var(--border-radius, 8px);
    transition: background .15s, color .15s, padding-left .15s;
}
.sub-link:hover {
    background: var(--gray-light, #f0f0f0);
    color: var(--primary, #6c63ff);
    padding-left: 14px;
}
.sub-link .bi-dot {
    font-size: 1.2rem;
    line-height: 1;
    flex-shrink: 0;
}

/* ---- Grille catégories ---- */
.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}
.category-card {
    background: white;
    border-radius: var(--border-radius, 8px);
    overflow: hidden;
    box-shadow: var(--shadow, 0 2px 8px rgba(0,0,0,.08));
    transition: var(--transition, .3s);
    display: block;
}
.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,.15);
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
    transition: var(--transition, .3s);
}
.category-card:hover .category-image img {
    transform: scale(1.1);
}
.category-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,.8));
    color: white;
    padding: 20px;
    transform: translateY(10px);
    transition: var(--transition, .3s);
}
.category-card:hover .category-overlay {
    transform: translateY(0);
}
.category-title {
    font-weight: 600;
    margin-bottom: 5px;
}
.category-link {
    font-size: .875rem;
    opacity: 0;
    transition: var(--transition, .3s);
}
.category-card:hover .category-link {
    opacity: 1;
}

/* ---- Feature cards ---- */
.feature-card {
    background: white;
    box-shadow: var(--shadow, 0 2px 8px rgba(0,0,0,.08));
    transition: var(--transition, .3s);
}
.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,.1);
}
.feature-icon {
    font-size: 2.5rem;
    color: var(--primary, #6c63ff);
}

/* ---- Services sidebar ---- */
.service-list { padding: 0; }
.service-item {
    padding: 10px 0;
    border-bottom: 1px solid var(--gray-light, #f0f0f0);
}
.service-item:last-child { border-bottom: none; }

/* ---- Responsive ---- */
@media (max-width: 768px) {
    .hero-stats {
        position: static;
        margin-top: 30px;
        gap: 20px;
    }
    .stat-number { font-size: 1.4rem; }
    .categories-grid { grid-template-columns: 1fr; }
    .feature-card { margin-bottom: 20px; }
}
@media (max-width: 576px) {
    .hero-title { font-size: 2.2rem; }
    .hero-subtitle { font-size: 1.1rem; }
    .hero-buttons { flex-direction: column; align-items: center; }
    .hero-buttons .btn { width: 100%; max-width: 250px; margin-bottom: 10px; }
}
</style>

@endsection