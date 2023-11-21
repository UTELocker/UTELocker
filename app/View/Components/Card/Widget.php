<?php

namespace App\View\Components\Cards;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Widget extends Component
{
    public $title;
    public $value;
    public $icon;
    public $info;
    public $widgetId;
    /**
     * Create a new component instance.
     */
    public function __construct($title, $value, $icon, $info = null, $widgetId = null)
    {
        $this->title = $title;
        $this->value = $value;
        $this->icon = $icon;
        $this->info = $info;
        $this->widgetId = $widgetId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cards.widget');
    }
}
