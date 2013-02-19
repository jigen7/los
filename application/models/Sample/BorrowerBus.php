<?

class Model_Sample_BorrowerBus extends Zend_Db_Table {
	protected $_name="sampledata.borrower_bus";
	
	public function compareBus($id, $field, $attrib)
	{
		$select = $this->select();
		$select->where('id = '.$id)
			   ->where($field." LIKE ? ",$attrib);
		return $this->fetchAll($select)->count();
	}	
	
	public function hasFields($fields)
	{
		if("bus_name" == $fields) return true;
		if("bus_nat" == $fields) return true;
		if("bus_yrs" == $fields) return true;
		if("bus_income" == $fields) return true;
		if("capno" == $fields) return true;
		return false;
	}	

}
?>