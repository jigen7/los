<?php

class AutoRoutingController extends Zend_Controller_Action
{

    public function preDispatch()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
          	}
		else {
            $this->_helper->redirector('login','auth');
            }
	$this->_helper->RoleAccess();
	
	}
    public function init()
    {
        /* Initialize action controller here */
		
    }

    public function indexAction()
    {

    }
		
	public function approverattendanceAction()
	{
	//alexis code

		Zend_Layout::getMvcInstance()->assign('usedojo', 'true');
		$form = new Form_Admin_ApproverAttendance();
		$this->view->form = $form;
		$aamodel = new Model_AutoRouting_ApproverAttendance();
		$this->view->detail = $aamodel->getAllApproverAttendance();
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {			
			$user = $formData['approver'];
			$from = $formData['dateFrom'];
			$to = $formData['dateTo'];
			$remarks = $formData['remarks'];
				
			$umodel = new Model_Users();
			$role = $umodel->getRoleType($user);
			$from = date("m-d-Y", strtotime($from));
			$to = date("m-d-Y", strtotime($to));
			
			if(!$aamodel->hasAttendance($user))	
				$aamodel->addApproverAttendance($user, $role, $from, $to, login_user(), $remarks);					
			$this->view->detail = $aamodel->getAllApproverAttendance();
		}else{$form->populate($formData);}
	}	
	
	public function definemembersAction()
	{
		//alexis code

		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');
		
//		$form = new Form_Admin_DefineMembers();
//		$this->view->form = $form;
		
		$ccmodel = new Model_AutoRouting_CrecomConfig();
		$scmodel = new Model_AutoRouting_SubCrecomConfig();
				
		$this->view->cctable = $ccmodel->getAll();		
		$this->view->sctable = $scmodel->getAll();		
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {			
			$task = $formData['hiddenS'];
			if($task == "addcrecom"){
				//add in the table
			}else if($task == "updatecrecom"){
				$temp = array(); $noSame = true;
				$cctable = $ccmodel->getAll();
				foreach($cctable as $ccrow){
					$temp[] = $formData["croles".$ccrow->id];
				}
				for($i=0; $i!=count($temp); $i++){
					for($j=0; $j!=count($temp); $j++){
						if(($i != $j) && ($temp[$i] == $temp[$j]) && ($temp[$i] != "" || $temp[$j] != ""))
							$noSame = false;
					}
				}	
				if($noSame){
					//check the chairman and vice chairman if there is role 
					//if there is both, get the row by id, then update the role
					//if one missing echo error
				}else{
					//echo error same
				}
				$this->view->cctable = $ccmodel->getAll();	
			}else if($task == "addsubcrecom"){
				//addin the table
			}else if($task == "updatesubcrecom"){
			
			}
			
					
		}else{}		
	
	}
	
	public function dashboardAction(){
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/table-style.css');

		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/md5.js');
		$this->_helper->viewRenderer('accounts-dashboard');
		Zend_Layout::getMvcInstance()->assign('usedojo', 'true');
		$form = new Form_Search();
		$this->view->form = $form;
		foreach($form->getElements() as $element) {
		$element->removeDecorator('DtDdWrapper');
		$element->removeDecorator('Label');
		}
		
		if ($this->getRequest()->isPost()) {
	    $formData = $this->getRequest()->getPost();
		
		//print_r($formData);
		$this->view->startdate = $formData['startdate'];
		$this->view->enddate = $formData['enddate'];
		$form->startdate->setValue($formData['startdate']);
		$form->enddate->setValue($formData['enddate']);
		}
		}
	
	public function crecomdecisionAction(){
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/md5.js');
		$this->_helper->viewRenderer('crecom-decision-route');

		$group = $this->_getParam('grp');
		$capno = $this->_getParam('cap');	
		$user = Zend_Auth::getInstance()->getIdentity();
		$roleType = $user->role_type;
		$userTable = new Model_Users();
		if($userTable->getdigipasswordstatus(login_user()) === false) {
		$this->_helper->redirector('digitalpassword','admin');		
		}	
		
		$borrower = new Model_BorrowerAccount();
		$detail = $borrower->fetchRowModel($capno);
		if(!$grp){
			if(strpos($detail->routetag,'-CRECOM')!== false){
			$group = 'crecom';	
			}  
			if(strpos($detail->routetag,'-SUBCRECOM')!== false){
			$group = 'crecom';	
			} 
			if(strpos($detail->routetag,'-EXE-BOD')!== false){
			$group = 'crecom';	
			}
			
		}
		
		if($group == 'crecom'){
			$accountTable = new Model_AutoRouting_AccountsCrecom();
			$this->_helper->RolePermissionHelper('auto_crecom_decision_approver');
			$this->_helper->RolePermissionHelper->statusAccess($detail->account_status,'access_crecom_decision');
			$configTable = new Model_AutoRouting_CrecomConfig();

		}else if($group == 'subcrecom'){
			$accountTable = new Model_AutoRouting_AccountsSubCrecom();
			$this->_helper->RolePermissionHelper('auto_subcrecom_decision_approver');
			$this->_helper->RolePermissionHelper->statusAccess($detail->account_status,'access_subcrecom_decision');
			$configTable = new Model_AutoRouting_SubCrecomConfig();
		}
		//Redirect if already Dhe already decide
		if($accountTable->chkifDecide($capno,$roleType) === true){
		    $this->_redirect('/index/routedecisionview/cap/'.$capno);	
		}

		
		$borrower = new Model_BorrowerAccount();
		$select = $borrower->select();
		$select->where('capno like ?',$capno);
		$detail = $borrower->fetchRow($select);
		$this->view->detail = $detail; 
		
		$crecomTable = new Model_AutoRouting_AccountsCrecom();
		$crecomDetail = $crecomTable->fetchAllModel($capno);
		$this->view->crecomDetail = $crecomDetail;

		$subcrecomTable = new Model_AutoRouting_AccountsSubCrecom();
		$subcrecomDetail = $subcrecomTable->fetchAllModel($capno);
		$this->view->subcrecomDetail = $subcrecomDetail;
		
		
		$crawform = new Model_BorrowerCrawFormApprovalsection();
		$select = $crawform->select();
		$select->where('capno like ?',$capno)->order('date_approval2 ASC');
		$approveHistory = $crawform->fetchAll($select);
		$this->view->history = $approveHistory;
		
		$route = $detail->routetag;
		$this->view->capno = $capno;
		
	if ($this->getRequest()->isPost()) {
	    $formData = $this->getRequest()->getPost();

		
		$action = $formData['decisionBox'];

		
		if($action == 'approve'){
			$decision = 'Approved';
		}else if($action == 'disapprove') {
			$decision = 'Disapproved';			
		}
		
		$data = array(
		'capno'=>$capno,
		'decision'=>$decision,
		'user'=>$user->username,
		'role'=>$roleType,
		'type'=>$configTable->getMemberType($roleType),
		'date_decision'=>date("r"),
		'remarks'=>$formData['remarks'],
		);
		$accountTable->insert($data);

		$this->_helper->CrecomHelper->chkApprovalStatus2($capno,$group);
		$this->_helper->AutoRouteSms($capno);
		$this->_redirect('/index/routedecisionview/cap/'.$capno);	

		
		
		//$accountTable
		
	}// end of isPost
		
		


		
	}
	public function previousremarksAction(){
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->_helper->viewRenderer('prev-remarks');
		
		$capno = $this->_getParam('cap');	
		$table = new Model_UsersDraftSave();
		
		$select = $table->select();
		$select->where("capno like ?",$capno);
		$select->where("by like ?", login_user());
		$select->order("id DESC");
		$detail = $table->fetchAll($select);
		
		$this->view->history = $detail;
		$this->view->capno = $capno;
	}
	
	public function boardtagAction(){
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/md5.js');
		$this->_helper->viewRenderer('corpsec-decision-route');

		$group = $this->_getParam('grp');
		$capno = $this->_getParam('cap');	
		$user = Zend_Auth::getInstance()->getIdentity();
		$roleType = $user->role_type;
		$userTable = new Model_Users();
		if($userTable->getdigipasswordstatus(login_user()) === false) {
		$this->_helper->redirector('digitalpassword','admin');		
		}	
		
		$borrower = new Model_BorrowerAccount();
		$detail = $borrower->fetchRowModel($capno);
				
		$borrower = new Model_BorrowerAccount();
		$select = $borrower->select();
		$select->where('capno like ?',$capno);
		$detail = $borrower->fetchRow($select);
		$this->view->detail = $detail; 
		
		$crecomTable = new Model_AutoRouting_AccountsCrecom();
		$crecomDetail = $crecomTable->fetchAllModel($capno);
		$this->view->crecomDetail = $crecomDetail;

		$subcrecomTable = new Model_AutoRouting_AccountsSubCrecom();
		$subcrecomDetail = $subcrecomTable->fetchAllModel($capno);
		$this->view->subcrecomDetail = $subcrecomDetail;
		
		
		$crawform = new Model_BorrowerCrawFormApprovalsection();
		$select = $crawform->select();
		$select->where('capno like ?',$capno)->order('date_approval2 ASC');
		$approveHistory = $crawform->fetchAll($select);
		$this->view->history = $approveHistory;
		
		$route = $detail->routetag;
		$this->view->capno = $capno;
		
	if ($this->getRequest()->isPost()) {
	    $formData = $this->getRequest()->getPost();
		$action = $formData['decisionBox'];
		
		$crawformapproval = new Model_BorrowerCrawFormApprovalsection();

		if($action == 'approve-bod'){
		$decide = 'Approved';	
		$group = 'BOARD';	
		}
		else if($action == 'disapprove-bod'){
		$decide = 'Disapproved';
		$group = 'BOARD';	
		}
		else if($action == 'approve-exe'){
		$decide = 'Approved';
		$group = 'EXECOM';	
		}
		else if($action == 'disapprove-exe'){
		$decide = 'Disapproved';
		$group = 'EXECOM';	
		}
			$data = array(
			'capno'=>$capno,
			'decision'=>$group.' '.$decide,
			'approved_by'=>'CORPSEC TAGGED '.$group,
			'reason'=>'',
			'date_approval'=>date("m-d-Y h:i a"),
			'date_approval2'=>date("r"),
			'role'=> '',
			'date_type'=>3,
			);
		$crawformapproval->insert($data);		

	   $this->_redirect('/index/routedecisionview/cap/'.$capno);	
		
	}// end of isPost
	}
	
	public function recalldecisionAction(){
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		
			$id = $this->_getParam('id');	
			//$decision = $this->_getParam('act');	
			$group = $this->_getParam('grp');
			$capno = $this->_getParam('cap');	
		


		$borrower = new Model_BorrowerAccount();
		$detail = $borrower->fetchRowModel($capno);
		$this->_helper->RolePermissionHelper->statusAccess($detail->account_status,'access_autorouting_recall');

		$form = new Form_AutoRouting_PopupBox();
		$this->view->message = "Recall you Decision for this account?";
		//echo $id.' '.$decision.' '.$group.' '.$capno;
		$this->_helper->viewRenderer('box-recall-decision');

		$this->view->form = $form;
		$this->view->capno = $capno;
		
	if ($this->getRequest()->isPost()) {
	    $formData = $this->getRequest()->getPost();
		  if ($form->isValid($formData)) { 
		  
			$decision =$form->getValue('decision');

			
		if($group == 'crecom'){
			$accountTable = new Model_AutoRouting_AccountsCrecom();
			$groupSave = 'Crecom';
		}else if($group == 'subcrecom'){
			$accountTable = new Model_AutoRouting_AccountsSubCrecom();
			$groupSave = 'SubCrecom';
		}
		/*
		switch($decision){
			case 'Approved':
			$decision = 'Disapproved';
			break;	
			case 'Disapproved':
			$decision = 'Approved'; 
			break;
		}*/
		$data = array(
		'decision'=>$decision,		
		'remarks'=>$form->getValue('submit_remarks'),
		);
		$where = "id = ".$id;
		$accountTable->update($data,$where);
					
			//Insert Account History
				$history = new Model_AccountHistory();
				$select = $history->select();
				$select->where('capno like ?',$capno)->order('id DESC');
				$historyDetail = $history->fetchRow($select);
				$hdata = array (
				'capno'=>$capno,
				'status'=>$groupSave.' - Recall',
				'by'=>login_user(),
				'date'=>date("r"),
				'remarks'=>$decision.': '.$form->getValue('submit_remarks'),
				'date_last'=>$historyDetail->date,
				);
				$history->insert($hdata);
			//End of History
			$this->_helper->CrecomHelper->chkApprovalStatus2($capno,$group);
			$this->view->isSubmit = "OK";	
			} // end of PostData
		}// End of isPost
	
	}// End of Action 
    

    
	public function accountsprocessAction()
	{
	Zend_Layout::getMvcInstance()->assign('usedojo', 'true');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/table-style.css');
	$this->_helper->viewRenderer('accounts_report');
	$form = new Form_Search();
	$this->view->form = $form;
	foreach($form->getElements() as $element) {
	$element->removeDecorator('DtDdWrapper');
	$element->removeDecorator('Label');
	}
	
	if ($this->getRequest()->isPost()) {
		$formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)) {     
		
	//	echo $formData['submit'];

		//$table = new Model_Booking_BookingAuto();
		$table = new Model_Report_Accounts();
		$select = $table->select();
		
		$acctProcess = $form->getValue('acctProcess');
		$acctLevel = $form->getValue('acctLevel');
		$startdate = $form->getValue('startdate');
		if(!$startdate) { $startdate = '1999-12-30'; } 
		$enddate = $form->getValue('enddate');
		if(!$enddate) { $enddate = '2999-12-30'; } 
	
		if($startdate == $enddate){
				$enddate = $startdate." 24:00:00";
				$startdate = $startdate." 00:00:00";
				
		$select->where("datetime between '$startdate'  and '$enddate'");
		}
		else {
			$startdate = $startdate." 01:00:00";	// of the morning 
			$enddate = $enddate." 24:00:00";	 // of the evening
			$select->where("datetime between '$startdate' and '$enddate'");
		}
		/*****************************Startof Account Process******************************/	
		if($acctProcess){
			if($acctProcess == 'Approved'){
				$statusTable = new Model_Admin_AccountStatus();
				$select = $table->select();
					foreach($statusTable->routeBox('approve') as $x){
						$select->orwhere('status like ?',$x->status);
					}
				$condition = $select->getPart(Zend_Db_Select::WHERE);
				$select = $table->select()->order("datetime DESC");		
				$select->where(arrayString($condition));
			
			}else if($acctProcess == 'Rejected'){
					$statusTable = new Model_Admin_AccountStatus();
				$select = $table->select();
					foreach($statusTable->routeBox('reject') as $x){
						$select->orwhere('status like ?',$x->status);
					}
				$condition = $select->getPart(Zend_Db_Select::WHERE);
				$select = $table->select()->order("datetime DESC");		
				$select->where(arrayString($condition));
			}
			else{
			$select->where("role like ?","%".$acctProcess."%");
			}
		}
	/*****************************End of Account Process******************************/	
		if($acctLevel){
			$select->where('routetag like ?','%'.$acctLevel.'%');
		}
	$rowsDetail = $table->fetchAll($select);
	$this->view->rowResult = $rowsDetail;
	}
	}
	}// end of account report
	
	public function accountspendingAction()
	{
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/table-style.css');
	$this->_helper->viewRenderer('accounts-pending');
	$form = new Form_Search();
	$this->view->form = $form;
		foreach($form->getElements() as $element) {
	$element->removeDecorator('DtDdWrapper');
	$element->removeDecorator('Label');
	}
	
	if ($this->getRequest()->isPost()) {
		$formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)) {     
		
	//	echo $formData['submit'];
		$acctPending = $form->getValue('acctPending');
		//$table = new Model_Booking_BookingAuto();
		$acct = new Model_BorrowerAccount();
		
		
		$statusTable = new Model_Admin_AccountStatus();
		$selectStatus = $statusTable->select();
		
		switch($acctPending){
			case 'MA':
				$select = $acct->select();
				$selectStatus = $statusTable->select();
				$selectStatus->where('"current_user" like ?', 'MA');
				$arrStatus = $statusTable->fetchAll($selectStatus);
					foreach($arrStatus as $x){
						$select->orwhere('account_status like ?',$x->status);
					}
				$condition = $select->getPart(Zend_Db_Select::WHERE);
				$select = $acct->select();
				$select->where(arrayString($condition));
						
			break;
			
			case 'CA':
				$select = $acct->select();
				$selectStatus = $statusTable->select();
				$selectStatus->where('"current_user" like ?', 'CA');
				$arrStatus = $statusTable->fetchAll($selectStatus);
					foreach($arrStatus as $x){
						$select->orwhere('account_status like ?',$x->status);
					}
				$condition = $select->getPart(Zend_Db_Select::WHERE);
				$select = $acct->select();
				$select->where(arrayString($condition));
					
			break;
			
			case 'CO':
				$select = $acct->select();
				$selectStatus = $statusTable->select();
				$selectStatus->where('"current_user" like ?', 'CO');
				$arrStatus = $statusTable->fetchAll($selectStatus);
					foreach($arrStatus as $x){
						$select->orwhere('account_status like ?',$x->status);
					}
				$condition = $select->getPart(Zend_Db_Select::WHERE);
				$select = $acct->select();
				$select->where(arrayString($condition));
			break;
			
			case 'CSH':
				$select = $acct->select();
				$selectStatus = $statusTable->select();
				$selectStatus->where('"current_user" like ?', 'CSH');
				$arrStatus = $statusTable->fetchAll($selectStatus);
					foreach($arrStatus as $x){
						$select->orwhere('account_status like ?',$x->status);
					}
				$condition = $select->getPart(Zend_Db_Select::WHERE);
				$select = $acct->select();
				$select->where(arrayString($condition));
			break;
			
			case 'ALMH':
				$select = $acct->select();
				$selectStatus = $statusTable->select();
				$selectStatus->where('"current_user" like ?', 'ALMH');
				$arrStatus = $statusTable->fetchAll($selectStatus);
					foreach($arrStatus as $x){
						$select->orwhere('account_status like ?',$x->status);
					}
				$condition = $select->getPart(Zend_Db_Select::WHERE);
				$select = $acct->select();
				$select->where(arrayString($condition));
			break;
			
			case 'CMGH':
				$select = $acct->select();
				$selectStatus = $statusTable->select();
				$selectStatus->where('"current_user" like ?', '%CMGH%');
				$arrStatus = $statusTable->fetchAll($selectStatus);
					foreach($arrStatus as $x){
						$select->orwhere('account_status like ?',$x->status);
					}
				$condition = $select->getPart(Zend_Db_Select::WHERE);
				$select = $acct->select();
				$select->where(arrayString($condition));
			break;
			
			case 'PRES':
				$select = $acct->select();
				$selectStatus = $statusTable->select();
				$selectStatus->where('"current_user" like ?', '%PRES%');
				$arrStatus = $statusTable->fetchAll($selectStatus);
					foreach($arrStatus as $x){
						$select->orwhere('account_status like ?',$x->status);
					}
				$condition = $select->getPart(Zend_Db_Select::WHERE);
				$select = $acct->select();
				$select->where(arrayString($condition));
			break;
			
			case 'SUBCRECOM':
			break;
			
			case 'CRECOM':
				$select = $acct->select();
				$selectStatus = $statusTable->select();
				$selectStatus->where('"current_user" like ?', 'CRECOM');
				$arrStatus = $statusTable->fetchAll($selectStatus);
					foreach($arrStatus as $x){
						$select->orwhere('account_status like ?',$x->status);
					}
				$condition = $select->getPart(Zend_Db_Select::WHERE);
				$select = $acct->select();
				$select->where(arrayString($condition));				
			break;
			
			case 'BOD':
			break;
			
			case 'AO':
				$select = $acct->select();
				$selectStatus = $statusTable->select();
				$selectStatus->where('"current_user" like ?', 'AO');
				$arrStatus = $statusTable->fetchAll($selectStatus);
					foreach($arrStatus as $x){
						$select->orwhere('account_status like ?',$x->status);
					}
				$condition = $select->getPart(Zend_Db_Select::WHERE);
				$select = $acct->select();
				$select->where(arrayString($condition));
			break;
			
			case 'LA':
				$select = $acct->select();
				$selectStatus = $statusTable->select();
				$selectStatus->where('"current_user" like ?', 'LA');
				$arrStatus = $statusTable->fetchAll($selectStatus);
					foreach($arrStatus as $x){
						$select->orwhere('account_status like ?',$x->status);
					}
				$condition = $select->getPart(Zend_Db_Select::WHERE);
				$select = $acct->select();
				$select->where(arrayString($condition));				
			break;
			
			case 'LO':
				$select = $acct->select();
				$selectStatus = $statusTable->select();
				$selectStatus->where('"current_user" like ?', 'LO');
				$arrStatus = $statusTable->fetchAll($selectStatus);
					foreach($arrStatus as $x){
						$select->orwhere('account_status like ?',$x->status);
					}
				$condition = $select->getPart(Zend_Db_Select::WHERE);
				$select = $acct->select();
				$select->where(arrayString($condition));				
			break;
			
			
		}
		$select->order("application_date DESC");
		$select->where('relation like ?','Principal');
		/*****************************Startof Account Process******************************/	
	$rowsDetail = $acct->fetchAll($select);
	$this->view->rowResult = $rowsDetail;
	}
	}
	}// end of accounts pending
	   
}// End of Controller



function login_user(){
	
	$user = Zend_Auth::getInstance()->getIdentity();
	return $user->username;
}

function login_user_role(){
	
	$user = Zend_Auth::getInstance()->getIdentity();
	return $user->role_type;
}

function arrayString($array){
	foreach($array as $x){
		$string = $string.$x;
		
	}
	return $string;
	
}