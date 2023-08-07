<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LinkSecondary extends Component
{
    public $icon;
    public $link;

    /**
     * Create a new component instance.
     */
    public function __construct($link, $icon = '')
    {
        $this->icon = $icon;
        $this->link = $link;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.link-secondary');
    }
}
