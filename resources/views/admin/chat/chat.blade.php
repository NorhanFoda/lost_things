
@extends('admin.layouts.app')

@section('pageTitle')
{{trans('admin.lost_app')}}
@endsection

@section('pageSubTitle') 
{{trans('admin.founds')}}
@endsection

@section('content')

    <div class="wrapper">
        <div class="card" style="width:70%; margin:auto;">
            <div class="card-header chet-header">
				<div class="users">Loading users ...</div>
            </div>
            <div class="card-body">
				{{-- <div class="chat-item"></div> --}}
            </div>
            <div class="card-footer">
				<form id="chat-form" enctype="multipart/form-data">
                    <div class="input-group">
						<input type="text" name="content"style="border-radius: 20px;" id="content" class="form-control" placeholder="Type your message ..." autocomplete="off">
						{{-- <input type="file" name="image" id="image" class="form-control" accept=".gif, .jpg, .png, .webp"> --}}
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-send"style="border-radius: 50%; width:40px;" id="send"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
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

		var escapeHtml = function(unsafe) {
		    return unsafe
		         .replace(/&/g, "&amp;")
		         .replace(/</g, "&lt;")
		         .replace(/>/g, "&gt;")
		         .replace(/"/g, "&quot;")
		         .replace(/'/g, "&#039;");
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
				first_index = Object.keys(shot)[0];
				next = Object.keys(shot)[1];
				last_index = Object.keys(shot)[Object.keys(shot).length - 2];
                for(let index in shot){
                    if(shot[index].chat_id == {{$chat_id}}){
						var chat_name = '{{auth()->user()->name}}';
					
						if(shot[index].user_id == '{{auth()->user()->id}}' && shot[index].type == 1){
							continue;
						}

						//sender
						else if(shot[index].user_id != '{{auth()->user()->id}}' && shot[index].type == 0){
							// chat_content = shot[index].message;
							users_name[index] = chat_name;
							chat_element += '<div class="chat-2" style="margin:10px; text-align: left;">';
							if(shot[index].image !=null && shot[index].message != null){
								chat_content = escapeHtml(shot[index].message);
								chat_element += `<div class="chat-left1">
													<div  class="text-chet1">`+chat_content+` 
													</div>
													<img class='round' height='150' width='150' src='`+shot[index].image+`' alt='no image'/>
													<img  class='round uesr-chat1'  height='30' width='30' src='`+'{{\App\User::find($user_id)->image ? \App\User::find($user_id)->image : "/images/avatar.png"}}'+`' alt='no image'/>
												</div><br>`;
							}
							else if(shot[index].image !=null && shot[index].message == null){
								chat_element += `<div class="chat-left1">
													<img class='round' height='150' width='150' src='`+shot[index].image+`' alt='no image'/>
													<img  class='round uesr-chat1'  height='30' width='30' src='`+'{{\App\User::find($user_id)->image ? \App\User::find($user_id)->image : "/images/avatar.png"}}'+`' alt='no image'/>
												</div><br>`;
							}else if(shot[index].image ==null && shot[index].message != null){
								chat_content = escapeHtml(shot[index].message);
								chat_element += `<div class="chat-left1">
													<div  class="text-chet1">`+chat_content+` 
													</div>
													<img  class='round uesr-chat1'  height='30' width='30' src='`+'{{\App\User::find($user_id)->image ? \App\User::find($user_id)->image : "/images/avatar.png"}}'+`' alt='no image'/>
												</div><br>`;
							}							
							chat_element += `</div>`;	
						}

						//receiver
						if(shot[index].user_id == '{{auth()->user()->id}}' && shot[index].type == 0){
							chat_content = shot[index].message;
							users_name[index] = chat_name;

							chat_element += '<div>';
							// if(next != last_index && shot[index].type == 0 && shot[next].user_id == shot[index].user_id){
							// 	console.log('current:  '+ shot[index].user_id);
							// 	console.log('next:  '+ shot[next].user_id);
							// 	chat_element += `<div>`+chat_content+`</div><br>`;
							// }
							// else{
								chat_element += `<div  style="margin:10px;">
												<img class='round' height='30' width='30' src=`+'{{auth()->user()->image ? auth()->user()->image : "/images/avatar.png"}}'+` alt='no image'/>
												<div style="background:#00000069; padding:10px; min-width:40%; max-width:80%; display:inline-block; border-radius:20px; vertical-align: text-top;">	`+chat_content+`</div>
											</div><br>`;
							// }
							chat_element += '</div>';

						}
						else if(shot[index].user_id != '{{auth()->user()->id}}' && shot[index].type == 1){
							continue;
						}
						$(".card-body").html(chat_element);
					}
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
			$(".card-header").append("<img class='round uesr-chet-2 ' height='30' width='30' src='{{auth()->user()->image ? auth()->user()->image : '/images/avatar.png'}}' alt='no image'/>");

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
			// $("#chat-form button").html('Send as ' + user_name_btn);
		}

		// #chat-form action handler
		$("#chat-form").submit(function(e) {
			var me = $(this),
				chat_content = me.find('[name=content]'),
				user_name = localStorage.getItem('user_name');

			//if the value of chat_content is empty
			if(chat_content.val().trim().length <= 0) {
				//set focus to chat_content
				chat_content.focus();
			}else if(!user_name) {
				alert('We need your name!1!1!1!1');
			}
			else{ 
				$.ajax({
					url: "{{ route('chat.store') }}",
					data: {
						content: chat_content.val().trim(),
						chat_id: '{{$chat_id}}',
						user2_id: '{{$user_id}}',
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
