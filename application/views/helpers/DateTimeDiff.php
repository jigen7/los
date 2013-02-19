<?php
class Zend_View_Helper_DateTimeDiff extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	
	function dateTimeDiff($d1,$d2){

	if ($d1 < $d2){
    $temp = $d2;
    $d2 = $d1;
    $d1 = $temp;
  }
  else {
    $temp = $d1; //temp can be used for day count if required
  }
  $d1 = date_parse(date("Y-m-d H:i:s",strtotime($d1)));
  $d2 = date_parse(date("Y-m-d H:i:s",strtotime($d2)));

  //seconds
  if ($d1['second'] >= $d2['second']){
    $diff['second'] = $d1['second'] - $d2['second'];
  }
  else {
    $d1['minute']--;
    $diff['second'] = 60-$d2['second']+$d1['second'];
  }
  //minutes
  if ($d1['minute'] >= $d2['minute']){
    $diff['minute'] = $d1['minute'] - $d2['minute'];
  }
  else {
    $d1['hour']--;
    $diff['minute'] = 60-$d2['minute']+$d1['minute'];
  }
  //hours
  if ($d1['hour'] >= $d2['hour']){
    $diff['hour'] = $d1['hour'] - $d2['hour'];
  }
  else {
    $d1['day']--;
    $diff['hour'] = 24-$d2['hour']+$d1['hour'];
  }
  //days
  if ($d1['day'] >= $d2['day']){
    $diff['day'] = $d1['day'] - $d2['day'];
  }
  else {
    $d1['month']--;
    $diff['day'] = date("t",$temp)-$d2['day']+$d1['day'];
  }
  //months
  if ($d1['month'] >= $d2['month']){
    $diff['month'] = $d1['month'] - $d2['month'];
  }
  else {
    $d1['year']--;
    $diff['month'] = 12-$d2['month']+$d1['month'];
  }
  //years
  $diff['year'] = $d1['year'] - $d2['year'];
    //return $diff;    
	if($diff['year'] != 0){
	$year = $diff['year'].'y ';
	} 
	if($diff['month'] != 0){
	$month = $diff['month'].'m ';
	}
	if($diff['day'] != 0){
	$day = $diff['day'].'d ';
	}
	if($diff['hour'] != 0){
	$hour = $diff['hour'].'hr ';
	}
	if($diff['minute'] != 0){
	$minute = $diff['minute'].'min ';
	}
	if($diff['second'] != 0){
	$second = $diff['second'].'sec ';
	}
	//print_r($diff);
 	return $year.$month.$day.$hour.$minute.$second;
		}

	
}
?>