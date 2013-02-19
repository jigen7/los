<?php
class Zend_View_Helper_GetLastDate extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getLastDate($capno){

	$user = login_user_role();

	$history = new Model_AccountHistory();
	$select = $history->select();
	$select->where('capno like ?',$capno);	
	$select->order('id DESC');

	$historyDetail = $history->fetchRow($select);					
	return date('m-d-Y h:i a',strtotime($historyDetail->date));
	
	
	
	}
}
	

?>