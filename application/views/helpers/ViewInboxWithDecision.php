<?php
class Zend_View_Helper_ViewInboxWithDecision extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewInboxWithDecision($status){
	
		$user = Zend_Auth::getInstance()->getIdentity();
	
		$accnt = new Model_BorrowerAccount();
		$statusTable = new Model_Admin_AccountStatus();

		$select = $accnt->select();

		if($status == "approved"){
			
			$select = $accnt->select();
			foreach($statusTable->routeBox('approve') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
	
		}
		else if($status == 'rejected'){
			$select = $accnt->select();
			foreach($statusTable->routeBox('reject') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);

		}
		
		$select = $accnt->select();
		if(login_user_role() == 'MA'){
		//$select->where('created_by like ?', $user->username);
		}
		$select->order("date_decided DESC");	
			$startdate = date('Y-m-d',strtotime('-60 day'));
			$enddate = date('Y-m-d',strtotime('+1 day'));
			$select->where("date_decided between '$startdate' AND '$enddate'");
			$select->order("date_decided DESC");	
		$select->where('relation like ?', 'Principal');
		$select->where($statusTable->arrayString($condition));
		$accntdetail = $accnt->fetchAll($select)->count();
	
	return "<font color='RED'><b>".$accntdetail."</b></font>";

	}
	

	
}
?>