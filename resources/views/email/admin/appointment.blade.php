@component('mail::message')
     Mr/Mrs {{$admin->first_name}}
     <p>You have been assigned for a consultency.</p>
     <p>Appointment id is: {{$appointment->id}}</p>
@endcomponent
