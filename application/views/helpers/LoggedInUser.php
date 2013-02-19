<?php
class Zend_View_Helper_LoggedInUser extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function loggedInUser(){

	$auth = Zend_Auth::getInstance();
	$user = $auth->getIdentity();
	$username = $this->_view->escape(ucfirst($user->username));


	return $username;
	}
	

	
}
?>