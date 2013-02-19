<?php
class Zend_View_Helper_GetColorPending extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getColorPending($capno){

	
	
	$history = new Model_AccountHistory();

	
	
	
	if($history->chkCaAnD($capno) == 0 && $history->chkCaRtMa($capno) == 0
	&& $history->chkCoRtCa($capno) == 0
	){
	// if the account is a new application
		return "<b><font color='#2C6700'>&#9608; - NEW</font></b>";
	}
	else {
		if($history->retCaRtMa($capno) > $history->retCoRtCa($capno)){
			return "<b><font color='#CC0000'>&#9608; - RTS-MA</font></b>";
		}
		else if ($history->retCaRtMa($capno) < $history->retCoRtCa($capno)) {
			return "<b><font color='#FFCC00'>&#9608; - RTS-CO</font></b>";
		}
		else if ($history->retCaRtMa($capno) == $history->retCoRtCa($capno)) {
			return "<b><font color='#0000FF'>&#9608; - PENDING</font></b>";
		}
	}
	
	
	
	
	}
}
	

?>