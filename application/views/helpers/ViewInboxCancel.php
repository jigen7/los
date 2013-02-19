<?php
class Zend_View_Helper_ViewInboxCancel extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewInboxCancel($status){
	
	$user = Zend_Auth::getInstance()->getIdentity();
	$role = $user->role_type;

		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		if($status == 'cancel'){
		$select->where("account_status = 'CO - Cancel'");}
		else if($status == 'noaction'){
		$select->where("account_status = 'CO - NA'");}
		else if($status == 'outright'){
		$select->where("account_status = 'CO - OR'");}
		else if($status == 'mareject'){
		$select->where("account_status = 'MA - R'");}
		
		
		$accntdetail = $accnt->fetchAll($select)->count();

	return "<font color='RED'><b>".$accntdetail."</b></font>";
	}
	

	
}
?>