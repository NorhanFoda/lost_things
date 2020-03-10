@extends('admin.layouts.app')

@section('pageTitle')
    {{trans('admin.lost_app')}}
@endsection

@section('pageSubTitle')
    {{trans('admin.edit_category')}}
@endsection

@section('content')

    <!--start div-->
    <div class="row" style="display:block">

        <div class="row breadcrumbs-top m-2">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">{{trans('admin.categories')}}</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/">{{trans('admin.main')}}</a>
                        </li>
                        <li class="breadcrumb-item active">{{trans('admin.edit_category')}}
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        @foreach($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <span style="color:red;">{{$error}}</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>    
        @endforeach

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{trans('admin.add_category')}}</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        {{-- @include('alert') --}}
                        <form class="form form-horizontal needs-validation" novalidate method="POST" enctype="multipart/form-data" action="{{route('categories.updateCategory', $cat->id)}}">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="form-body">
                                <div class="row">

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <span>{{trans('admin.title')}}</span>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" placeholder="{{trans('admin.category')}}" value="{{$cat->title}}" name="title" required>
                                                <div class="invalid-feedback">
                                                    {{trans('admin.please_enter_category')}}
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
                                                <div class="input-group control-group increment" >
                                                    <input type="file" name="image" class="form-control" accept=".gif, .jpg, .png, .webp" required>
                                                    <div class="invalid-feedback">
                                                        {{trans('admin.please_enter_image')}}
                                                    </div>
        
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">{{trans('admin.edit')}}</button>
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
