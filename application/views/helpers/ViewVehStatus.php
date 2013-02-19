<?php
class Zend_View_Helper_ViewVehStatus extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewVehStatus($seq){

	$table = new Model_CategoryValues();
	$select = $table->select();
	$select->where('name like ?','VehStatus')
			->where('seq =?',$seq);
	$row = $table->fetchRow($select);

	return $row->values;
	}
	

	
}
?>