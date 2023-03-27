<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class adminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = Auth::user(); // get the authenticated user

            $role_id = $user->role_id; // get the user's role

            if ($role_id == 1) {
                //return $next();
                // return 1;///response()->json(['message' => 'you are not admin']);
                return 1;
            } else {
                return $next($request);
            }
        } catch (Exception $e) {

            // if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {

            //     return $this->returnError("E005", "Token is Invalid");
            // } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {

            //     return $this->returnError("E006", "Token is Expired");
            // } else {

            //     return $this->returnError("E007", "Authorization Token not found");
            // }
        }
    }
}
