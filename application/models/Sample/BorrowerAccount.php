<?

class Model_Sample_BorrowerAccount extends Zend_Db_Table {
	protected $_name="sampledata.borrower_accounts";
	
	public function getAllAccounts()
	{
		$select = $this->select();
		return $this->fetchAll($select);
	}
	
	public function getAccount($id)
	{
		$select = $this->select();
		$select->where('id = '.$id);
		return $this->fetchRow($select);
	}
	
	public function compareAccount($id, $field, $attrib)
	{
		$select = $this->select();
		$select->where('id = '.$id)
			   ->where($field." LIKE ? ",$attrib);
		return $this->fetchAll($select)->count();
	}
	
	public function hasFields($fields)
	{
		if("borrower_name" == $fields) return true;
		if("lengthstay" == $fields) return true;
		if("res_type" == $fields) return true;
		if("loanterm" == $fields) return true;
		if("monthly_amortization" == $fields) return true;
		if("capno" == $fields) return true;
		if("combine_income" == $fields) return true;
		if("capbasis" == $fields) return true;
		return false;
	}

}
?>