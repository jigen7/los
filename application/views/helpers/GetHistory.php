<?php
class Zend_View_Helper_GetHistory extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getHistory($capno,$process){

	$history = new Model_AccountHistory();
	$select = $history->select();
	$select->where('capno like ?',$capno);	
	$select->order('id DESC');
	
	$historyDetail = $history->fetchRow($select);					
		
	if ($process == 'by'){
	return $historyDetail->by;
	}
	else if($process == 'date'){
	return $historyDetail->date;
	}
	
	}
}
	

?>