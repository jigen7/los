<?php

class Zend_View_Helper_CreditScore_RetrieveHelper extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
	}	
	
    function RetrieveHelper($namever, $namefield, $att)
	{	
		$nameEx = explode(" ", $namever);
		$ver = $nameEx[1];
		$ver = (int)$ver;
		$ver--;
		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();
		$mcmodel = new Model_Creditscore_ModelFieldsCategory();
		$ftmodel = new Model_Creditscore_FieldsTable();
		$ftrow = $ftmodel->getNFType($namefield);

		if($ftrow->type != "Numeric"){
			return $mcmodel->getWeight($nameEx[0]." ".$ver, $namefield, $att);
//			$weight = $mcmodel->getWeight($nameEx[0]." ".$ver, $namefield, $att);
//			if($weight > 0 || $weight < 0) return $weight;			
		}	
		else{
			return $mnmodel->getWeight($nameEx[0]." ".$ver, $namefield, $att);
//			$weight = $mnmodel->getWeight($nameEx[0]." ".$ver, $namefield, $att);
///			if($weight > 0 || $weight < 0) return $weight;
//			else return $weight = 0;
		}
	}

}
