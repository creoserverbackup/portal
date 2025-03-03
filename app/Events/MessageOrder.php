<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageOrder implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public $orderId;


    public function __construct($orderId, $data)
    {
        $this->data = $data;
        $this->orderId = $orderId;
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
            'data' => $this->data,
            'orderId' => $this->orderId,
        ];
    }
}
