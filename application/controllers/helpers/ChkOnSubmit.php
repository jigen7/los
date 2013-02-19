<?php

class Zend_Controller_Action_Helper_ChkOnSubmit extends
                Zend_Controller_Action_Helper_Abstract
{
    function direct($capno)
    {
    	/**
    	 * Paolo Marco M. Manarang 
    	 * March 18,2010 
    	 * Helper to check needed files when submitting an applciation to ca , to co
    	 * 
    	**/
		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		$select->where('capno like ?',$capno);
		$accntdetail = $accnt->fetchRow($select);
		
		if(login_user_role() == 'CA'){
		
			$highemp = getHighest($capno);
			$cvtable = new Model_BorrowerCv();
			// If CA
			$counter = 0;
			if(!$cvtable->ifCV($highemp)){
				echo 'Credit Verification need for '.$accnt->getRelation($highemp).' '.$accnt->getBorrowerName($highemp);
				$counter++;
			}
			
			if($accnt->getComaker($capno)){
				$comaker_capno = $accnt->getComaker($capno);
				$comakerDetail = $accnt->fetchRowModel($comaker_capno);
				$comaker_status = $comakerDetail->comaker_accnt_status;
				
				
				if($comaker_status != 'CA - CMK' && $comaker_status != 'CA - S - CMK'){
				echo "<br>Please Complete CoMaker Information";
				$counter++;	
				}
				
				if($comaker_status == 'CA - CMK' && $comaker_status != 'CA - S - CMK'){
				echo "<br>Please Submit CoMaker Information";
				$counter++;	
				}
			}
			

			
			if($accntdetail->account_status2 == 'RECON'){
				
				if(!$accntdetail->recon_remarks_ca){
				echo "<br>Recon Application - Please Provide Recommendation in the Recon Form";	
				$counter++;	
					
				}
				
				if($accntdetail->recon_type == 'eydi'){
				$reconModule = Zend_Controller_Action_HelperBroker::getStaticHelper('ReconModule');
				//echo $reconModule->chkchanges($capno);
				if($reconModule->chkchangesEyDi($capno) == 0){
				echo "<br>Recon Application - No Changes from the previous application check E.Y. & D.I.";	
				$counter++;	
				}
			}
			}
			
		}// end of CA
		
		if(login_user_role() == 'MA'){
		
			
			if($accnt->getComaker($capno)){
				$comaker_capno = $accnt->getComaker($capno);
				$comakerDetail = $accnt->fetchRowModel($comaker_capno);
				$comaker_status = $comakerDetail->comaker_accnt_status;
				
				
				if($comaker_status != 'MA - CMK' && $comaker_status != 'MA - S - CMK'){
				echo "<br>Please Complete CoMaker Information";
				$counter++;	
				}
				
				if($comaker_status == 'MA - CMK' && $comaker_status != 'MA - S - CMK'){
				echo "<br>Please Submit CoMaker Information";
				$counter++;	
				}
			}
			
			// If the total combined income is zero then require for co-maker / coborrower
		if($accntdetail->account_status2 != 'RECON'){
		if(totalcombinedincome($capno) == 0){
				echo "<br>Total Combined Income is P0.00 Please include Coborrower";
				$counter++;			
		}	}
		
		if($accntdetail->account_status2 == 'RECON'){

			if($accntdetail->recon_type == 'normal'){
			$reconModule = Zend_Controller_Action_HelperBroker::getStaticHelper('ReconModule');
			//echo $reconModule->chkchanges($capno);
				if($reconModule->chkchanges($capno) != $reconModule->countreconField($capno)){
				echo "<br>Recon Application - Some Field/s do not change based from the previous application";	
				$counter++;	
				}
		}
		/**Count Spouse and Coborrower **/	
		/*
		$countSp = $accnt->countSpouse($capno);
		$countCo = 	$accnt->countCoborrower($capno);
		
		if((($countSpCo > 0) || ($accntdetail->civilstatus == '2') || ($accntdetail->civilstatus == '3') || ($accntdetail->civilstatus == '4') || ($accntdetail->civilstatus == '5')) && (totalcombinedincome($capno) < 50000)){
		
			if($accntdetail->gmi_ratio >30){
			
				if($countCo == 0){
				echo "<br>For Married Total Combined Income should be at least P50,000 and GMI must not be > 30%";
				echo "<br>Please Add a Co-Maker/Coborrower";
				$counter++;		
					
				}else if ($countCo > 0){
				echo "<br>For Married Total Combined Income should be at least P50,000 and GMI must not be > 30%";
				echo "<br>Please Secure a Co-Maker/Coborrower that will meet the total income requirement";
				$counter++;									
				}
			}
		}
		else if (($countSpCo == 0) && (totalcombinedincome($capno) < 30000)){
			if($accntdetail->gmi_ratio >30){
				
				if($countCo == 0){
				echo "<br>For Single Total Combined Income should be at least P30,000 and GMI must not be > 30%";
				echo "<br>Please Add a Co-Maker/Coborrower";
				$counter++;		
					
				}else if ($countCo > 0){
				echo "<br>For Single Total Combined Income should be at least P30,000 and GMI must not be > 30%";
				echo "<br>Please Secure a Co-Maker/Coborrower that will meet the total income requirement";
				$counter++;									
				}

			}
		}
		*/
		

				/*
			if($reconModule->chkchangesField($capno) == 0){
				echo "<br>Recon Application - Please Change the values of the designated fields";
				$arrayFields = explode(',',$accntdetail->recon_fields);	
				foreach ($arrayFields as $x){
				echo " ".viewFieldsRecon($x).", ";
        		}// end of for each 
				$counter++;	
				} */	
			}

		}// end of MA
			
			
			if($counter > 0){
				exit;
			}

	}
	
}




