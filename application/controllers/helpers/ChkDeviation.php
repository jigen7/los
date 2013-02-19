<?php

class Zend_Controller_Action_Helper_ChkDeviation extends
                Zend_Controller_Action_Helper_Abstract
{
    function direct($capno)
    {

		$user = Zend_Auth::getInstance()->getIdentity();
		$role = $user->role_type;
		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		$select->where('capno like ?',$capno);
		$accntdetail = $accnt->fetchRow($select);

		//Vehicle Age
		/*
		if($accntdetail->veh_age > 3){
		$dev_veh_age = "Vehicle age at most 3 years";
		} $val_veh_age=$accntdetail->veh_age;
		*/
		//Loan Amount High
		if($accntdetail->loanterm > 60){
		$dev_loantermhigh = "Loan Term exceeds acceptable max term 60 mos";
		} $val_loantermhigh =$accntdetail->loanterm;

		//Loan Amount Low
		if($accntdetail->loanterm < 12){
		$dev_loantermlow = "Loan Term exceeds acceptable min term 12 mos";
		} $val_loantermlow =$accntdetail->loanterm;
		
		
		//GMI Ratio
		if($accntdetail->gmi_ratio > 30 ){
		$dev_gmi = "GMI Ratio must not be > 30%";
		} $val_gmi=$accntdetail->gmi_ratio ;
		
		//Loan Purpose 
		if($accntdetail->loan_purpose == 'Others' || $accntdetail->loan_purpose == 'Restructure') {
		$dev_loan_purpose = "Loan Purpose is not within Guideline";	
		}$val_loan_purpose = $accntdetail->loan_purpose;
		// End of Loan Purpose Deviation
		
		//Second Hand Deviation
		
		if($accntdetail->veh_status == 2 || $accntdetail->veh_status == 3 || $accntdetail->veh_status == 4){
		//if the car is second hand
			
			if($accntdetail->veh_age > 4){
			$dev_veh_yr = "2nd hand unit should not be more than 4 years old";				
			} $val_veh_yr = $accntdetail->veh_status;
			
			if($accntdetail->loanterm > reqVehTerm($accntdetail->veh_age)){
			$dev_veh_tenor ="2nd hand tenor should not be more than ".(reqVehTerm($accntdetail->veh_age) / 12)." year/s.";	
			} $val_veh_tenor = $accntdetail->loanterm;
				
			if($accntdetail->car_history == '1'){
			//if car history is not ok	
			  $dev_veh_car_history ="2nd hand must have a favorable car history";				
			} $val_veh_car_history = $accntdetail->car_history;
			
			if($accntdetail->veh_age <= 1 && ($accntdetail->downpayment_percent < 20)){
			$dev_downpayment = "1 yr Pre-Owned: Downpayment must be at least 20%";
			} 
			else if ($accntdetail->downpayment_percent < 30 && ($accntdetail->veh_age == 2)){
			$dev_downpayment = "2 yrs Pre-Owned: Downpayment must be at least 30%";
			}
			else if ($accntdetail->downpayment_percent < 40 && ($accntdetail->veh_age == 3 || $accntdetail->veh_age == 4)){
			$dev_downpayment = "3-4 yrs Pre-Owned: Downpayment must be at least 40%";
			}
			$val_downpayment = $accntdetail->downpayment_percent;
		}
		else if ($accntdetail->veh_status == 1){
			// If the car is Brand New 		
			//Downpayment
			if($accntdetail->downpayment_percent < 20){
			//$counter+= 1;	
			$dev_downpayment = "Brand New: Downpayment must be at least 20%";
			} $val_downpayment = $accntdetail->downpayment_percent;
			
		}
		// Veh Use
		if($accntdetail->veh_use == 4 ){
			 if($accntdetail->downpayment_percent < 40 ){
				$dev_veh_use_taxi_downpayment = "Taxi Application: Downpayment must be at least 40%";
				$val_veh_use_taxi_downpayment = $accntdetail->downpayment_percent;
			 }
			 if($accntdetail->loanterm > 36 ){
				$dev_veh_use_taxi_term = "Taxi Application: tenor should not be more than 36mos";
				$val_veh_use_taxi_term = $accntdetail->loanterm;
				 }
		} 
		
		//Amout Loan
		if($accntdetail->amountloan < 250000 ){
		$dev_loan_amount = "Minimum Loanable Value is P250,000";
		} 
		else if($accntdetail->amountloan > 4000000){
		$dev_loan_amount = "Maximum Loanable Value is P4,000,000";	
		} $val_loan_amount=$accntdetail->amountloan ;
		
		//Brand New  Selling Price vs LCP
		if($accntdetail->veh_status == 1){
		if($accntdetail->selling_price > $accntdetail->lcp){
		$dev_sell_lcp = "Selling Price is greater than the declared LCP";
		} } $val_sell_lcp =	$accntdetail->selling_price;
		//Pre Owned Selling Price vs Appraisal Value
		if($accntdetail->veh_status == 2){
		if($accntdetail->selling_price < $accntdetail->appraisal_value){
		$dev_sell_appraisal = "Appraisal Value is greater than the Selling Price";
		} } $val_sell_appraisal = $accntdetail->selling_price;
		
		//$countSpCo = countSpouse($accntdetail->capno) + countcoborrower($accntdetail->capno);
		$countSpCo = countSpouse($accntdetail->capno);
		
		if((($countSpCo > 0) || ($accntdetail->civilstatus == '2') || ($accntdetail->civilstatus == '3') || ($accntdetail->civilstatus == '4') || ($accntdetail->civilstatus == '5')) && (totalcombinedincome($capno) < 50000)){
		$dev_totalcombine = "For Married Total Combined Income should be at least P50,000";
		}
		else if (($countSpCo == 0) && (totalcombinedincome($capno) < 30000)){
		$dev_totalcombine = "For Single Total Combined Income should be at least P30,000";
		

		
		} 
		
		//No Source of Income
		/*
		if($accntdetail->total_income == 0){
		$dev_total_income = "No Source of Income";
		$val_total_income = $accntdetail->total_income;
		} */
		if(totalcombinedincomeSpouse($capno) == 0){
		$dev_total_income = "No Source of Income";
		$val_total_income = $accntdetail->total_income;
		}
		

		$val_totalcombine = totalcombinedincome($capno);
	
////////////////////////////////////////////////////////////////	
		//Borrower Deviation
		
		// Borrower Age
		$loanyr = $accntdetail->loanterm / 12;
		if($accntdetail->age < 21){
		$dev_borrower_age = "Age must be at least 21 years old";
		$val_borrower_age = $accntdetail->age;
		} 
		else if(($accntdetail->age + $loanyr) > 65){
		$dev_borrower_age = "Age should not be more than 65 years old upon loan maturity";
		$val_borrower_age = $accntdetail->age + $loanyr;
		} 
		

		



		//Residency Years
		if(($accntdetail->residence_yrs + ($accntdetail->residence_months / 12)) < 2){
		$dev_residence_yrs = "Residence Years should be at least 2 years";
		} $val_residence_yrs = $accntdetail->residence_yrs + ($accntdetail->residence_months / 12);

		// Employment
			$emp = new Model_BorrowerEmployment();
			$select = $emp->select();
			$select->where("capno = '$capno' and employer = 'Current'" );
			//$select->where('employer like ?','Current');
			$employmentdetail = $emp->fetchAll($select);
			if(!is_null($employmentdetail)){
			foreach($employmentdetail as $empdetail){
				
	
			$emp_pos = getEmpPos($empdetail->emp_pos);
			$emp_status = getEmpStatus($empdetail->emp_status);
			
			if ($empdetail->employer == "Current"){
				if(totalempyrs($capno) <2){
					//No Previous Job was found
					$dev_employment_yrs ="Total Employment Years is less than 2 years";
				}
				}
				else {
				//Reserve 
				}$val_employment_yrs = totalempyrs($capno);

			
			if($emp_status != "Permanent"){

				$dev_employment_status = "Employment Status must be permanent / regular";
			} 	$val_employment_status = $emp_status;
			}}


		//Business
			$bus = new Model_BorrowerBusiness();
			$select = $bus->select();
			$select->where('capno like ?',$capno);
			$businessdetail = $bus->fetchAll($select);
		if(!is_null($businessdetail)){
			foreach($businessdetail as $busdetail){
			if($busdetail->bus_yrs < 2){
			$dev_business_yrs = "Years in Business must be at least 2 years";
			$val_business_yrs = $busdetail->bus_yrs;
			} 
			}
		}
			
		

		
		//Citizenship
		if(getCitizenship($accntdetail->citizenship) != "Filipino"){
		
			$dev_citizenship1 = "Borrower Citizenship is not a Filipino";
		
		} $val_citizenship1 = $accntdetail->citizenship;

		
		//Check CV and CI Table
		
		if($role == 'CA' || $role == 'CO'){
			
			$cv = new Model_BorrowerCv();
			$select = $cv->select();
			$select->where('capno like ?',$capno);
			$cvdetail = $cv->fetchRow($select);
			
			if($cvdetail->nfis == 'Positive'){
				$dev_nfis = "NFIS Positive";
			}	$val_nfis = $cvdetail->nfis;
			
			if($cvdetail->date_nfis){
			$cvDays = daysDifference(date('Y-m-d'),date('Y-m-d',strtotime($cvdetail->date_nfis)));
			if($cvDays > 30){
				$dev_nfis_check = "NFIS check is more than 1 month";
			}	$val_nfis_check = $cvDays.' '.$cvdetail->date_nfis;
			}
			
			if($cvdetail->cmap == 'Positive'){
				$dev_cmap = "CMAP Positive";
			}	$val_cmap = $cvdetail->cmap;
			
			
			$ci = new Model_BorrowerCi();
			$select = $ci->select();
			$select->where('capno like ?',$capno);
			$ciDetail = $ci->fetchRow($select);
			
			if($ciDetail->empver_ci2 == 'Unfavorable' || 
			$ciDetail->busver_ci2 == 'Unfavorable' || 
			$ciDetail->trdchk_ci2 == 'Unfavorable' ||
			$ciDetail->backgrd_ci2 == 'Unfavorable' ||
		    $ciDetail->residence_ci2 == 'Unfavorable' || 
			$ciDetail->income_ci == 'Unfavorable' ||
			$ciDetail->ci_appraisal_report == 'Unfavorable' ||
			$cvdetail->empver2 == 'Unfavorable' ||
			$cvdetail->busver2 == 'Unfavorable' ||
			$cvdetail->trdchk2 == 'Unfavorable' ||
			$cvdetail->backgrd == 'Unfavorable' ||
			$cvdetail->bankref == 'Unfavorable' ||
			$cvdetail->creditchk == 'Unfavorable' ||
			$cvdetail->pastdealings == 'Unfavorable' ||
			$cvdetail->income == 'Unfavorable'
			){
				$dev_ci_favorable = "Unfavorable CI Report";
				$val_ci_favorable = 'Unfavorable';				
			}else {
				$val_ci_favorable = 'Favorable';			
			}
			
			if($ciDetail->date_ci){
			$ciDays = daysDifference(date('Y-m-d'),date('Y-m-d',strtotime($ciDetail->date_ci)));
			if($ciDays > 90){
				$dev_ci_check = "CI Report is more than 3 months";
			}	$val_ci_check = $ciDays.' '.$ciDetail->date_ci;
			}
		}
		
		
		//End of Citizeship
		//End of Borrower Deviations
		
///////////////////////////////////////////////////////////		
		//Coborrower Deviations
		//Array Counter 
		$coage = array();
		$cocitizenship = array();
		$coresidence_yrs = array();
		$coemployment_yrs = array();
		$coemployment_status = array();
		$cobusiness_yrs = array();
		$confis = array();
		$confis_check = array();
		$coci_favorable = array();
		$coci_check = array();
		$cototalincome = array();
		$cocmap = array();
		
		
		$sql = $accnt->select()
	    ->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where('relation LIKE ?','Coborrower');
		$coDetail = $accnt->fetchAll($sql);
		$counter = 1;
		foreach($coDetail as $detail){
			
		if(($detail->age < 21) || ($detail->age+$loanyr > 65)){
		$dev_coborrower_age = "Age must be at least 21 - 65 Coborrower: ";
		$val_coborrower_age = $detail->age;
		$coage[] = $counter;
		}
		
		if(getCitizenship($detail->citizenship) != 'Filipino'){
		//Coborrower Citizenship	
		$dev_coCitizenship = "Citizenship is not a Filipino Coborrower: ";
		$val_coCitizenship = $detail->citizenship;
		$cocitizenship[] = $counter;
		}
		
		if($detail->residence_yrs < 2){
		$dev_coresidence_yrs = "Residence Years should be at least 2 years Coborrower: ";
		$val_coresidence_yrs = $detail->residence_yrs;
		$coresidence_yrs[] = $counter;	
		}
		
		//No Source of Income
		if($detail->coborrower_extend_relation != 'Spouse'){
		if($detail->total_income == 0){
		$dev_cototal_income = "No Source of Income CoBorrower: ";
		$val_cototal_income = $detail->total_income;
		$cototalincome[] = $counter;
		}}
		

			
		//Start of Coborrower Employment Check

			$emp = new Model_BorrowerEmployment();
			$select = $emp->select();
			$select->where('capno  like ?',$detail->capno);
			$select->where('employer  like ?','Current');
			$empcodetail = $emp->fetchAll($select);
			if(!is_null($empcodetail)){
			foreach($empcodetail as $empdetail){
			$emp_pos = getEmpPos($empdetail->emp_pos);
			$emp_status = getEmpStatus($empdetail->emp_status);
			
			if ($empdetail->employer == "Current")
				{
				if(totalempyrs($detail->capno) <2){
				$dev_coemployment_yrs ="Total Employment Years is less than 2 years Coborrower: ";
				$coemployment_yrs[] = $counter;	}
				
				else {
				//Reserve 
				}$val_coemployment_yrs = totalempyrs($detail->capno);
				
				}
			//($emp_pos != "Managerial") && 
			if($emp_status != "Permanent"){
				$dev_coemployment_status = "Employment Status must be permanent / regular Coborrower: ";
				$val_coemployment_status = $emp_status;
				$coemployment_status[] = $counter;
			}

			}}
		//End of Coborrower Employment Deviation
		//Co Borrower Business 


			$bus = new Model_BorrowerBusiness();
			$select = $bus->select();
			$select->where('capno = ?',$detail->capno);
			$buscodetail = $bus->fetchAll($select);
		if(!is_null($buscodetail)){	
			foreach($buscodetail as $busdetail){
			if($busdetail->bus_yrs < 2){
			$dev_cobusiness_yrs = "Years in Business must be at least 2 years Coborrower: ";
			$val_cobusiness_yrs = $busdetail->bus_yrs;
			$cobusiness_yrs[] = $counter;	
			}
			}}

		//End of Co Borrower Business  


		
		//Start of Coborrower CA
		if($role == 'CA' || $role == 'CO'){
			
			$cv = new Model_BorrowerCv();
			$select = $cv->select();
			$select->where('capno like ?',$detail->capno);
			$cvdetail = $cv->fetchRow($select);
			
			if($cvdetail->nfis == 'Positive'){
				$dev_confis = "Coborower NFIS Positive Coborrower: ";
				$confis[] = $counter;
			}	$val_confis = $cvdetail->nfis;
			
			if($cvdetail->cmap == 'Positive'){
				$dev_cocmap = "Coborower CMAP Positive Coborrower: ";
				$cocmap[] = $counter;
			}	$val_cocmap = $cvdetail->cmap;
			
			
			if($cvdetail->date_nfis){
			$cvDays = daysDifference(date('Y-m-d'),date('Y-m-d',strtotime($cvdetail->date_nfis)));
			if($cvDays > 30){
				$dev_confis_check = "NFIS check is more than 1 month Coborrower: ";
				$confis_check[] = $counter;
			}	$val_confis_check = $cvDays.' '.$cvdetail->date_nfis;
			}
			
			$ci = new Model_BorrowerCi();
			$select = $ci->select();
			$select->where('capno like ?',$detail->capno);
			$ciDetail = $ci->fetchRow($select);
			
			if($ciDetail->empver_ci2 == 'Unfavorable' || 
			$ciDetail->busver_ci2 == 'Unfavorable' || 
			$ciDetail->trdchk_ci2 == 'Unfavorable' ||
			$ciDetail->backgrd_ci2 == 'Unfavorable' ||
		    $ciDetail->residence_ci2 == 'Unfavorable' || 
			$ciDetail->income_ci == 'Unfavorable' ||
			$cvdetail->empver2 == 'Unfavorable' ||
			$cvdetail->busver2 == 'Unfavorable' ||
			$cvdetail->trdchk2 == 'Unfavorable' ||
			$cvdetail->backgrd == 'Unfavorable' ||
			$cvdetail->bankref == 'Unfavorable' ||
			$cvdetail->creditchk == 'Unfavorable' ||
			$cvdetail->pastdealings == 'Unfavorable' ||
			$cvdetail->income == 'Unfavorable'
			){
				$dev_coci_favorable = "Unfavorable CI Report Coborrower: ";
				$coci_favorable[] = $counter;
				$val_coci_favorable = 'Unfavorable';				
			}else {
				$val_coci_favorable = 'Favorable';			
			}
			
			if($ciDetail->date_ci){
			$ciDays = daysDifference(date('Y-m-d'),date('Y-m-d',strtotime($ciDetail->date_ci)));
			if($ciDays > 90){
				$dev_coci_check = "CI Report is more than 3 months Coborrower: ";
				$coci_check[] = $counter;
			}	$val_coci_check = $ciDays.' '.$ciDetail->date_ci;
			}
		} // End of Coborrower CA
		$counter++;
		}//End of For Each  Coborrower Deviations
////////////////////////////////////////////////////////////////////		
		//Spouse Deviation
		//Array
		$spage = array();
		$spcitizenship = array();
		$spresidence_yrs = array();
		$spemployment_yrs = array();
		$spemployment_status = array();
		$spbusiness_yrs = array();
		$spnfis = array();
		$spcmap = array();
		$spnfis_check = array();
		$spci_favorable = array();
		$spci_check = array();
		$sptotalincome = array();
		
		
		$sql = $accnt->select()
	    ->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where('relation LIKE ?','Spouse');
		$spoDetail = $accnt->fetchAll($sql);
		
		$counterx = 1;
		foreach($spoDetail as $detail){
			
		//if ($detail->total_income == ''){
		if(($detail->age < 21) || ($detail->age+$loanyr > 65)){
		$dev_spoborrower_age = "Age must be at least 21 - 65 Spouse: ";
		$val_spoborrower_age = $detail->age;
		$spage[] = $counterx;
		}
		
		if($detail->residence_yrs < 2){
		$dev_sporesidence_yrs = "Residence Years should be at least 2 years Spouse: ";
		$val_sporesidence_yrs = $detail->residence_yrs;
		$spresidence_yrs[] = $counterx;	
		}
		
		/*
		//No Source of Income
		if($detail->total_income == 0){
		$dev_spototal_income = "No Source of Income Spouse: ";
		$val_spototal_income = $detail->total_income;
		$sptotalincome[] = $counterx;
		}
		*/
	
		if(getCitizenship($detail->citizenship) != 'Filipino'){
		//Spouse Citizenship	
		$dev_spoCitizenship = "Citizenship is not a Filipino Spouse: ";
		$val_spoCitizenship = $detail->citizenship;
		$spcitizenship[] = $counterx;
		}
		
			$emp = new Model_BorrowerEmployment();
			$select = $emp->select();
			$select->where('capno  like ?',$detail->capno);
			$select->where('employer  like ?','Current');
			$empspdetail = $emp->fetchAll($select);
			if(!is_null($empspdetail)){
			foreach($empspdetail as $empdetail){


			$emp_pos = getEmpPos($empdetail->emp_pos);
			$emp_status = getEmpStatus($empdetail->emp_status);
			if ($empdetail->employer == "Current"){
				if(totalempyrs($detail->capno) <2){
				$dev_spoemployment_yrs ="Total Employment Years is less than 2 years Spouse: ";
				$spemployment_yrs[] = $counterx;	}
				
				else {
				//Reserve 
				}$val_spoemployment_yrs = totalempyrs($detail->capno);
				
				}
			
			if($emp_status != "Permanent"){
				$dev_spoemployment_status = "Employment Status must be permanent / regular Spouse: ";
				$val_spoemployment_status = $emp_status;
				$spemployment_status[] = $counterx;
			}
			}}

		//End of Spouse Employment Deviation
		//Co Spouse Business 

			$bus = new Model_BorrowerBusiness();
			$select = $bus->select();
			$select->where('capno  like ?',$detail->capno);
			$busspdetail = $bus->fetchAll($select);
			if(!is_null($busspdetail)){
			foreach($busspdetail as $busdetail){
			if($busdetail->bus_yrs < 2){
			$dev_spobusiness_yrs = "Years in Business must be at least 2 years Spouse: ";
			$val_spobusiness_yrs = $busdetail->bus_yrs;
			$spbusiness_yrs[] = $counterx;
			}
			}}
		
		//Start of Spouse CA
		if($role == 'CA' || $role == 'CO'){
			
			$cv = new Model_BorrowerCv();
			$select = $cv->select();
			$select->where('capno like ?',$detail->capno);
			$cvdetail = $cv->fetchRow($select);
			
			if($cvdetail->nfis == 'Positive'){
				$dev_spnfis = "Spouse NFIS Positive Spouse: ";
				$spnfis[] = $counterx;
			}	$val_spnfis = $cvdetail->nfis;
			
			if($cvdetail->cmap == 'Positive'){
				$dev_spcmap = "Spouse CMAP Positive Spouse: ";
				$spcmap[] = $counterx;
			}	$val_spcmap = $cvdetail->cmap;
			
			
			if($cvdetail->date_nfis){
			$cvDays = daysDifference(date('Y-m-d'),date('Y-m-d',strtotime($cvdetail->date_nfis)));
			if($cvDays > 30){
				$dev_spnfis_check = "NFIS check is more than 1 month Spouse: ";
				$spnfis_check[] = $counterx;
			}	$val_spnfis_check = $cvDays.' '.$cvdetail->date_nfis;
			}
			
			$ci = new Model_BorrowerCi();
			$select = $ci->select();
			$select->where('capno like ?',$detail->capno);
			$ciDetail = $ci->fetchRow($select);
			
			if($ciDetail->empver_ci2 == 'Unfavorable' || 
			$ciDetail->busver_ci2 == 'Unfavorable' || 
			$ciDetail->trdchk_ci2 == 'Unfavorable' ||
			$ciDetail->backgrd_ci2 == 'Unfavorable' ||
		    $ciDetail->residence_ci2 == 'Unfavorable' || 
			$ciDetail->income_ci == 'Unfavorable' ||
			$cvdetail->empver2 == 'Unfavorable' ||
			$cvdetail->busver2 == 'Unfavorable' ||
			$cvdetail->trdchk2 == 'Unfavorable' ||
			$cvdetail->backgrd == 'Unfavorable' ||
			$cvdetail->bankref == 'Unfavorable' ||
			$cvdetail->creditchk == 'Unfavorable' ||
			$cvdetail->pastdealings == 'Unfavorable' ||
			$cvdetail->income == 'Unfavorable'
			){
				$dev_spci_favorable = "Unfavorable CI Report Spouse: ";
				$spci_favorable[] = $counterx;
				$val_spci_favorable = 'Unfavorable';				
			}else {
				$val_spci_favorable = 'Favorable';			
			}
			
			if($ciDetail->date_ci){
			$ciDays = daysDifference(date('Y-m-d'),date('Y-m-d',strtotime($ciDetail->date_ci)));
			if($ciDays > 90){
				$dev_spci_check = "CI Report is more than 3 months Spouse: ";
				$spci_check[] = $counterx;
			}	$val_spci_check = $ciDays.' '.$ciDetail->date_ci;
			}
		} // End of Spouse CA

		$counterx++;
	}
	//}// of if total income 0
		//End of Spouse Deviation
		

		$dev = array(
		
		'dev_downpayment' => $dev_downpayment,
		//'dev_veh_age' =>$dev_veh_age,
		'dev_gmi' => $dev_gmi,
		'dev_loan_amount' => $dev_loan_amount,
		'dev_sell_lcp' => $dev_sell_lcp, 
		'dev_sell_appraisal' =>$dev_sell_appraisal,
		'dev_totalcombine' => $dev_totalcombine,
		'dev_borrower_age'=> $dev_borrower_age,
		'dev_residence_yrs'=> $dev_residence_yrs,
		'dev_employment_yrs'=>$dev_employment_yrs,
		'dev_employment_status'=>$dev_employment_status,
		'dev_veh_yr'=>$dev_veh_yr,
		'dev_veh_tenor'=>$dev_veh_tenor,
		'dev_veh_car_history'=>$dev_veh_car_history,
		'dev_loan_purpose'=>$dev_loan_purpose,
		'dev_business_yrs'=>$dev_business_yrs,
		'dev_citizenship1'=>$dev_citizenship1,
		'dev_loantermhigh'=>$dev_loantermhigh,
		'dev_loantermlow' =>$dev_loantermlow,
		'dev_nfis'=>$dev_nfis,
		'dev_cmap'=>$dev_cmap,
		'dev_nfis_check'=>$dev_nfis_check,
		'dev_ci_favorable'=>$dev_ci_favorable,
		'dev_ci_check'=>$dev_ci_check,
		'dev_total_income'=>$dev_total_income,
		//Coborrower Deviation
		'dev_coborrower_age'=>$dev_coborrower_age.implode('-',$coage),
		'dev_coresidence_yrs'=>$dev_coresidence_yrs.implode('-',$coresidence_yrs),
		'dev_coemployment_yrs'=>$dev_coemployment_yrs.implode('-',$coemployment_yrs),
		'dev_coemployment_status'=>$dev_coemployment_status.implode('-',$coemployment_status),
		'dev_cobusiness_yrs'=>$dev_cobusiness_yrs.implode('-',$cobusiness_yrs),
		'dev_citizenship2'=>$dev_coCitizenship.implode('-',$cocitizenship),
		'dev_confis'=>$dev_confis.implode('-',$confis),
		'dev_cocmap'=>$dev_cocmap.implode('-',$cocmap),
		'dev_confis_check'=>$dev_confis_check.implode('-',$confis_check ),
		'dev_coci_favorable'=>$dev_coci_favorable.implode('-',$coci_favorable),
		'dev_coci_check'=>$dev_coci_check.implode('-',$coci_check),
		'dev_cototal_income'=>$dev_cototal_income.implode('-',$cototalincome),
		//Spouse Deviation
		'dev_spouse_age'=>$dev_spoborrower_age.implode('-',$spage),
		'dev_sporesidence_yrs'=>$dev_sporesidence_yrs.implode('-',$spresidence_yrs),
		'dev_spoemployment_yrs'=>$dev_spoemployment_yrs.implode('-',$spemployment_yrs),
		'dev_spoemployment_status'=>$dev_spoemployment_status.implode('-',$spemployment_status),
		'dev_spobusiness_yrs'=>$dev_spobusiness_yrs.implode('-',$spbusiness_yrs),
		'dev_spocitizenship'=>$dev_spoCitizenship.implode('-',$spcitizenship),
		'dev_spnfis'=>$dev_spnfis.implode('-',$spnfis),
		'dev_spcmap'=>$dev_spcmap.implode('-',$spcmap),
		'dev_spnfis_check'=>$dev_spnfis_check.implode('-',$spnfis_check),
		'dev_spci_favorable'=>$dev_spci_favorable.implode('-',$spci_favorable),
		'dev_spci_check'=>$dev_spci_check.implode('-',$spci_check),
		'dev_spototal_income'=>$dev_spototal_income.implode('-',$sptotalincome),
		'dev_veh_use_taxi_downpayment'=>$dev_veh_use_taxi_downpayment,	
		'dev_veh_use_taxi_term'=>$dev_veh_use_taxi_term,
		); 
		
		$devValues = array(
		'capno' => $capno,
		'values_downpayment' => $val_downpayment,	
		//'values_veh_age' =>$val_veh_age,
		'values_gmi' => $val_gmi,
		'values_loan_amount' => $val_loan_amount,
		'values_totalcombine' => $val_totalcombine,
		'values_borrower_age'=> $val_borrower_age,
		'values_residence_yrs'=> $val_residence_yrs,
		'values_employment_yrs'=>$val_employment_yrs,
		'values_employment_status'=>$val_employment_status,
		'values_veh_yr'=>$val_veh_yr,
		'values_veh_tenor'=>$val_veh_tenor,
		'values_veh_car_history'=>$val_veh_car_history,
		'values_loan_purpose'=>$val_loan_purpose,
		'values_business_yrs'=>$val_business_yrs,
		'values_citizenship1'=>$val_citizenship1,
		'values_citizenship2'=>$val_citizenship2,
		'values_loantermhigh'=>$val_loantermhigh,
		'values_loantermlow' =>$val_loantermlow,
		'values_sell_lcp'=> $val_sell_lcp,
		'values_sell_appraisal'=>$val_sell_appraisal,
		'values_nfis'=>$val_nfis,
		'values_cmap'=>$val_cmap,
		'values_nfis_check'=>$val_nfis_check,
		'values_ci_favorable'=>$val_ci_favorable,
		'values_ci_check'=>$val_ci_check,
		'values_total_income'=>$val_total_income,
		'values_veh_use_taxi_downpayment'=>$val_veh_use_taxi_downpayment,
		'values_veh_use_taxi_term'=>$val_veh_use_taxi_term,
		//Coborrower valiation
		'values_coborrower_age'=>$val_coborrower_age,
		'values_citizenship2'=>$val_coCitizenship,
		'values_coresidence_yrs'=>$val_coresidence_yrs,
		'values_coemployment_yrs'=>$val_coemployment_yrs,
		'values_coemployment_status'=>$val_coemployment_status,
		'values_cobusiness_yrs'=>$val_cobusiness_yrs,
		'values_confis'=>$val_confis,
		'values_cocmap'=>$val_cocmap,
		'values_confis_check'=>$val_confis_check,
		'values_coci_favorable'=>$val_coci_favorable,
		'values_coci_check'=>$val_coci_check,
		'values_cototal_income'=>$val_cototal_income,

		//Spouse valiation
		'values_spouse_age'=>$val_spoborrower_age,
		'values_sporesidence_yrs'=>$val_sporesidence_yrs,
		'values_spoemployment_yrs'=>$val_spoemployment_yrs,
		'values_spoemployment_status'=>$val_spoemployment_status,
		'values_spobusiness_yrs'=>$val_spobusiness_yrs,
		'values_spocitizenship'=>$val_spoCitizenship,
		'values_spnfis'=>$val_spnfis,
		'values_spcmap'=>$val_spcmap,
		'values_spnfis_check'=>$val_spnfis_check,
		'values_spci_favorable'=>$val_spci_favorable,
		'values_spci_check'=>$val_spci_check,
		'values_sptotal_income'=>$val_spototal_income,
		);
		$Deviation = new Model_BorrowerDeviation();

		$sql = $Deviation->select()->where('capno LIKE ?',$capno);
		
		if($Deviation->fetchAll($sql)->count()== 0){
			$Deviation->insert($devValues);
			//if no record found insert data
		}
		else{
		//else update using the capno
		$where = "capno like '$capno'";
		$Deviation->update($devValues,$where);				
		}
		
		
	return $dev;		
	}
	

}


