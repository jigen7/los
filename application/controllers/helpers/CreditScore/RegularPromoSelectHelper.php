<?php

class Zend_Controller_Action_Helper_RegularPromoSelectHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct()
	{	
		$form = new Form_Creditscore_RegularPromo();
		$rpmodel = new Model_Creditscore_RegularPromoSource();

		$table = $rpmodel->getAllRegularPromo();
		$form->regpro->addMultiOption('','');
		foreach($table as $row){
			//$form->regpro->addMultiOption($row->name, $row->name);
			$form->regpro->addMultiOption($row->promo_name, $row->promo_name);
		}
		return $form->regpro;
    }

}

