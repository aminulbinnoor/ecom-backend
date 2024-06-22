<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Product;


class CheckoutController extends Controller
{

     public function products(Request $request)
     {
         $ids = collect(json_decode($request->products))->pluck('id')->toArray();
         $data = Product::whereIn('id',$ids)->get();

         $p = json_decode($request->products);

         for ($i=0; $i < count($p) ; $i++) {
             $a = collect($data)->where('id',$p[$i]->id)->first();
             $p[$i]->price = $a->price;
             $p[$i]->discount_price = $a->discount_price;
             $p[$i]->order_limit = $a->order_limit;
         }


         $msg = ['message' => 'room fetch with composition_id', 'status' => 'info', 'success' => true];
         return response()->json(output($msg,$p),200);

      }
}
