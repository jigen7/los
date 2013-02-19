<?php
class Zend_View_Helper_ViewAccountStatusCmk extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewAccountStatusCmk($status){

	if($status == "MA - CMK"){
	
	return "MA Encodes Info Co-Maker";
		
	}
	else if($status == "MA - S - CMK"){
	return "MA Submit Co-Maker";
	}
	else if($status == "CA - CMK"){
	return "CA Encodes Co-Maker";
	}
	else if($status == "CA - S - CMK"){
	return "CA Submit Co-Maker";
	}
	
	
	else { 
	return "";
	}

	}
}
	

?>