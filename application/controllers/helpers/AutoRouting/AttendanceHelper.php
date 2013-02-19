<?php

class Zend_Controller_Action_Helper_AttendanceHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct($username)
	{	
		$absent = false;
		$apatmodel = new Model_AutoRouting_ApproverAttendance();
		if($apatmodel->hasAttendance($username)){
			$isAbsent = $apatmodel->checkDate($username, date("m-d-Y"));
			if($isAbsent){
				$apalmodel = new Model_AutoRouting_ApproveAlternate();
				$apalrow = $apalmodel->getAlternates();
				$isAlternate1 = $apatmodel->checkDate($apalrow->alternate_1, date("m-d-Y"));
				if($isAlternate1){
					$isAlternate2 = $apatmodel->checkDate($apalrow->alternate_2, date("m-d-Y"));
					if($isAlternate2){
						return $absent = true;
					}
				}			
			}
		}
		if($absent){
			//ScoreMatrixHelper();
		}
		
	}

}

