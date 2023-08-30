<?php

namespace App\View\Components\Settings;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MenuItem extends Component
{
    public ?string $text;
    public ?string $href;
    public ?bool $active;
    public mixed $menu;

    /**
     * Create a new component instance.
     */
    public function __construct($href, $text, $menu, $active = false)
    {
        $this->text = $text;
        $this->href = $href;
        $this->active = $active;
        $this->menu = $menu;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.settings.menu-item');
    }

    public function isActive($option): bool
    {
        return $option === $this->active;
    }
}
