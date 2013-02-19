<?

class Model_Creditscore_Audittrail extends Zend_Db_Table {
	protected $_name="creditscore.audittrail";
	
	public function weightAuditTrail($namever, $prod, $user, $id, $wfrom, $wto)
	{
		$csmodel = new Model_Creditscore_CSModel();
		$csrow = $csmodel->getModel($namever, $prod);				
		$fsmodel = new Model_Creditscore_Fieldsselected();
		$fsrow = $fsmodel->getRow($id);

		$data = array(
					'name'	=> $csrow->name,
					'version' => $csrow->version,
					'username' => $user,
					'field'	=> $fsrow->field,
					'attribute' => $fsrow->attribute,
					'wfrom' => $wfrom,
					'wto'	=> $wto,
					'datetime'	=> date("Y-m-d : H:i:s", time()) 
				);	
		$this->insert($data);			
	}

	public function cWeightAuditTrail($namever, $prod, $user, $id, $wfrom, $wto)
	{
		$csmodel = new Model_Creditscore_CSModel();
		$csrow = $csmodel->getModel($namever, $prod);				
		$mcmodel = new Model_Creditscore_ModelFieldsCategory();
		$mcrow = $mcmodel->getRow($id);

		$data = array(
					'name'	=> $csrow->name,
					'version' => $csrow->version,
					'username' => $user,
					'field'	=> $mcrow->namefield,
					'attribute' => $mcrow->attribute,
					'wfrom' => $wfrom,
					'wto'	=> $wto,
					'datetime'	=> date("Y-m-d : H:i:s", time()) 
				);	
		$this->insert($data);			
	}	
	
	public function nWeightAuditTrail($namever, $prod, $user, $id, $wfrom, $wto, $att)
	{
		$csmodel = new Model_Creditscore_CSModel();
		$csrow = $csmodel->getModel($namever, $prod);				
		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();
		$mnrow = $mnmodel->getRow($id);
		$data = array(
					'name'	=> $csrow->name,
					'version' => $csrow->version,
					'username' => $user,
					'field'	=> $mnrow->namefield,
					'attribute' => $att,
					'wfrom' => $wfrom,
					'wto'	=> $wto,
					'datetime'	=> date("Y-m-d : H:i:s", time()) 
				);	
		$this->insert($data);			
	}		
	
	public function rangeAuditTrail($namever, $prod, $user, $id, $wfrom, $wto)
	{
		$csmodel = new Model_Creditscore_CSModel();
		$csrow = $csmodel->getModel($namever, $prod);				
		$fsmodel = new Model_Creditscore_Fieldsselected();
		$fsrow = $fsmodel->getRow($id);

		$data = array(
					'name'	=> $csrow->name,
					'version' => $csrow->version,
					'username' => $user,
					'field'	=> $fsrow->field,
					'attribute' => $fsrow->attribute,
					'wfrom' => $wfrom,
					'wto'	=> $wto,
					'datetime'	=> date("Y-m-d : H:i:s", time()) 
				);	
		$this->insert($data);			
	}
		
	public function getAuditTrail($namever, $prod)
	{
		$csmodel = new Model_Creditscore_CSModel();
		$csrow = $csmodel->getModel($namever, $prod);		
		$select1 = $this->select();	
		$select1->where('name LIKE ?', $csrow->name);
		$table = $this->fetchAll($select1);
		$select2 = $this->select();
		foreach($table as $row){
			if ($row->field == "Renamed"){
				$select2->where('name LIKE ?', $row->wfrom);
			}
		}	
		$select2->where('name LIKE ?', $csrow->name)
			    ->order('id DESC');
		return $this->fetchAll($select2);
	}	
	
	public function fieldAuditTrail($namever, $prod, $user, $field, $cfrom, $cto)
	{
		$csmodel = new Model_Creditscore_CSModel();
		$csrow = $csmodel->getModel($namever, $prod);				
		$data = array(
					'name'	=> $csrow->name,
					'version' => $csrow->version,
					'username' => $user,
					'field'	=> $field,
					'attribute' => $cfrom,
					'wfrom' => '',
					'wto'	=> $cto,
					'datetime'	=> date("Y-m-d : H:i:s", time()) 
				);	
		$this->insert($data);			
	}
	
	public function updateAuditTrail()
	{
		$csmodel = new Model_Creditscore_CSModel();
		$csrow = $csmodel->getModel($namever, $prod);				

		$data = array(
					'name'	=> $csrow->name,
					'version' => $csrow->version,
					'username' => $user,
					'field'	=> $field,
					'attribute' => '',
					'wfrom' => $cfrom,
					'wto'	=> $cto,
					'datetime'	=> date("Y-m-d : H:i:s", time()) 
				);	
		$this->insert($data);			
	}
	
	public function changesAuditTrail($name, $ver, $user, $field, $attrib, $cfrom, $cto)
	{
		$data = array(
					'name'	=> $name,
					'version' => $ver,
					'username' => $user,
					'field'	=> $field,
					'attribute' => $attrib,
					'wfrom' => $cfrom,
					'wto'	=> $cto,
					'datetime'	=> date("Y-m-d : H:i:s", time()) 
				);	
		$this->insert($data);		
	}
	
	public function checkFromTo($name, $ver, $field, $attrib)
	{
		$select = $this->select();
		$select->where('name LIKE ?', $name)
			   ->where('version LIKE ?', $ver)
			   ->where('field LIKE ?', $field)
			   ->where('attribute LIKE ?', $attrib);
		$count = $this->fetchAll($select)->count();	
		return ($count > 0)? "false" : "true";
	}
	
	public function checkFieldAdded($name, $ver, $field, $attrib)
	{
		$select = $this->select();
		$select->where('name LIKE ?', $name)
			   ->where('version LIKE ?', $ver)
			   ->where('field LIKE ?', $field)
			   ->where('attribute LIKE ?', $attrib);
		$count = $this->fetchAll($select)->count();
		return ( $count > 0)? "false" : "true";
	}
	
	public function renameModel($pName, $nName){
		$nameField1 = explode(" ",$pName);
		$nameField2 = explode(" ",$nName);
		$select = $this->select();
		$select->where('name LIKE ?', $nameField1[0]);
		$table = $this->fetchAll($select);
		foreach($table as $row){
			$data = array('name'=>$nameField2[0]);
			$this->update($data, "id = ".$row->id);
		}		
	}	
	
}
?>