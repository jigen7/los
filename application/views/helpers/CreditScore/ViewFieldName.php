<?php
class Zend_View_Helper_CreditScore_ViewFieldName extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
	}
		
	function viewFieldName($field){	
		if($field == "") return "";
		$table = new Model_Creditscore_Fieldsattributes();
		$select = $table->select();
		$select->where('field LIKE ?',$field);
		$row = $table->fetchRow($select);
		if($row->name != "") return $row->name;
		
		$table = new Model_Creditscore_FieldsTable();
		$row = $table->getField($field);
		return $row->name;
	}
	
}
?>