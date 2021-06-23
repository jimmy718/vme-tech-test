<?php

namespace App\Models;

use App\Events\ProductUpdatingEvent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int name
 * @property string barcode
 * @property int brand_id
 * @property Brand brand
 * @property int price
 * @property string image_url
 * @property Carbon date_added
 */
class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date_added' => 'datetime'
    ];

    protected $dispatchesEvents = [
        'updating' => ProductUpdatingEvent::class
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
