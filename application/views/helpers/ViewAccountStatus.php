<?php
class Zend_View_Helper_ViewAccountStatus extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewAccountStatus($status){

	if($status){
	$table = new Model_Admin_AccountStatus();
	$select = $table->select();
	$select->where('status like ?',$status);
	$detail = $table->fetchRow($select);
	
	if($detail->desc){
		return $detail->desc;
	}
	else { return ''; 
	}
	}
	else {
		return '';
	}
	
	/*
	if($status == "MA - EN"){
	
	return "MA Encodes New Info";
		
	}
	else if($status == "MA - E"){
	return "MA Encodes Info";
	}
	
	else if($status == "MA - S"){
	return "MA Submits the application";
	}
	
	else if($status == "CA - An"){
	return "CA Analyze";
	}
	
		
	else if($status == "CA - AnD"){
	return "CA Analysis Done";
	}
	
	else if($status == "CA - RTMA"){
	return "CA Returned to MA";
	}
	
	else if($status == "CO - RTCA"){
	return "CO Returned to CA";
	}
	else if($status == "CA - ReAp"){
	return "CA Recommends for Approval";
	}
	else if($status == "CA - ReR"){
	return "CA Recommends for Rejection";
	}
	else if($status == "CO - REV"){
	return "CO Reviews";
	}
	else if($status == "CO - REVD"){
	return "CO Reviews Done";
	}			
	else if($status == "CO - Rejected"){
	return "CO Rejected";
	}				
	else if($status == "CO - Approved"){
	return "CO Approved";
	}	
	else if($status == "CA - OR"){
	return "CA - Outright Reject";
	}						
	else if($status == "CA - NA"){
	return "CA - No Action";
	}			
	else if($status == "CA - Cancel"){
	return "CA - Cancel";
	}	
	else if($status == "CO - OR"){
	return "CO - Outright Reject";
	}						
	else if($status == "CO - NA"){
	return "CO - No Action";
	}			
	else if($status == "CO - Cancel"){
	return "CO - Cancel";
	}		
	
	else if($status == "Rejected"){
	return "Rejected";
	}				
	else if($status == "Approved"){
	return "Approved";
	}
	else if($status == "CO - Ap"){
	return "CO Approve";
	}
	
	else if($status == "CO - R"){
	return "CO Reject";}
	
	else if($status == "CO - P"){
	return "CO Pass";
	}	
	else if($status == "CO - ReAp"){
	return "CO Recommends for Approval";
	}	
	else if($status == "CO - ReR"){
	return "CO Recommends for Rejection";
	}	
	else if($status == "CSH - Ap"){
	return "CSH Approve";
	}
	
	else if($status == "CSH - R"){
	return "CSH Reject";}
	
	else if($status == "CSH - P"){
	return "CSH Pass";
	}	
	else if($status == "CSH - ReAp"){
	return "CSH Recommends for Approval";
	}	
	else if($status == "CSH - ReR"){
	return "CSH Recommends for Rejection";
	}
	else if($status == "CMGH - Ap"){
	return "CMGH Approve";
	}	
	else if($status == "CMGH - R"){
	return "CMGH Reject";
	}	
	else if($status == "CMGH - P"){
	return "CMGH Pass";
	}	
	else if($status == "CMGH - ReAp"){
	return "CMGH Recommends for Approval";
	}	
	else if($status == "CMGH - ReR"){
	return "CMGH Recommends for Rejection";
	}	
	else if($status == "PRES - Ap"){
	return "President Approve";
	}	
	else if($status == "PRES - R"){
	return "President Reject";
	}	
	else if($status == "PRES - P"){
	return "President Pass";
	}	
	else if($status == "PRES - ReAp"){
	return "President Recommends for Approval";
	}	
	else if($status == "PRES - ReR"){
	return "President Recommends for Rejection";	
	}
	else if($status == "MA - R"){
	return "MA - Reject";	
	}
	else if($status == "CSH - RTCO"){
	return "CSH Returned to CO";
	}
	
	else if($status == "ALMH - ENCSH"){
	return "ALMH Endorse to CSH";
	}
	else if($status == "ALMH - ENCMGH"){
	return "ALMH Endorse to CMGH";
	}	
	else if($status == "ALMH - ENPRES"){
	return "ALMH Endorse to President";
	}	
	else if($status == "CA - P"){
	return "CA Pass";
	}
	else if($status == "CA - CMK"){
	return "CA CoMaker Done";
	}
	else if($status == "CA - S - CMK"){
	return "CA Submit CoMaker";
	}
	else if($status == "MA - CMK"){
	return "MA CoMaker Done";
	}
	else if($status == "MA - S - CMK"){
	return "MA Submit CoMaker";
	}	
	else if($status == "CO - ReAp - ABCSH"){
	return "CO Recommends for Approval CSH On-Leave";
	}		
	else if($status == "CO - ReR - ABCSH"){
	return "CO Recommends for Rejection CSH On-Leave";
	}		
	else if($status == "CSH - ReAp - ABCMGH"){
	return "CSH Recommends for Approval CMGH On-Leave";
	}		
	else if($status == "CSH - ReR - ABCMGH"){
	return "CSH Recommends for Rejection CMGH On-Leave";
	}
	else if($status == "MA - PreB"){
	return "MA - PreBooking";
	}	
	else if($status == "LA - ChkDoc"){
	return "LA - Documents Checked";
	}	
	else if($status == "Booked"){
	return "Booked";
	}
	else if($status == "LA - Recall"){
	return "LA Recall Application";
	}										
	else { 
	return "";
	}
	*/
	}
}
?>