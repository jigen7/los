<?php
class Zend_View_Helper_CreditScore_BusinessCenter extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	function setView($view){$this->_view = $view;}
		
	function businessCenter($namever){
		$bcmodel = new Model_Creditscore_BusinessCentersModel();
		$table = $bcmodel->addedBusinessCenter($namever);
		$count = count($table);
		$str = ""; $ctr=0;
		foreach($table as $row){
			$ctr++;
			$comma = ($count != $ctr)? ", " : " ";
			$str = $str.$row->busctr.$comma;		
		}
		return $str;
	}
	
}
?>