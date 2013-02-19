<?

class Model_Creditscore_ScoreAttributes extends Zend_Db_Table {
	protected $_name="creditscore.score_attributes";
	
	public function insertCapSess($capno,$sesid)
	{
		$data = array(
			'capno'=>$capno,
			'session'=>$sesid,
		);
		$this->insert($data);	
	}
	
	public function getScorecard($id){
		$select = $this->select();
		$select->where("id = ?", $id);
		return $this->fetchRow($select)->toArray();
	}
	
	public function changeScore($id){
		$data = array('score' => -9999, 
					  'scoretag' => 'Outside Credit Scoring Model Range for Manual Evaluation');
		$this->update($data, 'id = '.$id);
	}
	
	public function setFileName($sesid, $file){
		$select = $this->select();
		$select->where('session LIKE ?', $sesid);
		$table = $this->fetchAll($select);
		$data = array('filename' => $file);
		foreach($table as $row){
			$this->update($data, 'id = '.$row->id);
		}	
	}
	
	public function getSession($file)
	{
		$select = $this->select();
		$select->where('filename LIKE ?', $file);
		return $this->fetchRow($select);
	}
	
	public function getData($account, $session)
	{
		$select = $this->select();
		$select->where('session LIKE ?', $session)
			   ->where('capno LIKE ?', $account)
			   ->order('id ASC');
		return $this->fetchAll($select);
	}
	
	public function getScorecards($capno, $blast, $prod)
	{
		$tempArr = array();
		$select = $this->select();
		if($capno && $blast){
			$bamodel = new Model_BorrowerAccount();
			$select1 = $bamodel->select();
			$select1->where("borrower_lname LIKE ?",strtoupper($blast)."%")
					->where("capno LIKE ?", $capno);
			$table1 = $bamodel->fetchAll($select1);	
			$arr = array();
			foreach($table1 as $row1){
				$arr[] = $row1->capno;
			}		
			if(count($arr) > 0){
				$select->where("capno IN (?)",$arr)
					   ->where("prod LIKE ?", $prod);		
				$table3 = $this->fetchAll($select);
				foreach($table3 as $row3){
					$tempArr[] = $row3;
				}						
			}			
		}else if($capno){
			$select->where('capno LIKE ?', $capno)
				   ->where("prod LIKE ?", $prod);
			$table2 = $this->fetchAll($select);
			foreach($table2 as $row2){
				$tempArr[] = $row2;
			}
		}else if($blast){
			$bamodel = new Model_BorrowerAccount();
			$select3 = $bamodel->select();
			$select3->where("borrower_lname LIKE ?",strtoupper($blast)."%");
			$table3 = $bamodel->fetchAll($select3);	
			$arr = array();
			foreach($table3 as $row3){
				$arr[] = $row3->capno;		
			}
			if(count($arr) > 0){
				$select->where("capno IN (?)",$arr)
					   ->where("prod LIKE ?", $prod);		
				$table3 = $this->fetchAll($select);
				foreach($table3 as $row3){
					$tempArr[] = $row3;
				}						
			}
		}
		return $tempArr;
	}
	
	public function checkScorecard($data){
		$select = $this->select();
		$select->where('capno LIKE ?',$data['capno'])
			   ->where('session LIKE ?', $data['session'])
			   ->where('model_used LIKE ?', $data['model_used']);
		$count = $this->fetchAll($select)->count();	
		return ($count == 0)? true : false;   	   
	}
	
	public function getScorecardID($data){
		$select = $this->select();
		$select->where('capno LIKE ?',$data['capno'])
			   ->where('session LIKE ?', $data['session'])
			   ->where('model_used LIKE ?', $data['model_used']);
		$row = $this->fetchRow($select);
		return $row->id;	
	}	

}
?>