@component('mail::message')
تطبيق مفقودات

قم بالضغط على الزر لاعادة تعيين كلمة مرور جديده

@component('mail::button', ['url' => ''])
{{-- @component('mail::button', ['url' => 'path to forn end page?token='.$token]) --}}
تعيين كلمة مرور جديده
@endcomponent

شكرا<br>
{{-- {{ config('app.name') }} --}}
@endcomponent
