<?
defined('_JEXEC') or die('Access Denined');
jimport ('joomla.controller');
class OpenChatController extends JcontrollerLegacy
{
	private $perPage;
	private $blockedlisperPage;
	private $limitstart;
	private $pagination;

	function __construct(){
		parent::__construct();
		$this->perPage=10;
		$this->limitstart=JRequest::getInt('limitstart',0);
	}

	/**
	 * Chat History
	 */
	function chat_history()
	{
		$doc=JFactory::getDocument();
		$doc->addStyleSheet(JURI::root().'media/com_openchat/css/openchat.css');
		JToolBarHelper::Title('Chat History','chat-history.png');
		$chats=$this->getChatHistory();
		JHTML::_('behavior.formvalidation');
		echo '<form action="index.php?option=com_openchat&task=chat_history" method="post" class="pagi" name="adminForm" id="adminForm">';
		echo '<div class="tableinfo">';
		echo '<table class="table" cellspacing="1" align="center">';
			echo'<thead>';
				echo '<tr>';
					echo '<th>ID</th>';
					echo '<th>Full Name</th>';
					echo '<th>Username</th>';
					echo '<th>ChatMsg</th>';
					echo '<th>Date Time</th>';
					echo '<th>Action</th>';
				echo '</tr>';
			echo'</thead>';
			echo '<tbody>';
		for ($i=0; $i < count($chats); $i++) { 
			$chat=$chats[$i];
			echo '<tr>';
					echo '<td>'.$chat->id.'</td>';
					echo '<td>'.$chat->name.'</td>';
					echo '<td>'.$chat->username.'</td>';
					echo '<td>'.$chat->msg.'</td>';
					echo '<td>'.$chat->datetime.'</td>';
					if($chat->blocked_users>0){
						echo '<td><a href="index.php?option=com_openchat&task=unblockuser&user_id='.$chat->user_id.'">UnBlock User</td>';
					}else{
						echo '<td><a href="index.php?option=com_openchat&task=blockuser&user_id='.$chat->user_id.'">Block User</td>';
					}
					
			echo '</tr>';
		}
			echo '</tbody>';
			echo '<tfoot>';
				echo'<tr>';
					echo'<td colspan="6">'.$this->pagination->getListFooter().'</td>';
				echo'</tr>';

		echo '</table>';
		echo '</div>';
		echo '</form>';
	}

	/**
	 * Block Users
	 */
	function blocked_users()
	{
		$doc=JFactory::getDocument();
		$doc->addStyleSheet(JURI::root().'media/com_openchat/css/openchat.css');
		JToolBarHelper::Title('Blocked Users','blocked-chat.png');
		echo JText::_('Welcome to Blocked Users');
		echo '<pre>';
		$blockedUsers=$this->getBlockedUsers();
		JHTML::_('behavior.formvalidation');
		echo '<form action="index.php?option=com_openchat&task=blocked_users" method="post" name="adminForm" id="adminForm">';
		echo '<div class="tableinfo">';
		echo '<table class="table" cellspacing="1" align="center">';
			echo'<thead>';
				echo '<tr>';
					echo '<th>ID</th>';
					echo '<th>Full Name</th>';
					echo '<th>Username</th>';
					echo '<th>Date Time</th>';
					echo '<th>Action</th>';
				echo '</tr>';
			echo'</thead>';
			echo '<tbody>';
		for ($i=0; $i < count($blockedUsers); $i++) { 
			$blockedUser=$blockedUsers[$i];
			echo '<tr>';
					echo '<td>'.$blockedUser->id.'</td>';
					echo '<td>'.$blockedUser->name.'</td>';
					echo '<td>'.$blockedUser->username.'</td>';
					echo '<td>'.$blockedUser->datetime.'</td>';
						echo '<td><a href="index.php?option=com_openchat&task=unblockuser&user_id='.$blockedUser->user_id.'">UnBlock User</td>';
					
			echo '</tr>';
		}
			echo '</tbody>';
			echo '<tfoot>';
				echo'<tr>';
					echo'<td colspan="5">'.$this->pagination->getListFooter().'</td>';
				echo'</tr>';

		echo '</table>';
		echo '</div>';
		echo '</form>';
	}

