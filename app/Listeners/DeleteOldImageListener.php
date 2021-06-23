<?php

namespace App\Listeners;

use App\Events\ProductUpdatingEvent;
use Illuminate\Support\Facades\Storage;

class DeleteOldImageListener
{
    public function handle(ProductUpdatingEvent $event): void
    {
        if ($event->product->isClean('image_url')) {
            return;
        }

        Storage::disk('images')->delete($event->product->getOriginal('image_url'));
    }
}
