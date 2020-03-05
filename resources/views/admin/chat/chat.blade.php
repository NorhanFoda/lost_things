{{-- {{dd($user_id)}}s --}}
@extends('admin.layouts.app')

@section('pageTitle')
{{trans('admin.lost_app')}}
@endsection

@section('pageSubTitle') 
{{trans('admin.founds')}}
@endsection

@section('content')

    <div class="wrapper">
        <div class="card">
            <div class="card-header">
                <div class="users">Loading users ...</div>
            </div>
            <div class="card-body">
				@if(count($messages) > 0)
					@foreach ($messages as $msg)
						@if($msg->type == 0 && $msg->user_id == auth()->user()->id)
							<div class="chat-item-right pull-right"></div>
						@else 
							@if($msg->type == 0 && $msg->user_id != auth()->user()->id)
								<div class="chat-item-left pull-left">{{$msg->message}}</div>
							@endif
						@endif
					@endforeach
				@else
					No Messages
				@endif
            </div>
            <div class="card-footer">
                <form id="chat-form">
                    <div class="input-group">
                        <input type="text" name="content" class="form-control" placeholder="Type your message ..." autocomplete="off">
                        <div class="input-group-btn">
                            <button class="btn btn-primary">Send</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
	<script src="https://www.gstatic.com/firebasejs/4.9.1/firebase.js"></script>
	<script>
		var old_users_val = $(".users").html();

		var scroll_bottom = function() {
			var card_height = 0;
			$('.card-body .chat-item').each(function() {
				card_height += $(this).outerHeight();
			});
			$(".card-body").scrollTop(card_height);
		}

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
		localStorage.setItem("user_name", '{{auth()->user()->name}}');
		firebase.database().ref('/messages').on('value', function(snapshot) {
			var chat_element = "";
			if(snapshot.val() != null) {
                var shot = snapshot.val();
                for(let index in shot){
                    var chat_name = '{{auth()->user()->name}}';
                    chat_content = shot[index].message;
					users_name[index] = chat_name;

					chat_element += '<div class="chat-item '+shot[index].type+'">';
					chat_element += '<div class="chat-text">';
					chat_element += chat_content;
					chat_element += '</div>';
					
					// $('.chat-item-right').html(chat_element)
					$(".card-body").html(chat_element);
				}
	
			}else{
				$(".card-body").html('<i>No chat</i>')
			}

			users_name = users_name.reverse();
			users_name = $.unique(users_name);

			var html_name = "";
			for(var i = 0; i < users_name.length; i++) {
				if(users_name[i] != undefined)
					html_name += users_name[i] + ', ';
			}

			$(".card-header .users").html(html_name.substring(0, html_name.length - 2));
			old_users_val = $(".users").html();

			scroll_bottom();
		}, function(error) {
			alert('ERROR! Please, open your console.')
			console.log(error);
		});

		firebase.database().ref('/typing').on('value', function(snapshot) {
			var user = snapshot.val();
			if(user && user.name != user_name) {
				$(".users").html(user.name + ' is typing');
			}else{
				$(".users").html(old_users_val);
			}
		});

		// Get user name from localStorage
		var user_name = localStorage.getItem('user_name');
		
		// #logout action handler
		$("#logout").click(function() {
			var ask = confirm('Are you sure?');
			if(ask) {
				localStorage.removeItem("user_name");
				location.reload();
			}
			return false;
		});

		// Set the card height equal to the height of the window
		$(".card-body").css({
			height: $(window).outerHeight() - 200,
			overflowY: 'auto'
		});

		// Set button text
		if(user_name) {
			var user_name_btn = (user_name.length > 15 ? user_name.substring(0, 12) + '...' : user_name);
			$("#chat-form button").html('Send as ' + user_name_btn);
		}

		// #chat-form action handler
		$("#chat-form").submit(function() {
			var me = $(this),
				chat_content = me.find('[name=content]'),
				user_name = localStorage.getItem('user_name');

			// if the value of chat_content is empty
			if(chat_content.val().trim().length <= 0) {
				// set focus to chat_content
				chat_content.focus();
			}else if(!user_name) {
				alert('We need your name!1!1!1!1');
			}else{ // if the chat_content value is not empty
				$.ajax({
					url: '{{ route('chat.store') }}',
					data: {
						content: chat_content.val().trim(),
						chat_id: '{{$chat_id}}',
						user2_id: '{{$user_id}}'
					},
					method: 'post',
					headers: {
						'X-CSRF-TOKEN': $("meta[name=csrf-token]").attr('content')
					},
					beforeSend: function() {
						chat_content.attr('disabled', true);
					},
					complete: function() {
						chat_content.attr('disabled', false);
					},
					success: function() {
						chat_content.val('');
						chat_content.focus();
						scroll_bottom();
					}
				});
			}

			return false;
		});

		var timer;
		$("#chat-form [name=content]").keyup(function() {
			var ref = firebase.database().ref('typing');
			ref.set({
				name: user_name
			});

			timer = setTimeout(function() {
				ref.remove();
			}, 2000);
		});
	</script>
@endsection
