<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ButtonPrimary extends Component
{
    public $icon;
    public $disabled;
    public $type;

    /**
     * Create a new component instance.
     */
    public function __construct($icon = '', $disabled = false, $type = 'button')
    {
        $this->icon = $icon;
        $this->disabled = $disabled;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.button-primary');
    }
}
