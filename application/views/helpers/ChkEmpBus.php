<?php
class Zend_View_Helper_ChkEmpBus extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function chkEmpBus($capno,$process){
		
		//View Helper to return the number of employment and business of one client

	if($process == 'emp'){
	$emp = new Model_BorrowerEmployment();
	$select = $emp->select();
	$select->where('capno like ?',$capno);	
	$select->where("employer  = 'Current' or employer = 'Remittance'");	
	$select->order('id DESC');
	$empDetail = $emp->fetchAll($select)->count();					
	return $empDetail;
	}
	else if($process == 'bus'){
	$bus = new Model_BorrowerBusiness();
	$select = $bus->select();
	$select->where('capno like ?',$capno);	
	$select->order('id DESC');
	$busDetail = $bus->fetchAll($select)->count();					
	return $busDetail;	
	}
	
	
	}
}
	

?>