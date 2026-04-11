<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class newProductCard extends Component
{
    public $title;
    public $image;
    public $price;
    public $salePrice;
    public $category;
    public $id;
    public $isAvailable;
    public $forceColoredCart;
    public function __construct($image, $title, $price, $salePrice = null, $category, $id, $isAvailable,$forceColoredCart=false)
{
    $this->image = $image;
    $this->title = $title;
    $this->price = $price;
    $this->salePrice = $salePrice;
    $this->category = $category;
    $this->id = $id;
    $this->isAvailable = $isAvailable;
    $this->forceColoredCart=$forceColoredCart;
}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.new-product-card');
    }
}