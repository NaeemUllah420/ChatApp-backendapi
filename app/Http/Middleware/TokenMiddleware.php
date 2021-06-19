<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class TokenMiddleware
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
        $token=$request->header('token');
        $user=User::where('token',$token)->first();
        if(!empty($token) && !empty($user))
        {
            $request->user=$user;
        }
        else
        {
            return response()->forbidden(); ;
        }
        return $next($request);
    }
}
