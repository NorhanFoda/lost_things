@component('mail::message')
# Introduction

The body of your message.

@component('mail::button', ['url' => ''])
Your verification code is: {{$code}}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
