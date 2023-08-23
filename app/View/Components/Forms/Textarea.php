<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;

class Textarea extends BaseFormComponent
{
    public function render(): View|Closure|string
    {
        return view('components.forms.textarea');
    }
}
