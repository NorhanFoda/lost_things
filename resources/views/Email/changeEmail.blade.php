@component('mail::message')
# Introduction

The body of your message.

@component('mail::button', ['url' => ''])
your token is {{$token}}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
