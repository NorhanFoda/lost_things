<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
<meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
<meta name="author" content="PIXINVENT">
<title>تسجيل دخول</title>
<link rel="apple-touch-icon" href="{{asset('admin/app-assets/images/ico/apple-icon-120.png')}}">
<link rel="shortcut icon" type="image/x-icon" href="{{asset('admin/app-assets/images/ico/favicon.ico')}}">
<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
<!-- BEGIN: Vendor CSS-->
<link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/vendors/css/vendors.min.css')}}">
<!-- END: Vendor CSS-->
<!-- BEGIN: Theme CSS-->
<!-- BEGIN: Theme CSS-->
@if(app()->getLocale() == 'ar')
<link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css-rtl/bootstrap.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css-rtl/bootstrap-extended.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css-rtl/colors.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css-rtl/components.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css-rtl/themes/dark-layout.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css-rtl/themes/semi-dark-layout.css')}}">
@else
<link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css/bootstrap.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css/bootstrap-extended.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css/colors.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css/components.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css/themes/dark-layout.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css/themes/semi-dark-layout.css')}}">
@endif
<!-- BEGIN: Page CSS-->
<link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css/core/menu/menu-types/vertical-menu.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css/core/colors/palette-gradient.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/css/pages/authentication.css')}}">
<!-- END: Page CSS-->
<!-- BEGIN: Custom CSS-->
<link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/style.css')}}">
<!-- END: Custom CSS-->
</head>
<!-- END: Head-->
<!-- BEGIN: Body-->
<body class="vertical-layout vertical-menu-modern dark-layout 1-column navbar-floating footer-static bg-full-screen-image blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column" data-layout="dark-layout">
<!-- BEGIN: Content-->
<div class="app-content content">
<div class="content-overlay"></div>
<div class="header-navbar-shadow"></div>
<ul class="nav navbar-nav text-center">
<li class="dropdown dropdown-language nav-item" style="max-width: 150px;
margin: auto;
width: 142px;
margin-top: 20px;">
@if(app()->getLocale() == 'ar')
<a class="dropdown-toggle nav-link" id="dropdown-flag" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="flag-icon flag-icon-sa"></i><span class="selected-language">العربية</span></a>
@else
<a class="dropdown-toggle nav-link" id="dropdown-flag" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="flag-icon flag-icon-us"></i><span class="selected-language">English</span></a>
@endif
<div class="dropdown-menu" aria-labelledby="dropdown-flag">
@if(app()->getLocale() == 'ar')<a class="dropdown-item" href="{{route('change_locale','en')}}" data-language="en"><i class="flag-icon flag-icon-us"></i> English</a>
@else<a class="dropdown-item" href="{{route('change_locale','ar')}}" data-language="ar"><i class="flag-icon flag-icon-sa"></i> العربية</a>
@endif
</div>
</li>
</ul>
<div class="content-wrapper">
<div class="content-header row">
</div>
<div class="content-body">
<section class="row flexbox-container">
<div class="col-xl-8 col-11 d-flex justify-content-center">
<div class="card bg-authentication rounded-0 mb-0">
<div class="row m-0">
<div class="col-lg-6 d-lg-block d-none text-center align-self-center px-1 py-0">
<img src="{{asset('admin/app-assets/images/pages/login.png')}}" alt="branding logo">
</div>
<div class="col-lg-6 col-12 p-0">
<div class="card rounded-0 mb-0 px-2" style="padding:10px 10px 25px">
<div class="card-header pb-1">
<div class="card-title">
<h4 class="mb-0">{{trans('admin.login')}}</h4>
</div>
</div>
<div class="card-content">
<div class="card-body pt-1">
<form action="{{ route('login') }}" method="post">
@csrf
<fieldset class="form-label-group form-group position-relative has-icon-left">
<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
<div class="form-control-position">
<i class="feather icon-user"></i>
</div>
<label for="email" class="col-md-12 col-form-label text-md-left">{{trans('admin.email') }}</label>
@error('email')
<span class="invalid-feedback" role="alert">
<strong>{{ $message }}</strong>
</span>
@enderror
</fieldset>
<fieldset class="form-label-group position-relative has-icon-left">
<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
<div class="form-control-position">
<i class="feather icon-lock"></i>
</div>
<label for="password" class="col-md-12 col-form-label text-md-left">{{ trans('admin.password') }}</label>
@error('password')
<span class="invalid-feedback" role="alert">
<strong>{{ $message }}</strong>
</span>
@enderror
</fieldset>
<div class="form-group d-flex justify-content-between align-items-center">
<div class="text-left">
<fieldset class="checkbox">
<div class="vs-checkbox-con vs-checkbox-primary">
<input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
<span class="vs-checkbox">
<span class="vs-checkbox--check">
<i class="vs-icon feather icon-check"></i>
</span>
</span>
<span class="">{{trans('admin.remember') }}</span>
</div>
</fieldset>
</div>
<div class="text-left"><a href="{{ route('password.request') }}" class="card-link">{{trans('admin.forget')}}</a></div>
</div>
<button type="submit" class="btn btn-primary float-right btn-inline">{{trans('admin.login')}}</button>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
</div>
</div>
</div>
<!-- END: Content-->
<!-- BEGIN: Vendor JS-->
<script src="{{asset('admin/app-assets/vendors/js/vendors.min.js')}}"></script>
<!-- BEGIN Vendor JS-->
<!-- BEGIN: Page Vendor JS-->
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
<script src="{{asset('admin/app-assets/js/core/app-menu.js')}}"></script>
<script src="{{asset('admin/app-assets/js/core/app.js')}}"></script>
<script src="{{asset('admin/app-assets/js/scripts/components.js')}}"></script>
<!-- END: Theme JS-->
<!-- BEGIN: Page JS-->
<!-- END: Page JS-->
</body>
<!-- END: Body-->
</html>