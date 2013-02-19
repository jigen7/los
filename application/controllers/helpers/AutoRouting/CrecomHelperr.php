<?php
/**
 * Paolo Marco M. Manarang
 * paolomanarang@gmail.com
 * Aug 27,2010
 * Helper for Crecom and Subcrecom
 * 
 */
class Zend_Controller_Action_Helper_CrecomHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct(){	

	}
	
	function chkifDecided($capno,$routetag){
	//Prevents Dual Decision in Crecom
	$userData = Zend_Auth::getInstance()->getIdentity();
	$username = $userData->username;

	if(strpos($routetag,'-CRECOM')!== false || 
	strpos($routetag,'-SUBCRECOM')!== false || 
	strpos($routetag,'-EXE-BOD')!== false){	
		$counter = 0;
		if(strpos($routetag,'-CRECOM')!== false || 
		strpos($routetag,'-EXE-BOD')!== false){
		$accountTable = new Model_AutoRouting_AccountsCrecom();
		}
		if(strpos($routetag,'-SUBCRECOM')!== false){
		$accountTable = new Model_AutoRouting_AccountsSubCrecom();
		}
		$select = $accountTable->select();
		$select->where('capno like ?',$capno);
		$select->where('"user" like ?', $username);

		$counter = $counter + $accountTable->fetchAll($select)->count();
		return $counter;
	} // enf of end if
	
	}//end of function 
	
	
	function chkApprovalStatus($capno,$grp){
		//-Temp Member of 3 one reject will reject the account
		//-if the president reject the account it will be automatically rejected
		//-Will approve if the chairman approve and two members approve
		//-CSH will start the crecom-subcrecom process his recommending will also be his crecom decision

		
		
		$config = new Model_AutoRouting_Config();
		if($grp == 'crecom'){
		$accountTable = new Model_AutoRouting_AccountsCrecom();
		$configTable = new Model_AutoRouting_CrecomConfig();
		$group = 'Crecom';
		$numChairman = $config->getColConfig('crecom_chairman');
		$numMember = $config->getColConfig('crecom_member');
		
		}else if($grp == 'subcrecom'){
		$accountTable = new Model_AutoRouting_AccountsSubCrecom();
		$configTable = new Model_AutoRouting_SubCrecomConfig();
		$group = 'SubCrecom';
		$numChairman = $config->getColConfig('subcrecom_chairman');
		$numMember = $config->getColConfig('subcrecom_member');
		}
		
		$chkStatus = $group.' - InP';
		$status = $group.' - InP';
			//Chairman Disapprove
			if($accountTable->chkChairmanNormalDisapprove($capno) > 0){
				$status = $group.' - R';
				$decide = 'Rejected';	
			}
			// end of Chairman Disapprove
			else{
				if(($accountTable->chkChairmanApproveNormal($capno) >= $numChairman) && 
				($accountTable->chkMemberApproveNormal($capno) >= $numMember)
				){
				$status = $group.' - Ap';	
				$decide = 'Approved';
				}
				//echo $accountTable->chkMemberApproveNormal($capno).':' .$numMember.' '.$accountTable->chkChairmanApproveNormal($capno).': '.$numChairman;
			}// end of Chairman did'nt disapprove
			
/*********************Temporary if the member is 3******************************/
		if($configTable->countEnable() == 3){
			//3 Members
			if($accountTable->countDisapprove($capno) > 0){
				$status = $group.' - R';
				$decide = 'Rejected';				
			}
		}
		else if($configTable->countEnable() < 3){
			$status = $group.' - R';
			$decide = 'Rejected';
		}
/********************End of Temporary*****************************************/

		echo $status;

		//update status if status is not equal to the orignal Status
		if($chkStatus != $status){
			$borrower = new Model_BorrowerAccount();
			$data = array(
			'account_status'=>$status,		
			'date_decided'=>date("r"),
			);
			$where = "capno like '$capno'";
			$borrower->update($data,$where);
			
			$data = array(
			'capno'=>$capno,
			'decision'=>$group.' '.$decide,
			'approved_by'=>$group,
			'reason'=>'',
			'date_approval'=>date("m-d-Y h:i a"),
			'date_approval2'=>date("r"),
			'role'=> '',
			'date_type'=>3,
			);
			
			$crawformapproval = new Model_BorrowerCrawFormApprovalsection();

			$crawformapproval->insert($data);
			
			
			//Insert Account History
				$history = new Model_AccountHistory();
				$select = $history->select();
				$select->where('capno like ?',$capno)->order('id DESC');
				$historyDetail = $history->fetchRow($select);
				$hdata = array (
				'capno'=>$capno,
				'status'=>$status,
				'by'=>login_user(),
				'date'=>date("r"),
				'remarks'=>$group,
				'date_last'=>$historyDetail->date,
				);
				$history->insert($hdata);
			//End of History
		}
	}
	
	
	
	
	function crecom($capno,$routetag,$status,$remarks){
	//function module 1 autorouting 
	if(strpos($routetag,'-CRECOM')!== false || 
	strpos($routetag,'-SUBCRECOM')!== false){
	
	$user = Zend_Auth::getInstance()->getIdentity();
	$roleType = $user->role_type;
	
	if(strpos($routetag,'-CRECOM')!== false){
	$accountTable = new Model_AutoRouting_AccountsCrecom();
	$what = 'Crecom - InP';	
	}
	if(strpos($routetag,'-SUBCRECOM')!== false){
	$accountTable = new Model_AutoRouting_AccountsSubCrecom();
	$what = 'SubCrecom - InP';
	}
	
	if($roleType == 'CSH'){
	//Routine for CSH Recommending to Add in the 
	//Crecom Table
		if($status == 'CSH - ReAp'){
		$decision = 'Approved';		
		}
		else if($status == 'CSH - ReR'){
		$decision = 'Disapproved';
		}
		$data = array(
		'capno'=>$capno,
		'decision'=>$decision,
		'user'=>$user->username,
		'role'=>$roleType,
		'type'=>'member',
		'date_decision'=>date("r"),
		'remarks'=>$remarks,
		);
		$accountTable->insert($data);
		
		$borrower = new Model_BorrowerAccount();
		$data = array(
		'account_status'=>$what,		
		);
		$where = "capno like '$capno'";
		$borrower->update($data,$where);
		
			//Insert Account History
				$history = new Model_AccountHistory();
				$select = $history->select();
				$select->where('capno like ?',$capno)->order('id DESC');
				$historyDetail = $history->fetchRow($select);
				$hdata = array (
				'capno'=>$capno,
				'status'=>$what,
				'by'=>login_user(),
				'date'=>date("r"),
				'remarks'=>$remarks,
				'date_last'=>$historyDetail->date,
				);
				$history->insert($hdata);
			//End of History
	}//END OF CSH
	
	}//end of SUBCRECOM/CRECOM routetag
	}//end of function
