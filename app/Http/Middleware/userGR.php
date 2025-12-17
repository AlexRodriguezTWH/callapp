<?php

namespace App\Http\Middleware;

use Closure;

class userGR
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
      if( session()->has('gr_user') == NULL ){
          return Redirect()->route('login.index');
      }

      return $next($request);
    }
}
