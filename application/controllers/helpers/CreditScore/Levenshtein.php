<?php

class Zend_Controller_Action_Helper_Levenshtein extends Zend_Controller_Action_Helper_Abstract
{
    function direct($d1, $d2)
	{	
		$larger = (strlen($d1) > strlen($d2))? strlen($d1) : strlen($d2);
		$diff = levenshtein($d1, $d2);
		$percent = 100 - ($diff * ($larger / 100.00));
		echo "<br>String 1: ".$d1."<br>String 2: ".$d2."<br>Percent: ".$percent."%<br>";
    }
	

}
