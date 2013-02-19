<?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */
class Model_CreditScoreAttr extends Zend_Db_Table {
	protected $_name="score_attributes";
	
	function updateScore($string){
			
		parse_str($string,$output);
		
		$scoretag = Zend_Controller_Action_HelperBroker::getStaticHelper('ScoreTag');
		// To get if P1,P2,F1,F2,F3
		$scoreR = $scoretag->direct($output['totalscore'], $output['empbus_status'],$output['capno']);
	
		//LOS Borrower Accounts Model to be change in CIN
		$borrower = new Model_BorrowerAccount();
		$principaldetail = $borrower->fetchRowModel($output['capno']);
		// END OF LOS
		if($principaldetail->deviation){
			$ifDev ='D';
		}			
		
		$where = "id =".$output['lastid'];
		$data = array(
		'score'=>$output['totalscore'],
		'score_attributes'=>$string,
		'scoretag'=>$output['empbus_status'].' '.$output['totalscore'].' '.$scoreR.' '.$ifDev,
		'modelused'=>$output['modelused'],
		'prod'=>$output['prod']
		);
		$this->update($data, $where);
	}

	//Alexis
	public function getScorecard($id){
		$select = $this->select();
		$select->where("id = ?", $id);
		return $this->fetchRow($select)->toArray();
	}
	
	//Alexis
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
	
}
?>