 <?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */

    class Model_Admin_RolePermission extends Zend_Db_Table
    {
	protected $_name="admin.users_role_permission";
	
	 public function hasPermission($process){
		 
		 $auth = Zend_Auth::getInstance();
		 $user = $auth->getIdentity();
		 $role = $user->role_type;  
	 
		$select = $this->select();
		$select->where("process like ?",$process);
		$detail = $this->fetchRow($select);
		
		if($detail->$role){
			return $detail->$role;
		}else{
			return false;	
		}
		
	 }
	}


 ?>