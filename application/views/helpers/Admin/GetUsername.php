<?php
class Zend_View_Helper_GetUsername extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}

	function getUsername(){
	
	$auth = Zend_Auth::getInstance();
	$user = $auth->getIdentity();
	return $user->username;    
	
	


	}
	

	

	

	
}
?>