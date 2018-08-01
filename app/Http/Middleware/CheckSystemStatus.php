<?php

namespace App\Http\Middleware;

use Closure;

class CheckSystemStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        if ( \DB::table('config_vars')->where('con_name', 'SYSTEM_STATUS' )->get()[0]->con_value !== '1' ) {
            return redirect('/offline');
        }
        
        return $next($request);
    }
}
