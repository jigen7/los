<?

class Model_Creditscore_ProductType extends Zend_Db_Table {
	protected $_name="admin.product_type";
	
	public function getName($code){
		$select = $this->select();
		$select->where('code LIKE ?',$code);
		$row = $this->fetchRow($select);	
		return $row->name;
	}

}
?>