<?php
class Zend_View_Helper_GetBorrowerName extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getBorrowerName($capno){

	$table = new Model_BorrowerAccount();
	$select = $table->select();
	$select->where('capno like ?',$capno);
	$detail = $table->fetchRow($select);
	
	return $detail->borrower_fname.' '.$detail->borrower_mname.' '.$detail->borrower_lname;
	
	}
	
}
?>