<?php
class Zend_View_Helper_GetVisibility extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
/***
 * Function to take in acconut status 2 to 
 * check if its a recon account return the css visibility=hidden * 
 * 
 */
	function getVisibility($acctstatus2){

	$model = new Model_BorrowerAccount();
	/*
		//if the capno is a comaker get the main capno
	if($model->isComaker($capno)){
		//get the main principal capno
		$main = $model->getMainCapno($capno);
	}else {
		//if principal,spouse,coborrower
		
	}
	*/
	//if($this->_view->loggedInUserType() == 'MA'){
	if($acctstatus2 == 'RECON'){
	return "style='visibility:hidden'";
		}
	//	}
	}

}
?>