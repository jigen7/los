<?php
class Zend_View_Helper_ViewCa extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewCa($username){

	$ca = new Model_Users();
	$select = $ca->select();
	$select->where('username like ?',$username);
	$cadetail = $ca->fetchRow($select);
	
	
	return $cadetail->name;
	}
	

	
}
?>