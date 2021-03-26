<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public $label;

    /**
     * Create a new component instance.
     *
     * @param $label
     */
    public function __construct($label) {
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.button');
    }
}
