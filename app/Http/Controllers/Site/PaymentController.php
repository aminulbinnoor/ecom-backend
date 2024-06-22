<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Category;
use App\Model\Order;
use SslPayment;

class PaymentController extends Controller
{

    /**
     * Sslcommerze payment success
     * @author code4mk
     * @since 1.0.0
     * @param $request Illuminate\Http\Request;
     * @return redirect.
     */
     public function sslSuccess(Request $request)
     {
         $data = SslPayment::verify($request);
         // return response()->json($data);
         if ($data->status == 'VALID') {
             $order = Order::where('id',$data->tran_id)
                            ->update([
                                'status' => '1'
                            ]);
          }
          $order = Order::where('id',$data->tran_id)->first();
          if($order){
              $customer = User::where('id',$order->user_id)->first();
              if($customer->email){
                  send_mail($customer->email,'email.order', "Your order is placed (cod). order id - $order->id",['order' => $order]);
              }
              if($customer->phone){
                  mobile_msg($customer->phone,"Your order is placed, order id - $data->tran_id . contact number  +8801932360360. P2P");
              }
          }

          return redirect(config('p2p')['main_site']."/payment-success?order_id=$data->tran_id");
      }
}
