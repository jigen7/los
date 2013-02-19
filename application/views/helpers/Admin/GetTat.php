<?php 
class Zend_View_Helper_GetTat extends Zend_Controller_Action_Helper_Abstract
{
    
    function getTat($to,$from){
    	$output ="";
		if(trim($to)=="" OR trim($from)==""){
			$output ="NULL";
		}else{
			
			//first, extract ('YYYY-MM-DD') and ('HH:MM:SS')
			$temp = explode(" ",$from);
			$_start = array(
				"ymd"  => $temp[0],
				"time" => $temp[1],
				"ampm" => date("A",strtotime($temp[1]))
			);
			$temp = explode(" ",$to);
			$_end = array(
				"ymd"  => $temp[0],
				"time" => $temp[1],
				"ampm" => date("A",strtotime($temp[1]))
			);
			
			//check if date is countable
			if(strtotime($to) > strtotime($from)){
				
				$lb_out = array(
					'hour'   => str_pad($this->getLunchBreak_Out('h'),2,"00",STR_PAD_LEFT),
					'minute' => str_pad($this->getLunchBreak_Out('m'),2,"00",STR_PAD_LEFT),
					'second' =>	str_pad($this->getLunchBreak_Out('s'),2,"00",STR_PAD_LEFT)
				);
				//set lunch break in time
				$lb_in = array(
					'hour'   => str_pad($this->getLunchBreak_In('h'),2,"00",STR_PAD_LEFT),
					'minute' => str_pad($this->getLunchBreak_In('m'),2,"00",STR_PAD_LEFT),
					'second' =>	str_pad($this->getLunchBreak_In('s'),2,"00",STR_PAD_LEFT)
				);
				//set working time in time
				$wt_in = array(
					'hour'   => str_pad($this->getWorkingTime_AM('h'),2,"00",STR_PAD_LEFT),
					'minute' => str_pad($this->getWorkingTime_AM('m'),2,"00",STR_PAD_LEFT),
					'second' =>	str_pad($this->getWorkingTime_AM('s'),2,"00",STR_PAD_LEFT)
				);
				//set working time out time
				$wt_out = array(
					'hour'   => str_pad($this->getWorkingTime_PM('h'),2,"00",STR_PAD_LEFT),
					'minute' => str_pad($this->getWorkingTime_PM('m'),2,"00",STR_PAD_LEFT),
					'second' =>	str_pad($this->getWorkingTime_PM('s'),2,"00",STR_PAD_LEFT)
				);
				
				$day_deduction = 0;//86400
			
				$return = $this->createDateRangeArray($_start['ymd'],$_end['ymd']);
				
				$ctr =0;
				$first = 0;
				$last = count($return);
				foreach($return as $x){
					if(date('D',strtotime($x)) == 'Sat' OR date('D',strtotime($x)) == 'Sun'){
						if($ctr==$first){
							$_start['time'] = implode(":",$wt_in);
						}
						if($ctr==$last){
							$_end['time'] = implode(":",$wt_out);
						}
						$day_deduction += 86400;
						$return[$ctr] = "deducted";
					}
					$ctr++;
				}
				
				$return = $this->array_remove($return,"deducted");
				
				$dt_holiday = new Model_Admin_TurnAroundHoliday();
				$hl = $dt_holiday->fetchAll();
				$ctr =0;
				$first = 0;
				$last = count($return);
				foreach($hl as $x){
					if(in_array($x->date,$return)){
						if($ctr==$first){
							$_start['time'] = implode(":",$wt_in);
						}
						if($ctr==$last){
							$_end['time'] = implode(":",$wt_out);
						}
						$day_deduction += 86400;
						$return[$ctr] = "deducted";
					}
					$ctr++;
				}
	
				$return = $this->array_remove($return,"deducted");
				
				$remaining_day = 0;
				if(count($return)==0){
					$remaining_day = 0;
				}else{
					$remaining_day = count($return) - 1;
				}
				//echo $remaining_day;
				
				//let's count the time
				$time_deduction = 0; //in seconds
				
				$working_in = implode(":",$wt_in);
				$working_out = implode(":",$wt_out);
				$lunch_in = implode(":",$lb_in);
				$lunch_out = implode(":",$lb_out);
				
				
				
				$remaining_time = 0; //in seconds
				//check if start time is before the working time in
				if( ($remaining_day==0) AND ($_start['time']==$_end['time']) ){
					$output = "Zero Minute"; //same date
				}else{
					//$_start['time'] = "17:29:00";
					
					//check start time
					if(strtotime($working_in) > strtotime($_start['time'])){ //early
						$_start['time'] = $working_in;
					}
					if(strtotime($_start['time']) >= strtotime($working_out)){ //late
						$remaining_day--;
						$_start['time'] = $working_in;
					}
					
					//check end time
					if(strtotime($_end['time']) > strtotime($working_out)){ //late
						$_end['time'] = $working_out;
					}
					if(strtotime($_end['time']) < strtotime($working_in)){ //early
						$_end['time'] = $working_in;
					}
					
					//check for lunch break
					if($this->checkTime($_start['time'],$lunch_out,$lunch_in)){
						$_start['time'] = $lunch_in;
					}
					if($this->checkTime($_end['time'],$lunch_out,$lunch_in)){
						$_end['time'] = $lunch_in;
					}			
					
					$added_time = 0;
					//complicated cases
					if( $this->checkTime($_start['time'],$lunch_out,$working_out) AND $this->checkTime($_end['time'],$working_in,$lunch_out) ){
						$added_time = strtotime($working_out) - strtotime($_start['time']);
						$remaining_day--;
						$_start['time'] = $working_in;
						
					}
					
					//check for whole day
					if( $this->checkTime($_start['time'], $working_in, $lunch_out) AND $this->checkTime($_end['time'],$lunch_in,$working_out) ){
						$time_deduction += 3600;
					}
					
					//compute time difference
					$remaining_time = ( strtotime($_end['time']) - strtotime($_start['time']) );
					$remaining_time -= $time_deduction;
				}
				
				$remaining_time+=$added_time;
				//compute for hours
				if($remaining_time >= 3600){ //1 hour = 3600 seconds
					$temp = $remaining_time/3600;
					$temp = explode(".",$temp);
					$temp = $temp[0];
					
					//deduct hours
					$hours = $temp;
					$remaining_time -= ($temp*3600);
				}
				
				//compute for minutes
				if($remaining_time >= 60){ // 1 minute = 60 seconds
					$temp = $remaining_time/60;
					$temp = explode(".",$temp);
					$temp = $temp[0];
					
					//deduct hours
					$minutes = $temp;
					$remaining_time -= ($temp*60);
				}
				$seconds = $remaining_time;
				
				//check for additional days
				if($hours >= 8){ //1day = 8 hrs
					$temp = $hours/8;
					$temp = explode(".",$temp);
					$temp = $temp[0];
					
					//deduct days
					$remaining_day += $temp;
					$hours -= ($temp*8);
				}
				
				//compute for the year
				if( $remaining_day >= 360 ){ //1 year = 360 days
					$temp = $remaining_day/360;
					$temp = explode(".",$temp);
					$temp = $temp[0];
					
					//deduct years
					$years = $temp;
					$remaining_day -= ($temp*360); 
				}
				//compute for months
				if( $remaining_day >= 22 ){ //1 month = 22 days
					$temp = $remaining_day/22;
					$temp = explode(".",$temp);
					$temp = $temp[0];
					
					//deduct months
					$months = $temp;
					$remaining_day -= ($temp*22);
				}
				
				$days = $remaining_day;
				
				//format output
				if($years!=0){
					$output = "{$years}y ";
				}
				if($months!=0){
					$output .= "{$months}m ";
				}
				if($days!=0){
					$output .= "{$days}d ";
				}
				if($hours!=0){
					$output .= "{$hours}hr ";
				}
				if($minutes!=0){
					$output .= "{$minutes}min ";
				}
				if($seconds!=0){
					$output .= "{$seconds}sec ";
				}
				
				if($remaining_day==0 AND $remaining_time==0){
					$output = "Zero Minute"; //same date
				}
				
			}else{
				if(strtotime($to) == strtotime($from)){
					$output = "Zero Minute"; //same date
				}else{
					$output = "Zero Minute"; //invalid date
				}
				
			}
			
		}
		
		
		return $output;
		
    }
	
