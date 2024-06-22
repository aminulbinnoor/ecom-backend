<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Category;
use App\Model\Theme;

class CategoryController extends Controller
{
  public function getTheme(Request $request)
  {

      if(redis_exists('catGetTheme') && config('p2p.redis')) {
          $style = json_decode(get_redis('catGetTheme'));
      }else{
          $category = Category::where('slug',$request->slug)->first();
          $style = Theme::where('category_id',$category->id)->get();
          \set_redis('catGetTheme',json_encode($style));
      }

    $msg = ['message' => 'Room Wise Product list', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$style),200);
  }
}
