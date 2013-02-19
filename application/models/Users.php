 <?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */

    class Model_Users extends Zend_Db_Table

    {
	protected $_name="users";
		
	function checkUnique($username)
	
	{
	
		$select = $this->_db->select()->from($this->_name,array('username'))->where('username=?',$username);		
		$result = $this->getAdapter()->fetchOne($select);
		
		if($result){
		return true;
		}
		else {
		return false;
		}
	}
	
	function returnUsernamebyId($id){
		$select = $this->select();
		$select->where('id = ?',$id);
		$detail = $this->fetchRow($select);
		return $detail->username;		
	}
	
	function returnUsernamebyRole($role){
		
		//use in autorouting absent
		$select = $this->select();
		$select->where('role_type like ?',$role);
		$select->where('testuser = false');
		$select->where('active = true');
		$select->where('profile like ?','main');
		$detail = $this->fetchRow($select);
		
		if($detail){
		return $detail->username;	
		}
		else{
		return	$role;
		}
		
	}
	
	function cellnumberbyUsername($username){
			$ma = new Model_Users();
			$select = $ma->select();
			$select->where('username like ?',$username);
			$madetail = $ma->fetchRow($select);
		
			return $madetail->cellnumber;
	}
	
	function returnUsersbyRole($role){
		$select = $this->select();
		$select->where('role_type like ?',$role);
		$select->where('testuser = false');
		$select->where('active = true');

		$detail = $this->fetchAll($select);
		return $detail;
		
	}
	
	function returnApproverbyRole($role){
		//use in autorouting absent
		$select = $this->select();
		$select->where('role_type like ?',$role);
		$select->where('profile like ?','main');
		$detail = $this->fetchRow($select);
		return $detail->username;
	}
	
	function returnFullname($username){
	//Returns the name of the MA base on its Username
		if ($username){
		$ma = new Model_Users();
		$select = $ma->select();
		$select->where('username like ?',$username);
		$madetail = $ma->fetchRow($select);
		
			if ($madetail->name){
			return $madetail->name;
			}
			else {return "";}
		}
		else {
			return "";
		}
	}
	
	function returnFullname2($username){
	//Returns the name of the MA base on its Username
	if ($username){
	$ma = new Model_Users();
	$select = $ma->select();
	$select->where('username like ?',$username);
	$madetail = $ma->fetchRow($select);
	
	if ($madetail->name){
	return $madetail->name;
	}
	else {return $username;}
	}
	else {
		return $username;
	}
	}
	
	function checkactive($username){
		$select = $this->select();
		$select->where('username like ?',$username);
		$detail = $this->fetchRow($select);
		
		if($detail->active === true){
			return true;
		}else{
			return false;
		}
		
	}
	
	function checkifexist($username){
		
		$select = $this->select();
		$select->where('username like ?',$username);
		$detail = $this->fetchRow($select);
	
		if($detail->username){
			return true;
		}else{
			return false;
		}		
		
	}
	
	function checkpassword($username,$password){	
		$select = $this->select();
		$select->where('username like ?',$username);
		$detail = $this->fetchRow($select);
		
		if($detail->password == $password){
			return true;
		}else{
			return false;
		}	
	}
	
	function getpasswordstatus($username){
		//use in chcking if they change their default password
		$select = $this->select();
		$select->where('username like ?',$username);
		$detail = $this->fetchRow($select);
		
		if($detail->change_default_password === true){
			return true;
		}else{
			return false;
		}
		
	}
	
	function getdigipasswordstatus($username){
		//use in chcking if they change their default password
		$select = $this->select();
		$select->where('username like ?',$username);
		$detail = $this->fetchRow($select);
		
		if($detail->change_default_digi_password === true){
			return true;
		}else{
			return false;
		}
		
	}	

	//alexis
	function getApprovers(){
		$select = $this->select();
		$role = array('CO','CSH','CMGH','PRES','ALMH');
		$select->where(' role_type IN (?)', $role)->order('id ASC');
		return $this->fetchAll($select);
	}
	
	//alexis
	function getRoleType($username){
		$select = $this->select();
		$select->where('username LIKE ?', $username);
		$row = $this->fetchRow($select);
		return $row->role_type;	
	}
	
}


 ?>