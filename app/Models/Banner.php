<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Banner extends Model
{
    protected $fillable = [
        'image',
        'mobile_image',
        'small_image',
        'product_id'
    ];
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}