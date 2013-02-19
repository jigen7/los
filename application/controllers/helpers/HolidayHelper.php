<?php 
class Zend_Controller_Action_Helper_HolidayHelper extends Zend_Controller_Action_Helper_Abstract{
    
	
    function direct() {
    	echo "a";
    }
    function getHolidayList(){
		$holiday = new Model_Admin_TurnAroundHoliday();
		return	$holiday->fetchAll();
	}
    
    function getYearList($from,$to){
		$list = array();
		for($i=$from;$i<=$to;$i++){
			$list[$i] = (string) $i;
		}
		
		return $list;
	}
	function getHolidayByYear($year){
		$holiday = new Model_Admin_TurnAroundHoliday();
		$select = $holiday->select();
		
		$year_start = $year;
		$year_end = $year+1;a
		
		$select->where("date BETWEEN '{$year_start}-01-01' AND '{$year_end}-01-01'");
		$list = $holiday->fetchAll($select);
		
		return $list;
	}
	
	function validateHoliday($day,$year,$month,$id){
		$holiday = new Model_Admin_TurnAroundHoliday();
		$select = $holiday->select();
		
		$select->where("date = '{$year}-{$month}-{$day}'");
		$list = $holiday->fetchAll($select);
		
		$ctr =0;
		$validate = array();
		
		foreach($list as $x){
			$ctr++;
			$validate[1] = $x->name;
			if($id!=null AND $id==$x->id){
				$ctr--;
			}
		}
		
		if($ctr!=0){
			$validate[0] = FALSE;
		}else{
			$validate[0] = TRUE;
		}
		return $validate;
	}
	
	function validateIfSundayOrSaturday($day,$year,$month){
		$date = "{$year}-{$month}-{$day} 00:00:00";
		if(strtolower(date('D',(strtotime($date)))) == 'sun'){
			return FALSE;
		}else if(strtolower(date('D',(strtotime($date)))) == 'sat'){
			return FALSE;
		}else{
			return TRUE;
		}
		
			
	}
	
	function saveHoliday($day,$year,$month,$name){
		$holiday = new Model_Admin_TurnAroundHoliday();
		$date = "{$year}-{$month}-{$day}";
		$data = array(
			'date'=>$date,
			'name'=>$name
		);
		$holiday->insert($data);
	}
	
	function getRequestedHoliday($id){
		$holiday = new Model_Admin_TurnAroundHoliday();
		$select = $holiday->select();

		$select->where("id = ?",$id);
		$list = $holiday->fetchRow($select);
		
		return $list;
	}
	
	function parseDate($date,$cat){
		switch($cat){
			case 'd':
				return substr($date,8,2);
			break;
			case 'm':
				return substr($date,5,2);
			break;
			case 'y':
				return substr($date,0,4);
			break;
		}
	}
	
	function updateHoliday($day,$year,$month,$name,$id){
		$holiday = new Model_Admin_TurnAroundHoliday();
		$select = $holiday->select();
		
		$date = "{$year}-{$month}-{$day}";
		$data = array(
			'date'=>$date,
			'name'=>$name
		);
		
		$holiday->update($data,"id={$id}");
	}
	
	function deleteHoliday($id){
		$holiday = new Model_Admin_TurnAroundHoliday();
		$holiday->delete("id = {$id}");
	}
}
	
	

    
