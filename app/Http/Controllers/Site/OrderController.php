<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Product;
use App\Model\OrderProduct;
use App\Model\Order;
use SslPayment;

class OrderController extends Controller
{
  /**
   * Order submit
   * @author code4mk
   * @since 1.0.0
   * @param $request Illuminate\Http\Request;
   * @return json.
   */
  public function createOrder(Request $request)
  {
      $sp_address = [
          'first_name' => $request->first_name,
          'last_name' => $request->last_name,
          'address' => $request->address,
          'city' => $request->city,
          'postcode' => $request->postcode ?? null,
          'phone' => $request->phone,
          'email' => $request->email,
      ];
      /* order info store */
      $order = new Order;
      $order->user_id = auth_user()->id;
      $order->shipping_address = json_encode($sp_address);
      $order->payment_method = $request->payment_method;
      $order->delivery_method = 'home';
      $order->delivery_charge = 100;
      $order->total  = $request->total;
      $order->status = "-1";
      $order->save();


      /* product instock manage & order product listing */
      $products = false; //($request->order)->products;
      if ($products) {
          foreach ($products as $key => $product) {
              $product = Product::where('id',$product->id)->first();
              $product->instock = $product->instock - 1;
              $product->save();

              $orderProduct = new OrderProduct;
              $orderProduct->order_id = $order->id;
              $orderProduct->product_id = $product->id;
              $orderProduct->name = $product->name;
              $orderProduct->images = $product->images;
              $orderProduct->quantity = $product->quantity;
              $orderProduct->price = $product->price;
              $orderProduct->discount = $product->discount;
              $orderProduct->save();

          }
      }

      if($request->payment_method == 'online') {
          /* payment process */
          $paid_amount = $request->total;
          $data = SslPayment::tnx($order->id)
                    ->customer('kamal212')
                    ->amount($paid_amount)
                    ->getRedirectUrl();
        $link = "";
        $error = false;
        $paymentMethod = 'online';
        if( $data->failedreason == "") {
            $link = $data->GatewayPageURL;
            $msg = ['message' => 'sslpayment link works', 'status' => 'info', 'success' => true];
        }else{
            $msg = ['message' => $data->failedreason, 'status' => 'info', 'success' => true];
        }
        return response()->json(output($msg,['link' => $link, 'payment_method' => $paymentMethod,'d'=>$data]),200);
    }else{
        $order->status = '1';
        $order->save();

        if(auth_user()->email){
            $mail_msg = config('p2p')['site_mail_msg'];
            $user = auth_user()->first_name;
            send_mail(
                auth_user()->email,
                'email.user.order.order',
                $mail_msg['order-placed']['title'],
                [
                  'order' => $order,
                  'desc'=> $mail_msg['order-placed']['description'],
                  'user' => $user
                ]
            );
        }
        if(auth_user()->phone) {
            mobile_msg(auth_user()->phone,"Dear " . auth_user()->first_name . ",\nThank you for your order. Your order id is ".$order->id. ". See the latest status of your order in your website profile.");
            if(auth_user()->phone !== $request->phone) {
                mobile_msg($request->phone,"An order is placed by " . auth_user()->first_name . ", order id - $order->id");
            }
        }
        $msg = ['message' => 'order placed', 'status' => 'info', 'success' => true];
        return response()->json(output($msg,['oid' => $order->id, 'payment_method' => 'cod', 'u' => auth_user()]),200);
    }
  }

  public function get(Request $request)
  {
    //$user_id = auth_user()->id;
    $orders = Order::where('user_id',auth_user()->id)->get();
    $msg = ['message' => 'Order retrieved successfully', 'status' => 'info', 'success' => true];
    return response()->json(output($msg,$orders),200);
  }
}
