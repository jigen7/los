<?php

class Zend_Controller_Action_Helper_ModelSelectionHelper extends Zend_Controller_Action_Helper_Abstract
{
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
		$rpmtable = $rpmmodel->getModelsWithRPs((string)$barow->promo_fid);
		
		foreach($bcmtable as $bcmrow){
			foreach($rpmtable as $rpmrow){
				if($bcmrow->namever == $rpmrow->namever){
					$csrow = $csmodel->getModelSelection($bcmrow->namever, $prodType);
					if($csrow){ return $csrow->namever;
/*						$bt = $bcmmodel->addedBusinessCenter($rpmrow->namever);
						$rt = $rpmmodel->addedRegularPromo($rpmrow->namever);
						$x = ""; foreach($bt as $b){$x = $x.$b->busctr.",";}
						$y = ""; foreach($rt as $r){$y = $y.$r->regpro.",";}
						echo "<br><br>Capno: ".$capno.
								 "<br>Name: ".$barow->borrower_lname.", ".$barow->borrower_fname." ".$barow->borrower_mname.
								 "<br>Model Used: ".$csrow->namever.
								 "<br>BCs: ".$x.
								 "<br>RPs: ".$y.
								 "<br>Score: ".$this->scoreAccount($capno,$rpmrow->namever);
*/					}
				}
			}
		}		
    }
	
//#########################################################################################	
/*	
	function scoreAccount($capno,$model){
		
		$borrower = new Model_BorrowerAccount();			
		$csmodel = new Model_Creditscore_CSModel();	
		$scoreAtt = new Model_Creditscore_ScoreAttributes();

		$scoremodule = Zend_Controller_Action_HelperBroker::getStaticHelper('ScoreModule2');
		$string = $scoremodule->storeattr($capno,'multiple');		

		$principaldetail = $borrower->fetchRowModel($capno);
		if($principaldetail->deviation){$ifDev ='D';}		
		parse_str($this->scoreIndivdualAccount($string,$model),$output);
	
		$scoretag = Zend_Controller_Action_HelperBroker::getStaticHelper('ScoreTag');
		$scoreR = $scoretag->direct($output['totalscore'], $output['empbus_status'],$output['capno']);		
		
		$data = array(
					'capno'=>$accounts->account,
					'by'=>login_user(),
					'datetime'=>date("r"),
					'attributes'=>$scoremodule->storeattr($capno,'attributes'),
					'score_attributes'=>$this->scoreIndivdualAccount($string,$model),
					'model_used'=>$model,
					'score'=>$output['totalscore'],
					'scoretag'=>$output['empbus_status'].' '.$output['totalscore'].' '.$scoreR.' '.$ifDev,
					'session'=>$session,
					'prod'=>$prod
				);
		return $data['score']." -> ".$data['scoretag'];
	}	

*/
/*	
	function scoreIndivdualAccount($rowArr,$modelver){
		if(!$modelver){$modelver = 'currentcbsimodel 1';}
				
		parse_str($rowArr['attributes'], $output);				
		$model_fields = getModelsFields($modelver);
		$sum = 0;
		foreach($model_fields as $field){			
			$wt = getWeights($output[$field],$modelver,$field);		
			$ScoreString .= $Amp . $field . '=' . $wt .'&';
			$sum = $sum + $wt;
		}
		$ScoreString .= 'totalscore=' . $sum.'&';
		$ScoreString .= 'lastid=' . $rowArr['lastid'].'&';
		$ScoreString .= 'capno=' . $rowArr['capno'].'&';
		$ScoreString .= 'capbasis=' . $output['capbasis'].'&';
		$ScoreString .= 'empbus_status=' . $output['empbus_status'].'&';
		$ScoreString .= 'modelused=' . $modelver.'&';	
		return $ScoreString;
			
	}	
*/

}
/*	function getModelsFields($namever)
	{
		$model_category = new Model_Creditscore_ModelFieldsCategory();
		$array = array('field','namever');
		$select = $model_category->select();
		$select->where('namever LIKE ?',$namever)
		       ->distinct('field')
		       ->from($model_category,$array);
		$output = $model_category->fetchAll($select);
		$fields = array();
		foreach($output as $x){
			$fields[]= $x->field;
		}
		$model_range = new Model_Creditscore_ModelFieldsNumeric();
		$select2 = $model_range->select();
		$select2->where('namever LIKE ?',$namever)
		        ->distinct('field')
		        ->from($model_range,$array);
		$output2 = $model_range->fetchAll($select2);

		foreach($output2 as $x){
			$fields[]= $x->field;
		}
		return $fields;	
	}	
	
*/
/*

	function getWeights($fieldvalue,$modelver,$field)
	{
		$category = new Model_Creditscore_ModelFieldsCategory();
	
		if($fieldvalue){
			$fieldsTable = new Model_Creditscore_FieldsTable();
			$selectfield = $fieldsTable->select();
			$selectfield->where("field like ?",$field);
			$fieldDetail = $fieldsTable->fetchRow($selectfield);
			$fieldType = $fieldDetail->ref_type;

		
			// Query the Category Table
			$select = $category->select();
			// to change if id is used
			if($fieldType == 'String'){
				$select->where("attribute like ?",$fieldvalue);
			}
			else if($fieldType == 'Numeric'){
				$select->where("seq = ?",$fieldvalue);
			}
			$select->where("field like ?",$field);
			$select->where("namever like ?",$modelver);
			$detail = $category->fetchRow($select);
		
			//Query the Range Numeric Table
			$range = new Model_Creditscore_ModelFieldsNumeric();
			$select = $range->select();
			$select->where("namever like ?",$modelver);
			$select->where("field like ?",$field);
			$detail2 = $range->fetchAll($select);
			$detail2count = $detail2->count();
			//echo $detail2count;
		
			if(($detail) && ($detail2count == 0)) {
				$wt = $detail->wto;
			//echo "none Range-".$field.'-'.$fieldvalue.'<br>';
			
			
			}else if((!$detail) && ($detail2count > 0)) {
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
	// insert OCS code
	}
*/