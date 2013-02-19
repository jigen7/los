<?php
class Zend_View_Helper_GetReconReprocess extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getReconReprocess($capno){
			$account = new Model_BorrowerAccount();
			$detail = $account->fetchRowModel($capno);
			return $detail->account_status2;
	}
}
	

?>