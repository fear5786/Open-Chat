<?
defined('_JEXEC') or die('Access Denined');
jimport ('joomla.controller');
class OpenChatController extends JcontrollerLegacy
{
	private $perPage;
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
		echo '<form action="index.php?option=com_openchat&task=chat_history" method="post" name="adminForm" id="adminForm">';
		echo '<table class="table" cellspacing="1" align="center">';
			echo'<thead>';
				echo '<tr>';
					echo '<th>ID</th>';
					echo '<th>Full Name</th>';
					echo '<th>Username</th>';
					echo '<th>ChatMsg</th>';
					echo '<th>Date Time</th>';
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
			echo '</tr>';
		}
			echo '</tbody>';
			echo '<tfoot>';
				echo'<tr>';
					echo'<td>'.$this->pagination->getListFooter().'</td>';
				echo'</tr>';

		echo '</table>';
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
	}
	/**
	 * Get Total Chats
	 */
	private function getTotal(){
		$db=JFactory::getDBO();
		$query = 'SELECT c.*,u.username,u.name FROM #__openchat_msg as c LEFT JOIN #__users as u ON c.user_id=u.id ORDER BY c.id DESC';
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
		$query = 'SELECT c.*,u.username,u.name FROM #__openchat_msg as c LEFT JOIN #__users as u ON c.user_id=u.id ORDER BY c.id DESC LIMIT '.$this->limitstart.','.$this->perPage;
		$db->setQuery($query);
		$db->query();
		$rows=$db->loadObjectList();
		$total=$this->getTotal();
		$this->pagination=new JPagination($total,$this->limitstart,$this->perPage);
		return $rows;
	}	
}