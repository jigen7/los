<?php

class Model_Admin_TurnAroundHoliday extends Zend_Db_Table_Abstract{
	
	protected $_name = 'admin.turn_around_holiday';
	
	public function dateTimeDiff($end,$start){
		//convert to seconds
		$end = strtotime($end);
		$start = strtotime($start);
		//set lunch break out time
		$lb_out = array(
			'hour'   => $this->getLunchBreak_Out('h'),
			'minute' => $this->getLunchBreak_Out('m'),
			'second' =>	$this->getLunchBreak_Out('s')
		);
		//set lunch break in time
		$lb_in = array(
			'hour'   => $this->getLunchBreak_In('h'),
			'minute' => $this->getLunchBreak_In('m'),
			'second' =>	$this->getLunchBreak_In('s')
		);
		//set working time in time
		$wt_in = array(
			'hour'   => $this->getWorkingTime_AM('h'),
			'minute' => $this->getWorkingTime_AM('m'),
			'second' =>	$this->getWorkingTime_AM('s')
		);
		//set working time out time
		$wt_out = array(
			'hour'   => $this->getWorkingTime_PM('h'),
			'minute' => $this->getWorkingTime_PM('m'),
			'second' =>	$this->getWorkingTime_PM('s')
		);
		//set deduction
		$less=0;
		//output
		$output = "";
		
		if($end>=$start){
			//set holiday list
			$hl = $this->fetchAll();
			//additional date
			$add = array(
				"second" => 0,
				"minute" => 0,
				"hour"   => 0,
				"day"    => 0,
				"month"  => 0,
				"year"   => 0
			);
			//convert input to date array
			for($i=$start; $i <= $end; $i+=86400 ){
				//get the name of day
				$day_name = strtolower(date('D',($i)));
						
				//get the date without time, for checking holiday
				$date_holiday = strtolower(date('Y-m-d',($i)));
				
				//check if the day is saturday
				if($day_name=='sat'){
					//if yes, deduct 1 day
					$less+=28800;
				}
				//check if the day is sunday
				if($day_name=='sun'){
					//if yes, deduct 1 day
					$less+=28800;
				}
				//let us assume that there will be no holidays on saturday and sunday
				foreach($hl  as $x){
					//compare dates
					if($date_holiday == $x->date){
					//if yes, deduct 1 day
						$less+=28800;
					}
				}
			}
			$pd_end = date_parse(date('Y-m-d H:i:s',$end));
			$pd_start = date_parse(date('Y-m-d H:i:s',$start));
			
			$day_gap = ($pd_end['day']-$pd_start['day']);
			
			if($day_gap>=1){
				//start field
				//
				/*
				$time_end = ($pd_end['second']) + ($pd_end['minute']*60) + ($pd_end['hour']*3600);
				$time_start = ($pd_start['second']) + ($pd_start['minute']*60) + ($pd_start['hour']*3600);
				
				if($time_start>$time_end){
					if($this->checkIfSpottedAm($start)){
						$add['second'] = ($lb_out['second'] - $pd_start['second']);
						$add['minute'] = ($lb_out['minute'] - $pd_start['minute']);
						$add['hour'] = ($lb_out['hour'] - $pd_start['hour']);
						$pd_start['hour'] = $lb_in['hour'];
						$pd_start['minute'] = $lb_in['minute'];
						$pd_start['second'] = $lb_in['second'];
					}else{
						$pd_start['day']++;
						$add['second'] = ($lb_in['second'] - $pd_start['second']);
						$add['minute'] = ($lb_in['minute'] - $pd_start['minute']);
						$add['hour'] = ($lb_in['hour'] - $pd_start['hour']);
						$pd_start['hour'] = $wt_in['hour'];
						$pd_start['minute'] = $wt_in['minute'];
						$pd_start['second'] = $wt_in['second'];
					}
				}
				
				*/
				if($this->checkIfSpottedLB($pd_start,null,$lb_in,$lb_out)){
					//start time is on the lunch break, we have to move the day(+1), set
					//addtional time(working time out - lunch in time), and
					//set the starting time to working time in
					$pd_start['day']++;
					$add['second'] += ($wt_out['second'] - $lb_in['second']);
					$add['minute'] += ($wt_out['minute'] - $lb_in['minute']);
					$add['hour'] += ($wt_out['hour'] - $lb_in['hour']);
					$pd_start['hour'] = $wt_in['hour'];
					$pd_start['minute'] = $wt_in['minute'];
					$pd_start['second'] = $wt_in['second'];
				}else{
					
					//check if time is spotted am or pm
					if($this->checkIfSpottedAm($start)){
						//we have to check if the start time is inbound
						if($this->checkIfLessThanWI($pd_start,$wt_in)){
							$pd_start['hour'] = $wt_in['hour'];
							$pd_start['minute'] = $wt_in['minute'];
							$pd_start['second'] = $wt_in['second'];
						}
					}else {
						if($this->checkIfLessThanWO($pd_start,$wt_out)){
							//we are now sure that start time is on after lunch break
							$pd_start['day']++;
							$add['second'] += ($wt_out['second'] - $pd_start['second']);
							$add['minute'] += ($wt_out['minute'] - $pd_start['minute']);
							$add['hour'] += ($wt_out['hour'] - $pd_start['hour']);
							$pd_start['hour'] = $wt_in['hour'];
							$pd_start['minute'] = $wt_in['minute'];
							$pd_start['second'] = $wt_in['second'];
						}else{
							//we do not count outbound time
							$pd_start['day']++;
							$pd_start['hour'] = $wt_in['hour'];
							$pd_start['minute'] = $wt_in['minute'];
							$pd_start['second'] = $wt_in['second'];
						}
					}
				}
				//end field
				if($this->checkIfSpottedLB(null,$pd_end,$lb_in,$lb_out)){
					//change end time to luncg break out time
					$pd_end['hour'] = $lb_out['hour'];
					$pd_end['minute'] = $lb_out['minute'];
					$pd_end['second'] = $lb_out['second'];
				}else{
					if($this->checkIfSpottedAm($end)){
						if($this->checkIfLessThanWI($pd_end,$wt_in)){
							$pd_end['hour'] = $wt_in['hour'];
							$pd_end['minute'] = $wt_in['minute'];
							$pd_end['second'] = $wt_in['second'];
						}
					}else{
						if($this->checkIfLessThanWO($pd_end,$wt_out)){
							//we are now sure that start time is on after lunch break
							
						}else{
							//we do not count outbound time
							$pd_end['hour'] = $wt_out['hour'];
							$pd_end['minute'] = $wt_out['minute'];
							$pd_end['second'] = $wt_out['second'];
						}
					}	
				}
			}else{
			//1 day
				//start field
				if($this->checkIfSpottedLB($pd_start,null,$lb_in,$lb_out)){
					//start time is on the lunch break, we have to move the day(+1), set
					//addtional time(working time out - lunch in time), and
					//set the starting time to working time in
					$pd_start['hour'] = $wt_in['hour'];
					$pd_start['minute'] = $wt_in['minute'];
					$pd_start['second'] = $wt_in['second'];
				}else{
					//check if time is spotted am or pm
					if($this->checkIfSpottedAm($start)){
						//we have to check if the start time is inbound
						if($this->checkIfLessThanWI($pd_start,$wt_in)){
							$pd_start['hour'] = $wt_in['hour'];
							$pd_start['minute'] = $wt_in['minute'];
							$pd_start['second'] = $wt_in['second'];
						}
					}else {
						if($this->checkIfLessThanWO($pd_start,$wt_out)){
							//we are now sure that start time is on after lunch break
							
						}else{
							//we do not count outbound time
							$pd_start['hour'] = $wt_out['hour'];
							$pd_start['minute'] = $wt_out['minute'];
							$pd_start['second'] = $wt_out['second'];
						}
					}
				}
				//end field
				if($this->checkIfSpottedLB(null,$pd_end,$lb_in,$lb_out)){
					//change end time to luncg break out time
					$pd_end['hour'] = $lb_out['hour'];
					$pd_end['minute'] = $lb_out['minute'];
					$pd_end['second'] = $lb_out['second'];
				}else{
					if($this->checkIfSpottedAm($end)){
						if($this->checkIfLessThanWI($pd_end,$wt_in)){
							$pd_end['hour'] = $wt_in['hour'];
							$pd_end['minute'] = $wt_in['minute'];
							$pd_end['second'] = $wt_in['second'];
						}
					}else{
						if($this->checkIfLessThanWO($pd_end,$wt_out)){
							//we are now sure that start time is on after lunch break
							
						}else{
							//we do not count outbound time
							$pd_end['hour'] = $wt_out['hour'];
							$pd_end['minute'] = $wt_out['minute'];
							$pd_end['second'] = $wt_out['second'];
						}
					}	
				}
			}
			if($this->checkIfWholeDay($pd_start,$pd_end,$lb_in,$lb_out)){
				$less += 3600;
				//echo "less 1 hour for lunch break";
			}
			//find the difference
			$d2 = $pd_start;
			$d1 = $pd_end;
			//seconds
			if($d1['second'] >= $d2['second']){
				$diff['second'] = $d1['second'] - $d2['second'];
			}else{ 
				$d1['minute']--;
				$diff['second'] = 60-$d2['second']+$d1['second'];
			}
					
			//minutes
			if($d1['minute'] >= $d2['minute']){
				$diff['minute'] =$d1['minute'] - $d2['minute'];
			}else{
				$d1['hour']--;
				$diff['minute'] = 60-$d2['minute']+$d1['minute'];
			}
					
			//hours		
			if($d1['hour'] >= $d2['hour']){
				$diff['hour'] = $d1['hour'] - $d2['hour'];
			}else{
				$d1['day']--;
				$diff['hour'] = 24-$d2['hour']+$d1['hour'];
			}
					
			//days
			if($d1['day'] >= $d2['day']){
				$diff['day'] = $d1['day'] - $d2['day'];
			}else{ 
				$d1['month']--;
				$diff['day'] = date("t",$temp) - $d2['day']+$d1['day'];
			}
			
			//months
			if($d1['month'] >= $d2['month']){
				$diff['month'] = $d1['month'] - $d2['month'];
			}else{ 
				$d1['year']--;
				$diff['month'] = 12-$d2['month']+$d1['month'];
			}
					
			//years
			$diff['year'] = $d1['year'] - $d2['year'];
			
			//convert additional time, and differenc to seconds, then add
			//seconds(60), minute(3600), hour(28800), day(633600), month(7603200), year(91238400)
			$seconds = ($add['second']) + ($add['minute']*60) + ($add['hour']*3600) + ($add['day']*28800) + ($add['month']*633600) + ($add['year']*7603200);
			$seconds += ($diff['second']) + ($diff['minute']*60) + ($diff['hour']*3600) + ($diff['day']*28800) + ($diff['month']*633600) + ($diff['year']*7603200);
			//deduct less
			$seconds -= $less;
			//$y,$m,$d,$h,$min,$sec
			//year
			if($seconds >= 7603200){
				$y = (string) ($seconds / 7603200);
				$y = explode(".",$y);
				$y = $y[0];
				
				$seconds = $seconds - (7603200*$y);
			}
			//month
			if($seconds >= 633600){
				$m = (string) ($seconds / 633600);
				$m = explode(".",$m);
				$m = $m[0];
				
				$seconds = $seconds - (633600*$m);
			}
			//day
			if($seconds >= 28800){
				$d = (string) ($seconds / 28800);
				$d = explode(".",$d);
				$d = $d[0];
				
				$seconds = $seconds - (28800*$d);
			}
			//hour
			if($seconds >= 3600){
				$h = (string) ($seconds / 3600);
				$h = explode(".",$h);
				$h = $h[0];
				
				$seconds = $seconds - (3600*$h);
			}
			//minute
			if($seconds >= 60){
				$min = (string) ($seconds / 60);
				$min = explode(".",$min);
				$min = (int)$min[0];
				
				$seconds = $seconds - (60*$min);
			}
			//second
			$sec = $seconds;
			
			//finalize output message
			if($y!=0){
				$output = "{$y}y ";
			}
			if($m!=0){
				$output .= "{$m}m ";
			}
			if($d!=0){
				$output .= "{$d}d ";
			}
			if($h!=0){
				$output .= "{$h}hr ";
			}
			if($min!=0){
				$output .= "{$min}min ";
			}
			if($sec!=0){
				$output .= "{$sec}sec ";
			}
		}else{
			$output = "Negative Date";
		}
		
		if($output==""){
			$output = "0 min";
		}
		
		return $output;//"<br/> Less: {$less}<br/> Start[{$pd_start['hour']}]:[{$pd_start['minute']}]:[{$pd_start['second']}]<br/> End[{$pd_end['hour']}]:[{$pd_end['minute']}]:[{$pd_end['second']}]<br/><br/> Start[{$pd_start['year']}]-[{$pd_start['day']}]-[{$pd_start['month']}]<br/> End[{$pd_end['year']}]-[{$pd_end['day']}]-[{$pd_end['month']}]";
		
	}
	
	
	/**
		FUNCTIONS
	*/
	//fetch working-time out time
	public function getWorkingTime_AM($x){
		$config = new Model_Admin_TurnAroundConfig();
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
	//check if spotted on lunch break time
	public function checkIfSpottedLB($pd_start,$pd_end,$lb_in,$lb_out){
		if($pd_start!=null){
			if($pd_start['hour']>=$lb_out['hour'] AND $pd_start['hour']<=$lb_in['hour']){
				if($pd_start['hour']>=$lb_out['hour'] AND $pd_start['hour']<$lb_in['hour']){
					return TRUE;
				}else if($pd_start['hour']==$lb_in['hour'] AND $pd_start['minute']<= $lb_in['minute']){
					if($pd_start['minute']<$lb_in['minute']){
						return TRUE;
					}else if($pd_start['minute']==$lb_in['minute'] AND $pd_start['second']<= $lb_in['second']){
						return TRUE;
					}else{
						return FALSE;
					}
				}else{
					return FALSE;
				}
			}
		}
		if($pd_end!=null){
			if($pd_end['hour']>=$lb_out['hour'] AND $pd_end['hour']<=$lb_in['hour']){
				if($pd_end['hour']>=$lb_out['hour'] AND $pd_end['hour']<$lb_in['hour']){
					return TRUE;
				}else if($pd_end['hour']==$lb_in['hour'] AND $pd_end['minute']<= $lb_in['minute']){
					if($pd_end['minute']<$lb_in['minute']){
						return TRUE;
					}else if($pd_end['minute']==$lb_in['minute'] AND $pd_end['second']<= $lb_in['second']){
						return TRUE;
					}else{
						return FALSE;
					}
				}else{
					return FALSE;
				}
			}
		}
		
	}
	//check if spotted on am or pm
	public function checkIfSpottedAm($pd){
		if(date('a',$pd) == 'am'){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	//check if they are the same
	public function checkIfTheSame($pd_start,$pd_end){
		if($pd_start['hour']==$pd_end['hour']){
			if($pd_start['minute']==$pd_start['minute']){
				if($pd_start['second']==$pd_start['second']){
					return TRUE;
				}else{
					return FALSE;
				}
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	//check if the time is not normal
	public function checkIfNotNormal($pd_start,$pd_end){
		if($pd_start['hour']>=$pd_end['hour']){
			if($pd_start['hour']>$pd_end['hour']){
				return TRUE;
			}else if($pd_start['hour']==$pd_end['hour'] AND $pd_start['minute']>=$pd_end['minute']){
				if($pd_start['minute']>$pd_end['minute']){
					return TRUE;
				}else if($pd_start['minute']==$pd_end['minute'] AND $pd_start['second']>=$pd_end['second']){
					if($pd_start['second']>$pd_end['second']){
						return TRUE;
					}else{
						return FALSE;
					}
				}
			}
		}else{
			return FALSE;
		}
		
	}
	//check if there is need to deduct lunch break
	public function checkIfWholeDay($pd_start,$pd_end,$lb_in,$lb_out){
		if($pd_start['hour']<$lb_out['hour'] AND $pd_end['hour']>=$lb_in['hour']){
			return TRUE;
		}else{ return FALSE; }
	}
	//check if less than working out time
	public function checkIfLessThanWO($pd,$wt_out){
		if($pd['hour']<=$wt_out['hour']){
			if($pd['hour']<$wt_out['hour']){
				return TRUE;
			}else if($pd['hour']==$wt_out['hour'] AND $pd['minute']<=$wt_out['minute']){
				if($pd['minute']<$wt_out['minute']){
					return TRUE;
				}else if($pd['minute']==$wt_out['minute'] AND $pd['second']<=$wt_out['second']){
					if($pd['second']<$wt_out['second']){
						return TRUE;
					}else{
						return FALSE;
					}
				}
			}
		}else{
			return FALSE;
		}
	}
	//check if less than working out time
	public function checkIfLessThanWI($pd,$wt_in){
		if($pd['hour']<=$wt_in['hour']){
			if($pd['hour']<$wt_in['hour']){
				return TRUE;
			}else if($pd['hour']==$wt_in['hour'] AND $pd['minute']<=$wt_in['minute']){
				if($pd['minute']<$wt_in['minute']){
					return TRUE;
				}else if($pd['minute']==$wt_in['minute'] AND $pd['second']<=$wt_in['second']){
					if($pd['second']<$wt_in['second']){
						return TRUE;
					}else{
						return FALSE;
					}
				}
			}
		}else{
			return FALSE;
		}
	}
	
	
}
