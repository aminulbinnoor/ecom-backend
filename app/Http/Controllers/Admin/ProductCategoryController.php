<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Model\ProductSubcategory;
use App\Model\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
  public function create(Request $request)
  {
     $product_category = new ProductCategory;
     $product_category->name = $request->name;
     $product_category->details = $request->details;
     $product_category->is_active = $request->status ?? true;
     $product_category->slug = Str::slug($request->name,'-');
     $product_category->save();

     $product_category->slug = Str::slug($request->name,'-') .'-'. $product_category->id;

     if ($request->hasFile('images')) {
         $product_categoryImages = $request->file('images');
          foreach ($product_categoryImages as $key => $pimage) {
              $path = 'product-category/'.md5($product_category->id).'/';
              $file = $product_category->slug.'-'.$key.'.png';
              image_upload_base64($path, $file,$pimage,p2p_drive());
              $product_categoryImages[$key] = ['path' => $path , 'file' => $file];
          }
      $product_category->images = json_encode($product_categoryImages);
     }
     if ($request->hasFile('banner_image')) {
         $bannerImages = $request->file('banner_image');
          foreach ($bannerImages as $key => $pimage) {
              $path = 'product-category/'.md5($product_category->id).'/banner/';
              $file = $product_category->slug.'-'.$key.'.png';
              image_upload_base64($path, $file,$pimage,p2p_drive());
              $bannerImages[$key] = ['path' => $path , 'file' => $file];
          }
          $product_category->banner_image = json_encode($bannerImages);
     }
     $product_category->save();

     $msg = ['message' => 'Product Category created', 'status' => 'info', 'success' => true];
     return response()->json(output($msg,[]),200);
  }

  public function get(Request $request)
  {
    if ($request->id) {
      $product_category = ProductCategory::where('id',$request->id)->first();
      $msg = ['message' => 'Product Category retrived', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$product_category),200);
    }elseif ($request->show_all) {
        $product_category = ProductCategory::all();
        $msg = ['message' => 'Product Subcategory retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$product_category),200);
     }

    $per_page = $request->per_page ?? 2;
    $product_category = ProductCategory::paginate($per_page);
    $msg = ['message' => 'Product Subcategory retrived', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$product_category),200);
  }

  public function update(Request $request)
  {
    if(ProductCategory::where('id','!=',$request->id)->where('slug',Str::slug($request->name,'-'))->exists()){
      $msg = ['message' => 'Product Category already exits', 'status' => 'info', 'success' => false];
      return response()->json(output($msg,[]),200);
    }
     $product_category = ProductCategory::where('id',$request->id)
                          ->update([
                            'name' => $request->name,
                            'details' => $request->details,
                            'is_active' => $request->status,
                            'slug' => Str::slug($request->name,'-'),
                          ]);

      $product_category =  ProductCategory::where('id',$request->id)->first();
      $product_category->slug = Str::slug($request->name,'-') . '-'.$product_category->id;
       if ($request->hasFile('images')) {
           $path = 'product-category/'.md5($product_category->id);
            if(Storage::disk('s3')->exists($path)){
                Storage::disk('s3')->delete($path);
            }

           $categoryImages = $request->file('images');
           foreach ($categoryImages as $key => $pimage) {
               $path = 'product-category/'.md5($product_category->id).'/';
               $file = $product_category->slug.'-'.$key.'.png';
               image_upload_base64($path, $file,$pimage,p2p_drive());
               $categoryImages[$key] = ['path' => $path , 'file' => $file];
           }
           $product_category->images = json_encode($categoryImages);
        }
        if ($request->hasFile('banner_image')) {
            $bannerImages = $request->file('banner_image');
             foreach ($bannerImages as $key => $pimage) {
                 $path = 'product-category/'.md5($product_category->id).'/banner/';
                 $file = $product_category->slug.'-'.$key.'.png';
                 image_upload_base64($path, $file,$pimage,p2p_drive());
                 $bannerImages[$key] = ['path' => $path , 'file' => $file];
             }
             $product_category->banner_image = json_encode($bannerImages);
        }
      $product_category->save();
      $msg = ['message' => 'Product Category updated', 'status' => 'info', 'success' => true];
     return response()->json(output($msg,[]),200);
  }

  public function delete(Request $request)
  {
    $category = ProductCategory::where('id',$request->id)
                         ->delete();
    $msg = ['message' => 'ProductCategory Deleted', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,[]),200);

  }

  public function getProductCategoryWiseSubCategory(Request $request,$id) {
      $product_subcategory = ProductSubcategory::where('product_category_id',$id)->get();
      $msg = ['message' => 'Product Subcategory retrived', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$product_subcategory),200);
  }

}
