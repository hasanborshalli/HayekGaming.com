<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class navbar extends Component
{
    public $categories;
    public $cartQuantity;
    public function __construct($categories, $cartQuantity)
    {
        $this->categories=$categories;
        $this->cartQuantity=$cartQuantity;
    }
        
    

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.navbar');
    }
}