	function checkTime($currentTime, $startTime, $endTime){
		// written 11/26/2006 by Patrick H. (patrickh@gmail.com)
		//
		// the time passed must meet all the below criteria to return 1 (true):
		//
		// - current hour needs to be equal or greater than start hour
		// - current hour needs to be equal or less than end hour
		// - current minute needs to be equal or greater than start minute (if current hour is ok)
		// - current minute needs to be equal or less than end minute (if current hour is ok)
		//
		// if any of those checks does not pass, it will return 0 (false)
	
		global $cHour;
		global $cMin;
		global $sHour;
		global $sMin;
		global $eHour;
		global $eMin;
	
		// break up current time
		$now = explode(":",$currentTime);
		$cHour = intval($now[0]);	// current time - hour
		$cMin = intval($now[1]);	// current time - minute
		$cSec = intval($now[2]);	// current time - minute
		
		// break up start time
		$start = explode(":",$startTime);
		$sHour = intval($start[0]);	// start of range - hour
		$sMin = intval($start[1]);	// start of range - minute
		$sSec = intval($start[2]);	// start of range - minute
		
		// brek up end time
		$end = explode(":",$endTime);
		$eHour = intval($end[0]);	// end of range - hour
		$eMin = intval($end[1]);	// end of range - minute
		$eSec = intval($end[2]);	// end of range - minute
		
		// this is the variable used to track the result of the checks
		$pass = true;
	
		if($sHour <= $eHour){
			// the range is on the same day
	
			// compare to the start hour
			if($cHour < $sHour){
				$pass = false;
			}
	
			// compare to the end hour
			if($cHour > $eHour){
				$pass = false;
			}
	
			// compare to the start min
			if($cHour == $sHour){
				if($cMin < $sMin){
					$pass = false;
				}else if($cMin == $sMin){
					if($cSec < $sSec){
						$pass = false;	
					}
				}
			}
	
			// compare to the end min
			if($cHour == $eHour){
				if($cMin > $eMin){
					$pass = false;
				}else if($cMin == $eMin){
					if($cSec > $eSec){
						$pass = false;
					}
				}
			}
	
		} else {
			// the range is overnight, so the logic is a little different
	
			if( ($cHour < $sHour) && ($cHour > $eHour) ){
				$pass = false;
			}
	
			// compare to the start min
			if($cHour == $sHour){
				if($cMin < $sMin){
					$pass = false;
				}else if($cMin == $sMin){
					if($cSec < $sSec){
						$pass = false;	
					}
				}
			}
	
			// compare to the end min
			if($cHour == $eHour){
				if($cMin > $eMin){
					$pass = false;
				}else if($cMin == $eMin){
					if($cSec > $eSec){
						$pass = false;	
					}
				}
			}
	
		}
	
		// done with check, return the result
		if($pass == false){
			return 0;	// failed
		} else {
			return 1;	// passed
		}
	
	}


