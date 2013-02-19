<?php
class Zend_View_Helper_ViewCarHistory extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewCarHistory($seq){

	if($seq == '0'){
	return 'Ok';	
	}
	else if($seq == '1'){
	return 'Not Ok';	
	}
	else if($seq == ''){
	return '';	
	}


	}
	
}
?>