<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Select extends Component
{
    public $multiple;
    public $search;
    public $alignRight;
    public $fieldLabel;
    public $fieldRequired;
    public $fieldName;
    public $fieldId;
    public $popover;
    public $disabled;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $fieldName,
        $fieldId,
        $fieldRequired = false,
        $fieldLabel = null,
        $disabled = false,
        bool $multiple = false,
        bool $search = false,
        bool $alignRight = false,
        $popover = null
    ) {
        $this->fieldName = $fieldName;
        $this->fieldLabel = $fieldLabel;
        $this->fieldId = $fieldId;
        $this->fieldRequired = $fieldRequired;
        $this->disabled = $disabled;
        $this->multiple = $multiple;
        $this->search = $search;
        $this->popover = $popover;
        $this->alignRight = $alignRight;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.select');
    }
}
