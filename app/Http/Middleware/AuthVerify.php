<?php

namespace App\Http\Middleware;

use \Firebase\JWT\JWT;
use Closure;

class AuthVerify
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
        $id = "";
        try {
            $decode = JWT::decode($request->header('jwt'), 'p2p', array('HS256'));
            $id  = "done";
        } catch (\Exception $e) {
            $id = "";
        }

        if($id !== ""){
            return $next($request);
        }else{
            $msg = ['message' => 'unauthorized request','type' => 'info', 'status' => true];
            return response()->json(output($msg,[]),401);
        }

    }
}
