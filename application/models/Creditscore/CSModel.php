<?

class Model_Creditscore_CSModel extends Zend_Db_Table {
	protected $_name="creditscore.model";
	
	public function addModel($modelName, $username, $dateFrom, $dateTo, $product){
		$data = array(
			  'namever'	=>	strtolower($modelName).' 1',
			  'name'	=>	strtolower($modelName),
			  'username'=>	strtolower($username),
			  'vpfrom'	=>	date("Y-m-d", strtotime($dateFrom)),
			  'vpto'	=>	date("Y-m-d", strtotime($dateTo)),
//			  'busctr'	=>	$businessCenter,
//			  'regpro'	=>	$regularPromo,
			  'status'	=>	'EDIT',
			  'version'	=>	'1',
			  'remarks'	=>	"null",
			  'product'	=>	$product
		);
		$this->insert($data);
	}

	public function checkNameVer($modelName, $prod)
	{  	
		$select = $this->select();
		$select->where('name LIKE ?',strtolower($modelName))
		       ->where('product LIKE ?',$prod);
 		$count = $this->fetchAll($select)->count();		
		return ($count > 0 || $modelName == '')? true : false;
	}
	
	public function checkDate($dateFrom, $dateTo)
	{
		$dToday = strtotime(date("Y-m-d"));
		if($dToday > strtotime($dateFrom)) return true;
		if($dateFrom == '' || $dateTo == '') return true;
		$dFrom = strtotime($dateFrom);	
		$dTo = strtotime($dateTo);
		return ($dFrom >= $dTo)? true : false;	
	}
	
	public function checkIfUpdated($name, $prod)
	{
		$select = $this->select();
		$select->where('namever LIKE ?',strtolower($name))
		       ->where('product LIKE ?',$prod)
			   ->where('status LIKE ?', 'CURWUPD');
 		$count = $this->fetchAll($select)->count();			
		return ($count > 0)? true : false;		
	}
	
	public function isDateConflict($vpFrom, $vpTo, $dFrom)
	{
		$dDate = date_parse(date("Y-m-d",strtotime($vpTo)));		
		$m = $dDate['month'];
		$d = $dDate['day'];
		$y = $dDate['year'];

		if($m == 1 || $m == 3 || $m == 5 || $m == 7 || $m == 8 || $m == 10 || $m == 12){
			$d = ($d == 31)? 1 : ++$d;
			if($d == 1){
				if($m == 12){$m = 1; $y = $y + 1;
				}else {++$m;}
			}
		}
		else if($m == 2){
			$x = $y;
			while($x > 0){$x = $x - 4;}
			if($x == 0){ $d = ($d == 29)? 1 : ++$d;			
			}else{ $d = ($d == 28)? 1 : ++$d;}
			if($d == 1){++$m;}		
		}else{
			$d = ($d == 30)? 1 : ++$d;
			if($d == 1){++$m;}		
		}
		$cDate = $y."-".$m."-".$d;	
		$cDate = date("Y-m-d", strtotime($cDate));		
		if(strtotime($cDate) < strtotime($dFrom)) return true;
		
		$vpFrom = strtotime($vpFrom);
		$dFrom = strtotime($dFrom);
		return ($vpFrom >= $dFrom)? true : false;	
	}
	
	public function setUpdateDate($id, $date)
	{
		$dDate = date_parse(date("Y-m-d",strtotime($date)));			
		$m = $dDate['month'];
		$d = $dDate['day'];
		$y = $dDate['year'];
		if($m != 1 || $m != 3 || $m != 5 || $m != 7 || $m != 8 || $m != 10 || $m != 12){
			$d = ($d == 1)? 31 : $d--;
			if($d == 31) --$m;
		}else if($m == 3){
			$x = $y;
			while($x > 0){$x = $x - 4;}
			if($x == 0){ $d = ($d == 1)? 29 : $d--;			
			}else{ $d = ($d == 1)? 28 : $d--;}
			if($d == 28 || $d == 29){--$m;}		
		}else{
			$d = ($d == 1)? 30 : $d--;
			if($d == 30){
				if($m == 1){$m = 12; $y = $y - 1;
				}else{ --$m;}	
			}
		}
		$cDate = $y."-".$m."-".$d;		
		$cDate = date("Y-m-d", strtotime($cDate));		
		$data = array('vpto' => $cDate);
		$this->update($data, "id = ".$id);
	}	
	
