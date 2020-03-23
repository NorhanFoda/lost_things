
@extends('admin.layouts.app')

@section('pageTitle')
{{trans('admin.lost_app')}}
@endsection

@section('pageSubTitle') 
{{trans('admin.user_messages')}}
@endsection

@section('content')

    <!--start div-->

    <div class="row" style="display:block">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">
                    {{trans('admin.user_messages')}}
                </h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/">{{trans('admin.main')}}</a>
                        </li>
                        <li class="breadcrumb-item active">{{trans('admin.user_messages')}}
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        {{-- <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Holy guacamole!</strong> You should check in on some of those fields below.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div> --}}

        <div class="col-12">
            <div class="card" style="width:70%; margin:auto;">
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <!--<a href="{{route('founds.createFound')}}" class="btn btn-primary btn-block my-2 waves-effect waves-light">{{trans('admin.add_post')}} </a>-->
                            <table class="table table-bordered mb-0">
                                <thead>
                                <tr align="center">
                                    <th>#</th>
                                    <th>{{trans('admin.user')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($chats) > 0)
                                    @foreach($chats as $chat)
                                        <tr>
                                            @if(count($chat->messages) > 0)
                                                <td align="center">{{$loop->iteration}}</td>
                                                <td>
                                                    <a href="{{route('chat.getChat', $chat->id)}}" style="color:white">
                                                        <div style="vertical-align:text-top">
                                                            @if($chat->user1_id != auth()->user()->id)
                                                                <img src="{{\App\User::find($chat->user1_id)->image ? \App\User::find($chat->user1_id)->image : '/images/avatar.png'}}" 
                                                                alt="{{\App\User::find($chat->user1_id)->name}}" 
                                                                class="round" width="40" height="40">
                                                                <span style="font-weight:bolder">{{\App\User::find($chat->user1_id)->name}}</span><br>
                                                            @else
                                                                <img src="{{\App\User::find($chat->user2_id)->image ? \App\User::find($chat->user2_id)->image : '/images/avatar.png'}}" 
                                                                alt="{{\App\User::find($chat->user2_id)->name}}" 
                                                                class="round" width="40" height="40">
                                                                <span style="font-weight:bolder">{{\App\User::find($chat->user2_id)->name}}</span><br>
                                                            @endif
                                                            <p class="chat-msg">{{$chat->messages[count($chat->messages) - 1]->message}}</p>
                                                            <small class="chat-date">{{$chat->messages[count($chat->messages) - 1]->created_at->diffForHumans(\Carbon\Carbon::now())}}</small>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td style="text-align:center;">
                                                    <a title="delete" onclick="return true;" id="confirm-color" object_id='{{$chat->id}}'
                                                        class="delete" style="color:white;"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
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
                        token = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            url: "{{route('chats.delete')}}",
                            type: "post",
                            dataType: 'json',
                            data: {"_token": "{{ csrf_token() }}", id: id},
                            success: function(data){
                                if(data.data == 1){
                                    Swal.fire({
                                        type: 'success',
                                        title: '{{trans('admin.chat_deleted')}}',
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

