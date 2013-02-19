<?php
class Zend_View_Helper_CreditScore_ViewModelName extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
	}
		
	function viewModelName($id){
		$table = new Model_Creditscore_CSModel();
		$select = $table->select();
		$select->where('id = ?',(int)$id);
		$row = $table->fetchRow($select);
		return $row->namever;
	}
	
}
?>