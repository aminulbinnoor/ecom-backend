<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Building;
use App\Model\BuildingCategory;

class BuildingController extends Controller
{
  public function getBuilding(Request $request)
  {
      $buildingCategory = BuildingCategory::where('slug', $request->slug)->first();
      $buildings = Building::where('building_category_id',$buildingCategory->id)->paginate(15);
      $msg = ['message' => 'Building retrive', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$buildings),200);
  }

  public function buildingDetail(Request $request)
  {
      $deatils = Building::where('slug',$request->slug)->first();
      $msg = ['message' => 'Building details retrive', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$deatils),200);
  }
}
