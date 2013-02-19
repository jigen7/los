<?php
class Zend_View_Helper_ChkDigiPassStatus extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function chkDigiPassStatus($username){
	$users = new Model_Users();
	
		if($users->getdigipasswordstatus('jrpimentel') === true){
			return true;
		}else{
			return false;
		}

	
	}
}
?>
