<?php
class Zend_View_Helper_ViewInboxDecided extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewInboxDecided(){
		

	
	$user = Zend_Auth::getInstance()->getIdentity();
	$role = $user->role_type;

		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		
		if ($role =="CO"){
		$select->where("account_status = 'CO - Ap' OR account_status = 'CO - R' OR account_status = 'Approved' 
		OR account_status = 'Rejected'");
		$select->where('submitted_co like ?', $user->username);
		$accntdetail = $accnt->fetchAll($select)->count();
		}
	return "<font color='RED'><b>".$accntdetail."</b></font>";
	}
	

	
}
?>