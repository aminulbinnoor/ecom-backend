<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Model\Composition;
use App\Model\ProductRoom;
use App\Model\Product;
use App\Model\Room;

class RoomController extends Controller
{

    public function create(Request $request)
    {
      // if(Room::where('slug',Str::slug($request->name,'-'))->exists()){
      //   $msg = ['message' => 'Room already exits', 'status' => 'info', 'success' => false];
      //   return response()->json(output($msg,[]),200);
      // }
       $room = new Room;
       $room->name = $request->name;
       $room->category_id = $request->category_id;
       $room->theme_id = $request->theme_id;
       $room->composition_id = $request->composition_id;
       $room->room_tag_id = $request->room_tag_id;
       $room->short_description = $request->short_description;
       $room->description = $request->description;
       $room->length = $request->length;
       $room->width = $request->width;
       $room->slug = Str::slug($request->name,'-');
       $room->is_active = $request->status ?? true;
       $room->save();

       $room->slug = Str::slug($request->name,'-') .'-'. $room->id;

       // if ($request->hasFile('feature_images')) {
       //     $roomFImages = $request->file('feature_images');
       //      foreach ($roomFImages as $key => $pimage) {
       //          $path = 'rooms/'.md5($room->id).'/feature/';
       //          $file = $room->slug.'-'.$key.'.png';
       //          image_upload_base64($path, $file,$pimage,p2p_drive());
       //          $roomFImages[$key] = ['path' => $path , 'file' => $file];
       //      }
       //      $room->feature_images = json_encode($roomFImages);
       // }

       if ($request->hasFile('images')) {
           $roomImages = $request->file('images');
            foreach ($roomImages as $key => $pimage) {
                $path = 'rooms/'.md5($room->id).'/';
                $file = $room->slug.'-'.$key.'.png';
                image_upload_base64($path, $file,$pimage,p2p_drive());
                $roomImages[$key] = ['path' => $path , 'file' => $file];
            }
        $room->feature_images = json_encode($roomImages);
        $room->images = json_encode($roomImages);
       }
       $room->save();

       $msg = ['message' => 'Room created', 'status' => 'info', 'success' => true];
       return response()->json(output($msg,[]),200);
    }

    public function get(Request $request)
    {
      if ($request->id) {
        $room = Room::with(['composition'])->where('id',$request->id)->first();
        $msg = ['message' => 'Room retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$room),200);
      }elseif ($request->show_all) {
        $room = Room::all();
        $msg = ['message' => 'Room retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$room),200);
       }

      $per_page = $request->per_page ?? 2;
      $rooms = Room::with(['composition'])->paginate($per_page);
      $msg = ['message' => 'Room retrived', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$rooms),200);
    }

    public function update(Request $request)
    {
      if(Room::where('id','!=',$request->id)->where('slug',Str::slug($request->name,'-'))->exists()){
        $msg = ['message' => 'Room already exits', 'status' => 'info', 'success' => false];
        return response()->json(output($msg,[]),200);
      }
       $room =  Room::where('id',$request->id)
                     ->update([
                       'name' => $request->name,
                       'category_id' => $request->category_id,
                       'theme_id' => $request->theme_id,
                       'composition_id' => $request->composition_id,
                       'room_tag_id' => $request->room_tag_id,
                       'description' => $request->description,
                       'short_description' => $request->short_description,
                       'description' => $request->description,
                       'length' => $request->length,
                       'width' => $request->width,
                       'is_active' => $request->status,
                       'slug' => Str::slug($request->name,'-'),
                     ]);
        $room =  Room::where('id',$request->id)->first();
        $room->slug = Str::slug($request->name,'-') . '-'.$room->id;
         if ($request->hasFile('upload_images')) {
             $path = 'rooms/'.md5($room->id);
              if(Storage::disk('s3')->exists($path)){
                  Storage::disk('s3')->delete($path);
              }

             $roomImages = $request->file('upload_images');
             foreach ($roomImages as $key => $pimage) {
                 $path = 'rooms/'.md5($room->id).'/';
                 $file = $room->slug.'-'.$key.'.png';
                 image_upload_base64($path, $file,$pimage,p2p_drive());
                 $roomImages[$key] = ['path' => $path , 'file' => $file];
             }
             $room->feature_images = json_encode($roomImages);
             $room->images = json_encode($roomImages);
          }

       $room->save();
       $msg = ['message' => 'Room updated', 'status' => 'info', 'success' => true];
       return response()->json(output($msg,[]),200);
    }

    public function delete(Request $request)
    {
      $room = Room::where('id',$request->id)->delete();
      $msg = ['message' => 'Room Deleted', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,[]),200);

    }

    public function products(Request $request) {
        $per_page = $request->per_page ?? 2;
        $products = Product::where('room_id',$request->room_id)->paginate();
        $msg = ['message' => 'Product retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$products),200);
    }

    public function createRoomProduct(Request $request)
    {
      if(ProductRoom::where('room_id',$request->room_id)->where('product_id',$request->product_id)->exists()){
        $msg = ['message' => 'Product is already exists', 'status' => 'info', 'success' => false];
        return response()->json(output($msg,[]),200);
      }
      if(Product::where('id',$request->product_id)->exists()){
        $data = new ProductRoom;
        $data->product_id = $request->product_id;
        $data->room_id = $request->room_id;
        $data->save();
        $msg = ['message' => 'Room product created', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,[]),200);
      }else{
        $msg = ['message' => 'Product not exists', 'status' => 'info', 'success' => false];
        return response()->json(output($msg,[]),200);
      }

    }

    public function getProduct(Request $request)
    {
      $product_id = ProductRoom::where('room_id',$request->room_id)->pluck('product_id')->toArray();
      $items = [];
      if($product_id){
        $items = Product::whereIn('id',$product_id)->get();
      }

      $msg = ['message' => 'data fetch done', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$items),200);
    }

    public function deleteRoomProduct(Request $request)
    {
      $delete_product_id = ProductRoom::where('room_id',$request->room_id)->where('product_id',$request->product_id)->delete();
      $msg = ['message' => 'Room product deleted', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,[]),200);
    }

}
