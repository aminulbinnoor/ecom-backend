@component('mail::message')
  <pre>
      Dear {{$user->first_name}},
      We are delighted to have you as a member of P2P family.
      Your account has been created and set to active.
      To sign in to our site, use these credentials during checkout or on the My Account page:
        •	Email:
        •	Password: Password you set when creating account

      When you sign in to your account, you will be able to:
        •	Proceed through checkout faster
        •	Check the status of orders
        •	View past orders
        •	Store alternative addresses (for shipping to multiple family members and friends)

      Thank you,
      https://p2p.com.bd
  </pre>
@endcomponent
