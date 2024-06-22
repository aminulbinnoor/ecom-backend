<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\BrandPartner;

class BrandPartnerController extends Controller
{
    public function lists(Request $request)
    {
        if(redis_exists('brand_partner') && config('p2p.redis')) {
            $partners = json_decode(get_redis('brand_partner'));
        }else{
             $partners = BrandPartner::where('status',1)->get();
            \set_redis('brand_partner',json_encode($partners));
        }
      $msg = ['message' => 'brand image fetch done', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$partners),200);
    }
}
