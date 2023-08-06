<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputGroup extends Component
{
    public $append;
    public $preappend;
    public $prepend;
    /**
     * Create a new component instance.
     */
    public function __construct($prepend = false, $append = false, $preappend = false)
    {
        $this->prepend = $prepend;
        $this->append = $append;
        $this->preappend = $preappend;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.input-group');
    }
}
