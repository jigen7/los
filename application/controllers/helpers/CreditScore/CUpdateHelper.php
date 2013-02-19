<?php

class Zend_Controller_Action_Helper_CUpdateHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct($formData, $namever, $prodType, $username)
	{	
		$mcmodel = new Model_Creditscore_ModelFieldsCategory();
			
		foreach ($formData['crow'] as $key => $value) {
				$mcrow = $mcmodel->getRow($key);			
				CWeightHelper($key, $namever, $prodType, $username, $value);
				$mcmodel->updateFieldsAndAttrib($key, $value);	
		}
    }

}
	
    function CWeightHelper($key, $namever, $prodType, $username, $value)
	{
		$atmodel = new Model_Creditscore_Audittrail();
		$mcmodel = new Model_Creditscore_ModelFieldsCategory();
		$check = "false";
		$mcrow = $mcmodel->getRow($key);		
		$nameField = explode(" ", $namever);	
		$check = $atmodel->checkFromTo($nameField[0], $nameField[1], $mcrow->namefield, $mcrow->attribute);	
		if($check == "true"){ 
			$weight = $mcmodel->getPrevWeight($nameField[0]." ".--$nameField[1], $mcrow->field, $mcrow->attribute);
			$atmodel->cWeightAuditTrail($namever, $prodType, $username, $key, $weight, $value);
		}else if($mcrow->wto != $value){
			$atmodel->cWeightAuditTrail($namever, $prodType, $username, $key, $mcrow->wto, $value);
		}
   }	

