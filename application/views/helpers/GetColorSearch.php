<?php
class Zend_View_Helper_GetColorSearch extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getColorSearch($status,$capno){

	//use in autorouting
	$statusTable = new Model_Admin_AccountStatus();
	
	if($status){
	if($statusTable->routeview($status,'search_process')=== TRUE){
		//Green
		return "#458B00";
	}
	else if($statusTable->routeview($status,'search_encode')=== TRUE){
		// Yellow
		return "#CFB53B";

	}
	else if($statusTable->routeview($status,'search_outright')=== TRUE){
		// Light Red
		return "#FF6347";

	}	
	else if($statusTable->routeview($status,'search_approve')=== TRUE){
		// Blue
		return "#003F87";
		//Light Blue more than 1 month
	}
	else if($statusTable->routeview($status,'search_reject')=== TRUE){
		//Red
		return "#CC1100";
	}		
	else if($statusTable->routeview($status,'search_bookingprocess')=== TRUE){
		// Grey
		return "##8F8F8F";
	}
	else if($statusTable->routeview($status,'search_book')=== TRUE){
		// Black
		return "#292421";
	}
	else{
		// Default Color
		return "#4A708B";
	}	
	}//end of no status
	else {
		return "#4A708B";
	}
	
}
}
	

?>