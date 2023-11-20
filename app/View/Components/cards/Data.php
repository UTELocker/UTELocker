<?php

namespace App\View\Components\Cards;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Data extends Component
{
    public $title;
    public $padding;
    public $otherClasses;
    public $height;
    public $action;
    /**
     * Create a new component instance.
     */
    public function __construct($title = false, $padding = true, $otherClasses = '', $height = 'auto', $action = '')
    {
        $this->title = $title;
        $this->padding = $padding;
        $this->otherClasses = $otherClasses;
        $this->height = $height;
        $this->action = $action;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cards.data');
    }
}
