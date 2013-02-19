<?

class Model_Creditscore_RegularPromoSource extends Zend_Db_Table {
	protected $_name="promo_source";
	
	public function getAllRegularPromo()
	{	
		$select = $this->select();
		return $this->fetchAll($select);
	}
	
	public function getCode($name){
		$select = $this->select();
		$select->where('promo_name LIKE ?',$name);
		return $this->fetchRow($select);
	}

}
?>