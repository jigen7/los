<?php
/**
 * Paolo Marco M. Manarang 
 * March 12,2010
 * Action Helper for the Scoring Module on How the Application should be scored
 * 
 */
class Zend_Controller_Action_Helper_ScoreModule2 extends Zend_Controller_Action_Helper_Abstract
{
    function direct($capno,$mode)
    {
		$scoretag = Zend_Controller_Action_HelperBroker::getStaticHelper('ScoreTag');		

		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		$select->where('capno like ?',$capno);
		$accntdetail = $accnt->fetchRow($select);
		$user = Zend_Auth::getInstance()->getIdentity();
	
	/*
		$ldRecon = new Model_Booking_BookingAutoRecon();
	
	
		$ldDetail = $ldRecon->fetchRowModel($capno);
	if($ldDetail){
		$accntdetail->loanterm = $ldDetail->term;
		$accntdetail->downpayment_percent = $ldDetail->downpayment_percent;
		$accntdetail->gmi_ratio = $ldDetail->gmi;
		$accntdetail->veh_brand = $ldDetail->veh_brand;
		$accntdetail->veh_type = $ldDetail->veh_type;
	}
	*/
	//Check if the deviation can be scored
	$countDevi = 0;
	if(($accntdetail->loanterm < .5) || ($accntdetail->loanterm > 60)){
	//echo "Loan Term Exceeded Boundary .5 months - 60 years: ".$accntdetail->loanterm."<br>";
	$countDevi++;
	}
	
	if($accntdetail->downpayment_percent < 15){
	//echo "Downpayment Exceeded Low Boundary < 15%: ".$accntdetail->downpayment_percent."<br>";
	$countDevi++;
	}
	 
	if($accntdetail->gmi_ratio > 45){
	//echo "GMI Exceeded High Boundary > 45%: ".$accntdetail->gmi_ratio."<br>";
	$countDevi++;
	}
	
	if($accntdetail->veh_age > 5){
	//echo "Vehicle Age Exceeded High Boundary > 5 Years: ".$accntdetail->veh_age."<br>";
	$countDevi++;
	}
	
	if($accntdetail->veh_status == 3){
	//Imported Brand New Car 
	$countDevi++;
	}
	
	if($accntdetail->veh_status == 4){
	//Imported 2nd Hand
	$countDevi++;
	}
	
	if($accntdetail->account_type == 1){
	// Corporation 
	$countDevi++;
	}
	
	if($accntdetail->veh_loan_type == 1){
	// Fleet Accounts 
	$countDevi++;
	}
	

	//echo "Loan Term";
	//echo $loan_term_wt;
	//echo "Veh Status ";
	//echo $veh_status_wt;
	//echo "Veh Type ";
	//echo $veh_type_wt;
	//echo "Veh Brand";
	//	echo $veh_brand_wt;
	//echo "Downpayment ";
	//echo $downpayment_wt;
	
	//echo "Cap Basis ";
	$cap_basis = getHighest($capno);
	//echo $cap_basis;
	
	$select = $accnt->select(); // Reset the Selection of the Database
	$select->where('capno like ?',$cap_basis);
	$accntbasisdetail = $accnt->fetchRow($select);
	
	$lengh_stay_wt = getNewLenghtStay($accntbasisdetail->residence_yrs);
	//echo "Length Stay ";
	//echo $lengh_stay_wt;
	$neighbor_type_wt = getNewNeighborType($accntbasisdetail->neighborhoodtype,'wt'); 
	//echo "Neighbor Type ";
	//echo $neighbor_type_wt;
	
	
	if ($accntbasisdetail->empbus_status == "E"){
		$emp_status_wt = getNewEmpStatuswt($cap_basis,'wt');
	}
	else if ($accntbasisdetail->empbus_status == "SE"){
		
	if($accntdetail->account_type != 1){	
		$bus_yrs_wt = getNewBusYrs($cap_basis,'wt');
		$bus_nat_wt = getNewBusNat($cap_basis,'wt');
		
		$bus_values = getNewBusYrs($cap_basis,'busyrs');
		
		
		if($bus_values < 2){
		echo "<br>Business Years Exceeded Low Boundary < 2 Years: ".$bus_values."<br>";
		$countDevi++;
		}
	}// End of Not Corporation
		
	}
	else if($accntbasisdetail->empbus_status == "O"){
		echo "Other Source of Income";
		$countDevi++;		
	}
	
	//echo "Emp Status";
	//echo $emp_status_wt;
	
//	echo "Bus Yrs";
//	echo $bus_yrs_wt;
	
//	echo "Bus Nat";
	//echo $bus_nat_wt;
	if(totalcombinedincome($capno) < 30000){
		echo "Total Combined is less 30K";
		$countDevi++;	
	}
	
	
	$totalcombine_wt = getNewTotalCombineIncome(totalcombinedincome($capno));
	//echo "totalcombine";
	//echo $totalcombine_wt;
	
	
	$background_wt = getNewBackground($accntdetail->account_status,$cap_basis,'wt');

	//	echo "background";
	//echo $background_wt;
	
	$loan_term_wt = getNewLoanTerm($accntdetail->loanterm);

	if($accntdetail->veh_loan_type == 1){
		$countDeviFleet = 0;
		//score indiv vehicle if fleet
		$vehModel = new Model_Vehicles();
		$select = $vehModel->select();
		$select->where("capno like ?",$capno);
		$vehDetail = $vehModel->fetchAll($select);

		foreach($vehDetail as $x){
			$veh_type_wt = getNewVehType($x->veh_type,'wt');
			$veh_status_wt = getNewVehStatus($x->veh_status,'wt');
			$veh_brand_wt = getNewVehBrand($x->veh_brand,'wt');
			$downpayment_wt = getNewDownpayment($x->downpayment_percent);

		/******************OCS Value Fleet********************/
			if($x->downpayment_percent < 15){
			//echo "Downpayment Exceeded Low Boundary < 15%: ".$accntdetail->downpayment_percent."<br>";
			$countDeviFleet++;
			}
		
			if($x->veh_age > 5){
			//echo "Vehicle Age Exceeded High Boundary > 5 Years: ".$accntdetail->veh_age."<br>";
			$countDeviFleet++;
			}
			
			if($x->veh_status == 3){
			//Imported Brand New Car 
			$countDeviFleet++;
			}
			
			if($x->veh_status == 4){
			//Imported 2nd Hand
			$countDeviFleet++;
			}
		
		
		/******************End of OCS Fleet Value***********/
		
			$sum = $loan_term_wt + $veh_status_wt + $veh_type_wt + $veh_brand_wt
	+ $downpayment_wt + $lengh_stay_wt + $neighbor_type_wt + $emp_status_wt
	+ $bus_yrs_wt + $bus_nat_wt + $totalcombine_wt + $background_wt;
	
		//To get if OCS is the score
		if(($countDevi+$countDeviFleet) > 0){
			$tempSum = $sum;
			$sum = -9999;	
			
			/*		
			$sumArr = array();
			$sumArr[] = $tempSum;
			$sumArr[] = "OCS";
			$sum = $sumArr;	
			*/		
		}
		
		$scoretag = Zend_Controller_Action_HelperBroker::getStaticHelper('ScoreTag');
		// To get if P1,P2,F1,F2,F3
		$scoreR = $scoretag->direct($sum, $accntbasisdetail->empbus_status,$capno);
		
		/* Return to single value from array uncomment	
		$sum = $sumArr[0];
		*/
		if($sum >= 0){

			$data = array(
			'score_tag'=>$accntbasisdetail->empbus_status.' '.$sum.' '.$scoreR." ".$sumArr[1],
			);
		}else{
			//for ocs
			echo "Y";
			$data = array(
			'score_tag'=>$scoreR,
			);
		}	
			$where = "id = ".$x->id;
			$vehModel->update($data,$where);
		}
	}
	else {
		$downpayment_wt = getNewDownpayment($accntdetail->downpayment_percent);
		$veh_type_wt = getNewVehType($accntdetail->veh_type,'wt');
		$veh_status_wt = getNewVehStatus($accntdetail->veh_status,'wt');
		$veh_brand_wt = getNewVehBrand($accntdetail->veh_brand,'wt');
	}
	
	
	$sum = $loan_term_wt + $veh_status_wt + $veh_type_wt + $veh_brand_wt
	+ $downpayment_wt + $lengh_stay_wt + $neighbor_type_wt + $emp_status_wt
	+ $bus_yrs_wt + $bus_nat_wt + $totalcombine_wt + $background_wt;

	// Create new PDF 
	$pdf = new Zend_Pdf(); 
	// Add new page to the document 
	$page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4); 
	$pdf->pages[] = $page; 
	// Set font 
	$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10); 
	// Draw text 
	$page->drawText('Score Breakdown', 30, 800);
	$page->drawText('Cap No : '.$capno, 30, 780);
	$page->drawText('Principal Borrower  : '.$accntdetail->borrower_lname.', '.$accntdetail->borrower_fname.' '.$accntdetail->borrower_mname, 30, 770);
	
	$page->drawText('Loan Term  : '.$accntdetail->loanterm.'months wt : '.$loan_term_wt, 30, 750);
	$page->drawText('Downpayment %  : '.$accntdetail->downpayment_percent.'% wt : '.$downpayment_wt, 30, 740);
	$page->drawText('Vehicle Type   : '.getNewVehType($accntdetail->veh_type,'values').' wt : '.$veh_type_wt, 30, 730);
	$page->drawText('Vehicle Status   : '.getNewVehStatus($accntdetail->veh_status,'values').' wt : '.$veh_status_wt, 30, 720);
	$page->drawText('Vehicle Brand   : '.getNewVehBrand($accntdetail->veh_brand,'values').' wt : '.$veh_brand_wt, 30, 710);

	$page->drawText('Model Basis   : '.$cap_basis, 30, 690);
	$page->drawText('Name  : '.$accntbasisdetail->borrower_lname.', '.$accntbasisdetail->borrower_fname.' '.$accntbasisdetail->borrower_mname, 30, 680);
	$page->drawText('Relation   : '.$accntbasisdetail->relation, 30, 670);
	$page->drawText('Employment / Business   : '.$accntbasisdetail->empbus_status, 30, 660);

	$page->drawText('Lenght Stay   : '.$accntbasisdetail->residence_yrs.'years /'.$accntbasisdetail->residence_months.'mos. wt : '.$lengh_stay_wt, 30, 650);
	$page->drawText('Neighbor Type   : '.getNewNeighborType($accntbasisdetail->neighborhoodtype,'values').' wt : '.$neighbor_type_wt, 30, 640);

	if ($accntbasisdetail->empbus_status == "E"){
	$page->drawText('Employment Status   : '.getNewEmpStatuswt($cap_basis,'values').' wt : '.$emp_status_wt, 30, 620);
	}else if($accntbasisdetail->empbus_status == "SE"){
		if($accntdetail->account_type != 1){	
	$page->drawText('Business Years   : '.getNewBusYrs($cap_basis,'values').' wt : '.$bus_yrs_wt, 30, 620);
	$page->drawText('Business Nature   : '.getNewBusNat($cap_basis,'values').' wt : '.$bus_nat_wt, 30, 610);
		}//End of Corporation
	}
	$page->drawText('Total Combine Income   : '.totalcombinedincome($capno).' wt : '.$totalcombine_wt, 30, 590);
	$page->drawText('Background   : '.getNewBackground($accntdetail->account_status,$cap_basis,'values').' wt : '.$background_wt, 30, 580);
 
	$page->drawText('__________________', 30, 560);
	$page->drawText('Sum : '.$accntbasisdetail->empbus_status.' '.$sum.' '.$scoretag->direct($sum,$accntbasisdetail->empbus_status,$accntbasisdetail->capno), 30, 550);
	$page->drawText('Date : '.date('m/d/Y h:i:s'), 30, 530);
	$page->drawText('Generated By : '.viewNewName($user->username), 30, 520);
	//$pdf->save('D:\cscore\breakdown-'.$accntdetail->capno.'-'.$accntdetail->borrower_lname.'-'.$user->role_type.'-'.date('his').'.pdf'); 
	$pdf->save('C:\cscore'.'\/'.$accntdetail->borrower_lname.'-'.$accntdetail->capno.'-'.$user->role_type.'-'.date('his').'-MD02.pdf'); 

	//echo "Total Score: ".$sum;
	
	if($countDevi > 0){
	$tempSum = $sum;	
	
		if($mode == 'ocs-off'){
			$sumArr = array();
			$sumArr[] = $tempSum;
			$sumArr[] = "OCS";
			return $sumArr;			
		}else {
			$sum = -9999;
			return $sum;		
		}
	
	}
	else {
	//	echo "Sum Overalln ".$sum;

	return $sum;
	}
	} // end of direct
	
	
	function storeattr($capno,$type){
		
		$table = new Model_BorrowerAccount();
		$select = $table->select();
		$select->where("capno like ?",$capno);
		$detail = $table->fetchRow($select)->toArray();
		
		$QueryString = '';
		$attrArr = array();
		foreach ($detail as $Key => $Value){
        //$QueryString .= $Amp . $Key . '=' . $Value.';';
		$attrArr[$Key] = $Value;
		}

		//echo "<br><br>";
		//preint_r($attrArr);
		
		$cap_basis = getHighest($capno);
		$detailBasis = $table->fetchRowModel($cap_basis)->toArray();
	
		//echo "<br><br>";
		//preint_r($detailBasis);
	
		foreach ($detailBasis as $Key => $Value){
		
			if(($Key == 'residence_yrs') || 
			($Key == 'neighborhoodtype') ||
			($Key == 'empbus_status') )
			{
			$attrArr[$Key] = $Value;		
			}
		}
		echo "<br><br>";
		//preint_r($attrArr);
		
		if($attrArr['empbus_status'] == "SE"){
			$arrbusfield = array('id','max(bus_income)');

			$bus = new Model_BorrowerBusiness();
			$select = $bus->select();
			$select->where('capno like ?',$cap_basis)
			->from($bus,$arrbusfield)->group('id')->order("id ASC");
			$busdetail = $bus->fetchRow($select);
			
			$select = $bus->select();
			$select->where('id = ?',$busdetail->id);
			$busRow = $bus->fetchRow($select)->toArray();
			
			foreach ($busRow as $Key => $Value){
	        //$QueryString .= $Amp . $Key . '=' . $Value.';';
				if($Key != 'capno'){
					$attrArr[$Key] = $Value;
				}
			}
			
		}else if($attrArr['empbus_status'] == "E"){
			$arrempfield = array('id','max(emp_income)');
			$emp = new Model_BorrowerEmployment();
			$select = $emp->select();
			$select->where('capno like ?',$cap_basis)
			->where('employer like ?','Current')->from($emp,$arrempfield)
			->group('id')->order("id ASC");
			$empdetail = $emp->fetchRow($select);
			//echo $select;
					//preint_r($empdetail);
			/*
			$select->where('capno like ?',$cap_basis);
			$empdetail = $emp->fetchAll($select);
			
			$sum = 0;
			$emp_id = 0;
			
			foreach($empdetail as $detail){
				
		    if ($detail->emp_income > $sum){
		    	$sum = $detail->emp_income;
			    $emp_id = $detail->id;
			}
			*/	
		
			$select = $emp->select();
			$select->where('id = ?',$empdetail->id);
			$empRow = $emp->fetchRow($select)->toArray();
			
			foreach ($empRow as $Key => $Value){
	        //$QueryString .= $Amp . $Key . '=' . $Value.';';
				if($Key != 'capno'){
					$attrArr[$Key] = $Value;
				}
			}
		
			
		
		}
		$attrArr['combine_income']= totalcombinedincome($capno);
		$attrArr['background']= getNewBackground('',$cap_basis,'values');
		//echo $capno.'-'.$cap_basis.'<br>';
		$attrArr['capbasis'] = $cap_basis;
		$attrArr['residence_yrs'] = $attrArr['residence_yrs'] + ($attrArr['residence_months'] / 12);

		foreach ($attrArr as $Key => $Value){
        $QueryString .= $Amp . $Key . '=' . $Value.'&';
		}
		//echo $QueryString;		 
		
		if($type == 'single'){
		$scoreattr = new Model_CreditScoreAttr();
		$data = array(		
		'capno' => $capno,
		'attributes'=>$QueryString,
		'by'=> login_user(),
		'datetime'=>date("r"),
		'role'=> login_user_role()		
		);
		$lastid =  $scoreattr->insert($data);
		
		$select = $scoreattr->select();
		$select->where("id = ?",$lastid);
		$detailArr = $scoreattr->fetchRow($select)->toArray();
		$detailArr['lastid']= $lastid;
		return $detailArr;
		} 
		else if($type == 'multiple'){
			$data = array(
			'attributes'=>$QueryString,
			'capno'=>$capno
			);
		return 	$data;
		}
		else if($type == 'attributes'){
		return 	$QueryString;
		}
		
		/*
		parse_str($QueryString, $output);		
		
		preint_r($output);
		echo $output['capbasis'];
		*/
		
		//$QueryString = explode(';',$QueryString);
		//echo "<br><br>";
		//preint_r($QueryString);
		
		/*
		$search = 'capno';
		if(array_key_exists($search,$attrArr)){
			echo $attrArr[$search];
		}*/
		
				
	}


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
			
			if(($cvDetail->model_cv_backgrd) && (!$ciDetail->backgrd_ci2)){
				//If true
				
			$sql4 = $table->select()
		     ->where('name LIKE ?', 'BackgroundInvestigation')
			 ->where('values LIKE ?', $cvDetail->backgrd);
			$tableDetail = $table->fetchRow($sql4);
			$wt = $tableDetail->wt;
			$values = $tableDetail->values;
		
			}
			else if(($ciDetail->backgrd_ci2) && (!$cvDetail->model_cv_backgrd)){
				//IF false
			$sql5 = $table->select()
		     ->where('name LIKE ?', 'BackgroundInvestigation')
			 ->where('values LIKE ?', $ciDetail->backgrd_ci2);
			$tableDetail = $table->fetchRow($sql5);
			$wt = $tableDetail->wt;
			$values = $tableDetail->values;
			}
			else if(($ciDetail->backgrd_ci2) && ($cvDetail->model_cv_backgrd)){
			$sql5 = $table->select()
		     ->where('name LIKE ?', 'BackgroundInvestigation')
			 ->where('values LIKE ?', $ciDetail->backgrd_ci2);
			$tableDetail = $table->fetchRow($sql5);
			$wt = $tableDetail->wt;
			$values = $tableDetail->values;	
			}			
			else if((!$ciDetail->backgrd_ci2) && (!$cvDetail->model_cv_backgrd)){
			$values = "None";	
			$wt = 0;
			}

		}
		
		if ($process == 'wt'){
		return $wt;
		}
		else if($process == 'values'){
		return $values;
		}
	
	}

	function getNewVehStatus($num,$process){
		if($num){
		$table = new Model_CategoryValues2();
		 $sql = $table->select()
	     ->where('name LIKE ?', 'VehStatus')
		 ->where('seq = ?', $num);
		$tableDetail = $table->fetchRow($sql);

		if ($process == 'wt'){
		return $tableDetail->wt;
		}
		else if($process == 'values'){
		return $tableDetail->values;
		}
			
		}else {
			return 'null';
		}

	}
	
	function getNewNeighborType($num,$process){
		
		if($num){
		$table = new Model_CategoryValues2();
		 $sql = $table->select()
	     ->where('name LIKE ?', 'NeighborhoodType')
		 ->where('seq = ?', $num);
		$tableDetail = $table->fetchRow($sql);
		
		if ($process == 'wt'){
		return $tableDetail->wt;
		}
		else if($process == 'values'){
		return $tableDetail->values;
		}
		}else {
			return 'null';
		}

	}
	
	function getNewBusNat($capno,$process){
		
			$bus = new Model_BorrowerBusiness();
			$select = $bus->select();
			$select->where('capno like ?',$capno);
			$busdetail = $bus->fetchAll($select);
			
			$sum = 0;

			foreach($busdetail as $detail){
				
		    if ($detail->bus_income >= $sum){
		    	$sum = $detail->bus_income;
			    $bus_nat = $detail->bus_nat;
				}
			/*
			If the Income has Equal!!
			else if($detail->emp_income == $sum){
				
				
			}
			*/
			
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
	}
	
	function getNewBusYrs($capno,$process){
		
			$bus = new Model_BorrowerBusiness();
			$select = $bus->select();
			$select->where('capno like ?',$capno);
			$busdetail = $bus->fetchAll($select);
			
			$sum = 0;

			foreach($busdetail as $detail){
				
		    if ($detail->bus_income >= $sum){
		    	$sum = $detail->bus_income;
			    $bus_yrs = $detail->bus_yrs;
				}
			/*
			If the Income has Equal!!
			else if($detail->emp_income == $sum){
				
				
			}
			*/
			
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
	}
	
	function getNewEmpStatuswt($capno,$process){
			
			//Determine the Highest Salary among the employments and getNew its Emp Status
			$emp = new Model_BorrowerEmployment();
			$select = $emp->select();
			$select->where('capno like ?',$capno);
			$empdetail = $emp->fetchAll($select);
			
			$sum = 0;
			$emp_status = 0;
			foreach($empdetail as $detail){
				
		    if ($detail->emp_income > $sum){
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
			
			
	 
	}
	
	
	
	function getNewVehBrand($num,$process){
		
	$table = new Model_CategoryValues2();
	 $sql = $table->select()
	     ->where('name LIKE ?', 'VehBrand')
		 ->where('values LIKE ?', $num);
		$count = $table->fetchAll($sql)->count();
		$tableDetail = $table->fetchRow($sql);

		if ($count == 0){
		$sql = $table->select()
	     ->where('name LIKE ?', 'VehBrand')
		 ->where('values LIKE ?', 'Others');
		$tableDetail = $table->fetchRow($sql);
		$o = $num;
		}
		
		if ($process == 'wt'){
		return $tableDetail->wt;
		}
		else if($process == 'values'){
		return $o.' '.$tableDetail->values;
		}

	}
	
	function getNewVehType($num,$process){
		
		$table = new Model_CategoryValues2();
		 $sql = $table->select()
	     ->where('name LIKE ?', 'VehType')
		 ->where('values LIKE ?', $num);
		$tableDetail = $table->fetchRow($sql);

		if ($process == 'wt'){
		return $tableDetail->wt;
		}
		else if($process == 'values'){
		return $tableDetail->values;
		}

	}
	
	function getNewLoanTerm($num){
		
		$table = new Model_CategoryValues2();
		 $sql = $table->select()
	     ->where('name LIKE ?', 'LoanTerm');
		$tableDetail = $table->fetchAll($sql);
		
		foreach($tableDetail as $details) : 
		if (($num >= $details->lower_range) && ($num <= $details->upper_range)){
		$wt = $details->wt;			
		}
		endforeach;
		return $wt;

	}
	
	function getNewTotalCombineIncome($num){
		
		$table = new Model_CategoryValues2();
		 $sql = $table->select()
	     ->where('name LIKE ?', 'TotalCombineIncome');
		$tableDetail = $table->fetchAll($sql);
		
		foreach($tableDetail as $details) : 
		if (($num >= $details->lower_range) && ($num < $details->upper_range)){
		$wt = $details->wt;			
		}
		endforeach;
		return $wt;

	}
	
	function getNewDownpayment($num){
		
		$table = new Model_CategoryValues2();
		 $sql = $table->select()
	     ->where('name LIKE ?', 'Downpayment');
		$tableDetail = $table->fetchAll($sql);
		
		foreach($tableDetail as $details) : 
		if (($num > $details->lower_range) && ($num <= $details->upper_range)){
		$wt = $details->wt;			
		}
		endforeach;
		return $wt;

	}
	
	function getNewLenghtStay($num){
		
		$table = new Model_CategoryValues2();
		 $sql = $table->select()
	     ->where('name LIKE ?', 'LengthStay');
		$tableDetail = $table->fetchAll($sql);
		
		foreach($tableDetail as $details) : 
		if (($num >= $details->lower_range) && ($num < $details->upper_range)){
		$wt = $details->wt;			
		}
		endforeach;

		return $wt;
		

	}
	function viewNewName($username){
	//Returns the name of the MA base on its Username
	if ($username){
	$ma = new Model_Users();
	$select = $ma->select();
	$select->where('username like ?',$username);
	$madetail = $ma->fetchRow($select);
	
	if ($madetail->name){
	return $madetail->name;
	}
	else {return "";}
	}
	else {
		return "";
	}
	
	}
	
	   function print_r_format($array)
   {
      echo '<pre>';
      print_r($array);
      echo '</pre>';
   }
   
   
	
	