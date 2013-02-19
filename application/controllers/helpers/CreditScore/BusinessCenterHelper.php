<?php

class Zend_Controller_Action_Helper_BusinessCenterHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct($namever, $busctr, $username, $prodType)
	{	
		$bcmodel = new Model_Creditscore_BusinessCentersModel();
		$rpmodel = new Model_Creditscore_RegularPromoModel();
		$atmodel = new Model_Creditscore_Audittrail();
		
		if($bcmodel->checkBusinessCenter($namever, $busctr)){
			$bcmodel->addBusinessCenter($namever, $busctr);
			$atmodel->fieldAuditTrail($namever, $prodType, $username, $busctr, 'Added', '');			
		}
    }

}

