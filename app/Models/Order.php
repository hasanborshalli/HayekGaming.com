<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'name',
        'mobile',
        'second_mobile',
        'city',
        'street',
        'building',
        'floor',
        'remarks',
        'total',
    ];
    public function products()
    {
        return $this->hasMany(Order_item::class, 'order_id');
    }
    public function watches()
    {
        return $this->hasMany(Order_item::class, 'order_id');
    }
    public function orderItems()
{
    return $this->hasMany(\App\Models\Order_item::class);
}
}