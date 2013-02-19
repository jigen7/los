<?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */
class Model_AutoRouting_CrecomConfig extends Zend_Db_Table {
	protected $_name="autorouting.crecom_config";
	
		public function getMemberType($role){
		$select = $this->select();
		$select->where('role like ?',$role);
		$detail = $this->fetchRow($select);
		return $detail->type;
		}
		
		public function countEnable(){
		$select = $this->select();
		$select->where('enable = true');
		$count = $this->fetchAll($select)->count();
		return $count;	
		}
		
		public function getAll(){
			$select = $this->select();
			$select->order("id ASC");
			return $this->fetchAll($select);
		}
	
		public function getMembers(){
		$select = $this->select();
		$select->where('enable = true');
		return $this->fetchAll($select);			
		}
	
    }
	

?>