	public function setUpdateStatus($id, $status)
	{
		$data = array('status' => $status);
		$this->update($data, 'id = '.$id);
	}
	
	public function getModel($namever, $prod)
	{
		$select = $this->select();
		$select->where('namever LIKE ?',strtolower($namever))
			   ->where('product LIKE ?',$prod);
		return $this->fetchRow($select);		
	}	

	public function getProdType($namever)
	{
		$select = $this->select();
		$select->where('namever LIKE ?',$namever);
		$row = $this->fetchRow($select);
		return $row->product;			
	}

	public function getCompareModel($id)
	{
		$select = $this->select();
		$select->where('id = '.$id);
		return $this->fetchRow($select);		
	}	
	
	public function getModelById($id)
	{
		$select = $this->select();
		$select->where('id = '.(int)$id);
		$row = $this->fetchRow($select);	
		return $row->namever;	
	}		

	public function getCurrentModel($namever, $prod)
	{
		$select = $this->select();
		$select->where('status LIKE ?','CURRENT')
			   ->where('product LIKE ?',$prod)
			   ->where('name LIKE ?',strtolower($namever)."%")
			   ->orWhere('namever LIKE ?',strtolower($namever));
		return $this->fetchAll($select);		
	}
	
	public function getUpdateModel($namever, $prod)
	{
		$select = $this->select();
		$select->where('namever LIKE ?',strtolower($namever))
		       ->where('product LIKE ?',$prod);
		$row = $this->fetchRow($select);	
		return $row->toArray();
	}		

	public function getUpdateModels($prod)
	{
		$select = $this->select();
		$select->where('status LIKE ?','CURRENT')
		       ->where('product LIKE ?',$prod);
		return $this->fetchAll($select);	
	}		
	
	public function setUpdateModel($name, $dfrom, $dto, $ver, $id, $prod)
	{
		$data = array(
					'namever'	=>	$name." ".++$ver,
					'name'		=>	$name,
					'username'	=>	'alexis',
					'vpfrom'	=>	$dfrom,
					'vpto'		=>	$dto,
//					'busctr'	=>	$busctr,
//					'regpro'	=>	$regpro,
					'status'	=>	'EDIT',
					'version'	=>	$ver,
					'remarks'	=>	'null',
					'product'	=>	$prod
				);
		$this->insert($data, 'id = '.$id);		
	}

	public function setEditModel($id, $name, $user, $dfrom, $dto, $prod)
	{
		$data = array(
					'namever'	=>	$name." 1",
					'name'		=>	$name,
					'username'	=>	$user,
					'vpfrom'	=>	$dfrom,
					'vpto'		=>	$dto,
//					'busctr'	=>	$busctr,
//					'regpro'	=>	$regpro,
					'status'	=>	'EDIT',
					'version'	=>	1,
					'remarks'	=>	'null',
					'product'	=>	$prod
				);
		$this->update($data, 'id = '.$id);		
	}
	
	public function setRTSModel($id, $namever, $user, $dfrom, $dto, $prod)
	{
		$nameField = explode(" ",$namever);
		$data = array(
					'namever'	=>	$namever,
					'name'		=>	$nameField[0],
					'username'	=>	$user,
					'vpfrom'	=>	$dfrom,
					'vpto'		=>	$dto,
//					'busctr'	=>	$busctr,
//					'regpro'	=>	$regpro,
					'status'	=>	'EDIT',
					'version'	=>	$nameField[1],
					'remarks'	=>	'null',
					'product'	=>	$prod
				);
		$this->update($data, 'id = '.$id);		
	}	
	
