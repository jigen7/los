<?php
class Zend_View_Helper_ViewReconFormat extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewReconFormat($val,$field){

	if($field == 'selling_price' || $field == 'amountloan'){		
		return 'P '.number_format($val,2);
	}
	else if($field == 'downpayment_percent' || $field == 'rate'
	){		
		return number_format($val,2).' %';
	}
	else {
		return $val;
	}
	}//end of function

	
}
?>