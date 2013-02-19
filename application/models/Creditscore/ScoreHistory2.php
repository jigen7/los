<?

class Model_Creditscore_ScoreHistory2 extends Zend_Db_Table {
	protected $_name="creditscore.scorehistory2";
	
	public function addAccount($accid, $sesid)
	{
		$select = $this->select();
		$data = array('capno' => $accid, 'session' => $sesid);
		$this->insert($data);
	}
	
	public function checkAccount($capno, $sesid)
	{
		$select = $this->select();
		$select->where('capno LIKE ?', $capno)
			   ->where('session LIKE ?', trim($sesid));
		$count = $this->fetchAll($select)->count();
		return ($count == 0)? true : false;
	}
	
	public function getAccounts($sesid)
	{
		$select = $this->select();
		$select->where('session LIKE ?',$sesid);
		return $this->fetchAll($select);
	}
	
	public function hasAccounts($sesid)
	{
		$select = $this->select();
		$select->where('session LIKE ?', $sesid);
		$count = $this->fetchAll($select)->count();
		return ($count > 0)? true : false; 
	}
	
	public function deleteAccount($capno, $sesid)
	{
		$select = $this->select();
		$select->where('capno LIKE ?', $capno)
			   ->where('session LIKE ?', $sesid);
		$row = $this->fetchRow($select);
		$this->delete('id = '.$row->id);
	}
	
	public function deleteAccounts($sesid)
	{
		$select = $this->select();
		$select->where('session LIKE ?', $sesid);
		$table = $this->fetchAll($select);
		foreach($table as $row){
			echo $row->id;
			$this->delete('id = '.$row->id);
		}	
	
	}

}
?>