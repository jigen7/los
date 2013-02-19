<?

class Model_AutoRouting_ApproverAttendance extends Zend_Db_Table {
	protected $_name="autorouting.approver_attendance";
	
	public function addApproverAttendance($user, $role, $from, $to, $by, $remarks){
		$data = array(
					'username' => $user,
					'role' => $role,
					'from_date' => $from,
					'to_date' => $to,
					'by' => $by,
					'date_input_by'	=> date("m-d-Y H:i:s", time()),
					'check_by' => '',
					'date_check' => date("m-d-Y H:i:s", time()),
					'remarks' => $remarks
				);
		$this->insert($data);
	}
	
	public function getAllApproverAttendance(){
		$select = $this->select();
		return $this->fetchAll($select);
	}
	
	public function hasAttendance($username){
		$select = $this->select();
		$select->where('username LIKE ?', $username);
		$count = $this->fetchAll($select)->count();
		return ($count != 0)? true : false;
	}
	
	public function getApproverAttendance(){
		$select = $this->select();
		$select->where('username LIKE ?',$username);
		return $this->fetchRow($select);
	}
	
	public function checkDate($username, $date){
		$select = $this->select();
		$select->where('username LIKE ?', $username)
			   ->where("from_date <= ".$date)
			   ->where("to_date >= ".$date);
		$count = $this->fetchAll($select)->count();	
		return ($count != 0)? true : false;   
	}
		
	
}

?>