@extends('admin.layouts.app')

@section('pageTitle')
    {{trans('admin.lost_app')}}
@endsection

@section('pageSubTitle')
    {{trans('admin.add_user')}}
@endsection

@section('content')

    <!--start div-->
    <div class="row" style="display:block">

        <div class="row breadcrumbs-top m-2">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">{{trans('admin.users')}}</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/">{{trans('admin.main')}}</a>
                        </li>
                        <li class="breadcrumb-item active">{{trans('admin.add_user')}}
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{trans('admin.add_user')}}</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        {{-- @include('alert') --}}
                        <form class="form form-horizontal needs-validation" novalidate method="post" enctype="multipart/form-data" action="{{route('users.store')}}">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <span>{{trans('admin.name')}}</span>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" placeholder="{{trans('admin.name')}}" name="name" required>
                                                <div class="invalid-feedback">
                                                    @error('name')
                                                        {{$message}}
                                                    @enderror
                                                    {{trans('admin.please_enter_name')}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <span>{{trans('admin.email')}}</span>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="email" class="form-control" placeholder="{{trans('admin.email')}}" name="email">
                                                <div class="invalid-feedback">
                                                    @error('email')
                                                        {{$message}}
                                                    @enderror
                                                    {{trans('admin.please_enter_email_or_phone')}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <span>{{trans('admin.phone')}}</span>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" placeholder="{{trans('admin.phone')}}" name="phone">
                                                <div class="invalid-feedback">
                                                    @error('phone')
                                                        {{$message}}
                                                    @enderror
                                                    {{trans('admin.please_enter_phone_or_email')}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <span>{{trans('admin.password')}}</span>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="password" class="form-control" placeholder="{{trans('admin.password')}}" minlength="6" name="password" required>
                                                <div class="invalid-feedback">
                                                    @error('password')
                                                        {{$message}}
                                                    @enderror
                                                    {{trans('admin.please_enter_password')}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <span>{{trans('admin.password_confirmation')}}</span>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="password" class="form-control" placeholder="{{trans('admin.password_confirmation')}}" name="password_confirmation" required>
                                                <div class="invalid-feedback">
                                                    @error('password_confirmation')
                                                        {{$message}}
                                                    @enderror
                                                    {{trans('admin.please_enter_password_confirmation')}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <span>{{trans('admin.image')}}</span>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="file" class="form-control" name="image" accept=".gif, .jpg, .png, .webp">
                                                <div class="inavlid-feedback">
                                                    @error('image')
                                                        {{$message}}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">{{trans('admin.add')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
