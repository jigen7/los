<?php
class Zend_View_Helper_GetDecideddate extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getDecideddate($capno,$status){

	$history = new Model_AccountHistory();
	$select = $history->select();
	$select->where('capno like ?',$capno);	
	$select->order('id DESC');
	$user = login_user_role();

		$select->where('status like ?',$status);	
		$historyDetail = $history->fetchRow($select);					
		

	return $historyDetail->date;
	
	
	}
}
	

?>