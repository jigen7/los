<?php
class Zend_View_Helper_ViewModelCheck extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewModelCheck($col){
		
		if ($col == '1'){
		
			return "<img src=".$this->baseUrl()."/images/check.png width=25px/>";

			}


	
	


	}
	
	 function baseUrl() {
		$front = Zend_Controller_Front::getInstance();
		$url = $front->getBaseUrl();
		return $url;
	}
}


?>