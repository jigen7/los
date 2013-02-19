<?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */
class Model_AutoRouting_AccountsSubCrecom extends Zend_Db_Table {
	protected $_name="autorouting.accounts_subcrecom";
	
		public function fetchRowModel($capno){
		$select = $this->select();
		$select->where('capno LIKE ?',$capno);
		$detail =  $this->fetchRow($select);	
		return $detail;	
	}
	
	public function fetchAllModel($capno){
		$select = $this->select();
		$select->where('capno LIKE ?',$capno);
		$detail =  $this->fetchAll($select);	
		return $detail;	
	}
	
	public function chkifDecide($capno,$role){
		$select = $this->select();
		$select->where('capno LIKE ?',$capno);
		$select->where('role LIKE ?',$role);
		$detail = $this->fetchRow($select);
		
		if($detail){
			return true;
		}else {
			return false;
		}	
	}
	
	public function countDisapprove($capno){
		$select = $this->select();
		$select->where('capno like ?',$capno);
		$select->where('decision like ?','Disapproved');
		return $this->fetchAll($select)->count();
	}
	
	public function countApprove($capno){
		$select = $this->select();
		$select->where('capno like ?',$capno);
		$select->where('decision like ?','Approved');
		return $this->fetchAll($select)->count();
	}
	
	public function chkChairmanDisapproveNormal($capno){
		$select = $this->select();
		$select->where('capno like ?',$capno);		
		$select->where('decision like ?','Disapproved');
		$select->where('type like ?','chairman');
		return $this->fetchAll($select)->count();
	}
	
	public function chkChairmanApproveNormal($capno){
		//chairman not absent
		$select = $this->select();
		$select->where('capno like ?',$capno);
		$select->where('decision like ?','Approved');
		$select->where('type like ?','chairman');
		return $this->fetchAll($select)->count();
	}
	
	public function chkMemberApproveNormal($capno){
		//chairman not absent
		$select = $this->select();
		$select->where('capno like ?',$capno);
		$select->where('decision like ?','Approved');
		$select->where("type='vice-chairman' or type ='member'");
		return $this->fetchAll($select)->count();
	}
	
	public function chkChairmanifDecidedNormal($capno){
		$select = $this->select();
		$select->where('capno like ?',$capno);
		$select->where("decision = 'Approved' or decision = 'Disapproved'");
		$select->where('type like ?','chairman');
		return $this->fetchAll($select)->count();
	}
	
	public function chkMemberDecidedNormal($capno){
		$select = $this->select();
		$select->where('capno like ?',$capno);
		$select->where("decision = 'Approved' or decision = 'Disapproved'");
		$select->where("type='vice-chairman' or type ='member'");
		$select->order("id ASC");
		$select->limit(2);
		$detail = $this->fetchAll($select);
		return $detail;
	}
	
	function getAccounts($process,$user,$status,$from,$to){

		$startdate = $from;
		if(!$startdate) { $startdate = '1999-12-30'; } 
		$enddate = $to;
		if(!$enddate) { $enddate = '2999-12-30'; } 
		
		
		$select = $this->select();
		$select->where('"user" like ?',$user);
		
		$select->where("decision like ?",$status);
		
		if($startdate == $enddate){
				$enddate = $startdate." 24:00:00";
				$startdate = $startdate." 00:00:00";
		$select->where("date_decision between '$startdate'  and '$enddate'");
		}else {
			$startdate = $startdate." 01:00:00";	// of the morning 
			$enddate = $enddate." 24:00:00";	 // of the evening
			$select->where("date_decision between '$startdate' and '$enddate'");
		
		}
		//$select->distinct('capno')->from($this,array('capno'));
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