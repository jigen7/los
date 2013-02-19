<?php
class Zend_View_Helper_GetTurnAroundTime extends Zend_Controller_Action_Helper_Abstract
{
	
	function getTurnAroundTime($start,$end,$process){
		//Getting the sum of turnaround time
		$tat = new Model_Admin_TurnAroundHoliday();
		
		switch ($process){	
		case 'turn_around':
			return $tat->dateTimeDiff($start,$end);
		break;
		case  'timesum':
			$start = explode(" ",$start);
			$second = 0;
			foreach($start as $x){
				//sec,min,hr,d,m,y
				switch(substr($x,-1,1)){
					case 'c':
						$second += (int) $x;
					break;
					case 'n':
						$second += ( 60 * (int) $x );
					break;
					case 'r':
						$second += ( 3600 * (int) $x );
					break;
					case 'd':
						$second += ( 28800 * (int) $x );
					break;
					case 'm':
						$second += ( 633600 * (int) $x );
					break;
					case 'y':
						$second += ( 7603200 * (int) $x );
					break;
				}
				
			}
			return $second;
		break;
		case 'compute':
			$total_seconds = (int)$start;
			$y=0;$m=0;$d=0;$hr=0;$min=0;$sec=0;
			
			if($total_seconds >= 7603200){
				$y = floor($total_seconds/ 7603200);
				$total_seconds = ($total_seconds - ($y*7603200));
			}
			
			if($total_seconds >= 633600){
				$m = floor($total_seconds / 633600);
				$total_seconds = ($total_seconds - ($m*633600));
			}
			
			if($total_seconds >= 28800){
				$d = floor($total_seconds / 28800);
				$total_seconds = ($total_seconds - ($d*28800));
			}
			
			if($total_seconds >= 3600){
				$h = floor($total_seconds / 3600);
				$total_seconds = ($total_seconds - ($h*3600));
			}
			
			if($total_seconds >= 60){
				$min = floor($total_seconds / 60);
				$total_seconds = ($total_seconds - ($min*60));
			}
			
			$sec = ($total_seconds); 
			
			if($y!=0){
				$y = $y."y ";
			}else{$y="";}
			if($m!=0){
				$m = $m."m ";
			}else{$m="";}
			if($d!=0){
				$d = $d."d ";
			}else{$d="";}
			if($h!=0){
				$h = $h."hr ";
			}else{$h="";}
			if($min!=0){
				$min = $min."min ";
			}else{$min="";}
			if($sec!=0){
				$sec = $sec."sec";
			}else{$sec="";}
			return $y.$m.$d.$h.$min.$sec;
		break;
		}
	}
	
	

	

	

	
}
?>