	/**
	 * Get Blocked Users
	 */
	function getBlockedUsers(){
		$db=JFactory::getDBO();
		$this->perPage=3;
		$query = 'SELECT ub.*,u.username,u.name FROM #__openchat_blocked_users as ub 
		LEFT JOIN #__users as u ON ub.user_id=u.id 
		ORDER BY ub.id DESC LIMIT '.$this->limitstart.','.$this->perPage;
		$db->setQuery($query);
		$db->query();
		$rows=$db->loadObjectList();
		$total=$this->getTotalBlockedUsers();
		$this->pagination=new JPagination($total,$this->limitstart,$this->perPage);
		return $rows;
	}

	function getTotalBlockedUsers(){
		$db=JFactory::getDBO();
		$query =  'SELECT ub.*,u.username,u.name FROM #__openchat_blocked_users as ub 
		LEFT JOIN #__users as u ON ub.user_id=u.id 
		ORDER BY ub.id DESC';
		$db->setQuery($query);
		$db->query();
		$total=$db->getNumRows();
		return $total;
	}

	/**
	 * Get Total Chats
	 */
	private function getTotal(){
		$db=JFactory::getDBO();
		$query = 'SELECT c.*,u.username,u.name,ub.user_id as blocked_users FROM #__openchat_msg as c LEFT JOIN #__users as u ON c.user_id=u.id LEFT JOIN #__openchat_blocked_users as ub ON c.user_id=ub.user_id ORDER BY c.id DESC';
		$db->setQuery($query);
		$db->query();
		$total=$db->getNumRows();
		return $total;
	}

	/**
	 * Get The Chat History MSG
	 * @return [type] [description]
	 */
	function getChatHistory(){
		$db=JFactory::getDBO();
		$query = 'SELECT c.*,u.username,u.name,ub.user_id as blocked_users FROM #__openchat_msg as c 
		LEFT JOIN #__users as u ON c.user_id=u.id 
		LEFT JOIN #__openchat_blocked_users as ub ON c.user_id=ub.user_id
		ORDER BY c.id DESC LIMIT '.$this->limitstart.','.$this->perPage;
		$db->setQuery($query);
		$db->query();
		$rows=$db->loadObjectList();
		$total=$this->getTotal();
		$this->pagination=new JPagination($total,$this->limitstart,$this->perPage);
		return $rows;
	}
	/**
	 * Add user to block-list follow the user_id which following in the href
	 * ex. index.php?option=com_openchat&task=blockuser&user_id=623 //add user id 623 into the block-list
	 * @return [type] [description]
	 */
	function blockuser(){
		$app=JFactory::getApplication();
		$userId=JRequest::getInt('user_id');
		$db=JFactory::getDBO();
		$db->setQuery("INSERT INTO #__openchat_blocked_users (user_id) VALUES ($userId)");
		if($db->query()){
			$app->redirect('index.php?option=com_openchat&task=chat_history','User Blocked Sucessfully');
		}else{
			$app->redirect('index.php?option=com_openchat&task=chat_history','Error Occured','error');
		}

	}
	/**
	 * 
	 * 
	 * 
	 */
	function unblockuser(){
		$app=JFactory::getApplication();
		$userId=JRequest::getInt('user_id');
		$db=JFactory::getDBO();
		$db->setQuery("DELETE FROM #__openchat_blocked_users WHERE user_id=$userId");
		if($db->query()){
			$app->redirect('index.php?option=com_openchat&task=blocked_users','User Unblocked Sucessfully');
		}else{
			$app->redirect('index.php?option=com_openchat&task=blocked_users','Error Occured','error');
		}

	}
}