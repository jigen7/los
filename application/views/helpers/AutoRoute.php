<?php
class Zend_View_Helper_AutoRoute extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}

	function autoRoute($capno){
		//Helper for Borrower View use in CO Credit Decision that will return true or false if wihtin his range 
		// Also use in the autorouting procedure to get if within his range

		//Takes in the highest capno pass in the view
		
		//Fetch the Main Borrower
		$accnt = new Model_BorrowerAccount();
		//Fetch the Main Borrower
		$accntdetail = $accnt->fetchRowModel(capnosep($capno).'0'.capnorecon($capno));
		//Fetch the Highest Capno
		$modeldetail = $accnt->fetchRowModel($capno);		
		
		if($accntdetail->routetag){
			// routetag exist suppose to be in autorouting
			$routetag =  $accntdetail->routetag;
		} 
		else {
            // if it does not exist	not yet submitted by CO so route is not stamp	
			// upon implementation route does not exist
			$scoretag = $this->_view->viewScore($accntdetail->score,$modeldetail->capno,'view');
			$autoroute = Zend_Controller_Action_HelperBroker::getStaticHelper('AutoRoute');
			$routetag = $autoroute->direct($accntdetail->capno,$scoretag);
		}
		
		$user = Zend_Auth::getInstance()->getIdentity();
	    $userRole = '-'.$user->role_type;
		
		if($user->role_type == 'PRES'){
		
			if(strpos($routetag,'-PRES') === false){
			$counter = $counter+0;	
			}else {
			$counter = $counter+1;
			}
		
			if(strpos($routetag,'-CMGH') === false){
			$counter = $counter+0;	
			}else {
			$counter = $counter+1;
			}
			
			if(strpos($routetag,'-EXE-BOD') === false){
			$counter = $counter+0;	
			}else {
			$counter = $counter+0;
			}			
			
			if(strpos($routetag,'-CRECOM') === false){
			$counter = $counter+0;	
			}else {
			$counter = $counter+1;
			}
			
			if(strpos($routetag,'-SUBCRECOM') === false){
			$counter = $counter+0;	
			}else {
			$counter = $counter+1;
			}
			
			if($counter >= 1){
				return true;
			}else{
				return false;
			}

		}
		
		else if($user->role_type == 'CORPSEC'){
			
			if(strpos($routetag,'-EXE-BOD') === false){
			$counter = $counter+0;	
			}else {
			$counter = $counter+1;
			}			
			
			if($counter >= 1){
				return true;
			}else{
				return false;
			}

		}
		
		else if($user->role_type == 'CMGH'){
			//will pass and go to PRES
				return false;
		}
		else {
			//CO & CSH
		// Check if the string contains the current approval base on the
		// routeTag
		if(strpos($routetag,$userRole) === false){
		return false;	
		}else {
		return true;
		}
		
		}//end of else 
		
		
/*

		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		$select->where('capno like ?',$capno);
		$accntdetail = $accnt->fetchRow($select);
		
		$deviation =  $accntdetail->deviation;
		$loanAmount = $accntdetail->amountloan;

		$scoretag = Zend_Controller_Action_HelperBroker::getStaticHelper('scoreTag');
		$score = $scoretag->direct($accntdetail->score,$accntdetail->empbus_status);
	
	
	if($userRole == 'CO'){	

		if(!$deviation){
		if (($score == 'P1') && ($loanAmount <= 760000)){
		return true;
		}
		else if (($score == 'P2') && ($loanAmount <= 912000)){
		return true;
		} 
	
		else {
		return false;
		}
		}// end of Deviation
		else {
		//if the application has deviation pass to the next level as 
		//CO cant approve accoutns with Deviation
		return false;
		}

	}
	if($userRole == 'CSH'){	
	
	if(!$deviation){
		//Without Deviation
		if (($score == 'P1') && ($loanAmount <= 1000000)){
		return true;
		}
		else if (($score == 'P2') && ($loanAmount <= 1200000)){
		return true;
		}
		else if (($score == 'F1') && ($loanAmount <= 760000)){
		return true;
		} 
		else {
		return false;
		}
	} // End of Without Deviation
	else {
		if (($score == 'P1') && ($loanAmount <= 760000)){
		return true;
		}
		else if (($score == 'P2') && ($loanAmount <= 912000)){
		return true;
		} 
		else if (($score == 'F1') && ($loanAmount <= 760000)){
		return true;
		} 
		else {
		return false;	
		}
	}
	
	}// End of CSH
	
	if($userRole == 'CMGH'){	
	
		if(!$deviation){
			//Without Deviation
			if (($score == 'P1') && ($loanAmount <= 1250000)){
			return true;
			}
			else if (($score == 'P2') && ($loanAmount <= 1500000)){
			return true;
			}
			else if (($score == 'F1') && ($loanAmount <= 1000000)){
			return true;
			} 
			else if (($score == 'F2') && ($loanAmount <= 760000)){
			return true;
			} 
			else {
			return false;
			}
			
		}
		else{
			//With Deviation
			if (($score == 'P1') && ($loanAmount <= 1000000)){
			return true;
			}
			else if (($score == 'P2') && ($loanAmount <= 1200000)){
			return true;
			}
			else if (($score == 'F1') && ($loanAmount <= 1000000)){
			return true;
			}
			else if (($score == 'F2') && ($loanAmount <= 760000)){
			return true;
			} 			 
			else {
			return false;
			}
			
		}

		}//End of CMHG
	if($userRole == 'PRES'){		
		if(!$deviation){
			//Without Deviation
			if (($score == 'P1') && ($loanAmount <= 1500000)){
			return true;
			}
			else if (($score == 'P2') && ($loanAmount <= 1800000)){
			return true;
			}
			else if (($score == 'F1') && ($loanAmount <= 1250000)){
			return true;
			} 
			else if (($score == 'F2') && ($loanAmount <= 1000000)){
			return true;
			} 
			else if (($score == 'F3') && ($loanAmount <= 760000)){
			return true;
			} 
			else {
			return false;
			}
			
			
		}
		
		else {
			//With Deviation
			if (($score == 'P1') && ($loanAmount <= 1250000)){
			return true;
			}
			else if (($score == 'P2') && ($loanAmount <= 1500000)){
			return true;
			}
			else if (($score == 'F1') && ($loanAmount <= 1250000)){
			return true;
			} 
			else if (($score == 'F2') && ($loanAmount <= 1000000)){
			return true;
			} 
			else if (($score == 'F3') && ($loanAmount <= 760000)){
			return true;
			} 
		
		}
		
		} 
		*/
	}
	
}






?>