<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable;
    protected $fillable = [
       'category_id',
       'sub_category_id',
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
       'isNew',
       'is_available'
       ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }
    public function gameTypes()
    {
        return $this->belongsToMany(GameType::class);
    }
    public function toSearchableArray()
    {

        return [
            'name' => $this->name,
        ];
    }
}