<?php
class Zend_View_Helper_GetLastDateAutoRoute extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		/**
		 * Paolo Marco Manarang 
		 * March 08,2010 
		 * 
		 * A view elper to return the last date base on the current login user 
		 * used in the autorouting process
		 * 
		 * @param object $capno
		 * @return 
		 */
	function getLastDateAutoRoute($capno){

	$user = login_user_role();

	$history = new Model_AccountHistory();
	$select = $history->select();
	$select->where('capno like ?',$capno);	
	//$select->where('status like ?',$user.'%');	

	$select->order('id DESC');

	$historyDetail = $history->fetchRow($select);					
	return $historyDetail->date;	
	
	}
}
	

?>