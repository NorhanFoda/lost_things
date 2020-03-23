@extends('admin.layouts.app')

@section('pageTitle')
{{trans('admin.lost_app')}}
@endsection

@section('pageSubTitle') 
{{trans('admin.user_messages')}}
@endsection

@section('content')

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

    <div class="col-12">
        <div class="card" style="width:70%; margin:auto;">
            <div class="card-content">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr align="center">
                                    <th>{{trans('admin.chats')}}</th>
                                </tr>
                            </thead>
                            <tbody class="chat-body">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
	<script src="https://www.gstatic.com/firebasejs/4.9.1/firebase.js"></script>
	<script>

		// Initialize Firebase
		var config = {
			apiKey: '{{config('services.firebase.api_key')}}',
			authDomain: '{{config('services.firebase.auth_domain')}}',
			databaseURL: "{{config('services.firebase.database_url')}}",
			projectId: "{{config('services.firebase.project_id')}}",
			storageBucket: "{{config('services.firebase.storage_bucket')}}",
			messagingSenderId: "{{config('services.firebase.messaging_sender_id')}}"
		};
		firebase.initializeApp(config);

		// chats
		var users_name = [];
		firebase.database().ref('/messages').on('value', function(snapshot) {
			var chat_element = "";
            var chat_array = [];
			if(snapshot.val() != null) {
                var shot = snapshot.val();
                for(let index in shot){
                    if(chat_array.indexOf(shot[index].chat_id) == -1){
                        chat_array.push(shot[index].chat_id);
                    }
                }

                for(var i = 0; i < chat_array.length; i++){
                    $.ajax({
                        url: "{{route('getChatUsers')}}",
                        type: "POST",
                        dataType: 'html',
                        data: {"_token": "{{ csrf_token() }}", id: chat_array[i] },
                        success: function(data){
                            var chat = JSON.parse(data);
                            var sender_name = chat.sender.name;
                            var sender_image = chat.sender.image;
                            //build chat element
                            chat_element += '<tr>';
                            chat_element += `<td>
                                                <a href="get_chat_page/`+chat.chat_id+`" style="color:white">
                                                    <div style="vertical-align:text-top">
                                                        <img src="`+sender_image+`" 
                                                        alt="`+sender_name+`" 
                                                        class="round" width="40" height="40">
                                                        <span style="font-weight:bolder; margin:5px;">`+sender_name+`</span><br>
                                                        <p class="chat-msg">`+chat.last_msg.message+`</p>
                                                        <small class="chat-date">`+chat.sent_time+`</small>
                                                    </div>
                                                </a>
                                            </td>
                                            <td style="text-align:center;">
                                                <a title="delete" onclick="return true;" id="confirm-color" object_id='`+chat.chat_id+`'
                                                    class="delete" style="color:white;"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                            </td>`;
                            chat_element += '</tr>';
                            $(".chat-body").html(chat_element);   
                        }
                    });
                }
			}
            else{
				$(".chat-body").html('<tr><td>No chat</td></tr>')
			}

			
		}, function(error) {
			alert('ERROR! Please, open your console.')
			console.log(error);
		});

        //delete chat
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
                                    // window.location.reload(); 
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
