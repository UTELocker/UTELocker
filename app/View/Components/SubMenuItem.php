<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SubMenuItem extends Component
{
    public $text;
    public $link;
    public $permission;

    /**
     * Create a new component instance.
     */
    public function __construct($text, $link, $permission = true)
    {
        $this->text = $text;
        $this->link = $link;
        $this->permission = $permission;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sub-menu-item');
    }
}
