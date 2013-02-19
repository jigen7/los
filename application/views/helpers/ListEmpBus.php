<?php
class Zend_View_Helper_ListEmpBus extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function listEmpBus($capno,$process){
		
		//View Helper to return the list of employment and business of one client

	if($process == 'emp'){
	$emp = new Model_BorrowerEmployment();
	$select = $emp->select();
	$select->where('capno like ?',$capno);	
	$select->where("employer  = 'Current' or employer = 'Remittance'");	
	$select->order('id DESC');
	$empDetail = $emp->fetchAll($select);					
	return $empDetail;
	}

	else if($process == 'empcount'){
	$emp = new Model_BorrowerEmployment();
	$select = $emp->select();
	$select->where('capno like ?',$capno);	
	$select->where("employer  = 'Current' or employer = 'Remittance'");	
	$select->order('id DESC');
	$empDetail = $emp->fetchAll($select)->count();					
	return $empDetail;
	}
	
	else if($process == 'empCurrent'){
	$emp = new Model_BorrowerEmployment();
	$select = $emp->select();
	$select->where('capno like ?',$capno);	
	$select->where("employer  = 'Current' or employer = 'Remittance'");	
	$select->order('id DESC');
	$empDetail = $emp->fetchAll($select);					
	return $empDetail;
	}
	
	else if($process == 'empOther'){
	$emp = new Model_BorrowerEmployment();
	$select = $emp->select();
	$select->where('capno like ?',$capno);	
	$select->where("employer  = 'Remittance'");	
	$select->order('id DESC');
	$empDetail = $emp->fetchAll($select)->toArray();					
	return $empDetail;
	}
	
	else if($process == 'bus'){
	$bus = new Model_BorrowerBusiness();
	$select = $bus->select();
	$select->where('capno like ?',$capno);	
	$select->order('id DESC');
	$busDetail = $bus->fetchAll($select);					
	return $busDetail;	
	}
	
	else if($process == 'buscount'){
	$bus = new Model_BorrowerBusiness();
	$select = $bus->select();
	$select->where('capno like ?',$capno);	
	$select->order('id DESC');
	$busDetail = $bus->fetchAll($select)->count();					
	return $busDetail;	
	}	
	
	else if($process == 'othermonthly'){
	$othermonthly = new Model_BorrowerIncomeMonthly();
	$select = $othermonthly->select();
	$select->where('capno like ?',$capno);
	$othermonthlydetail = $othermonthly->fetchAll($select);
	return $othermonthlydetail;	
	}
	
	else if($process == 'othersource'){
	$othersource = new Model_BorrowerIncomeSource();
	$select = $othersource->select();
	$select->where('capno like ?',$capno);
	$othersourcedetail = $othersource->fetchAll($select);				
	return $othersourcedetail;	
	}
	else if($process == 'othermonthlyArray'){
		// Use in if the borrower has a othert monthly income
	$othermonthly = new Model_BorrowerIncomeMonthly();
	$select = $othermonthly->select();
	$select->where('capno like ?',$capno);
	$othermonthlydetail = $othermonthly->fetchAll($select)->toArray();
	return $othermonthlydetail;	
	}
	
	else if($process == 'othersourceArray'){
		// Use in if the borrower has a other source income

	$othersource = new Model_BorrowerIncomeSource();
	$select = $othersource->select();
	$select->where('capno like ?',$capno);
	$othersourcedetail = $othersource->fetchAll($select)->toArray();				
	return $othersourcedetail;	
	}
	
	else if($process == 'othermonthlysum'){
	$othermonthly = new Model_BorrowerIncomeMonthly();
	$select = $othermonthly->select();
	$select->where('capno like ?',$capno);
	$othermonthlydetail = $othermonthly->fetchAll($select);
	$sum = 0;
		foreach($othermonthlydetail as $detail){
		$sum+= $detail->amount;
		}
	return sum;
	}
	else if($process == 'othersourcesum'){
	$othersource = new Model_BorrowerIncomeSource();
	$select = $othersource->select();
	$select->where('capno like ?',$capno);
	$othersourcedetail = $othersource->fetchAll($select);
		$sum = 0;
		foreach($othersourcedetail as $detail){
		$sum+= $detail->amount;
		}
	return sum;					
	}
			
	
	
	
	}
}
	

?>