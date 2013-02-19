<?php
class Zend_View_Helper_ViewCo extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewCo($username){

	$co = new Model_Users();
	$select = $co->select();
	if ($username){
	$select->where('username like ?',$username);
	$codetail = $co->fetchRow($select);
	
	return $codetail->name;
	}
	
	else{
	return ' ';
	
	}
	

}
	

	
}
?>