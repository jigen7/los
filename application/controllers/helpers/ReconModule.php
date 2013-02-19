<?php 
class Zend_Controller_Action_Helper_ReconModule extends Zend_Controller_Action_Helper_Abstract {
    function direct($capno) {
        /**
         * Paolo Marco M. Manarang
         * April 07,2010
         * Helper for Recon Procedure
         * When the Profile of the Main borrower is change or saved
         **/
        $accnt = new Model_BorrowerAccount();
        $select = $accnt->select();
        $select->where('capno like ?', capnosep($capno).'_'.capnorecon($capno))->order('id ASC');
        $allBorrower = $accnt->fetchAll($select);
        
		/****Update Original Application Status ***/
		$data = array(
		'account_status' => 'MA - Recon',
		);
		$where = "capno like '$capno'";
		$accnt->update($data,$where);
		/****End of Update Original Application Status ***/
		
		
        $capnoComaker = $accnt->getComaker($capno);
        
        $allArray = Array();
        foreach ($allBorrower as $detail) {
            $allArray[] = $detail->capno;
        }
        

        if ($capnoComaker) {
            // if comaker exist and spouse
            $select = $accnt->select();
			$select->where("capno like ?",$capnoComaker);
            $allBorrower = $accnt->fetchAll($select);
            foreach ($allBorrower as $detail) {
                $allArray[] = $detail->capno;
            }
        }
        // Add Comaker Data and edit its mainborrower capno
        foreach ($allArray as $capno) {
            echo $capno;
            echo '<br>';
            $newrecon = capnorecon($capno) + 1;
            $newcap = capnoseprecon($capno).$newrecon;
            echo $newcap.'<br>';
            
            //Insert Account History
            $history = new Model_AccountHistory();
            $select = $history->select();
            $select->where('capno like ?', $capno)->order('id DESC');
            $historyDetail = $history->fetchRow($select);
            $hdata = array('capno'=>$capno, 'status'=>'MA - S', 'by'=>login_user(), 'date'=>date("r"), 'remarks'=>'RECON', 'date_last'=>$historyDetail->date, );
            $history->insert($hdata);
            //End of History
            
            /**Model_BorrowerAccount();**/$select = $accnt->select();
            $select->where('capno like ?', $capno);
            $array = $accnt->fetchRow($select)->toArray();
            $array['id'] = null;
            $array['capno'] = $newcap;
            $array['account_status2'] = 'RECON';
            $array['account_status'] = 'MA - E';
            $array['deviation'] = null;
            $array['recon_type'] = "normal";
			$array['routetag'] = "";			
            
            if ($accnt->isPrincipal($capno)) {
                //for comaker puspose
                $capnoPrincipal = $newcap;
            }
            if ($accnt->isComaker($capno)) {
                // for comaker puspose copy the new capno of principal capno
                $array['comaker_of'] = $capnoPrincipal;
            }
            
            print_r($array);
            $accnt->insert($array);
            /**End of Model_BorrowerAccount()**/
            
			/**Model_BorrowerDeviation()**/
			
            $deviation = new Model_BorrowerDeviation();
            $select = $deviation->select();
            $select->where('capno like ?', $capno);
            $deviationRow = $deviation->fetchAll($select);
            $deviationArray = $deviationRow->toArray();
            
            if ($deviationArray) {
                foreach ($deviationArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $deviation->insert($array);
                }
            }
            /**End of Model_BorrowerDeviation()**/
             
             /**Model_BorrowerDeviationOthers()**/
            $table = new Model_BorrowerDeviationOthers();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $table->insert($array);
                }
            }
            /**End of Model_BorrowerDeviationOthers()**/
             
