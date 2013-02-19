<?php
class Zend_View_Helper_CreditScore_GetRemark extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
	}
	
	function getRemark($namever, $prod){
		$csmodel = new Model_Creditscore_CSModel();
		$csrow = $csmodel->getModel($namever, $prod);
		echo $csrow->remarks;
   }

}