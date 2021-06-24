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
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'barcode' => $this->barcode,
            'price' => number_format($this->price / 100, 2),
            'image_url' => $this->image_url,
            'date_added' => $this->date_added->format('d/m/Y'),
            'brand' => new BrandResource($this->whenLoaded('brand'))
        ];
    }
}
