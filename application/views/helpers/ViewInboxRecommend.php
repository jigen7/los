<?php
class Zend_View_Helper_ViewInboxRecommend extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewInboxRecommend(){
		

	
	$user = Zend_Auth::getInstance()->getIdentity();
	$role = $user->role_type;

		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		
		if ($role =="CA"){
		$select->where("account_status = 'CA - ReAp' OR account_status = 'CA - ReR'");
		$select->where('submitted_ca like ?', $user->username);
		$accntdetail = $accnt->fetchAll($select)->count();
		}
	return "<font color='RED'><b>".$accntdetail."</b></font>";
	}
	

	
}
?>