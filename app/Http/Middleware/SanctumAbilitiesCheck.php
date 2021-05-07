<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 07 May 2021 17:27:05 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SanctumAbilitiesCheck
{
    public function handle(Request $request, Closure $next, ...$abilities)
    {
        foreach ($abilities as $ability) {
            if (!$request->user()->tokenCan($ability)) {
                abort(400, 'Access denied');
            }
        }

        return $next($request);
    }
}
