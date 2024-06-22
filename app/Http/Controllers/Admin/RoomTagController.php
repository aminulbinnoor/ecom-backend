<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Model\RoomTag;

class RoomTagController extends Controller
{
  public function create(Request $request)
  {
    $room_tag = new RoomTag;
    $room_tag->name = $request->name;
    $room_tag->short_description = $request->short_description;
    $room_tag->slug = Str::slug($request->name,'-');
    $room_tag->is_active = $request->status ?? true;
    $room_tag->save();

    $room_tag->slug = Str::slug($request->name,'-') .'-'. $room_tag->id;

   if ($request->hasFile('images')) {
       $room_tagImages = $request->file('images');
        foreach ($room_tagImages as $key => $pimage) {
            $path = 'roomtags/'.md5($room_tag->id).'/';
            $file = $room_tag->slug.'-'.$key.'.png';
            image_upload_base64($path, $file,$pimage,p2p_drive());
            $room_tagImages[$key] = ['path' => $path , 'file' => $file];
        }
    $room_tag->images = json_encode($room_tagImages);
   }
   $room_tag->save();

   $msg = ['message' => 'Room Tag created', 'status' => 'info', 'success' => true];
   return response()->json(output($msg,[]),200);
}

public function get(Request $request)
{
  if ($request->id) {
    $room = RoomTag::where('id',$request->id)->first();
    $msg = ['message' => 'Room retrived', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$room),200);
  }elseif ($request->show_all) {
    $room = RoomTag::all();
    $msg = ['message' => 'RoomTag retrived', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$room),200);
   }

  $per_page = $request->per_page ?? 2;
  $rooms = RoomTag::paginate($per_page);
  $msg = ['message' => 'RoomTag retrived', 'status' => 'info', 'success' => true];
  return response()->json(output($msg,$rooms),200);
}

public function update(Request $request)
{
  if(RoomTag::where('id','!=',$request->id)->where('slug',Str::slug($request->name,'-'))->exists()){
    $msg = ['message' => 'RoomTag already exits', 'status' => 'info', 'success' => false];
    return response()->json(output($msg,[]),200);
  }
    $room_tag =  RoomTag::where('id',$request->id)
                 ->update([
                   'name' => $request->name,
                   'short_description' => $request->short_description,
                   'is_active' => $request->status,
                   'slug' => Str::slug($request->name,'-'),
                 ]);
    $room_tag =  RoomTag::where('id',$request->id)->first();
    $room_tag->slug = Str::slug($request->name,'-') . '-'.$room_tag->id;
     if ($request->hasFile('upload_images')) {
         $path = 'roomtags/'.md5($room_tag->id);
          if(Storage::disk('s3')->exists($path)){
              Storage::disk('s3')->delete($path);
          }

         $room_tagImages = $request->file('upload_images');
         foreach ($room_tagImages as $key => $pimage) {
             $path = 'rooms/'.md5($room_tag->id).'/';
             $file = $room_tag->slug.'-'.$key.'.png';
             image_upload_base64($path, $file,$pimage,p2p_drive());
             $room_tagImages[$key] = ['path' => $path , 'file' => $file];
         }
         $room_tag->images = json_encode($room_tagImages);
      }

    $room_tag->save();
    $msg = ['message' => 'RoomTag updated', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,[]),200);
}

public function delete(Request $request)
{
  $room_tag = RoomTag::where('id',$request->id)->delete();
  $msg = ['message' => 'RoomTag Deleted', 'status' => 'info', 'success' => true];
  return response()->json(output($msg,[]),200);

}
}
