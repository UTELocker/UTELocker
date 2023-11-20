<?php

namespace App\View\Components\Cards;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DataRow extends Component
{
    public $label;
    public $value;
    public $html;
    /**
     * Create a new component instance.
     */
    public function __construct($label, $value, $html = false)
    {
        $this->label = $label;
        $this->value = $value;
        $this->html = $html;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cards.data-row');
    }
}
