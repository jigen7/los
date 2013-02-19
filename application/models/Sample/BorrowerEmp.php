<?

class Model_Sample_BorrowerEmp extends Zend_Db_Table {
	protected $_name="sampledata.borrower_emp";

	public function compareEmp($id, $field, $attrib)
	{
		$select = $this->select();
		$select->where('id = '.$id)
			   ->where($field." LIKE ? ",$attrib);
		return $this->fetchAll($select)->count();
	}	
	
	public function hasFields($fields)
	{
		if("emp_status" == $fields) return true;
		if("emp_yrs" == $fields) return true;
		if("emp_name" == $fields) return true;
		if("capno" == $fields) return true;
		return false;
	}

}
?>