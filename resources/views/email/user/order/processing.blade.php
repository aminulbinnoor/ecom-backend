@component('mail::message')
  <pre>
    Dear {{auth_user()->first_name}},

     Thank you for your order from P2P.com.bd
     We expect you to deliver your order within 3 to 5 days. Our experts will deliver and ensure proper installation of the product at your premises as par your direction. That too, without any additional cost.
     If you have any question/ query/ suggestion regarding your order, you can email us at care@p2p.com.bd or call us at +8801********.

     Your order has been received for processing order id is {{$order->id}}
     Thanks for putting your trust on us!
     P2P Family
  </pre>
@endcomponent
