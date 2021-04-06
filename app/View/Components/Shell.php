<?php

namespace App\View\Components;

use App\Models\Store;
use Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class Shell extends Component {

    public $routeName;
    public $translations;


    /**
     * Create a new component instance.
     *
     */
    public function __construct() {
        $this->routeName    = Route::currentRouteName();
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

    public function store(): Store {
        return Auth::user()->customer->store;
    }
}
