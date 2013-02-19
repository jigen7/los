<?php
class Zend_Controller_Action_Helper_GetUsername extends Zend_Controller_Action_Helper_Abstract
{

	function direct(){
		$auth = Zend_Auth::getInstance();
		$user = $auth->getIdentity();
		return $user->username;    
	}
	
}
?>