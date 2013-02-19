<?

class Model_Creditscore_BusinessCenters extends Zend_Db_Table {
	protected $_name="admin.business_centers";
	
	public function getAllBusinessCenter()
	{	
		$select = $this->select();
		return $this->fetchAll($select);
	}
	
	public function getCode($name){
		$select = $this->select();
		$select->where('name LIKE ?',$name);
		return $this->fetchRow($select);
	}
	
	public function getName($code){
		$select = $this->select();
		$select->where('code LIKE ?',$code);
		return $this->fetchRow($select);	
	}

}
?>