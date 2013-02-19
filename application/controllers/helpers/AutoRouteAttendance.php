<?php

class Zend_Controller_Action_Helper_AutoRouteAttendance extends
                Zend_Controller_Action_Helper_Abstract
{
    function direct($route)
    {
	//Check the availability of the next user if the approves/recommending is absent
		$user = Zend_Auth::getInstance()->getIdentity();
	    $userRole = $user->role_type;
		//check if it is outside
			if(strpos($route,'OUTSIDE') === false){
				
			}else {
				$dev = '-OUTSIDE';
			}
		 $pos = strpos($route,'-');
  		 $cut = substr($route,0,$pos);
		
		$userAttendance = new Model_UsersTimeInfo();
		$approvers = new Model_Users();

	if($userRole == 'CO'){
		// if CSH Level of approval
		$approver = $approvers->returnUsernamebyRole('CSH');
		$nextapprover = $userAttendance->chkAbsent($approver);
		 if(strpos($route,'CSH')=== false){
			return $route;
		 }else {
  		 //found CSH 
		 //check availaibility
		 //echo $approvers->returnUsernamebyRole('CSH');

		 	if($nextapprover == 'present'){
		 		return $route;
		  	}
			else if ($nextapprover == ''){
				//no alternative declared
				return 'AB-CMGH'.$dev;
			}
			else {
				// has given a alternative approver
		 		return 'AB-'.$nextapprover.$dev;
		 	}

		 }
	} // end of CO
	
	if($userRole == 'CSH'){	
 		$newroute = $route;

	    if(strpos($route,'CMGH')!== false){
  		 //found CMGH 
		 //check availaibility
		 //echo $approvers->returnUsernamebyRole('CSH');
		 $approver = $approvers->returnUsernamebyRole('CMGH');
		 $nextapprover = $userAttendance->chkAbsent($approver);
		 	if($nextapprover == 'present'){
		 		$newroute = $route;
		  	}
			else if ($nextapprover == ''){
				//no alternative declared
				$newroute = 'AB-PRES'.$dev;
			}
			else {
				// has given a alternative approver
		 		$newroute = 'AB-'.$nextapprover.$dev;
		 	}
		 }
 
		 if(strpos($route,'PRES')!== false){
		 $approver = $approvers->returnUsernamebyRole('PRES');
		 $nextapprover = $userAttendance->chkAbsent($approver);
		 	if($nextapprover == 'present'){
		 		$newroute = $route;
		  	}
			else if ($nextapprover == ''){
				//no alternative declared
				// Add code if president is absent and no alternative is declared
				
			}
			else {
				// has given a alternative approver
		 		$newroute = 'AB-'.$nextapprover.$dev;
		 		
		 	}			//mr chua approved
		 }
		 return $newroute;
	}//End of CSH
	
		if($userRole == 'CMGH'){	
		return $route;
		}
		if($userRole == 'PRES'){	
		return $route;
		}
		if($userRole == 'ALMH'){	
		return $route;
		}

	
	}
	
}
?>