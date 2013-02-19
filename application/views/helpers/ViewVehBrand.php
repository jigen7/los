<?php
class Zend_View_Helper_ViewVehBrand extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewVehBrand($seq){

	$table = new Model_CategoryValues();
	$select = $table->select();
	$select->where('name like ?','VehBrand')
			->where('seq =?',$seq);
	$row = $table->fetchRow($select);

	return $row->values;
	}
	

	
}
?>