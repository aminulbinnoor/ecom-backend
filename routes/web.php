<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;
use App\Excel\Export;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Http\Request;


Route::get('payment', 'Site\OrderController@createOrder');

Route::get('send-mail', function () {

    //$otp = otp_send('017');
    // return response()->json(otp_check('017','534184'));
    // send_mail('test@gmail.com','email.dummy', 'test mail by kamal 234',[]);
    //$order = (object)['id' => 23];
    //send_mail('test@gmail.com','email.order', "Your order is placed (cod). order id - $order->id",['data' => 'p2p']);
});


Route::get('file-export', function(){
    $headers = [
        'ids',
        'name',
        'slug',
        'is_active',
        'date',
    ];
    $data = [
        [1,2,3,4,5],
        [1,2,3,4,6],
        [1,2,3,4,7],
        [1,2,3,4,8],
    ];
    return excel_export($headers,$data,'order-lists-2020','xlsx');
});

Route::get('wel',function(){
  $order = 1;
  $name = "aminul";
  $pa = "<pre>
     Dear " . $name .",
       Thank you for your order from P2P.com.bd
       We expect you to deliver your order within 3 to 5 days. Our experts will deliver and ensure proper
       installation of the product at your premises as par your direction. That too, without any additional cost.
       If you have any question/ query/ suggestion regarding your order, you can email us at care@p2p.com.bd or call us at +8801********.

    Your order has been received for processing order id is". $order."
    Thanks for putting your trust on us!
    P2P Family.
    </pre>"
    ;

return view('welcome',compact('pa'));
});

Route::get('get-redis',function(){

    // Redis::set('name', 'Taylor');

return response()->json(get_redis('namei'));
});
