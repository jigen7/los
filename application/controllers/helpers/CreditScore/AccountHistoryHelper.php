<?php

class Zend_Controller_Action_Helper_AccountHistoryHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct($namever, $prodType, $username, $process, $remarks)
	{	
		$csmodel = new Model_Creditscore_CSModel();
		$ahmodel = new Model_Creditscore_Accounthistory();	
	
		$csrow = $csmodel->getModel($namever, $prodType);				
		$check = $ahmodel->checkAccountHistory($namever, $prodType, $process);
		if($check == "true") $ahmodel->updateAccountHistory($namever, $prodType, $process);					
		else $ahmodel->setAccountHistory($csrow->name, $csrow->version, $process, $username, $remarks);					
    }

}
