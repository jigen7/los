 <?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */

    class Model_Admin_AccountStatus extends Zend_Db_Table
    {
	protected $_name="admin.account_status";
	
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
	
	}


 ?>