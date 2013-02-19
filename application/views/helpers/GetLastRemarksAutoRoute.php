<?php
class Zend_View_Helper_GetLastRemarksAutoRoute extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getLastRemarksAutoRoute($capno,$role){


	$history = new Model_AccountHistory();
	$select = $history->select();
	$select->where('capno like ?',$capno);	
	$select->where('status like ?',$role.' -%');	

	$select->order('id DESC');

	$historyDetail = $history->fetchRow($select);					
	return $historyDetail->remarks;
	
	
	}
}
	

?>