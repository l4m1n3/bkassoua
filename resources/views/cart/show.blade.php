@extends('layouts.slaves')

@section('title', 'Panier - Bkassoua')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Contenu principal du panier -->
        <div class="col-lg-12 col-md-9">
            <!-- En-tête du panier -->
            <div class="cart-header mb-4">
                <h1 class="page-title mb-2">Votre Panier</h1>
                <p class="page-subtitle text-muted">
                    {{ $carts->count() }} article{{ $carts->count() > 1 ? 's' : '' }} dans votre panier
                </p>
            </div>

            @if($carts->isEmpty())
                <!-- État vide du panier -->
                <div class="empty-cart text-center py-5">
                    <i class="bi bi-cart-x display-1 text-muted"></i>
                    <h3 class="mt-3 text-muted">Votre panier est vide</h3>
                    <p class="text-muted mb-4">Découvrez nos produits et ajoutez-les à votre panier.</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary">
                        <i class="bi bi-bag me-2"></i>Continuer mes achats
                    </a>
                </div>
            @else
                <!-- Liste des articles -->
                <div class="cart-items mb-4">
                    @foreach($carts as $cart)
                    <div class="cart-item card border-0 shadow-sm mb-3" data-cart-id="{{ $cart->id }}">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img src="{{ $cart->product->image ? asset('storage/' . $cart->product->image) : asset('images/default-product.jpg') }}" 
                                         alt="{{ $cart->product->name }}" 
                                         class="cart-item-image img-fluid rounded">
                                </div>
                                <div class="col-md-4">
                                    <h5 class="cart-item-title mb-1">{{ $cart->product->name }}</h5>
                                    <p class="cart-item-category text-muted small mb-0">
                                        {{ $cart->product->category->name ?? 'Non catégorisé' }}
                                    </p>
                                    <div class="cart-item-rating mt-1">
                                        <div class="stars small">
                                            <i class="bi bi-star-fill text-warning"></i>
                                            <i class="bi bi-star-fill text-warning"></i>
                                            <i class="bi bi-star-fill text-warning"></i>
                                            <i class="bi bi-star-fill text-warning"></i>
                                            <i class="bi bi-star text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="cart-item-price text-primary fw-bold" data-unit-price="{{ $cart->product->price }}">
                                        {{ number_format($cart->product->price, 0, ',', ' ') }} fcfa
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="quantity-selector">
                                        <div class="input-group quantity-input" style="max-width: 120px;">
                                            <button class="btn btn-outline-secondary decrease-btn" type="button" data-id="{{ $cart->id }}">-</button>
                                            <input type="number" class="form-control text-center quantity-input-field" 
                                                   id="quantity-{{ $cart->id }}" 
                                                   value="{{ $cart->quantity }}" 
                                                   min="1" 
                                                   max="{{ $cart->product->stock_quantity }}"
                                                   data-id="{{ $cart->id }}">
                                            <button class="btn btn-outline-secondary increase-btn" type="button" data-id="{{ $cart->id }}">+</button>
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            {{ $cart->product->stock_quantity }} disponible(s)
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="cart-item-total text-end">
                                        <div class="fw-bold text-primary mb-2" id="total-{{ $cart->id }}" data-current-total="{{ $cart->product->price * $cart->quantity }}">
                                            {{ number_format($cart->product->price * $cart->quantity, 0, ',', ' ') }} fcfa
                                        </div>
                                        <form action="{{ route('cart.remove', $cart->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                    onclick="return confirm('Supprimer cet article du panier ?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Résumé de commande -->
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Code promo -->
                        <div class="promo-code card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i class="bi bi-tag me-2"></i>Code promo
                                </h5>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Entrez votre code promo">
                                    <button class="btn btn-outline-primary" type="button">Appliquer</button>
                                </div>
                            </div>
                        </div>

                        <!-- Garanties -->
                        <div class="guarantees card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Garanties Bkassoua</h5>
                                <div class="row text-center">
                                    <div class="col-4">
                                        <i class="bi bi-truck text-primary display-6 mb-2"></i>
                                        <h6>Livraison Rapide</h6>
                                        <small class="text-muted">Sous 24-48h</small>
                                    </div>
                                    <div class="col-4">
                                        <i class="bi bi-arrow-left-right text-primary display-6 mb-2"></i>
                                        <h6>Retours Faciles</h6>
                                        <small class="text-muted">30 jours</small>
                                    </div>
                                    <div class="col-4">
                                        <i class="bi bi-shield-check text-primary display-6 mb-2"></i>
                                        <h6>Paiement Sécurisé</h6>
                                        <small class="text-muted">Cryptage SSL</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="order-summary card border-0 shadow-sm sticky-top" style="top: 100px;">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Résumé de la commande</h5>
                                
                                <div class="summary-item d-flex justify-content-between mb-2">
                                    <span>Sous-total (<span id="total-items-count">{{ $carts->count() }}</span> article{{ $carts->count() > 1 ? 's' : '' }})</span>
                                    <span id="subtotal" data-current-subtotal="{{ $total }}">{{ number_format($total, 0, ',', ' ') }} fcfa</span>
                                </div>
                                
                                <div class="summary-item d-flex justify-content-between mb-2">
                                    <span>Livraison</span>
                                    <span id="shipping-cost" class="{{ $total >= 50000 ? 'text-success' : '' }}">
                                        {{ $total >= 50000 ? 'Gratuite' : '1,000 fcfa' }}
                                    </span>
                                </div>
                                
                                @if($total >= 50000)
                                <div class="summary-item d-flex justify-content-between mb-2 text-success" id="shipping-savings-container">
                                    <span>Économie livraison</span>
                                    <span id="shipping-savings">-1,000 fcfa</span>
                                </div>
                                @endif
                                
                                <hr>
                                
                                <div class="summary-total d-flex justify-content-between mb-3">
                                    <strong>Total</strong>
                                    <strong class="text-primary" id="grand-total" data-current-grand-total="{{ $total >= 50000 ? $total : $total + 1000 }}">
                                        {{ number_format($total >= 50000 ? $total : $total + 1000, 0, ',', ' ') }} fcfa
                                    </strong>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="{{ route('checkout.process') }}" class="btn btn-primary btn-lg">
                                        <i class="bi bi-credit-card me-2"></i>Passer la commande
                                    </a>
                                    <a href="{{ route('shop') }}" class="btn btn-outline-primary">
                                        <i class="bi bi-bag me-2"></i>Continuer mes achats
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let isUpdating = false; // Pour éviter les mises à jour simultanées

    // Gestion de la quantité
    function updateCartItem(cartId) {
        if (isUpdating) return;
        
        const quantityInput = document.getElementById(`quantity-${cartId}`);
        const quantity = parseInt(quantityInput.value);
        const maxQuantity = parseInt(quantityInput.getAttribute('max'));
        
        if (quantity < 1) {
            quantityInput.value = 1;
            return;
        } else if (quantity > maxQuantity) {
            quantityInput.value = maxQuantity;
            showAlert('Stock insuffisant', 'warning');
            return;
        }
        
        // Mettre à jour le total de l'article immédiatement (feedback visuel)
        updateItemTotal(cartId, quantity);
        
        // Mettre à jour le résumé de commande
        updateOrderSummary();
        
        // Envoyer une requête AJAX pour mettre à jour la base de données
        updateCartInDatabase(cartId, quantity);
    }

    function increaseQuantity(cartId) {
        const quantityInput = document.getElementById(`quantity-${cartId}`);
        const maxQuantity = parseInt(quantityInput.getAttribute('max'));
        const currentValue = parseInt(quantityInput.value);
        
        if (currentValue < maxQuantity) {
            quantityInput.value = currentValue + 1;
            updateCartItem(cartId);
        } else {
            showAlert('Stock maximum atteint', 'warning');
        }
    }

    function decreaseQuantity(cartId) {
        const quantityInput = document.getElementById(`quantity-${cartId}`);
        const currentValue = parseInt(quantityInput.value);
        
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
            updateCartItem(cartId);
        }
    }

    // Fonction pour mettre à jour le total de l'article
    function updateItemTotal(cartId, quantity) {
        const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
        if (!cartItem) return;
        
        const priceElement = cartItem.querySelector('.cart-item-price');
        if (!priceElement) return;
        
        const unitPrice = parseInt(priceElement.getAttribute('data-unit-price'));
        const newTotal = unitPrice * quantity;
        
        // Mettre à jour l'affichage avec animation
        const totalElement = document.getElementById(`total-${cartId}`);
        if (totalElement) {
            totalElement.style.transform = 'scale(1.1)';
            totalElement.style.transition = 'transform 0.3s ease';
            totalElement.setAttribute('data-current-total', newTotal);
            
            setTimeout(() => {
                totalElement.textContent = new Intl.NumberFormat('fr-FR').format(newTotal) + ' fcfa';
                totalElement.style.transform = 'scale(1)';
            }, 150);
        }
    }

    // Fonction pour mettre à jour le résumé de commande
    function updateOrderSummary() {
        let newSubtotal = 0;
        let totalItems = 0;
        
        // Calculer le nouveau sous-total et le nombre total d'articles
        document.querySelectorAll('[id^="total-"]').forEach(element => {
            const totalValue = parseInt(element.getAttribute('data-current-total'));
            if (!isNaN(totalValue)) {
                newSubtotal += totalValue;
            }
            
            const cartId = element.id.replace('total-', '');
            const quantityInput = document.getElementById(`quantity-${cartId}`);
            if (quantityInput) {
                totalItems += parseInt(quantityInput.value);
            }
        });
        
        updateSummaryDisplay(newSubtotal, totalItems);
    }

    // Fonction pour mettre à jour l'affichage du résumé
    function updateSummaryDisplay(newSubtotal, totalItems) {
        const subtotalElement = document.getElementById('subtotal');
        const totalItemsElement = document.getElementById('total-items-count');
        
        if (subtotalElement && totalItemsElement) {
            subtotalElement.style.transform = 'scale(1.05)';
            subtotalElement.style.transition = 'transform 0.3s ease';
            subtotalElement.setAttribute('data-current-subtotal', newSubtotal);
            
            totalItemsElement.textContent = totalItems;
            
            // Mettre à jour le texte (singulier/pluriel)
            const itemsText = totalItems > 1 ? 'articles' : 'article';
            totalItemsElement.parentNode.innerHTML = `Sous-total (<span id="total-items-count">${totalItems}</span> ${itemsText})`;
        }
        
        // Calculer les frais de livraison
        const shippingCost = newSubtotal >= 50000 ? 0 : 1000;
        const shippingElement = document.getElementById('shipping-cost');
        const shippingSavingsContainer = document.getElementById('shipping-savings-container');
        
        if (shippingElement) {
            if (newSubtotal >= 50000) {
                shippingElement.textContent = 'Gratuite';
                shippingElement.className = 'text-success';
                
                if (!shippingSavingsContainer) {
                    const savingsHtml = `
                        <div class="summary-item d-flex justify-content-between mb-2 text-success" id="shipping-savings-container">
                            <span>Économie livraison</span>
                            <span id="shipping-savings">-1,000 fcfa</span>
                        </div>
                    `;
                    shippingElement.insertAdjacentHTML('beforebegin', savingsHtml);
                }
            } else {
                shippingElement.textContent = '1,000 fcfa';
                shippingElement.className = '';
                
                if (shippingSavingsContainer) {
                    shippingSavingsContainer.remove();
                }
            }
        }
        
        // Calculer le total général
        const grandTotal = newSubtotal + shippingCost;
        const grandTotalElement = document.getElementById('grand-total');
        
        if (grandTotalElement) {
            grandTotalElement.style.transform = 'scale(1.1)';
            grandTotalElement.style.transition = 'transform 0.3s ease';
            grandTotalElement.setAttribute('data-current-grand-total', grandTotal);
            
            setTimeout(() => {
                if (subtotalElement) {
                    subtotalElement.textContent = new Intl.NumberFormat('fr-FR').format(newSubtotal) + ' fcfa';
                }
                grandTotalElement.textContent = new Intl.NumberFormat('fr-FR').format(grandTotal) + ' fcfa';
                
                if (subtotalElement) subtotalElement.style.transform = 'scale(1)';
                grandTotalElement.style.transform = 'scale(1)';
            }, 150);
        }
    }

    // Fonction pour mettre à jour le panier dans la base de données (AJAX)
    function updateCartInDatabase(cartId, quantity) {
        isUpdating = true;
        
        const url = '{{ route("cart.update", ":id") }}'.replace(':id', cartId);
        
        fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                quantity: quantity
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Mettre à jour les données avec la réponse du serveur si nécessaire
                console.log('Panier mis à jour:', data);
            } else {
                showAlert(data.message || 'Erreur lors de la mise à jour', 'danger');
                // Recharger la page pour synchroniser les données
                setTimeout(() => location.reload(), 2000);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showAlert('Erreur de connexion. Vérifiez votre connexion internet.', 'danger');
        })
        .finally(() => {
            isUpdating = false;
        });
    }

    // Fonction d'alerte
    function showAlert(message, type = 'info') {
        const existingAlert = document.querySelector('.alert');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show mt-3`;
        alertDiv.innerHTML = `
            <i class="bi bi-${type === 'warning' ? 'exclamation-triangle' : type === 'danger' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.cart-items').prepend(alertDiv);
        
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // Ajouter les écouteurs d'événements
    document.querySelectorAll('.decrease-btn').forEach(button => {
        button.addEventListener('click', function() {
            const cartId = this.getAttribute('data-id');
            decreaseQuantity(cartId);
        });
    });

    document.querySelectorAll('.increase-btn').forEach(button => {
        button.addEventListener('click', function() {
            const cartId = this.getAttribute('data-id');
            increaseQuantity(cartId);
        });
    });

    document.querySelectorAll('.quantity-input-field').forEach(input => {
        input.addEventListener('change', function() {
            const cartId = this.getAttribute('data-id');
            updateCartItem(cartId);
        });
        
        input.addEventListener('input', function() {
            const cartId = this.getAttribute('data-id');
            const maxQuantity = parseInt(this.getAttribute('max'));
            let value = parseInt(this.value);
            
            if (this.value === '' || isNaN(value)) {
                this.value = 1;
            } else if (value < 1) {
                this.value = 1;
            } else if (value > maxQuantity) {
                this.value = maxQuantity;
                showAlert('Stock maximum atteint', 'warning');
            }
        });
        
        input.addEventListener('blur', function() {
            if (this.value === '' || parseInt(this.value) < 1) {
                this.value = 1;
                const cartId = this.getAttribute('data-id');
                updateCartItem(cartId);
            }
        });
    });
});
</script>

