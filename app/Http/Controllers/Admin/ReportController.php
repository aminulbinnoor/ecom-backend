<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Order;

class ReportController extends Controller
{
    public function orderReport()
    {
        $orders = Order::with(['products','user'])->get();
        $headers = [
            'id',
            'user',
            'phone',
            'shipping_address',
            'total',
        ];

        $subset = $orders->map(function ($order) {
            $db = [
                'id' => $order->id,
                'user' => $order['user']->first_name,
                'phone' => $order['user']->phone,
                'shipping_address' => $order->shipping_address,
                'total' => $order->total,
            ];
            return $db;
        });
        // return response()->json($subset);
        return excel_export($headers,$subset->toArray(),'order-lists-'.strtotime(now()),'xlsx');
    }
}
