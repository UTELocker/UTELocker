<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BarChart extends Component
{

    public $chartData;
    public $multiple;
    public $spaceRatio;
    public $prefix;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($chartData, $multiple = false, $spaceRatio = '0.2', $prefix = '')
    {
        $this->chartData = $chartData;
        $this->multiple = $multiple;
        $this->spaceRatio = $spaceRatio;
        $this->prefix = $prefix;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.bar-chart');
    }

}
