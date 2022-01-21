<?php

namespace App\Listeners;

use App\Events\ProductStatus;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class StatusUpdated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ProductStatus $event)
    {
        if ($event->product->quantity == 0 && $event->product->isAvailable()) {
            $event->product->status = Product::UNAVAILABLE_PRODUCT;
            $event->product->save();
            Log::info('successfully executed');
        }
    }
}
