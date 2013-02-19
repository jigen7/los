<?php
class Zend_View_Helper_Booking_GetBookDetails extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){$this->_view = $view;}

	function getBookDetails($capno){
	
	$table = new Model_Booking_BookingAuto();
	$select = $table->select();
	$select->where('capno like ?',$capno);
	return $table->fetchRow($select);	
		
	}

	
}






?>