<?php
class Zend_View_Helper_GetRtsRemarks extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getRtsRemarks($capno){

	$history = new Model_AccountHistory();
	$select = $history->select();
	$select->where('capno like ?',$capno);	
	$select->order('id DESC');
	$user = login_user_role();
	if($user == 'MA'){
		$select->where('status like ?','CA - RTMA');	
		$historyDetail = $history->fetchRow($select);					
		
	}else if($user== 'CA'){
		$select->where('status like ?','CO - RTCA');	
		$historyDetail = $history->fetchRow($select);					
		
	}
	return str_replace("\n",'\t',$historyDetail->remarks);
	}
}
	

?>