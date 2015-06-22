<?
defined('_JEXEC') or die('Access Denined');
jimport ('joomla.controller');
class OpenChatController extends JcontrollerLegacy
{
	/**
	 * Chat History
	 */
	function chat_history()
	{
		$doc=JFactory::getDocument();
		$doc->addStyleSheet(JURI::root().'media/com_openchat/css/openchat.css');
		JToolBarHelper::Title('Chat History','chat-history.png');
		$chats=$this->getChatHistory();
		echo '<pre>';
		print_r($chats);
		echo JText::_('Welcome to Chat History');
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
	 * Get The Chat History MSG
	 * @return [type] [description]
	 */
	function getChatHistory(){
		$db=JFactory::getDBO();
		$query = 'SELECT c.*,u.username,u.name FROM #__openchat_msg as c LEFT JOIN #__users as u ON c.user_id=u.id';
		$db->setQuery($query);
		$rows=$db->loadObjectList();
		return $rows;
	}	
}