<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\DeliverySystem;
use Illuminate\Http\Request;

class DeliverySystemController extends Controller
{
  public function create(Request $request)
  {
     $delivery = new DeliverySystem;
     $delivery->name = $request->name;
     $delivery->order_id = $request->order_id;
     $delivery->address = $request->address;
     $delivery->stage = $request->stage;
     $delivery->save();

     $msg = ['message' => 'Delivery system created', 'status' => 'info', 'success' => true];
     return response()->json(output($msg,[]),200);
  }

  public function get(Request $request)
  {
    if ($request->id) {
      $delivery = DeliverySystem::where('id',$request->id)->first();
      $msg = ['message' => 'Delivery system retrived', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$delivery),200);
    }elseif ($request->show_all) {
        $delivery = DeliverySystem::all();
        $msg = ['message' => 'Delivery system retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$delivery),200);
     }

    $per_page = $request->per_page ?? 2;
    $delivery = DeliverySystem::paginate($per_page);
    $msg = ['message' => 'Delivery system retrived', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$delivery),200);
  }

  public function update(Request $request)
  {
     $delivery = DeliverySystem::where('id',$request->id)
                          ->update([
                            'name' => $request->name,
                            'order_id' => $request->order_id,
                            'address' => $request->address,
                            'stage' => $request->stage,
                          ]);
     $msg = ['message' => 'Delivery system updated', 'status' => 'info', 'success' => true];
     return response()->json(output($msg,[]),200);
  }

  public function delete(Request $request)
  {
    $category = DeliverySystem::where('id',$request->id)
                         ->delete();
    $msg = ['message' => 'DeliverySystem Deleted', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,[]),200);

  }
}
