<?php
class Zend_View_Helper_ViewAccountStatusDept extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewAccountStatusDept($status){

	if($status){
	$table = new Model_Admin_AccountStatus();
	$select = $table->select();
	$select->where('status like ?',$status);
	$detail = $table->fetchRow($select);
		if($detail->department){
				return $detail->department;
		}else { return '';}
	}else {
		return '';
	}
	
	}
}
?>