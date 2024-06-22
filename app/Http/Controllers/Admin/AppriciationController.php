<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Model\Appriciation;

class AppriciationController extends Controller
{

  public function create(Request $request)
  {
     $appriciation = new Appriciation;
     $appriciation->client_name = $request->client_name;
     $appriciation->address = $request->address;
     $appriciation->name = $request->name;
     $appriciation->slug = Str::slug($request->client_name,'-');
     $appriciation->type = $request->type;
     $appriciation->url = $request->url;
     $appriciation->status = $request->status;
     $appriciation->save();
     $appriciation->slug = Str::slug($request->client_name,'-') .'-'. $appriciation->id;

     if ($request->hasFile('thumbnail_image')) {
         $appriciationTImages = $request->file('thumbnail_image');
          foreach ($appriciationTImages as $key => $pimage) {
              $path = 'appriciations/'.md5($appriciation->id).'/thumbnail/';
              $file = $appriciation->slug.'-'.$key.'.png';
              image_upload_base64($path, $file,$pimage,p2p_drive());
              $appriciationTImages[$key] = ['path' => $path , 'file' => $file];
          }
          $appriciation->thumbnail_image = json_encode($appriciationTImages);
     }
     $appriciation->save();

     $msg = ['message' => 'Appriciation created', 'status' => 'info', 'success' => true];
     return response()->json(output($msg,[]),200);
  }

  public function get(Request $request)
  {
    if ($request->id) {
        $appriciation = Appriciation::where('id',$request->id)->first();
        $msg = ['message' => 'Appriciation retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$appriciation),200);
    }elseif ($request->show_all) {
        $appriciation = Appriciation::all();
        $msg = ['message' => 'Appriciation retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$appriciation),200);
   }

        $per_page = $request->per_page ?? 2;
        $appriciations = Appriciation::paginate($per_page);
        $msg = ['message' => 'Appriciation retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$appriciations),200);
  }

  public function update(Request $request)
  {
     $appriciation = Appriciation::where('id',$request->id)
                                 ->update([
                            'name' => $request->name,
                            'client_name' => $request->client_name,
                            'address' => $request->address,
                            'slug' => Str::slug($request->client_name,'-'),
                            'type' => $request->type,
                            'url' => $request->url,
                            'status' => $request->status,
                          ]);

    $appriciation =  Appriciation::where('id',$request->id)->first();
    $appriciation->slug = Str::slug($request->client_name,'-') .'-'. $appriciation->id;
    $appriciationTImageDeleteAfter = json_decode($request->deleted_after_appriciation_timages);
    $pimages = [];

    if ($request->deleted_appriciation_timages) {
       $deletedTImages = json_decode($request->deleted_appriciation_timages);

       foreach ($deletedTImages as $key => $value) {
           foreach (image_upload_size() as $sizekey => $size) {
               $path = $value->path.'/'.$sizekey.'/'.$value->file;
               if(Storage::disk('s3')->exists($path)){
                   Storage::disk('s3')->delete($path);
               }
           }
       }
   }

   if ($request->hasFile('thumbnail_image')) {
       $appriciationTImages = $request->file('thumbnail_image');
       foreach ($appriciationTImages as $key => $pimage) {
           $path = 'appriciations/'.md5($appriciation->id).'/thumbnail/';
           $file = $appriciation->slug.'-'.$key.'.png';
           image_upload_base64($path, $file,$pimage,p2p_drive());
           $appriciationTImages[$key] = ['path' => $path , 'file' => $file];
       }
       $appriciation->thumbnail_image = json_encode($appriciationTImages);
   }
   $appriciation->save();
   $msg = ['message' => 'Appriciation updated', 'status' => 'info', 'success' => true];
   return response()->json(output($msg,[]),200);
  }

  public function delete(Request $request)
  {
    $category = Appriciation::where('id',$request->id)
                         ->delete();
    $msg = ['message' => 'Appriciation Deleted', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,[]),200);

  }

}