             /**Model_BorrowerBusiness()**/
            $table = new Model_BorrowerBusiness();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $table->insert($array);
                }
            }
            /**End of Model_BorrowerBusiness()**/
             
             /**Model_BorrowerEmployment()**/
            $table = new Model_BorrowerEmployment();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $table->insert($array);
                }
            }
            /**End of Model_BorrowerEmployment()**/			
             
             /**Model_BorrowerCrawForm()**/
            $table = new Model_BorrowerCrawForm();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $table->insert($array);
                }
            }
            /**End of Model_BorrowerCrawForm()**/	

	    /**Model_BorrowerCrawForm Req()**/
            $table = new Model_BorrowerCrawFormReq();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $table->insert($array);
                }
            }
            /**End of Model_BorrowerCrawFormReq()**/	
             
             /**Model_BorrowerCi()**/
            $table = new Model_BorrowerCi();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $array['model_ci_srcincomever'] = null;
                    $array['model_ci_empver'] = null;
                    $array['model_ci_busver'] = null;
                    $array['model_ci_trdchk'] = null;
                    $array['model_ci_backgrd'] = null;
                    $table->insert($array);
                    
                }
            }
            /**End of Model_BorrowerCi()**/				
             
             
             /**Model_BorrowerCv()**/
            $table = new Model_BorrowerCv();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $array['model_cv_srcincomever'] = null;
                    $array['model_cv_empver'] = null;
                    $array['model_cv_busver'] = null;
                    $array['model_cv_trdchk'] = null;
                    $array['model_cv_backgrd'] = null;
                    $table->insert($array);
                }
            }
            /**End of Model_BorrowerCv()**/		
             
             /**Model_BorrowerIncomeMonthly()**/
            $table = new Model_BorrowerIncomeMonthly();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $table->insert($array);
                }
            }
            /**End of Model_BorrowerIncomeMonthly()**/	
             
             /**Model_BorrowerIncomeSource()**/
            $table = new Model_BorrowerIncomeSource();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $table->insert($array);
                }
            }
            /**End of Model_BorrowerIncomeSource()**/					
             
             
             /**Model_BorrowerInsuranceCharges()**/
            $table = new Model_BorrowerInsuranceCharges();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $table->insert($array);
                }
            }
            /**End of Model_BorrowerInsuranceCharges()**/	
             
             /**Model_BorrowerInsuranceCharges()**/
            $table = new Model_BorrowerInsurancePerils();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $table->insert($array);
                }
            }
            /**End of Model_BorrowerInsuranceCharges()**/	
             
             /**Model_BorrowerInsurancePolicy()**/
            $table = new Model_BorrowerInsurancePolicy();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $table->insert($array);
                }
            }
            /**End of Model_BorrowerInsurancePolicy()**/
            
             /**Model_BorrowerObBank()**/
            $table = new Model_BorrowerObBank();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $table->insert($array);
                }
            }
            /**End of Model_BorrowerObBank()**/
            
             /**Model_BorrowerObBfLiabilities()**/
            $table = new Model_BorrowerObBfLiabilities();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $table->insert($array);
                }
            }
            /**End of Model_BorrowerObBfLiabilities()**/
             
             /**Model_BorrowerObCreditCard()**/
            $table = new Model_BorrowerObCreditCard();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $table->insert($array);
                }
            }
            /**End of Model_BorrowerObCreditCard()**/	
             
             /**Model_BorrowerObExistLoan()**/
            $table = new Model_BorrowerObExistLoan();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $table->insert($array);
                }
            }
            /**End of Model_BorrowerObExistLoan()**/	
             
             /**Model_BorrowerObTrdBusRef()**/
            $table = new Model_BorrowerObTrdBusRef();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $table->insert($array);
                }
            }
            /**End of Model_BorrowerObTrdBusRef()**/
             
             /**Model_BorrowerOtherAuto()**/
            $table = new Model_BorrowerOtherAuto();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $table->insert($array);
                }
            }
            /**End of Model_BorrowerOtherAuto()**/	
             
             /**Model_BorrowerOtherBusFinance()**/
            $table = new Model_BorrowerOtherBusFinance();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $table->insert($array);
                }
            }
            /**End of Model_BorrowerOtherBusFinance()**/
             
             /**Model_BorrowerOtherReal()**/
            $table = new Model_BorrowerOtherReal();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $table->insert($array);
                }
            }
            /**End of Model_BorrowerOtherReal()**/	
             
             /**Model_BorrowerOtherShare()**/
            $table = new Model_BorrowerOtherShare();
            $select = $table->select();
            $select->where('capno like ?', $capno);
            $tableRow = $table->fetchAll($select);
            $tableArray = $tableRow->toArray();
            if ($tableArray) {
                foreach ($tableArray as $array) {
                    $array['capno'] = $newcap;
                    $array['id'] = null;
                    $table->insert($array);
                }
            }
            /**End of Model_BorrowerOtherShare()**/
			
             
        }
    }
    
    function chkchanges($capno) {
    
		$borrower  = new Model_BorrowerAccount();
		$oldDetails = $borrower->fetchRowModel(returnprevCap($capno));
		$currDetails = $borrower->fetchRowModel($capno);

		/*
        $arrayFields = array(
		'dealer', 'veh_brand','veh_unit','veh_type','veh_yrmodel',
		'veh_status','lcp','selling_price','veh_discount',
		'downpayment_percent','downpayment_actual','rate','loanterm',
		'amountloan','monthly_amortization','gmi_ratio','dealer_incentive',
		'effective_yield','addon_rate'	
		);
		*/
		$arrayFields = explode(',',$currDetails->recon_fields);
        $counter =0;
        foreach ($arrayFields as $x){
        	
			if($currDetails->$x != $oldDetails->$x){
				$counter++;
			}//end of if
        }// end of for each 
		
		return $counter;
        } //end of chkchagnes function
       
   function countreconField($capno){
        	
		$borrower  = new Model_BorrowerAccount();
		$detail = $borrower->fetchRowModel($capno);
		
		$field = explode(',', $detail->recon_fields);	
		
		if(login_user_role() == 'MA'){
			/*
			if (in_array("effective_yield", $field)) {
	 		$key = array_search('effective_yield', $field); 
			unset($arrayFields[$key]);
			}	
			if (in_array("dealer_incentive", $field)) {
	 		$key = array_search('dealer_incentive', $field); 
			unset($arrayFields[$key]);
			
			}
			*/
		}
		
		return count($field);
        }
        
    function chkchangesEyDi($capno) {
    
		$borrower  = new Model_BorrowerAccount();
		$oldDetails = $borrower->fetchRowModel(returnprevCap($capno));
		$currDetails = $borrower->fetchRowModel($capno);

		
        $arrayFields = array(
		'dealer_incentive',
		'effective_yield'		
		);
        $counter =0;
        foreach ($arrayFields as $x){
        	
			if($currDetails->$x != $oldDetails->$x){
				$counter++;
			}//end of if
        }// end of for each 
		
		return $counter;
        } //end of chkchagnes function        
        
 function chkchangesField($capno) {
    	//check the fields base on the check values before recon
		$borrower  = new Model_BorrowerAccount();
		$oldDetails = $borrower->fetchRowModel(returnprevCap($capno));
		$currDetails = $borrower->fetchRowModel($capno);
		
        $arrayFields = explode(',',$currDetails->recon_fields);
		
		if(login_user_role() == 'MA'){
			if (in_array("effective_yield", $arrayFields)) {
	 		$key = array_search('effective_yield', $arrayFields); 
			unset($arrayFields[$key]);
			}	
			if (in_array("dealer_incentive", $arrayFields)) {
	 		$key = array_search('dealer_incentive', $arrayFields); 
			unset($arrayFields[$key]);
			}
		}
		
        $counter = 0;
        foreach ($arrayFields as $x){
			if($currDetails->$x != $oldDetails->$x){
				$counter++;			
			}//end of if
        }// end of for each 
		
		return $counter;
        } //end of chkchagnesfield function        
        
        function chkreconChanges($capno,$type){
        
		$borrower  = new Model_BorrowerAccount();
		$oldDetails = $borrower->fetchRowModel(returnprevCap($capno));
		$currDetails = $borrower->fetchRowModel($capno);
		
        $reconFields = explode(',',$currDetails->recon_fields);
		
        $arrayFields = array(
		'dealer', 'veh_brand','veh_unit','veh_type','veh_yrmodel',
		'veh_status','lcp','selling_price','veh_discount',
		'downpayment_percent','downpayment_actual','rate','loanterm',
		);
		
		if(login_user_role() == 'MA'){
			if (in_array("effective_yield", $reconFields)) {
	 		$key = array_search('effective_yield', $reconFields); 
			unset($reconFields[$key]);
			}	
			if (in_array("dealer_incentive", $reconFields)) {
	 		$key = array_search('dealer_incentive', $reconFields); 
			unset($reconFields[$key]);
			}
		}
        foreach ($arrayFields as $x){        	
			if($currDetails->$x != $oldDetails->$x){
				$changeField[] = $x;
			}
			

        }// end of for each 
		
		
		if($type == 'fields'){
		return $changeField;
		} 
		
		else if($type == 'compare'){
			
			
		
		}
		}// end of function
		
		
		
    } // end of main
	
	
	
	function viewFieldsRecon($fields){
		if($fields == 'dealer'){
			return 'Dealer';
		}
		else if($fields == 'veh_unit'){
			return 'Unit';
			
		}		
		else if($fields == 'veh_brand'){
			return 'Brand';			
		}
		else if($fields == 'downpayment_percent'){
			return 'Downpayment %';			
		}
		else if($fields == 'loanterm'){
			return 'Loan Term';
		}	
		else if($fields == 'rate'){
			return 'Addon Rate';
		}	
		else if($fields == 'addon_rate'){
			return 'Financing Scheme';
		}	
		else if($fields == 'effective_yield'){
			return 'Effective Yield';
		}	
		else if($fields == 'dealer_incentive'){
			return 'Dealer Incentive';
		}		
		else if($fields == 'amountloan'){
			return 'Amount Finance';
		}		
		
	}
    
