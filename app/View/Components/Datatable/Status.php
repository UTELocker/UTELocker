<?php

namespace App\View\Components\Datatable;

use App\Classes\CommonConstant;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Status extends Component
{
    public string $status;
    public bool $isActive = false;

    /**
     * Create a new component instance.
     */
    public function __construct(string $status = CommonConstant::DATABASE_NO)
    {
        $this->status = $status;
        $this->isActive = $status === CommonConstant::DATABASE_YES;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.datatable.status', [
            'isActive' => $this->isActive,
            'status' => $this->status
        ]);
    }
}
