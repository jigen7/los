<?
/**
 * Auto Loan System 
 * @author Paolo Marco Manarang <paolomanarang@gmail.com>
 * @package LOS
 */
class Model_BorrowerAccount extends Zend_Db_Table {
	protected $_name="borrower_accounts";
	
	
	public function fetchRowModel($capno){
		
		$table = new Model_BorrowerAccount();
		$select = $table->select();
		$select->where('capno like ?',$capno);	
		$detail = $table->fetchRow($select);
		return $detail;
		
	}
	
	public function getRelation($capno){
		
		$table = new Model_BorrowerAccount();
		$select = $table->select();
		$select->where('capno like ?',$capno);
		
		$detail = $table->fetchRow($select);
		
		if($table->isComaker($capno)){
		return "Co-Maker";
		}else {		
		return $detail->relation;
		}
	}
	
	public function getBorrowerName($capno){
		
	
	$select = $this->select();
	$select->where('capno like ?',$capno);
	$detail = $this->fetchRow($select);
	return $detail->borrower_fname.' '.$detail->borrower_mname.' '.$detail->borrower_lname;
		
	}
	
	public function getAcntStatus($capno){
		$table = new Model_BorrowerAccount();
		$select = $table->select();
		$select->where('capno like ?',$capno);
		
		$detail = $table->fetchRow($select);
		
		return $detail->account_status;
		
	}
	
	public function getEmpBus($capno){
	$select = $this->select();
	$select->where('capno like ?',$capno);
	$detail = $this->fetchRow($select);
	return $detail->empbus_status;
	}
		
	public function getMainCapno($capno){
		// For Co-Maker Pusposes if comaker_of field has a valid capno 
		//return the capno if not return the original passed capno
		// Use in Role Privileges View Helper to Deny Access
		$select = $this->select();
		$select->where('capno like ?',$capno);
		$detail = $this->fetchRow($select);
		
		if($detail->comaker_of){
			return $detail->comaker_of;			
		}else {
			return $capno;
		}
		 
	}
	
	public function getFieldValue($capno,$field){
		$select = $this->select();
		$select->where('capno like ?',$capno);
		$detail = $this->fetchRow($select);
		return $detail->$field;
	}
	
	public function getMainCapno2($capno){
		//return the original capno if its a comaker capno
		$select = $this->select();
		$select->where('capno like ?',$capno);
		$detail = $this->fetchRow($select);
		
		if($detail->comaker_of){
			return $detail->comaker_of;			
		}else {
			return $capno;
		}
		 
	}
	
	public function isComaker($capno){
		// isComaker
		// Tells if the borrower is a comaker account
		// Use in profile dropdown to show while n comaker account
		$select = $this->select();
		$select->where('capno like ?',$capno);
		$detail = $this->fetchRow($select);
		
		if($detail->comaker_of){
			return $detail->comaker_of;			
		}else {
			return '';
		}
		 
	}
	
	public function getComaker($capno){
		
		// For Co-Maker Pusposes returns the capno of the comaker base on the pass capno 
		// Use in profile dropdown to show the comaker account
		// Returns
		$select = $this->select();
		$select->where('comaker_of like ?',$capno);
		$detail = $this->fetchRow($select);
		
		if($detail->capno){
			return $detail->capno;			
		}else {
			return '';
		}
		 
	}
	
	function countSpouse($capno){
		
		$sql = $this->select()
	    ->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where('relation LIKE ?','Spouse');
		$count = $this->fetchAll($sql)->count();
		return $count;
	
	}
	
	function countCoborrower($capno){
		
		$sql = $this->select()
	    ->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where('relation LIKE ?','Coborrower');
		$count = $this->fetchAll($sql)->count();
		return $count;
	
	}
	
	function getPrincipalCapno($capno){
		//use in determining the principal 
		//capno its a coborrower or a spouse or a comaker 
		//use in delete 
		//04-12-2010
		
		$select = $this->select();
	    $select->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where('relation LIKE ?','Principal');
 		$detail = $this->fetchRow($select);
		
		return $detail->capno;		
		
	}
	
