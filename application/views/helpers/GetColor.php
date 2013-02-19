<?php
class Zend_View_Helper_GetColor extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getColor($status){

	//use in autorouting
	
	if(strpos($status,'- ReAp')){	
	return '#cf3';
	}
	else if(strpos($status,'- P')){	
	return '#cf3';
	}
	else if(strpos($status,'- Ap')){	
	return '#6c3';
	}
	else if(strpos($status,'- R')){	
	return '#f33';
	}			
}
}
	

?>