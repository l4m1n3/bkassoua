@foreach ($orders as $order)
<tr class="order-row" id="order-{{ $order->id }}" data-order-id="{{ $order->id }}">
    
    <td>
        <div class="form-check">
            <input class="form-check-input order-checkbox" type="checkbox" value="{{ $order->id }}">
        </div>
    </td>

    <td>
        <div class="order-info">
            <div class="order-id">#{{ $order->id }}</div>
            <small class="text-muted">
                {{ $order->items_count ?? 0 }} article(s)
            </small>
        </div>
    </td>

    <td>
        <div class="customer-info">
            <div class="customer-name">
                {{ $order->user->name ?? 'Client inconnu' }}
            </div>
            <small class="text-muted">
                {{ $order->user->email ?? '' }}
            </small>
        </div>
    </td>

    <td>
        <div class="amount">
            {{ number_format($order->total_amount ?? 0, 0, ',', ' ') }} fcfa
        </div>
    </td>

    <td>
        <span class="status-badge status-{{ $order->status ?? 'unknown' }}">
            <i class="bi bi-circle-fill me-1"></i>
            {{ ucfirst($order->status ?? 'inconnu') }}
        </span>
    </td>

    <td>
        @php
            $paymentStatus = $order->payment->status ?? null;
        @endphp

        @if($paymentStatus)
            <span class="payment-badge payment-{{ $paymentStatus }}">
                <i class="bi bi-{{ $paymentStatus == 'paid' ? 'check-circle' : 'clock' }} me-1"></i>
                {{ ucfirst($paymentStatus) }}
            </span>
        @else
            <span class="payment-badge payment-none">
                <i class="bi bi-x-circle me-1"></i>
                Non payé
            </span>
        @endif
    </td>

    <td>
        <div class="date-info">
            <div>{{ $order->created_at?->format('d/m/Y') }}</div>
            <small class="text-muted">
                {{ $order->created_at?->format('H:i') }}
            </small>
        </div>
    </td>

    <td>
        <div class="action-buttons">

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#orderDetailModal{{ $order->id }}">
                <i class="bi bi-eye"></i>
            </button>

            @if(($order->status ?? '') == 'pending')

                @if(($order->payment->status ?? '') === 'pending')
                    <button class="btn btn-sm btn-success"
                            onclick="validatePayment({{ $order->id }})">
                        <i class="bi bi-check-lg"></i>
                    </button>
                @endif

                <button class="btn btn-sm btn-danger"
                        onclick="cancelOrder({{ $order->id }})">
                    <i class="bi bi-x-lg"></i>
                </button>
            @endif

            @if(($order->status ?? '') == 'processing')
                <button class="btn btn-sm btn-info"
                        onclick="updateOrderStatus({{ $order->id }}, 'shipped')">
                    <i class="bi bi-truck"></i>
                </button>
            @endif

            @if(($order->status ?? '') == 'shipped')
                <button class="btn btn-sm btn-success"
                        onclick="updateOrderStatus({{ $order->id }}, 'delivered')">
                    <i class="bi bi-check-circle"></i>
                </button>
            @endif

        </div>
    </td>

</tr>
@endforeach