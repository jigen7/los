<?php

class Zend_Controller_Action_Helper_DefineFieldHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct($fieldSelected, $namever, $prodType, $username)
	{	
		$fsmodel = new Model_Creditscore_Fieldsselected();
		$atmodel = new Model_Creditscore_Audittrail();
		$ftmodel = new Model_Creditscore_FieldsTable();
		
		$fieldName = $ftmodel->getField($fieldSelected);
		if($fsmodel->checkFieldType($fieldSelected) == "String"){
			if($fsmodel->checkCField($namever, $fieldSelected)) {
				$fsmodel->addCategoryField($fieldSelected, $namever);
			    $atmodel->fieldAuditTrail($namever, $prodType, $username, $fieldName->name, 'Added', '');	
			}
		}else{
			if($fsmodel->checkNField($namever, $fieldSelected)) {
				$fsmodel->addNumericField($fieldSelected, $namever);
			    $atmodel->fieldAuditTrail($namever, $prodType, $username, $fieldName->name, 'Added', '');
			}
		}
    }

}

