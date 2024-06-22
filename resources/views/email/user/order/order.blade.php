@php
$theDesc = $desc;

eval("\$theDesc = \"$theDesc\";");

@endphp
@component('mail::message')
  <pre>
    {{$theDesc}}
  </pre>
@endcomponent
