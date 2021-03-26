<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 26 Mar 2021 15:40:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\View\Components;

use Illuminate\View\Component;

class Breadcrumbs extends Component {

    public $title;

    /**
     * Create a new component instance.
     *
     * @param $title
     */
    public function __construct($title) {
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render() {
        return view('components.breadcrumbs');
    }
}