/***********************MODULE 2****************************/	
	function crecomMod2($capno,$routetag,$status,$remarks){
	//function module 1 autorouting 
	if(strpos($routetag,'-CRECOM')!== false || 
	strpos($routetag,'-SUBCRECOM')!== false || 
	strpos($routetag,'-EXE-BOD')!== false){
	
	$user = Zend_Auth::getInstance()->getIdentity();
	$roleType = $user->role_type;
	
			if(strpos($routetag,'-CRECOM')!== false){
			$accountTable = new Model_AutoRouting_AccountsCrecom();
			$what = 'Crecom - InP';	
			}
			if(strpos($routetag,'-SUBCRECOM')!== false){
			$accountTable = new Model_AutoRouting_AccountsSubCrecom();
			$what = 'SubCrecom - InP';
			}
			if(strpos($routetag,'-EXE-BOD')!== false){
			//BOARD
			$accountTable = new Model_AutoRouting_AccountsCrecom();
			$what = 'Crecom - InP';	
			}
	
	if($roleType == 'CSH'){
	//Routine for CSH Recommending to Add in the 
	//Crecom Table
		if($status == 'CSH - ReAp'){
		$decision = 'Approved';	
			$borrower = new Model_BorrowerAccount();
			$data = array(
			'account_status'=>$what,		
			);
			$where = "capno like '$capno'";
			$borrower->update($data,$where);	
		}
		else if($status == 'CSH - ReR' || $status == 'CSH - RP'){
		$decision = 'Disapproved';
		}
		$data = array(
		'capno'=>$capno,
		'decision'=>$decision,
		'user'=>$user->username,
		'role'=>$roleType,
		'type'=>'member',
		'date_decision'=>date("r"),
		'remarks'=>$remarks,
		);
		$accountTable->insert($data);
		


		
			//Insert Account History
				$history = new Model_AccountHistory();
				$select = $history->select();
				$select->where('capno like ?',$capno)->order('id DESC');
				$historyDetail = $history->fetchRow($select);
				$hdata = array (
				'capno'=>$capno,
				'status'=>$what,
				'by'=>login_user(),
				'date'=>date("r"),
				'remarks'=>$remarks,
				'date_last'=>$historyDetail->date,
				);
				$history->insert($hdata);
			//End of History
	}//END OF CSH
	else if($roleType == 'ALMH'){
		
	$decision = 'Approved';	
	$data = array(
		'capno'=>$capno,
		'decision'=>$decision,
		'user'=>$user->username,
		'role'=>$roleType,
		'type'=>'member',
		'date_decision'=>date("r"),
		'remarks'=>$remarks,
		);
	$accountTable->insert($data);
	
	
	}// END OF ALMH
	}//end of SUBCRECOM/CRECOM routetag
	}//end of function
	
	function chkApprovalStatus2($capno,$grp){
		
		$config = new Model_AutoRouting_Config();
		$borrower = new Model_BorrowerAccount();
		$detail= $borrower->fetchRowModel($capno);
		$routetag = $detail->routetag;
		if($grp == 'crecom'){
		$accountTable = new Model_AutoRouting_AccountsCrecom();
		$configTable = new Model_AutoRouting_CrecomConfig();
		$group = 'Crecom';
		$numChairman = $config->getColConfig('crecom_chairman');
		$numMember = $config->getColConfig('crecom_member');

		}else if($grp == 'subcrecom'){
		$accountTable = new Model_AutoRouting_AccountsSubCrecom();
		$configTable = new Model_AutoRouting_SubCrecomConfig();
		$group = 'SubCrecom';
		$numChairman = $config->getColConfig('subcrecom_chairman');
		$numMember = $config->getColConfig('subcrecom_member');
		}
		
		$chkStatus = $group.' - InP';
		$status = $group.' - InP';

		/*************Code Here Best of Three***********************/
		if($accountTable->chkChairmanifDecidedNormal($capno) > 0){
			$approve = 0;
			$disapprove = 0;
			foreach($accountTable->chkMemberDecidedNormal($capno) as $x){
				if($x->decision == 'Approved'){
				$approve++;	
				}else if($x->decision == 'Disapproved'){
				$disapprove++;	
				}
			}//end for each			
			$chairmanDecision = $accountTable->chkChairmanDecisionNormal($capno);
					
			if($chairmanDecision == 'Approved'){
				$approve++;	
			}else if($chairmanDecision == 'Disapproved'){
				$disapprove++;
			}
			/****Tallying the Decisions****/
			$sumDecision = $approve + $disapprove;
			if($sumDecision == 3){
				if($approve > $disapprove){
					if(strpos($routetag,'-CRECOM')!== false){
					$status = $group.' - Ap';
					$decide = 'Approved';
					}
					if(strpos($routetag,'-EXE-BOD')!== false || 
					strpos($routetag,'CRECOM-EXEBOD')!== false){
					$status = $group.' - ENEXEBOD';
					$decide = 'Approved Endorse to BOD';
					}
				}
				else if($disapprove > $approve){
					$status = $group.' - R';
					$decide = 'Rejected';
				}			
			}//end of sumDecision = 3
			
		}
		/*************Code Here Best of Three***********************/
		// BOARD Level Crecom - ENBOARD status???
		


		echo $status;

		//update status if status is not equal to the orignal Status
		if($chkStatus != $status){
			$borrower = new Model_BorrowerAccount();
			$data = array(
			'account_status'=>$status,		
			'date_decided'=>date("r"),
			);
			$where = "capno like '$capno'";
			$borrower->update($data,$where);
			
			$data = array(
			'capno'=>$capno,
			'decision'=>$group.' '.$decide,
			'approved_by'=>$group,
			'reason'=>'',
			'date_approval'=>date("m-d-Y h:i a"),
			'date_approval2'=>date("r"),
			'role'=> '',
			'date_type'=>3,
			);
			
			$crawformapproval = new Model_BorrowerCrawFormApprovalsection();

			$crawformapproval->insert($data);
			
			
			//Insert Account History
				$history = new Model_AccountHistory();
				$select = $history->select();
				$select->where('capno like ?',$capno)->order('id DESC');
				$historyDetail = $history->fetchRow($select);
				$hdata = array (
				'capno'=>$capno,
				'status'=>$status,
				'by'=>login_user(),
				'date'=>date("r"),
				'remarks'=>$group,
				'date_last'=>$historyDetail->date,
				);
				$history->insert($hdata);
			//End of History
			$reportAccounts = Zend_Controller_Action_HelperBroker::getStaticHelper('Accounts');
			$reportAccounts->direct($capno,$status);
		}
	}
}

