<?

class Model_Creditscore_RegularPromoModel extends Zend_Db_Table {
	protected $_name="creditscore.regular_promo_model";
	
	public function addRegularPromo($namever, $regpro, $code)
	{	
		$data = array('namever'=>$namever, 'regpro'=>$regpro, 'code'=>$code);
		$this->insert($data);			
	}
	
	public function checkRegularPromo($namever, $busctr)
	{
		$select = $this->select();
		$select->where('namever LIKE ?', $namever)
		 	   ->where('regpro LIKE ?', $busctr);
		$count = $this->fetchAll($select)->count();
		return($count == 0)? true : false;
	}
	
	public function addedRegularPromo($namever)
	{
		$select = $this->select();
		$select->where('namever LIKE ?', $namever);
		return $this->fetchAll($select);
	}
	
	public function deleteRegularPromo($id)
	{
		$this->delete('id = '.$id);
	}
	
	public function copyRegularPromo($data)
	{
		$this->insert($data);
	}
	
	public function getRegularPromo($id)
	{
		$select = $this->select();
		$select->where('id = '.$id);
		return $this->fetchRow($select);
	}	
	
	public function getModelsWithRPs($regpro){
		$select = $this->select();
		$select->where('code LIKE ?',$regpro);
		return $this->fetchAll($select);	
	}
	
	public function getRegularPromos($regpro)
	{
		$select = $this->select();
		$select->where('regpro LIKE ?', $regpro);
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