	function isPrincipal($capno){
		$select = $this->select();
	    $select->where('capno LIKE ?',$capno);
 		$detail = $this->fetchRow($select);
		
		if($detail->relation == 'Principal' && $detail->comaker_of == ''){
			return 'true';
		}else {
			return '';
		}
		
		
	}
	
	function getEmpBusStatus($capno){
		
		$select = $this->select();
		$select->where('capno LIKE ?',$capno);
 		$detail = $this->fetchRow($select);
		
		return $detail->empbus_status;
	}
	
	function chkApproved($capno){
		
		$select = $this->select();
		$select->where('capno LIKE ?',$capno);
 		$detail = $this->fetchRow($select);
		
		if($detail->account_status == 'Approved' || 
		$detail->account_status == 'CO - Ap' ||
		$detail->account_status == 'CSH - Ap' ||
		$detail->account_status == 'CMGH - Ap' ||
		$detail->account_status == 'PRES - Ap' ||
		$detail->account_status == 'SUBCRE - Ap' || 
		$detail->account_status == 'CRECOM - Ap' ||
		$detail->account_status == 'EXECOM - Ap' ||
		$detail->account_status == 'BOARD - Ap'						
		) {
			return true;		
		}
		else {
			return false;
		}
		
		
			
	}
	
	function search($capno,$name,$status,$datefrom,$dateto,$appType){
		$select = $this->select();
		$select->where('relation like ?','Principal');
		if($capno){
			$select->where('capno like ?',$capno."%");
		}
		if($status){
			$select->where('account_status LIKE ?',$status.'%');				
		}
		// EDIT Alexis
		if($name){
			$select->where('borrower_lname LIKE ?',strtoupper($name).'%');
		}
		if($appType){
			$select->where('application_type LIKE ?', $appType);
		}		
		if($datefrom && $dateto){
			$datefrom = date("Y-m-d", strtotime($datefrom));
			$dateto = date("Y-m-d", strtotime($dateto));
			$datefrom = $datefrom." 08:30:00";
			$dateto = $dateto." 18:30:00";		
			if($dateto >= $datefrom){
				$select->where("application_date BETWEEN '$datefrom' AND '$dateto'");
				$select->order('application_date ASC');
			}
		}
		$select->order("id DESC");
		return $select;
		//return $this->fetchAll($select);
	}
	
	public function deviationChk($capno){
		
		$select = $this->select();
		$select->where("capno like ?",$capno);
		$detail = $this->fetchRow($select);
		
		$scoretag = $detail->score_tag;
		if(strpos($scoretag,'Outside')!== false){
			// True
			
			
		}else {
			//False
			if(strpos($scoretag,'D')!== false){
			//if scoretag already contains D as for deviation	
				
			}else{
			$scoretag = $scoretag."D";
			}
			
		}
		$autoroute = Zend_Controller_Action_HelperBroker::getStaticHelper('AutoRoute');

		$route = $autoroute->direct($capno,$scoretag);		

		$maindata = array(
		'deviation'=>true,
		'score_tag'=>$scoretag,
		'routetag'=>$route
		);
		$where = "capno like '$capno'";
		
		$this->update($maindata,$where);
		
	}
	
	//alexis
	public function getAccount($capno){
		$select = $this->select();
		$select->where('capno LIKE ?', $capno);
		return $this->fetchRow($select);
	}
	
	public function chkIfRecon($capno){
	$detail = $this->fetchRowModel($capno);
	
	if($detail->account_status2 == 'RECON'){
		return true;	
	}
	else if ($detail->account_status2 == 'Recon'){
		return true;
	}
	else{
		return false;	
	}
		
	}
	
	public function chkIfReprocess($capno){
	$detail = $this->fetchRowModel($capno);
	
	if($detail->account_status2 == 'REPROCESS'){
		return true;	
	}
	else if ($detail->account_status2 == 'Reprocess'){
		return true;
	}
	else{
		return false;	
	}
		
	}
	
}
?>