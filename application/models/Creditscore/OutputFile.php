<?

class Model_Creditscore_OutputFile extends Zend_Db_Table {
	protected $_name="creditscore.outputfile";

	public function saveFile($sesid, $name, $user, $models, $accounts, $prod)
	{
		$data = array(
			'sesid' => $sesid,
			'filename' => $name,
			'user' => $user,
			'models' => $models,
			'accounts' => $accounts,
			'datetime' => date("m-d-Y h:i:sa"),
			'prod' => $prod
		);
		$this->insert($data);
	}
	
	public function checkFiles($file)
	{
		$select = $this->select();
		$select->where('filename LIKE ?', $file);
		$count = $this->fetchAll($select)->count();
		return ($count > 0)? true : false; 
	}
	
	public function getFiles($prod)
	{
		$select = $this->select();
		$select->where('prod LIKE ?', $prod)
			   ->order('filename ASC');
		return $this->fetchAll($select);
	}
	
	public function searchFile($file, $prod)
	{
		$select = $this->select();
		$select->where('filename LIKE ?', $file)
			   ->where('prod LIKE ?', $prod);
		return $this->fetchAll($select);
	}
	
	
}
?>