<style>
/* Votre CSS existant reste le même */
.cart-header {
    padding: 1rem 0;
    border-bottom: 2px solid var(--gray-light);
}

.page-title {
    font-weight: 700;
    color: var(--dark);
}

.empty-cart {
    background: white;
    border-radius: var(--border-radius);
    padding: 3rem 2rem;
    box-shadow: var(--shadow);
}

.cart-item {
    transition: var(--transition);
}

.cart-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.cart-item-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
}

.cart-item-title {
    font-weight: 600;
    color: var(--dark);
}

.quantity-input .btn {
    width: 40px;
}

.quantity-input .form-control {
    border-left: none;
    border-right: none;
}

.order-summary {
    border: 2px solid var(--primary-light);
}

.summary-item {
    font-size: 0.9rem;
}

.summary-total {
    font-size: 1.1rem;
}

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

.cart-item-total div, #subtotal, #grand-total {
    transition: all 0.3s ease;
}

@media (max-width: 768px) {
    .cart-item .row > div {
        margin-bottom: 1rem;
    }
    
    .cart-item .row > div:last-child {
        margin-bottom: 0;
    }
    
    .cart-item-total {
        text-align: left !important;
    }
    
    .order-summary {
        position: static !important;
    }
}

@media (max-width: 576px) {
    .quantity-input {
        max-width: 100px !important;
    }
    
    .cart-item-image {
        width: 60px;
        height: 60px;
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
    }
}

.quantity-input .btn:focus {
    box-shadow: none;
    border-color: var(--primary);
}

.quantity-input .btn:hover {
    background-color: var(--primary-light);
    border-color: var(--primary);
}

.quantity-input-field:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.2rem rgba(var(--primary-rgb), 0.25);
}
</style>
@endsection