<?

class Model_Creditscore_OCSCategory extends Zend_Db_Table {
	protected $_name="creditscore.ocsfields_category";
	
	public function addCategory($namever, $hField, $hAtt){
		$data = array(
				'namever'	=> $namever,
				'field'		=> $hField,
				'attribute'	=> $hAtt
			);
		$this->insert($data);
	}

	public function delCategory($namever, $hField, $hAtt){
		$select = $this->select();
		$select->where('namever LIKE ?', $namever)
			   ->where('field LIKE ?', $hField)
			   ->where('attribute LIKE ?', $hAtt);
		$row = $this->fetchRow($select);
		$this->delete('id = '.$row->id);
	}
	
	public function getCategories($namever){
		$select = $this->select();
		$select->where('namever LIKE ?', $namever)
		       ->order('field DESC');
		return $this->fetchAll($select);
	}
	
	public function checkCategory($namever, $hField, $hAtt){
		$select = $this->select();
		$select->where('namever LIKE ?', $namever)
			   ->where('field LIKE ?', $hField)
			   ->where('attribute LIKE ?', $hAtt);
		$count = $this->fetchAll($select)->count();
		return ($count == 0)? true : false;

	}
	
	

}
?>