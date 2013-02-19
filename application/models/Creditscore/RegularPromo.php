<?

class Model_Creditscore_RegularPromo extends Zend_Db_Table {
	protected $_name="admin.regular_promo";
	
	public function getAllRegularPromo()
	{	
		$select = $this->select();
		return $this->fetchAll($select);
	}
	
	public function getCode($name){
		$select = $this->select();
		$select->where('name LIKE ?',$name);
		return $this->fetchRow($select);
	}

}
?>