<?php
class Zend_View_Helper_ViewMa extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
	}
		
	function viewMa($username){
	//Returns the name of the MA base on its Username
	if ($username){
	$ma = new Model_Users();
	$select = $ma->select();
	$select->where('username like ?',$username);
	$madetail = $ma->fetchRow($select);
	
	if ($madetail->name){
	return $madetail->name;
	}
	else {return $username;}
	}
	else {
		return $username;
	}
	
	
	}
	
}
?>