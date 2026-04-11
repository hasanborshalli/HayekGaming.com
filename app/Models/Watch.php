<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class Watch extends Model
{
    use Searchable;
    protected $fillable = [
       'type_id',
       'name',
       'description',
       'features',
       'box_contents',
       'price',
       'image',
       'image1',
       'image2',
       'image3',
       'image4',
       'image5',
       'image6',
       'featured',
       'sale',
       'cost',
       'is_available',
       'color',
       'color1',
       'color2',
       'color3',
       'color4',
       'color5',
       'color6',
    ];
     public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
    public function toSearchableArray()
    {

        return [
            'name' => $this->name,
        ];
    }
}