<?php

namespace App\View\Components\Filters;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FilterBox extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.filters.filter-box');
    }
}
