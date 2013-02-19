<?php

class Zend_Controller_Action_Helper_CloneData extends
                Zend_Controller_Action_Helper_Abstract
{
    function direct($capno)
    {
			//Main Table Borrower	
			$account = new Model_BorrowerAccount();
			$select = $account->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			$accntRow = $account->fetchAll($select);
			$accntArray = $accntRow->toArray();
			//Employment Table		
			$emp = new Model_BorrowerEmployment();
			$select = $emp->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			$empRow = $emp->fetchAll($select);
			$empRowArray = $empRow->toArray();
			//Business Table
			$bus = new Model_BorrowerBusiness();
			$select = $bus->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			$busRow = $bus->fetchAll($select);
			$busRowArray = $busRow->toArray();
			//Deviation
			$deviation = new Model_BorrowerDeviation();
			$select = $deviation->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			$deviationRow = $deviation->fetchAll($select);
			$deviationArray = $deviationRow->toArray();
			//Obligations Bank Accounts
			$bank = new Model_BorrowerObBank();
			$select = $bank->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			$bankRow = $bank->fetchAll($select);
			$bankArray = $bankRow->toArray();	
			//Obligations Credit Card
			$creditcard = new Model_BorrowerObCreditCard();
			$select = $creditcard->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			$creditcardRow = $creditcard->fetchAll($select);
			$creditcardArray = $creditcardRow->toArray();	
			//Obligation Exist Loan
			$existloan = new Model_BorrowerObExistLoan();
			$select = $existloan->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			$existloanRow = $existloan->fetchAll($select);
			$existloanArray = $existloanRow->toArray();		 
			//Obligation Trade and Bus Ref
			$trdbusref = new Model_BorrowerObTrdBusRef();
			$select = $trdbusref->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			$trdbusrefRow = $trdbusref->fetchAll($select);
			$trdbusrefArray = $trdbusrefRow->toArray();				
			//Other Auto
			$otherauto = new Model_BorrowerOtherAuto();
			$select = $otherauto->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			$otherautoRow = $otherauto->fetchAll($select);
			$otherautoArray = $otherautoRow->toArray();			
			//Other Real Estate
			$otherreal = new Model_BorrowerOtherReal();
			$select = $otherreal->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			$otherrealRow = $otherreal->fetchAll($select);
			$otherrealArray = $otherrealRow->toArray();		
			//Other Shares
			$othershare = new Model_BorrowerOtherShare();
			$select = $othershare->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			$othershareRow = $othershare->fetchAll($select);
			$othershareArray = $othershareRow->toArray();		
			//CI 
			$ci = new Model_BorrowerCi();
			$select = $ci->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			$ciRow = $ci->fetchAll($select);
			$ciArray = $ciRow->toArray();	
			//CV
			$cv = new Model_BorrowerCv();
			$select = $cv->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			$cvRow = $cv->fetchAll($select);
			$cvArray = $cvRow->toArray();	
			//Business Financials
			$busfin = new Model_BorrowerObBfLiabilities();
			$select = $busfin->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			$busfinRow = $busfin->fetchAll($select);
			$busfinArray = $busfinRow->toArray();	
			//Other Business Finance
			$busfn = new Model_BorrowerOtherBusFinance();
			$select = $busfn->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			$busfnRow = $busfn->fetchAll($select);
			$otherbusfnArray = $busfnRow->toArray();	
			//Other Monthly Income
			$othermonthly = new Model_BorrowerIncomeMonthly();
			$select = $othermonthly->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
		    $othermonthlyRow = $othermonthly->fetchAll($select);
			$othermonthlyArray = $othermonthlyRow->toArray();	
			//Other Source of Income
			$othersource = new Model_BorrowerIncomeSource();
			$select = $othersource->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
		    $othersourceRow = $othersource->fetchAll($select);
			$othersourceArray = $othersourceRow->toArray();	

			/** Clone Process **/
			if(login_user_role() == 'MA'){
				
			$account_ma = new Model_BorrowerAccountMa();
			$emp_ma = new Model_BorrowerEmploymentMa();
			$bus_ma = new Model_BorrowerBusinessMa();
			$deviation_ma = new Model_BorrowerDeviationMa();
			$bankaccount_ma = new Model_BorrowerObBankMa();
			$creditcard_ma = new Model_BorrowerObCreditCardMa();
			$existloan_ma = new Model_BorrowerObExistLoanMa();
			$trdbusref_ma = new Model_BorrowerObTrdBusRefMa();
			$otherauto_ma = new Model_BorrowerOtherAutoMa();
			$otherreal_ma = new Model_BorrowerOtherRealMa();
			$othershare_ma = new Model_BorrowerOtherShareMa();
			$busfin_ma = new Model_BorrowerOtherBusFinanceMa();
			$otherbusfin_ma = new Model_BorrowerOtherBusFinanceMa();
			$othermonthly_ma = new Model_BorrowerIncomeMonthlyMa();			
			$othersource_ma = new Model_BorrowerIncomeSourceMa();			
			
		if($account_ma->isExist($capno)){	
			foreach ($accntArray as $array){
				
				$select_ma = $account_ma->select()->where('capno LIKE ?',$array[capno]);
				$array[car_history] = ifempty($array[car_history]);
				$array[deviation] = ifempty($array[deviation]);			
				$array[ma_clone_date] = date("r");
				$array[ma_clone_by] = login_user();
	
				if($account_ma->fetchAll($select_ma)->count() == 0){
					$account_ma->insert($array);
					
				}
				else {
					//$where = "capno like '$array[capno]'";
				//	$account_ma->update($array,$where);
				}

			}// End For Each Borrower Table
			if($empRowArray){
			foreach ($empRowArray as $array){
				
				$selemp_ma = $emp_ma->select()->where('capno LIKE ?',$array[capno]);
		
				$array[ma_clone_date] = date("r");
				$array[ma_clone_by] = login_user();
	
				if($emp_ma->fetchAll($selemp_ma)->count() == 0){
					$emp_ma->insert($array);
					
				}
				else {
					//$where = "capno like '$array[capno]'";
				//	$emp_ma->update($array,$where);
					
				}

			}// End For Each Employment Table
			}
			if($busRowArray){
			foreach ($busRowArray as $array){
				
				$selbus_ma = $bus_ma->select()->where('capno LIKE ?',$array[capno]);
		
				$array[ma_clone_date] = date("r");
				$array[ma_clone_by] = login_user();
	
				if($bus_ma->fetchAll($selbus_ma)->count() == 0){
					$bus_ma->insert($array);
				}
				else {
					//$where = "capno like '$array[capno]'";
					//$bus_ma->update($array,$where);
					
				}

			}// End For Each Business Table
			}//End if Business MA
			
			if($deviationArray){
			foreach ($deviationArray as $array){
				
				$select = $deviation_ma ->select()->where('capno LIKE ?',$array[capno]);
		
	
				if($deviation_ma->fetchAll($select)->count() == 0){
					$deviation_ma->insert($array);
					
				}
				else {
					//$where = "capno like '$array[capno]'";
					//$deviation_ma->update($array,$where);
					
				}

			}// End For Each Deviation Table
			}//End if Deviation MA
			
			if($bankArray){
			foreach ($bankArray as $array){
				
				$select = $bankaccount_ma ->select()->where('capno LIKE ?',$array[capno]);
		
				if($bankaccount_ma->fetchAll($select)->count() == 0){
				   $bankaccount_ma->insert($array);
					
				}
				else {
					//$where = "capno like '$array[capno]'";
					//$bankaccount_ma->update($array,$where);
					
				}

			}// End For Each Deviation Table
			}//End if Bank Account MA
			
			if($creditcardArray){
			foreach ($creditcardArray as $array){
				
				$select = $creditcard_ma ->select()->where('capno LIKE ?',$array[capno]);
		
				if($creditcard_ma->fetchAll($select)->count() == 0){
				   $creditcard_ma->insert($array);
					
				}
				else {
					//$where = "capno like '$array[capno]'";
					//$creditcard_ma->update($array,$where);
					
				}

			}// End For Each Credit Card Table
			}//End if Credit Card MA

			if($existloanArray){
			foreach ($existloanArray as $array){
				
				$select = $existloan_ma->select()->where('capno LIKE ?',$array[capno]);
		
				if($existloan_ma->fetchAll($select)->count() == 0){
				   $existloan_ma->insert($array);
					
				}
				else {
					//$where = "capno like '$array[capno]'";
					//$existloan_ma->update($array,$where);
					
				}

			}// End For Each  Exist LoanTable
			}//End if Exist Loan MA
			
			if($trdbusrefArray){
			foreach ($trdbusrefArray as $array){
				
				$select = $trdbusref_ma ->select()->where('capno LIKE ?',$array[capno]);
		
				if($trdbusref_ma ->fetchAll($select)->count() == 0){
				   $trdbusref_ma ->insert($array);
					
				}
				else {
					//$where = "capno like '$array[capno]'";
					//$trdbusref_ma ->update($array,$where);
					
				}

			}// End For Each  Trade Bus References
			}//End if Trade Bus References MA
			
			if($otherautoArray){
			foreach ($otherautoArray as $array){
				
				$select = $otherauto_ma ->select()->where('capno LIKE ?',$array[capno]);
		
				if($otherauto_ma ->fetchAll($select)->count() == 0){
				   $otherauto_ma ->insert($array);
					
				}
				else {
					//$where = "capno like '$array[capno]'";
					//$otherauto_ma ->update($array,$where);
					
				}

			}// End For Each Other Auto
			}//End if Other Auto MA
			
			if($otherrealArray){
			foreach ($otherrealArray as $array){
				
				$select = $otherreal_ma ->select()->where('capno LIKE ?',$array[capno]);
		

	
				if($otherreal_ma ->fetchAll($select)->count() == 0){
				   $otherreal_ma ->insert($array);
					
				}
				else {
					//$where = "capno like '$array[capno]'";
					//$otherreal_ma ->update($array,$where);
					
				}

			}// End For Each Other Auto
			}//End if Other Auto MA
			
			if($othershareArray){
			foreach ($othershareArray as $array){
				
				$select = $othershare_ma ->select()->where('capno LIKE ?',$array[capno]);
		

	
				if($othershare_ma ->fetchAll($select)->count() == 0){
				   $othershare_ma ->insert($array);
					
				}
				else {
					//$where = "capno like '$array[capno]'";
					//$othershare_ma ->update($array,$where);
					
				}

			}// End For Each Other Auto
			}//End if Other Auto MA
			
			if($deviationArray ){
			foreach ($deviationArray  as $array){
				
				$select = $deviation_ma->select()->where('capno LIKE ?',$array[capno]);
		

				if($deviation_ma->fetchAll($select)->count() == 0){
				   $deviation_ma->insert($array);
					
				}
				else {
					//$where = "capno like '$array[capno]'";
					//$deviation_ma->update($array,$where);
					
				}

			}// End For Each Other Auto
			}//End if Other Auto MA
			
			if($busfinArray){
			foreach ($busfinArray  as $array){
				
				$select = $busfin_ma->select()->where('capno LIKE ?',$array[capno]);
		

	
				if($busfin_ma->fetchAll($select)->count() == 0){
				   $busfin_ma->insert($array);
					
				}
				else {
					//$where = "capno like '$array[capno]'";
					//$busfin_ma->update($array,$where);
					
				}

			}// End For Each Business Financial
			} //End for null		
			
			if($otherbusfnArray){
			foreach ($otherbusfnArray  as $array){
				
				$select = $otherbusfin_ma->select()->where('capno LIKE ?',$array[capno]);
		

	
				if($otherbusfin_ma->fetchAll($select)->count() == 0){
				   $otherbusfin_ma->insert($array);
					
				}
				else {
					//$where = "capno like '$array[capno]'";
				//	$otherbusfin_ma->update($array,$where);
					
				}

			}// End For Each Business Financial
			} //End for null	
			
			
			if($othermonthlyArray ){
			foreach ($othermonthlyArray  as $array){
				
				$select = $othermonthly_ma ->select()->where('capno LIKE ?',$array[capno]);
		

	
				if($othermonthly_ma ->fetchAll($select)->count() == 0){
				   $othermonthly_ma ->insert($array);
					
				}
				else {
					//$where = "capno like '$array[capno]'";
				//	$othermonthly_ma ->update($array,$where);
					
				}

			}// End For Each Other Monthly Income
			} //End for null	
			
			if($othersourceArray  ){
			foreach ($othersourceArray   as $array){
				
				$select = $othermonthly_ma ->select()->where('capno LIKE ?',$array[capno]);
		

	
				if($othersource_ma  ->fetchAll($select)->count() == 0){
				   $othersource_ma  ->insert($array);
					
				}
				else {
					//$where = "capno like '$array[capno]'";
					//$othersource_ma  ->update($array,$where);
					
				}

			}// End For Each Other Source Income
			} //End for null	

		}// End of is Exist
			
			}//End of MA 
	
			else if(login_user_role() == 'CA'){
			$account_ca = new Model_BorrowerAccountCa();
			$emp_ca = new Model_BorrowerEmploymentCa();
			$bus_ca = new Model_BorrowerBusinessCa();
			$deviation_ca = new Model_BorrowerDeviationCa();
			$bankaccount_ca = new Model_BorrowerObBankCa();
			$creditcard_ca = new Model_BorrowerObCreditCardCa();
			$existloan_ca = new Model_BorrowerObExistLoanCa();
			$trdbusref_ca = new Model_BorrowerObTrdBusRefCa();
			$otherauto_ca = new Model_BorrowerOtherAutoCa();
			$otherreal_ca = new Model_BorrowerOtherRealCa();
			$othershare_ca = new Model_BorrowerOtherShareCa();	
			$ci_ca = new Model_BorrowerCiCa();
			$cv_ca = new Model_BorrowerCvCa();
			$busfin_ca = new Model_BorrowerOtherBusFinanceCa();
			$otherbusfin_ca = new Model_BorrowerOtherBusFinanceCa();
			$othermonthly_ca = new Model_BorrowerIncomeMonthlyCa();			
			$othersource_ca = new Model_BorrowerIncomeSourceCa();	
			
			foreach ($accntArray as $array){
				
				$select_ca = $account_ca->select()->where('capno LIKE ?',$array[capno]);
				$array[car_history] = ifempty($array[car_history]);
				$array[deviation] = ifempty($array[deviation]);			
				$array[ca_clone_date] = date("r");
				$array[ca_clone_by] = login_user();
	
				if($account_ca->fetchAll($select_ca)->count() == 0){
					$account_ca->insert($array);
					
				}
				else {
					$where = "capno like '$array[capno]'";
					$account_ca->update($array,$where);
					
				}

			}//End For Each Borrower
			if($empRowArray){	
			foreach ($empRowArray as $array){
				
				$selemp_ca = $emp_ca->select()->where('capno LIKE ?',$array[capno]);
		
				$array[ca_clone_date] = date("r");
				$array[ca_clone_by] = login_user();
	
				if($emp_ca->fetchAll($selemp_ca)->count() == 0){
					$emp_ca->insert($array);
					
				}
				else {
					$where = "capno like '$array[capno]'";
					$emp_ca->update($array,$where);
					
				}

			}// End For Each CA Employment Table
			}// End If Emp CA
			if($busRowArray){	
			foreach ($busRowArray as $array){
				
				$selbus_ca = $bus_ca->select()->where('capno LIKE ?',$array[capno]);
		
				$array[ca_clone_date] = date("r");
				$array[ca_clone_by] = login_user();
	
				if($bus_ca->fetchAll($selbus_ca)->count() == 0){
					$bus_ca->insert($array);
					
				}
				else {
					$where = "capno like '$array[capno]'";
					$bus_ca->update($array,$where);
					
				}

			}// End For Each CA Business Table
			} // End if Business CA 
			
			if($bankArray){
			foreach ($bankArray as $array){
				
				$select = $bankaccount_ca ->select()->where('capno LIKE ?',$array[capno]);
		

	
				if($bankaccount_ca->fetchAll($select)->count() == 0){
				   $bankaccount_ca->insert($array);
					
				}
				else {
					$where = "capno like '$array[capno]'";
					$bankaccount_ca->update($array,$where);
					
				}

			}// End For Each Deviation Table
			}//End if Bank Account MA
			
			if($creditcardArray){
			foreach ($creditcardArray as $array){
				
				$select = $creditcard_ca ->select()->where('capno LIKE ?',$array[capno]);
		

	
				if($creditcard_ca->fetchAll($select)->count() == 0){
				   $creditcard_ca->insert($array);
					
				}
				else {
					$where = "capno like '$array[capno]'";
					$creditcard_ca->update($array,$where);
					
				}

			}// End For Each Credit Card Table
			}//End if Credit Card MA

			if($existloanArray){
			foreach ($existloanArray as $array){
				
				$select = $existloan_ca->select()->where('capno LIKE ?',$array[capno]);
		

	
				if($existloan_ca->fetchAll($select)->count() == 0){
				   $existloan_ca->insert($array);
					
				}
				else {
					$where = "capno like '$array[capno]'";
					$existloan_ca->update($array,$where);
					
				}

			}// End For Each  Exist LoanTable
			}//End if Exist Loan MA
			
			if($trdbusrefArray){
			foreach ($trdbusrefArray as $array){
				
				$select = $trdbusref_ca ->select()->where('capno LIKE ?',$array[capno]);
		

	
				if($trdbusref_ca ->fetchAll($select)->count() == 0){
				   $trdbusref_ca ->insert($array);
					
				}
				else {
					$where = "capno like '$array[capno]'";
					$trdbusref_ca ->update($array,$where);
					
				}

			}// End For Each  Trade Bus References
			}//End if Trade Bus References MA
			
			if($otherautoArray){
			foreach ($otherautoArray as $array){
				
				$select = $otherauto_ca ->select()->where('capno LIKE ?',$array[capno]);
		

	
				if($otherauto_ca ->fetchAll($select)->count() == 0){
				   $otherauto_ca ->insert($array);
					
				}
				else {
					$where = "capno like '$array[capno]'";
					$otherauto_ca ->update($array,$where);
					
				}

			}// End For Each Other Auto
			}//End if Other Auto MA
			
			if($otherrealArray){
			foreach ($otherrealArray as $array){
				
				$select = $otherreal_ca ->select()->where('capno LIKE ?',$array[capno]);
		

	
				if($otherreal_ca ->fetchAll($select)->count() == 0){
				   $otherreal_ca ->insert($array);
					
				}
				else {
					$where = "capno like '$array[capno]'";
					$otherreal_ca ->update($array,$where);
					
				}

			}// End For Each Other Auto
			}//End if Other Auto MA
			
			if($othershareArray){
			foreach ($othershareArray as $array){
				
				$select = $othershare_ca ->select()->where('capno LIKE ?',$array[capno]);
		

				if($othershare_ca ->fetchAll($select)->count() == 0){
				   $othershare_ca ->insert($array);
					
				}
				else {
					$where = "capno like '$array[capno]'";
					$othershare_ca ->update($array,$where);
					
				}

			}// End For Each Other Auto
			}//End if Other Auto MA
			
			if($deviationArray ){
			foreach ($deviationArray  as $array){
				
				$select = $deviation_ca->select()->where('capno LIKE ?',$array[capno]);
		

	
				if($deviation_ca->fetchAll($select)->count() == 0){
				   $deviation_ca->insert($array);
					
				}
				else {
					$where = "capno like '$array[capno]'";
					$deviation_ca->update($array,$where);
					
				}

			}// End For Each Other Auto
			}//End if Other Auto MA
			
			if($ciArray){
			foreach ($ciArray as $array){
				
				$select = $ci_ca ->select()->where('capno LIKE ?',$array[capno]);
		

				$array[model_ci_srcincomever] = ifempty($array[model_cv_srcincomever]);
				$array[model_ci_empver] = ifempty($array[model_ci_empver]);	
				$array[model_ci_busver] = ifempty($array[model_ci_busver]);
				$array[model_ci_trdchk] = ifempty($array[model_ci_trdchk]);	
				$array[model_ci_backgrd] = ifempty($array[model_ci_backgrd]);
				
				
				if($ci_ca->fetchAll($select)->count() == 0){
				   $ci_ca->insert($array);
					
				}
				else {
					$where = "capno like '$array[capno]'";
					$ci_ca->update($array,$where);
					
				}

			}// End For Each Other Auto
			}//End if Other Auto MA
			
			if($cvArray){
			foreach ($cvArray as $array){
				
				$select = $cv_ca ->select()->where('capno LIKE ?',$array[capno]);
		

				$array[model_cv_srcincomever] = ifempty($array[model_cv_srcincomever]);
				$array[model_cv_empver] = ifempty($array[model_cv_empver]);	
				$array[model_cv_busver] = ifempty($array[model_cv_busver]);
				$array[model_cv_trdchk] = ifempty($array[model_cv_trdchk]);	
				$array[model_cv_backgrd] = ifempty($array[model_cv_backgrd]);

				if($cv_ca->fetchAll($select)->count() == 0){
				   $cv_ca->insert($array);
					
				}
				else {
					$where = "capno like '$array[capno]'";
					$cv_ca->update($array,$where);
					
				}

			}// End For Each Other Auto
			}//End if Other Auto MA
			
			
			if($busfinArray){
			foreach ($busfinArray  as $array){
				
				$select = $busfin_ca->select()->where('capno LIKE ?',$array[capno]);

	
				if($busfin_ca->fetchAll($select)->count() == 0){
				   $busfin_ca->insert($array);
					
				}
				else {
					$where = "capno like '$array[capno]'";
					$busfin_ca->update($array,$where);
					
				}

			}// End For Each Business Financial
			} //End for null		
			
			if($otherbusfnArray){
			foreach ($otherbusfnArray  as $array){
				
				$select = $otherbusfin_ca->select()->where('capno LIKE ?',$array[capno]);

	
				if($otherbusfin_ca->fetchAll($select)->count() == 0){
				   $otherbusfin_ca->insert($array);
					
				}
				else {
					$where = "capno like '$array[capno]'";
					$otherbusfin_ca->update($array,$where);
					
				}
			}
			}
			
			if($othermonthlyArray ){
			foreach ($othermonthlyArray  as $array){
				
				$select = $othermonthly_ca->select()->where('capno LIKE ?',$array[capno]);
		

	
				if($othermonthly_ca->fetchAll($select)->count() == 0){
				   $othermonthly_ca->insert($array);
					
				}
				else {
					$where = "capno like '$array[capno]'";
					$othermonthly_ca ->update($array,$where);
					
				}

			}// End For Each Other Monthly Income
			} //End for null	
			
			if($othersourceArray  ){
			foreach ($othersourceArray   as $array){
				
				$select = $othermonthly_ca ->select()->where('capno LIKE ?',$array[capno]);
		

	
				if($othersource_ca->fetchAll($select)->count() == 0){
				   $othersource_ca->insert($array);
					
				}
				else {
					$where = "capno like '$array[capno]'";
					$othersource_ca->update($array,$where);
					
				}

			}// End For Each Other Source Income
			} //End for null		
			
			//$newma = $account_ma->createRow($accntArray);
			//print_r_html(array_diff_values($accntArray,$accntArray));
			//$newma->save();
			}//End of CA

	}
}
function ifempty($value){
	
	if($value){
		return 'true';
	}
	else {
		return 'false';
	}
	
	
}

