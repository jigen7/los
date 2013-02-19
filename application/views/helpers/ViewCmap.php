<?php
class Zend_View_Helper_ViewCmap extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewCmap($seq){
	
	$table = new Model_ListSelectValues();
	$select = $table->select();
	
	if ($seq){
	$select->where('type like ?','CMAP')
			->where('value like ?',$seq);
	$row = $table->fetchRow($select);


	return $row->seq;
	}
	else {
		return "";
	}
	}
	
	
}
?>