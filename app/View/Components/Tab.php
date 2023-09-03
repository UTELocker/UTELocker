<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tab extends Component
{
    public $href;
    public $text;
    public $ajax;
    /**
     * Create a new component instance.
     */
    public function __construct($href, $text, $ajax = 'true')
    {
        $this->href = $href;
        $this->text = $text;
        $this->ajax = $ajax;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.tab');
    }
}
