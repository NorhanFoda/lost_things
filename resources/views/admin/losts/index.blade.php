
@extends('admin.layouts.app')

@section('pageTitle')
{{trans('admin.lost_app')}}
@endsection

@section('pageSubTitle') 
{{trans('admin.losts')}}
@endsection

@section('content')

    <!--start div-->

    <div class="row" style="display:block">

`
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
                            <a href="{{route('losts.createLost')}}" class="btn btn-primary btn-block my-2 waves-effect waves-light">{{trans('admin.add_post')}} </a>
                            <table class="table table-bordered mb-0">
                                <thead>
                                <tr align="center">
                                    <th>#</th>
                                    <th>{{trans('admin.title')}}</th>
                                    <th>{{trans('admin.user')}}</th>
                                    <th>{{trans('admin.location')}}</th>
                                    <th>{{trans('admin.published_at')}}</th>
                                    <th>{{trans('admin.image')}}</th>
                                    <th>{{trans('admin.action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($losts as $lost)
                                    <tr align="center">
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$lost->title}}</td>
                                        <td>{{$lost->user->name}}</td>
                                        <td>{{$lost->location}}</td>
                                        <td>{{$lost->published_at}}</td>
                                        <td>
                                            <img @if(count($lost->images) > 0) src="{{$lost->images[0]->path}}" @else src="no image" @endif alt="user" style="width:100px; height:100px">
                                        </td>
                                        <td>
                                            <a href="{{route('losts.showLost', $lost->id)}}" class="btn" style="color:white"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                            <a href="{{route('losts.editLost', $lost->id)}}" class="btn" style="color:white;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                            <a title="delete" onclick="return true;" id="confirm-color" object_id='{{$lost->id}}'
                                                class="btn delete" style="color:white;"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                            {{-- <form action="{{route('losts.destroy', $lost->id)}}" method="POST" style="display:inline-block">
                                                {{csrf_field()}}
                                                {{method_field('DELETE')}}
                                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                            </form> --}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
        {{$losts->links()}}
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
                                        timer: 2000
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
                        timer: 2000
                    });
                }
            })
        });
    </script>

@endsection
