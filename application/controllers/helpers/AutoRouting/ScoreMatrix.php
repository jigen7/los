<?php

class Zend_Controller_Action_Helper_ScoreMatrix extends Zend_Controller_Action_Helper_Abstract
{
    function direct($scoretag, $amount)
	{	
		$isAbsent = true;
		$matrix = new Model_AutoRouting_CreditMatrix();
		$scoreValue = explode(" ", $scoretag);
			
		if($scoreValue[2] == "Scoring"){
			$col = "OCS";
		}else if($scoreValue[3]){
			$str = ($scoreValue[2] == 'P1' || $scoreValue[2] == 'P2')? 'F1' : $scoreValue[2];
			$col = $str.$scoreValue[3];
		}else{
			$col = $scoreValue[2];
		}
		$result = $matrix->getRouteTag($amount);
		$routetag = $result->$col;

		if($isAbsent){
			$config = new Model_AutoRouting_Config();
			if(strpos($routetag, "-CRECOM") !== FALSE){
				$config = $config->getColConfig("crecom_absence_reduced");
				$limit = $matrix->getAbsentRouteTag($amount, $routetag, $col, $config);
				$routetag = $routetag."-NO CHAIRMAN".$limit;
			}else if(strpos($routetag, "-SUBCRECOM") !== FALSE){
				$config = $config->getColConfig("subcrecom_absence_reduced");
				$limit = $matrix->getAbsentRouteTag($amount, $routetag, $col, $config);
				$routetag = $routetag."-NO CHAIRMAN".$limit;
			}			
		}
		
		echo "<br>".$routetag." - ".$col;
	}

}

