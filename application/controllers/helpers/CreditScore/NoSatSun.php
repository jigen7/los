<?php

class Zend_Controller_Action_Helper_NoSatSun extends Zend_Controller_Action_Helper_Abstract
{
    function direct($d1, $d2)
	{	
		$dFrom = date_parse(date("Y-m-d H:i:s",strtotime($d1)));		
		$dTo = date_parse(date("Y-m-d H:i:s",strtotime($d2)));			
		
	    if ($dFrom['second'] >= $dTo['second']){
	    	$second = $dFrom['second'] - $dTo['second'];
	    }else{
    		$dFrom['minute']--;
    		$second = 60 - $dTo['second'] + $dFrom['second'];
  		}
  		if ($dFrom['minute'] >= $dTo['minute']){
    		$minute = $dFrom['minute'] - $dTo['minute'];
  		}else{
    		$dFrom['hour']--;
    		$minute = 60 - $dTo['minute'] + $dFrom['minute'];
  		}
	    if ($dFrom['hour'] >= $dTo['hour']){
		    $hour = $dFrom['hour'] - $dTo['hour'];
	    }else{
		    $dFrom['day']--;
		    $hour = 24 - $dTo['hour'] + $dFrom['hour'];
	    }
		
		$dFrom = $dFrom['year']."-".$dFrom['month']."-".$dFrom['day'];
		$dTo = $dTo['year']."-".$dTo['month']."-".$dTo['day'];
		$dFrom = date_parse(date("Y-m-d",strtotime($dFrom)));		
		$dTo = date_parse(date("Y-m-d",strtotime($dTo)));		
		
		if($dFrom != $dTo){
			$mF = $dFrom['month'];
			$dF = $dFrom['day'];
			$yF = $dFrom['year'];
			$dayCtr = 0;
			$months = array(1,3,5,7,8,10,12); //months with 31 days
			while($dFrom != $dTo){
				if(in_array($mF, $months)){ 
					$dF = ($dF == 31)? 1 : ++$dF;
					if($dF == 1){
						if($mF == 12){$mF = 1; $yF = $yF + 1;
						}else {++$mF;}
					}
				}else if($mF == 2){ //month of February
					$x = $yF;
					while($x > 0){$x = $x - 4;}
					if($x == 0){ $dF = ($dF == 29)? 1 : ++$dF;			
					}else{ $dF = ($dF == 28)? 1 : ++$dF;}
					if($dF == 1){++$mF;}	
				}else{	//months with 30 days
					$dF = ($dF == 30)? 1 : ++$dF;
					if($dF == 1){++$mF;}
				}
				$cDate = $yF."-".$mF."-".$dF;	
				$day = date("D", strtotime($cDate));
				$days = array("Mon","Tue","Wed","Thu","Fri");
				if(in_array($day, $days)){$dayCtr++;}					
				$dFrom = date_parse(date("Y-m-d",strtotime($cDate)));										
			}
		}
		if($dayCtr != 0){$day = $dayCtr.'d ';}
		if($hour != 0){$hour = $hour.'hr ';}
		if($minute != 0){$minute = $minute.'min ';}
		if($second != 0){$second = $second.'sec ';}		
		return $day.$hour.$minute.$second;
    }
	

}
