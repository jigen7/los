<?php

class Zend_Controller_Action_Helper_CreditScore extends Zend_Controller_Action_Helper_Abstract
{
	
	function scoreMultipleAccount($accounts,$session,$process, $prod){
		//USed in Multiple Scoring 
		$csmodel = new Model_Creditscore_CSModel();	
		$modelArr = explode(',',$accounts->model_use);
		array_pop($modelArr);		
		//preint_r($modelArr);	
		//echo $accounts->account; // CAPNO
		$scoremodule = Zend_Controller_Action_HelperBroker::getStaticHelper('ScoreModule2');		
		$string = $scoremodule->storeattr($accounts->account,'multiple');
		//parse_str($string,$output);
		//print_r_format($output);				
		$modelList = array();
		$scoreAtt = new Model_Creditscore_ScoreAttributes();
		//LOS Borrower Accounts Model to be change in CIN
		//Get Deviation
		$borrower = new Model_BorrowerAccount();
		$principaldetail = $borrower->fetchRowModel($accounts->account);
		if($principaldetail->deviation){
			$ifDev ='D';
		}		
		// END OF LOS
		foreach($modelArr as $x){
			//Scoring procedure
			$model_namever = $csmodel->getModelById($x);
			//Get Fields of the Model
			//$model_fields = getModelsFields($model_namever);	
			$scoreAccount = $this->scoreIndividualAccount($string,$model_namever,'multiple');	
			parse_str($scoreAccount,$output);
	
			$scoretag = Zend_Controller_Action_HelperBroker::getStaticHelper('ScoreTag');
			$scoreR = $scoretag->direct($output['totalscore'], $output['empbus_status'],$output['capno']);		
			if($process =='save'){
				$data = array(
					'capno'=>$accounts->account,
					'by'=>login_user(),
					'datetime'=>date("r"),
					'attributes'=>$scoremodule->storeattr($accounts->account,'attributes'),
					'score_attributes'=>$scoreAccount,
					'model_used'=>$model_namever,
					'score'=>$output['totalscore'],
					'scoretag'=>$output['empbus_status'].' '.$output['totalscore'].' '.$scoreR.' '.$ifDev,
					'session'=>$session,
					'prod'=>$prod
				);
				$output['lastid'] = ($scoreAtt->checkScorecard($data))? $scoreAtt->insert($data): $scoreAtt->getScorecardID($data);
			}
			//preint_r($data);	
			$modelList[$output['modelused']] = $output;
		}
		return $modelList;
	}
	
	
	function scoreIndividualAccount($rowArr,$modelver,$process){
		//array that will be pass used in LOS 		
		//preint_r($rowArr);
		//echo $rowArr['attributes'];
		$attribs = "";
		if($process != 'multiple'){
			$capno = $rowArr;
			$modelver = $this->selectModel($capno);
			$withmodel = 'model selected';
			if($modelver == "") {
				$modelver = 'currentcbsimodel 1';
				$withmodel = 'no model selected';
			}
			$scoremodule = Zend_Controller_Action_HelperBroker::getStaticHelper('ScoreModule2');		
			$smmodel = $scoremodule->storeattr($capno,'single');
			$attribs = $smmodel['attributes'];
			$lastid = $smmodel['lastid'];
		}else{
			$capno = $rowArr['capno'];
			$attribs = $rowArr['attributes'];
			$lastid = $rowArr['lastid'];
		}
				
		parse_str($attribs, $output);				
		$model_fields = getModelsFields($modelver);
		$csmodel = new Model_Creditscore_CSModel();
		$prodType = $csmodel->getProdType($modelver);

		$sum = 0;
		foreach($model_fields as $field){			
			$wt = getWeights($output[$field],$modelver,$field);		
			$ScoreString .= $Amp . $field . '=' . $wt .'&';
			$sum = $sum + $wt;
		}
		$scoretag = Zend_Controller_Action_HelperBroker::getStaticHelper('ScoreTag');
		$scoreR = $scoretag->direct($sum, $output['empbus_status'],$rowArr['capno']);		
		$borrower = new Model_BorrowerAccount();
		$principaldetail = $borrower->fetchRowModel($capno);
		if($principaldetail->deviation){
			$ifDev ='D';
		}	

		$ScoreString .= 'totalscore=' . $sum.'&';
		$ScoreString .= 'lastid=' . $lastid.'&';
		$ScoreString .= 'capno=' . $capno.'&';
		$ScoreString .= 'capbasis=' . $output['capbasis'].'&';
		$ScoreString .= 'empbus_status=' . $output['empbus_status'].'&';
		$ScoreString .= 'modelused=' . $modelver.'&';
		$ScoreString .= 'withmodel=' . $withmodel.'&';	
		$ScoreString .= 'scoretag=' . $output['empbus_status']." ".$sum." ".$scoreR." ".$ifDev."&";	
		$ScoreString .= 'prod=' . $prodType.'&';	
		
		if($process != 'multiple'){
			$csamodel = new Model_CreditscoreAttr();
			$csamodel->updateScore($ScoreString);
			
			echo "<br>Capno: ".$capno.
				 "<br>Model: ".$modelver." (".$prodType.")".
				 "<br>Score: ".$sum.
				 "<br>ScoreTag: ".$output['empbus_status']." ".$sum." ".$scoreR." ".$ifDev.
				 "<br>W/Model: ".$withmodel.
				 "<br>LastID: ".$lastid;
		}
		else{ 
			return $ScoreString;
		}
	}	
	
