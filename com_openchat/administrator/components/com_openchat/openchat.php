<?php
defined ('_JEXEC') or die('Access Denied');
jimport('joomla.controller');
$controller=JControllerLegacy::getInstance('OpenChat');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();