	function array_remove($arr,$value) { return array_values(array_diff($arr,array($value))); } 
	
	function createDateRangeArray($strDateFrom,$strDateTo) {
	  // takes two dates formatted as YYYY-MM-DD and creates an
	  // inclusive array of the dates between the from and to dates.
	
	  // could test validity of dates here but I'm already doing
	  // that in the main script
	
	  $aryRange=array();
	
	  $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
	  $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));
	
	  if ($iDateTo>=$iDateFrom) {
	    array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
	
	    while ($iDateFrom<$iDateTo) {
	      $iDateFrom+=86400; // add 24 hours
	      array_push($aryRange,date('Y-m-d',$iDateFrom));
	    }
	  }
	  return $aryRange;
	}
	
	public function getWorkingTime_AM($x){
		$config = new Model_Admin_TurnAroundConfig();
		$workingTime = $config->getWorkingTime('workhours');
		
		$date = '2011-00-00 '.(string) $workingTime->time_from;
		$date = date_parse(date('Y-m-d H:i:s',strtotime($date)));
		
		switch($x){
			case 'h':
				return $date['hour'];
			break;
			case 'm':
				return $date['minute'];
			break;
			case 's':
				return $date['second'];
			break;
		}
	}
	//fetch working-time in time
	public function getWorkingTime_PM($x){
		$config = new Model_Admin_TurnAroundConfig();
		$workingTime = $config->getWorkingTime('workhours');
		
		$date = '2011-00-00 '.(string) $workingTime->time_to;
		$date = date_parse(date('Y-m-d H:i:s',strtotime($date)));
		
		switch($x){
			case 'h':
				return $date['hour'];
			break;
			case 'm':
				return $date['minute'];
			break;
			case 's':
				return $date['second'];
			break;
		}
	}
	//fetch lunch break out time
	public function getLunchBreak_Out($x){
		$config = new Model_Admin_TurnAroundConfig();
		$lunchBreak = $config->getWorkingTime('lunchbreak');
		
		$date = '2011-00-00 '.(string) $lunchBreak->time_from;
		$date = date_parse(date('Y-m-d H:i:s',strtotime($date)));
		
		switch($x){
			case 'h':
				return $date['hour'];
			break;
			case 'm':
				return $date['minute'];
			break;
			case 's':
				return $date['second'];
			break;
		}
	}
	//fetch lunch break in time
	public function getLunchBreak_In($x){
		$config = new Model_Admin_TurnAroundConfig();
		$lunchBreak = $config->getWorkingTime('lunchbreak');
		
		$date = '2011-00-00 '.(string) $lunchBreak->time_to;
		$date = date_parse(date('Y-m-d H:i:s',strtotime($date)));
		
		switch($x){
			case 'h':
				return $date['hour'];
			break;
			case 'm':
				return $date['minute'];
			break;
			case 's':
				return $date['second'];
			break;
		}
	}
}
	
	

    
