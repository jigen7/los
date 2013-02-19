<?php
class Zend_View_Helper_ViewCVCheck extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewCVCheck($col,$status){
		

		
			if($col == 'favorable'){
			return "<img src=".$this->baseUrl()."/images/check.png width=25px/>";
			}

			else if($col == 'unfavorable'){
			return "<img src=".$this->baseUrl()."/images/cross.png width=25px/>";
			}
		
			else if ($col == 'none'){

			return "None";
			}


	
	


	}
	
	 function baseUrl() {
		$front = Zend_Controller_Front::getInstance();
		$url = $front->getBaseUrl();
		return $url;
	}
}


?>