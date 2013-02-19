<?

class Model_Creditscore_ModelFieldsCategory extends Zend_Db_Table {
	protected $_name="creditscore.modelfields_category";
	
	public function addRow($data)
	{
		$this->insert($data);
	}
	
	public function addedFields($name)
	{
		$array = array('field','namever','namefield');
		$select = $this->select();
		$select->distinct('field')
			   ->from($this,$array)
		       ->where("namever LIKE ?", $name);
		$count = $this->fetchAll($select)->count();
		if($count > 0) $select->order('field ASC');
		return $this->fetchAll($select);		
	}
	
	public function deleteField($model, $field)
	{
		$select = $this->select();
		$select->where('namever LIKE ?', $model)
			   ->where('field LIKE ?', $field);
		$table = $this->fetchAll($select);
		foreach($table as $row){
			$this->delete('id = '.$row->id);
		}
	}	
	
	public function getRow($id)
	{
		$select = $this->select();
		$select->where('id = '.$id);
		return $this->fetchRow($select);
	}		
	
	public function addFieldsAndAttrib($id, $wfrom, $wto)
	{
		$data = array('wfrom'=>$wfrom,'wto'=>$wto);
		$this->update($data,'id = '.$id);
	}	
	
	public function copyFieldsAndAttrib($copy)
	{
		$this->insert($copy);
	}
	
	public function getFieldsAndAttrib($namever)
	{
		$select = $this->select();
 		$select->where('namever LIKE ?', strtolower($namever))
			   ->order('field ASC')
			   ->order('attribute ASC');
		return $this->fetchAll($select);
	}	
	
	public function getCFieldsAndAttrib($namever)
	{
		$select = $this->select();
 		$select->where('namever LIKE ?', strtolower($namever))
			   ->order('id ASC');
		return $this->fetchAll($select);
	}	
	
	public function checkField($namever, $field)
	{
		$select = $this->select();
		$select->where('namever LIKE ?', strtolower($namever))
			   ->where('field LIKE ?', $field);
		$count = $this->fetchAll($select)->count();	   
		return ($count == 0)? true : false;
	}
	
	public function updateFieldsAndAttrib($id, $value)
	{
		$select = $this->select();
		$select->where('id = '.$id);
		$wto = $this->fetchRow($select);
		$data = array('wfrom'=>$wto->wto,'wto'=>$value);
		$this->update($data,'id = '.$id);
	}	

	public function getWeight($namever, $field, $att)
	{
		$select = $this->select();
		$select->where('namever LIKE ?', $namever)
			   ->where('namefield LIKE ? ', $field)
			   ->where('attribute LIKE ?', $att);
		$count = $this->fetchAll($select)->count();
		if($count > 0){
			 $row = $this->fetchRow($select);	
			 return $row->wto;	
		}
		return 0;
	}
	
	public function getPrevWeight($namever, $field, $att)
	{
		$select = $this->select();
		$select->where('namever LIKE ?', $namever)
			   ->where('field LIKE ? ', $field)
			   ->where('attribute LIKE ?', $att);
		$count = $this->fetchAll($select)->count();
		if($count > 0){
			 $row = $this->fetchRow($select);	
			 return $row->wto;	
		}
		return 0;
	}	
	
	public function renameModel($pName, $nName){
		$select = $this->select();
		$select->where('namever LIKE ?', $pName);
		$table = $this->fetchAll($select);
		foreach($table as $row){
			$data = array('namever' => $nName." 1");
			$this->update($data,"id = ".$row->id);
		}		
	}
	
	public function getWeightsForScore($fieldType, $fieldvalue, $field, $modelver){
		$select = $this->select();
		if($fieldType == 'String'){
			$select->where("attribute like ?",$fieldvalue);
		}
		else if($fieldType == 'Numeric'){
			$select->where("seq = ?",$fieldvalue);
		}
		$select->where("field like ?",$field);
		$select->where("namever like ?",$modelver);
		return $this->fetchRow($select);	
	}

}
?>