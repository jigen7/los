<?php
class Zend_View_Helper_ReturnAccounts extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){$this->_view = $view;}

	function returnAccounts($route,$process,$username,$status,$from,$to){
	
	
	$table = new Model_Report_Accounts();
	
	if($route == 'crecom'){
		$grp = new Model_AutoRouting_AccountsCrecom();
		return $grp->getAccounts($process,$username,$status,$from,$to);
		
	}else if($route == 'subcrecom'){
		
		
		
	}else{
	return $table->getAccounts($process,$username,$status,$from,$to);
	}
	
	}

	





}
?>