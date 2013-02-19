<?php
class Zend_View_Helper_ViewTotalIncome extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewTotalIncome($capno,$type){
		//use in craw in the itemize section....
		$total = 0.00;
		//for employment
		if($type == 'current'){
			foreach($this->_view->listEmpBus($capno,'empCurrent') as $detail){
	
				$total = $total + $detail->emp_income;
	
			
			}
			
			foreach($this->_view->listEmpBus($capno,'bus') as $detail){
				$total = $total + $detail->bus_income;
			}
			return $total;
		}
		/*
		else if($type == 'other'){
		foreach($this->_view->listEmpBus($capno,'empOther') as $detail){
				$total = $total + $detail->emp_income;
		}			
			return $c++;
		}
		*/
		else if($type == 'othermonthly'){			
			foreach($this->_view->listEmpBus($capno,'othermonthly') as $detail){
					$total = $total + $detail->amount;
				}
			return $total;
		}
		else if($type == 'othersource'){			
			foreach($this->_view->listEmpBus($capno,'othersource') as $detail){
					$total = $total + $detail->amount;
				}
			return $total;
		}

		
		
		


	}
	

	
}
?>