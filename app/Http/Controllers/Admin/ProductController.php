<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Model\ProductSubcategory;
use App\Model\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Model\Package;
use App\Model\Product;
use App\Model\Room;

class ProductController extends Controller
{
    public function create(Request $request)
    {
        ini_set('max_execution_time', '200000');
      if(Product::where('slug',Str::slug($request->name,'-'))->exists()){
        $msg = ['message' => 'Product already exits', 'status' => 'info', 'success' => false];
        return response()->json(output($msg,[]),200);
      }
       $category = ProductCategory::where('id',$request->product_category_id)->first();
       $product = new Product;
       // $product->category_id = $request->category_id;
       // $product->theme_id = $request->theme_id;
       // $product->composition_id = $request->composition_id;
       // $product->room_id = $request->room_id;
       $product->product_category_id = $request->product_category_id;
       $product->product_category_name =  $category->name;
       $product->product_sub_category_id = $request->product_sub_category_id;
       $product->name = $request->name;
       $product->slug = Str::slug($request->name,'-');
       $product->price = $request->price;
       $product->short_description = $request->short_description;
       $product->description = $request->description;
       $product->instock = $request->instock;
       $product->order_limit = $request->order_limit;
       $product->discount_type = $request->discount_type;
       $product->discount_amount = $request->discount_amount ? $request->discount_amount : 0;
       $product->discount_price = $request->discount_type === 'per' ?  $request->discount_amount / 100 * $request->price : $request->discount_amount;
       $product->status = $request->status;
       $product->save();

       $product->slug = Str::slug($request->name,'-') .'-'. $product->id;

       $product->sku = p2p_set_id($product->id,'sku-',10);

       if ($request->hasFile('images')) {
           $productImages = $request->file('images');
            foreach ($productImages as $key => $pimage) {
                $path = 'products/'.md5($product->id).'/';
                $file = $product->slug.'-'.$key.'.png';
                image_upload_base64($path, $file,$pimage,p2p_drive());
                $productImages[$key] = ['path' => $path , 'file' => $file];
            }
         $product->images = json_encode($productImages);
       }

       if ($request->hasFile('feature_image')) {
           $productFImages = $request->file('feature_image');
            foreach ($productFImages as $key => $pimage) {
                $path = 'products/'.md5($product->id).'/feature/';
                $file = $product->slug.'-'.$key.'.png';
                image_upload_base64($path, $file,$pimage,p2p_drive());
                $productFImages[$key] = ['path' => $path , 'file' => $file];
            }
            $product->feature_image = json_encode($productFImages);
       }
       $product->save();

       $msg = ['message' => 'Product created', 'status' => 'info', 'success' => true];
       return response()->json(output($msg,[]),200);
    }

    public function get(Request $request)
    {
      if ($request->id) {
        $product = Product::where('id',$request->id)->first();
        $msg = ['message' => 'Package retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$product),200);
      }elseif ($request->show_all) {
          $products = Product::all();
          $msg = ['message' => 'Package retrived', 'status' => 'info', 'success' => true];
          return response()->json(output($msg,$products),200);
       }

      $per_page = $request->per_page ?? 2;
      $products = Product::paginate($per_page);
      $msg = ['message' => 'product retrived', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$products),200);
    }

