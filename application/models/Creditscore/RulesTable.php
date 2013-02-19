<?

class Model_Creditscore_RulesTable extends Zend_Db_Table {
	protected $_name="creditscore.rules";
	
	public function getRules($namever)
	{
		$select = $this->select();
		$select->where('namever LIKE ?', $namever)
		       ->order('rule ASC');
		return $this->fetchAll($select);
	}		
	
	public function addRule($namever, $rule, $desc)
	{
		$data = array(
					'namever' => $namever,
					'rule' => strtolower($rule),
					'description' => $desc
				);
		$this->insert($data);
	}
	
	public function checkRule($namever, $rule)
	{
		$select = $this->select();
		$select->where('namever LIKE ?', $namever)
		       ->where('rule LIKE ?', strtolower($rule));
		$count = $this->fetchAll($select)->count();
		return ($count == 0)? true : false;
	}
	
	public function deleteRule($id)
	{
		$this->delete('id = '.$id);
	}
	
	public function copyRules($copy)
	{
		$this->insert($copy);
	}			
	
	public function getRule($id)
	{
		$select = $this->select();
		$select->where('id = '.$id);
		return $this->fetchRow($select);
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