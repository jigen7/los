<?

class Model_Creditscore_OCSHistory extends Zend_Db_Table {
	protected $_name="creditscore.ocshistory";
	
	public function setOCS($sesid, $modelver, $capno, $rule, $rowId, $scoreId){
		$data = array(
					'sesid' => $sesid,
					'modelver' => $modelver,
					'capno' => $capno,
					'rule' => $rule,
					'row' => $rowId, 
					'scorerow' => $scoreId
				);
		$this->insert($data);
		
		$samodel = new Model_Creditscore_ScoreAttributes();
		$samodel->changeScore($scoreId);
	}
	
	public function getOCS($id){
		$select = $this->select();
		$select->where('scorerow LIKE ?', $id);
		return $this->fetchAll($select);
	}

	

}
?>