function getHighEmpX($capno){
			
			//Determine the Highest Salary among the employments and get 
			//its Emp ID to be use in the Deviation
			
			$emp = new Model_BorrowerEmployment();
			$select = $emp->select();
			$select->where('capno like ?',$capno)->where('employer like ?','Current');
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

function getEmpPos($seq){

	$table = new Model_CategoryValues();
	$select = $table->select();
	$select->where('name like ?','EmpPosition')
			->where('seq =?',$seq);
	$row = $table->fetchRow($select);

	return $row->values;
	}
	
function getPreviousEmp($capno){
	
			$emp = new Model_BorrowerEmployment();
			$select = $emp->select();
			$select->where('capno like ?',$capno);
			$empdetail = $emp->fetchAll($select);
			
			$counter = 0;
			foreach($empdetail as $detail){
				
		    if ($detail->employer == "Previous"){
			$counter++;
			}
			}
			
		return $counter;
	
	
}

function totalempyrs($capno){
			
			$emp = new Model_BorrowerEmployment();
			$select = $emp->select();
			$select->where('capno like ?',$capno);
			$empdetail = $emp->fetchAll($select);
			$lastYr=100;
			$num = 0;
			if(!is_null($empdetail)){				
			$highemp = getHighEmpX($capno);
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
					if(daysDifference($modeldate,$detail->date_resigned) < 365){
						$num = $num+$detail->emp_yrs+($detail->emp_months / 12);
					}
					else{
					// Dont Add						
					}
				}
				else if($detail->employer == 'Current'){
				
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
			}}}

		return $num;
	}

