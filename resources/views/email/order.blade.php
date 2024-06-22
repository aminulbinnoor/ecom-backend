@component('mail::message')
     Your order is place. order id is {{$order->id}}
     <p style="margin:30px"></p>
     Due: {{ $order->total }}
@endcomponent
