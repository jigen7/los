<?php

class Zend_Controller_Action_Helper_RoleAccess2 extends
                Zend_Controller_Action_Helper_Abstract
{

	/***
	 * Paolo Marco Manarang
	 * March 24,2010
	 * Role Acess use in acconuteditAction where to know who can add spouse and coborrower
	 * ***/

    function direct($process)
    {
	$user = Zend_Auth::getInstance()->getIdentity();
	$userRoles = new Model_UsersRoles();
	$select = $userRoles->select()->where('roles like ?',$user->role_type);
	$access = $userRoles->fetchRow($select);
	
	if (($access->edit_profile) && ($process == 'add_spouse')){
	return true;
	}
	if (($access->edit_profile) && ($process == 'add_coborrower')){
	return true;
	}
	else { 
	return false; 
	}
	
	
    }
}



