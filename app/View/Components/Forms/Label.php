<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Label extends Component
{
    public $fieldId;
    public $fieldLabel;
    public $popover;
    public $fieldRequired;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $fieldId,
        $fieldRequired = false,
        $fieldLabel = null,
        $popover = null
    ) {
        $this->fieldLabel = $fieldLabel;
        $this->fieldId = $fieldId;
        $this->popover = $popover;
        $this->fieldRequired = $fieldRequired;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.label');
    }
}
