@component('mail::message')
  <pre>
    Dear {{$user->first_name}},
      Your order {{$order->id}} has been picked up by our delivery partner from our premises.
      You may expect your order to reach you within next 24-48 hours.
      For query, please call at +8801******

      Please let us know your feedback once you receive your desired product.

    Regards
    P2P Family
  </pre>
@endcomponent
