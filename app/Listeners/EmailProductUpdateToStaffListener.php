<?php

namespace App\Listeners;

use App\Events\ProductUpdatingEvent;
use App\Mail\ProductUpdateMail;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;

class EmailProductUpdateToStaffListener
{
    public function handle(ProductUpdatingEvent $event)
    {
        $product = $event->product;
        $original = $event->product->getOriginal();

        Mail::to(env('ALL_STAFF_EMAIL'))->send(new ProductUpdateMail(
            $this->getOldAttributes($original, $product),
            $this->getNewAttributes($product)
        ));
    }

    protected function getOldAttributes(array $original, Product $product): array
    {
        return [
            'name' => $original['name'],
            'price' => number_format($original['price'] / 100, 2),
            'image' => $original['image_url'],
            'brand' => $product->isDirty('brand_id')
                ? Brand::where('id', $original['brand_id'])->value('name')
                : $product->brand->name,
        ];
    }

    protected function getNewAttributes(Product $product): array
    {
        return [
            'name' => $product->name,
            'price' => number_format($product->price / 100, 2),
            'image' => $product->image_url,
            'brand' => $product->brand->name,
        ];
    }
}
