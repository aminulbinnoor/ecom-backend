<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\ProductSubcategory;
use App\Model\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductSubcategoryController extends Controller
{
  public function create(Request $request)
  {
      if(ProductSubcategory::where('slug',Str::slug($request->name,'-'))->exists()){
          $msg = ['message' => 'ProductSubcategory already exits', 'status' => 'info', 'success' => false];
          return response()->json(output($msg,[]),200);
      }

     $product_subcategory = new ProductSubcategory;
     $product_subcategory->name = $request->name;
     $product_subcategory->product_category_id = $request->product_category_id;
     $product_subcategory->details = $request->details;
     $product_subcategory->is_active = $request->status ?? true;
     $product_subcategory->slug = Str::slug($request->name,'-');
     $product_subcategory->save();

     $msg = ['message' => 'ProductSubcategory created', 'status' => 'info', 'success' => true];
     return response()->json(output($msg,[]),200);
  }

  public function get(Request $request)
  {
    if ($request->id) {
      $product_subcategory = ProductSubcategory::where('id',$request->id)->first();
      $msg = ['message' => 'Product Subcategory retrived', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$product_subcategory),200);
    }elseif ($request->show_all) {
        $product_subcategory = ProductSubcategory::all();
        $msg = ['message' => 'Product Subcategory retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$product_subcategory),200);
     }

    $per_page = $request->per_page ?? 2;
    $product_subcategory = ProductSubcategory::paginate($per_page);
    $msg = ['message' => 'Product Subcategory retrived', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$product_subcategory),200);
  }

  public function update(Request $request)
  {
    if(ProductSubcategory::where('id','!=',$request->id)->where('slug',Str::slug($request->name,'-'))->exists()){
      $msg = ['message' => 'ProductSubcategory already exits', 'status' => 'info', 'success' => false];
      return response()->json(output($msg,[]),200);
    }
     $category = ProductSubcategory::where('id',$request->id)
                          ->update([
                            'name' => $request->name,
                            'product_category_id' => $request->product_category_id,
                            'details' => $request->details,
                            'is_active' => $request->status,
                            'slug' => Str::slug($request->name,'-'),
                          ]);
     $msg = ['message' => 'ProductSubcategory updated', 'status' => 'info', 'success' => true];
     return response()->json(output($msg,[]),200);
  }

  public function delete(Request $request)
  {
    $category = ProductSubcategory::where('id',$request->id)
                         ->delete();
    $msg = ['message' => 'ProductSubcategory Deleted', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,[]),200);

  }
}
