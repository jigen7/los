<?php

class Zend_Controller_Action_Helper_Accounts extends
                Zend_Controller_Action_Helper_Abstract
{
    function direct($capno,$status)
    {
		$table = new Model_Report_Accounts();
		$userData = Zend_Auth::getInstance()->getIdentity();
		$username = $userData->username;
		
		$borrower = new Model_BorrowerAccount();
		$borrowerDetail = $borrower->fetchRowModel($capno);
		$data = array(
		'capno'=>$capno,
		'datetime'=>date("r"),
		'status'=>$status,
		'by'=>$username,
		'role'=>$userData->role_type,
		'routetag'=>$borrowerDetail->routetag,
		);		
		$table->insert($data);
	}

	
	

}

	