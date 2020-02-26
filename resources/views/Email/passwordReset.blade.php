@component('mail::message')
تطبيق مفقودات

من فضلك قم بادخال كود التفعيل فى التطبيق لتعيين كلمة مرور جديده

@component('mail::button', ['url' => ''])
كود التفعيل الخاص بك هو: {{$code}}
@endcomponent

شكرا<br>
{{-- {{ config('app.name') }} --}}
@endcomponent
