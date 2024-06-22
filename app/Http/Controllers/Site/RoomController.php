<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ProductRoom;
use App\Model\Product;
use App\Model\Room;
use App\Model\RoomTag;

class RoomController extends Controller
{
  public function roomWiseProduct(Request $request)
  {
    $room = Room::where('slug',$request->slug)->first();
    $rp = ProductRoom::where('room_id',$room->id)->pluck('product_id')->toArray();
    $products = Product::whereIn('id',$rp)
                       ->get()
                       ->groupBy('product_category_name');
    $msg = ['message' => 'Room Wise Product list', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$products),200);
  }

  public function similarProduct(Request $request)
  {
    $products = Product::get();
    $msg = ['message' => 'Similar Product list', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$products),200);
  }

  public function roomTags(Request $request)
  {
    if (redis_exists('roomTags') && config('p2p.redis')) {
      $tags = json_decode(get_redis('roomTags'));
    }else {
      $tags =  RoomTag::all();
      set_redis('roomTags',\json_encode($tags));
    }
     $msg = ['message' => 'retrieve room tags ', 'status' => 'info', 'success' => true];
     return response()->json(output($msg,$tags),200);

  }
}
