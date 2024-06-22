<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ProductCategory;

class ProductCategoryController extends Controller
{
    public function get(Request $request)
    {
        $category = ProductCategory::where('slug',$request->category)->first();

        if ($category) {
            $msg = ['message' => 'category retrive', 'status' => 'info', 'success' => true];
            return response()->json(output($msg,$category),200);
        }else{
            $msg = ['message' => 'category not found', 'status' => 'info', 'success' => false];
            return response()->json(output($msg,[]),404);
        }

        return response()->json($category);
    }

}
