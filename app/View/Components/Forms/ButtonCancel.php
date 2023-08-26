<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ButtonCancel extends Component
{
    public $link;

    /**
     * Create a new component instance.
     */
    public function __construct($link = 'javascript:;')
    {
        $this->link = $link;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.button-cancel');
    }
}
