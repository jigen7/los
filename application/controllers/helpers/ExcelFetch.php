<?php

class Zend_Controller_Action_Helper_ExcelFetch extends
                Zend_Controller_Action_Helper_Abstract
{
	/***
	 * Helper for edit account restriction via accoutn status and users
	 * 
	 * 
	 *  
	 */
    function direct($capno)
    {
    }
	
	function getApprove($capno){
		$statusTable = new Model_Admin_AccountStatus();

		$history = new Model_AccountHistory();
		$select = $history->select();
		foreach($statusTable->routeBox('approve') as $x){
		$select->orwhere('status like ?',$x->status);
		}
		$condition = $select->getPart(Zend_Db_Select::WHERE);
		
		$select = $history->select();
		$select->where('capno like ?',$capno);	
		$select->order('id DESC');
		$select->where(arrayString($condition));
		
		$historyDetail = $history->fetchRow($select);					
		
		if($historyDetail){
		$date = date('m-d-Y h:i a',strtotime($historyDetail->date));
		$status = $historyDetail->status;
		return $status.' '.$date;
		}else{
		return 'None Approval Status';
		}
		
		
	}
	
	function getCvFields($capno,$field){
		$table = new Model_BorrowerCv();
		$select = $table->select();
		$select->where("capno like ?",$capno);
		$detail = $table->fetchRow($select);
		
		
		if($field == 'sourceincome'){
			return $detail->srcincomever2;
		}
		if($field == 'background'){
			return $detail->backgrd;
		}
		if($field == 'pastdealing'){
			return $detail->pastdealings;
		}
		if($field == 'bankref'){
			return $detail->bankref;
		}
		
		
		
	}
	
	function getCiFields($capno,$field){
		$table = new Model_BorrowerCi();
		$select = $table->select();
		$select->where("capno like ?",$capno);
		$detail = $table->fetchRow($select);
		
		if($field == 'background'){
			return $detail->backgrd_ci2;
		}
		
		
	}
	
	function getBusEmpStatus($capno){
		
		$table = new Model_BorrowerAccount();
		$select = $table->select();
		$select->where("capno like ?",$capno);
		$detail = $table->fetchRow($select);
		
		return $detail->empbus_status;
	}
	
	function getLenghtStay($capno){
		
		$table = new Model_BorrowerAccount();
		$select = $table->select();
		$select->where("capno like ?",$capno);
		$detail = $table->fetchRow($select);
		return $detail->residence_yrs + (residence_months / 12);
	}	
	
	function getNewBackground($status,$capno,$process){
		
		$table = new Model_CategoryValues2();

		$ci = new Model_BorrowerCi();
			$sql2 = $ci->select()
			->where('capno LIKE ?', $capno);
			$ciDetail = $ci->fetchRow($sql2);
		
		$cv = new Model_BorrowerCv();
			$sql3 = $cv->select()
		     ->where('capno LIKE ?', $capno);
			$cvDetail = $cv->fetchRow($sql3);

		//if ($status == "MA - E"){
		if(login_user_role() == 'MA'){	
			$sql = $table->select()
		     ->where('name LIKE ?', 'BackgroundInvestigation')
			 ->where('values LIKE ?', "Favorable");
			$tableDetail = $table->fetchRow($sql);
			$wt = $tableDetail->wt;
			$values = $tableDetail->values;
		}
		else {
			
			if($cvDetail->model_cv_backgrd){
				//If true
				
			$sql4 = $table->select()
		     ->where('name LIKE ?', 'BackgroundInvestigation')
			 ->where('values LIKE ?', $cvDetail->backgrd);
			$tableDetail = $table->fetchRow($sql4);
			$wt = $tableDetail->wt;
			$values = $tableDetail->values;
		
			}
			else{
				//IF false

			$sql5 = $table->select()
		     ->where('name LIKE ?', 'BackgroundInvestigation')
			 ->where('values LIKE ?', $ciDetail->backgrd_ci2);
			$tableDetail = $table->fetchRow($sql5);
			$wt = $tableDetail->wt;
			$values = $tableDetail->values;

			}


		}
		
		if ($process == 'wt'){
		return $wt;
		}
		else if($process == 'values'){
		return $values;
		}
	
	}

	
function getNeighborHood($capno){
		
		$table = new Model_BorrowerAccount();
		$select = $table->select();
		$select->where("capno like ?",$capno);
		$detail = $table->fetchRow($select);
		return $detail->neighborhoodtype;
	}	
	
	function getEmpFields($capno,$process,$field){
			
			//Determine the Highest Salary among the employments and getNew its Emp Status
			$emp = new Model_BorrowerEmployment();
			$select = $emp->select();
			$select->where('capno like ?',$capno);
			$empdetail = $emp->fetchAll($select);
			
			$sum = 0;

	if($field == 'status'){
			$emp_status = 0;
			foreach($empdetail as $detail){
		    if ($detail->emp_income >= $sum){
		    	$sum = $detail->emp_income;
			    $emp_status = $detail->emp_status;
				}
			/*
			If the Income has Equal!!
			else if($detail->emp_income == $sum){
			}
			*/
			}
			
		$table = new Model_CategoryValues2();
		 $sql = $table->select()
	     ->where('name LIKE ?', 'EmpStatus')
		 ->where('seq = ?', $emp_status);
		$tableDetail = $table->fetchRow($sql);


		if ($process == 'wt'){
		return $tableDetail->wt;
		}
		else if($process == 'values'){
		return $tableDetail->values;
		}
	
	}//end of field= status
	
	if($field == 'position'){
			$emp_status = 0;
			foreach($empdetail as $detail){
		    if ($detail->emp_income >= $sum){
		    	$sum = $detail->emp_income;
			    $emp_pos = $detail->emp_pos;
				}
			/*
			If the Income has Equal!!
			else if($detail->emp_income == $sum){
			}
			*/
			}

		
		$table = new Model_CategoryValues2();
		 $sql = $table->select()
	     ->where('name LIKE ?', 'EmpPosition')
		 ->where('seq = ?', $emp_pos);
		$tableDetail = $table->fetchRow($sql);


		if ($process == 'wt'){
		return $tableDetail->wt;
		}
		else if($process == 'values'){
		return $tableDetail->values;
		}
	
	}//end of field= position
	
	if($field == 'empindustry'){
			$emp_status = 0;
			foreach($empdetail as $detail){
		    if ($detail->emp_income >= $sum){
		    	$sum = $detail->emp_income;
			$emp_industry = $detail->emp_industry;
			}
			/*
			If the Income has Equal!!
			else if($detail->emp_income == $sum){
			}
			*/
			}

		
		$table = new Model_CategoryValues2();
		 $sql = $table->select()
	     ->where('name LIKE ?', 'BusinessNature')
		 ->where('seq = ?', $emp_industry);
		$tableDetail = $table->fetchRow($sql);


		if ($process == 'wt'){
		return $tableDetail->wt;
		}
		else if($process == 'values'){
		return $tableDetail->values;
		}
	
	}//end of field= position
	
	if($field == 'years'){
			$emp_status = 0;
			foreach($empdetail as $detail){
		    if ($detail->emp_income >= $sum){
		    	$sum = $detail->emp_income;
			    $emp_yrs = $detail->emp_yrs + ($detail->emp_months / 12);
				}
			/*
			If the Income has Equal!!
			else if($detail->emp_income == $sum){
			}
			*/
			}
			
		return $emp_yrs;
	
	}//end of field= years
	
	
	}
	
	function getBusField($capno,$process,$field){
		
			$bus = new Model_BorrowerBusiness();
			$select = $bus->select();
			$select->where('capno like ?',$capno);
			$busdetail = $bus->fetchAll($select);
			
			$sum = 0;

		if($field == 'nature'){
			foreach($busdetail as $detail){
				
		    if ($detail->bus_income >= $sum){
		    	$sum = $detail->bus_income;
			    $bus_nat = $detail->bus_nat;
				}
			}//End for Each
			
		$table = new Model_CategoryValues2();
		 $sql = $table->select()
	     ->where('name LIKE ?', 'BusinessNature')
		 ->where('seq = ?', $bus_nat);
		$tableDetail = $table->fetchRow($sql);

		if ($process == 'wt'){
		return $tableDetail->wt;
		}
		else if($process == 'values'){
		return $tableDetail->values;
		}
		}// end of nature of business
		
		if($field == 'sourceincome'){
			foreach($busdetail as $detail){
				
		    if ($detail->bus_income >= $sum){
		    	$sum = $detail->bus_income;
			    $bus_src = $detail->bus_srcincome;
				}
			}//End for Each
			
		$table = new Model_CategoryValues2();
		 $sql = $table->select()
	     ->where('name LIKE ?', 'BusinessSrcIncome')
		 ->where('seq = ?', $bus_src);
		$tableDetail = $table->fetchRow($sql);

		if ($process == 'wt'){
		return $tableDetail->wt;
		}
		else if($process == 'values'){
		return $tableDetail->values;
		}
		}// end of sourceincome of business
		
		if($field == 'busyears'){
			foreach($busdetail as $detail){				
		    if ($detail->bus_income >= $sum){
		    	$sum = $detail->bus_income;
			    $bus_yrs = $detail->bus_yrs + ($detail->bus_months / 12);
				}			
			}//End for Each
			
		$table = new Model_CategoryValues2();
		 $sql = $table->select()
	     ->where('name LIKE ?', 'BusYrs');
		$tableDetail = $table->fetchAll($sql);
		
		foreach($tableDetail as $details) : 
		if (($bus_yrs>= $details->lower_range) && ($bus_yrs < $details->upper_range)){
		$wt = $details->wt;	
		$values = $details->values;		
		}
		endforeach;
		
		if ($process == 'wt'){
		return $wt;
		}
		else if($process == 'values'){
		return $values;
		}
		else if($process == 'busyrs'){
		return $bus_yrs;	
			
		}
			
		}// end of bus years

	
		
		
	}
	

	
	function scoreRate($score,$capno){
		
		$borrower = new Model_BorrowerAccount();
		$emptype = $borrower->getEmpBus($capno);
		
	// Deprecated function see Action Helper March 03,2010
	if ($emptype == "E"){
		if ($score >= 447){
			return "P2";
		}
		else if(($score>=390) && ($score <= 446)){
			return "P1"; 
		}
		else if(($score>=378) && ($score <=389)){
			return "F1";		
		}
		else if(($score>=372) && ($score <=377)){
			return "F2";		
		}
		else if(($score >= 1 ) && ($score<=371)){
			return "F3";		
		}
		else if ($score == 0){
			return "No Score";	
		}
		else if ($score < 0){
			return "Outside Credit Scoring Model Range for Manual Evaluation";	
		}
		
	}
	else if($emptype == "SE"){
		
		if ($score >= 489){
			return "P2";
		}
		else if(($score>=424) && ($score <= 488)){
			return "P1"; 
		}
		else if(($score>=412) && ($score <=423)){
			return "F1";		
		}
		else if(($score>=402) && ($score <=411)){
			return "F2";		
		}
		else if(($score >= 1) && ($score<=401)){
			return "F3";		
		}
		else if ($score == 0){
			return "No Score";	
		}
		else if ($score < 0){
			return "Outside Credit Scoring Model Range for Manual Evaluation";	
		}
		
	}
	else{
		return '';
	}
	
}

}
