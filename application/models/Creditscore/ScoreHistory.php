<?

class Model_Creditscore_ScoreHistory extends Zend_Db_Table {
	protected $_name="creditscore.scorehistory";
	
	public function setAccount($accid, $sesid)
	{
		$select = $this->select();
		$data = array('account' => $accid, 'session' => $sesid);
		$this->insert($data);
	}
	
	public function setModels($models, $sesid)
	{
		$select = $this->select();
		$select->where('session LIKE ?', $sesid);
		$ids = $this->fetchAll($select);
		$data = array('model_use' => $models);
		foreach($ids as $id)
		{
			$this->update($data, 'id = '.$id->id);					
		}
	}
	
	public function getModels($sesid)
	{
		$select = $this->select();
		$select->where('session LIKE ?',$sesid);
		return $this->fetchRow($select);		
	}
	
	public function getAccounts($sesid)
	{
		$select = $this->select();
		$select->where('session LIKE ?',$sesid);
		return $this->fetchAll($select);
	}
	
	public function hasAccount($capno, $sesid)
	{
		$select = $this->select();
		$select->where('account LIKE ?', $capno)
			   ->where('session LIKE ?', $sesid);
		$count = $this->fetchAll($select)->count();
		return ($count == 0)? true : false;
	}
	
	public function deleteAccount($capno, $sesid)
	{
		$select = $this->select();
		$select->where('account LIKE ?', $capno)
			   ->where('session LIKE ?', $sesid);
		$table = $this->fetchAll($select);
		foreach($table as $row){
			$this->delete('id = '.$row->id);
		}
	}

}
?>