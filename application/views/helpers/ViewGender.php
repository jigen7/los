<?php
class Zend_View_Helper_ViewGender extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewGender($seq){

	$table = new Model_CategoryValues();
	$select = $table->select();
	$select->where('name like ?','Gender')
			->where('seq =?',$seq);
	$row = $table->fetchRow($select);


	return $row->values;
	}
	
}
?>