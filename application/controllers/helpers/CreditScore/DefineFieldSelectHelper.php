<?php

class Zend_Controller_Action_Helper_DefineFieldSelectHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct()
	{	
		$form = new Form_Creditscore_ModelDefineFields();
		$ftmodel = new Model_Creditscore_FieldsTable();
		$fsmodel = new Model_Creditscore_Fieldsselected();

		$table = $ftmodel->getFields();
		foreach($table as $row){
			$form->fields->addMultiOption($row->field, $row->name);
		}
		return $form->fields;
    }

}