	public function renameField($outputAttr){
		$famodel = new Model_Creditscore_Fieldsattributes();
		$fatable = $famodel->getFields();
		foreach($fatable as $farow){
			if($outputAttr[$farow->field]){
				if($farow->seq == $outputAttr[$farow->field]) 
					$outputAttr[$farow->field] = $farow->values;
			}
		}
		return $outputAttr;		
	}
	
	public function checkOCS($modelver, $outputAttr, $scoreId, $capno, $sesid){
		$otmodel = new Model_Creditscore_OCSTable();
		$ohmodel = new Model_Creditscore_OCSHistory();
		$ottable = $otmodel->getAllCatNum($modelver);
//		$ocsArr = array();
		$counter = 0;
		foreach($ottable as $otrow){
//			echo "<br>[".$otrow->rule."] [".$otrow->field1."]";
			if($otrow->type == 'String'){
//				echo " [".$outputAttr[$otrow->field1]."] [".$otrow->value1."]";
				if($outputAttr[$otrow->field1] == $otrow->value1){
//					$ocsArr[$counter] = array('rule'=>$otrow->rule, 'rowId'=>$otrow->id);
					$ohmodel->setOCS($sesid, $modelver, $capno, $otrow->rule, $otrow->id, $scoreId);
					$counter++;	
				}			
			}else{
				if($otrow->logic == '&&' || $otrow->logic == '||'){
					if($outputAttr[$otrow->field1] && $outputAttr[$otrow->field2]){
						$eval1 = "(float)\"".$outputAttr[$otrow->field1]."\" ".$otrow->compare1." (float)\"".$otrow->value1."\"";
						$eval2 = "(float)\"".$outputAttr[$otrow->field2]."\" ".$otrow->compare2." (float)\"".$otrow->value2."\"";
						$eval3 = "(".$eval1.") ".$otrow->logic." (".$eval2.")";					
						$eval4 = "return (".$eval3.")? true : false;";	
//						echo " [".$eval3."] [".eval($eval4)."]";				
						if(eval($eval4)){
//							$ocsArr[$counter] = array('rule'=>$otrow->rule, 'rowId'=>$otrow->id);
							$ohmodel->setOCS($sesid, $modelver, $capno, $otrow->rule, $otrow->id, $scoreId);
							$counter++;	
						}
					}
				}else{
					if($outputAttr[$otrow->field1]){
						$eval1 = "(float)\"".$outputAttr[$otrow->field1]."\" ".$otrow->compare1." (float)\"".$otrow->value1."\"";
						$eval2 = "return (".$eval1.")? true : false;";
//						echo " [".$eval1."] [".eval($eval2)."]";
						if(eval($eval2)){
//							$ocsArr[$counter] = array('rule'=>$otrow->rule, 'rowId'=>$otrow->id);
							$ohmodel->setOCS($sesid, $modelver, $capno, $otrow->rule, $otrow->id, $scoreId);
							$counter++;	
						}
					}					
				}	
			}
		} 
//		if($counter > 0) echo "<br>OCS -> ".$counter."<br>";	
		return $counter;	
	
	}
	
