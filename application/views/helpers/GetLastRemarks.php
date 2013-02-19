<?php
class Zend_View_Helper_GetLastRemarks extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getLastRemarks($capno){

	$user = login_user_role();

	$history = new Model_AccountHistory();
	$select = $history->select();
	$select->where('capno like ?',$capno);	
	$select->order('id DESC');

	$historyDetail = $history->fetchRow($select);					
	return str_replace("\n",'',$historyDetail->remarks);
	
	
	}
}
	

?>