@component('mail::message')
# Hi

A product has been updated:

## Old Values
@foreach($oldValues as $key => $value)
    {{$key}} - {{$value}}
@endforeach

## New Values
@foreach($oldValues as $key => $value)
    {{$key}} - {{$value}}
@endforeach

Thanks,<br>
{{ config('app.name') }}
@endcomponent
