<?php

class Zend_Controller_Action_Helper_FilterHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct($formData, $namever, $username, $prodType)
	{	
		$bmodel = new Model_Creditscore_BusinessCenters();
		$rmodel = new Model_Creditscore_RegularPromoSource();
		$bcmodel = new Model_Creditscore_BusinessCentersModel();
		$rpmodel = new Model_Creditscore_RegularPromoModel();
		$atmodel = new Model_Creditscore_Audittrail();
		
		if($formData['hiddenA'] == 'addbusctr'){
			if($bcmodel->checkBusinessCenter($namever, $formData['hiddenS'])){
				$code = $bmodel->getCode($formData['hiddenS']);
				$bcmodel->addBusinessCenter($namever, $formData['hiddenS'], $code->code);
				$atmodel->fieldAuditTrail($namever, $prodType, $username, $formData['hiddenS'], 'Added', '');			
			}
		}
		else if($formData['hiddenA'] == 'addregpro'){
			if($rpmodel->checkRegularPromo($namever, $formData['hiddenS'])){
				$code = $rmodel->getCode($formData['hiddenS']);
				$rpmodel->addRegularPromo($namever, $formData['hiddenS'], $code->id);
				$atmodel->fieldAuditTrail($namever, $prodType, $username, $formData['hiddenS'], 'Added', '');			
			}
		}else if($formData['hiddenA'] == 'delbusctr'){
			$row = $bcmodel->getBusinessCenter($formData['hiddenS']);
			$bcmodel->deleteBusinessCenter($formData['hiddenS']);	
			$atmodel->fieldAuditTrail($namever, $prodType, $username, $row->busctr, 'deleted', '');		
		}else if($formData['hiddenA'] == 'delregpro'){
			$row = $rpmodel->getRegularPromo($formData['hiddenS']);	
			$rpmodel->deleteRegularPromo($formData['hiddenS']);
			$atmodel->fieldAuditTrail($namever, $prodType, $username, $row->regpro, 'deleted', '');				
		}
    }

}

