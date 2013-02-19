<?php
class Zend_View_Helper_ViewTotalEmpYrs extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewTotalEmpYrs($capno){
		
			$emp = new Model_BorrowerEmployment();
			$select = $emp->select();
			$select->where('capno like ?',$capno);
			$empdetail = $emp->fetchAll($select);
			$lastYr=100;
			$num = 0;
			if(!is_null($empdetail)){
			$highemp = getHighEmp($capno);
				if($highemp){
			$select = $emp->select();
			$select->where('id = ?',$highemp);
			$emphigh = $emp->fetchRow($select);
						
			$modelindustry = $emphigh->emp_industry;
			$modeldate = $emphigh->emp_date;
			
			
			foreach($empdetail as $detail){

			if ($detail->emp_industry == $modelindustry){
				//Only Add the same Industry 
				if ($detail->employer == 'Previous'){
					//check if its a previous date but also check if the # of days is a 1 year interval
					//echo $this->_view->daysDifference($modeldate, $detail->date_resigned);
					if($this->_view->daysDifference($modeldate, $detail->date_resigned) < 370){
						$num = $num+$detail->emp_yrs+($detail->emp_months / 12);

					}
					else{
					// Dont Add						
					}
				}
				else if($detail->employer == 'Current' || $detail->employer == 'Remittance'){
					//$num = $num+$detail->emp_yrs+($detail->emp_months / 12);
				
					$currYr = $detail->emp_yrs+($detail->emp_months / 12);
					
					if($currYr <= $lastYr){
						$lastYr = $currYr;
						$num = $lastYr;
					}
				}
			
			} 

			else {
				// if different model industry
				
				if($detail->employer == 'Current' || $detail->employer == 'Remittance'){
					//$num = $num+$detail->emp_yrs+($detail->emp_months / 12);
				
					$currYr = $detail->emp_yrs+($detail->emp_months / 12);
					
					if($currYr <= $lastYr){
						$lastYr = $currYr;
						$num = $lastYr;
					}
				}
				
			}// end of different model industry
			//Check 
			}}
			
			}// end of is null empdetail 
			
		return number_format($num, 2, '.', '');;
		
	}
	

	
}
function getHighEmp($capno){
			
			//Determine the Highest Salary among the employments and get 
			//its Emp ID to be use in the Deviation
			
			$emp = new Model_BorrowerEmployment();
			$select = $emp->select(); //employer = 'Remittance'
			$select->where('capno like ?',$capno)->where("employer = 'Current' or employer = 'Remittance'" );
			$empdetail = $emp->fetchAll($select);
			
			if(!is_null($empdetail)){
			foreach($empdetail as $detail){
				
		    if ($detail->emp_income > $sum){
		    	$sum = $detail->emp_income;
			    $emp_id = $detail->id;
				}
			}
			return $emp_id;
			} else { return NULL; }
	
			
		

}
?>