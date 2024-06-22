<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\CustomerStory;

class CustomerStoryController extends Controller
{
  public function create(Request $request)
  {
     $cstory = new CustomerStory;
     $cstory->name = $request->name;
     $cstory->stories = $request->stories;
     $cstory->status = $request->status;
     $cstory->save();

     $msg = ['message' => 'Customer Story created', 'status' => 'info', 'success' => true];
     return response()->json(output($msg,[]),200);
  }

  public function get(Request $request)
  {
    if ($request->id) {
      $cstory = CustomerStory::where('id',$request->id)->first();
      $msg = ['message' => 'CustomerStory retrived', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$cstory),200);
    }elseif ($request->show_all) {
        $cstory = CustomerStory::all();
        $msg = ['message' => 'Customer Story retrived', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$cstory),200);
     }

    $per_page = $request->per_page ?? 2;
    $orders = CustomerStory::paginate($per_page);
    $msg = ['message' => 'Customer Story retrived', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$orders),200);
  }

  public function update(Request $request)
  {
    $cstory = CustomerStory::where('id',$request->id)
                          ->update([
                            'name' => $request->name,
                            'stories' => $request->stories,
                            'status' => $request->status,                            
                          ]);
    $msg = ['message' => 'Customer Story updated', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,[]),200);
  }

  public function delete(Request $request)
  {
    $cstory = CustomerStory::where('id',$request->id)
                         ->delete();
    $msg = ['message' => 'Customer Story Deleted', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,[]),200);

  }
}
