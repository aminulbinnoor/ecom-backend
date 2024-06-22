<?php

namespace App\Http\Middleware;

use Closure;

class Authorization
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
        $code = "";
        $setupCode = "VCVRb99R4cCSw00Kv500HIKNIktDtKNTk5GkfNvF4rs";

        if ($request->header('authorization')) {
            $split = explode(" ",$request->header('authorization'));
            $code = isset($split[1]) ? $split[1] : '';
        }
        if($request->header('authorization') && $setupCode == $code){
            return $next($request);
        }else{
            $msg = ['message' => 'unauthorized request','type' => 'info', 'status' => true];
            return response()->json(output($msg,[]),401);
        }

    }
}
