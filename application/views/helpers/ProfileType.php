<?php
class Zend_View_Helper_ProfileType extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function profileType($capno){

	$count = strlen($capno);

	if($count == 14){
	$capnocurr = substr($capno,12,1);
	}
	else if($count ==16){
	$capnocurr = substr($capno,14,1);
	}
	
	if ($capnocurr == 0){
		
	return 'Principal';
	}
	else if(($capnocurr == 1) || ($capnocurr == 2)){
	return 'Spouse';
	}
	else {
		return 'Co-Borrower';
	}
	
	}
	
}
?>