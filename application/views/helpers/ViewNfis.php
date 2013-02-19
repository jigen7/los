<?php
class Zend_View_Helper_ViewNfis extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewNfis($seq){
	
	$table = new Model_ListSelectValues();
	$select = $table->select();
	
	if ($seq){
	$select->where('type like ?','NFIS')
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