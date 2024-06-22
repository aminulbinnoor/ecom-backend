<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Product;
use App\Model\ProductCategory;

class ProductController extends Controller
{
    public function get(Request $request)
    {
        $product = Product::with(['category'])->where('id',$request->id)
                            ->orWhere('slug',$request->slug)
                            ->first();

        if ($product) {
            $msg = ['message' => 'Product retrive', 'status' => 'info', 'success' => true];
            return response()->json(output($msg,$product),200);
        }else{
            $msg = ['message' => 'product not found', 'status' => 'info', 'success' => false];
            return response()->json(output($msg,[]),404);
        }

        return response()->json($product);
    }

    public function getSingle(Request $request)
    {
        $product = Product::where('slug',$request->slug)->first();
        $msg = ['message' => 'Product retrive', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$product),200);
    }


    public function getAllData(Request $request)
    {

        if($request->category){
            $category = ProductCategory::where('slug',$request->category)->first();
            $products =  null;
            if ($category) {
                $products = Product::where('product_category_id',$category->id)->paginate(20);
            }
            if ($products) {
                $msg = ['message' => 'Product retrive', 'status' => 'info', 'success' => true];
                return response()->json(output($msg,$products),200);
            }else{
                $msg = ['message' => 'product not found', 'status' => 'info', 'success' => false];
                return response()->json(output($msg,[]),404);
            }
        }

        $products = Product::with(['category'])->paginate(20);
        if ($products) {
            $msg = ['message' => 'Product retrive', 'status' => 'info', 'success' => true];
            return response()->json(output($msg,$products),200);
        }else{
            $msg = ['message' => 'product not found', 'status' => 'info', 'success' => false];
            return response()->json(output($msg,[]),404);
        }

        return response()->json($product);
    }



    public function getCategoryList(Request $request)
    {
        if (redis_exists('getProductCategoryList') && config('p2p.redis')) {
          $categories = json_decode(get_redis('getProductCategoryList'));
        }else {
          $categories = ProductCategory::all();
          set_redis('getProductCategoryList',\json_encode($categories));
        }

        $msg = ['message' => 'product categories retreive', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$categories),200);

    }

}
