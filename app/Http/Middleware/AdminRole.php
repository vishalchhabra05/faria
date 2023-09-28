<?php

namespace App\Http\Middleware;
use Closure;
use Auth;
use App\User;
class AdminRole
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
        if(Auth::user()){
            if (Auth::user()->roles[0]->id == 1) {
                return $next($request);
            }else{
                return redirect('/');    
            }
        }
        else{
            return redirect('login');
         }
    }
}
