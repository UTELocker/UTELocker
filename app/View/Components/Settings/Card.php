<?php

namespace App\View\Components\Settings;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    public string $method;

    /**
     * Create a new component instance.
     */
    public function __construct($method = 'PUT')
    {
        $this->method = $method;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.settings.card');
    }
}
