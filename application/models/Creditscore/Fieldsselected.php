<?

class Model_Creditscore_Fieldsselected extends Zend_Db_Table {
	protected $_name="creditscore.modelfields";

	public function checkFieldType($field)
	{
		$ftmodel = new Model_Creditscore_Fieldsattributes();
		$ftrow = $ftmodel->getCount($field);
		return ($ftrow > 0)? "String" : "Numeric";
	}
	
	public function addCategoryField($field, $name)
	{
		$mcmodel = new Model_Creditscore_ModelFieldsCategory();
		$famodel = new Model_Creditscore_Fieldsattributes();
		$table = $famodel->getFieldsAndAttribs($field);
		foreach($table as $row){
			$data = array(
					'namever'	=>	$name,
					'field'		=>	$field,
					'attribute'	=>	$row->values,
					'seq'		=>	$row->seq,
					'wfrom'		=> 	0,
					'wto'		=>	0,
					'namefield'	=>	$row->name,
				);
			$mcmodel->addRow($data);
		}				
	}

	public function addNumericField($field, $name)
	{
		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();
		$ftmodel = new Model_Creditscore_FieldsTable();
		$ftrow = $ftmodel->getField($field);
		$data = array(
				'namever'	=>	$name,
				'field'		=>	$field,
				'attribute'	=>	'',
				'wfrom'		=> 	0,
				'wto'		=>	0,
				'rfrom'		=> 	0,
				'rto'		=>	1,		
				'namefield'	=>	$ftrow->name,
			);
		$mnmodel->addRow($data);				
	}
	
	public function addedCNFields($name)
	{		
		$mcmodel = new Model_Creditscore_ModelFieldsCategory();
		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();
		$mcarray = $mcmodel->addedFields($name);
		$mnarray = $mnmodel->addedFields($name);
	
		$cmarray = array();
		foreach($mcarray as $field){
			$cmarray[] = $field;
		}
		foreach($mnarray as $field){
			$cmarray[] = $field;
		}
		return $cmarray;					
	}	
	
	public function deleteCNField($model, $field)
	{
		$mcmodel = new Model_Creditscore_ModelFieldsCategory();
		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();
		$mcarray = $mcmodel->deleteField($model, $field);
		$mnarray = $mnmodel->deleteField($model, $field);
	}	

	public function getCRow($id)
	{
		$select = $this->select();
		$select->where('id = '.$id);
		return $this->fetchRow($select);
	}

	public function viewModelSpecific($name)
	{
		$mcmodel = new Model_Creditscore_ModelFieldsCategory();
		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();
		$mcarray = $mcmodel->getFieldsAndAttrib($name);
		$mnarray = $mnmodel->getFieldsAndAttrib($name);
		$cmarray = array();
		foreach($mcarray as $field){
			$cmarray[] = $field;
		}
		foreach($mnarray as $field){
			$field->attribute = $field->rfrom." - ".$field->rto;			
			$cmarray[] = $field;
		}
		return $cmarray;		
	}	
	
	public function checkCField($namever, $field)
	{
		$mnmodel = new Model_Creditscore_ModelFieldsCategory();
		return $mnmodel->checkField($namever, $field);
	}
	
	public function checkNField($namever, $field)
	{
		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();
		return $mnmodel->checkField($namever, $field);
	}


//########################################################################
//########################################################################
//########################################################################
//########################################################################
	
	
	public function addTableField($field, $name)
	{
		$ftmodel = new Model_Creditscore_FieldsTable();
		$famodel = new Model_Creditscore_Fieldsattributes();
		if($famodel->getCount($field) > 0){
			$table = $famodel->getFieldsAndAttribs($field);
			foreach($table as $row){
				$type = $ftmodel->getType($field);
				$data = array(
						'namever'	=>	$name,
						'field'		=>	$field,
						'attribute'	=>	$row->values,
						'wfrom'		=> 	0,
						'wto'		=>	0,
						'namefield'	=>	$row->name,
						'type'		=>	$type->type,
						'rfrom'		=>	0,
						'rto'		=>  0
					);
			$this->insert($data);
			}
		}else{
			$type = $ftmodel->getType($field);
			$data = array(
					'namever'	=>	$name,
					'field'		=>	$field,
					'attribute'	=>	'',
					'wfrom'		=> 	0,
					'wto'		=>	0,
					'namefield'	=>	$type->name,
					'type'		=>	$type->type,
					'rfrom'		=>	0,
					'rto'		=>  0
				);
			$this->insert($data);			
		}
	}	
	
	public function replicateField($name, $field)
	{
		$ftmodel = new Model_Creditscore_FieldsTable();
		$type = $ftmodel->getType($field);
		$data = array(
				'namever'	=>	$name,
				'field'		=>	$field,
				'attribute'	=>	'',
				'wfrom'		=> 	0,
				'wto'		=>	0,
				'namefield'	=>	$type->name,
				'type'		=>	$type->type,
				'rfrom'		=>	0,
				'rto'		=>  0
			);
		$this->insert($data);
	}
	
	public function checkFields($field, $name)
	{
		$select = $this->select();
		$select->where('field LIKE ?', $field)
			   ->where('namever LIKE ?', $name);
		$count = $this->fetchAll($select)->count();
		return ($count > 0)? false : true;
	}
	
	public function addedFields($name)
	{
		$array = array('field','namever','namefield');
		$select = $this->select();
		$select->distinct('field')
			   ->from($this,$array)
		       ->where("namever LIKE ?", $name);
		$count = $this->fetchAll($select)->count();
		if($count > 0) $select->order('field DESC');
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
	
	public function deleteReplicateField($id)
	{
		$this->delete('id = '.$id);
	}
	
	public function getFieldsAndAttrib($namever)
	{
		$select = $this->select();
 		$select->where('namever LIKE ?', strtolower($namever))
			   ->order('id DESC');
		return $this->fetchAll($select);
	}	
	
	public function copyFieldsAndAttrib($copy)
	{
		$this->insert($copy);
	}
	
	public function addFieldsAndAttrib($id, $wfrom, $wto)
	{
		$data = array('wfrom'=>$wfrom,'wto'=>$wto);
		$this->update($data,'id = '.$id);
	}
	
	public function updateFieldsAndAttrib($id, $value)
	{
		$select = $this->select();
		$select->where('id = '.$id);
		$wto = $this->fetchRow($select);
		$data = array('wfrom'=>$wto->wto,'wto'=>$value);
		$this->update($data,'id = '.$id);
	}
	
	public function saveFieldsAndAttrib($id, $value)
	{
		$data = array('wto'=>$value);
		$this->update($data,'id = '.$id);
	}
	
	public function getRow($id)
	{
		$select = $this->select();
		$select->where('id = '.$id);
		return $this->fetchRow($select);
	}
	
	public function compareField()
	{
		$select = $this->select();
	}
	
	public function getModels($namever)
	{
		$select = $this->select();
		$select->where('namever LIKE ?', $namever);
		return $this->fetchAll($select);
	}
	
	public function getModelsFields($namever)
	{
		$array = array('field','namever');
		
		$select = $this->select();
		$select->where('namever LIKE ?',$namever)
		->distinct('field')
		->from($this,$array);
		$output = $this->fetchAll($select);
		//echo $select;
		$fields = array();
		
		foreach($output as $x){
		$fields[]= $x->field;			
		}
		return $fields;
		
	}	
	
	

}
?>