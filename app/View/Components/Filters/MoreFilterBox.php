<?php

namespace App\View\Components\Filters;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MoreFilterBox extends Component
{
    public $extraSlot;

    /**
     * Create a new component instance.
     */
    public function __construct($extraSlot = false)
    {
        $this->extraSlot = $extraSlot;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.filters.more-filter-box');
    }
}
