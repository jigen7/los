<?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */
class Model_Report_Accounts extends Zend_Db_Table {
	protected $_name="report.accounts";
	
	function getAccounts($process,$user,$status,$from,$to){

		$startdate = $from;
		if(!$startdate) { $startdate = '1999-12-30'; } 
		$enddate = $to;
		if(!$enddate) { $enddate = '2999-12-30'; } 
		
		
		$select = $this->select();
		if($user){
		$select->where("by like ?",$user);
		}
		$select->where("status like ?",$status);
		
		if($startdate == $enddate){
				$enddate = $startdate." 24:00:00";
				$startdate = $startdate." 00:00:00";
		$select->where("datetime between '$startdate'  and '$enddate'");
		}else {
			$startdate = $startdate." 01:00:00";	// of the morning 
			$enddate = $enddate." 24:00:00";	 // of the evening
			$select->where("datetime between '$startdate' and '$enddate'");
		
		}
		$select->distinct('capno')->from($this,array('capno'));
		$select->order('capno');
		if($process == 'get'){
		return $this->fetchAll($select);
		}
		if($process == 'count'){
		return $this->fetchAll($select)->count();
		}
	}
	
    }
?>