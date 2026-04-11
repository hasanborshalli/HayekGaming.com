<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class productBox extends Component
{
    public $name;
    public $price;
    public $id;
    public $image;
    public $image1;
    public $image2;
    public $image3;
    public $image4;
     public $image5;
    public $image6;
    public $sale;
    public $pageType;
    
    public function __construct($name, $price,$image, $image1, $image2, $image3, $image4, $image5, $image6, $id,$sale,$pageType)
    {
        $this->name=$name;
        $this->price=$price;
        $this->id=$id;
        $this->image=$image;
        $this->image1=$image1;
        $this->image2=$image2;
        $this->image3=$image3;
        $this->image4=$image4;
        $this->image5=$image5;
        $this->image6=$image6;
        $this->sale=$sale;
        $this->pageType=$pageType;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.product-box');
    }
}