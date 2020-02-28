
@extends('admin.layouts.app')

@section('pageTitle')
{{trans('admin.lost_app')}}
@endsection

@section('pageSubTitle') 
{{trans('admin.users')}}
@endsection

@section('content')

    <!--start div-->

    <div class="row" style="display:block">


        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">
                    {{trans('admin.users')}}
                </h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">{{trans('admin_content.main')}}</a>
                        </li>
                        <li class="breadcrumb-item active">{{trans('admin.users')}}
                        </li>
                    </ol>
                </div>
            </div>
        </div>


        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <a href="{{route('users.create')}}" class="btn btn-primary btn-block my-2 waves-effect waves-light">{{trans('admin.add_user')}} </a>
                            <table class="table table-bordered mb-0">
                                <thead>
                                <tr align="center">
                                    <th>#</th>
                                    <th>{{trans('admin.name')}}</th>
                                    <th>{{trans('admin.email')}}</th>
                                    <th>{{trans('admin.phone')}}</th>
                                    <th>{{trans('admin.status')}}</th>
                                    <th>{{trans('admin.image')}}</th>
                                    <th>{{trans('admin.action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr align="center">
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email ? $user->email : trans('admin.nodata')}}</td>
                                        <td>{{$user->phone ? $user->phone : trans('admin.nodata')}}</td>
                                        <td>
                                            <a @if($user->is_blockded == 0 ) title="unban" @else title="ban" @endif onclick="return true;" id="confirm-color" object_id='{{$user->id}}' object_status='{{$user->is_blocked}}'  class="ban-unlock">
                                                @if($user->is_blocked == 1)<i class="fa fa-ban"></i> @else <i class="fa fa-unlock"></i>@endif </a>
                                        </td>
                                        <td><img src="{{asset($user->image ? asset($user->image) : 'uploads/avatar.png')}}" alt="user" style="width:200px; height:100px"></td>
                                        <td>
                                            <a href="{{route('users.show', $user->id)}}">show</a>
                                            <a href="{{route('users.edit', $user->id)}}">edit</a>
                                            <form action="{{route('users.destroy', $user->id)}}">
                                                {{csrf_field()}}
                                                {{method_field('DELETE')}}
                                                <button type="submit">delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!--end div-->

@endsection










@section('scripts')
    <script>
        $(document).on('click', '.ban-unlock', function (e) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'swal2-confirm',
                    cancelButton: 'swal2-cancel'
                },
                buttonsStyling: true
            });
            swalWithBootstrapButtons.fire({
                title: '{{trans('sweet_alert.title')}}',
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
                        type: 'post',
                        url: 'ban-user/'+id,
                        data: {
                            _method:'get',
                            _token: token
                        } ,
                        dataType: 'json',
                        success: function(response) {
                            if (response === 'ban') {
                                Swal.fire({
                                    type: 'success',
                                    title: '{{trans('sweet_alert.banned')}}',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                window.location.reload();
                            } else if (response === 'unban') {
                                Swal.fire({
                                    type: 'success',
                                    title: '{{trans('sweet_alert.unbanned')}}',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                window.location.reload();
                            }
                        }
                    });
                } else if (
                    // / Read more about handling dismissals below /
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire({
                        title: '{{trans('sweet_alert.cancelled')}}',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            })
        });
    </script>

@endsection
