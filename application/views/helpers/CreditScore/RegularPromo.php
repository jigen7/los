<?php
class Zend_View_Helper_CreditScore_RegularPromo extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	function setView($view){$this->_view = $view;}
		
	function regularPromo($namever){
		$rpmodel = new Model_Creditscore_RegularPromoModel();
		$table = $rpmodel->addedRegularPromo($namever);
		$count = count($table);
		$str = ""; $ctr=0;
		foreach($table as $row){
			$ctr++;
			$comma = ($count != $ctr)? ", " : " ";
			$str = $str.$row->regpro.$comma;		
		}
		return $str;
	}
	
}
?>