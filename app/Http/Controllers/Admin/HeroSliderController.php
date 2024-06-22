<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Model\HeroSlider;

class HeroSliderController extends Controller
{
  public function create(Request $request)
  {
     $hslider = new HeroSlider;
     $hslider->name = $request->name;
     $hslider->slug = Str::slug($request->name,'-');
     $hslider->status = $request->status;
     $hslider->save();
     $hslider->slug = Str::slug($request->name,'-') .'-'. $hslider->id;

     if ($request->hasFile('images')) {
         $hsliderImages = $request->file('images');
          foreach ($hsliderImages as $key => $pimage) {
              $path = 'hero-slider-image/'.md5($hslider->id).'/';
              $file = $hslider->slug.'-'.$key.'.png';
              image_upload_base64($path, $file,$pimage,p2p_drive());
              $hsliderImages[$key] = ['path' => $path , 'file' => $file];
          }
        $hslider->images = json_encode($hsliderImages);
     }
     $hslider->save();

     $msg = ['message' => 'HeroSlider created', 'status' => 'info', 'success' => true];
     return response()->json(output($msg,[]),200);
  }

  public function get(Request $request)
  {
    if ($request->id) {
        $hslider = HeroSlider::where('id',$request->id)->first();
        $msg = ['message' => 'HeroSlider retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$hslider),200);
    }elseif ($request->show_all) {
        $hslider = HeroSlider::all();
        $msg = ['message' => 'HeroSlider retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$hslider),200);
   }

        $per_page = $request->per_page ?? 2;
        $hsliders = HeroSlider::paginate($per_page);
        $msg = ['message' => 'HeroSlider retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$hsliders),200);
  }

  public function update(Request $request)
  {
     $hslider = HeroSlider::where('id',$request->id)
                            ->update([
                            'name' => $request->name,
                            'slug' => Str::slug($request->name,'-'),
                            'status' => $request->status,
                          ]);

    $hslider =  HeroSlider::where('id',$request->id)->first();
    $hslider->slug = Str::slug($request->name,'-') .'-'. $hslider->id;

    $appriciationTImageDeleteAfter = json_decode($request->deleted_after_hslider_timages);
    $pimages = [];

    if ($request->deleted_hslider_timages) {
       $deletedTImages = json_decode($request->deleted_hslider_timages);

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
       $hsliderImages = $request->file('images');
       foreach ($hsliderImages as $key => $pimage) {
           $path = 'hero-slider-image/'.md5($hslider->id).'/';
           $file = $hslider->slug.'-'.$key.'.png';
           image_upload_base64($path, $file,$pimage,p2p_drive());
           $hsliderImages[$key] = ['path' => $path , 'file' => $file];
       }
       $hslider->images = json_encode($hsliderImages);
   }
   $hslider->save();
   $msg = ['message' => 'HeroSlider updated', 'status' => 'info', 'success' => true];
   return response()->json(output($msg,[]),200);
  }

  public function delete(Request $request)
  {
    $hslider = HeroSlider::where('id',$request->id)
                         ->delete();
    $msg = ['message' => 'HeroSlider Deleted', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,[]),200);

  }
}
