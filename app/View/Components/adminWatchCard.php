<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class adminWatchCard extends Component
{
     public $name;
    public $type;
    public $price;
    public $id;
    public $cost;
    public $color;
    public $color1;
    public $color2;
    public $color3;
    public $color4;
    public $color5;
    public $color6;
    
    public function __construct($name, $type, $price, $id,$cost,$color,$color1,$color2,$color3,$color4,$color5,$color6)
    {
        $this->name=$name;
        $this->type=$type;
        $this->price=$price;
        $this->id=$id;
        $this->cost=$cost;
        $this->color=$color;
        $this->color1=$color1;
        $this->color2=$color2;
        $this->color3=$color3;
        $this->color4=$color4;
        $this->color5=$color5;
        $this->color6=$color6;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin-watch-card');
    }
}