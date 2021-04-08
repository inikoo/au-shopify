<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Wed, 07 Apr 2021 00:08:32 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\View\Components;

use App\Models\User;
use Illuminate\View\Component;

class Dashboard extends Component {

    public $user;

    /**
     * Create a new component instance.
     *
     * @param \App\Models\User     $user
     */
    public function __construct(User $user) {
        $this->user     = $user;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render() {
        return view('components.dashboard');
    }
}
