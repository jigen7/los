<?php

class Zend_Controller_Action_Helper_RenameFieldHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct($field)
	{	
		$ftmodel = new Model_Creditscore_FieldsTable();
		$ftrow = $ftmodel->getField($field);
		return $ftrow->name;
    }

}

