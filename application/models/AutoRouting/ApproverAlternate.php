<?

class Model_AutoRouting_ApproverAlternate extends Zend_Db_Table {
	protected $_name="autorouting.approver_alternate";
	
	public function getAlternates($username){
		$select = $this->select();
		$select->where('username LIKE ?', $username);
		return $this->fetchRow($select);
	}		
	
}

?>