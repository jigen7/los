<?php
class Zend_View_Helper_GetCurrentUser extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getCurrentUser($status,$capno){
	
	if($status){
	$userTable = new Model_Users();
	$statusTable = new Model_Admin_AccountStatus();
	$borrowerTable = new Model_BorrowerAccount();
	$detail = $borrowerTable->fetchRowModel($capno);
	
	
	$select = $statusTable->select();
	$select->where('status like ?',$detail->account_status);	

	$statusDetail = $statusTable->fetchRow($select);					
	
	$currUser = $statusDetail->current_user;
	
	
	switch($currUser){
		case 'MA':
			return $this->_view->viewMa($detail->created_by);
		break;
		case 'CA':
			return $this->_view->viewMa($detail->submitted_ca);
		break;
		case 'CO':
			return $this->_view->viewMa($detail->submitted_co);
		break;
		case 'LA':
			return $this->_view->viewMa($detail->submitted_la);
		break;
		case 'LP':
			return $this->_view->viewMa($detail->submitted_lo);
		break;
		case 'CSH':
			return $this->_view->viewMa($userTable->returnApproverbyRole('CSH'));
		break;					
		case 'ALMH':
			return $this->_view->viewMa($userTable->returnApproverbyRole('ALMH'));
		break;
		case 'CMGH':
			return $this->_view->viewMa($userTable->returnApproverbyRole('CMGH'));
		break;	
		case 'PRES':
			return $this->_view->viewMa($userTable->returnApproverbyRole('PRES'));
		break;		
		case 'CMGH/PRES':
			if(strpos($detail->routetag,'-CMGH') !== false){
			return $this->_view->viewMa($userTable->returnApproverbyRole('CMGH'));
			}
			if(strpos($detail->routetag,'-PRES') !== false){
			return $this->_view->viewMa($userTable->returnApproverbyRole('PRES'));
			}
			if(strpos($detail->routetag,'-CRECOM') !== false){
			return 'CRECOM';
			}			
		case 'AO':	
			return $this->_view->viewMa($userTable->returnApproverbyRole('AO'));
		break;
		
		default:
			return $statusDetail->current_user;
		break;	
		
		
	}
	}//end of status null
	}
}
	

?>