	public function setCURWUPD($id)
	{
		$data = array( 'status'	=>	'CURWUPD');
		$this->update($data,'id = '.$id);
	}	
	
	public function setEdit($namever)
	{	
		$select = $this->select();
		$select->where('namever LIKE ?', strtolower($namever));
		$row = $this->fetchRow($select);
		$data = array('status'=>'EDIT');
		$this->update($data,'id = '.$row->id);
	}	
	
	public function setApprove($name, $prod, $remark)
	{
		$select = $this->select();
		$select->where('namever LIKE ?', strtolower($name));
		$row = $this->fetchRow($select);
		$data = array('status'=>'APPROVED','remarks'=>$remark);
		$this->update($data,'id = '.$row->id);		
	}	

	public function getApprovalModels($prod)
	{
		$select = $this->select();
		$select->where('status LIKE ?','APPROVAL')
			   ->where('product LIKE ?',$prod);
		return $this->fetchAll($select);	
	}	
	
	public function setApprovalModel($namever)
	{	
		$select = $this->select();
		$select->where('namever LIKE ?', strtolower($namever));
		$row = $this->fetchRow($select);
		$data = array('status'=>'APPROVAL');
		$this->update($data,'id = '.$row->id);
	}		
	
	public function searchApprovalModel($namever, $prod)
	{
		$select = $this->select();
		$select->where('product LIKE ?',$prod)
			   ->where('status LIKE ?', 'APPROVAL')
			   ->where('namever LIKE ?', strtolower($namever)."%");
		return $this->fetchAll($select);
	}	

	public function getViewModels($prod)
	{
		$select = $this->select();
		$select->where('product LIKE ?',$prod)
			   ->order('status ASC')
			   ->order('namever ASC');
		return $this->fetchAll($select);		
	}
	
	public function searchViewModel($name, $status, $prod)
	{		
		$select = $this->select();	
		if($name){$select->where('namever LIKE ?',strtolower($name)."%");}
		if($status){$select->where('status LIKE ?',$status);}
		$select->where('product LIKE ?', $prod);
		return $this->fetchAll($select);
	}
	
	public function getPendingModels($prod)
	{
		$status = array('EDIT','APPROVAL','RTS','APPROVED');
		$select = $this->select();
		$select->where('product LIKE ?',$prod)
			   ->where('status IN (?)',$status)
			   ->order('status ASC')
			   ->order('name ASC')
			   ->order('id ASC');
		return $this->fetchAll($select);			
	}	
	
	public function searchPendingModel($name, $status, $prod)
	{		
		$count = 0;	
		$select = $this->select();	
		
		if($status == "FOR APPROVAL") $status = 'APPROVAL';
		if($status == "FOR EDITING") $status = 'EDIT';
		$arrStatus = array('EDIT','APPROVAL','RTS','APPROVED');
		if($name){
			$select->where('status IN (?)',$arrStatus)
				   ->where('namever LIKE ?',strtolower($name)."%");
			$count++;		
		}
		if($status == "APPROVED" || $status == "EDIT" || $status == "RTS" || $status == "APPROVAL"){
			$select->where('status LIKE ?',$status);	
			$count++;
		}
		if($count == 0){$select->where('status IN (?)', $arrStatus)->order('status ASC');}
		$select->where('product LIKE ?', $prod);
		return $this->fetchAll($select);
	}	

	
	public function selectModels($name, $status, $regpro, $busctr, $prod)
	{
		$select = $this->select();
		if($name){$select->where('namever LIKE ?',strtolower($name)."%");}
		if($status){$select->where('status LIKE ?',$status);}
		if($busctr){
			$bcmodel = new Model_Creditscore_BusinessCentersModel();
			$bctable = $bcmodel->getBusinessCenters($busctr);
			$arr = array();
			foreach($bctable as $bcrow){
				$arr[] = $bcrow->namever;
			}
			$select->where('namever IN (?)',$arr);
		}
		if($regpro){
			$rpmodel = new Model_Creditscore_RegularPromoModel();
			$rptable = $rpmodel->getRegularPromos($regpro);
			$arr = array();
			foreach($rptable as $rprow){
				$arr[] = $rprow->namever;
			}
			$select->where('namever IN (?)',$arr);			
		}
		$select->where('product LIKE ?', $prod);
		$select->order('status ASC')->order('namever ASC');
		return $this->fetchAll($select);
	}
	
