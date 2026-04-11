<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class footer extends Component
{
    public $categories;
    public $movingSentence;
    public function __construct($categories,$movingSentence)
    {
        $this->categories=$categories;
        $this->movingSentence=$movingSentence;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.footer');
    }
}