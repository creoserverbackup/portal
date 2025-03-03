<?php

namespace App\Events;

use App\Models\StatisticVisits;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddPathPageCustomer implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $uid;
    public $toUrl;
    public $fromUrl;
    public $time;
    public $site;
    public $customerName;

    /**
     * Create a new event instance.
     *
     * @param StatisticVisits $statistic
     * @param string $customerName
     */
    public function __construct(StatisticVisits $statistic, $customerName = '')
    {
        $this->uid = $statistic->uid;
        $this->toUrl = $statistic->toUrl;
        $this->fromUrl = $statistic->fromUrl;
        $this->time = $statistic->time;
        $this->site = $statistic->site;
        $this->customerName = $customerName;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('customer');
    }

    /**
     * Push data to the client
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'uid' => $this->uid,
            'toUrl' => $this->toUrl,
            'fromUrl' => $this->fromUrl,
            'time' => $this->time,
            'site' => $this->site,
            'customerName' => $this->customerName,
        ];
    }
}
