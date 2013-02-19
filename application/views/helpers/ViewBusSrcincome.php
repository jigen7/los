<?php
class Zend_View_Helper_ViewBusSrcincome extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewBusSrcincome($seq){

	$table = new Model_CategoryValues();
	$select = $table->select();
	$select->where('name like ?','BusinessSrcIncome')
			->where('seq =?',$seq);
	$row = $table->fetchRow($select);

	return $row->values;
	}
	

	
}
?>