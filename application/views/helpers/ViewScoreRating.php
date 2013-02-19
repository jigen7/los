<?php
class Zend_View_Helper_ViewScoreRating extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewScoreRating($score){
		
	$array = explode(' ',$score);
	
	if($array[3] == 'Model'){
	return $score;
	}else{
	return $array[2];
	}
	}
	
}






?>