<?

class Model_Creditscore_FieldsTable extends Zend_Db_Table {
	protected $_name="admin.fields";
	
	public function getFields()
	{
		$fields = $this->select();
		$fields->order('name ASC');
		return $this->fetchAll($fields);	
	}
	
	public function getAttributes($name)
	{
		$fields = $this->select();
		$fields->where('field LIKE ?', $name);
		return $this->fetchAll($fields);
	}
	
	public function getType($field)
	{
		$fields = $this->select();
		$fields->where('field LIKE ?', $field);
		return $this->fetchRow($fields);
	}

	public function getField($field)
	{
		$fields = $this->select();
		$fields->where('field LIKE ?', $field);
		return $this->fetchRow($fields);
	}
	
	public function getNFType($field)
	{
		$fields = $this->select();
		$fields->where('name LIKE ?', $field);
		return $this->fetchRow($fields);
	}
}
?>