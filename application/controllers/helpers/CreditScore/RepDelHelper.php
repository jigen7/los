<?php

class Zend_Controller_Action_Helper_RepDelHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct($formData, $prodType, $username)
	{	if($formData['button']){
			$atmodel = new Model_Creditscore_Audittrail();
			$fsmodel = new Model_Creditscore_Fieldsselected();		
			$mnmodel = new Model_Creditscore_ModelFieldsNumeric();	
			
			if($formData['button'] == "Replicate"){
				$hidden = $formData['hiddenField'];
				$nameField = explode("-", $hidden);
				$fsmodel->addNumericField($nameField[1],$nameField[0]);
			}else if($formData['button'] == "Delete"){
				$hidden = $formData['hiddenField'];
				$nameField = explode("-", $hidden);
				$mnrow = $mnmodel->getRow($nameField[1]);
				$atmodel->fieldAuditTrail($nameField[0], $prodType, $username, $mnrow->namefield, 'deleted', '');
				$mnmodel->deletereplicatefield($nameField[1]);					
			}
		}
    }

}
