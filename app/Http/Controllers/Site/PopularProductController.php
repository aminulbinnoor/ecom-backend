<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Model\PopularProduct;
use Illuminate\Http\Request;
use App\Model\Composition;
use App\Model\Product;

class PopularProductController extends Controller
{
  public function get(Request $request)
  {
    // dummy -- auth_user()->id == 1
    $wishlists = PopularProduct::where('user_id', 1)
                        ->where('type',$request->type)
                        ->pluck('item_id')
                        ->toArray();
    $items = [];
    if($request->type == "products"){
      $items = Product::whereIn('id',$wishlists)->get();
    }else{
      $items = Composition::whereIn('id',$wishlists)->get();
    }
    $msg = ['message' => 'data fetch done', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$items),200);

  }
}