    function selectModel($capno)
	{
		$bamodel = new Model_BorrowerAccount();	
		$csmodel = new Model_Creditscore_CSModel();
		$ptmodel = new Model_Creditscore_ProductType();		
		$bcmodel = new Model_Creditscore_BusinessCenters();
		$rpmmodel = new Model_Creditscore_RegularPromoModel();
		$bcmmodel = new Model_Creditscore_BusinessCentersModel();
		
		$bcode = $capno[3].$capno[4];
		$prodType = $ptmodel->getName($capno[1]);

		$bcrow = $bcmodel->getName($bcode);
		$baname = ($bcrow->name == "")? 'Metro Manila' : $bcrow->name; 
		$bcmtable = $bcmmodel->getModelsWithBCs($baname);	
		$barow = $bamodel->getAccount($capno);
		$promoId = ($barow->promo_fid == 0)? "1" : (string)$barow->promo_fid;
		$rpmtable = $rpmmodel->getModelsWithRPs($promoId);
		
		foreach($bcmtable as $bcmrow){
			foreach($rpmtable as $rpmrow){
				if($bcmrow->namever == $rpmrow->namever){
					$csrow = $csmodel->getModelSelection($bcmrow->namever, $prodType);
					if($csrow){ return $csrow->namever;}
				}
			}
		}		
    }
	
	
	
}

	function getModelsFields($namever)
	{
		$fields = array();
		$model_category = new Model_Creditscore_ModelFieldsCategory();
		$output = $model_category->addedFields($namever);
		foreach($output as $x){
			$fields[]= $x->field;
		}	
		$model_range = new Model_Creditscore_ModelFieldsNumeric();
		$output2 = $model_range->addedFields($namever);
		foreach($output2 as $x){
			$fields[]= $x->field;
		}
		return $fields;	
	}	

	function getWeights($fieldvalue,$modelver,$field)
	{	
		if($fieldvalue){
			$fieldsTable = new Model_Creditscore_FieldsTable();
			$fieldRow = $fieldsTable->getType($field);
			$fieldType = $fieldRow->ref_type;
		
			$category = new Model_Creditscore_ModelFieldsCategory();
			$detail = $category->getWeightsForScore($fieldType, $fieldvalue, $field, $modelver);

			$range = new Model_Creditscore_ModelFieldsNumeric();
			$detail2 = $range->getWeightsForScore($modelver, $field);
	
			if(($detail) && ($detail2->count() == 0)) {
				$wt = $detail->wto;
			//echo "none Range-".$field.'-'.$fieldvalue.'<br>';		
			}else if((!$detail) && ($detail2->count() > 0)) {
			//echo "Range-".$field.'-'.$fieldvalue.'<br>';	
				$fieldvalue = str_replace(',','',$fieldvalue);
				// downpayment issue
				if($field == 'downpayment_percent' || $field == 'loanterm'){
					foreach($detail2 as $x) : 
						if (($fieldvalue > $x->rfrom) && ($fieldvalue <= $x->rto)){
							$wt = $x->wto;			
						}
					endforeach;
				} else {
					foreach($detail2 as $x) : 
						if (($fieldvalue >= $x->rfrom) && ($fieldvalue < $x->rto)){
							$wt = $x->wto;			
						}
					endforeach;
				}
			}else{
			//echo "Undefined-".$field.'-'.$fieldvalue.'<br>';
			}	
			return $wt;
		} else {return 0;}
	}

	