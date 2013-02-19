 <?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */

    class Model_UsersTimeInfo extends Zend_Db_Table

    {
	protected $_name="users_login_information";
		
	function loginDB($username,$role){
		$data = array(
		'datetime'=>date("r"),
		'type'=>'login',
		'set_by'=>'SYSTEM',	
		'role'=>$role,
		'ip'=>$_SERVER['REMOTE_ADDR'],
		'username'=>$username
		);
		$this->insert($data);
	}
	
	function logoutDB($username,$role){
		$data = array(
		'datetime'=>date("r"),
		'type'=>'logout',
		'set_by'=>'SYSTEM',	
		'role'=>$role	,
		'ip'=>$_SERVER['REMOTE_ADDR'],
		'username'=>$username
		);
		$this->insert($data);
		
	}
	
	function chkAbsent($username){
		//chk absent and return na alternative approver for that person if no one is indicated
		// pass it to the next level
		$date = date('Y-m-d');
		$startdate = $date." 08:00:00";	// of the morning 
		$enddate = $date." 21:30:00";	 // of the afternoon
		
		$select = $this->select();

		$select->where('username like ?',$username);
		$select->where('type like ?','absent');
		$select->where("datetime between '$startdate' and '$enddate'");
		$detail = $this->fetchAll($select);
		
		$count =  $detail->count();	

		if($count > 0){						
			if ($detail->approver_alternative){
			return $detail->approver_alternative;	
			}
			else {
			return '';	
			}
			
		}else {			
			return 'present';
		}
	}
	
	
	

	
    }


 ?>