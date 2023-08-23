<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Email extends Component
{
    public $fieldLabel;
    public $fieldRequired;
    public $fieldPlaceholder;
    public $fieldValue;
    public $fieldName;
    public $fieldId;
    public $popover;
    public $fieldHelp;
    /**
     * Create a new component instance.
     */
    public function __construct(
        $fieldLabel,
        $fieldPlaceholder,
        $fieldName,
        $fieldId,
        $fieldRequired = false,
        $fieldValue = null,
        $popover = null,
        $fieldHelp = null
    ) {
        $this->fieldLabel = $fieldLabel;
        $this->fieldRequired = $fieldRequired;
        $this->fieldPlaceholder = $fieldPlaceholder;
        $this->fieldValue = $fieldValue;
        $this->fieldName = $fieldName;
        $this->fieldId = $fieldId;
        $this->popover = $popover;
        $this->fieldHelp = $fieldHelp;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.email');
    }
}
