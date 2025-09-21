<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json(['message' => 'Commande introuvable.'], 404);
        }

        // Simuler un paiement réussi
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method' => $request->payment_method,
            'amount' => $order->total_price,
            'payment_status' => 'paid',
        ]);

        // Mettre à jour le statut de la commande
        $order->update([
            'status' => 'completed',
            'payment_status' => 'paid',
        ]);

        return response()->json(['message' => 'Paiement effectué avec succès.', 'payment' => $payment]);
    }
}
