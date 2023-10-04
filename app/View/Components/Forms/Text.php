<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Text extends BaseFormComponent
{
    public $fieldHelp;
    public ?bool $fieldReadOnly;
    /**
     * Create a new component instance.
     */
    public function __construct(
        $fieldLabel,
        $fieldName,
        $fieldId,
        $fieldRequired = false,
        $fieldPlaceholder = null,
        $fieldValue = null,
        $fieldHelp = null,
        $fieldReadOnly = false,
        $popover = null
    ) {
        parent::__construct(
            $fieldLabel,
            $fieldName,
            $fieldId,
            $fieldRequired,
            $fieldPlaceholder,
            $fieldValue,
            $popover
        );
        $this->fieldHelp = $fieldHelp;
        $this->fieldReadOnly = $fieldReadOnly;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.text');
    }
}
