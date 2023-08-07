<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MenuItem extends Component
{
    public $icon;
    public $text;
    public $link;
    public $active;
    public $addon;
    public $count;

    /**
     * Create a new component instance.
     */
    public function __construct($icon, $text, $link = null, $active = false, $addon = false, $count = 0)
    {
        $this->icon = $icon;
        $this->text = $text;
        $this->link = $link;
        $this->active = $active;
        $this->addon = $addon;
        $this->count = $count;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.menu-item');
    }
}
