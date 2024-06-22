<?php

namespace App\Http\Controllers\Site;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\HeroSlider;
use Illuminate\Support\Facades\Redis;

class HeroSliderController extends Controller
{
  public function lists(Request $request)
  {
    if(redis_exists('hsliders') && config('p2p.redis')) {
        $hsliders = json_decode(get_redis('hsliders'));
    }else{
        $hsliders = HeroSlider::where('status',1)->get();
        set_redis('hsliders',\json_encode($hsliders));
    }


    $msg = ['message' => 'hero slider images fetch done', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$hsliders),200);
  }

}
