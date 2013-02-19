<?php

class Zend_Controller_Action_Helper_AutoRoute extends
                Zend_Controller_Action_Helper_Abstract
{
	
    function direct($capno,$score)
    {
	//Tagging of the application route
	
	
	$table = new Model_BorrowerAccount();
	$select = $table->select();
	$select->where('capno like ?',$capno);
	$detail = $table->fetchRow($select);
	
	if($detail->amountloan2){
	$amountloan = $detail->amountloan2;
	}else {
	$amountloan = $detail->amountloan;		
	}
	//echo $capno.$score;
	//0 - EmpBus Status 1 - Raw Score 2 - Score Equivalent 3 - Deviation (D) 
	$scoreValue = explode(' ',$score);

	if ($scoreValue[3]){
	// with deviation	
		if($scoreValue[2] == 'P1' || $scoreValue[2] == 'P2' || $scoreValue[2] == 'F1'){
			if($amountloan > 0 && $amountloan <= 760000){
			$level = 'A3D-CSH';	
			}
			else if($amountloan > 760000 && $amountloan <= 1000000){
			$level = 'B3D-CMGH';	
			}
			else if($amountloan > 1000000 && $amountloan <= 1250000){
			$level = 'C3D-PRES';	
			}
			else if($amountloan > 1250000 && $amountloan <= 1500000){
			$level = 'D3D-CRECOM-NOSUBCRECOM';	
			}
			else if($amountloan > 1500000 && $amountloan <= 2500000){
			$level = 'E3D-CRECOM';	
			}
			else if($amountloan > 2500000 && $amountloan <= 4000000){
			$level = 'F3D-EXE-BOD';	
			}
			else if($amountloan > 4000000){
			$level = 'G3D-EXE-BOD';	
			}
		}
		else if($scoreValue[2] == 'F2'){
			if($amountloan > 0 && $amountloan <= 760000){
			$level = 'A4D-CMGH';	
			}
			else if($amountloan > 760000 && $amountloan <= 1000000){
			$level = 'B4D-PRES';	
			}
			else if($amountloan > 1000000 && $amountloan <= 1250000){
			$level = 'C4D-CRECOM-NOSUBCRECOM';	
			}
			else if($amountloan > 1250000 && $amountloan <= 1500000){
			$level = 'D4D-CRECOM';	
			}
			else if($amountloan > 1500000 && $amountloan <= 2500000){
			$level = 'E4D-EXE-BOD';	
			}
			else if($amountloan > 2500000 && $amountloan <= 4000000){
			$level = 'F4D-EXE-BOD';	
			}
			else if($amountloan > 4000000){
			$level = 'G4D-EXE-BOD';	
			}
		}//End of F2 without Deviation	
		else if($scoreValue[2] == 'F3'){
			if($amountloan > 0 && $amountloan <= 760000){
			$level = 'A5D-PRES';	
			}
			else if($amountloan > 760000 && $amountloan <= 1000000){
			$level = 'B5D-CRECOM-NOSUBCRECOM';	
			}
			else if($amountloan > 1000000 && $amountloan <= 1250000){
			$level = 'C5D-CRECOM';	
			}
			else if($amountloan > 1250000 && $amountloan <= 1500000){
			$level = 'D5D-EXE-BOD';	
			}
			else if($amountloan > 1500000 && $amountloan <= 2500000){
			$level = 'E5D-EXE-BOD';	
			}
			else if($amountloan > 2500000 && $amountloan <= 4000000){
			$level = 'F5D-EXE-BOD';	
			}
			else if($amountloan > 4000000){
			$level = 'G5D-EXEBOD';	
			}
		}//End of F3 with Deviation
	else if($scoreValue[2] == 'Scoring'){
			//$level = 'Outside';
			if($amountloan > 0 && $amountloan <= 760000){
			$level = 'A6DO-CRECOM-NOSUBCRECOM-Outside';	
			}
			else if($amountloan > 760000 && $amountloan <= 1000000){
			$level = 'B6DO-CRECOM-Outside';	
			}
			else if($amountloan > 1000000 && $amountloan <= 1250000){
			$level = 'C6DO-EXE-BOD-Outside';	
			}
			else if($amountloan > 1250000 && $amountloan <= 1500000){
			$level = 'D6DO-EXE-BOD-Outside';	
			}
			else if($amountloan > 1500000 && $amountloan <= 2500000){
			$level = 'E6DO-EXE-BOD-Outside';	
			}
			else if($amountloan > 2500000 && $amountloan <= 4000000){
			$level = 'F6DO-EXE-BOD-Outside';	
			}
			else if($amountloan > 4000000){
			$level = 'G6DO-EXE-BOD-Outside';	
			}			
	}

	}
	else {
	//without deviation	
		if($scoreValue[2] == 'P2'){
			if($amountloan > 0 && $amountloan <= 912000){
			$level = 'A2-CO';	
			}
			else if($amountloan > 912000 && $amountloan <= 1200000){
			$level = 'B2-CSH';	
			}
			else if($amountloan > 1200000 && $amountloan <= 1500000){
			$level = 'C2-CMGH';	
			}
			else if($amountloan > 1500000 && $amountloan <= 1800000){
			$level = 'D2-PRES';	
			}
			else if($amountloan > 1800000 && $amountloan <= 3000000){
			$level = 'E2-CRECOM-NOSUBCRECOM';	
			}
			else if($amountloan > 3000000 && $amountloan <= 4800000){
			$level = 'F2-CRECOM';	
			}
			else if($amountloan > 4800000){
			$level = 'G2-EXE-BOD';	
			}	
		}// End of P2 with Deviation
		else if($scoreValue[2] == 'P1'){
			if($amountloan > 0 && $amountloan <= 760000){
			$level = 'A1-CO';	
			}
			else if($amountloan > 760000 && $amountloan <= 1000000){
			$level = 'B1-CSH';	
			}
			else if($amountloan > 1000000 && $amountloan <= 1250000){
			$level = 'C1-CMGH';	
			}
			else if($amountloan > 1250000 && $amountloan <= 1500000){
			$level = 'D1-PRES';	
			}
			else if($amountloan > 1500000 && $amountloan <= 2500000){
			$level = 'E1-CRECOM-NOSUBCRECOM';	
			}
			else if($amountloan > 2500000 && $amountloan <= 4000000){
			$level = 'F1-CRECOM';	
			}
			else if($amountloan > 4000000){
			$level = 'G1-EXE-BOD';	
			}
		}// End of P1 without Deviation
		else if($scoreValue[2] == 'F1'){
			if($amountloan > 0 && $amountloan <= 760000){
			$level = 'A3-CSH';	
			}
			else if($amountloan > 760000 && $amountloan <= 1000000){
			$level = 'B3-CMGH';	
			}
			else if($amountloan > 1000000 && $amountloan <= 1250000){
			$level = 'C3-PRES';	
			}
			else if($amountloan > 1250000 && $amountloan <= 1500000){
			$level = 'D3-CRECOM-NOSUBCRECOM';	
			}
			else if($amountloan > 1500000 && $amountloan <= 2500000){
			$level = 'E3-CRECOM';	
			}
			else if($amountloan > 2500000 && $amountloan <= 4000000){
			$level = 'F3-EXE-BOD';	
			}
			else if($amountloan > 4000000){
			$level = 'G3-EXE-BOD';	
			}
		}//End of F1 without Deviation
		else if($scoreValue[2] == 'F2'){
			if($amountloan > 0 && $amountloan <= 760000){
			$level = 'A4-CMGH';	
			}
			else if($amountloan > 760000 && $amountloan <= 1000000){
			$level = 'B4-PRES';	
			}
			else if($amountloan > 1000000 && $amountloan <= 1250000){
			$level = 'C4-CRECOM-NOSUBCRECOM';	
			}
			else if($amountloan > 1250000 && $amountloan <= 1500000){
			$level = 'D4-CRECOM';	
			}
			else if($amountloan > 1500000 && $amountloan <= 2500000){
			$level = 'E4-EXE-BOD';	
			}
			else if($amountloan > 2500000 && $amountloan <= 4000000){
			$level = 'F4-EXE-BOD';	
			}
			else if($amountloan > 4000000){
			$level = 'G4-EXE-BOD';	
			}
		}//End of F2 without Deviation	
		else if($scoreValue[2] == 'F3'){
			if($amountloan > 0 && $amountloan <= 760000){
			$level = 'A5-PRES';	
			}
			else if($amountloan > 760000 && $amountloan <= 1000000){
			$level = 'B5-CRECOM-NOSUBCRECOM';	
			}
			else if($amountloan > 1000000 && $amountloan <= 1250000){
			$level = 'C5-CRECOM';	
			}
			else if($amountloan > 1250000 && $amountloan <= 1500000){
			$level = 'D5-EXE-BOD';	
			}
			else if($amountloan > 1500000 && $amountloan <= 2500000){
			$level = 'E5-EXE-BOD';	
			}
			else if($amountloan > 2500000 && $amountloan <= 4000000){
			$level = 'F5-EXE-BOD';	
			}
			else if($amountloan > 4000000){
			$level = 'G5-EXE-BOD';	
			}
		}//End of F3 without Deviation
	
	}
	
	return $level;
	
	
	}
	
	function presApproval($capno){
	
		$borrower = new Model_BorrowerAccount();
		$route = $borrower->getFieldValue($capno,'routetag');
		
		if(strpos($route,'PRES') === false){
		}else{
		return 'PRES - Ap';	
		}

		if(strpos($route,'-EXE-BOD') === false){
		}else{
		return 'PRES - EXEBOD - Ap';	
		}
		
		if(strpos($route,'-CRECOM') === false){
		}else{
		return 'PRES - CRECOM - Ap';	
		}
		
		if(strpos($route,'-CRECOM-NOSUBCRECOM') === false){
		}else{
		return 'PRES - SUBCRECOM - Ap';	
		}
		
	}
	
	function presDisapproval($capno){
		
		$borrower = new Model_BorrowerAccount();
		$route = $borrower->getFieldValue($capno,'routetag');
		
		if(strpos($route,'PRES') === false){
		}else{
		return 'PRES - R';	
		}

		if(strpos($route,'-EXE-BOD') === false){
		}else{
		return 'PRES - EXEBOD - R';	
		}
		
		if(strpos($route,'-CRECOM') === false){
		}else{
		return 'PRES - CRECOM - R';	
		}
		
		if(strpos($route,'-CRECOM-NOSUBCRECOM') === false){
		}else{
		return 'PRES - SUBCRECOM - R';	
		}
	}
	

}

	