<?php

namespace App\Jobs;

use App\Events\AdminDataEvent;
use App\Models\Settings;
use App\Services\Event\EventService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $eventService = new EventService();
        $eventService->createNewFactuurOrder($this->orderId);

        $data['orderId'] = $this->orderId;
        event(new AdminDataEvent(Settings::ADMIN_DATA_TYPE['order_new'], $data));
    }
}
