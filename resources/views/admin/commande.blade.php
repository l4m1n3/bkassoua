
@extends('layouts.app_admin')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Liste des commandes</h2>
            <div class="d-flex">
                <form class="me-2">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Rechercher..." name="search">
                        <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </form>
                <select class="form-select" id="statusFilter">
                    <option value="">Tous les statuts</option>
                    <option value="pending">En attente</option>
                    <option value="delivered">Livré</option>
                    <option value="cancelled">Annulé</option>
                </select>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                @if($orders->isEmpty())
                    <div class="text-center py-5">
                        <i class="fa fa-box-open fa-3x text-muted mb-3"></i>
                        <h4>Aucune commande trouvée</h4>
                        <p class="text-muted">Aucune commande n'a été passée pour le moment.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Utilisateur</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Paiement</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>{{ number_format($order->total_amount, 0, ',', ' ') }} Fcfa</td>
                                        <td>
                                            <span class="badge text-capitalize {{ $order->status == 'pending' ? 'bg-warning' : ($order->status == 'delivered' ? 'bg-success' : 'bg-danger') }}">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($order->payment)
                                                <span class="badge text-capitalize {{ $order->payment->status == 'pending' ? 'bg-danger' : 'bg-success' }}">
                                                    {{ $order->payment->status }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Non payé</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#orderDetailModal{{ $order->id }}" title="Détails">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                
                                                @if($order->status == 'pending')
                                                    @if($order->payment && $order->payment->status === 'pending')
                                                        <form action="{{ route('admin.orders.validate', $order->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm" title="Valider le paiement">
                                                                <i class="fa fa-check-circle"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Annuler la commande">
                                                            <i class="fa fa-times-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($orders->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Order Detail Modals -->
    @foreach ($orders as $order)
        <div class="modal fade" id="orderDetailModal{{ $order->id }}" tabindex="-1"
            aria-labelledby="orderDetailModalLabel{{ $order->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fa fa-receipt me-2"></i> Commande #{{ $order->id }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <h5 class="border-bottom pb-2">Informations client</h5>
                                <ul class="list-unstyled">
                                    <li><strong>Nom :</strong> {{ $order->user->name }}</li>
                                    <li><strong>Email :</strong> {{ $order->user->email }}</li>
                                    <li><strong>Téléphone :</strong> {{ $order->user->phone_number ?? 'Non renseigné' }}</li>
                                    <li><strong>Adresse :</strong> {{ $order->user->address ?? 'Non renseignée' }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h5 class="border-bottom pb-2">Informations commande</h5>
                                <ul class="list-unstyled">
                                    <li><strong>Date :</strong> {{ $order->created_at->format('d/m/Y H:i') }}</li>
                                    <li><strong>Statut :</strong> 
                                        <span class="badge {{ $order->status == 'pending' ? 'bg-warning' : ($order->status == 'delivered' ? 'bg-success' : 'bg-danger') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </li>
                                    <li><strong>Paiement :</strong> 
                                        @if($order->payment)
                                            <span class="badge {{ $order->payment->status == 'pending' ? 'bg-danger' : 'bg-success' }}">
                                                {{ ucfirst($order->payment->status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Non payé</span>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h5 class="border-bottom pb-2">Articles commandés</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th>Quantité</th>
                                        <th>Prix unitaire</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->price, 0, ',', ' ') }} Fcfa</td>
                                            <td>{{ number_format($item->quantity * $item->price, 0, ',', ' ') }} Fcfa</td>
                                        </tr>
                                    @endforeach
                                    <tr class="table-light">
                                        <td colspan="3" class="text-end"><strong>Total commande :</strong></td>
                                        <td><strong>{{ number_format($order->total_amount, 0, ',', ' ') }} Fcfa</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        @if($order->payment)
                            <div class="mt-4">
                                <h5 class="border-bottom pb-2">Informations paiement</h5>
                                <ul class="list-unstyled">
                                    <li><strong>Méthode :</strong> {{ $order->payment->method ?? 'Non spécifiée' }}</li>
                                    <li><strong>Montant :</strong> {{ number_format($order->payment->amount, 0, ',', ' ') }} Fcfa</li>
                                    <li><strong>Référence :</strong> {{ $order->payment->reference ?? 'Non disponible' }}</li>
                                    <li><strong>Date :</strong> {{ optional($order->created_at)->format('d/m/Y H:i') ?? 'Date inconnue' }}</li>
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        @if($order->status == 'pending')
                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="delivered">
                                <button type="submit" class="btn btn-success">Marquer comme livré</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection