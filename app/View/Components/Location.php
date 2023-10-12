<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Location extends Component
{
    private $location;
    /**
     * Create a new component instance.
     */
    public function __construct($location)
    {
        $this->location = $location;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.location', [
            'location' => $this->location,
        ]);
    }
}
