<?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */
class Model_AccountHistory extends Zend_Db_Table {
	protected $_name="borrower_accounts_history";
	
	
	function getLastStatus($capno){
	$user = login_user_role();
	$history = new Model_AccountHistory();
	$select = $history->select();
	$select->where('capno like ?',$capno);	
	$select->order('id DESC');
	$historyDetail = $history->fetchRow($select);					
	return $historyDetail->status;
	}
	
	function getLastRemarks($capno,$status){
	$user = login_user_role();
	$history = new Model_AccountHistory();
	$select = $history->select();
	$select->where('capno like ?',$capno);	
	$select->order('id DESC');
	$historyDetail = $history->fetchRow($select);					
	return $historyDetail->remarks;
	}
	
	function getLastDatebyStatus($capno,$status){
	$user = login_user_role();
	$history = new Model_AccountHistory();
	$select = $history->select();
	$select->where('capno like ?',$capno);	
	$select->order('id DESC');
	$historyDetail = $history->fetchRow($select);					
	return $historyDetail->date;
	}
	
	function getLastUserbyStatus($capno,$status){
	$user = login_user_role();
	$history = new Model_AccountHistory();
	$select = $history->select();
	$select->where('capno like ?',$capno);	
	$select->order('id DESC');
	$historyDetail = $history->fetchRow($select);					
	return $historyDetail->by;
	}
	
	public function getLastMAS($capno){
		// gets the last MA - S to teh accoutn history
		$select = $this->select();
		$select->where("capno like ?",$capno);
		$select->where("status like ?",'MA - S')->order('id ASC');
		$detail = $this->fetchRow($select);
		return $detail->date;
		
		}
		
	public function countCaStatus($capno){
		// return number of how many CA status found in the account history 
		// use in MA - Reject 
		
		$select = $this->select();
		$select->where("capno like ?",$capno);
		$select->where("status like ?",'CA%')->order('id ASC');
		$count = $this->fetchAll($select)->count();
		//echo $count;
		return $count;
		
	}
	
	public function chkCaAnD($capno){
		// return the number of the said status		
		$select = $this->select();
		$select->where("capno like ?",$capno);
		$select->where("status like ?",'CA - AnD')->order('id ASC');
		$count = $this->fetchAll($select)->count();
		return $count;
		
	}
	
	public function chkCaRtMa($capno){
		// return the number of the said status	CA return to Ma	
		$select = $this->select();
		$select->where("capno like ?",$capno);
		$select->where("status like ?",'CA - RTMA')->order('id ASC');
		$count = $this->fetchAll($select)->count();
		return $count;
		
	}
	
	public function chkCoRtCa($capno){
		// return the number of the said status	CA return to Ma	
		$select = $this->select();
		$select->where("capno like ?",$capno);
		$select->where("status like ?",'CO - RTCA')->order('id ASC');
		$count = $this->fetchAll($select)->count();
		return $count;
		
	}
	
	public function retCaRtMa($capno){
		// return the number id of the said statusCA return to Ma	
		$select = $this->select();
		$select->where("capno like ?",$capno);
		$select->where("status like ?",'CA - RTMA')->order('id DESC');
		$detail = $this->fetchRow($select);
		
		if($detail){
			return $detail->id;
					
		}
		else {
			return 0;	
		}
	}
	
	public function retCoRtCa($capno){
		// return the number id of the said statusCA return to Ma	
		$select = $this->select();
		$select->where("capno like ?",$capno);
		$select->where("status like ?",'CO - RTCA')->order('id DESC');
		$detail = $this->fetchRow($select);
		
		if($detail){
			return $detail->id;		
		}
		else {			
			return 0;
		}
	}
	
	public function getApproveDate($capno){
		// use in RoleAccess View Helper for Recon pusposes
		$account = new Model_BorrowerAccount();
		
		if($account->chkApproved($capno)){
			
			$select = $this->select();
			$select->where("capno like ?",$capno);
			$select->where("status = 'Approved' OR 	status = 'CO - Ap'
			OR status = 'CSH - Ap' OR status = 'CMGH - Ap' OR status = 'PRES - Ap' 
			OR status = 'SUBCRE - Ap' OR status = 'CRECOM - Ap' 
			OR status = 'EXECOM - Ap' OR status = 'BOARD - Ap'")->order("id DESC");
			$detail = $this->fetchRow($select);
			
			if($detail){
			return date('Y-m-d',strtotime($detail->date));
			} else {
			return "2020-01-01";	
			}
		}else {
			return "2020-01-01";
		}
		
	}
	
	public function chkCVCI($capno){
		//for irene's checking
		$select = $this->select();
		$select->where("capno like ?",$capno);
		$select->where("status = 'CA - ReR' OR status = 'CA - ReAp' OR status = 'CA - P'");
		return $this->fetchAll($select)->count();
	
		
	}
	
	public function chknotRecommended($capno){
		// for sir many's report
		$select = $this->select();
		$select->where("capno like ?",$capno);
		$select->where("status = 'CSH - ReR' OR status = 'CSH - R' OR status = 'CSH - P' OR status = 'CSH - RP'");
		$notrecommend = $this->fetchAll($select)->count();
		return $notrecommend;
	}
	
	public function chkRecommended($capno){
		$select = $this->select();
		$select->where("capno like ?",$capno);
		$select->where("status = 'CSH - ReAp' OR status = 'CSH - Ap'");
		$recommend = $this->fetchAll($select)->count();
		return $recommend;
	}
	
	public function ifRecommend($capno){
		$countX = $this->chkRecommended($capno);
		$countY = $this->chknotRecommended($capno);
		
		if($countX > $countY){
			return "Recommended by CSD";
		}
		else {
			return "Not Recommended by CSD";
		}
		

	}
	
    }
?>