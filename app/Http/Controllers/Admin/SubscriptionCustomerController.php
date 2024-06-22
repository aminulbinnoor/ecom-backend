<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\SubscriptionCustomer;
use Illuminate\Http\Request;
use Validator;

class SubscriptionCustomerController extends Controller
{
  public function get(Request $request)
  {
    $per_page = $request->per_page ?? 2;
    $subscriptioncustomers = SubscriptionCustomer::paginate($per_page);
    $msg = ['message' => 'Subscription Customer retrived', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$subscriptioncustomers),200);
  }

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
    $subscribe_customers->email = $request->email;
    $subscribe_customers->phone = $request->phone;
    $subscribe_customers->save();
    $msg = ['message' => 'Subscription successful', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,[]),200);

  }


}
