@component('mail::message')
     Mr/Mrs {{auth_user()->name}}
     <p>Your Appointment has created</p>
     <p>Appointment id is: {{$appointment->id}}</p>
@endcomponent
