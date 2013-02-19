<?php
class Zend_View_Helper_ViewInboxInProcess extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewInboxInProcess(){

		$accnt = new Model_BorrowerAccount();
		$statusTable = new Model_Admin_AccountStatus();
			$select = $accnt->select();
			foreach($statusTable->routeBox('inprocess_autorouting') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
			$select = $accnt->select();
			$select->order("date_decided DESC");	
		$select->where('relation like ?', 'Principal');
		$select->where(arrayString($condition));
		$accntdetail = $accnt->fetchAll($select)->count();

	return "<font color='RED'><b>".$accntdetail."</b></font>";
	}
	

	
}
?>