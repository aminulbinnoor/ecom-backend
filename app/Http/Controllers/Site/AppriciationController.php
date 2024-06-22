<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Appriciation;

class AppriciationController extends Controller
{
    public function lists(Request $request)
    {

        if(redis_exists('partner') && config('p2p.redis')) {
            $partners = json_decode(get_redis('partner'));
        }else{
             $partners = Appriciation::where('status',1)->get();
            \set_redis('partner',json_encode($partners));
        }


      $msg = ['message' => 'Appriciation fetch done', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$partners),200);
    }
}
