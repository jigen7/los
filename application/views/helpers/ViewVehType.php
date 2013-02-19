<?php
class Zend_View_Helper_ViewVehType extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewVehType($seq){

	$table = new Model_CategoryValues();
	$select = $table->select();
	$select->where('name like ?','VehType')
			->where('seq =?',$seq);
	$row = $table->fetchRow($select);

	return $row->values;
	}
	

	
}
?>