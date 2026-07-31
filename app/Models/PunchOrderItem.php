<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PunchOrderItem extends Model
{
    protected $fillable = [
        'punch_order_id',
        'product_id',
        'watch_id',
        'type',
        'quantity',
        'unit_price',
    ];

    public function punchOrder()
    {
        return $this->belongsTo(PunchOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function watch()
    {
        return $this->belongsTo(Watch::class, 'watch_id');
    }
}
