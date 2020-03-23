<!DOCTYPE html>
<!--
  To change this license header, choose License Headers in Project Properties.
  To change this template file, choose Tools | Templates

  and open the template in the editor.
-->
<html lang="ar">

<head>
    <title> {{trans('admin.lost_app')}} </title>
    <!--
      Meta tags
      ================
    -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />

</head>

<body style="direction: rtl">

    <!--start mail
             ================-->
    <div class="mail-div"
        style="font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 700px; text-align: center; margin: auto; border: 1px solid #e2e0e0">
        <div class="mail-content"
            style="color:#fff;background: #333; line-height:30px; font-size: 20px; padding: 20px 30px">
            {{trans('admin.lost_app')}}
        </div>
        <div
            style="background: #f6f6f6; z-index: 5; font-size: 14px; line-height: 24px; color: #333; position: relative; padding:  20px; text-align: right;">
            <h3 style="margin-bottom: 10px;font-size: 17px;"> عزيزي العميل : </h3>
            لقد تم ارسال هذا الايميل لتأكيد تغيير كلمة المرور الخاصه بك
            <div style="color:#333; margin: 30px auto 20px; text-align: center;"><span style="color:#333; background:#fff; padding:10px; border:1px solid #ccc;">كود التفعيل : <b style="font-size: 20px;">
                {{$code}}
                </b></span></div>
         
        </div>
    </div>
    <!--end mail-->



</body>

</html>


<!--@component('mail::message')-->
<!--تطبيق مفقودات-->

<!--من فضلك قم بادخال كود التفعيل فى التطبيق لتعيين كلمة مرور جديده-->

<!--@component('mail::button', ['url' => ''])-->
<!--كود التفعيل الخاص بك هو: {{$code}}-->
<!--@endcomponent-->

<!--شكرا<br>-->
<!--{{-- {{ config('app.name') }} --}}-->
<!--@endcomponent-->
