<?php

class Zend_Controller_Action_Helper_RoleAccess extends
                Zend_Controller_Action_Helper_Abstract
{
    function direct()
    {
	$moduleName = $this->getRequest()->getModuleName();
    $controller = $this->getRequest()->getControllerName();
    $action = $this->getRequest()->getActionName();
    $frontController = Zend_Controller_Front::getInstance();
	$redirector = $this->_actionController->getHelper('Redirector');
       	
		
	$user = Zend_Auth::getInstance()->getIdentity();
	$userRoles = new Model_UsersRoles();
	$select = $userRoles->select()->where('roles like ?',$user->role_type);
	$access = $userRoles->fetchRow($select);
	
	if ((!$access->access_add_account) && ($controller == 'index') && ($action == 'addaccount')){
		//$redirector->gotoUrl('/error/denied/'); 
		}
	else if ((!$access->edit_borrower_profile) && ($controller == 'index') && ($action == 'accountedit')){
		$redirector->gotoUrl('/error/denied/'); }

	else if ((!$access->access_admin) && ($controller == 'admin') && ($action == 'index')){
		$redirector->gotoUrl('/error/denied/'); }

	
	
    }
}

