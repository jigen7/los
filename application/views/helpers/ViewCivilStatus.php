<?php
class Zend_View_Helper_ViewCivilStatus extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewCivilStatus($seq){

	$table = new Model_CategoryValues();
	$select = $table->select();
	if ($seq){
	$select->where('name like ?','CivilStatus')
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