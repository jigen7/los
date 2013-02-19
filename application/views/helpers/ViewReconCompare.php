<?php
class Zend_View_Helper_ViewReconCompare extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewReconCompare($val1,$val2,$type){

	if($type == 'changes'){
		if($val1 == $val2){
			return "<font color='GREEN'><b>NO CHANGE</b></font>";
		}	else {
			return "<font color='RED'><b>CHANGE</b></font>";
		}
	}
	else if($type == 'ammend'){
		if($val1 != $val2){
		return "style='font-weight:bold'";
		//return "<b>".$val2."</b>";
		}
	}
	
	else if($type == 'ammendFields'){
		if($val1 != $val2){
		return "<b>".$val2."</b>";
		}
		else {
		return $val2;
			
		}
	}
	
	}//end of function

	
}
?>