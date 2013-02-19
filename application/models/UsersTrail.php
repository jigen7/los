 <?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */

    class Model_UsersTrail extends Zend_Db_Table

    {
	protected $_name="user_account_trail";	
	
	public function passwordchange($user){
		$data = array(
		'datetime'=>date("r"),
		'user'=>$user,
		'by'=>$user,
		'type'=>'User Changes Password'				
		);
		$this->insert($data);
	
	}
	public function digipasswordchange($user){
		$data = array(
		'datetime'=>date("r"),
		'user'=>$user,
		'by'=>$user,
		'type'=>'User Changes Digital Password'				
		);
		$this->insert($data);
	
	}
	
	public function failedlogin($username,$string){
		
		$table = new Model_UsersTimeInfo();
		$data = array(
		'datetime'=>date("r"),
		'type'=>$string,
		'set_by'=>'SYSTEM',	
		'role'=>$role	,
		'ip'=>$_SERVER['REMOTE_ADDR'],
		'username'=>$username
		);
		$table->insert($data);		
	}
	
	function chkloginfailed($username){
		
		$table = new Model_UsersTimeInfo();
		$select = $table->select();
		$select->where('username like ?',$username);
		
		$startdate = date('Y-m-d').' 01:00:00';	// of the morning 
		$enddate = date('Y-m-d').' 24:00:00';	 // of the evening
		$select->where('type like ?','Invalid password');
		$select->where("datetime between '$startdate' and '$enddate'");

		$count = $table->fetchAll($select)->count();

		if($count >= 10){
			$data = array(
			'active'=>0
			);
			$users = new Model_Users();
			$where = "username like '$username'";
			$users->update($data,$where);
			
			$this->failedlogin($username, 'Account Lock');
		}
		}
	
	
    }


 ?>