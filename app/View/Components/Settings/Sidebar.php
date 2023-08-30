<?php

namespace App\View\Components\Settings;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public mixed $activeMenu;

    /**
     * Create a new component instance.
     */
    public function __construct($activeMenu)
    {
        $this->activeMenu = $activeMenu;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.settings.sidebar');
    }
}
