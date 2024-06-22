<?php
// 6d99b6bd6c5661acc33a5d3ebbd9efee-07bc7b05-84791676
// pass: 02fa7162401851d5b42ebaa8441ba153-07bc7b05-2358aaea
return [
    'product_status' => [
        ['key' => 1, 'value' => 'wirehous'],
        ['key' => 2, 'value' => 'ready'],
        ['key' => 3, 'value' => 'marketplace']
    ],
    'order_status' => [
        ['key' => 0, 'value' => 'pre-new'],
        ['key' => 1, 'value' => 'new'],
        ['key' => 2, 'value' => 'processing'],
        ['key' => 3, 'value' => 'delivered'],
        ['key' => 4, 'value' => 'cancel']
    ],

    'main_site' => 'http://127.0.0.1:3002',
    'redis' => true,

    'site_mail_msg' => [
      'order-placed' => [
        'title' => 'Your order has been received for processing.',
        'description' => '
         Dear $user,\n
         We expect you to deliver your order within 3 to 5 days. Our experts will deliver and ensure proper
         installation of the product at your premises as par your direction. That too, without any additional cost.

         If you have any question/ query/ suggestion regarding your order, you can email us at
         care@p2p.com.bd or call us at +8801********. \t

         Your order has been received for processing order id is $order->id. \t

         Thanks for putting your trust on us!
         https://p2p.com.bd
        '
      ],

      'order-processing' => [
        'title' => 'Your order has been received for processing.',
        'description' => '
          Dear $user->first_name,\n
          We expect you to deliver your order within 3 to 5 days. Our experts will deliver and ensure proper installation
          of the product at your premises as par your direction. That too, without any additional cost.
          If you have any question/ query/ suggestion regarding your order, you can email us at
          care@p2p.com.bd or call us at +8801********. \t

          Your order has been received for processing order id is $order->id. \t

          Thanks for putting your trust on us!
          https://p2p.com.bd'
      ],

      'order-delivered'=>[
        'title' => 'Your order is on its way!',
        'description' =>'
           Dear $user->first_name,\n
           Your order $order->id has been picked up by our delivery partner from our premises.
           You may expect your order to reach you within next 24-48 hours.
           For query, please call at +8801****** \t

           Please let us know your feedback once you receive your desired product. \t

           Regards
           https://p2p.com.bd
        '
      ],

      'order-cancelled'=>[
        'title' => 'Your order has been cancelled.',
        'description' => '
          Dear $user->first_name,\n
          Your order has been cancelled. If you have any question regarding your order,
          you can email us at care@p2p.com.bd or call us at +8801********.\t

          Regards
          https://p2p.com.bd
          '
      ],
    ]
];
