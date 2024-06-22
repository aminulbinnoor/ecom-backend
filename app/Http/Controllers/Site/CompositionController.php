<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Composition;
use App\Model\Room;

class CompositionController extends Controller
{
    public function getRoom(Request $request)
    {
      $cmp = Composition::where('slug',$request->slug)->first();
      $rooms = Room::where('composition_id',$cmp->id)->get();
      $msg = ['message' => 'Room fetch with composition_id', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,[ 'cmp' => $cmp,'rooms' => $rooms]),200);
    }
}