    public function update(Request $request)
    {
        ini_set('max_execution_time', '200000');
      if(Product::where('id','!=',$request->id)->where('slug',Str::slug($request->name,'-'))->exists()){
        $msg = ['message' => 'Package already exits', 'status' => 'info', 'success' => false];
        return response()->json(output($msg,[]),200);
      }
      $category = ProductCategory::where('id',$request->product_category_id)->first();
      $product =  Product::where('id',$request->id)
                           ->update([
                             // 'category_id' => $request->category_id,
                             // 'theme_id' => $request->theme_id,
                             // 'composition_id' => $request->composition_id,
                             // 'room_id' => $request->room_id,
                             'product_category_id' => $request->product_category_id,
                             'product_category_name' => $category->name,
                             'product_sub_category_id' => $request->product_sub_category_id,
                             'name' => $request->name,
                             'slug' => Str::slug($request->name,'-'),
                             'price' => $request->price,
                             'description' => $request->description,
                             'short_description' => $request->short_description,
                             'order_limit' => $request->order_limit,
                             'instock' => $request->instock,
                             'discount_type' => $request->discount_type,
                             'discount_amount' => $request->discount_amount ? $request->discount_amount : 0,
                             'discount_price' => $request->discount_type === 'per' ?  $request->discount_amount / 100 * $request->price : $request->discount_amount,
                             'status' => $request->status
                           ]);
      $product =  Product::where('id',$request->id)->first();
      $product->slug = Str::slug($request->name,'-') . '-'.$product->id;

      $productImageDeleteAfter = json_decode($request->deleted_after_product_images);
      $productFImageDeleteAfter = json_decode($request->deleted_after_product_fimages);
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
          $productImages = $request->file('images');
          foreach ($productImages as $key => $pimage) {
              $path = 'products/'.md5($product->id).'/';
              $file = $product->slug.'-'.$key . '-'. strtotime(now()) .'.png';
              image_upload_base64($path, $file,$pimage,p2p_drive());
              $productImages[$key] = ['path' => $path , 'file' => $file];
          }
          $pimages = $productImages;
      }

      $product->images =  json_encode(array_merge($pimages,$productImageDeleteAfter));

      if ($request->hasFile('feature_image')) {
          $productFImages = $request->file('feature_image');
          foreach ($productFImages as $key => $pimage) {
              $path = 'products/'.md5($product->id).'/feature/';
              $file = $product->slug.'-'.$key.'.png';
              image_upload_base64($path, $file,$pimage,p2p_drive());
              $productFImages[$key] = ['path' => $path , 'file' => $file];
          }
          $product->feature_image = json_encode($productFImages);
      }
     $product->save();
     $msg = ['message' => 'Product updated', 'status' => 'info', 'success' => true];
     return response()->json(output($msg,$productImageDeleteAfter),200);
    }

    public function setVariation(Request $request)
    {
      $product =  Product::where('id',$request->id)
                        ->update([
                            'variations' => json_encode($request->variations)
                           ]);
       $msg = ['message' => 'Product Variations has been saved', 'status' => 'info', 'success' => true];
       return response()->json(output($msg,[]),200);
    }

    public function getVariation(Request $request)
    {
      $product_variations = Product::where('id',$request->id)->first();
      $msg = ['message' => 'Product Variations has been retrived', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$product_variations->variations),200);

    }

    // specification dimensions
    public function setSpecificationDimensions(Request $request)
    {
      $product =  Product::where('id',$request->id)
                        ->update([
                            'specification_dimensions' => json_encode($request->variations)
                           ]);
       $msg = ['message' => 'Product specification dimensions has been saved', 'status' => 'info', 'success' => true];
       return response()->json(output($msg,[]),200);
    }

    public function getSpecificationDimensions(Request $request)
    {
      $sd = Product::where('id',$request->id)->first();
      $msg = ['message' => 'Product Variations has been retrived', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$sd->specification_dimensions),200);

    }

    // specification details
    public function setSpecificationDetails(Request $request)
    {
      $sd =  Product::where('id',$request->id)
                        ->update([
                            'specification_details' => json_encode($request->specification_details)
                           ]);
       $msg = ['message' => 'Product specification details has been saved', 'status' => 'info', 'success' => true];
       return response()->json(output($msg,[]),200);
    }

    public function getSpecificationDetails(Request $request)
    {
      $sd = Product::where('id',$request->id)->first();
      $msg = ['message' => 'Product specification details has been retrived', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$sd->specification_details),200);

    }




    public function delete(Request $request)
    {
      $category = Product::where('id',$request->id)
                           ->delete();
      $msg = ['message' => 'Product Deleted', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,[]),200);
    }

    public function products (Request $request) {
        $per_page = $request->per_page ?? 2;
        $products = Product::where('package_id',$request->package_id)->paginate();
        $msg = ['message' => 'Product retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$products),200);
    }

}
