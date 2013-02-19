<?php
class Zend_View_Helper_GetColorAutoRouting extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getColorAutoRouting($capno,$group){

	//use in autorouting Sept 14,2010
	$user = Zend_Auth::getInstance()->getIdentity();
	$statusTable = new Model_Admin_AccountStatus();
	$count = 0;
	if($group == 'crecom'){
		$accountTable = new Model_AutoRouting_AccountsCrecom();
		$select = $accountTable->select();
		$select->where('capno like ?',$capno);
		$select->where('"user" like ?',$user->username);
		
		$count = $accountTable->fetchAll($select)->count();
		
		
	}
	//		$accountTable = new Model_AutoRouting_AccountsSubCrecom();


	if($count > 0){
		return "#458B00";

	}else{
		return "WHITE"; 
	}
}
}
	

?>