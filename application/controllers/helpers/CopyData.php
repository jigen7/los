<?php

class Zend_Controller_Action_Helper_CopyData extends
                Zend_Controller_Action_Helper_Abstract
{
    function direct($capno)
    {
    	//Helper for the Recon Data
			//Main Table Borrower	
			$account = new Model_BorrowerAccount();
			$select = $account->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			$accntRow = $account->fetchAll($select);
			$accntArray = $accntRow->toArray();
			
			$nextrecon = capnorecon($capno) + 1;
			$newcapno = capnoseprecon($capno).$nextrecon;

	}
}
function ifempty($value){
	
	if($value){
		return 'true';
	}
	else {
		return 'false';
	}
	
	
}

