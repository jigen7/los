<?php
/**
 * Paolo Marco M. Manarang 
 * November 15,2009
 * Action Helper for the Scoring Module on How the Application should be scored
 * 
 */
class Zend_Controller_Action_Helper_ScoreModule extends Zend_Controller_Action_Helper_Abstract
{
    function direct($capno,$mode)
    {
		$scoretag = Zend_Controller_Action_HelperBroker::getStaticHelper('ScoreTag');		

		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		$select->where('capno like ?',$capno);
		$accntdetail = $accnt->fetchRow($select);
		$user = Zend_Auth::getInstance()->getIdentity();


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
	
	
	$loan_term_wt = getLoanTerm($accntdetail->loanterm);
	$downpayment_wt = getDownpayment($accntdetail->downpayment_percent);
	$veh_type_wt = getVehType($accntdetail->veh_type,'wt');
	$veh_status_wt = getVehStatus($accntdetail->veh_status,'wt');
	$veh_brand_wt = getVehBrand($accntdetail->veh_brand,'wt');
	
	//echo "Loan Term";
	//echo $loan_term_wt;
	//echo "Veh Status ";
	//echo $veh_status_wt;
//	echo "Veh Type ";
	//echo $veh_type_wt;
	//echo "Veh Brand";
	//echo $veh_brand_wt;
	//echo "Downpayment ";
	//echo $downpayment_wt;
	
	//echo "Cap Basis ";
	$cap_basis = getHighest($capno);
	//echo $cap_basis;
	
	$select = $accnt->select(); // Reset the Selection of the Database
	$select->where('capno like ?',$cap_basis);
	$accntbasisdetail = $accnt->fetchRow($select);
	
	$lengh_stay_wt = getLenghtStay($accntbasisdetail->residence_yrs);
	//echo "Length Stay ";
	//echo $lengh_stay_wt;
	$neighbor_type_wt = getNeighborType($accntbasisdetail->neighborhoodtype,'wt'); 
	//echo "Neighbor Type ";
	//echo $neighbor_type_wt;
	
	
	if ($accntbasisdetail->empbus_status == "E"){
		$emp_status_wt = getEmpStatuswt($cap_basis,'wt');
	}
	else if ($accntbasisdetail->empbus_status == "SE"){
		

		$bus_yrs_wt = getBusYrs($cap_basis,'wt');
		$bus_nat_wt = getBusNat($cap_basis,'wt');
		
		$bus_values = getBusYrs($cap_basis,'busyrs');
		if($bus_values < 2){
		//echo "<br>Business Years Exceeded Low Boundary < 2 Years: ".$bus_values."<br>";
		$countDevi++;
		}
		
	}
	else if($accntbasisdetail->empbus_status == "O"){
		echo "Other Source of Income";
		$countDevi++;		
	}
	
	$totalcombine_wt = getTotalCombineIncome(totalcombinedincome($capno));

	$background_wt = getBackground($accntdetail->account_status,$cap_basis,'wt');
	
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
	$page->drawText('Vehicle Type   : '.getVehType($accntdetail->veh_type,'values').' wt : '.$veh_type_wt, 30, 730);
	$page->drawText('Vehicle Status   : '.getVehStatus($accntdetail->veh_status,'values').' wt : '.$veh_status_wt, 30, 720);
	$page->drawText('Vehicle Brand   : '.getVehBrand($accntdetail->veh_brand,'values').' wt : '.$veh_brand_wt, 30, 710);

	$page->drawText('Model Basis   : '.$cap_basis, 30, 690);
	$page->drawText('Name  : '.$accntbasisdetail->borrower_lname.', '.$accntbasisdetail->borrower_fname.' '.$accntbasisdetail->borrower_mname, 30, 680);
	$page->drawText('Relation   : '.$accntbasisdetail->relation, 30, 670);
	$page->drawText('Employment / Business   : '.$accntbasisdetail->empbus_status, 30, 660);

	$page->drawText('Lenght Stay   : '.$accntbasisdetail->residence_yrs.'years /'.$accntbasisdetail->residence_months.'mos. wt : '.$lengh_stay_wt, 30, 650);
	$page->drawText('Neighbor Type   : '.getNeighborType($accntbasisdetail->neighborhoodtype,'values').' wt : '.$neighbor_type_wt, 30, 640);

	if ($accntbasisdetail->empbus_status == "E"){
	$page->drawText('Employment Status   : '.getEmpStatuswt($cap_basis,'values').' wt : '.$emp_status_wt, 30, 620);
	}else if($accntbasisdetail->empbus_status == "SE"){
	$page->drawText('Business Years   : '.getBusYrs($cap_basis,'values').' wt : '.$bus_yrs_wt, 30, 620);
	$page->drawText('Business Nature   : '.getBusNat($cap_basis,'values').' wt : '.$bus_nat_wt, 30, 610);
	}
	$page->drawText('Total Combine Income   : '.totalcombinedincome($capno).' wt : '.$totalcombine_wt, 30, 590);
	$page->drawText('Background   : '.getBackground($accntdetail->account_status,$cap_basis,'values').' wt : '.$background_wt, 30, 580);

	$page->drawText('__________________', 30, 560);
	$page->drawText('Sum : '.$accntbasisdetail->empbus_status.' '.$sum.' '.$scoretag->direct($sum,$accntbasisdetail->empbus_status,$accntbasisdetail->capno), 30, 550);
	$page->drawText('Date : '.date('m/d/Y h:i:s'), 30, 530);
	$page->drawText('Generated By : '.viewName($user->username), 30, 520);
	//$pdf->save('D:\cscore\breakdown-'.$accntdetail->capno.'-'.$accntdetail->borrower_lname.'-'.$user->role_type.'-'.date('his').'.pdf'); 
	$pdf->save('C:\cscore'.'\/'.$accntdetail->borrower_lname.'-'.$accntdetail->capno.'-'.$user->role_type.'-'.date('his').'-MD01.pdf'); 

	//echo "Total Score: ".$sum;
	
	if($countDevi > 0){
	$sum = -9999;
	//echo "Sum Overall ".$sum;

	return $sum;	
	}
	else {
		//echo "Sum Overalln ".$sum;

	return $sum;
	}
	}
	

}

	function getBackground($status,$capno,$process){
		
		$table = new Model_CategoryValues();

		$ci = new Model_BorrowerCi();
			$sql2 = $ci->select()
			->where('capno LIKE ?', $capno);
			$ciDetail = $ci->fetchRow($sql2);
		
		$cv = new Model_BorrowerCv();
			$sql3 = $cv->select()
		     ->where('capno LIKE ?', $capno);
			$cvDetail = $cv->fetchRow($sql3);

		if ($status == "MA - E"){
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

	function getVehStatus($num,$process){
		
		$table = new Model_CategoryValues();
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

	}
	
	function getNeighborType($num,$process){
		
		$table = new Model_CategoryValues();
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

	}
	
	function getBusNat($capno,$process){
		
			$bus = new Model_BorrowerBusiness();
			$select = $bus->select();
			$select->where('capno like ?',$capno);
			$busdetail = $bus->fetchAll($select);
			
			$sum = 0;

			foreach($busdetail as $detail){
				
		    if ($detail->bus_income > $sum){
		    	$sum = $detail->bus_income;
			    $bus_nat = $detail->bus_nat;
				}
			/*
			If the Income has Equal!!
			else if($detail->emp_income == $sum){
				
				
			}
			*/
			
			}//End for Each
			
		$table = new Model_CategoryValues();
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
	
	function getBusYrs($capno,$process){
		
			$bus = new Model_BorrowerBusiness();
			$select = $bus->select();
			$select->where('capno like ?',$capno);
			$busdetail = $bus->fetchAll($select);
			
			$sum = 0;

			foreach($busdetail as $detail){
				
		    if ($detail->bus_income > $sum){
		    	$sum = $detail->bus_income;
			    $bus_yrs = $detail->bus_yrs;
				}
			/*
			If the Income has Equal!!
			else if($detail->emp_income == $sum){
				
				
			}
			*/
			
			}//End for Each
			
		$table = new Model_CategoryValues();
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
	
	function getEmpStatuswt($capno,$process){
			
			//Determine the Highest Salary among the employments and get its Emp Status
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
			
		$table = new Model_CategoryValues();
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
	
	
	
	function getVehBrand($num,$process){
		
	$table = new Model_CategoryValues();
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
	
	function getVehType($num,$process){
		
		$table = new Model_CategoryValues();
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
	
	function getLoanTerm($num){
		
		$table = new Model_CategoryValues();
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
	
	function getTotalCombineIncome($num){
		
		$table = new Model_CategoryValues();
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
	
	function getDownpayment($num){
		
		$table = new Model_CategoryValues();
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
	
	function getLenghtStay($num){
		
		$table = new Model_CategoryValues();
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
	function viewName($username){
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
	
	
	
	