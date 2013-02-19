<?

class Model_Creditscore_Fields extends Zend_Db_Table {
	protected $_name="admin.fields";
	
	public function getTable()
	{	
		$table = $this->select();
		$table->distinct('model');
		return $this->fetchAll($table);		
	}
	
	public function getFields($table)
	{
		$fields = $this->select();
		$fields->where('model LIKE ?', $table);
		return $this->fetchAll($fields);
	}
	
	public function firstTable()
	{
		$table = $this->select();
		$table->distinct('model');
		return $this->fetchRow($table);	
	}
	
	public function getFieldModel($field){
		
		$select = $this->select();
		$select->where('field like ?',$field);
		$detail = $this->fetchRow($select);
		
		return $detail->model;
		
	}

	public function getFieldBasis($field){
		$select = $this->select();
		$select->where('field like ?',$field);
		$detail = $this->fetchRow($select);
		return $detail->basis;
	}
	
	public function getFetchMode($field){
		$select = $this->select();
		$select->where('field like ?',$field);
		$detail = $this->fetchRow($select);
		return $detail->fetchmode;
	}
	
	public function getFieldEmpBus($field){
		$select = $this->select();
		$select->where('field like ?',$field);
		$detail = $this->fetchRow($select);
		return $detail->busemp;
	}

	
}
?>