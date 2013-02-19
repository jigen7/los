<?php
class Zend_View_Helper_GetCurrentStatus extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getCurrentStatus($capno){
	

	$borrowerTable = new Model_BorrowerAccount();
	$detail = $borrowerTable->fetchRowModel($capno);
	
	if($detail->account_status){
	return $this->_view->viewAccountStatus($detail->account_status);
	}else {
	return '';
	}
	
		
		

	}
}
	

?>