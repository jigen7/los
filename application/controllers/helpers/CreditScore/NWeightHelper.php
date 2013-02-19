<?php

class Zend_Controller_Action_Helper_NWeightHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct($formData, $namever, $prodType, $username)
	{	
		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();
		$atmodel = new Model_Creditscore_Audittrail();

		foreach ($formData['rfrow'] as $key => $value) {
			$mnrow = $mnmodel->getRow($key);
			$past = $mnrow->rfrom;
			$mnmodel->addFieldsAndFRange($key, $value);	
			if($past != $value){
				$att = "MIN";
				$atmodel->nWeightAuditTrail($namever, $prodType, $username, $key, $past, $value, $att);		
			}
		}
		foreach ($formData['rtrow'] as $key => $value) {
			$mnrow = $mnmodel->getRow($key);
			$past = $mnrow->rto;
			$mnmodel->addFieldsAndTRange($key, $value);	
			if($past != $value){
				$att = "MAX";
				$atmodel->nWeightAuditTrail($namever, $prodType, $username, $key, $past, $value, $att);		
			}			
		}
		foreach ($formData['nrow'] as $key => $value) {
			$mnrow = $mnmodel->getRow($key);
			funcNWeightHelper($key, $namever, $prodType, $username, $value);
			$mnmodel->addFieldsAndAttrib($key, $mnrow->wto, $value);	
		}				
	}
}

	function funcNWeightHelper($key, $namever, $prodType, $username, $value)
	{
		$atmodel = new Model_Creditscore_Audittrail();
		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();
		$check = "false";
		$mnrow = $mnmodel->getRow($key);	
		$nameField = explode(" ", $mnrow->namever);	
		$att = $mnrow->rfrom." - ".$mnrow->rto;
		$check = $atmodel->checkFromTo($nameField[0], $nameField[1], $mnrow->namefield, $att);	
		if($check == "true"){ 
			$atmodel->nWeightAuditTrail($namever, $prodType, $username, $key, 0, $value, $att);
		}else if($mnrow->wto != $value){
			$atmodel->nWeightAuditTrail($namever, $prodType, $username, $key, $mnrow->wto, $value, $att);
		}		
	}
	
