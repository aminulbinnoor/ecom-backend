<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\BuildingCategory;

class BuildingCategoryController extends Controller
{
  public function getCategory(Request $request)
  {
    if (redis_exists('buildingCat') && config('p2p.redis')) {
      $categories = json_decode(get_redis('roomTags'));
    }else {
      $categories = BuildingCategory::all();
      set_redis('buildingCat',\json_encode($categories));
    }
      $msg = ['message' => 'Building categories retrive', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$categories),200);
  }
}
