<?php

class Zend_Controller_Action_Helper_AutoRouteSms extends
                Zend_Controller_Action_Helper_Abstract
{
	function direct($capno){
		
		//check the status in the table if its still the status for that role
		// is still for the current 
		//Add a column for the cellphone number of the appovers
		//if crecom get the users who are included
		
		$borrower = new Model_BorrowerAccount();
		$sms = new Model_Sms_SmsSending();
		$smsHistory = new Model_Sms_SmsHistory();
		
		//Check if any rows is true then move it to the history
		$sms->checktoMove();
		
		
		//Naiiwan pag last si sir many sa crecom will need to check if its approve then delete the row
		
		$users = new Model_Users();
		$statusTable = new Model_Admin_AccountStatus();
		
		$detail = $borrower->fetchRowModel($capno);
		$account_status = $detail->account_status;

		//$account_status = "Crecom - InP";
		$nextRole = $statusTable->getCurrentUser($account_status);

		if($nextRole == "AO"){
			$nextRole = "ALMH";
		}
		
		//echo $nextRole;
		$nextApprover = $users->returnUsernamebyRole($nextRole);
		//CO,CSH,ALMH,CMGH,PRES,CRECOM,SUBCRECOM
		//echo $nextApprover;
		$nextApproverName = $users->returnFullname2($nextApprover);
		//echo $nextApproverName;
		
		$enableApprover = array("CSH","ALMH","CMGH","PRES");

						$data = array(
						'capno'=>$detail->capno,
						'borrower'=>substr($detail->borrower_lname.','.$detail->borrower_fname,0,20),
						'amount_finance'=>'P'.number_format($detail->amountloan,2),
						'down_payment'=>number_format($detail->downpayment_percent,2),
						'term'=>$detail->loanterm,
						'gmi'=>number_format($detail->gmi_ratio,2),
						'approver'=>$nextApproverName,
						'cpno'=>$users->cellnumberbyUsername($nextApprover),
						'decided'=>'F',
						'status'=>"M",
						'account_status'=>$account_status
						);	

		
		if($nextApproverName == "CRECOM" || $nextApproverName == "SUBCRECOM" || $nextApproverName == "BOD"){
			//also insert if subcrecom is available
			//BOD Routine
			
			//Check the CRECOM TAble if they already are there dont insert if not insert
			if($nextApproverName == "CRECOM"){
				$crecomModel = new Model_AutoRouting_AccountsCrecom();
				$crecomConfig = new Model_AutoRouting_CrecomConfig();
				
				$enableMember = $crecomConfig->getMembers();
				
				foreach($enableMember as $y){
						$nextApprover2 = $users->returnUsernamebyRole($y->role);
						$crecomApproverName = $users->returnFullname2($nextApprover2);

					if($crecomModel->chkifDecide($detail->capno,$y->role) === false){
						$data['approver'] =  $crecomApproverName;
						$data['cpno'] = $users->cellnumberbyUsername($nextApprover2);
						$select = $sms->select();
						$select->where("approver like ?",$crecomApproverName);
						$detailx = $sms->fetchRow($select);
						
						if($detailx){
							
						}else{
						$sms->insert($data);
						}
					}
					else {
						//if true 

						//if true check if he's in the table then move him
						$select = $sms->select();
						$select->where("approver like ?",$crecomApproverName);
						$detailx = $sms->fetchRow($select);
						
						if($detailx){
						$smsHistory->insert($detailx->toArray());
						$where = "id =".$detailx->id;
						$sms->delete($where);
						}
					}
				}
				
			}
			
		}else {
			//if not group approval 
			//CMGH/PRES get the status if ganto CSH - ReAp
			
			//move the row???
			//Start the move
			$select = $sms->select();
			$select->where("capno like ?",$detail->capno);
			$rows = $sms->fetchAll($select);
			
			foreach($rows as $x){
				
				if($x->approver != $nextApproverName){
					//if the approver is not equal to the current approver remove them from the list
					$smsHistory->insert($x->toArray());
					$where = "id =".$x->id;
					$sms->delete($where);
				}
				
			}
			//Check if the account is approved/decided 
			if($statusTable->isDecided($account_status) === true){
				foreach($rows as $x){
					$smsHistory->insert($x->toArray());
					$where = "id =".$x->id;
					$sms->delete($where);
				}
				
			}
			//end of is decided
		$counter = $this->checkiftoInsert($detail->capno,$nextApproverName);
			//End of Move
		
		//check routine pag CRECOM/SUBCRECOM
		if(($counter <= 0) && (in_array($nextRole,$enableApprover) === TRUE)
		){
		//Dont insert if the table alrady have a record

		$data['status'] = $statusTable->smsStatus($account_status);
		
		$sms->insert($data);
		
		}// End of if insert
		
		} // end of is CRECOM
	}
	

	


	function checkiftoInsert($capno,$nextApproverName){
		//will check if the is suppose to be inserted
		$sms = new Model_Sms_SmsSending();
		$select = $sms->select();
		$select->where("capno like ?",$capno);
		$select->where("approver like ?",$nextApproverName);
		$count = $sms->fetchAll($select)->count();
		return $count;
	}
	
	
	
	/*
    function direct($capno,$status,$route)
    {
	
	$sms = new Model_SmsApproval();
	$users = new Model_Users();
	$approval = new Model_UsersApprover();
	
	
	//$role = $approval->returnStatusRole($status,chkRoute($route));
	$role = $approval->returnStatusRole($status,chkRoute($route));

	$approver = $users->returnUsernamebyRole($role);
	

	//$sms->addtoDB($capno, $users->returnFullname($approver));

	}
	*/
	
}




function chkRoute($route){
	$x='';
	
	$user = Zend_Auth::getInstance()->getIdentity();
	$userRole = $user->role_type;
	
	if($userRole =='CSH' || $userRole =='CMGH'){
	if(strpos($route,'-CMGH') !== FALSE){
		$x='CMGH';
	}
	if(strpos($route,'-PRES') !== FALSE){
		$x='PRES';
	}
	if(strpos($route,'CMGH-PRES') !== FALSE){
		$x='PRES';
	}

	}
	//Add codes for SUBCRECOM,CRECOM,BOARD,EXECOM
	//Add codes for SUBCRECOM,CRECOM,BOARD,EXECOM
	//Add codes for SUBCRECOM,CRECOM,BOARD,EXECOM
	//Add codes for SUBCRECOM,CRECOM,BOARD,EXECOM
	//Add codes for SUBCRECOM,CRECOM,BOARD,EXECOM
	//Add codes for SUBCRECOM,CRECOM,BOARD,EXECOM
	return $x;
	
	
	
	
}


?>