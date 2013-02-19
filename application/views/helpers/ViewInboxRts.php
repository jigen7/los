<?php
class Zend_View_Helper_ViewInboxRts extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewInboxRts(){

	$user = Zend_Auth::getInstance()->getIdentity();
	$role = $user->role_type;
	
		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
	
	if ($role == "MA"){
		$select->where("account_status = 'CA - RTMA'");
		$select->where('created_by like ?', $user->username);
		$select->where('relation like ?', 'Principal');
		$accntdetail = $accnt->fetchAll($select)->count();
	}
	if ($role == "AO"){
		$select->where("account_status = 'LA - RTMA'  ");
		$select->where('relation like ?', 'Principal');
		$accntdetail = $accnt->fetchAll($select)->count();
	}
	if ($role == "CA"){

		$select->where('account_status like ?','CO - RTCA');
		$select->where('relation like ?', 'Principal');
		$select->where('submitted_ca like ?', $user->username);
		$accntdetail = $accnt->fetchAll($select)->count();
	}
	
	if ($role == "CO"){

		$select->where('account_status like ?','CSH - RTCO');
		$select->where('relation like ?', 'Principal');
		//$select->where('submitted_co like ?', $user->username);
		$accntdetail = $accnt->fetchAll($select)->count();
	}
	
	if ($role == "LA"){
		$select->where('account_status like ?','LO - RTLA');
		$select->where('relation like ?', 'Principal');
		//$select->where('submitted_co like ?', $user->username);
		$accntdetail = $accnt->fetchAll($select)->count();
	}	
	
	
	return "<font color='RED'><b>".$accntdetail."</b></font>";
	}
	

	
}
?>