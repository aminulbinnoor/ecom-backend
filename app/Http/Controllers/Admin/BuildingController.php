<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Building;
use Illuminate\Support\Str;

class BuildingController extends Controller
{
  public function create(Request $request)
  {
    $building = new Building;
    $building->building_category_id = $request->building_category_id;
    $building->name = $request->name;
    $building->short_description = $request->short_description;
    $building->description = $request->description;
    $building->min_land_req = $request->min_land_req;
    $building->no_of_floor = $request->no_of_floor;
    $building->total_space_per_floor = $request->total_space_per_floor;
    $building->structure = $request->structure;
    $building->beds = $request->beds;
    $building->bathrooms = $request->bathrooms;
    $building->balcony = $request->balcony;
    $building->garden = $request->garden;
    $building->pool = $request->pool;
    $building->garage_capacity = $request->garage_capacity;
    $building->steel = $request->steel;
    $building->fitting = $request->fitting;
    $building->slug = Str::slug($request->name,'-');
    $building->status = $request->status ?? true;
    $building->save();

    $building->slug = Str::slug($request->name,'-') .'-'. $building->id;

    if ($request->hasFile('images')) {
        $buildingImages = $request->file('images');
         foreach ($buildingImages as $key => $pimage) {
             $path = 'building/'.md5($building->id).'/';
             $file = $building->slug.'-'.$key.'.png';
             image_upload_base64($path, $file,$pimage,p2p_drive());
             $buildingImages[$key] = ['path' => $path , 'file' => $file];
         }
      $building->images = json_encode($buildingImages);
    }

    if ($request->hasFile('feature_image')) {
        $buildingFImages = $request->file('feature_image');
         foreach ($buildingFImages as $key => $pimage) {
             $path = 'building/'.md5($building->id).'/feature/';
             $file = $building->slug.'-'.$key.'.png';
             image_upload_base64($path, $file,$pimage,p2p_drive());
             $buildingFImages[$key] = ['path' => $path , 'file' => $file];
         }
         $building->feature_image = json_encode($buildingFImages);
    }
   $building->save();

   $msg = ['message' => 'Building created succesfully', 'status' => 'info', 'success' => true];
   return response()->json(output($msg,[]),200);
}

public function get(Request $request)
{
  if ($request->id) {
    $building = Building::where('id',$request->id)->first();
    $msg = ['message' => 'Building retrived', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$building),200);
  }elseif ($request->show_all) {
    $building = Building::all();
    $msg = ['message' => 'Building retrived', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$building),200);
   }

  $per_page = $request->per_page ?? 2;
  $building = Building::paginate($per_page);
  $msg = ['message' => 'Building retrived', 'status' => 'info', 'success' => true];
  return response()->json(output($msg,$building),200);
}

public function update(Request $request)
{
  if(Building::where('id','!=',$request->id)->where('slug',Str::slug($request->name,'-'))->exists()){
    $msg = ['message' => 'Building already exits', 'status' => 'info', 'success' => false];
    return response()->json(output($msg,[]),200);
  }
    $building =  Building::where('id',$request->id)
                 ->update([
                   'building_category_id' => $request->building_category_id,
                   'name' => $request->name,
                   'description' => $request->description,
                   'short_description' => $request->short_description,
                   'min_land_req' => $request->min_land_req,
                   'no_of_floor' => $request->no_of_floor,
                   'total_space_per_floor' => $request->total_space_per_floor,
                   'structure' => $request->structure,
                   'beds' => $request->beds,
                   'bathrooms' => $request->bathrooms,
                   'balcony' => $request->balcony,
                   'garden' => $request->garden,
                   'pool' => $request->pool,
                   'garage_capacity' => $request->garage_capacity,
                   'steel' => $request->steel,
                   'fitting' => $request->fitting,
                   'status' => $request->status,
                   'slug' => Str::slug($request->name,'-'),
                 ]);
    $building =  Building::where('id',$request->id)->first();
    $building->slug = Str::slug($request->name,'-') . '-'.$building->id;
    $buildingImageDeleteAfter = json_decode($request->deleted_after_product_images);
    $buildingFImageDeleteAfter = json_decode($request->deleted_after_product_fimages);
    $pimages = [];
     if ($request->deleted_product_images) {
        // existing feature image deleted
        $deletedPImages = json_decode($request->deleted_product_images);

        foreach ($deletedPImages as $key => $value) {
            foreach (image_upload_size() as $sizekey => $size) {
                $path = $value->path.'/'.$sizekey.'/'.$value->file;
                if(Storage::disk('s3')->exists($path)){
                    Storage::disk('s3')->delete($path);
                }
            }
        }
     }
    if ($request->hasFile('images')) {
        $buildingImages = $request->file('images');
        foreach ($buildingImages as $key => $pimage) {
            $path = 'building/'.md5($building->id).'/';
            $file = $building->slug.'-'.$key . '-'. strtotime(now()) .'.png';
            image_upload_base64($path, $file,$pimage,p2p_drive());
            $buildingImages[$key] = ['path' => $path , 'file' => $file];
        }
        $pimages = $buildingImages;
    }

    $building->images =  json_encode(array_merge($pimages,$buildingImageDeleteAfter));

    if ($request->hasFile('feature_image')) {
        $buildingFImages = $request->file('feature_image');
        foreach ($buildingFImages as $key => $pimage) {
            $path = 'building/'.md5($building->id).'/feature/';
            $file = $building->slug.'-'.$key.'.png';
            image_upload_base64($path, $file,$pimage,p2p_drive());
            $buildingFImages[$key] = ['path' => $path , 'file' => $file];
        }
        $building->feature_image = json_encode($buildingFImages);
    }
    $building->save();
    $msg = ['message' => 'Building updated', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,[]),200);
}

public function delete(Request $request)
{
  $building = Building::where('id',$request->id)->delete();
  $msg = ['message' => 'Building Deleted', 'status' => 'info', 'success' => true];
  return response()->json(output($msg,[]),200);

}
}
