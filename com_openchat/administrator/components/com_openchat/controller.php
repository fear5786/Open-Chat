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
}