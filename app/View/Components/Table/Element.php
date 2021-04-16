<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 16 Apr 2021 19:17:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\View\Components\Table;

use Illuminate\View\Component;

class Element extends Component {

    public $label;
    public $table;
    public $element;
    public $open;

    public function __construct($label, $table, $element, $open) {
        $this->label   = $label;
        $this->table   = $table;
        $this->element = $element;
        $this->open    = $open;
    }


    public function render() {
        return view('components.table.element');
    }
}
