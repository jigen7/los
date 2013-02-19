<?php

class Zend_Controller_Action_Helper_UpdateHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct($formData, $namever, $prodType, $username)
	{	
		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();
		$mcmodel = new Model_Creditscore_ModelFieldsCategory();
		
		foreach ($formData['rfrow'] as $key => $value) {
				$mnrow = $mnmodel->getRow($key);
				$mnmodel->addFieldsAndFRange($key, $value);	
		}
		foreach ($formData['rtrow'] as $key => $value) {
				$mnrow = $mnmodel->getRow($key);
				$mnmodel->addFieldsAndTRange($key, $value);	
		}
		foreach ($formData['nrow'] as $key => $value) {
				$mnrow = $mnmodel->getRow($key);
				NWeightHelper($key, $namever, $prodType, $username, $value);
				$mnmodel->updateFieldsAndAttrib($key, $value);	
		}				
		foreach ($formData['crow'] as $key => $value) {
				$mcrow = $mcmodel->getRow($key);			
				CWeightHelper($key, $namever, $prodType, $username, $value);
				$mcmodel->updateFieldsAndAttrib($key, $value);	
		}
    }

}

	function NWeightHelper($key, $namever, $prodType, $username, $value)
	{
		$atmodel = new Model_Creditscore_Audittrail();
		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();
		$check = "false";
		$mnrow = $mnmodel->getRow($key);	
		$nameField = explode(" ", $mnrow->namever);	
		$att = $mnrow->rfrom." - ".$mnrow->rto;
		$check = $atmodel->checkFromTo($nameField[0], $nameField[1], $mnrow->field, $att);	
		if($check == "true"){ 
			$atmodel->nWeightAuditTrail($namever, $prodType, $username, $key, 0, $value, $att);
		}else if($mnrow->wto != $value){
			$atmodel->nWeightAuditTrail($namever, $prodType, $username, $key, $mnrow->wto, $value, $att);
		}		
	}
	
    function CWeightHelper($key, $namever, $prodType, $username, $value)
	{
		$atmodel = new Model_Creditscore_Audittrail();
		$mcmodel = new Model_Creditscore_ModelFieldsCategory();
		$check = "false";
		$mcrow = $mcmodel->getRow($key);		
		$nameField = explode(" ", $mcrow->namever);	
		$check = $atmodel->checkFromTo($nameField[0], $nameField[1], $mcrow->field, $mcrow->attribute);	
		if($check == "true"){ 
			$atmodel->cWeightAuditTrail($namever, $prodType, $username, $key, 0, $value);
		}else if($mcrow->wto != $value){
			$atmodel->cWeightAuditTrail($namever, $prodType, $username, $key, $mcrow->wto, $value);
		}
   }	

