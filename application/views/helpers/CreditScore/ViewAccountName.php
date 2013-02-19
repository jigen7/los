<?php
class Zend_View_Helper_CreditScore_ViewAccountName extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
	}
		
	function viewAccountName($capno){
		$table = new Model_BorrowerAccount();
		$select = $table->select();
		$select->where('capno LIKE ?',$capno);
		$row = $table->fetchRow($select);
		return $row->borrower_lname."-".$capno;
	}
	
}
?>