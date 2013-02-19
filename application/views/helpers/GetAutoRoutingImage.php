<?php
class Zend_View_Helper_GetAutoRoutingImage extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getAutoRoutingImage($string){

	if(strpos($string,'Rejection') !== false){
		echo "<img alt='Recommend for Rejection' src='".$this->_view->baseUrl()."/images/autorouting/recommend-rejection.jpg'/>";
	}
	if(strpos($string,'Approval') !== false){
		echo "<img alt='Recommend for Approval' src='".$this->_view->baseUrl()."/images/autorouting/recommend-approval.jpg'/>";
	}
	if(strpos($string,'Approved') !== false){
		echo "<img alt='Recommend for Approved' src='".$this->_view->baseUrl()."/images/autorouting/approved.jpg'/>";
	}
	if(strpos($string,'Disapproved') !== false){
		echo "<img alt='Disapproved' src='".$this->_view->baseUrl()."/images/autorouting/rejected.jpg'/>";
	}
	if(strpos($string,'endorsed') !== false){
		echo "<img alt='Endorsed' width='35%' src='".$this->_view->baseUrl()."/images/autorouting/endorse.jpg'/>";
	}
	if(strpos($string,'Pass') !== false){
		echo "<img alt='Pass' src='".$this->_view->baseUrl()."/images/autorouting/pass.jpg'/>";
	}
	}
	
}
?>