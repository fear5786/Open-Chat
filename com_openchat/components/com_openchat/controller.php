<?php
defined('_JEXEC') or die('Access Denied');
jimport('joomla.controller');
/**
* Controller
*/
class OpenChatController extends JControllerLegacy
{

	
	function chat()
	{
		$doc=JFactory::getDocument();
		$doc->addStyleSheet(JURI::root().'media/com_openchat/css/frontend.css');
		$doc->addScript(JURI::root().'media/com_openchat/js/jquery-1.11.3.js');
		$doc->addScript(JURI::root().'media/com_openchat/js/frontend.js');
		echo JText::_('Welcome to Chat Form');
		?>
			<div id="openchat">
				<div id="chat-msg-area">
					<ul id="chat-msg">
						
					</ul>
				</div>

				<div id="chat-toolbar">
					<input type="text" name="msg" id="msg">
					<input type="button" class="btn btn-primary" name="chat_btn" id="chat_btn" value="SEND">
				</div>
			</div>

		<?
	}

	/**
	 * save chat message
	 * @return [type] [description]
	 */
	function saveChatViaAjax(){
		$res=array();
		$app=JFactory::getApplication();
		$msg=JRequest::getString('msg');
		$userId=JFactory::getUser()->id;
		if($msg==""){
			$res['status']=false;
			$res['msg']="Please Enter Message";
			echo json_encode($res);
			exit();
		}
		if($userId=="0"){
			$res['status']=false;
			$res['msg']="Please Login Before Chat";
			echo json_encode($res);
			exit();
		}

		if($chatId=$this->saveChat($msg,$userId)){
			$chatDetails=$this->getChatDetailsById($chatId);
			$res['chatDetails']=$chatDetails;
			$res['status']=true;
		}else{
			$res['status']=false;
		}
		echo json_encode($res);
		$app->close();
	}
	
	private function saveChat($msg,$userId){
		$userId=(INT)$userId;
		$db=JFactory::getDBO();
		$query="INSERT INTO #__openchat_msg (msg,user_id) VALUES ('$msg','$userId')";
		$db->setQuery($query);
		if($db->query()){
			return $db->insertid();
		}else{
			return false;
		}
	}
	/**
	 * Chat Details From ID
	 */
	function getChatDetailsById($id){
		 $id=(INT)$id;
		 $db=JFactory::getDBO();
		 $query="SELECT c.id,c.msg,u.name FROM #__openchat_msg as c LEFT JOIN #__users as u ON c.user_id=u.id WHERE c.id=$id LIMIT 1";
		 $db->setQuery($query);
		 return $db->loadObject();
	}

	function getRecentChats(){
		 $res=array();
		 $app=JFactory::getApplication();
		 $db=JFactory::getDBO();
		 $max_chat_id=JRequest::getInt('max_chat_id',0);
		 if($max_chat_id>0){
			$query="SELECT c.id,c.msg,u.name FROM #__openchat_msg as c LEFT JOIN #__users as u ON c.user_id=u.id WHERE c.id>$max_chat_id ORDER BY c.id DESC LIMIT 0,50";
		 }else{
		 	$query="SELECT c.id,c.msg,u.name FROM #__openchat_msg as c LEFT JOIN #__users as u ON c.user_id=u.id ORDER BY c.id DESC LIMIT 0,50";

		 }
		 
		 $db->setQuery($query);	
		 $rows=$db->loadObjectList();
		 if($rows){
			$res['chats']=$rows;
			$res['status']=true;
		 }else{
			$res['status']=false;
		 }
		 echo json_encode($res);
		 $app->close();
	}

}