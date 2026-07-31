<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PunchOrder extends Model
{
    protected $fillable = [
        'note',
        'total',
    ];

    public function items()
    {
        return $this->hasMany(PunchOrderItem::class);
    }
}
