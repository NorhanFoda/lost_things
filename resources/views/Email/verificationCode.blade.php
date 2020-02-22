@component('mail::message')
# Introduction

كود التفعيل الخاص بك هو
<h2>{{$code}}</h2>

{{-- @component('mail::button', ['url' => ''])
Button Tex
@endcomponent --}}

شكرا<br>
{{ config('app.name') }}
@endcomponent
