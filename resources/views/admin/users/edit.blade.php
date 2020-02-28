@extends('admin.layouts.app')

@section('pageTitle')
    {{trans('admin_content.dashboard')}}
@endsection

@section('pageSubTitle')
    {{trans('admin_content.edit_user')}}
@endsection

@section('content')

    <!--start div-->
    <div class="row" style="display:block">

        <div class="row breadcrumbs-top m-2">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">{{trans('admin_content.users')}}</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{trans('admin_content.main')}}</a>
                        </li>
                        <li class="breadcrumb-item active">{{trans('admin_content.edit_user')}}
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{trans('admin_content.edit_user')}}</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        @include('alert')
                        <form class="form form-horizontal needs-validation" novalidate method="post" enctype="multipart/form-data" action="{{route('users.update', auth()->id())}}">
                            {{method_field('PATCH')}} {{csrf_field()}}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <span>{{trans('admin_content.name')}}</span>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" placeholder="{{trans('admin_content.name')}}" name="name" value="{{auth()->user()->name}}" required>
                                                <div class="invalid-feedback">
                                                    {{trans('admin_content.please_enter_name')}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <span>{{trans('admin_content.email')}}</span>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="email" class="form-control" placeholder="{{trans('admin_content.email')}}" name="email" value="{{auth()->user()->email}}" required>
                                                <div class="invalid-feedback">
                                                    {{trans('admin_content.please_enter_email')}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <span>{{trans('admin_content.phone')}}</span>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="number" class="form-control" placeholder="{{trans('admin_content.phone')}}" name="phone" value="{{auth()->user()->phone}}" minlength="10" maxlength="14" required>
                                                <div class="invalid-feedback">
                                                    {{trans('admin_content.please_enter_phone')}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <span>{{trans('admin_content.password')}}</span>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="tel" class="form-control" placeholder="{{trans('admin_content.password')}}" minlength="6" name="password">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <span>{{trans('admin_content.password_confirmation')}}</span>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="tel" class="form-control" placeholder="{{trans('admin_content.password_confirmation')}}" name="password_confirmation">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <span>{{trans('admin_content.image')}}</span>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="file" class="form-control" name="image" accept=".gif, .jpg, .png, .webp">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">{{trans('admin_content.edit')}}</button>
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
