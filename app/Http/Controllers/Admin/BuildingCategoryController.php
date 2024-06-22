<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Model\BuildingCategory;

class BuildingCategoryController extends Controller
{
  public function create(Request $request)
  {
    $building_cat = new BuildingCategory;
    $building_cat->name = $request->name;
    $building_cat->short_description = $request->short_description;
    $building_cat->slug = Str::slug($request->name,'-');
    $building_cat->status = $request->status ?? true;
    $building_cat->save();

    $building_cat->slug = Str::slug($request->name,'-') .'-'. $building_cat->id;

   if ($request->hasFile('images')) {
       $building_catImages = $request->file('images');
        foreach ($building_catImages as $key => $pimage) {
            $path = 'building-category/'.md5($building_cat->id).'/';
            $file = $building_cat->slug.'-'.$key.'.png';
            image_upload_base64($path, $file,$pimage,p2p_drive());
            $building_catImages[$key] = ['path' => $path , 'file' => $file];
        }
    $building_cat->images = json_encode($building_catImages);
   }
   $building_cat->save();

   $msg = ['message' => 'Building Category created', 'status' => 'info', 'success' => true];
   return response()->json(output($msg,[]),200);
}

public function get(Request $request)
{
  if ($request->id) {
    $building_cat = BuildingCategory::where('id',$request->id)->first();
    $msg = ['message' => 'Building Category retrived', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$building_cat),200);
  }elseif ($request->show_all) {
    $building_cat = BuildingCategory::all();
    $msg = ['message' => 'Building Category retrived', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$building_cat),200);
   }

  $per_page = $request->per_page ?? 2;
  $building_cats = BuildingCategory::paginate($per_page);
  $msg = ['message' => 'Building Category retrived', 'status' => 'info', 'success' => true];
  return response()->json(output($msg,$building_cats),200);
}

public function update(Request $request)
{
  if(BuildingCategory::where('id','!=',$request->id)->where('slug',Str::slug($request->name,'-'))->exists()){
    $msg = ['message' => 'BuildingCategory already exits', 'status' => 'info', 'success' => false];
    return response()->json(output($msg,[]),200);
  }
    $building_cat =  BuildingCategory::where('id',$request->id)
                 ->update([
                   'name' => $request->name,
                   'short_description' => $request->short_description,
                   'status' => $request->status,
                   'slug' => Str::slug($request->name,'-'),
                 ]);
    $building_cat =  BuildingCategory::where('id',$request->id)->first();
    $building_cat->slug = Str::slug($request->name,'-') . '-'.$building_cat->id;
     if ($request->hasFile('upload_images')) {
         $path = 'building-category/'.md5($building_cat->id);
          if(Storage::disk('s3')->exists($path)){
              Storage::disk('s3')->delete($path);
          }

         $building_catImages = $request->file('upload_images');
         foreach ($building_catImages as $key => $pimage) {
             $path = 'building-category/'.md5($building_cat->id).'/';
             $file = $building_cat->slug.'-'.$key.'.png';
             image_upload_base64($path, $file,$pimage,p2p_drive());
             $building_catImages[$key] = ['path' => $path , 'file' => $file];
         }
         $building_cat->images = json_encode($building_catImages);
      }

    $building_cat->save();
    $msg = ['message' => 'Building Category updated', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,[]),200);
}

public function delete(Request $request)
{
  $building_cat = BuildingCategory::where('id',$request->id)->delete();
  $msg = ['message' => 'Building Category Deleted', 'status' => 'info', 'success' => true];
  return response()->json(output($msg,[]),200);

}
}
