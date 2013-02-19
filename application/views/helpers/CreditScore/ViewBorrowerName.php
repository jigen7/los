<?php
class Zend_View_Helper_CreditScore_ViewBorrowerName extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewBorrowerName($capno){
	
	$table = new Model_Sample_BorrowerAccount();
	$select = $table->select();
	$select->where('capno like ?',$capno);
	$row = $table->fetchRow($select);
	return $row->borrower_name;
	}
	
}
?>