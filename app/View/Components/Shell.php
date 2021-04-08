<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Wed, 07 Apr 2021 00:09:08 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\View\Components;

use Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class Shell extends Component {

    public $routeName;
    public $translations;
    public $user;


    /**
     * Create a new component instance.
     *
     */
    public function __construct() {
        $this->routeName    = Route::currentRouteName();
        $this->user    = Auth::user();

        $this->translations = [
            'title' => [
                'dashboard' => __('Dashboard'),
                'products'  => __('Products'),
                'orders'    => __('Orders'),
            ]
        ];

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render() {
        return view('components.shell');
    }

    public function _($index): string {
        return data_get($this->translations, $index);
    }

    public function title(): string {
        return data_get($this->translations['title'], $this->routeName);
    }


}
