<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class CheckStatus
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
         //  return $next($request);
      $response = $next($request);
      //If the status is not approved redirect to login 
      if(Auth::check() && Auth::user()->status == '0'){
          Auth::logout();
          return redirect('/login')->with('error', 'Admin Deactivate Your Account Please Contact to Admin');
      }
      return $response;
    }
}
