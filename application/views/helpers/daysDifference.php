<?php
class Zend_View_Helper_DaysDifference extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	
	function daysDifference($endDate, $beginDate)
{
   //date('Y-m-d') FORMAT
   //explode the date by "-" and storing to array
   $date_parts1=explode("-", $beginDate);
   $date_parts2=explode("-", $endDate);
   //gregoriantojd() Converts a Gregorian date to Julian Day Count
   $start_date=gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
   $end_date=gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
   return $end_date - $start_date;
}
	
}
?>