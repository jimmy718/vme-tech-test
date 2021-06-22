<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * @property int id
 * @property int name
 * @property string barcode
 * @property string brand
 * @property string price
 * @property string image_url
 * @property Carbon date_added
 * @package App\Models
 */
class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date_added' => 'datetime'
    ];
}
