<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\RecentView;
use App\Model\Product;
use App\Model\Composition;

class RecentViewController extends Controller
{
    public function get(Request $request)
    {
      // dummy -- auth_user()->id == 1
      $recent_views = RecentView::where('user_id', 1)
                          ->where('type',$request->type)
                          ->pluck('item_id')
                          ->toArray();
      $items = [];
      if($request->type == "products"){
        $items = Product::whereIn('id',$recent_views)->get();
      }else{
        $items = Composition::whereIn('id',$recent_views)->get();
      }
      $msg = ['message' => 'data fetch done', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$items),200);
    }


    public function addToRecentView(Request $request)
    {
      if(RecentView::where('user_id', 1)->where('type',$request->type)->where('item_id',$request->item_id)->exists()){
            $msg = ['message' => 'Item have already in Recent View', 'status' => 'info', 'success' => false];
            return response()->json(output($msg,[]),200);
      }

      $wishlists = new RecentView;
      $wishlists->user_id = 1;
      $wishlists->item_id = $request->item_id;
      $wishlists->type = $request->type;
      $wishlists->save();
      $msg = ['message' => 'Item added in Recent View', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,[]),200);
    }
}
