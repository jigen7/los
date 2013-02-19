<?php
class Zend_View_Helper_ViewBusPos extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewBusPos($seq){

	$table = new Model_CategoryValues();
	$select = $table->select();
	if ($seq){
	$select->where('name like ?','EmpPosition')
			->where('seq =?',$seq);
	$row = $table->fetchRow($select);

	return $row->values;
	}
	
	else {
	return ' ';
	}
	}
}
?>