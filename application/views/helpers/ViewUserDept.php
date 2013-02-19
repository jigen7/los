<?php
class Zend_View_Helper_ViewUserDept extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewUserDept($username){
	//Returns the name of the MA base on its Username
	$ma = new Model_Users();
	$select = $ma->select();
	$select->where('username like ?',$username);
	$madetail = $ma->fetchRow($select);
	
	if ($madetail->department){
	return $madetail->department;
	}
	else {return "";}
	}
	

	
}
?>