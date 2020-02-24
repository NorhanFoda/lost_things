@component('mail::message')
تطبيق مفقودات

من فضلك قم بادخال كود التفعيل فى التطبيق لاكمال عملية التسجيل

@component('mail::button', ['url' => ''])
كود التفعيل الخاص بك هو: {{$code}}
@endcomponent

شكرا<br>
{{-- {{ config('app.name') }} --}}
@endcomponent
