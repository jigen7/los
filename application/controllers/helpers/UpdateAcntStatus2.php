<?php

class Zend_Controller_Action_Helper_UpdateAcntStatus extends
                Zend_Controller_Action_Helper_Abstract
{
	
	/**
	 * Paolo Marco Manarang
	 * paolomanarang@gmail.com
	 * March 03,2010
	 * Change the Account Status bese on the MA - CA - CO interaction
	 */
    function direct($capno,$process)
    {
		    $action = $this->getRequest()->getActionName();

			$account = new Model_BorrowerAccount();
			$select = $account->select();
			$select->where('capno like ?',$capno);
			$accntdetail = $account->fetchRow($select);
	
			$where = "capno like '$capno'";
			$login_user = login_user_role();
			$user = login_user();
			$counter =0;

			/*if(($login_user == "MA" ) && ($process == "") &&
			($accntdetail->account_status == "MA - EN")){
				$counter = 1;
				$account_status = "MA - E";
			}*/
			
			if(($login_user == "CA" ) && ($process == "") &&
			($accntdetail->account_status == "MA - S")){
				if($accntdetail->submitted_ca == $user){
				$counter = 1;
				$account_status = "CA - An";
				$data = array(
				'account_status'=>$account_status,
				'submitted_ca_date_view'=>date("r"),
				);
				}
		
			}
			
			else if(($login_user == "CA" ) && ($process == "save") &&
			(($accntdetail->account_status == "CA - An") || ($accntdetail->account_status == "CO - RTCA"))){

				$counter = 1;
				$account_status = "CA - AnD";
				$data = array(
				'account_status'=>$account_status,
				'submitted_ca_date_save'=>date("r"),
				);

			}
			
			
			else if(($login_user == "CO" ) && ($process == "edit")){ 
			
				if(($accntdetail->account_status == "CA - ReAp") ||
				($accntdetail->account_status == "CA - ReR") || 
				($accntdetail->account_status == "CSH - RTCO") || 
				($accntdetail->account_status == "CA - P"))		
				{
				$counter = 1;
				$account_status = "CO - REV";
				$data = array(
				'account_status'=>$account_status,
				'submitted_co_date_view'=>date("r"),
				);
				}
			}
			
			else if(($login_user == "CO" ) && ($process == "save") &&
			($accntdetail->account_status == "CO - REV")){

				$counter = 1;
				$account_status = "CO - REVD";
				$data = array(
				'account_status'=>$account_status,
				'submitted_co_date_save'=>date("r"),
				);

		
			}			
			
			if($counter == 1){
			//If counter is true update the account status

			$select->where('capno like ?',$capno);
			$account->update($data,$where);
			
			$accountstatus = new Model_AccountHistory();
			$select = $accountstatus->select();
			$select->where('capno like ?',$capno)->order('id DESC');
			$historyDetail = $accountstatus->fetchRow($select);
			$data = array(
			'capno' =>$capno,
			'status' =>$account_status,
			//'remarks' => $form->getValue('submit_remarks'),
			'date'=>date("r"),
			'by'=>  login_user(),
			'date_last'=>$historyDetail->date,
			);
			$accountstatus->insert($data);
			}
	}
	
	function booking($process){
		
		if($process == 'MA - PreB' || $process == 'LO - RTLA' || $process == 'LA - Recall'){
			return 'LA - ChkDoc';
		}
		else if($process == 'LA - ChkDoc'){
			return 'Booked';
		}
		else if($process == 'Booked'){
			return 'LA - Recall';
		}		
		else {
			return '';
		}
		
	}
	

}

