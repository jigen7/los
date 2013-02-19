 <?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */

    class Model_UsersRoles extends Zend_Db_Table

    {
	protected $_name="users_roles";
		
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
    }


 ?>