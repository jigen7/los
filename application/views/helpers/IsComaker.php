<?php
class Zend_View_Helper_IsComaker extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}

	function isComaker($capno){
		
		$table = new Model_BorrowerAccount();
		
		return $table->isComaker($capno);
		
	}
	
}






?>