<?php
class Zend_View_Helper_AutoRouteView extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}

	function autoRouteView($capno,$score,$param){
		// temporary use in Borrower View while the user is CO
		$auto = Zend_Controller_Action_HelperBroker::getStaticHelper('AutoRoute');
		$return = $auto->direct($capno,$score);
		
		if($param == 'full'){	
			
			return $return; 
		}
		
		else if($param == 'role'){
		
		if ($return == 'Outside'){
			return 'Outside';	
		}
		else {
			return substr($return, 3);
		}
		
		}
	    
		
	}
	
}

?>