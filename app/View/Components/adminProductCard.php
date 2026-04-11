<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class adminProductCard extends Component
{
    public $name;
    public $category;
    public $subCategory;
    public $gameType;
    public $price;
    public $id;
    public $cost;
    public function __construct($name, $category, $subCategory, $gameType, $price, $id,$cost)
    {
        $this->name=$name;
        $this->category=$category;
        $this->subCategory=$subCategory;
        $this->gameType=$gameType;
        $this->price=$price;
        $this->id=$id;
        $this->cost=$cost;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin-product-card');
    }
}