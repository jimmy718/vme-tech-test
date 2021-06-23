<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProductResource
 * @mixin Product
 * @package App\Http\Resources
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'barcode' => $this->barcode,
            'price' => round($this->price, 2),
            'image_url' => $this->image_url,
            'date_added' => $this->date_added->toDateTimeString(),
            'brand' => new BrandResource($this->whenLoaded('brand'))
        ];
    }
}
