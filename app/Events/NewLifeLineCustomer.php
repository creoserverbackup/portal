<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewLifeLineCustomer implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $uid;
    public $type;
    public $data;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($uid, $type = '', $data = '')
    {
        $this->uid = $uid;
        $this->type = $type;
        $this->data = $data;
    }

    public function broadcastOn()
    {
        return new Channel('uid-' . $this->uid);
    }

    /**
     * Push data to the client
     * @return array
     */
    public function broadcastWith()
    {
        return [
                'uid' => $this->uid,
                'type' => $this->type,
                'data' => $this->data,
        ];
    }
}
