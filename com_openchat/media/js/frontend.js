var JQ=jQuery.noConflict();
JQ(document).ready(function(){
	loadRecentChats();
	function chatScrollDown(){
		JQ('ul#chat-msg').scrollTop(JQ('ul#chat-msg')[0].scrollHeight);
	}
	/**
 	* Load Recent Check 
	*/
	function loadRecentChats () {
		var param={};
		param.option='com_openchat';
		param.task='getRecentChats';
		JQ.post('index.php',param,function(res){
			var obj=JSON.parse(res);
			if(obj.status){
				var chats=obj.chats;
				console.log(chats);
			}
		});
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
				var chatHTML='';
				chatHTML+='<li id="'+chatDetails.id+'">';
					chatHTML+='<span class="user">'+chatDetails.name+" : "+'</span>';
					chatHTML+='<span class="msg">'+chatDetails.msg+'</span>';
				chatHTML+='</li>';
				JQ('ul#chat-msg').append(chatHTML);
				chatScrollDown();
				console.log(chatHTML);
			}else{
				alert(obj.msg);
			}
		});
	});
});