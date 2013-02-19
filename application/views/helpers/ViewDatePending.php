<?php
class Zend_View_Helper_ViewDatePending
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
    
	
	function viewDatePending($capno,$process)
    {
	$user = Zend_Auth::getInstance()->getIdentity();
	$role = $user->role_type;

		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		
		if (($role =="MA") && ($process == "received")){
		$select->where('capno like ?',$capno);
		$select->where('relation ?', 'Principal');
		$accntdetail = $accnt->fetchRow($select);
		return $accntdetail->application_date;
		}



    }
}