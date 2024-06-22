<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Composition;
use App\Model\WishList;
use App\Model\Product;

class WishListController extends Controller
{
    public function get(Request $request)
    {
      // dummy -- auth_user()->id == 1
      $wishlists = WishList::where('user_id', 1)
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

    public function addToWishLists(Request $request)
    {
      if(WishList::where('user_id', 1)->where('type',$request->type)->where('item_id',$request->item_id)->exists()){
            $msg = ['message' => 'Item have already in wishlists', 'status' => 'info', 'success' => false];
            return response()->json(output($msg,[]),200);
      }

      $wishlists = new WishList;
      $wishlists->user_id = 1;
      $wishlists->item_id = $request->item_id;
      $wishlists->type = $request->type;
      $wishlists->save();
      $msg = ['message' => 'Item added in wishlists', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,[]),200);
    }
}
