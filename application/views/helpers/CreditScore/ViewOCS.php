<?php
class Zend_View_Helper_CreditScore_ViewOCS extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
	}
		
	function viewOCS($id){
		$ftmodel = new Model_Creditscore_FieldsTable();
		$table = new Model_Creditscore_OCSTable();
		$select = $table->select();
		$select->where('id = ?',(int)$id);
		$row = $table->fetchRow($select);
		if($row->type == "String"){
			$ftrow = $ftmodel->getField($row->field1);
			return $ftrow->name." = ".$row->value1;
		}else{
			$ftrow1 = $ftmodel->getField($row->field1);
			$ftrow2 = $ftmodel->getField($row->field2);
			return $ftrow1->name." ".$row->compare1." ".$row->value1." ".$row->logic." ".$ftrow2->name." ".$row->compare2." ".$row->value2;
		}
	}
	
}
?>