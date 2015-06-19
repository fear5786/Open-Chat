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
		echo JText::_('Welcome to Chat Form');
	}
}