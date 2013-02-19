<?php
class Zend_View_Helper_ViewInboxPending extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewInboxPending(){

	$user = Zend_Auth::getInstance()->getIdentity();
	$role = $user->role_type;
	
		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
	
	if ($role =="MA"){
		$select->where("account_status = 'MA - E' OR account_status = 'MA - EN' OR account_status = 'NA'");
		$select->where('created_by like ?', $user->username);
		$select->where('relation like ?', 'Principal');
		$accntdetail = $accnt->fetchAll($select)->count();
	}
	if ($role =="CA"){
		$select->where("account_status = 'MA - S' OR account_status = 'CA - An' OR account_status = 'CA - AnD'");
		$select->where('relation like ?', 'Principal');	
		$select->where('submitted_ca like ?',$user->username);
		$accntdetail = $accnt->fetchAll($select)->count();
	}
	if ($role =="CO"){
		$select->where("account_status = 'CA - ReAp' OR account_status = 'CA - ReR' OR account_status = 'CO - REV' OR account_status = 'CO - REVD' OR account_status = 'CA - OR' OR account_status = 'CA - NA' OR account_status = 'CA - Cancel'");
		$select->where('relation like ?', 'Principal');
		$select->where('submitted_co like ?', $user->username);
		$accntdetail = $accnt->fetchAll($select)->count();
	}	
	if ($role =="LA"){
		//$select->where("account_status = 'CO - Ap' OR account_status = 'CSH - Ap' OR account_status = 'CMGH - Ap' OR account_status = 'PRES - Ap'");
		$select->where("account_status = 'MA - PreB' OR account_status = 'LA - Recall'");		
		$select->where('relation like ?', 'Principal')->order("id ASC");
		//$select->where('submitted_la like ?', $user->username);
		
		$accntdetail = $accnt->fetchAll($select)->count();
	}	
	if ($role =="LO"){
		//$select->where("account_status = 'CO - Ap' OR account_status = 'CSH - Ap' OR account_status = 'CMGH - Ap' OR account_status = 'PRES - Ap'");
		$select->where("account_status = 'LA - ChkDoc'");		
		$select->where('relation like ?', 'Principal')->order("id ASC");
		$accntdetail = $accnt->fetchAll($select)->count();
	}	
	
	
	
	return "<font color='RED'><b>".$accntdetail."</b></font>";
	}
	

	
}
?>