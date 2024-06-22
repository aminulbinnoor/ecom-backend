<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Order;
use App\Model\User;
use LARAPDF;

class OrderController extends Controller
{
    public function get(Request $request)
    {

      if ($request->id) {
        $order = Order::with(['products','user'])->where('id',$request->id)->first();
        $msg = ['message' => 'Order retrieved successfully', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$order),200);
      }
      $per_page = $request->per_page ?? 2;
      $orders = Order::with(['products','user'])
                      ->where('status',$request->status)
                      ->orderBy('id','desc')
                      ->paginate($per_page);
      $msg = ['message' => 'Orders retrieved successfully', 'status' => 'info', 'success' => true];
      return response()->json(output($msg,$orders),200);
    }

    public function updateStatus(Request $request)
    {
        $od_status = collect(config('p2p')['order_status'])->firstWhere('key',$request->status);
        $order = Order::where('id',$request->id)
                      ->update([
                          'status' => $request->status,
                          'order_status' => $od_status['value']
                      ]);

        $order = Order::where('id', $request->id)->first();
        $user_id = Order::where('id', $request->id)->value('user_id');
        $user = User::where('id',$user_id)->firstOrFail();
        $mail_msg = config('p2p')['site_mail_msg'];
          if($user->email){
            if($od_status['value'] === "processing"){
                send_mail($user->email,'email.user.order.order', $mail_msg['order-processing']['title'],['user' => $user,'order' => $order, 'desc'=>$mail_msg['order-processing']['description']]);
                  if($user->phone){
                      mobile_msg($user->phone,"Dear " . $user->first_name . ",\nYour order # $order->id has been received for processing. Thank You");
                  }
            }elseif ($od_status['value'] === "delivered") {
                send_mail($user->email,'email.user.order.order', $mail_msg['order-delivered']['title'],['order' => $order, 'user' => $user,'desc'=>$mail_msg['order-delivered']['description']]);
                   if($user->phone){
                       mobile_msg($user->phone,"Dear " . $user->first_name . ",\nYour order # $order->id has been handed over to our delivery partner. It will reach you in next 24-48 hours.\nPlease call +8801****** for further query.");
                   }
            }elseif ($od_status['value'] === "cancel") {
                send_mail($user->email,'email.user.order.order', $mail_msg['order-cancelled']['title'], ['order' => $order, 'user' => $user,'desc'=>$mail_msg['order-cancelled']['description']]);
                    if($user->phone){
                        mobile_msg($user->phone,"Dear " . $user->first_name . ",\nYour order # $order->id has been cancelled.\nPlease call +8801****** if you have any query.\nThank You");
                    }
            }
          }
        $msg = ['message' => 'Order status has been changed successfully', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,$od_status['value']),200);
    }

    public function getInvoice($id)
    {
      $order = Order::with(['products','user'])->where('id',$id)->first();

      return LARAPDF::loadView('pdf.simple',['order'=>$order, 'user' => $order->user, 'products' =>$order->products ])
            ->paper('a4')->show();

      $msg = ['message' => 'Order retrieved', 'status' => 'info', 'success' => true];
      return response()->json($order,200);
    }
}
