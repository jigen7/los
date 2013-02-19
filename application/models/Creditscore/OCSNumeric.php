<?

class Model_Creditscore_OCSNumeric extends Zend_Db_Table {
	protected $_name="creditscore.ocsfields_numeric";
	
	public function addCategory($namever, $hField, $hR1, $hR2, $hC1, $hC2, $logic){
		$data = array(
				'namever'	=> $namever,
				'field'		=> $hField,
				'range1'	=> $hR1,
				'range2'	=> $hR2,
				'compare1'	=> $hC1,
				'compare2'	=> $hC2,
				'logic'  	=> $logic
			);
		$this->insert($data);
	}

	public function delCategory($id){
		$this->delete('id = '.$id);
	}
	
	public function getCategories($namever){
		$select = $this->select();
		$select->where('namever LIKE ?', $namever)
			   ->order('field DESC');
		return $this->fetchAll($select);
	}
	
	public function checkCategory($namever, $hField, $hR1, $hR2, $hC1, $hC2, $logic){
		$select = $this->select();
		$select->where('namever LIKE ?', $namever)
			   ->where('field LIKE ?', $hField)
			   ->where('range1 LIKE ?', $hR1)
			   ->where('range2 LIKE ?', $hR2)
			   ->where('compare1 LIKE ?', $hC1)
			   ->where('compare2 LIKE ?', $hC2)
			   ->where('logic LIKE ?', $logic);
		$count = $this->fetchAll($select)->count();
		return ($count == 0)? true : false;

	}
	
	

}
?>