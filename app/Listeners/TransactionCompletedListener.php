<?php

namespace App\Listeners;

use App\Models\Ad;
use App\Models\User;
use Laravel\Paddle\Events\TransactionCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class TransactionCompletedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TransactionCompleted $event): void
    {
        $ad = Ad::find($event->payload['data']['custom_data']['ad_id']);
        $ad->status = 'paid';
        $ad->save();
    }
}
