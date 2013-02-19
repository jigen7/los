 <?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */

    class Model_Admin_AccountStatus extends Zend_Db_Table
    {
	protected $_name="admin.account_status";
	
	
	public function smsStatus($status){
		
		if($this->isPass($status) === true){
			return "P";
		}
		else if($this->isApproved($status) === true){
			return "A";
		}
		else if($this->isRejected($status) === true){
			return "R";
		}
		else if($this->isCSH($status) === true){
			return "S";
		}
		
		/* 
		 Add for crecom routine??for sir jez?
		 */
		else {
			//M
			return "M";
		}
		
		
	}
	
	public function isPass($process){
		$select = $this->select();
		$select->where("status like ?",$process);
		$detail = $this->fetchRow($select);
		return $detail->pass;
		
	}
	
	public function isCSH($process){
		// only CO - ReAp, CO - ReR, CO - P
		$select = $this->select();
		$select->where("status like ?",$process);
		$detail = $this->fetchRow($select);
		return $detail->csh_recommending;		
	}
	
	public function isRejected($process){
		$select = $this->select();
		$select->where("status like ?",$process);
		$detail = $this->fetchRow($select);
		return $detail->reject;		
	}
	
	public function isApproved($process){
		$select = $this->select();
		$select->where("status like ?",$process);
		$detail = $this->fetchRow($select);
		return $detail->approve;
	}
	
	public function isDecided($process){
		$select = $this->select();
		$select->where("status like ?",$process);
		$detail = $this->fetchRow($select);
		return $detail->decided;
	}
	
	public function routeBox($field){
		$select = $this->select();
		$select->where("$field = TRUE");
		$detail = $this->fetchAll($select);
		return $detail;
	}
	
	public function routeview($status,$field){
		/***
		 * Paolo Marco Manarang
		 * Aug 26,2010
		 ***/
		//returns boolean for the view RoleAccess View Helper
		// use also in RolePermissionHelper
		// use also in controller to deny access
		
		$select = $this->select();
		$select->where('status like ?',$status);
		$detail = $this->fetchRow($select);
		return $detail->$field;
	}
	
	public function arrayString($array){
	foreach($array as $x){
		$string = $string.$x;
		
	}
	return $string;
	
	}
	
	public function getCurrentUser($status){
		
		$select = $this->select();
		$select->where('status like ?',$status);
		$detail = $this->fetchRow($select);
		return $detail->current_user;
	}
	
	
	
	
	}


 ?>