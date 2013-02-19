<?php

class Zend_Controller_Action_Helper_RenameHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct($pName, $nName)
	{
		$ahmodel = new Model_Creditscore_Accounthistory();
		$atmodel = new Model_Creditscore_Audittrail();
		$mcmodel = new Model_Creditscore_ModelFieldsCategory();
		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();
		$rtmodel = new Model_Creditscore_RulesTable();
		$otmodel = new Model_Creditscore_OCSTable();
		$bcmodel = new Model_Creditscore_BusinessCentersModel();
		$rpmodel = new Model_Creditscore_RegularPromo();
		
		$ahmodel->renameModel($pName, $nName);
		$atmodel->renameModel($pName, $nName);
		$mcmodel->renameModel($pName, $nName);
		$mnmodel->renameModel($pName, $nName);
		$rtmodel->renameModel($pName, $nName);
		$otmodel->renameModel($pName, $nName);		
		$bcmodel->renameModel($pName, $nName);
		$rpmodel->renameModel($pName, $nName);
   	}

}

  