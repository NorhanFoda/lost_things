@extends('admin.layouts.app')

@section('pageTitle')
    {{trans('admin.lost_app')}}
@endsection

@section('pageSubTitle')
    {{trans('admin.users')}}
@endsection

@section('content')
<div class="content-body">

    <div class="row breadcrumbs-top">
        <div class="col-12">
            <h2 class="content-header-title float-left mb-0">
                {{trans('admin.users')}}
            </h2>
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/">{{trans('admin.main')}}</a>
                    </li>
                    <li class="breadcrumb-item active">{{trans('admin.users')}}
                    </li>
                </ol>
            </div>
        </div>
    </div>

    <!-- page users view start -->
    <section class="page-users-view">
        <div class="row">
            <!-- account start -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">{{$user->name}}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="users-view-image">
                                <img src="{{$user->image}}" class="users-avatar-shadow rounded mb-2 pr-2 ml-1" 
                                    alt="avatar" style="width:150px; height:150px;">
                            </div>
                            <div class="col-12 col-sm-9 col-md-6 col-lg-5">
                                <table>
                                    <tr>
                                        <td class="font-weight-bold">{{trans('admin.email')}}</td>
                                        <td>{{$user->email ? $user->email : trans('admin.nodata')}}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">{{trans('admin.phone')}}</td>
                                        <td>{{$user->phone ? $user->phone : trans('admin.nodata')}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-12 col-md-12 col-lg-5">
                                <table class="ml-0 ml-sm-0 ml-lg-0">
                                    <tr>
                                        <td class="font-weight-bold">{{trans('admin.status')}}</td>
                                        <td>{{$user->is_blocked == 1 ? trans('admin.blocked') : trans('admin.unblocked')}}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">{{trans('admin.location')}}</td>
                                        <td>{{$user->location ? $user->location : trans('admin.nodata')}}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">{{trans('admin.birth_date')}}</td>
                                        <td>{{$user->birth_date ? date('y-m-d', strtotime($user->birth_date)) : trans('admin.nodata')}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-12">
                                <a href="{{route('users.edit', $user->id)}}" class="btn btn-primary mr-1"><i class="feather icon-edit-1"></i>{{trans('admin.edit')}}</a>
                                <a title="delete" onclick="return true;" id="confirm-color" object_id='{{$user->id}}'
                                    class="delete btn btn-outline-danger" style="color:white;"><i class="feather icon-trash-2"></i>{{trans('admin.delete')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- account end -->
            <!-- posts start -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom mx-2 px-0">
                        <h6 class="border-bottom py-1 mb-0 font-medium-2">
                            <i class="fa fa-align-right"></i>
                            {{trans('admin.posts')}}
                        </h6>
                    </div>
                    <div class="card-body px-75">
                        <div class="table-responsive users-view-permission">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{trans('admin.category')}}</th>
                                        <th>{{trans('admin.title')}}</th>
                                        <th>{{trans('admin.status')}}</th>
                                        <th>{{trans('admin.published_at')}}</th>
                                        <th>{{trans('admin.image')}}</th>
                                        <th>{{trans('admin.action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($user->posts) > 0)
                                        @foreach($user->posts as $post)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$post->category->title}}</td>
                                            <td>
                                                {{$post->title}}
                                            </td>
                                            <td>
                                                {{$post->found == 1 ? trans('admin.found') : trans('admin.not_found')}}
                                            </td>
                                            <td>
                                                {{$post->published_at}}
                                            </td>
                                            <td>
                                                <img @if(count($post->images) > 0) src="{{$post->images[0]->path}}" @else src="no image" @endif alt="No image" style="width:200px; height:100px">
                                            </td>
                                            <td>
                                                <a href="
                                                    @if($post->found == 0)
                                                        {{route('losts.showLost', $post->id)}} 
                                                    @else 
                                                        {{route('founds.showFound', $post->id)}}
                                                    @endif" class="btn" style="color:white">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        {{trans('admin.no_posts')}}
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- posts end -->
        </div>
    </section>
    <!-- page users view end -->
</div>
@endsection

@section('script')
    <script>
        $(doument).ready(function(){
            //delete users
            $(document).on('click', '.delete', function (e) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'swal2-confirm',
                        cancelButton: 'swal2-cancel'
                    },
                    buttonsStyling: true
                });
                swalWithBootstrapButtons.fire({
                    title: '{{trans('admin.alert_title')}}',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{trans('admin.yes')}}',
                    cancelButtonText: '{{trans('admin.no')}}',
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        })
                        var id = $(this).attr('object_id');
                        var status = $(this).attr('object_status');
                            token = $('meta[name="csrf-token"]').attr('content');
                            $.ajax({
                                url: "{{route('users.delete')}}",
                                type: "post",
                                dataType: 'json',
                                data: {"_token": "{{ csrf_token() }}", id: id},
                                success: function(data){
                                    if(data.data == 1){
                                        Swal.fire({
                                            type: 'success',
                                            title: '{{trans('admin.user_deleted')}}',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                        window.location.reload(); 
                                    }
                                }
                            });
                    } else if (
                        // / Read more about handling dismissals below /
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire({
                            title: '{{trans('admin.alert_cancelled')}}',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    }
                })
            });
        });
    </script>
@endsection