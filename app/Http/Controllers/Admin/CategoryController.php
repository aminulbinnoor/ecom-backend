<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Model\Category;
use App\Model\Theme;

class CategoryController extends Controller
{

    public function create(Request $request)
    {
        if(Category::where('slug',Str::slug($request->name,'-'))->exists()){
            $msg = ['message' => 'Category already exits', 'status' => 'info', 'success' => false];
            return response()->json(output($msg,[]),200);
        }

       $category = new Category;
       $category->name = $request->name;
       $category->details = $request->details;
       $category->is_active = $request->status;
       $category->slug = Str::slug($request->name,'-');
       $category->save();

       $msg = ['message' => 'Category created', 'status' => 'info', 'success' => true];
       return response()->json(output($msg,[]),200);
    }

    public function get(Request $request)
    {
      if ($request->id) {
        $category = Category::where('id',$request->id)->first();
        $msg = ['message' => 'Category retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$category),200);
      }elseif ($request->show_all) {
          $category = Category::all();
          $msg = ['message' => 'Category retrived', 'status' => 'info', 'success' => true];
          return response()->json(output($msg,$category),200);
       }

      $per_page = $request->per_page ?? 2;
      $orders = Category::paginate($per_page);
      $msg = ['message' => 'Category retrived', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$orders),200);
    }

    public function update(Request $request)
    {
      if(Category::where('id','!=',$request->id)->where('slug',Str::slug($request->name,'-'))->exists()){
        $msg = ['message' => 'Category already exits', 'status' => 'info', 'success' => false];
        return response()->json(output($msg,[]),200);
      }
       $category = Category::where('id',$request->id)
                            ->update([
                              'name' => $request->name,
                              'details' => $request->details,
                              'is_active' => $request->status,
                              'slug' => Str::slug($request->name,'-'),
                            ]);
       $msg = ['message' => 'Category updated', 'status' => 'info', 'success' => true];
       return response()->json(output($msg,[]),200);
    }

    public function delete(Request $request)
    {
      $category = Category::where('id',$request->id)
                           ->delete();
      $msg = ['message' => 'Category Deleted', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,[]),200);

    }

    public function getCategoryWiseTheme (Request $request, $id) {
      $themes = Theme::where('category_id',$id)->get();
      $msg = ['message' => 'Theme retrived', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$themes),200);
    }

}
