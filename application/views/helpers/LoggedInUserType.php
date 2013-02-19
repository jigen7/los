<?php
class Zend_View_Helper_LoggedInUserType extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function loggedInUserType(){

	$auth = Zend_Auth::getInstance();
	$user = $auth->getIdentity();
	return $user->role_type;
	}
	

	
}
?>