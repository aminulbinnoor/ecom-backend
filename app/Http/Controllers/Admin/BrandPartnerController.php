<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Model\BrandPartner;

class BrandPartnerController extends Controller
{
  public function create(Request $request)
  {
     $bpartner = new BrandPartner;
     $bpartner->name = $request->name;
     $bpartner->slug = Str::slug($request->name,'-');
     $bpartner->status = $request->status;
     $bpartner->save();
     $bpartner->slug = Str::slug($request->name,'-') .'-'. $bpartner->id;

     if ($request->hasFile('images')) {
         $bpartnerImages = $request->file('images');
          foreach ($bpartnerImages as $key => $pimage) {
              $path = 'brand-partner-image/'.md5($bpartner->id).'/';
              $file = $bpartner->slug.'-'.$key.'.png';
              image_upload_base64($path, $file,$pimage,p2p_drive());
              $bpartnerImages[$key] = ['path' => $path , 'file' => $file];
          }
          $bpartner->images = json_encode($bpartnerImages);
     }
     $bpartner->save();

     $msg = ['message' => 'BrandPartner created', 'status' => 'info', 'success' => true];
     return response()->json(output($msg,[]),200);
  }

  public function get(Request $request)
  {
    if ($request->id) {
        $bpartner = BrandPartner::where('id',$request->id)->first();
        $msg = ['message' => 'BrandPartner retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$bpartner),200);
    }elseif ($request->show_all) {
        $bpartner = BrandPartner::all();
        $msg = ['message' => 'BrandPartner retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$bpartner),200);
   }

        $per_page = $request->per_page ?? 2;
        $bpartners = BrandPartner::paginate($per_page);
        $msg = ['message' => 'BrandPartner retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$bpartners),200);
  }

  public function update(Request $request)
  {
     $bpartner = BrandPartner::where('id',$request->id)
                            ->update([
                            'name' => $request->name,
                            'slug' => Str::slug($request->name,'-'),
                            'status' => $request->status,
                          ]);

    $bpartner =  BrandPartner::where('id',$request->id)->first();
    $bpartner->slug = Str::slug($request->name,'-') .'-'. $bpartner->id;

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

   if ($request->hasFile('images')) {
       $appriciationTImages = $request->file('images');
       foreach ($appriciationTImages as $key => $pimage) {
           $path = 'brand-partner-image/'.md5($bpartner->id).'/';
           $file = $bpartner->slug.'-'.$key.'.png';
           image_upload_base64($path, $file,$pimage,p2p_drive());
           $appriciationTImages[$key] = ['path' => $path , 'file' => $file];
       }
       $bpartner->images = json_encode($appriciationTImages);
   }
   $bpartner->save();
   $msg = ['message' => 'BrandPartner updated', 'status' => 'info', 'success' => true];
   return response()->json(output($msg,[]),200);
  }

  public function delete(Request $request)
  {
    $bpartner = BrandPartner::where('id',$request->id)
                         ->delete();
    $msg = ['message' => 'BrandPartner Deleted', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,[]),200);

  }
}
