<?

class Model_Creditscore_BusinessCentersModel extends Zend_Db_Table {
	protected $_name="creditscore.business_centers_model";
	
	public function addBusinessCenter($namever, $busctr, $code)
	{	
		$data = array('namever'=>$namever, 'busctr'=>$busctr, 'code'=>$code);
		$this->insert($data);			
	}
	
	public function checkBusinessCenter($namever, $busctr)
	{
		$select = $this->select();
		$select->where('namever LIKE ?', $namever)
		 	   ->where('busctr LIKE ?', $busctr);
		$count = $this->fetchAll($select)->count();
		return($count == 0)? true : false;
	}
	
	public function addedBusinessCenter($namever)
	{
		$select = $this->select();
		$select->where('namever LIKE ?', $namever);
		return $this->fetchAll($select);
	}
	
	public function getModelsWithBCs($busctr){
		$select = $this->select();
		$select->where('busctr LIKE ?', $busctr);
		return $this->fetchAll($select);	
	}
	
	public function deleteBusinessCenter($id)
	{
		$this->delete('id = '.$id);
	}
	
	public function copyBusinessCenters($data)
	{
		$this->insert($data);
	}
	
	public function getBusinessCenter($id)
	{
		$select = $this->select();
		$select->where('id = '.$id);
		return $this->fetchRow($select);
	}
	
	public function getBusinessCenters($busctr)
	{
		$select = $this->select();
		$select->where('busctr LIKE ?', $busctr);
		return $this->fetchAll($select);	
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
	
}
?>