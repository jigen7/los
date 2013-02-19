<?php

class Zend_Controller_Action_Helper_NUpdateHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct($formData, $namever, $prodType, $username)
	{	
		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();
		
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
		$check = $atmodel->checkFromTo($nameField[0], $nameField[1], $mnrow->namefield, $att);	
		if($check == "true"){ 
			$weight = $mnmodel->getPrevWeight($nameField[0]." ".--$nameField[1], $mnrow->field, $att);
			$atmodel->nWeightAuditTrail($namever, $prodType, $username, $key, $weight, $value, $att);
		}else if($mnrow->wto != $value){
			$atmodel->nWeightAuditTrail($namever, $prodType, $username, $key, $mnrow->wto, $value, $att);
		}		
	}


