<?php
class Zend_View_Helper_ChkIfRecon extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function chkIfRecon($capno){
		
		//View Helper to check if the account is a recon account
		$table = new Model_BorrowerAccount();
		
		return $table->chkIfRecon($capno);
		/*	
		if(capnorecon($capno) != 0){
			
			return true;
			
		}else {
			return false;
		}
		*/
	
	}
}
	

?>