@extends('admin.layouts.app')

@section('pageTitle')
    {{trans('admin.lost_app')}}
@endsection

@section('pageSubTitle')
    {{trans('admin.lost_app')}}
@endsection

@section('content')
<div class="content-body">
    <div class="row breadcrumbs-top">
        <div class="col-12">
            <h2 class="content-header-title float-left mb-0">
                {{trans('admin.posts')}}
            </h2>
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/">{{trans('admin.main')}}</a>
                    </li>
                    <li class="breadcrumb-item active">{{trans('admin.losts')}}
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
                        <div class="card-title">{{$post->title}}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- @foreach($post->images as $image)
                                <div class="users-view-image">
                                    <img src="{{$image->path}}" class="users-avatar-shadow rounded mb-2 pr-2 ml-1" 
                                        alt="avatar" style="width:150px; height:150px">
                                </div>
                            @endforeach --}}
                            <div class="col-12 col-sm-9 col-md-6 col-lg-5">
                                <table>
                                    <tr>
                                        <td class="font-weight-bold">{{trans('admin.category')}}</td>
                                        <td style='margin: 5px; padding: 15px;'>{{$post->category->title}}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">{{trans('admin.location')}}</td>
                                        <td style='margin: 5px; padding: 15px;'>{{$post->location}}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">{{trans('admin.place')}}</td>
                                        <td style='margin: 5px; padding: 15px;'>{{$post->place}}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">{{trans('admin.status')}}</td>
                                        <td style='margin: 5px; padding: 15px;'>@if($post->found == 0) {{trans('admin.not_found')}} @endif</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-12 col-md-12 col-lg-5">
                                <table class="ml-0 ml-sm-0 ml-lg-0">
                                    <tr>
                                        <td class="font-weight-bold">{{trans('admin.published_at')}}</td>
                                        <td style='margin: 5px; padding: 15px;'>{{$post->published_at}}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">{{trans('admin.user')}}</td>
                                        <td style='margin: 5px; padding: 15px;'>{{$post->user->name}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-12">
                                <a href="{{route('founds.editFound', $post->id)}}" class="btn btn-primary mr-1"><i class="feather icon-edit-1"></i>{{trans('admin.edit')}}</a>
                                <a title="delete" onclick="return true;" id="confirm-color" object_id='{{$post->id}}'
                                    class="btn btn-outline-danger delete"><i class="feather icon-trash-2"></i>{{trans('admin.delete')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- account end -->
            <!-- information start -->
            <div class="col-md-12 col-12 ">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title mb-2">{{trans('admin.description')}}</div>
                    </div>
                    <div class="card-body">
                        <table>
                            <tr>
                                <td>
                                    {{$post->description}}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <!-- information start -->
            <!-- comments start -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom mx-2 px-0">
                        <h6 class="border-bottom py-1 mb-0 font-medium-2"><i class="fa fa-comment-o"></i>{{ trans('admin.comments') }}
                        </h6>
                    </div>
                    <div class="card-body px-75">
                        <div class="table-responsive users-view-permission">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>{{trans('admin.user')}}</th>
                                        <th>{{ trans('admin.comments') }}</th>
                                        <th>{{ trans('admin.published_at') }}</th>
                                        <th>{{ trans('admin.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($post->comments) > 0)
                                        @foreach($post->comments as $comment)
                                            <tr>
                                                <td>{{$comment->user->name}}</td>
                                                <td>
                                                    {{$comment->text}}
                                                </td>
                                                <td>
                                                    {{date('d-m-y', strtotime($comment->created_at))}}
                                                </td>
                                                <td>
                                                    <a title="delete_comment" onclick="return true;" id="confirm-color" object_id='{{$comment->id}}'
                                                        class="btn delete_comment" style="color:white;"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            {{trans('admin.no_comments')}}
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- comments end -->
        </div>
    </section>
    <!-- page users view end -->
</div>
@endsection

@section('scripts')
    <script>

        function setId(){
            return $('.user').val();
        }
        function setStatus(){
            return $('.status').val();
        }

        //delete post
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
                            url: "{{route('losts.delete')}}",
                            type: "post",
                            dataType: 'json',
                            data: {"_token": "{{ csrf_token() }}", id: id},
                            success: function(data){
                                if(data.data == 1){
                                    Swal.fire({
                                        type: 'success',
                                        title: '{{trans('admin.post_deleted')}}',
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

        //delete comment
        $(document).on('click', '.delete_comment', function (e) {
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
                            url: "{{route('comments.delete')}}",
                            type: "post",
                            dataType: 'json',
                            data: {"_token": "{{ csrf_token() }}", id: id},
                            success: function(data){
                                if(data.data == 1){
                                    Swal.fire({
                                        type: 'success',
                                        title: '{{trans('admin.comment_deleted')}}',
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
    </script>

@endsection
