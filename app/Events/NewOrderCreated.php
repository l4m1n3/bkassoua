<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\Channel;

class NewOrderCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Order $order) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('orders')];
    }

    public function broadcastAs(): string
    {
        return 'new-order';
    }

    public function broadcastWith(): array
    {
        $user = $this->order->relationLoaded('user') 
            ? $this->order->user 
            : $this->order->user()->first();

        $itemsCount = $this->order->relationLoaded('items') 
            ? $this->order->items->count() 
            : $this->order->items()->count();

        return [
            'id'           => $this->order->id,
            'reference'    => $this->order->reference ?? '#ORD-' . str_pad($this->order->id, 5, '0', STR_PAD_LEFT),
            'total'        => (float) ($this->order->total_amount ?? 0) + ($this->order->delivery_fee ?? 0),
            'status'       => $this->order->status ?? 'pending',
            'customer'     => $user?->name ?? 'Client anonyme',
            'items_count'  => $itemsCount,
            'created_at'   => $this->order->created_at?->format('H:i') ?? now()->format('H:i'),
        ];
    }
}