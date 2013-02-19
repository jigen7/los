<?

class Model_Creditscore_OCSTable extends Zend_Db_Table {
	protected $_name="creditscore.ocsfields";
	
	public function addCategory($namever, $hField1, $hAtt, $rule){
		$data = array(
				'namever'	=> $namever,
				'field1'	=> $hField1,
				'value1'	=> $hAtt,
				'compare1'	=> '=',
				'type'  	=> 'String',
				'rule'		=> $rule
			);
		$this->insert($data);
	}
	
	public function addNumeric($namever, $hField1, $hField2, $hR1, $hR2, $hC1, $hC2, $logic, $rule){
		$data = array(
				'namever'	=> $namever,
				'field1'	=> $hField1,
				'value1'	=> $hR1,
				'compare1'	=> $hC1,
				'logic'		=> $logic,
				'field2'	=> $hField2,
				'value2'	=> $hR2,
				'compare2'	=> $hC2,				
				'type'  	=> 'Numeric',
				'rule'		=> $rule
			);
		$this->insert($data);
	}	
	
	public function checkCategory($namever, $hField1, $hAtt, $rule){
		$select = $this->select();
		$select->where('namever LIKE ?', $namever)
			   ->where('field1 LIKE ?', $hField1)
			   ->where('value1 LIKE ?', $hAtt)
			   ->where('rule LIKE ?', $rule);
		$count = $this->fetchAll($select)->count();
		return ($count == 0)? true : false;
	}	
	
	public function checkNumeric($namever, $hField1, $hField2, $hR1, $hR2, $hC1, $hC2, $logic, $rule){
		$select = $this->select();
		$select->where('namever LIKE ?', $namever)
			   ->where('field1 LIKE ?', $hField1)
			   ->where('field2 LIKE ?', $hField2)
			   ->where('value1 LIKE ?', $hR1)
			   ->where('value2 LIKE ?', $hR2)
			   ->where('compare1 LIKE ?', $hC1)
			   ->where('compare2 LIKE ?', $hC2)
			   ->where('logic LIKE ?', $logic)
			   ->where('rule LIKE ?', $rule);
		$count = $this->fetchAll($select)->count();
		return ($count == 0)? true : false;
	}
	
	public function delCatNum($id){
		$this->delete('id = '.$id);
	}
	
	public function getCatNum($namever, $type, $rule){
		$select = $this->select();
		$select->where('namever LIKE ?', $namever)
		       ->where('type LIKE ?', $type)
			   ->where('rule LIKE ?', $rule)
			   ->order('field1 ASC');
		return $this->fetchAll($select);
	}	
	
	public function getAllCatNum($namever){
		$select = $this->select();
		$select->where('namever LIKE ?', $namever);
		return $this->fetchAll($select);
	}
	
	public function copyCatNum($copy)
	{
		$this->insert($copy);
	}		
	
	public function delWithRule($namever, $rule)
	{
		$select = $this->select();
		$select->where('namever LIKE ?', $namever)
			   ->where('rule LIKE ?', $rule);
		$table = $this->fetchAll($select);
		foreach($table as $row){
			$this->delete('id = '.$row->id);
		}
	}
	
	public function renameModel($pName, $nName){
		$select = $this->select();
		$select->where('namever LIKE ?', $pName);
		$table = $this->fetchAll($select);
		foreach($table as $row){
			$data = array('namever'=>$nName." 1");
			$this->update($data,"id = ".$row->id);
		}		
	}	
	

}
?>