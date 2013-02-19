<?php
class Zend_View_Helper_RolePermission extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}

	function rolePermission($process,$capno){
	
	$auth = Zend_Auth::getInstance();
	$user = $auth->getIdentity();
	$role = $user->role_type;    
		
	$rolePermission = new Model_Admin_RolePermission();	
	
	$select = $rolePermission->select();
	$select->where("process like ?",$process);
	$detail = $rolePermission->fetchRow($select);
	
	return $detail->$role;
	


	}
	

	

	

	
}
?>