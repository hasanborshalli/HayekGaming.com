<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class watchCard extends Component
{
    public $title;
    public $image;
    public $image1;
    public $image2;
    public $image3;
    public $image4;
    public $image5;
    public $image6;

    public $color;
    public $color1;
    public $color2;
    public $color3;
    public $color4;
    public $color5;
    public $color6;

    public $price;
    public $salePrice;
    public $id;
    public $isAvailable;
    public $forceColoredCart;
    public $type;

    public function __construct(
        $image,
        $type,
        $title,
        $price,
        $salePrice = null,
        $id,
        $isAvailable,
        $forceColoredCart = false,
        $image1 = null,
        $image2 = null,
        $image3 = null,
        $image4 = null,
        $image5 = null,
        $image6 = null,
        $color = null,
        $color1 = null,
        $color2 = null,
        $color3 = null,
        $color4 = null,
        $color5 = null,
        $color6 = null
    ) {
        $this->image = $image;
        $this->image1 = $image1;
        $this->image2 = $image2;
        $this->image3 = $image3;
        $this->image4 = $image4;
        $this->image5 = $image5;
        $this->image6 = $image6;

        $this->color = $color;
        $this->color1 = $color1;
        $this->color2 = $color2;
        $this->color3 = $color3;
        $this->color4 = $color4;
        $this->color5 = $color5;
        $this->color6 = $color6;

        $this->title = $title;
        $this->price = $price;
        $this->salePrice = $salePrice;
        $this->id = $id;
        $this->isAvailable = $isAvailable;
        $this->forceColoredCart = $forceColoredCart;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.watch-card');
    }
}