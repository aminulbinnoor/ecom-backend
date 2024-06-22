<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Composition;
use App\Model\Category;
use App\Model\Theme;
use App\Model\Product;
use Illuminate\Support\Facades\Redis;

class TopFeatureController extends Controller
{
    public function looks(Request $request)
    {


      if(redis_exists('tflooks') && config('p2p.redis')) {
          $themes = json_decode(get_redis('tflooks'));
      }else{
          $themes = Composition::take(8)->get();
          foreach ($themes as $key => $value) {
              $a = '';
              $redirect_slug = (Category::where('id',$value->category_id)->first())['slug'].'/'.(Theme::where('id',$value->theme_id)->first())['slug'].'/'.$value->slug;
              foreach ($value->specification as $key2 => $value2) {
                  $a = $a .  $value2->value . ' ' . $value2->name . ', ';
              }
              $value->look_specification = $a;
              $value->redirect_slug = $redirect_slug;
          }

          set_redis('tflooks',\json_encode($themes));
      }



      $msg = ['message' => 'look fetch done', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$themes),200);
    }

    public function products()
    {

        if(get_redis('tfproducts') != null && config('p2p.redis')) {
            $products = json_decode(get_redis('tfproducts'));
        }else{
            $products = Product::take(8)->get();
            \set_redis('tfproducts',json_encode($products));
        }

        $msg = ['message' => 'product fetch done', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$products),200);

    }
}
