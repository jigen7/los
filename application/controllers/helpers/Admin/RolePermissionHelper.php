<?php

class Zend_Controller_Action_Helper_RolePermissionHelper extends Zend_Controller_Action_Helper_Abstract
{
 
	function direct($process){	
		$redirector = $this->_actionController->getHelper('Redirector');
		$role = new Model_Admin_RolePermission();		
		if($role->hasPermission($process) === FALSE || $role->hasPermission($process) == null){
			$redirector->gotoUrl('/error/denied/');		
		}
	}
	
	function officersredirect($process){
		$redirector = $this->_actionController->getHelper('Redirector');
		$role = new Model_Admin_RolePermission();		
		if($role->hasPermission($process) === TRUE){
			$redirector->gotoUrl('/index/indexroute/');		
		}		
		
	}
	
	function isApproved($process){
		$redirector = $this->_actionController->getHelper('Redirector');

		//Helper to determine account status that are approved
		$status = new Model_Admin_AccountStatus();
		//return boolean
		if($status->isApproved($process) === FALSE){
			$redirector->gotoUrl('/error/denied/');		
		}
		
	}		
	
	function hasAccess($process){
		$role = new Model_Admin_RolePermission();		
		return $role->hasPermission($process);	
	}
	
	function statusAccess($acctstatus,$field){
		$redirector = $this->_actionController->getHelper('Redirector');

		//Helper to determine account status that are approved
		$status = new Model_Admin_AccountStatus();
		//return boolean
		if($status->routeview($acctstatus,$field) === FALSE){
			//$redirector->gotoUrl('/error/endorsedenied/');		
			$redirector->gotoUrl('/error/denied/');		
		}
		
		
	}
	

}





