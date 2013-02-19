<?php
class Zend_View_Helper_ViewReconEffect extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function ViewReconEffect($arrayFields,$nameRow){
	$array =  Array();
	
	if(in_array("dealer", $arrayFields)){
	$array[] = 'veh_brand';
	$array[] = 'veh_unit';
	$array[] = 'selling_price';
	$array[] = 'lcp';
	$array[] = 'downpayment_percent';
	$array[] = 'downpayment_actual';
	$array[] = 'amountloan';
	$array[] = 'monthly_amortization';
	$array[] = 'gmi_ratio';		
	}
	
	if(in_array("loanterm", $arrayFields)){
	$array[] = 'amountloan';
	$array[] = 'monthly_amortization';
	$array[] = 'gmi_ratio';		
	}
	
	if(in_array("veh_brand", $arrayFields)){
	$array[] = 'dealer';	
	$array[] = 'veh_unit';
	$array[] = 'selling_price';
	$array[] = 'lcp';
	$array[] = 'downpayment_percent';
	$array[] = 'downpayment_actual';
	$array[] = 'amountloan';
	$array[] = 'monthly_amortization';
	$array[] = 'gmi_ratio';			
	}
	
	if(in_array("veh_unit", $arrayFields)){
	$array[] = 'dealer';	
	$array[] = 'veh_brand';
	$array[] = 'selling_price';
	$array[] = 'lcp';
	$array[] = 'downpayment_percent';
	$array[] = 'downpayment_actual';
	$array[] = 'amountloan';
	$array[] = 'monthly_amortization';
	$array[] = 'gmi_ratio';			
	}
	
	if(in_array("downpayment_percent", $arrayFields)){
	$array[] = 'amountloan';
	$array[] = 'monthly_amortization';
	$array[] = 'gmi_ratio';	
	}
	
	if(in_array($nameRow, $array)){
	return 	"style='font-style:italic;font-family: impact;'";	
	}
	
		
	
	
	
	}//end of function

	
}
?>