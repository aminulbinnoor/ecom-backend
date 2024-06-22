<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Model\Category;
use App\Model\Theme;
use App\Model\Composition;

class ThemeController extends Controller
{

    public function create(Request $request)
    {
      if(Theme::where('slug',Str::slug($request->name,'-'))->exists()){
        $msg = ['message' => 'Theme already exits', 'status' => 'info', 'success' => false];
        return response()->json(output($msg,[]),200);
      }
       $theme = new Theme;
       $theme->name = $request->name;
       $theme->category_id = $request->category_id;
       $theme->short_description = $request->short_description;
       $theme->description = $request->description;
       $theme->slug = Str::slug($request->name,'-');
       $theme->is_active = $request->status ?? true;
       $theme->save();

       $theme->slug = Str::slug($request->name,'-').'-' . $theme->id;

       if ($request->hasFile('theme_image')) {
           $theme_images = $request->file('theme_image');
            foreach ($theme_images as $key => $theme_image) {
                $path = 'theme/'.md5($theme->id).'/';
                $file = $theme->slug.'-'.$key.'.png';
                image_upload_base64($path, $file,$theme_image,p2p_drive());
                $theme_images[$key] = ['path' => $path , 'file' => $file];
            }
         $theme->images = json_encode($theme_images);
       }
       $theme->save();

       $msg = ['message' => 'Theme created', 'status' => 'info', 'success' => true];
       return response()->json(output($msg,[]),200);
    }

    public function get(Request $request)
    {
      if ($request->id) {
        $theme = Theme::with(['category'])->where('id',$request->id)->first();
        $msg = ['message' => 'Theme retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$theme),200);
      }elseif ($request->show_all) {
        $theme = Theme::all();
        $msg = ['message' => 'Theme retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$theme),200);
       }

      $per_page = $request->per_page ?? 2;
      $themes = Theme::with(['category'])->paginate($per_page);
      $msg = ['message' => 'Theme retrived', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$themes),200);
    }

    public function update(Request $request)
    {
      if(Theme::where('id','!=',$request->id)->where('slug',Str::slug($request->name,'-'))->exists()){
        $msg = ['message' => 'Theme already exits', 'status' => 'info', 'success' => false];
        return response()->json(output($msg,[]),200);
      }
       $theme =  Theme::where('id',$request->id)
                           ->update([
                             'category_id' => $request->category_id,
                             'name' => $request->name,
                             'description' => $request->description,
                             'short_description' => $request->short_description,
                             'description' => $request->description,
                             'is_active' => $request->status,
                             'slug' => Str::slug($request->name,'-'),
                           ]);
    $theme =  Theme::where('id',$request->id)->first();
    $theme->slug = Str::slug($request->name,'-').'-' . $theme->id;
    if ($request->hasFile('upload_theme_images')) {
        $theme_images = $request->file('upload_theme_images');
        $path = 'theme/'.md5($theme->id);
         if(Storage::disk('s3')->exists($path)){
             Storage::disk('s3')->delete($path);
         }

        foreach ($theme_images as $key => $theme_image) {
            $path = 'theme/'.md5($theme->id).'/';
            $file = $theme->slug.'-'.$key.'.png';
            image_upload_base64($path, $file,$theme_image,p2p_drive());
            $theme_images[$key] = ['path' => $path , 'file' => $file];
        }
        $theme->images = json_encode($theme_images);
    }
    $theme->save();
    $msg = ['message' => 'Theme updated', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,[]),200);
    }

    public function delete(Request $request)
    {
      $category = Theme::where('id',$request->id)
                           ->delete();
      $msg = ['message' => 'Theme Deleted', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,[]),200);

    }

    public function getThemeWiseComposition (Request $request, $id) {
      $compositions = Composition::where('theme_id',$id)->get();
      $msg = ['message' => 'Composition retrived', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$compositions),200);
    }
}
