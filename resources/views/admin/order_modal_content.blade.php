{{-- resources/views/admin/order_modal_content.blade.php --}}
<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        <i class="bi bi-receipt me-2"></i>Détails de la commande #{{ $order->id }}
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <div class="row">

        {{-- Informations client --}}
        <div class="col-md-6">
            <div class="info-section">
                <h6 class="section-title">
                    <i class="bi bi-person me-2"></i>Informations client
                </h6>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Nom complet</label>
                        <div>{{ $order->user->name }}</div>
                    </div>
                    <div class="info-item">
                        <label>Email</label>
                        <div>{{ $order->user->email }}</div>
                    </div>
                    <div class="info-item">
                        <label>Téléphone</label>
                        <div>{{ $order->user->phone_number ?? 'Non renseigné' }}</div>
                    </div>
                    <div class="info-item">
                        <label>Région</label>
                        <div>{{ $order->user?->address ?? 'Non renseignée' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Informations commande + paiement --}}
        <div class="col-md-6">
            <div class="info-section">
                <h6 class="section-title">
                    <i class="bi bi-info-circle me-2"></i>Informations commande
                </h6>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Date</label>
                        <div>{{ $order->created_at->format('d/m/Y à H:i') }}</div>
                    </div>
                    <div class="info-item">
                        <label>Statut</label>
                        <div>
                            <span class="status-badge status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="info-item">
                        <label>Sous-total</label>
                        <div>{{ number_format($order->subtotal ?? 0, 0, ',', ' ') }} fcfa</div>
                    </div>
                    <div class="info-item">
                        <label>Livraison</label>
                        <div>{{ number_format($order->shipping_cost ?? 0, 0, ',', ' ') }} fcfa</div>
                    </div>
                    <div class="info-item">
                        <label>Total</label>
                        <div class="amount-large">{{ number_format($order->payment->amount, 0, ',', ' ') }} fcfa</div>
                    </div>
                </div>
            </div>

            @if($order->payment)
            <div class="info-section mt-3">
                <h6 class="section-title">
                    <i class="bi bi-credit-card me-2"></i>Paiement
                </h6>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Statut</label>
                        <div>
                            <span class="payment-badge payment-{{ $order->payment->status }}">
                                {{ ucfirst($order->payment->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="info-item">
                        <label>Méthode</label>
                        <div>{{ $order->payment->payment_method ?? 'Non spécifiée' }}</div>
                    </div>
                    <div class="info-item">
                        <label>Date</label>
                        <div>{{ $order->payment->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- ── Articles commandés ── --}}
    <div class="info-section mt-4">
        <h6 class="section-title">
            <i class="bi bi-box-seam me-2"></i>
            Articles commandés
            <span class="badge bg-primary ms-2">{{ $order->items->count() }}</span>
        </h6>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="min-width:220px">Produit</th>
                        <th style="min-width:180px">Attributs choisis</th>
                        <th class="text-center">Prix unitaire</th>
                        <th class="text-center">Qté</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                    <tr>
                        {{-- Produit --}}
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @php
                                    $mainImage = $item->product?->images?->firstWhere('is_main', true)
                                              ?? $item->product?->images?->first();
                                @endphp
                                @if($mainImage)
                                    <img src="{{ asset('public/storage/' . $mainImage->path) }}"
                                         alt="{{ $item->product->name }}"
                                         class="product-thumb">
                                @else
                                    <div class="product-thumb-placeholder">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-600">
                                        {{ $item->product?->name ?? 'Produit supprimé' }}
                                    </div>
                                    <small class="text-muted">
                                        Réf : {{ $item->product?->id ?? '—' }}
                                    </small>
                                </div>
                            </div>
                        </td>

                        {{-- Attributs sélectionnés --}}
                        <td>
                            @if($item->resolved_options && $item->resolved_options->isNotEmpty())
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($item->resolved_options as $option)
                                        <span class="attribute-badge">
                                            @if(!empty($option['attribute']))
                                                <span class="attr-name">{{ $option['attribute'] }}</span>
                                                <span class="attr-sep">:</span>
                                            @endif
                                            <span class="attr-value">{{ $option['value'] }}</span>
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="no-attr">
                                    <i class="bi bi-dash-circle me-1"></i>Aucun attribut
                                </span>
                            @endif
                        </td>

                        <td class="text-center">
                            {{ number_format($item->price, 0, ',', ' ') }} fcfa
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary rounded-pill">
                                {{ $item->quantity }}
                            </span>
                        </td>
                        <td class="text-end fw-600 text-primary">
                            {{ number_format($item->quantity * $item->price, 0, ',', ' ') }} fcfa
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-light">
                        <td colspan="4" class="text-end fw-600">Total commande :</td>
                        <td class="text-end fw-600 text-primary">
                            {{ number_format($order->total_amount, 0, ',', ' ') }} fcfa
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>

    @if($order->status == 'pending')
        @if($order->payment && $order->payment->status === 'pending')
            <button type="button" class="btn btn-success" onclick="validatePayment({{ $order->id }})">
                <i class="bi bi-check-lg me-2"></i>Valider le paiement
            </button>
        @endif
        <button type="button" class="btn btn-danger" onclick="cancelOrder({{ $order->id }})">
            <i class="bi bi-x-lg me-2"></i>Annuler la commande
        </button>
    @endif

    @if($order->status == 'processing')
        <button type="button" class="btn btn-info" onclick="updateOrderStatus({{ $order->id }}, 'shipped')">
            <i class="bi bi-truck me-2"></i>Marquer comme expédiée
        </button>
    @endif

    @if($order->status == 'shipped')
        <button type="button" class="btn btn-success" onclick="updateOrderStatus({{ $order->id }}, 'delivered')">
            <i class="bi bi-check-circle me-2"></i>Marquer comme livrée
        </button>
    @endif
</div>