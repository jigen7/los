<?php
class Zend_View_Helper_GetRelationship extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getRelationship($capno){

	$table = new Model_BorrowerAccount();
	$select = $table->select();
	$select->where('capno like ?',$capno);
	$detail = $table->fetchRow($select);
	
	return $detail->relation;
	
	}
	
}
?>