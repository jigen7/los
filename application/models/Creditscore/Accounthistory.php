<?

class Model_Creditscore_Accounthistory extends Zend_Db_Table {
	protected $_name="creditscore.accounthistory";
	
	public function setAccountHistory($name, $version, $process, $user, $remarks)
	{
		$date = date("m-d-Y H:i:s", time());
		$data = array(
					'name' => $name, 
					'version' => $version,
					'processcode' => $process,
					'username' => $user,
					'datetime'	=> $date,
					'remarks'	=> $remarks
				);	
		$this->insert($data);				
	}
	
	public function getAccountHistory($namever, $prod)
	{
		$csmodel = new Model_Creditscore_CSModel();
		$csrow = $csmodel->getModel($namever, $prod);		
		$select = $this->select();		
		$select->where('name LIKE ?', $csrow->name)
			   ->order('id DESC');
		return $this->fetchAll($select);
	}	
	
	public function updateAccountHistory($namever, $prod, $procode)
	{
		$csmodel = new Model_Creditscore_CSModel();
		$csrow = $csmodel->getModel($namever, $prod);
		$date = date("m-d-Y H:i:s", time());
		$data = array('datetime' => $date);
		$select = $this->select();
		$select->where('name LIKE ?',$csrow->name)
			   ->where('version LIKE ?',$csrow->version)
			   ->where('processcode LIKE ?', $procode);
		$count = $this->fetchAll($select)->count();
		if($count > 1){
			$i = 1; $id=0;
			$table = $this->fetchAll($select);
			foreach($table as $row){
				if($i == $count) $id = $row->id; 
				$i++;
			}
			$this->update($data, "id = ".$id);
		}else{
			$ahrow = $this->fetchRow($select);
			//echo "**".$namever.$prod.$procode.$ahrow->id."**";		
			$this->update($data, "id = ".$ahrow->id);
		}
	}

	public function checkAccountHistory($name, $prod, $procode)
	{
		$csmodel = new Model_Creditscore_CSModel();
		$csrow = $csmodel->getModel($name, $prod);
		$select = $this->select();
		$select->where('name LIKE ?', $csrow->name)
			   ->where('version LIKE ?', $csrow->version)
			   ->where('processcode LIKE ?', $procode);
		$count = $this->fetchAll($select)->count();
		return ($count > 0)? "true" : "false";
	}

	public function remarkAccountHistory($namever, $prod, $procode, $remark)
	{
		$csmodel = new Model_Creditscore_CSModel();
		$csrow = $csmodel->getModel($namever, $prod);
		$date = date("m-d-Y H:i:s", time());
		$data = array('datetime' => $date, 'remarks' => $remark);
		$select = $this->select();
		$select->where('name LIKE ?',$csrow->name)
			   ->where('version LIKE ?',$csrow->version)
			   ->where('processcode LIKE ?', $procode);
		$ahrow = $this->fetchRow($select);
		$this->update($data, "id = ".$ahrow->id);
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