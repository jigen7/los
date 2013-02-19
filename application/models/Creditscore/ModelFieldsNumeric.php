<?

class Model_Creditscore_ModelFieldsNumeric extends Zend_Db_Table {
	protected $_name="creditscore.modelfields_numeric";
	
	public function addRow($data)
	{
		$this->insert($data);
	}
	
	public function getRow($id)
	{
		$select = $this->select();
		$select->where('id = '.$id);
		return $this->fetchRow($select);
	}	

	public function addedFields($name)
	{
		$array = array('field','namever','namefield');
		$select = $this->select();
		$select->distinct('field')
			   ->from($this,$array)
		       ->where("namever LIKE ?", $name);
		$count = $this->fetchAll($select)->count();
		if($count > 0) $select->order('namefield ASC');
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

	public function addFieldsAndAttrib($id, $wfrom, $wto)
	{
		$data = array('wfrom'=>$wfrom,'wto'=>$wto);
		$this->update($data,'id = '.$id);
	}
	
	public function addFieldsAndFRange($id, $rfrom)
	{
		$data = array('rfrom'=>$rfrom);
		$this->update($data,'id = '.$id);
	}	
	
	public function addFieldsAndTRange($id, $rto)
	{
		$data = array('rto'=>$rto);
		$this->update($data,'id = '.$id);
	}	
	
	public function addAttribute($id, $att)
	{
		$data = array('attribute'=>$att);
		$this->update($data,'id = '.$id);
	}
	
	public function updateFieldsAndFRange($id, $value)
	{
		$select = $this->select();
		$select->where('id = '.$id);
		$rto = $this->fetchRow($select);
		$data = array('rfrom'=>$value);
		$this->update($data,'id = '.$id);
	}
	
	public function updateFieldsAndTRange($id, $value)
	{
		$select = $this->select();
		$select->where('id = '.$id);
		$rto = $this->fetchRow($select);
		$data = array('rto'=>$value);
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
	
	public function copyFieldsAndAttrib($copy)
	{
		$this->insert($copy);
	}	
	
	public function getFieldsAndAttrib($namever)
	{
		$select = $this->select();
 		$select->where('namever LIKE ?', strtolower($namever))
			   ->order('namefield ASC')
			   ->order('id ASC');
		return $this->fetchAll($select);
	}		
	
	public function deleteReplicateField($id)
	{
		$this->delete('id = '.$id);
	}	
	
	public function checkField($namever, $field)
	{
		$select = $this->select();
		$select->where('namever LIKE ?', strtolower($namever))
			   ->where('field LIKE ?', $field);
		$count = $this->fetchAll($select)->count();	   
		return ($count == 0)? true : false;
	}
	
	public function getWeight($namever, $field, $att)
	{
		$range = explode(" - ",$att);
//		echo "<br>".$range[0]."+".$range[1];
		$select = $this->select();
		$select->where('namever LIKE ?', $namever)
			   ->where('namefield LIKE ?', $field)
			   ->where('rfrom LIKE ?', $range[0])
			   ->where('rto LIKE ?', $range[1]);
		$count = $this->fetchAll($select)->count();
		if($count > 0){
			 $row = $this->fetchRow($select);	
			 return $row->wto;	
		}
		return 0;
	}	
	
	public function getPrevWeight($namever, $field, $att)
	{
		$range = explode(" - ",$att);
//		echo "<br>".$range[0]."+".$range[1];
		$select = $this->select();
		$select->where('namever LIKE ?', $namever)
			   ->where('field LIKE ?', $field)
			   ->where('rfrom LIKE ?', $range[0])
			   ->where('rto LIKE ?', $range[1]);
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
			$data = array('namever'=>$nName." 1");
			$this->update($data,"id = ".$row->id);
		}		
	}		
	
	public function getWeightsForScore($modelver, $field){
		$select = $this->select();
		$select->where("namever LIKE ?",$modelver);
		$select->where("field LIKE ?",$field);
		return $this->fetchAll($select);	
	}
		
}
?>