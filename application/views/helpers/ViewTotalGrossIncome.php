<?php
class Zend_View_Helper_ViewTotalGrossIncome extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewTotalGrossIncome($capno){
		//use in craw
		$total = 0.00;
		//for employment
		foreach($this->_view->listEmpBus($capno,'emp') as $detail){
			if($detail->employer == 'Remittance'){
			$total = $total + (($detail->emp_income * $detail->emp_multiplier) / ($detail->emp_percentage/100));
			}
			else{
			$total = $total + ($detail->emp_income * $detail->emp_multiplier);
			}
		}
		
		foreach($this->_view->listEmpBus($capno,'bus') as $detail){
	
			$total = $total + ($detail->bus_income * $detail->bus_multiplier);

		}

return $total;
	}
	

	
}
?>