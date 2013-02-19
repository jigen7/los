<?php
class Zend_View_Helper_crawApprovalDate extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function crawApprovalDate($type,$dateString){

	if($type == 0){
		return "";
	}
	else if($type ==1){
		return date('m-d-Y',strtotime($dateString));
	}
	else if($type == 2){
		return date('m-d-Y',strtotime($dateString));
	}
	else if($type ==3){
		return $dateString;
		}

			
	}
}
	

?>