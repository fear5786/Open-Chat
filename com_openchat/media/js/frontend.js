var JQ=jQuery.noConflict();
var maxChatId=0;
JQ(document).ready(function(){
	loadRecentChats();
	function chatScrollDown(){
		JQ('ul#chat-msg').scrollTop(JQ('ul#chat-msg')[0].scrollHeight);
	}
	/**
	 * Check Recent Chat
	 */
	var timer=setInterval(function(){
		loadRecentChats();
	},500);

	/**
 	* Load Recent Check 
	*/
	function loadRecentChats () {
		var param={};
		param.option='com_openchat';
		param.task='getRecentChats';
		param.max_chat_id=maxChatId;
		JQ.post('index.php',param,function(res){
			var obj=JSON.parse(res);
			if(obj.status){
				var chats=obj.chats;
				for (var i = chats.length - 1; i >= 0; i--) {
					var chatDetails=chats[i];
					appendChatmessage(chatDetails);
				};
				console.log(chats);
			}
		});
	}
	/**
	 * Append Chat Message
	 */
	function appendChatmessage(chatDetails){
		var chatId=parseInt(chatDetails.id);
		if(chatId>maxChatId){
			maxChatId=chatId;
		}
		var chatHTML='';
		chatHTML+='<li id="'+chatDetails.id+'">';
			chatHTML+='<span class="user">'+chatDetails.name+" : "+'</span>';
			chatHTML+='<span class="msg">'+chatDetails.msg+'</span>';
		chatHTML+='</li>';
		JQ('ul#chat-msg').append(chatHTML);
		chatScrollDown();
	}

	/**
	 * OnClick Chat Button
	 */
	JQ(document).on('click','#chat_btn',function(){
		var msg=JQ('#msg').val().trim();

		var param={};
		param.option='com_openchat';
		param.task='saveChatViaAjax';
		param.msg=msg;

		JQ.post('index.php',param,function(res){
			var obj=JSON.parse(res);
			if(obj.status){
				JQ('#msg').val('');
				var chatDetails=obj.chatDetails;
				appendChatmessage(chatDetails);
			}else{
				alert(obj.msg);
			}
		});
	});
});