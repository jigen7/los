<?php
class Zend_View_Helper_GetEmpbus extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getEmpBus($capno){

	$table = new Model_BorrowerAccount();
	
	return $table->getEmpBusStatus($capno);
	
	}
}
	

?>