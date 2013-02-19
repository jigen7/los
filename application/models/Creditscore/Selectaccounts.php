<?

class Model_Creditscore_Selectaccounts extends Zend_Db_Table {
	protected $_name="creditscore.selectaccounts";
	
	public function selectaccounts($capno, $blastname, $busctr, $regpro, $dfrom, $dto, $status)
	{
		$select = $this->select();	
		$table = $this->fetchAll($select);
		return $table;			
	}
	
}
?>