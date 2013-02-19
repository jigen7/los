<?php
class Zend_View_Helper_ViewInboxSubmit extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewInboxSubmit(){
		

	
	$user = Zend_Auth::getInstance()->getIdentity();
	$role = $user->role_type;
		$statusTable = new Model_Admin_AccountStatus();

		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		
		if ($role =="MA"){
		$select->where("account_status = 'MA - S' OR account_status = 'CA - An' OR account_status = 'CA - AnD'");
		$select->where('created_by like ?', $user->username);
		$accntdetail = $accnt->fetchAll($select)->count();
		}
		
		if(login_user_role() == "LA"){
		$select = $accnt->select();
		$select->where("account_status = 'LA - ChkDoc'");
		$select->where('submitted_la like ?', $user->username);
		$accntdetail = $accnt->fetchAll($select)->count();			
		}
		
		if(login_user_role() == "AO"){
			$select = $accnt->select();
			foreach($statusTable->routeBox('almh_forbooking') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
			$select = $accnt->select();		
			$select->where(arrayString($condition));
			$select->order("submitted_mala_date DESC");
			$accntdetail = $accnt->fetchAll($select)->count();		
		}
		if(login_user_role() == "CO"){
		$select = $accnt->select();
		foreach($statusTable->routeBox('co_submitted') as $x){
			$select->orwhere('account_status like ?',$x->status);
		}
		$condition = $select->getPart(Zend_Db_Select::WHERE);
		$select = $accnt->select();	
		//$select->where("account_status = 'MA - S' OR account_status = 'CA - An' OR account_status = 'CA - AnD'");
		$select->where(arrayString($condition));
		//$select->where('submitted_co like ?', login_user());
		$select->order('submitted_co_date');
		$accntdetail = $accnt->fetchAll($select)->count();
		//$this->_helper->viewRenderer('inbox-submitted-co'); 		
		}
		
		if(login_user_role() == "MO" || login_user_role() == "ALMH"){
		$select = $accnt->select();
		$select->where("account_status = 'LA - ChkDoc' OR account_status = 'MA - PreB' OR account_status = 'LO - RTLA'");
		//$select->where('submitted_la like ?', login_user());
		$accntdetail = $accnt->fetchAll($select)->count();			
		}			
	return "<font color='RED'><b>".$accntdetail."</b></font>";
	}
	

	
}
?>