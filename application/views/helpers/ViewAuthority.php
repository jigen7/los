<?php
class Zend_View_Helper_ViewAuthority extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewAuthority($username){

	$authority = new Model_Users();
	$select = $authority->select();
	
	
	if ($username){
	
		$select->where('username like ?',$username);
		$authoritydetail = $authority->fetchRow($select);
		
		if($authoritydetail){
		return $authoritydetail->name;
		}else {
			return strtoupper($username);
	 }
	}else{
		return '';
	}

	

}
	

	
}
?>