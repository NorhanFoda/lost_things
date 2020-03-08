@extends('admin.layouts.app')

@section('pageTitle')
    {{trans('admin.lost_app')}}
@endsection

@section('pageSubTitle')
    {{trans('admin.add_post')}}
@endsection

@section('content')

    <!--start div-->
    <div class="row" style="display:block">

        <div class="row breadcrumbs-top m-2">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">{{trans('admin.posts')}}</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/">{{trans('admin.main')}}</a>
                        </li>
                        <li class="breadcrumb-item active">{{trans('admin.add_post')}}
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
                    <h4 class="card-title">{{trans('admin.add_post')}}</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        {{-- @include('alert') --}}
                        <form class="form form-horizontal needs-validation" novalidate method="post" enctype="multipart/form-data" action="{{route('losts.storeLost')}}">
                            @csrf
                            <div class="form-body">
                                <div class="row">

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <span>{{trans('admin.category')}}</span>
                                            </div>
                                            <div class="col-md-10">
                                                <select name="category_id" class="form-control" required>
                                                    @foreach($categories as $cat)
                                                    <option value="{{$cat->id}}">{{$cat->title}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    {{trans('admin.please_enter_category')}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <span>{{trans('admin.title')}}</span>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" placeholder="{{trans('admin.title')}}" name="title" required>
                                                <div class="invalid-feedback">
                                                    {{trans('admin.please_enter_title')}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <span>{{trans('admin.description')}}</span>
                                            </div>
                                            <div class="col-md-10">
                                                <textarea class="form-control" placeholder="{{trans('admin.description')}}" 
                                                    name="description" rows="4" required></textarea>
                                                <div class="invalid-feedback">
                                                    {{trans('admin.please_enter_description')}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <span>{{trans('admin.location')}}</span>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" placeholder="{{trans('admin.location')}}" name="location" required>
                                                <div class="invalid-feedback">
                                                    {{trans('admin.please_enter_location')}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <span>{{trans('admin.place')}}</span>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" placeholder="{{trans('admin.place')}}" name="place" required>
                                                <div class="invalid-feedback">
                                                    {{trans('admin.please_enter_place')}}
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
                                                    <input type="file" name="images[]" class="form-control" accept=".gif, .jpg, .png, .webp" required>
                                                    <div class="invalid-feedback">
                                                        {{trans('admin.please_enter_image')}}
                                                    </div>
                                                    <div class="input-group-btn"> 
                                                        <button class="btn btn-success" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                                    </div>
                                                </div>
                                                <div class="clone hidden">
                                                    <div class="control-group input-group" style="margin-top:10px">
                                                        <input type="file" name="images[]" class="form-control" accept=".gif, .jpg, .png, .webp">
                                                        <div class="input-group-btn"> 
                                                            <button class="btn btn-danger" type="button"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                                        </div>
                                                    </div>
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

@section('scripts')
    <script>
        $(document).ready(function() {

            $(".btn-success").click(function(){ 
                var html = $(".clone").html();
                $(".increment").after(html);
            });

            $("body").on("click",".btn-danger",function(){ 
                $(this).parents(".control-group").remove();
            });

        });
    </script>
@endsection
