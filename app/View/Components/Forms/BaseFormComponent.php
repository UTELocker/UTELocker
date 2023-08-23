<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class BaseFormComponent extends Component
{
    public ?string $fieldLabel;
    public ?string $fieldRequired;
    public ?string $fieldPlaceholder;
    public ?string $fieldValue;
    public ?string $fieldName;
    public ?string $fieldId;
    public ?string $popover;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $fieldLabel,
        $fieldName,
        $fieldId,
        $fieldRequired = false,
        $fieldPlaceholder = null,
        $fieldValue = null,
        $popover = null
    ) {
        $this->fieldLabel = $fieldLabel;
        $this->fieldRequired = $fieldRequired;
        $this->fieldPlaceholder = $fieldPlaceholder;
        $this->fieldValue = $fieldValue;
        $this->fieldName = $fieldName;
        $this->fieldId = $fieldId;
        $this->popover = $popover;
    }

    public function render()
    {
        //
    }
}
