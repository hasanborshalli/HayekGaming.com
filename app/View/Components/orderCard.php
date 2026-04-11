<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class orderCard extends Component
{
    public $id;
    public $name;
    public $city;
    public $price;
    public function __construct($id, $name, $city, $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->city = $city;
        $this->price = $price;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.order-card');
    }
}