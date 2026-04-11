<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class relatedProducts extends Component
{
    public $title;
    public $products;
    public $subId;
    public $isGames;
    public $category;
    public $gameTypeId;
    public $hasSubCategory;
    public $pageType;
    public function __construct($title, $products, $subId, $isGames, $category,$gameTypeId,$hasSubCategory,$pageType='product')
    {
        $this->title=$title;
        $this->products=$products;
        $this->subId=$subId;
        $this->isGames=$isGames;
        $this->category=$category;
        $this->gameTypeId=$gameTypeId;
        $this->hasSubCategory=$hasSubCategory;
        $this->pageType=$pageType;
    }
    //
    

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.related-products');
    }
}