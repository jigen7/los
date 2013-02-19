<?php
class Zend_View_Helper_GetRtsdate extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getRtsdate($capno,$user){

	$history = new Model_AccountHistory();
	$select = $history->select();
	$select->where('capno like ?',$capno);	
	$select->order('id DESC');
	
	if($user == 'ma'){
		$select->where("status = 'CA - RTMA' OR status = 'LA - RTMA'");	
		$historyDetail = $history->fetchRow($select);					
		
	}else if($user== 'ca'){
		$select->where('status like ?','CO - RTCA');	
		$historyDetail = $history->fetchRow($select);					
	}
	else if($user== 'la'){
		$select->where('status like ?','Lo - RTLA');	
		$historyDetail = $history->fetchRow($select);					
	}
	return $historyDetail->date;
	
	
	}
}
	

?>