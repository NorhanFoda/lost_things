
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
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <a href="{{route('founds.createFound')}}" class="btn btn-primary btn-block my-2 waves-effect waves-light">{{trans('admin.add_post')}} </a>
                            <table class="table table-bordered mb-0">
                                <thead>
                                <tr align="center">
                                    <th>#</th>
                                    <th>{{trans('admin.user')}}</th>
                                    <th>{{trans('admin.message')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($users) > 0)
                                    @foreach($users as $user)
                                        <tr align="center">
                                            @if(count($user->messages) > 0)
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$user->name}}</td>
                                                <td>
                                                    <a href="{{route('chat.getChat', $user->messages[count($user->messages) - 1]->chat_id)}}" 
                                                        style="color:white">
                                                        <div style="width:auto; height:auto">{{$user->messages[count($user->messages) - 1]->message}}</div>
                                                    </a>
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
        function viewChat(chat_id){
            alert(chat_id);
        }
    </script>

@endsection
