<?php

class Zend_Controller_Action_Helper_BusinessCenterSelectHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct()
	{	
		$form = new Form_Creditscore_BusinessCenters();
		$bcmodel = new Model_Creditscore_BusinessCenters();

		$table = $bcmodel->getAllBusinessCenter();
		$form->busctr->addMultiOption('','');
		foreach($table as $row){
			$form->busctr->addMultiOption($row->name, $row->name);
		}
		return $form->busctr;
    }

}

