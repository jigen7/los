<?php
class Zend_View_Helper_CountProfile extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function countProfile($capno,$relation){
	// View Hleper to count the co-maker and coborrower 
	$table = new Model_BorrowerAccount();
	
	if($relation == 'comaker'){
		
		$numComaker = $table->getComaker($capno);
		
		if($numComaker){
			return "with Co-Maker";
		} 
		
	}
	
	else if($relation == 'coborrower'){
		
		$numCoborrower = $table->countCoborrower($capno);
		
		if($numCoborrower > 0){
			
			return "with ".$numCoborrower." Coborrower/s";
		}
	
	}
		
		
	
	}
}
	

?>