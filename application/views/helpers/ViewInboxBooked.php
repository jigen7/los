<?php
class Zend_View_Helper_ViewInboxBooked extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewInboxBooked(){

		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();

		$select->where("account_status = 'Booked'");

		
		
		$accntdetail = $accnt->fetchAll($select)->count();

	return "<font color='RED'><b>".$accntdetail."</b></font>";
	}
	

	
}
?>