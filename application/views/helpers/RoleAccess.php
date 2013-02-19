<?php
class Zend_View_Helper_RoleAccess extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}

	function roleAccess($process,$capno){
	    
		$user = Zend_Auth::getInstance()->getIdentity();
	    $userRoles = new Model_UsersRoles();
		$select = $userRoles->select()->where('roles like ?',$user->role_type);
	    $access = $userRoles->fetchRow($select);

		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		$select->where('capno like ?',$capno);
		$accntdetail = $accnt->fetchRow($select);
	
		$history = new Model_AccountHistory();
		$account_status = $accntdetail->account_status;
		$comaker_accnt_status = $accntdetail->comaker_accnt_status;
		
		$statusTable = new Model_Admin_AccountStatus();
	if (($access->access_admin) && ($process == 'admin')){
	return true;
	}
	else if (($access->access_add_account) && ($process == 'add_account')){
	return true;
	}
	else if (($access->access_report) && ($process == 'report')){
	return true;
	}
	else if (($access->access_ma_assign) && ($process == 'ma_assign')){
	return true;
	}
	else if (($access->access_ca_assign) && ($process == 'ca_assign')){
	return true; 
	}
	else if (($access->access_ca_assign) && ($process == 'ci_assign')){
	return true; 
	//Ask Cla
	}
	else if (($access->access_pending) && ($process == 'pending')){
	return true; 
	}
	else if (($access->access_submitted) && ($process == 'submitted')){
	return true; 
	}
	else if (($access->access_rts) && ($process == 'rts')){
	return true; 
	}
	else if (($access->access_decision) && ($process == 'decision')){
	return true; 
	}
	else if (($access->access_recommended) && ($process == 'recommended')){
	return true; 
	}
	else if (($access->access_decided) && ($process == 'decided')){
	return true; 
	}
	else if (($access->access_recommended) && ($process == 'endorsed')){
	return true; 
	// Ask Cla
	}
	// Profile Page and Tabs

	else if (($access->access_details) && ($process == 'details')){
	return true; 
	}
	else if (($access->access_obligations) && ($process == 'obligations')){
	return true; 
	}
	else if (($access->access_unit) && ($process == 'unit')){
	return true; 
	}
	else if (($access->access_loan) && ($process == 'loan')){
	return true; 
	}
	else if (($access->access_cv) && ($process == 'cv')){
	return true; 
	}
	else if (($access->access_ci) && ($process == 'ci')){
	return true; 
	}
	else if (($access->access_documents) && ($process == 'documents')){
	return true; 
	}
	else if (($access->access_appraisal) && ($process == 'appraisal')){
	return true; 
	}
	else if (($access->access_insurance) && ($process == 'insurance')){
	return true; 
	}
	else if (($access->access_craw) && ($process == 'craw')){
	return true; 
	}
	/*
	else if (($access->edit_borrower_profile) && ($process == 'edit_borrower') &&
	($user->role_type == "MA") && (($accntdetail->account_status == "MA - E")|| ($accntdetail->account_status == "MA - EN")) ){
	return true; 
	// If the user is an MA and the account status is MA - E they can edit the account as long as 
	// the account is not yet submitted
	}
	else if (($access->edit_borrower_profile) && ($process == 'edit_borrower') &&
	($user->role_type != "MA") && (($accntdetail->account_status != "MA - EN") && ($accntdetail->account_status != "MA - E")) ){
	return true; 
	// If the user is not an MA and have access rights and the account status must not be 
	//MA - E as they cant edit it as long MA submitted it to the CA
	}
	*/
	else if (($access->edit_borrower_profile) && ($process == 'edit_borrower')) {
		
		if ($user->role_type == "MA"){
			if (($account_status == "MA - E") || ($account_status == "MA - EN") ||
			($account_status == "CA - RTMA")){
				if($accntdetail->created_by == "$user->username"){
					return true;
				}

			}
			
		}//End of MA Permission
		else if ($user->role_type == "CA"){
			if (($account_status == "MA - S") || ($account_status == "CA - An") ||
			($account_status == "CA - AnD")|| ($account_status == "CO - RTCA")){
				if($accntdetail->submitted_ca == "$user->username"){
					return true;
			}
		}
		}//End of CA Permission
		
		else if ($user->role_type == "CO"){
			if (($account_status == "CA - ReAp") || ($account_status == "CA - ReR") ||
			($account_status == "CO - REV") || ($account_status == "CO - REVD")
			|| ($account_status == "CA - OR")){
				if($accntdetail->submitted_co == "$user->username"){
					return true;

			}
		}
		}//End of CO Permission
		
		else if ($user->role_type == "LA"){
			
			return true;
		}//End of LA Permission

	}// End of Edit Permission
	else if (($access->access_reportprint) && ($process == 'printreport')){
	return true; 
	}
	else if (($access->access_account_history) && ($process == 'account_history')){
	return true; 
	}
	else if (($access->access_audit_trail) && ($process == 'audit_trail')){
	return true; 
	}
	else if (($access->access_credit_decision) && ($process == 'credit_decision')){

		if(login_user_role() == 'CO'){
		 	if(($account_status == 'CO - REVD')|| ($account_status == 'CO - REV') || 
			($account_status == 'CA - ReAp') || ($account_status == 'CA - ReR') ||
			($account_status == 'CA - OR') || ($account_status == 'CA - Cancel')
			|| ($account_status == 'CO - Cancel') || ($account_status == 'CSH - RTCO') || ($account_status == 'Approved')
			|| ($account_status == 'Rejected')
			){
		 		
				return true; 
		 	}
		}
	}
	else if($process == 'craw_edit'){
		// for preparing the craw
		if(login_user_role() == 'CO'){
			if(($account_status == 'CO - REVD')|| ($account_status == 'CO - REV') || 
			($account_status == 'CA - ReAp') || ($account_status == 'CA - ReR') || ($account_status == 'CA - P')
			|| ($account_status == 'CA - Cancel') || ($account_status == 'CA - OR') || ($account_status == 'CA - NA')
			
			){
					return true; 
			}
		}
	}

	else if($process == 'co_auto'){
		// for co autorouting
		if(login_user_role() == 'CO'){
			if(($account_status == 'CO - REVD')){
					return true; 
			}
			
		}		
	}
	else if($process == 'co_reject'){
		// for co-reject of account ca - na, ca - or, ca - cancel
		if(login_user_role() == 'CO'){
			if(($account_status == 'CA - NA' || $account_status == 'CA - OR') 
			|| $account_status == 'CA - Cancel'
			){
					return true; 
			}
			
		}		
	}
	

	else if (($access->access_booking) && ($process == 'booking')){
	return true; 
	}
	else if (($access->edit_profile) && ($process == 'edit_profile')){
	return true; 
	// For Saving the Main Profile Details
	}
	else if (($access->edit_details) && ($process == 'edit_details')){
	return true; 
	}
	else if (($access->edit_obligations) && ($process == 'edit_obligations')){
	return true; 
	}
	else if (($access->edit_unit) && ($process == 'edit_unit')){
	return true; 
	}
	else if (($access->edit_loan) && ($process == 'edit_loan')){
	return true; 
	}
	else if (($access->edit_cv) && ($process == 'edit_cv')){
	return true; 
	}
	else if (($access->edit_ci) && ($process == 'edit_ci')){
	return true; 
	}
	else if (($access->edit_documents) && ($process == 'edit_documents')){
	return true; 
	}
	else if (($access->edit_appraisal) && ($process == 'edit_appraisal')){
	return true; 
	}
	else if (($access->edit_insurance) && ($process == 'edit_insurance')){
	return true; 
	}
	else if (($access->edit_craw) && ($process == 'edit_craw')){
	return true; 
	}
	// Profile Menu Access
	else if (($access->view_submit_menu) && ($process == 'view_submit') &&
	($user->role_type == "MA") && ($accntdetail->account_status == "MA - E")){
	return true; 
	}
	
	else if (($access->view_submit_menu) && ($process == 'view_submit') &&
	($user->role_type == "CI")){
	// Determine what must be the application status so CI can see the submit button
	return true; 
	}
	else if (($access->view_recommend_menu) && ($process == 'view_recommend')){
		
		if($user->role_type == 'CA'){
			if ($account_status == 'CA - AnD'){
				if($accntdetail->submitted_ca == $user->username){
					return true;

			}
		}
	 }//End of CA
	 /*
	 	if($user->role_type == 'CO'){
			if ($account_status == 'CO - REVD'){
				if($accntdetail->submitted_co == $user->username){
					return true;

			}
		}
	 }*///End of CO
	}
	else if (($access->view_requestci_menu) && ($process == 'view_requestci')){
	//To be changed
	return false; 
	}
	else if (($access->view_rts_menu) && ($process == 'view_rts')){

		if($user->role_type == 'CA'){
			if(($account_status == 'CA - An') || ($account_status == 'CA - AnD')
			|| ($account_status == 'CO - RTCA') 
			){
				if($accntdetail->submitted_ca == $user->username){
					return true;
			}
		}
	 	}//End of CA
		else if($user->role_type == 'CO'){
			if(($account_status == 'CO - REV') || ($account_status == 'CO - REVD') || ($account_status == 'CA - ReAp') 
			|| ($account_status == 'CA - ReR') || ($account_status == 'CA - OR') || ($account_status == 'CO - OR')
			|| ($account_status == 'CO - NA') || ($account_status == 'CO - Cancel')
			|| ($account_status == 'CA - OR') || ($account_status == 'CA - NA') || ($account_status == 'CA - Cancel')
			|| ($account_status == 'CA - P')
			){
			//if($accntdetail->submitted_co == $user->username){
				return true;
			}
			//}
	 	}//End of CO
		else if($user->role_type == 'CSH'){
		if(($account_status == 'CO - RP') || ($account_status == 'CO - P') || ($account_status == 'CO - ReR') || ($account_status == 'CO - ReAp')){
			return true;	
		}
	 	}//End of CSH

	}
	else if (($access->view_recon_menu) && ($process == 'view_recon')){
	//to be changed
	
	$days = $this->_view->daysDifference(date('Y-m-d'),$history->getApproveDate($capno));
	//echo $history->getApproveDate($capno);
		/*
		if (($account_status == 'Approved' || $account_status == 'CO - Ap' || $account_status == 'CSH - Ap' ||
		$account_status == 'PRES - Ap' || $account_status == 'CMGH - Ap') && (!ifRecon($capno)) && ($days <= 60)){
			return true;			
		}
		*/
		
			if(($statusTable->routeview($account_status, 'view_recon_menu') === TRUE)
			&& ($days <= 60))			
			{
			return true;
			}		
	}
	else if (($access->view_outright_menu) && ($process == 'view_outright')){
	if($user->role_type == 'CA'){
		if (($account_status == 'CA - An') || ($account_status == 'CA - AnD')){
			return true; 
		}
		}
	if($user->role_type == 'CO'){
		if (($account_status == 'CA - OR') || ($account_status == 'CA - ReR') ||
		($account_status == 'CA - ReAp') || ($account_status == 'CO - REV') ||
		($account_status == 'CO - REVD') || ($account_status == 'CA - NA') ||
		($account_status == 'CA - Cancel') || ($account_status == 'CA - P') ){
			return true; 
		}
		}
	}
	else if (($access->view_conditional_menu) && ($process == 'view_conditional')){
	return true; 
	}
	else if (($access->view_endorsed_menu) && ($process == 'view_endorsed')){
	return true; 
	}
	else if (($access->view_noaction_menu) && ($process == 'view_noaction')){
		if($user->role_type == 'CA'){
		if (($account_status == 'CA - An') || ($account_status == 'CA - AnD')){
			return true; 
		}
		}
		if($user->role_type == 'CO'){
		if (($account_status == 'CA - OR') || ($account_status == 'CA - ReR') ||
		($account_status == 'CA - ReAp') || ($account_status == 'CO - REV') ||
		($account_status == 'CO - REVD') || ($account_status == 'CA - NA') ||
		($account_status == 'CA - Cancel') || ($account_status == 'CA - P')){
			return true; 
		}
		}
	}
	else if (($access->view_score) && ($process == 'view_score')){
		if($user->role_type == 'CA'){
		if (($account_status == 'CA - AnD')){
			return true; 
		}
		}
		if($user->role_type == 'CO'){
		if (($account_status == 'CO - REV') || ($account_status == 'CO - REVD')){
			return true; 
			//temp set to false 
		}
		}
	}
	
	else if($process == 'view_reprocess'){
		
		if($user->role_type == 'MA'){
			if($account_status == 'CA - OR' ||
			   $account_status == 'MA - R' ||
			   $account_status == 'CO - NA' || 
			   $account_status == 'CO - OR' ||
			   $account_status == 'CO - Cancel' ||
			   $account_status == 'CO - Rejected' ||
			   $account_status == 'ALMH - Cancel' ||
			   $account_status == 'AO - Cancel' ||
			   $account_status == 'PRES - R' ||
			   $account_status == 'Crecom - R' ||
			   $account_status == 'Rejected'){
			return true;
			}
		}
	}
	else if($process == 'view_recall'){
		//CA Recall 
		if($user->role_type == 'CA'){
			if($account_status == 'CA - RTMA'	){
			return true;
			}
		}
	}
	else if($process == 'view_mareject'){
		// for MA Reject Accounts
		$history = new Model_AccountHistory();
		if($accntdetail->created_by == $user->username){
		if($accntdetail->account_status != 'RECON'){
		if($user->role_type == 'MA'){
			if($account_status == 'MA - E' || 
			   $account_status == 'MA - EN' || $account_status == 'CA - RTMA'){
			   	//&&   $history->countCaStatus($capno) == 0)
			return true;
			}
		} }
		}
	}
	
	else if($process == 'view_marecall'){
		// for MA Recall Accounts
		if($accntdetail->created_by == $user->username){
			 if($statusTable->routeview($account_status, 'access_ma_recall') === TRUE){	
				if($user->role_type == 'MA'){
				return true;	
		 	 } }
		}
	}	
	
	else if($process == 'testscore'){
		// for MA Reject Accounts
		if(strpos($user->username,'test')===false)
			{
			}else {
			return true;

			}
		}
		
	else if($process == 'view_comaker_submit'){
		// for MA Reject Accounts
		if($user->role_type == 'CA'){
			if($comaker_accnt_status == 'CA - CMK'){
			return true;
			}
		}
	
	}else if($process == 'view_comaker_submit_ma'){
		if($user->role_type == 'MA'){
			if($comaker_accnt_status == 'MA - CMK'){
			return true;
			}
		
		}
		
	}
	else if($process == 'view_ma_booking'){
		//$days = $this->_view->daysDifference(date('Y-m-d'),$history->getApproveDate($capno));
	if($accntdetail->date_decided){
	$days = $this->_view->daysDifference(date('Y-m-d'),$accntdetail->date_decided);
	
		//echo 'jigen'.$days;

		if($user->role_type == 'AO'){

			if(($statusTable->routeview($account_status, 'ma_prebooking_view') === TRUE)
			&& ($days <= 60))			
			{
			return true;
			}
		}
		}
		
	}
	else if($process == 'booking_edit'){
		if($user->role_type == 'LA'){
			if($account_status == 'MA - PreB') {
				$userModel = new Model_Users();
				if($userModel->getRoleType($accntdetail->created_by) == 'LA'){
					return true;
				}
			}
		}
	}
	else if($process == 'booking_checking'){
		if($user->role_type == 'LA'){
			//if($accntdetail->submitted_la == $user->username){
				if($account_status == 'MA - PreB' || 
				$account_status == 'LO - RTLA' || 
				$account_status == 'LA - Recall'
				){
				return true;				
			//	} 
			}
		}
	}
	else if($process == 'booking_finalcheck'){
		if($user->role_type == 'LO'){
				if($account_status == 'LA - ChkDoc'){
				return true;				
				 
			}
		}
	}	
	else if($process == 'booking_larts'){
		if($user->role_type == 'LO'){
				if($account_status == 'LA - ChkDoc'){
				return true;				
				 
			}
		}
	}	
	else if($process == 'booking_marts'){
		if($user->role_type == 'LA'){
			if($account_status == 'MA - PreB' || $account_status == 'LA - Recall'
				|| $account_status == 'LO - RTLA'
				){
				return true;				
			}
		}
	}			
	
	else if($process == 'booking_recall'){
	if($account_status == 'Booked'){
		//if($accntdetail->submitted_la == $user->username){
		if($user->role_type == 'LA'){
			$todays_date = date("Y-m-d");
			$today = strtotime($todays_date); 						
			$recalldate = strtotime(date("Y-m-d", strtotime($accntdetail->date_booked)) . " +180 day");
			if($recalldate > $today) {		
				return true;
		} } } //}
	}	

	else if($process == 'view_la_manual_approve'){
	if($account_status){	
	 if($statusTable->routeview($account_status, 'inprocess_autorouting') === TRUE
	 || $statusTable->routeview($account_status, 'reject') === TRUE){	
		if($user->role_type == 'LA'){
		return true;	
		} }
	}	}
	
	else if($process == 'view_corecall'){
	 if($account_status){	
	 if($statusTable->routeview($account_status, 'co_submitted') === TRUE){	
		if($user->role_type == 'CO'){
			if($user->username == login_user()){
			return true;	
		} } }
	}	}

	
	else {
	return false;
	}

	}
	

	

	

	
}
?>