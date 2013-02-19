<?php
class Zend_View_Helper_GetMonthPriceList extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getMonthPriceList($mode,$pass){
	
	//use in pricelist update return the current and next month
	if($mode == 'updatePriceList'){
		
		$currMonth = date('n');
		$currDate = date('j');
		
		if($currDate >= 1 && $currDate <=15){
		$arr = $currMonth;
		} else {
		$arr = $currMonth + 1;
		}
		return $arr;	
	}

	
	

	

}

function monthName($num){
	
	
	
	
}
	

	
}
?>