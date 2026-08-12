<?php

namespace App\Http\Middleware;

use App\Helpers\GConst;
use Closure;
use Illuminate\Http\Request;

class EnsureDonor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // check if session user is Donor
        $currentRole = $request->session()->get(GConst::SESSION_ROLE);
        if ($currentRole != GConst::SESSION_ROLE_DONOR) {
            return redirect('/')->with('error', 'The entered URL was not valid!');
        }

        return $next($request);
    }
}