function getHighBus($capno){
			
			//Determine the Highest Salary among the Business and get 
			//its Bus ID to be use in the Deviation
			
			$bus = new Model_BorrowerBusiness();
			$select = $bus->select();
			$select->where('capno like ?',$capno);
			$busdetail = $bus->fetchAll($select);
			
			$sum = 0;
			foreach($busdetail as $detail){
				
		    if ($detail->bus_income > $sum){
		    	$sum = $detail->bus_income;
			    $bus_id = $detail->id;
				}
			}
			
		return $bus_id;

}

	function getEmpStatus($seq){

	$table = new Model_CategoryValues();
	$select = $table->select();
	$select->where('name like ?','EmpStatus')
			->where('seq =?',$seq);
	$row = $table->fetchRow($select);

	return $row->values;
	}
	
	function getCitizenship($seq){

	$table = new Model_CategoryValues();
	$select = $table->select();
	$select->where('name like ?','Citizenship')
			->where('seq =?',$seq);
	$row = $table->fetchRow($select);


	return $row->values;
	}
	
	function countcoborrower($capno){
		
		$tables = new Model_BorrowerAccount();
		
		$sql = $tables->select()
	    ->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where('relation LIKE ?','Coborrower')
		->order("capno ASC");
		
		$count = $tables->fetchAll($sql)->count();
		
		return $count;
		
		
	}

	function chkcocitizenship($capno){
		
		$tables = new Model_BorrowerAccount();
		
		$sql = $tables->select()
	    ->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where('relation LIKE ?','Coborrower')
		->order("capno ASC");
		
		$count = 0;
		$tables = $tables->fetchAll($sql);
		
		foreach($tables as $details){
			
			if(getCitizenship($details->citizenship) != "Filipino"){
				$count++;
			}
			
			
		}
		
		return $count;
		
		
	}
	
function daysDifference($endDate, $beginDate)
{

   //explode the date by "-" and storing to array
   $date_parts1=explode("-", $beginDate);
   $date_parts2=explode("-", $endDate);
   //gregoriantojd() Converts a Gregorian date to Julian Day Count
   $start_date=gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
   $end_date=gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
   return $end_date - $start_date;
}

function countSpouse($capno){
		
		$accnt = new Model_BorrowerAccount();
		$sql = $accnt->select()
	    ->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where('relation LIKE ?','Spouse');
		$count = $accnt->fetchAll($sql)->count();
		
		return $count;
	
}
	
function reqVehTerm($age){
	
	if($age == 1){
		return 36;		
	}
	else if($age == 2){
		return 24;		
	}
	else if($age == 3 || $age == 4){
		return 12;		
	}else {
		return 36;		
	}
	
}