	public function viewSelectModels($prod){
		$select = $this->select();
		$select->where('product LIKE ?', $prod)
			   ->order('status ASC')
		       ->order('namever ASC');	
		return $this->fetchAll($select);
	}

	public function updateRemark($name, $prod, $remark)
	{
		$select = $this->select();
		$select->where('namever LIKE ?', strtolower($name));
		$row = $this->fetchRow($select);
		$data = array('status'=>'RTS','remarks'=>$remark);
		$this->update($data,'id = '.$row->id);
	}

	public function getModelSelection($namever, $prod){
		$status = array('CURRENT', 'CURWUPD');
		$select = $this->select();
		$select->where('namever LIKE ?', $namever)
			   ->where('product LIKE ?', $prod)
			   ->where('status IN (?)', $status);
		return $this->fetchRow($select);	
	}
	

/*	public function getViewModels($prod)
	{
		$status = array('CURRENT','CURWUPD','APPROVED','EXPIRED');
		$select = $this->select();
		$select->where('product LIKE ?',$prod)
		       ->where('status IN (?)',$status);
		return $this->fetchAll($select);		
	}
	
	public function searchViewModel($name, $status)
	{		
		$count = 0;	
		$select = $this->select();	
		$arrStatus = array('APPROVED','CURRENT','CURWUPD','EXPIRED');

		if($name){
			$select->where('status IN (?)', $arrStatus)
				   ->where('name LIKE ?',$name)
				   ->orWhere('namever LIKE ?',$name);
			$count++;		
		}
		if($status == "APPROVED" || $status == "CURRENT" || $status == "CURWUPD" || $status == "EXPIRED"){
			$select->where('status LIKE ?',$status);	
			$count++;
		}
		if($count == 0){
			$select->where('status LIKE ?', 'APPROVED');
			$select->where('status LIKE ?', 'CURRENT');
			$select->where('status LIKE ?', 'CURWUPD');
			$select->where('status LIKE ?', 'EXPIRED');	
		}
		
		$table = $this->fetchAll($select);
		return $table;
	}
*/	
/*	
	public function getViewOutputModel($models)
	{
		$select = $this->select();
		$select->where('id IN (?)',$models)
			   ->order('namever ASC');
		return $this->fetchAll($select);	
	}
	
/*	
	public function getId($modelName)
	{
		$select = $this->select();
		$select->where('name LIKE ?', strtolower($modelName))
			   ->where('status LIKE?', 'EDIT');
	    $row = $this->fetchRow($select);
		return $row->id;
	}
	
	
	public function checkForEdit($modelName, $prod)
	{
		$select = $this->select();
		$select->where('name LIKE ?',strtolower($modelName))
		       ->where('product LIKE ?',$prod)
			   ->where('status LIKE ?', 'EDIT');
 		$count = $this->fetchAll($select)->count();			
		return ($count > 0 || $modelName == '')? true : false;
	}	

	public function getFields()
	{
		$data = array(
			  'age'	=>	'Age',
			  'amount of loan'	=>	'Amount of Loan',
			  'background investigation'	=>	'Background Investigation',
			  'bank fi reference checking'	=>	'Bank FI Reference Checking',
			  'citizenship'	=>	'Citizenship',
			  'downpayment'	=>	'Downpayment',
			  'employed self-employed'	=>	'Employed Self-Employed',
			  'employment status'	=>	'Employment status',
			  'gender'	=>	'Gender'
		);			
		
		return $data;
	}
	
*/	
	
	
//###################################################################################//		
//###################################################################################//	
//###################################################################################//	

	
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
    }
?>