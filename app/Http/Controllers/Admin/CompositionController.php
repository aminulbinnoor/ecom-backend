<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Model\Composition;
use App\Model\Room;

class CompositionController extends Controller
{
  public function create(Request $request)
  {
    if(Composition::where('slug',Str::slug($request->name,'-'))->exists()){
        $msg = ['message' => 'Composition already exits', 'status' => 'info', 'success' => false];
        return response()->json(output($msg,[]),200);
    }
       $composition = new Composition;
       $composition->category_id = $request->category_id;
       $composition->theme_id = $request->theme_id;
       $composition->name = $request->name;
       $composition->short_description = $request->short_description;
       $composition->description = $request->description;
       $composition->min_price = $request->min_price;
       $composition->max_price = $request->max_price;
       $composition->costing_price = $request->costing_price;
       $composition->specification = json_encode(json_decode($request->specification));
       $composition->is_active = $request->status;
       $composition->slug = Str::slug($request->name,'-');
       $composition->composition_code = $request->composition_code;
       $composition->save();

       if ($request->hasFile('composition_image')) {
           $cimages = $request->file('composition_image');
            foreach ($cimages as $key => $cimage) {
                $path = 'composition/'.md5($composition->id).'/';
                $file = $composition->slug.'-'.$key.'.png';
                image_upload_base64($path, $file,$cimage,p2p_drive());
                $cimages[$key] = ['path' => $path , 'file' => $file];
            }
         $composition->images = json_encode($cimages);
       }
       $composition->save();


     $msg = ['message' => 'Composition created', 'status' => 'info', 'success' => true];
     return response()->json(output($msg,[]),200);
  }

  public function get(Request $request)
  {
    if ($request->id) {
        $composition = Composition::where('id',$request->id)->first();

        $msg = ['message' => 'Composition retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$composition),200);
    }elseif ($request->show_all) {
        $composition = Composition::all();
        $msg = ['message' => 'Composition retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$composition),200);
     }

        $per_page = $request->per_page ?? 2;
        $orders = Composition::paginate($per_page);
        $msg = ['message' => 'Composition retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$orders),200);
  }

  public function update(Request $request)
  {
    if(Composition::where('id','!=',$request->id)->where('slug',Str::slug($request->name,'-'))->exists()){
        $msg = ['message' => 'Composition already exits', 'status' => 'info', 'success' => false];
        return response()->json(output($msg,[]),200);
    }
        $composition = Composition::where('id',$request->id)
                      ->update([
                        'category_id' => $request->category_id,
                        'theme_id' => $request->theme_id,
                        'name' => $request->name,
                        'short_description' => $request->short_description,
                        'description' => $request->description,
                        'min_price' => $request->min_price,
                        'max_price' => $request->max_price,
                        'costing_price' => $request->costing_price,
                        'specification' => json_encode(json_decode($request->specification)),
                        'is_active' => $request->status,
                        'slug' => Str::slug($request->name,'-'),
                        'composition_code' => $request->composition_code
                          ]);
        $composition = Composition::where('id',$request->id)->first();
            if ($request->hasFile('upload_composition_images')) {
               $path = 'composition/'.md5($composition->id);
                if(Storage::disk('s3')->exists($path)){
                    Storage::disk('s3')->delete($path);
                }
                $cimages = $request->file('upload_composition_images');
                 foreach ($cimages as $key => $cimage) {
                     $path = 'composition/'.md5($composition->id).'/';
                     $file = $composition->slug.'-'.$key.'.png';
                     image_upload_base64($path, $file,$cimage,p2p_drive());
                     $cimages[$key] = ['path' => $path , 'file' => $file];
                 }
              $composition->images = json_encode($cimages);
              $composition->save();
            }
        $msg = ['message' => 'Composition updated', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$composition),200);
  }

  public function delete(Request $request)
  {
    $category = Composition::where('id',$request->id)
                         ->delete();
    $msg = ['message' => 'Composition Deleted', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,[]),200);

  }

  public function getCompositionWiseRoom (Request $request, $id) {
    $rooms = Room::where('composition_id',$id)->get();
    $msg = ['message' => 'Room retrived', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$rooms),200);
  }
}
