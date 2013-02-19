<?php
class Zend_View_Helper_ViewFields extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewFields($fields){
		
		if($fields == 'capno'){
			return 'Cap No.';
		}
		else if($fields == 'relation'){
			return 'Relation';
			
		}		
		else if($fields == 'bus_name'){
			return 'Business Name';
			
		}
		else if($fields == 'bus_address'){
			return 'Business Address';
			
		}
		else if($fields == 'bus_telno'){
			return 'Business Telephone Number';
			
		}
		else if($fields == 'bus_srcincome'){
			return 'Business Source of Income';
			
		}
		else if($fields == 'bus_yrs'){
			return 'Business Years';
			
		}
		else if($fields == 'bus_income'){
			return 'Business Income';
			
		}
		else if($fields == 'bus_nat'){
			return 'Nature of Business';
			
		}
		else if($fields == 'bus_dti'){
			return 'DTI';
			
		}
		else if($fields == 'bus_zipcode'){
			return 'Business Zipcode';
			
		}
		else if($fields == 'date_resigned'){
			return 'Date Resigned';
			
		}
		else if($fields == 'emp_name'){
			return 'Employers Name';
			
		}
		else if($fields == 'emp_industry'){
			return 'Employers Industry';
			
		}
		else if($fields == 'emp_address'){
			return 'Employers Address';
			
		}
		else if($fields == 'emp_telno'){
			return 'Employers Telephone Number';
			
		}
		else if($fields == 'emp_pos'){
			return 'Position';
			
		}
		else if($fields == 'emp_status'){
			return 'Employment Status';
			
		}
		else if($fields == 'emp_yrs'){
			return 'Employment Years';
			
		}
		else if($fields == 'emp_income'){
			return 'Employment Income';
			
		}
		else if($fields == 'employer'){
			return 'Employer';
			
		}
		else if($fields == 'emp_gsiss'){
			return 'GSIS No.';
			
		}
		else if($fields == 'emp_zipcode'){
			return 'Employers Zipcode';
			
		}
		else if($fields == 'year_make'){
			return 'Year/Make';
			
		}
		else if($fields == 'model'){
			return 'Model';
			
		}
		else if($fields == 'auto_emv'){
			return 'Auto Estimated Market Value';
			
		}
		else if($fields == 'location'){
			return 'Location';
			
		}
		else if($fields == 'lot_area'){
			return 'Lot Area';
			
		}
		else if($fields == 'real_emv'){
			return 'Real Estate Estimated Market Value';
			
		}
		else if($fields == 'company'){
			return 'Company Name';
			
		}
		else if($fields == 'num_share'){
			return 'No. of Shares';
			
		}
		else if($fields == 'shares_emv'){
			return 'Shares Estimated Market Value';
			
		}
		else if($fields == 'bap'){
			return 'BAP';
			
		}
		else if($fields == 'nfis'){
			return 'NFIS';
			
		}
		else if($fields == 'cmap'){
			return 'CMAP';
			
		}
		else if($fields == 'srcincomever'){
			return 'Source of Income Verification';
			
		}
		else if($fields == 'empver'){
			return 'Employment Verification';
			
		}
		else if($fields == 'busver'){
			return 'Business Verification';
			
		}
		else if($fields == 'trdchk'){
			return 'Trade Checking';
			
		}
		else if($fields == 'backgrd'){
			return 'Background Investigation';
			
		}
		else if($fields == 'bankref'){
			return 'Bank Reference';
			
		}
		else if($fields == 'creditchk'){
			return 'Credit Checking';
			
		}
		else if($fields == 'pastdealings'){
			return 'Past Dealings w/ CBC';
			
		}
		else if($fields == 'remarks_bap'){
			return 'Remarks BAP';
			
		}
		else if($fields == 'remarks_nfis'){
			return 'Remarks NFIS';
			
		}
		else if($fields == 'remarks_cmap'){
			return 'Remarks CMAP';
			
		}
		else if($fields == 'remarks_srcincomever'){
			return 'Remarks Source of Inc. Verification';

		}
		else if($fields == 'remarks_empver'){
			return 'Remarks Employment Verification';	

		}
		else if($fields == 'remarks_busver'){
			return 'Remarks Business Verification';		

		}
		else if($fields == 'remarks_trdchk'){
			return 'Remarks Trade Checking';

		}
		else if($fields == 'remarks_backgrd'){
			return 'Remarks Background Investigation';

		}
		else if($fields == 'remarks_bankref'){
			return 'Remarks Bank Reference';

		}
		else if($fields == 'remarks_creditchk'){
			return 'Remarks Credit Checking';

		}
		else if($fields == 'remarks_pastdealings'){
			return 'Remarks Past Dealings';

		}
		else if($fields == 'model_cv_srcincomever'){
			return 'Model CV Source of Inc. Verification';

		}
		else if($fields == 'model_cv_empver'){
			return 'Model CV Employment Verification';

		}
		else if($fields == 'model_cv_busver'){
			return 'Model CV Business Verification';

		}
		else if($fields == 'model_cv_trdchk'){
			return 'Model CV Trade Checking';

		}
		else if($fields == 'model_cv_backgrd'){
			return 'Model CV Background Investigation';

		}
		else if($fields == 'scrincomever_ci'){
			return 'Soure of Inc. Verification CI';

		}
		else if($fields == 'empver_ci'){
			return 'Employment Verification CI';

		}
		else if($fields == 'busver_ci'){
			return 'Business Verification  CI';

		}
		else if($fields == 'trdchk_ci'){
			return 'Trade Checking CI';

		}
		else if($fields == 'backgrd_ci'){
			return 'Background Investigation CI';

		}
		else if($fields == 'remarks_scrincomever_ci'){
			return 'Remarks Soure of Inc. Verification CI';

		}
		else if($fields == 'remarks_empver_ci'){
			return 'Remarks Employment Verification CI';

		}
		else if($fields == 'remarks_busver_ci'){
			return 'Remarks Business Verification CI';

		}
		else if($fields == 'remarks_trdchk_ci'){
			return 'Remarks Trade Checking CI';

		}
		else if($fields == 'remarks_backgrd_ci'){
			return 'Remarks Background Investigation CI';

		}
		else if($fields == 'model_ci_scrincomever'){
			return 'Model CI Soure of Inc. Verification' ;

		}
		else if($fields == 'model_ci_empver'){
			return 'Model CI Employment Verification';

		}
		else if($fields == 'model_ci_busver'){
			return 'Model CI Business Verification';

		}
		else if($fields == 'model_ci_trdchk'){
			return 'Model CI Trade Checking';

		}
		else if($fields == 'model_ci_backgrd'){
			return 'Model CI Background Investigation';

		}
		else if($fields == 'bank'){
			return 'Bank';

		}
		else if($fields == 'branch'){
			return 'Branch';

		}
		else if($fields == 'account_type'){
			return 'Account Type';

		}
		else if($fields == 'account_no'){
			return 'Account Number';

		}
		else if($fields == 'adb'){
			return 'ADB';

		}
		else if($fields == 'date_opened'){
			return 'Date Opened';

		}
		else if($fields == 'company'){
			return 'Company';

		}
		else if($fields == 'limit'){
			return 'Limit';

		}
		else if($fields == 'expiry_date'){
			return 'Expiry Date';

		}
		else if($fields == 'facility_type'){
			return 'Facility Type';

		}
		else if($fields == 'amount'){
			return 'Amount';

		}
		else if($fields == 'collateral'){
			return 'Collateral';

		}
		else if($fields == 'contact_person'){
			return 'Contact Person';

		}
		else if($fields == 'contact_no'){
			return 'Contact Number';

		}
		else if($fields == 'nature_transaction'){
			return 'Nature of Transaction';

		}
		else if($fields == 'birthdate'){
			return 'Birth Day';

		}
		else if($fields == 'pres_address_brgy'){
			return 'Present Address - Barangay';

		}
		else if($fields == 'prev_address_brgy'){
			return 'Previous Address - Barangay';

		}
		else if($fields == 'prev_zipcode'){
			return 'Previous Address - Zipcode';

		}
		else if($fields == 'pres_zipcode'){
			return 'Present Address - Zipcode';

		}
		else if($fields == 'residence_yrs'){
			return 'Residence Years';
		}
		else if($fields == 'prev_address_city'){
			return 'Previous Address - City';

		}
		else if($fields == 'prev_address_city'){
			return 'Present Address - City';

		}
		else if($fields == 'pres_address_province'){
			return 'Previous Address - Province';

		}	
		else if($fields == 'prev_address_province'){
			return 'Present Address - Province';

		}		
		else if($fields == 'id'){
			return 'I.D.';

		}		
		else if($fields == 'total_income'){
			return 'Total Income';

		}		
		else if($fields == 'veh_brand'){
			return 'Vehicle Brand';

		}	
		else if($fields == 'amountloan'){
			return 'Loan Amount';

		}	
		else if($fields == 'gmi_ratio'){
			return 'GMI Ratio';

		}	
		else if($fields == 'monthly_amortization'){
			return 'Monthly Amortization';

		}	
		else if($fields == 'downpayment_actual'){
			return 'Downpayment Actual';

		}
		else if($fields == 'selling_price'){
			return 'Selling Price';

		}	
		else if($fields == 'lcp'){
			return 'LCP';

		}	
		else if($fields == 'veh_unit'){
			return 'Vehicle Unit';

		}	
		else if($fields == 'dealer_coordinator'){
			return 'Dealer Coordinator';

		}	
		else if($fields == 'tin_id'){
			return 'Tin ID';

		}	
		else if($fields == 'gender'){
			return 'Gender';

		}
		else if($fields == 'email'){
			return 'Email';

		}
		else if($fields == 'civilstatus'){
			return 'Civil Status';

		}	
		else if($fields == 'mobile'){
			return 'Mobile';

		}	
		else if($fields == 'landline'){
			return 'Landline';

		}	
		else if($fields == 'borrower_lname'){
			return 'Borrower Last Name';

		}		
		else if($fields == 'borrower_fname'){
			return 'Borrower First Name';

		}	
		else if($fields == 'borrower_mname'){
			return 'Borrower Middle Name';

		}	
		else if($fields == 'pres_address_no'){
			return 'Present Address No.';

		}	
		else if($fields == 'pres_address_st'){
			return 'Present Address Street';

		}
		else if($fields == 'pres_address_city'){
			return 'Present Address City';

		}
		else if($fields == 'emp_no'){
			return 'Employer Address No.';

		}
		else if($fields == 'emp_street'){
			return 'Employer Address Street';

		}
		else if($fields == 'emp_brgy'){
			return 'Employer Address Brgy';

		}
		else if($fields == 'emp_city'){
			return 'Employer Address City';

		}
		else if($fields == 'emp_province'){
			return 'Employer Address Province';

		}
		else if($fields == 'submitted_ca'){
			return 'Credit Analyst';

		}
		else if($fields == 'submitted_co'){
			return 'Credit Officer';

		}
		else if($fields == 'tel_avail'){
			return 'Telephone';
		}
		else if($fields == 'citizenship'){
			return 'Citizenship';
		}		
		else if($fields == 'rate'){
			return 'Rate';
		}	
		else if($fields == 'loanterm'){
			return 'Loan Term';
		}	
		else if($fields == 'downpayment_percent'){
			return 'Downpayment %';
		}
		else if($fields == 'veh_use'){
			return 'Vehicle Use';
		}
		else if($fields == 'veh_yrmodel'){
			return 'Vehicle Year Model';
		}
		else if($fields == 'veh_type'){
			return 'Vehicle Type';
		}
		else if($fields == 'veh_status'){
			return 'Vehicle Status';
		}
		else if($fields == 'dealer'){
			return 'Dealer';
		}
		else if($fields == 'dealer_agent'){
			return 'Dealer Agent';
		}
		else if($fields == 'empbus_status'){
			return 'Employment / Business';
		}
		else if($fields == 'emp_date'){
			return 'Employment Date';
		}
		else if($fields == 'residence_type'){
			return 'Residence Type';
		}
		else if($fields == 'neighborhoodtype'){
			return 'Neighborhood Type';
		}
		else if($fields == 'residence_months'){
			return 'Residence Months ';
		}
		else if($fields == 'submitted_mo'){
			return 'Marketing Officer';
		}
		else if($fields == 'residence_type'){
			return 'Residence Type';
		}
		else if($fields == 'residence_type_prev'){
			return 'Residence Type Previous';
		}
		else if($fields == 'residence_months_prev'){
			return 'Residence Months Previous';
		}
		else if($fields == 'score'){
			return 'Credit Score';
		}
		else if($fields == 'application_type'){
			return 'Application Type';
		}
		else if($fields == 'bus_months'){
			return 'Business Months';
		}
		else if($fields == 'bus_province'){
			return 'Business Province';
		}
		else if($fields == 'bus_city'){
			return 'Business City';
		}
		else if($fields == 'bus_brgy'){
			return 'Business Barangay';
		}
		else if($fields == 'bus_street'){
			return 'Business Street';
		}
		else if($fields == 'bus_date'){
			return 'Business Date';
		}
		else if($fields == 'bus_no'){
			return 'Business No.';
		}
		else if($fields == 'bus_pos'){
			return 'Business Position';
		}
		else if($fields == 'veh_age'){
			return 'Vehicle Age';
		}
		else if($fields == 'prev_address_st'){
			return 'Previous Address Street';
		}
		/*
		else if($fields == 'dealer_incentive'){
			return 'Dealer Incentive %';
		}
		else if($fields == 'dealer_incentive2'){
			return 'Dealer Incentive Actual';
		}	
		else if($fields == 'effective_yield'){
			return 'Effective Yield';
		}
		*/	
		else if($fields == 'addon_rate'){
			return 'Financing Scheme';
		}			
		else {
			
			return $fields;
		}



	}
	
	

	
}



?>