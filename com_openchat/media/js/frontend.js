var JQ=jQuery.noConflict();
JQ(document).ready(function(){
	//alert("WORKED");
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
			}else{
				alert(obj.msg);
			}
		});
	});
});