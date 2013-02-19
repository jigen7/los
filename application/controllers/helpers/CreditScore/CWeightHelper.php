<?php

class Zend_Controller_Action_Helper_CWeightHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct($formData, $namever, $prodType, $username)
	{
		$mcmodel = new Model_Creditscore_ModelFieldsCategory();
		foreach ($formData['crow'] as $key => $value) {
			$mcrow = $mcmodel->getRow($key);	
			funcCWeightHelper($key, $namever, $prodType, $username, $value);		
			$mcmodel->addFieldsAndAttrib($key, $mcrow->wto, $value);	
		}
   }

}

    function funcCWeightHelper($key, $namever, $prodType, $username, $value)
	{
		$atmodel = new Model_Creditscore_Audittrail();
		$mcmodel = new Model_Creditscore_ModelFieldsCategory();
		$check = "false";
		$mcrow = $mcmodel->getRow($key);		
		$nameField = explode(" ", $mcrow->namever);	
		$check = $atmodel->checkFromTo($nameField[0], $nameField[1], $mcrow->namefield, $mcrow->attribute);	
		if($check == "true"){ 
			$atmodel->cWeightAuditTrail($namever, $prodType, $username, $key, 0, $value);
		}else if($mcrow->wto != $value){
			$atmodel->cWeightAuditTrail($namever, $prodType, $username, $key, $mcrow->wto, $value);
		}
   }