<?php

namespace App\Http\Middleware;

use Closure;
use App\token;

class AuthMiddleware
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
        $token = $request->header('Authorization');
        $token = hash('sha1', $token);
        if($tokenData = token::where('token', $token)->where('revoke', 0)->first()){
            auth()->loginUsingId($tokenData->user_id);
        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        return $next($request);
    }
}
