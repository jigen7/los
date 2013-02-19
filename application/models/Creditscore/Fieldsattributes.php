<?

class Model_Creditscore_Fieldsattributes extends Zend_Db_Table {
	protected $_name="admin.list_category_values_new";
	
	public function getFields()
	{
		$fields = $this->select();
		return $this->fetchAll($fields);	
	}
	
	public function getAttributes($name)
	{
		$fields = $this->select();
		$fields->where('name LIKE ?', $name);
		return $this->fetchAll($fields);
	}
	
	public function getFieldsAndAttribs($name)
	{
		$fields = $this->select();
		$fields->where('field LIKE ?', $name)
		        ->order('values ASC');
		return $this->fetchAll($fields);	
	}	
	
	public function getCount($name)
	{
		$fields = $this->select();
		$fields->where('field LIKE ?', $name);
		return $this->fetchAll($fields)->count();	
	}

}
?>