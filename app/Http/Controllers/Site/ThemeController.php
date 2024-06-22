<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Composition;
use App\Model\Theme;

class ThemeController extends Controller
{
  public function getComposition(Request $request)
  {
    $theme = Theme::where('slug',$request->slug)->first();

    $composition = Composition::where('theme_id',$theme->id)
                       ->get();
    $msg = ['message' => 'Room Wise Product list', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$composition),200);
  }
}
