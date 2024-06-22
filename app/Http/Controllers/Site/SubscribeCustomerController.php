<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\SubscriptionCustomer;
use Validator;

class SubscribeCustomerController extends Controller
{
  public function create(Request $request)
  {
    $validation = Validator::make($request->all(), [
        'email' => 'required|unique:subscribe_customers',
    ]);

    if ($validation->fails())
    {
      $msg = ['message' => 'This email has already taken', 'status' => 'info', 'success' => false];
      return response()->json(output($msg,[]),200);
    }

    $subscribe_customers = new SubscriptionCustomer;
    $subscribe_customers->name = $request->name;
    $subscribe_customers->email = $request->email;
    $subscribe_customers->save();
    $msg = ['message' => 'Subscription successful', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,[]),200);

  }
}
