<?php
class Zend_View_Helper_ViewInboxRtsSubmitted extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewInboxRtsSubmitted(){

	$user = Zend_Auth::getInstance()->getIdentity();
	$role = $user->role_type;
	
		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
	
	if ($role == "CA"){

		$select->where('account_status like ?','CA - RTMA');
		$select->where('relation like ?', 'Principal');
		$select->where('submitted_ca like ?', $user->username);
		$accntdetail = $accnt->fetchAll($select)->count();
	}
	
	if ($role == "CO"){

		$select->where('account_status like ?','CO - RTCA');
		$select->where('relation like ?', 'Principal');
		//$select->where('submitted_co like ?', $user->username);
		$accntdetail = $accnt->fetchAll($select)->count();
	}
	
	
	
	
	return "<font color='RED'><b>".$accntdetail."</b></font>";
	}
	

	
}
?>