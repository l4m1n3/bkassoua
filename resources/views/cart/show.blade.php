{{-- resources/views/cart.blade.php --}}
@extends('layouts.slaves')

@section('title', 'Panier - Bkassoua')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="cart-header mb-4">
                <h1 class="page-title mb-2">Votre Panier</h1>
                <p class="text-muted">
                    <span id="cart-items-count">{{ $carts->count() }}</span> article{{ $carts->count() > 1 ? 's' : '' }}
                </p>
            </div>

            @if($carts->isEmpty())
                <div class="text-center py-5 bg-white rounded shadow">
                    <i class="bi bi-cart-x display-1 text-muted"></i>
                    <h3 class="mt-4 text-muted">Votre panier est vide</h3>
                    <a href="{{ route('shop') }}" class="btn btn-primary btn-lg mt-3">
                        <i class="bi bi-bag me-2"></i> Continuer mes achats
                    </a>
                </div>
            @else 
                <div class="row">
                    <div class="col-lg-8">
                        <div class="cart-items">
                            @foreach($carts as $cart)
                            <div class="cart-item card border-0 shadow-sm mb-3 p-3"
                                 data-cart-id="{{ $cart->id }}"
                                 data-update-url="{{ route('cart.update', $cart->id) }}"
                                 data-remove-url="{{ route('cart.remove', $cart->id) }}">

                                <div class="row align-items-center g-3">
                                    <div class="col-3 col-md-2">
                                        <img src="{{ $cart->product->image ? asset('storage/' . $cart->product->image) : asset('images/default.jpg') }}"
                                             alt="{{ $cart->product->name }}"
                                             class="img-fluid rounded shadow-sm"
                                             style="width:90px;height:90px;object-fit:cover;">
                                    </div>
                                    <div class="col-9 col-md-4">
                                        <h6 class="fw-bold mb-1">{{ $cart->product->name }}</h6>
                                        <small class="text-muted">{{ $cart->product->category->name ?? 'Non catégorisé' }}</small>
                                    </div>
                                    <div class="col-6 col-md-2 text-md-center">
                                        <div class="text-primary fw-bold" data-price="{{ $cart->product->price }}">
                                            {{ number_format($cart->product->price, 0, ',', ' ') }} FCFA
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <div class="input-group input-group-sm" style="width:130px;">
                                            <button class="btn btn-outline-secondary decrease-qty">-</button>
                                            <input type="text" class="form-control text-center qty-input"
                                                   value="{{ $cart->quantity }}"
                                                   data-max="{{ $cart->product->stock_quantity }}"
                                                   readonly>
                                            <button class="btn btn-outline-secondary increase-qty">+</button>
                                        </div>
                                        <small class="text-muted d-block text-center mt-1">
                                            {{ $cart->product->stock_quantity }} en stock
                                        </small>
                                    </div>
                                    <div class="col-6 col-md-1 text-end">
                                        <div class="fw-bold text-primary item-total">
                                            {{ number_format($cart->product->price * $cart->quantity, 0, ',', ' ') }} FCFA
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-1 text-end">
                                        <button class="btn btn-sm btn-danger remove-item">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Résumé -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-lg sticky-top" style="top:20px;">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Résumé de la commande</h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Sous-total</span>
                                    <strong id="subtotal">{{ number_format($total, 0, ',', ' ') }} FCFA</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Livraison</span>
                                    <span id="shipping">{{ $total >= 50000 ? 'Gratuite' : '1 000 FCFA' }}</span>
                                </div>
                                @if($total >= 50000)
                                <div class="d-flex justify-content-between text-success mb-3">
                                    <span>Économies livraison</span>
                                    <span>-1 000 FCFA</span>
                                </div>
                                @endif
                                <hr>
                                <div class="d-flex justify-content-between mb-4">
                                    <strong>Total</strong>
                                    <strong class="h4 text-primary" id="grand-total">
                                        {{ number_format($total >= 50000 ? $total : $total + 1000, 0, ',', ' ') }} FCFA
                                    </strong>
                                </div>
                                <form action="{{ route('checkout.process') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="bi bi-lock me-2"></i> Passer la commande
                                    </button>
                                </form>
                                <a href="{{ route('shop') }}" class="btn btn-outline-secondary w-100 mt-2">
                                    Continuer mes achats
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Toast -->
<div id="cartToast" class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
    <div class="toast align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">Panier mis à jour !</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toastEl = document.getElementById('cartToast');
    const toast = new bootstrap.Toast(toastEl.querySelector('.toast'), { delay: 3000 });

    function showToast(message) {
        document.getElementById('toastMessage').textContent = message;
        toast.show();
    }

    function updateTotals() {
        let subtotal = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const qty = parseInt(item.querySelector('.qty-input').value);
            const price = parseInt(item.querySelector('[data-price]').dataset.price);
            const total = price * qty;
            subtotal += total;
            item.querySelector('.item-total').textContent = new Intl.NumberFormat('fr-FR').format(total) + ' FCFA';
        });

        const shipping = subtotal >= 50000 ? 0 : 1000;
        const grandTotal = subtotal + shipping;

        document.getElementById('subtotal').textContent = new Intl.NumberFormat('fr-FR').format(subtotal) + ' FCFA';
        document.getElementById('grand-total').textContent = new Intl.NumberFormat('fr-FR').format(grandTotal) + ' FCFA';
        document.getElementById('shipping').textContent = shipping === 0 ? 'Gratuite' : '1 000 FCFA';
        document.getElementById('shipping').classList.toggle('text-success', shipping === 0);
        document.getElementById('cart-items-count').textContent = document.querySelectorAll('.cart-item').length;
    }

    // Mise à jour quantité
    document.querySelectorAll('.increase-qty, .decrease-qty').forEach(btn => {
        btn.addEventListener('click', function () {
            const item = this.closest('.cart-item');
            const input = item.querySelector('.qty-input');
            let qty = parseInt(input.value);

            if (this.classList.contains('increase-qty') && qty < parseInt(input.dataset.max)) qty++;
            if (this.classList.contains('decrease-qty') && qty > 1) qty--;

            input.value = qty;

            fetch(item.dataset.updateUrl, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: qty })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    updateTotals();
                    showToast('Quantité mise à jour');
                    if (data.cart_count) {
                        document.querySelectorAll('#cart-count').forEach(el => el.textContent = data.cart_count);
                    }
                }
            });
        });
    });

    // Suppression
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function () {
            const item = this.closest('.cart-item');
            if (!confirm('Supprimer cet article ?')) return;

            fetch(item.dataset.removeUrl, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    item.remove();
                    updateTotals();
                    showToast('Article supprimé');
                    if (data.cart_count !== undefined) {
                        document.querySelectorAll('#cart-count').forEach(el => el.textContent = data.cart_count);
                    }
                    if (document.querySelectorAll('.cart-item').length === 0) location.reload();
                }
            });
        });
    });

    updateTotals();
});
</script>
@endsection