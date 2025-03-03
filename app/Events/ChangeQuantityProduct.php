<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChangeQuantityProduct implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $prodId;
    public $quantity;


    public function __construct($prodId, $quantity)
    {
        $this->prodId = $prodId;
        $this->quantity = $quantity;
    }

    public function broadcastOn()
    {
        return new Channel('order');
    }

    /**
     * Push data to the client
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'prodId' => $this->prodId,
            'quantity' => $this->quantity,
        ];
    }
}
