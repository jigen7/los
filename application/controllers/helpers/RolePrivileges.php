<?php

class Zend_Controller_Action_Helper_RolePrivileges extends
                Zend_Controller_Action_Helper_Abstract
{
	/***
	 * Helper for edit account restriction via accoutn status and users
	 * 
	 * 
	 *  
	 */
    function direct($capno)
    {
	$moduleName = $this->getRequest()->getModuleName();
    $controller = $this->getRequest()->getControllerName();
    $action = $this->getRequest()->getActionName();
    $frontController = Zend_Controller_Front::getInstance();
	$redirector = $this->_actionController->getHelper('Redirector');
	$user = Zend_Auth::getInstance()->getIdentity();
	$role = $user->role_type;
	$username = $user->username;
	$userRoles = new Model_UsersRoles();
	$select = $userRoles->select()->where('roles like ?',$user->role_type);
	$access = $userRoles->fetchRow($select);
	
	$capno = getprincipal($capno);
	$accnt = new Model_BorrowerAccount();
	$select = $accnt->select();
	$select->where('capno like ?',$capno);
	$accntdetail = $accnt->fetchRow($select);
	$status = $accntdetail->account_status;
	$comaker_status = $accntdetail->comaker_accnt_status;
	//echo $role.$username.$status;
	if($role == 'MA'){
		if ($accntdetail->created_by != $username){
		$redirector->gotoUrl('/error/denied/'); 
			}
		else if(($status != 'MA - EN') && ($status != 'MA - E') &&
		($status != 'CA - RTMA' ) && ($status != 'CO - NA') && ($comaker_status != 'MA - CMK')
		){
		$redirector->gotoUrl('/error/denied/'); 
		}
	}
	else if($role == 'CA'){
		if ($accntdetail->submitted_ca != $username){
		$redirector->gotoUrl('/error/denied/'); 
			}
		else if(($status != 'MA - S') && ($status != 'CA - An') && ($status != 'CA - AnD') &&
		($status != 'CO - RTCA') && ($comaker_status != 'CA - CMK')){
		$redirector->gotoUrl('/error/denied/'); 
		}
	}
	else if($role == 'CO'){
		if ($accntdetail->submitted_co != $username){
		$redirector->gotoUrl('/error/denied/'); 
			}
		else if(($status != 'CA - ReAp') && ($status != 'CA - ReR') && ($status != 'CO - REV') && ($status != 'CO - REVD') &&
		($status != 'CSH - RTCO') && ($status != 'CA - OR')  ){
		$redirector->gotoUrl('/error/denied/'); 
		}
	}


	

    }
}

function getprincipal($capno){
		$capnosep = capnosep($capno);
		$recon = capnorecon($capno);
	
		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		$select->where('capno like ?',$capnosep.'_'.$recon);
		$select->where('relation like ?','Principal');
		$accntdetail = $accnt->fetchRow($select);
	
	
return $accntdetail->capno;
}
