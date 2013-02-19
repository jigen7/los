<?php

class Zend_Controller_Action_Helper_ComakerModule extends
                Zend_Controller_Action_Helper_Abstract
{
    function direct($capno)
    {
    	/**
    	 * Paolo Marco M. Manarang 
    	 * March 17,2010 
    	 * Helper to copy and update the Loan and Unit Detail of a Comaker
    	 * When the Profile of the Main borrower is change or saved 
    	 * 
    	**/
		$accnt = new Model_BorrowerAccount();
		
		// check if the main borrower has a comaker
		if($accnt->getComaker($capno)){
			
			// Get the Main Borrower Detail so we can copy it to the comaker
			$mainDetail = $accnt->fetchRowModel($capno);
			$comakerCapno = $accnt->getComaker($capno);

			$data = array(
			'loan_purpose'=>$mainDetail->loan_purpose,
			'source_application'=>$mainDetail->source_application,
			'submitted_ca'=>$mainDetail->submitted_ca,
			// Unit Detail 
			'dealer' => $mainDetail->dealer,
			'dealer_agent' => $mainDetail->dealer_agent,
			'branch_refferror' =>$mainDetail->branch_refferror,
			'dealer_coordinator'=> $mainDetail->dealer_coordinator,
			'submitted_mo'=>$mainDetail->submitted_mo,
			'branch' => $mainDetail->branch,
			'veh_brand' => $mainDetail->veh_brand,
			'veh_status' => $mainDetail->veh_status,
			'veh_type' => $mainDetail->veh_type,
			'veh_unit' => $mainDetail->veh_unit,
			'veh_yrmodel' =>$mainDetail->veh_yrmodel,
			'veh_age'=>$mainDetail->veh_age,
			'veh_use'=>$mainDetail->veh_use,
			'appraisal_value'=>$mainDetail->appraisal_value,
			'car_history' => $mainDetail->car_history,
			'appraisal_date'=> $mainDetail->appraisal_date,
			'appraised_by'=> $mainDetail->appraised_by,
			'appraisal_waver_request'=> $mainDetail->appraisal_waver_request,
			// Loan Tabs
			'amountloan'=>$mainDetail->amountloan,
			'selling_price'=>$mainDetail->selling_price,
			'lcp'=>$mainDetail->lcp,
			'veh_discount'=>$mainDetail->veh_discount,
			'downpayment_actual'=>$mainDetail->downpayment_actual,
			'downpayment_percent'=>$mainDetail->downpayment_percent,
			'monthly_amortization'=>$mainDetail->monthly_amortization,
			'gmi_ratio'=>re_gmi_ratio($comakerCapno),
			'loanterm'=>$mainDetail->loanterm,
			'addon_rate'=>$mainDetail->addon_rate,
			'rate'=>$mainDetail->rate,
			'effective_yield'=>$mainDetail->effective_yield,
			'dealer_incentive'=>$mainDetail->dealer_incentive,
			'dealer_incentive2'=>$mainDetail->dealer_incentive2,
			);
			
			$where = "capno like '$comakerCapno'";
			$accnt->update($data, $where);
			
		}
	}
	
	function comakerRts($capno){
		// RTS function for comaker
		
		$accnt = new Model_BorrowerAccount();
		
		if($accnt->getComaker($capno)){
			$comakerCapno = $accnt->getComaker($capno);
			if(login_user_role() == 'CO'){
				$status = 'CA - CMK';
			}
			else if(login_user_role() == 'CA'){
				$status = 'MA - CMK';
			}
			
			$data = array(
			'comaker_accnt_status'=>$status,			
			);
			$where = "capno like '$comakerCapno'";
			$accnt->update($data, $where);
			
			
		}
		
	}
	
}


