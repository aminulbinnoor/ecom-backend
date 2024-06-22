<?php

namespace App\Http\Controllers\Site;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\CustomerStory;

class CustomerStoryController extends Controller
{
  public function lists(Request $request)
  {
    $cstories = CustomerStory::get();
    $msg = ['message' => 'Customer story fetch done', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$cstories),200);
  }

}
