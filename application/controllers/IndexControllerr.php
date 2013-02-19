<?php

class IndexController extends Zend_Controller_Action
{
 
    public function preDispatch()
    {
   	 $this->_helper->switchSsl();

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
	
	public function newscoreAction(){
		    $this->_helper->viewRenderer->setNoRender(true);
			$arr = $this->_helper->ScoreModule2->storeattr("1100102081002600",'single');
			//$arr = $this->_helper->ScoreModule2->storeattr("1100106011000100");
			$scoreString = $this->_helper->CreditScore->scoreIndivdualAccount($arr,'');						
			$storeAttr = new Model_CreditScoreAttr();
			$storeAttr->updateScore($scoreString);
			//update base on the id to the ScoreAttr Tables
	}
        
    public function indexAction()
    {
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/table-style.css');

        // action body
		$this->view->urll = $this->_getParam('id');
		$this->_helper->RolePermissionHelper->officersredirect('index_officersredirect');
		$user = new Model_Users();
		if($user->getpasswordstatus(login_user()) === false) {
		$this->_helper->redirector('changepassword','auth');		
		}
		
    }

    public function indexrouteAction()
    {
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/creditscore/prototype.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/creditscore/effects.js');
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/creditscore/accordion.js');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/autorouting/accordion.css');
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
		
		

		// action body
		if($this->_helper->RolePermissionHelper->hasAccess('index_route_view_officer')){
		$this->_helper->viewRenderer('index-route'); 
		}
		else if($this->_helper->RolePermissionHelper->hasAccess('index_route_view_csh')){
		//CSH
		$this->_helper->viewRenderer('index-route-csh'); 
		}
		else if($this->_helper->RolePermissionHelper->hasAccess('index_route_view_pres')){
		//President
		$this->_helper->viewRenderer('index-route-pres'); 
		}		
		//ALMH
		else if($this->_helper->RolePermissionHelper->hasAccess('index_route_view_almh')){
		$this->_helper->viewRenderer('index-route-almh'); 
		}
		//CorpSec
		else if($this->_helper->RolePermissionHelper->hasAccess('index_route_view_corpsec')){
		$this->_helper->viewRenderer('index-route-corpsec'); 
		}		
		else {
		$this->_helper->redirector('denied','error');
		}
		
		$user = new Model_Users();
		if($user->getpasswordstatus(login_user()) === false) {
		$this->_helper->redirector('changepassword','auth');		
		}
		
		$table = new Model_BorrowerAccount;
		$statusTable = new Model_Admin_AccountStatus();
		$username = login_user();
/*****************************CSH APPROVAL LEVEL*****/
		// CSH Level
		if(login_user_role() == 'CSH'){
		$select = $table->select();
		foreach($statusTable->routeBox('csh_level') as $x){
		$select->orwhere('account_status like ?',$x->status);
		}
		$condition = $select->getPart(Zend_Db_Select::WHERE);
		$select = $table->select();				
		//$select->where("account_status like 'ALMH - ENCSH' OR account_status = 'CO - P' OR account_status = 'CO - ReR' OR account_status = 'CO - ReAp'");
		$select->where(arrayString($condition));		
		$select->where("routetag not like '%-CRECOM%' AND routetag not like '%-SUBCRECOM%' AND routetag not like '%-EXE-BOD%'");
		$result = $table->fetchAll($select);
		//Submitted
		$select = $table->select();
		foreach($statusTable->routeBox('csh_decided') as $x){
		$select->orwhere('account_status like ?',$x->status);
		}
		$condition = $select->getPart(Zend_Db_Select::WHERE);
		$select = $table->select();			
		//$select->where("account_status = 'CSH - Ap' OR account_status = 'CSH - R' OR account_status = 'CSH - P' OR account_status = 'CSH - ReR' OR account_status = 'CSH - ReAp'");
		$select->where(arrayString($condition));		
		$select->where("routetag not like '%-CRECOM%' AND routetag not like '%-SUBCRECOM%'");
		$result2 = $table->fetchAll($select);
		//CreCom
		$select = $table->select();
		foreach($statusTable->routeBox('crecom_level') as $x){
		$select->orwhere('account_status like ?',$x->status);
		}
		$condition = $select->getPart(Zend_Db_Select::WHERE);
		
		$select = $table->select();		
		$select->orwhere("routetag like '%-CRECOM%'");
		$select->orwhere("routetag like '%-EXE-BOD%'");
		$condition2 = $select->getPart(Zend_Db_Select::WHERE);
		
		$select = $table->select();		
		$select->where(arrayString($condition));
		$select->where(arrayString($condition2));
				
		$select->order("date_decided DESC");
		$resultCrecom = $table->fetchAll($select);
		$this->view->resultCrecom = $resultCrecom;			
		// SubCrecom
		$select = $table->select();
		foreach($statusTable->routeBox('subcrecom_level') as $x){
		$select->orwhere('account_status like ?',$x->status);
		}
		$condition = $select->getPart(Zend_Db_Select::WHERE);
		$select = $table->select();		
		$select->where(arrayString($condition));
		$select->where("routetag like '%-SUBCRECOM%'");
		$select->order("date_decided DESC");
		$resultSubCrecom = $table->fetchAll($select);
		$this->view->resultSubCrecom = $resultSubCrecom;
		// Crecom Inbox
		$select = $table->select();
		foreach($statusTable->routeBox('crecom_inbox') as $x){
		$select->orwhere('account_status like ?',$x->status);
		}
		$condition = $select->getPart(Zend_Db_Select::WHERE);
		$select = $table->select();			
		$select->where(arrayString($condition));
		$select->order("date_decided DESC");
		$resultCrecomInbox = $table->fetchAll($select);	
		$this->view->crecominbox = $resultCrecomInbox;
		
		// SubCrecom Inbox
		$select = $table->select();
		foreach($statusTable->routeBox('subcrecom_inbox') as $x){
		$select->orwhere('account_status like ?',$x->status);
		}
		$condition = $select->getPart(Zend_Db_Select::WHERE);
		$select = $table->select();			
		$select->where(arrayString($condition));
		$select->order("date_decided DESC");
		$resultSubCrecomInbox = $table->fetchAll($select);	
		$this->view->subcrecominbox = $resultSubCrecomInbox;
		}		
	
/*****************************CMGH APPROVAL LEVEL*****/
		else if(login_user_role() == 'CMGH'){
		$select = $table->select();
		$select->where("account_status like 'ALMH - ENCMGH' OR account_status = 'CSH - P' OR account_status = 'CSH - ReAp' OR account_status = 'CO - ReAp - ABCSH' OR account_status = 'CO - ReR - ABCSH'");
		$select->where("routetag like '%CMGH%' OR routetag like '%$username%'");
		$result = $table->fetchAll($select);
		//Submitted
		$select = $table->select();
		$select->where("account_status = 'CMGH - P' OR account_status = 'CMGH - Ap' OR account_status = 'CMGH - R' OR account_status = 'CMGH - ReR' OR account_status = 'CMGH - ReAp'");
		$result2 = $table->fetchAll($select);
		}
/*****************************PRES APPROVAL LEVEL*****/
		else if(login_user_role() == 'PRES'){
		//PRES LEVEL	
			$select = $table->select();
			foreach($statusTable->routeBox('pres_level') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
			$select = $table->select();
			$select->where("routetag like '%PRES%' OR routetag like '%$username%'");
			$select->where(arrayString($condition));
			$select->order("date_decided DESC");
			/*
			$select->where("account_status like 'ALMH - ENPRES' OR account_status = 'CMGH - P' OR account_status = 'CSH - P' OR account_status = 'CSH - ReR' OR account_status = 'CSH - ReAp' OR account_status = 'CMGH - ReAp' OR account_status = 'CMGH - ReR' OR account_status = 'CSH - ReAp - ABCMGH' OR account_status = 'CSH - ReR - ABCMGH'");
			*/
			$result = $table->fetchAll($select);
		//Submitted Decided
			$select = $table->select();
			foreach($statusTable->routeBox('pres_decided') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
			$select = $table->select();
			$select->order("date_decided DESC");
			$select->where(arrayString($condition));
			
			/*
			$select->where("account_status = 'PRES - P' OR account_status = 'PRES - Ap' OR account_status = 'PRES - R' 
			OR account_status = 'CORPSEC - Ap' OR account_status = 'CORPSEC - R'");
			*/
			$result2 = $table->fetchAll($select);
		//Endorse
			$select = $table->select();
			foreach($statusTable->routeBox('pres_endorse') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$select->order("date_endorsed DESC");
			$resultEndorse = $table->fetchAll($select);
			$this->view->resultEndorse = $resultEndorse;
		//EXEBOARD LEVEL
			$select = $table->select();
			foreach($statusTable->routeBox('pres_level') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
			$select = $table->select();		
			$select->where(arrayString($condition));
			$select->where("routetag like '%EXE-BOD%'");
			$select->order("date_decided DESC");

			$resultBoard = $table->fetchAll($select);
			$this->view->resultBoard = $resultBoard;	
			//PRES CRECOM LEVEL
			$select = $table->select();
			foreach($statusTable->routeBox('pres_level') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
			$select = $table->select();		
			$select->where(arrayString($condition));
			$select->where("routetag like '%-CRECOM%'");
			$select->order("date_decided DESC");
			$resultCrecom = $table->fetchAll($select);
			$this->view->resultPresCrecom = $resultCrecom;	
			//PRES SUBCRECOM LEVEL
			$select = $table->select();
			foreach($statusTable->routeBox('pres_level') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
			$select = $table->select();		
			$select->where(arrayString($condition));$select->where("routetag like '%-SUBCRECOM%'");
			$select->order("date_decided DESC");
			$resultSubCrecom = $table->fetchAll($select);
			$this->view->resultPresSubCrecom = $resultSubCrecom;		
	//CreCom
		$select = $table->select();
		foreach($statusTable->routeBox('pres_crecom_level') as $x){
		$select->orwhere('account_status like ?',$x->status);
		}
		$condition = $select->getPart(Zend_Db_Select::WHERE);
		$select = $table->select();		
		$select->orwhere("routetag like '%-CRECOM%'");
		$select->orwhere("routetag like '%-EXE-BOD%'");
		$condition2 = $select->getPart(Zend_Db_Select::WHERE);
		$select = $table->select();	
		$select->where(arrayString($condition2));
		$select->where(arrayString($condition));
		$select->order("date_decided DESC");
		$resultCrecom = $table->fetchAll($select);
		$this->view->resultCrecom = $resultCrecom;			
		// SubCrecom
		$select = $table->select();
		foreach($statusTable->routeBox('subcrecom_level') as $x){
		$select->orwhere('account_status like ?',$x->status);
		}
		$condition = $select->getPart(Zend_Db_Select::WHERE);
		$select = $table->select();		
		$select->where(arrayString($condition));
		$select->where("routetag like '%-SUBCRECOM%'");
		$select->order("date_decided DESC");
		$resultSubCrecom = $table->fetchAll($select);
		$this->view->resultSubCrecom = $resultSubCrecom;

			// Crecom Inbox
			$select = $table->select();
			foreach($statusTable->routeBox('crecom_inbox') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
			$select = $table->select();			
			$select->where(arrayString($condition));
			$select->order("date_decided DESC");
			$resultCrecomInbox = $table->fetchAll($select);	
			$this->view->crecominbox = $resultCrecomInbox;
			
			// SubCrecom Inbox
			$select = $table->select();
			foreach($statusTable->routeBox('subcrecom_inbox') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
			$select = $table->select();			
			$select->where(arrayString($condition));
			$select->order("date_decided DESC");
			$resultSubCrecomInbox = $table->fetchAll($select);	
			$this->view->subcrecominbox = $resultSubCrecomInbox;
				
		} 
/*****************************CORPSEC ENDORSEMENT LEVEL*****/
		else if(login_user_role() == 'CORPSEC'){
		//EXEBOARD LEVEL
			$select = $table->select();
			foreach($statusTable->routeBox('pres_level') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
			$select = $table->select();		
			$select->where(arrayString($condition));
			$select->order("date_decided DESC");
			$select->where("routetag like '%EXE-BOD%'");
			$result = $table->fetchAll($select);
		//Submitted
			$select = $table->select();
			foreach($statusTable->routeBox('pres_decided') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
			$select = $table->select();
			$select->order("date_decided DESC");
			$select->where(arrayString($condition));
			$result2 = $table->fetchAll($select);
		}
/*****************************ALMH ENDORSEMENT LEVEL*****/
		else if(login_user_role() == 'ALMH'){
		//Rejected
			$select = $table->select();
			foreach($statusTable->routeBox('almh_level2') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
			$select = $table->select();		
			$select->where(arrayString($condition));
			$startdate = date('Y-m-d',strtotime('-6 day'));
			$enddate = date('Y-m-d',strtotime('+1 day'));
			$select->where("date_decided between '$startdate' AND '$enddate'");
			$select->order("date_decided DESC");
			//$select->where("account_status = 'CO - R' OR account_status = 'CSH - R' OR account_status = 'CMGH - R' OR account_status = 'PRES - R'");
			$result = $table->fetchAll($select);
		//Submitted
			$select = $table->select();
			foreach($statusTable->routeBox('almh_endorse') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
			$select = $table->select();		
			$select->order("date_endorsed DESC");
			$select->where(arrayString($condition));
			$result2 = $table->fetchAll($select);
		//Approved 
			$select = $table->select();
			foreach($statusTable->routeBox('approve') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
			$select = $table->select()->order("application_date DESC");		
			$select->where(arrayString($condition));
			$startdate = date('Y-m-d',strtotime('-60 day'));
			$enddate = date('Y-m-d',strtotime('+30 day'));
			$select->where("date_decided between '$startdate' AND '$enddate'");
			$select->order("date_decided DESC");
			$resultApproved = $table->fetchAll($select);
			$this->view->resultApproved = $resultApproved;		
		//For Booking
			$select = $table->select();
			foreach($statusTable->routeBox('almh_forbooking') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
			$select = $table->select();		
			$select->where(arrayString($condition));
			$select->order("submitted_mala_date DESC");
			$resultForBooking = $table->fetchAll($select);
			$this->view->resultForBooking = $resultForBooking;
		//Booked Accounts
			$select = $table->select();
			foreach($statusTable->routeBox('booked') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
			$select = $table->select();		
			$select->where(arrayString($condition));
			$select->order("date_booked DESC");
			$resultBooked = $table->fetchAll($select);
			$this->view->resultBooked = $resultBooked;
		//CreCom
		$select = $table->select();
		foreach($statusTable->routeBox('pres_crecom_level') as $x){
		$select->orwhere('account_status like ?',$x->status);
		}
		$condition = $select->getPart(Zend_Db_Select::WHERE);
		$select = $table->select();		
		$select->orwhere("routetag like '%-CRECOM%'");
		$select->orwhere("routetag like '%-EXE-BOD%'");
		$condition2 = $select->getPart(Zend_Db_Select::WHERE);
		$select = $table->select();		
		$select->where(arrayString($condition));
		$select->where(arrayString($condition2));
		$select->order("date_decided DESC");
		$resultCrecom = $table->fetchAll($select);
		$this->view->resultCrecom = $resultCrecom;			
		// SubCrecom
		$select = $table->select();
		foreach($statusTable->routeBox('subcrecom_level') as $x){
		$select->orwhere('account_status like ?',$x->status);
		}
		$condition = $select->getPart(Zend_Db_Select::WHERE);
		$select = $table->select();		
		$select->where(arrayString($condition));
		$select->where("routetag like '%-SUBCRECOM%'");
		$select->order("date_decided DESC");
		$resultSubCrecom = $table->fetchAll($select);
		$this->view->resultSubCrecom = $resultSubCrecom;
		// Crecom Inbox
		$select = $table->select();
		foreach($statusTable->routeBox('crecom_inbox') as $x){
		$select->orwhere('account_status like ?',$x->status);
		}
		$condition = $select->getPart(Zend_Db_Select::WHERE);
		$select = $table->select();			
		$select->where(arrayString($condition));
		$select->order("date_decided DESC");
		$resultCrecomInbox = $table->fetchAll($select);	
		$this->view->crecominbox = $resultCrecomInbox;
		
		// SubCrecom Inbox
		$select = $table->select();
		foreach($statusTable->routeBox('subcrecom_inbox') as $x){
		$select->orwhere('account_status like ?',$x->status);
		}
		$condition = $select->getPart(Zend_Db_Select::WHERE);
		$select = $table->select();			
		$select->where(arrayString($condition));
		$select->order("date_decided DESC");
		$resultSubCrecomInbox = $table->fetchAll($select);	
		$this->view->subcrecominbox = $resultSubCrecomInbox;
		
		
		
		} 
		
		$this->view->detail = $result;
		$this->view->detail2 = $result2;

    }
	
	

    public function addaccountAction()
    {
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
				
		//Getting the List of Cities to be Rendered in a Select Box
		$listcity = new Model_ListCity();
        $listcitydetail = $listcity->fetchAll();
	    $this->view->listcitydetail = $listcitydetail;
		//End of List Cities
		
		
		$form = new Form_AddAccount();
		$this->view->form = $form;

		$comaker_of = $this->_getParam('cap');	
		/********************************/
		if($comaker_of){
		$table = new Model_BorrowerAccount();
		$detail = $table->fetchRowModel($comaker_of);
		$form->typeloan->setValue($detail->loantype);
		$form->loan_purpose->setValue($detail->loan_purpose);
		$form->source_application->setValue($detail->source_application);
		$form->typeloan->setAttrib('style','display:none');
		$form->loan_purpose->setAttrib('style','display:none');
		$form->source_application->setAttrib('style','display:none');
		$form->promo_avail->setAttrib('style','display:none');
		}
		/*******************************/
		
		 if ($this->getRequest()->isPost()) { // Start of getRequest()->isPost()
	     $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {  
			
			$comaker_of = $this->_getParam('cap');	

			$capno = capnoGen($form->getValue('typeloan'),0);

			$accnt = new Model_BorrowerAccount();
			
			if ($form->getValue('landline')){
			$landline = 1;}else {$landline = 0;}
			
			if($form->getValue('new_Account') == 0){
			$account_status2 = 'NEW';}else {$account_status2 = 'REPEAT';}	
			
			if($comaker_of){			
			$account_status = '';// no account status for comaker
				}
			else{
			$account_status = 'MA - EN';	
			}
		
			
			
			//data to be insert to Borrower Accounts
			$data  = array(
			'capno' => $capno,
			'application_date' => date("r"), // change to Y-m-d h:i:s if 12hrs or h
			'borrower_lname' => strtoupper($form->getValue('borrower_lname')),
			'borrower_fname' => strtoupper($form->getValue('borrower_fname')),
			'borrower_mname' => strtoupper($form->getValue('borrower_mname')),
			'birthdate' => $form->getValue('birthdate'),
			//'pres_address_no' => strtoupper($form->getValue('borrower_pres_address_no')),
			'pres_address_st' => strtoupper($form->getValue('borrower_pres_address_st')),
			'pres_address_brgy' => $form->getValue('borrower_pres_address_brgy'),
			'pres_address_city' => $form->getValue('borrower_pres_address_city'),
			'pres_address_province' => $formData['borrower_pres_address_province'],
			'pres_zipcode' => $form->getValue('pres_zipcode'),
			'source_application' => $form->getValue('source_application'),
			'landline' => $form->getValue('landline'),
			'mobile' => $form->getValue('mobile'),
			'email' => $form->getValue('email'),
			//'age'=>trim(round(dateDiff("/", date("m/d/Y", time()), date("m/d/Y",strtotime($form->getValue('birthdate'))))/365, 0),'.'),
			'age' => $form->getValue('age'),
			'relation' => 'Principal',
			'loantype' => $form->getValue('typeloan'),
			'promo_fid'=> $form->getValue('promo'),
			'gender'=> $form->getValue('gender'),
			'tel_avail' => $landline,
			'civilstatus' => $form->getValue('civilstatus'),
			'account_status' => $account_status,
			'created_by'=>login_user(),
			'account_status2'=>$account_status2,
			'submitted_ca'=>rdmCA(),
			'submitted_co'=>rdmCO(),
			'dealer_coordinator'=>"",
			'application_type'=> 'Regular',
			'loan_purpose'=>$form->getValue('loan_purpose'),
			'addon_rate'=>'OMA',
			'comaker_of'=> $comaker_of,
			);
			$accnt->insert($data);		
			
			if($form->getValue('gender') == 1){
				$gender2 = 2;				
			}else { $gender2 = 1;}
			
			//adding Spouse if Civil Status is Married or Separated
			if ($form->getValue('civilstatus') == 2 || $form->getValue('civilstatus') == 3){
			$data2 = array(
			'capno' =>capnospcoGen($capno,'Spouse'),
			'borrower_lname' =>$form->getValue(borrower_spouse_lname),
			'borrower_fname' =>$form->getValue(borrower_spouse_fname),
			'borrower_mname' =>$form->getValue(borrower_spouse_mname),
			'birthdate' => $form->getValue('birthdate_spouse'),
			'pres_address_st' => strtoupper($form->getValue('borrower_pres_address_st')),
			'pres_address_brgy' => $form->getValue('borrower_pres_address_brgy'),
			'pres_address_city' => $form->getValue('borrower_pres_address_city'),
			'pres_address_province' => $formData['borrower_pres_address_province'],
			'pres_zipcode' => $form->getValue('pres_zipcode'),
			'relation' => 'Spouse',
			'gender'=>$gender2,
			'age'=>$form->getValue('spage'),
			'created_by'=>login_user(),			
			);
			$accnt->insert($data2);	
				
			}
			
			$status = new Model_AccountHistory();
			$data3 = array(
			'capno'=>$capno,
			'status'=>$account_status,
			'by' => login_user(),
			'date' =>date("r"),
			'date_last'=>date("r"),
			);
			$status->insert($data3);
			
			if($comaker_of){
			$this->_helper->ComakerModule($comaker_of);
			
			$data = array(
			'capno'=>$capno,
			'borrower_lname' => strtoupper($form->getValue('borrower_lname')).'- Co-Maker',
			'borrower_fname' => strtoupper($form->getValue('borrower_fname')).'- Co-Maker',
			'borrower_mname' => strtoupper($form->getValue('borrower_mname')).'- Co-Maker',
			);
			
			/**Audit Trail**/
			$this->_helper->AuditTrail->addComaker($data,$comaker_of);
			/** End of Audit Trail **/				
			}
			$this->_redirect('/index/accountedit/cap/'.$capno);
			
			
			}
		 } // End of getRequest()->isPost()
		
    }
	
public function addaccountcorpAction()
    {
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
				
		//Getting the List of Cities to be Rendered in a Select Box
		$listcity = new Model_ListCity();
        $listcitydetail = $listcity->fetchAll();
	    $this->view->listcitydetail = $listcitydetail;
		//End of List Cities
		$this->_helper->viewRenderer('addaccount-corp');

		
		$form = new Form_AddAccount();
		$this->view->form = $form;

		$comaker_of = $this->_getParam('cap');	
		/********************************/
		if($comaker_of){
		$table = new Model_BorrowerAccount();
		$detail = $table->fetchRowModel($comaker_of);
		$form->typeloan->setValue($detail->loantype);
		$form->loan_purpose->setValue($detail->loan_purpose);
		$form->source_application->setValue($detail->source_application);
		$form->typeloan->setAttrib('style','display:none');
		$form->loan_purpose->setAttrib('style','display:none');
		$form->source_application->setAttrib('style','display:none');
		$form->promo_avail->setAttrib('style','display:none');
		}
		/*******************************/
		
		 if ($this->getRequest()->isPost()) { // Start of getRequest()->isPost()
	     $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {  
			
			$comaker_of = $this->_getParam('cap');	

			$capno = capnoGen($form->getValue('typeloan'),0);

			$accnt = new Model_BorrowerAccount();
			
			if ($form->getValue('landline')){
			$landline = 1;}else {$landline = 0;}
			
			if($form->getValue('new_Account') == 0){
			$account_status2 = 'NEW';}else {$account_status2 = 'REPEAT';}	
			
			if($comaker_of){			
			$account_status = '';// no account status for comaker
				}
			else{
			$account_status = 'MA - EN';	
			}
		
			
			
			//data to be insert to Borrower Accounts
			$data  = array(
			'capno' => $capno,
			'application_date' => date("r"), // change to Y-m-d h:i:s if 12hrs or h
			'borrower_lname' => strtoupper($form->getValue('borrower_lname')),
			'borrower_fname' => strtoupper($form->getValue('borrower_fname')),
			'borrower_mname' => strtoupper($form->getValue('borrower_mname')),
			'birthdate' => $form->getValue('birthdate'),
			//'pres_address_no' => strtoupper($form->getValue('borrower_pres_address_no')),
			'pres_address_st' => strtoupper($form->getValue('borrower_pres_address_st')),
			'pres_address_brgy' => $form->getValue('borrower_pres_address_brgy'),
			'pres_address_city' => $form->getValue('borrower_pres_address_city'),
			'pres_address_province' => $formData['borrower_pres_address_province'],
			'pres_zipcode' => $form->getValue('pres_zipcode'),
			'source_application' => $form->getValue('source_application'),
			'landline' => $form->getValue('landline'),
			'mobile' => $form->getValue('mobile'),
			'email' => $form->getValue('email'),
			//'age'=>trim(round(dateDiff("/", date("m/d/Y", time()), date("m/d/Y",strtotime($form->getValue('birthdate'))))/365, 0),'.'),
			'age' => $form->getValue('age'),
			'relation' => 'Principal',
			'loantype' => $form->getValue('typeloan'),
			'promo_fid'=> $form->getValue('promo'),
			'gender'=> $form->getValue('gender'),
			'tel_avail' => $landline,
			'civilstatus' => $form->getValue('civilstatus'),
			'account_status' => $account_status,
			'created_by'=>login_user(),
			'account_status2'=>$account_status2,
			'submitted_ca'=>rdmCA(),
			'submitted_co'=>rdmCO(),
			'dealer_coordinator'=>"",
			'application_type'=> 'Regular',
			'loan_purpose'=>$form->getValue('loan_purpose'),
			'addon_rate'=>'OMA',
			'comaker_of'=> $comaker_of,
			);
			$accnt->insert($data);		
			
			if($form->getValue('gender') == 1){
				$gender2 = 2;				
			}else { $gender2 = 1;}
			
			//adding Spouse if Civil Status is Married or Separated
			if ($form->getValue('civilstatus') == 2 || $form->getValue('civilstatus') == 3){
			$data2 = array(
			'capno' =>capnospcoGen($capno,'Spouse'),
			'borrower_lname' =>$form->getValue(borrower_spouse_lname),
			'borrower_fname' =>$form->getValue(borrower_spouse_fname),
			'borrower_mname' =>$form->getValue(borrower_spouse_mname),
			'birthdate' => $form->getValue('birthdate_spouse'),
			'pres_address_st' => strtoupper($form->getValue('borrower_pres_address_st')),
			'pres_address_brgy' => $form->getValue('borrower_pres_address_brgy'),
			'pres_address_city' => $form->getValue('borrower_pres_address_city'),
			'pres_address_province' => $formData['borrower_pres_address_province'],
			'pres_zipcode' => $form->getValue('pres_zipcode'),
			'relation' => 'Spouse',
			'gender'=>$gender2,
			'age'=>$form->getValue('spage'),
			'created_by'=>login_user(),			
			);
			$accnt->insert($data2);	
				
			}
			
			$status = new Model_AccountHistory();
			$data3 = array(
			'capno'=>$capno,
			'status'=>$account_status,
			'by' => login_user(),
			'date' =>date("r"),
			'date_last'=>date("r"),
			);
			$status->insert($data3);
			
			if($comaker_of){
			$this->_helper->ComakerModule($comaker_of);
			
			$data = array(
			'capno'=>$capno,
			'borrower_lname' => strtoupper($form->getValue('borrower_lname')).'- Co-Maker',
			'borrower_fname' => strtoupper($form->getValue('borrower_fname')).'- Co-Maker',
			'borrower_mname' => strtoupper($form->getValue('borrower_mname')).'- Co-Maker',
			);
			
			/**Audit Trail**/
			$this->_helper->AuditTrail->addComaker($data,$comaker_of);
			/** End of Audit Trail **/				
			}
			$this->_redirect('/index/accountedit/cap/'.$capno);
			
			
			}
		 } // End of getRequest()->isPost()
		
    }	
	
public function accountAction(){

		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/menu.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');


		
		$capno = $this->_getParam('cap');	
		
		updateTotalIncome($capno);	

		$this->_helper->UpdateAcntStatus($capno,'');
		
		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		$select->where('capno like ?',$capno);
		$accntdetail = $accnt->fetchRow($select);
		$this->view->detail = $accntdetail; 
		
		$veh = new Model_BorrowerVehDetails();
		$select = $veh->select();
		$select->where('capno like ?',$capno);
		$vehdetail = $veh->fetchRow($select);
		$this->view->vehdetail = $vehdetail; 
		
		$cv = new Model_BorrowerCV();
		$select = $cv->select();
		$select->where('capno like ?',$capno);
		$cvdetail = $cv->fetchRow($select);
		$this->view->cvdetail = $cvdetail; 
		
		$ci = new Model_BorrowerCI();
		$select = $ci->select();
		$select->where('capno like ?',$capno);
		$cidetail = $ci->fetchRow($select);
		$this->view->cidetail = $cidetail; 
		
		
		$insurance = new Model_BorrowerInsurancePolicy();
		$select = $insurance->select();
		$select->where('capno like ?',$capno);
		$insurancedetail = $insurance->fetchRow($select);
		$this->view->insurancedetail = $insurancedetail; 
		
		$insurancecharges = new Model_BorrowerInsuranceCharges();
		$select = $insurancecharges->select();
		$select->where('capno like ?',$capno);
		$insurancecharges = $insurancecharges->fetchAll($select);
		$this->view->insurancecharges = $insurancecharges; 
		
		$insuranceperils = new Model_BorrowerInsurancePerils();
		$select = $insuranceperils->select();
		$select->where('capno like ?',$capno);
		$insuranceperils = $insuranceperils->fetchAll($select);
		$this->view->insuranceperils = $insuranceperils; 
		
		$craw = new Model_BorrowerCraw();
		$select = $craw->select();
		$select->where('capno like ?',$capno);
		$crawdetail = $craw->fetchRow($select);
		$this->view->crawdetail = $crawdetail; 
				
		//Determine which layout to use depends on the relation		
		if ($accntdetail->relation == 'Spouse'){
			$this->_helper->viewRenderer('account-view-spouse');
		}elseif ($accntdetail->relation == 'Coborrower'){
			$this->_helper->viewRenderer('account-view-coborrower');
		}elseif ($accntdetail->relation == 'Principal'){
			

			$this->_helper->viewRenderer('account-view-borrower');
					
		}
		
		//Adding Spouse And Coborrower to the profile select
		$tables = new Model_BorrowerAccount();
		$form = new Form_AccountPageView();
		//$form->score->setValue($this->_helper->ScoreModule($capno));
		$form->car_history->setAttrib('Disabled','True')->setValue($accntdetail->car_history);
		$sql = $tables->select()
	    ->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where('relation LIKE ?','Spouse')
		->order("capno ASC");
		

		$sql2 = $tables->select()
	    ->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where("relation = 'Coborrower' or relation = 'Co-Maker'")
		->order("capno ASC");
		
		// Display the Main Borrower if Comaker Profile
		if($tables->isComaker($capno)){
		$detail = $tables->fetchRowModel($tables->getMainCapno($capno));
		$form->profile->addMultiOption(BaseUrl().'/index/account/cap/'.$detail->capno, 'Main Borrower'.' - '.$detail->borrower_lname.','.$detail->borrower_fname); 
		}
		
		 foreach ($tables->fetchAll($sql,"capno ASC") as $c) {
         $form->profile->addMultiOption(BaseUrl().'/index/account/cap/'.$c->capno,'Spouse - '.$c->borrower_lname.','.$c->borrower_fname);} 
		 
		 foreach ($tables->fetchAll($sql2,"capno ASC") as $c) {
		 	 	if($c->coborrower_type == 'comaker'){
		 		$title = 'Co-Maker';
		 	}else {
		 		$title = 'Coborrower '.$c->coborrower_type;
		 	}
         $form->profile->addMultiOption(BaseUrl().'/index/account/cap/'.$c->capno, $title.' - '.$c->borrower_lname.','.$c->borrower_fname);} 
		
		if($tables->getComaker($capno)){
			$detail = $tables->fetchRowModel($tables->getComaker($capno));
			$form->profile->addMultiOption(BaseUrl().'/index/account/cap/'.$detail->capno, 'Co-Maker'.' - '.$detail->borrower_lname.','.$detail->borrower_fname); 
		}
        //$form->profile->addMultiOption(BaseUrl().'/index/account/cap/'.$c->capno, strtoupper($c->relation).' - '.$c->borrower_lname.','.$c->borrower_fname);} 
		//end of Adding Spouse & Coborrower Info 
		
		$form->creditanalyst->setvalue($accntdetail->submitted_ca);
		$form->creditofficer->setvalue($accntdetail->submitted_co);
		
		$this->view->highcap = getHighest($capno);
		$this->view->form = $form;
		$this->view->capno = $capno;
		$this->view->application_date = date('m/d/Y',strtotime($accntdetail->application_date));
		$this->view->origcapno = capnosep($capno).'0'.capnorecon($capno);
		$this->view->totalincome = moneyformat(totalincome($capno));
		$this->view->totalcombinedincome = moneyformat(totalcombinedincome($capno));
		$this->view->comakerCapno = $accnt->getComaker($capno);
		$this->view->isComaker = $accnt->isComaker($capno);
		$this->view->prevcapno = returnprevCap($capno);
	if ($this->getRequest()->isPost()) {
   	 $formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)) {
	    
		
		
		$capno = $this->_getParam('cap');
		if(login_user_role() == 'CA'){
		$data = array(
		 'submitted_ca'=>$form->getValue('creditanalyst'),
		);
		}else if(login_user_role() == 'CO'){
		$data = array(
		 'submitted_co'=>$form->getValue('creditofficer'),
		);
		}
		
		$where = "capno like '$capno'";
		$table = new Model_BorrowerAccount();
		$table->update($data,$where);
		
		
		
		$this->_helper->ComakerModule($capno);
		$this->_redirect('/index/account/cap/'.$capno);
			
		}
	}
	
	}


public function accounteditAction(){

		Zend_Layout::getMvcInstance()->assign('usedojo', 'true');

		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/menu.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
 		
		$accnt = new Model_BorrowerAccount();

		$capno = $this->_getParam('cap');		
		
		$this->_helper->RolePrivileges($accnt->getMainCapno($capno));
	
 		$this->_helper->UpdateAcntStatus($capno,'edit');

		$listcity = new Model_ListCity();
		$listcitydetail = $listcity->fetchAll();
		$this->view->listcitydetail = $listcitydetail;
		
		
		$select = $accnt->select();
		$select->where('capno like ?',$capno);
		$accntdetail = $accnt->fetchRow($select);
		//$this->view->empdetail = $empdetails; use in view account
		
				
		//Determine which layout to use depends on the relation		
		if ($accntdetail->relation == 'Spouse'){
			$this->_helper->viewRenderer('account-edit-spouse');
		}elseif ($accntdetail->relation == 'Coborrower' || $accntdetail->relation == 'Co-Maker'){
			$this->_helper->viewRenderer('account-edit-coborrower');
		}elseif ($accntdetail->relation == 'Principal'){
			$this->_helper->viewRenderer('account-edit-borrower');
		}
		
		$form = new Form_AccountPage();
		
		
		/*****************************/
		if($accntdetail->account_status2 == 'RECON'){
		//Recon to ReadOnly the Fields 
		$form->dosri->setAttrib('id','dosri');	
		$form->dosri->setAttrib('onclick',"disableoption('dosri')");	
		$form->dosri->setAttrib('readonly','readonly');	
		$form->loan_purpose->setAttrib('id','loan_purpose');	
		$form->loan_purpose->setAttrib('onclick',"disableoption('loan_purpose')");	
		$form->loan_purpose->setAttrib('readonly','readonly');	
		$form->source_application->setAttrib('id','source_application');	
		$form->source_application->setAttrib('onclick',"disableoption('source_application')");	
		$form->source_application->setAttrib('readonly','readonly');	
		$form->civilstatus->setAttrib('id','civilstatus');	
		$form->civilstatus->setAttrib('onclick',"disableoption('civilstatus')");	
		$form->civilstatus->setAttrib('readonly','readonly');	
		$form->gender->setAttrib('id','gender');	
		$form->gender->setAttrib('onclick',"disableoption('gender')");	
		$form->gender->setAttrib('readonly','readonly');	
		$form->phone_ver->setAttrib('id','phone_ver');	
		$form->phone_ver->setAttrib('onclick',"disableoption('phone_ver')");	
		$form->phone_ver->setAttrib('readonly','readonly');	
		//$form->dealercoordinator->setAttrib('id','dealercoordinator');	
		//$form->dealercoordinator->setAttrib('onclick',"disableoption('dealercoordinator')");	
		$form->dealercoordinator->setAttrib('readonly','readonly');	
		$form->borrower_pres_address_brgy->setAttrib('onclick',"disableoption('brgySelect')");	
		$form->borrower_pres_address_brgy->setAttrib('readonly','readonly');	
		$form->borrower_pres_address_city->setAttrib('onclick',"disableoption('citySelect')");	
		$form->borrower_pres_address_city->setAttrib('readonly','readonly');	
		$form->borrower_pres_address_province->setAttrib('onclick',"disableoption('categorySelect')");	
		$form->borrower_pres_address_province->setAttrib('readonly','readonly');																											
		
		if(login_user_role() == 'MA') {
		$form->profile->setAttrib('onclick',"disableoption('profile')");	
		$form->profile->setAttrib('readonly','readonly');																											
		}
		$form->borrower_pres_address_st->setAttrib('readonly','readonly');																											
		$form->borrower_lname->setAttrib('readonly','readonly');																											
		$form->borrower_fname->setAttrib('readonly','readonly');																											
		$form->borrower_mname->setAttrib('readonly','readonly');																											
		$form->tin_id->setAttrib('readonly','readonly');																											
		$form->landline->setAttrib('readonly','readonly');																											
		$form->mobile->setAttrib('readonly','readonly');																											
		$form->email->setAttrib('readonly','readonly');		
		// CV TABS
		$form->remarks_nfis->setAttrib('readonly','readonly');
		$form->remarks_cmap->setAttrib('readonly','readonly');
		$form->remarks_empver->setAttrib('readonly','readonly');
		$form->remarks_busver->setAttrib('readonly','readonly');
		$form->remarks_trdchk->setAttrib('readonly','readonly');
		$form->remarks_backgrd->setAttrib('readonly','readonly');
		$form->remarks_bankref->setAttrib('readonly','readonly');
		$form->remarks_creditchk->setAttrib('readonly','readonly');	
		$form->remarks_pastdealings->setAttrib('readonly','readonly');
		$form->remarks_income->setAttrib('readonly','readonly');			
		$form->cv_empver2->setAttrib('onclick',"disableoption('cv_empver2')");	
		$form->cv_empver2->setAttrib('readonly','readonly');
		$form->cv_empver->setAttrib('onclick',"disableoption('cv_empver')");	
		$form->cv_empver->setAttrib('readonly','readonly');
		
		$form->cv_busver2->setAttrib('onclick',"disableoption('cv_busver2')");	
		$form->cv_busver2->setAttrib('readonly','readonly');
		$form->cv_busver2->setAttrib('onclick',"disableoption('cv_busver')");	
		$form->cv_busver->setAttrib('readonly','readonly');
		$form->cv_trdchk2->setAttrib('onclick',"disableoption('cv_trdchk2')");	
		$form->cv_trdchk2->setAttrib('readonly','readonly');	
		$form->cv_trdchk->setAttrib('onclick',"disableoption('cv_trdchk')");	
		$form->cv_trdchk->setAttrib('readonly','readonly');				
		$form->cv_backgrd->setAttrib('onclick',"disableoption('cv_backgrd')");	
		$form->cv_backgrd->setAttrib('readonly','readonly');
			
		$form->cv_bankref->setAttrib('onclick',"disableoption('cv_bankref')");	
		$form->cv_bankref->setAttrib('readonly','readonly');
		$form->cv_creditchk->setAttrib('onclick',"disableoption('cv_creditchk')");	
		$form->cv_creditchk->setAttrib('readonly','readonly');	
		$form->cv_pastdealings->setAttrib('onclick',"disableoption('cv_pastdealings')");	
		$form->cv_pastdealings->setAttrib('readonly','readonly');
		$form->cv_income->setAttrib('onclick',"disableoption('cv_income')");	
		$form->cv_income->setAttrib('readonly','readonly');		
		$form->cv_income2->setAttrib('onclick',"disableoption('cv_income2')");	
		$form->cv_income2->setAttrib('readonly','readonly');	
		$form->cv_income->setAttrib('onclick',"disableoption('cv_income')");	
		$form->cv_income->setAttrib('readonly','readonly');		
		$form->cv_cmap2->setAttrib('onclick',"disableoption('cv_cmap2')");	
		$form->cv_cmap2->setAttrib('readonly','readonly');	
		$form->cv_nfis2->setAttrib('onclick',"disableoption('cv_nfis2')");	
		$form->cv_nfis2->setAttrib('readonly','readonly');																											
		}
		
		/**********End of Recon Section *******************/
		
		//For Coborrower Type
		if ($accntdetail->relation == 'Coborrower' || $accntdetail->relation == 'Co-Maker'){
		$form->coborrower_select->setValue($accntdetail->coborrower_type);
		$form->coborrower_relation->setValue($accntdetail->coborrower_relation);
		$form->coborrower_extend->setValue($accntdetail->coborrower_extend_relation);
		
		$tables = new Model_BorrowerAccount();
	     $sql2 = $tables->select()
		->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where('relation LIKE ?','Coborrower')
		->order("capno ASC");	     
	 	foreach ($tables->fetchAll($sql2,"id ASC") as $c) {
         $form->coborrower_to->addMultiOption($c->capno, $c->borrower_lname.' '.$c->borrower_fname); }
		$form->coborrower_to->setValue($accntdetail->coborrower_extend_capno);

		}
		//End of Coborrower Type
		$form->borrower_fname->setValue($accntdetail->borrower_fname);
		$form->borrower_lname->setValue($accntdetail->borrower_lname);
		$form->borrower_mname->setValue($accntdetail->borrower_mname);
		$form->typeloan->setValue($accntdetail->loantype);
	
		//$form->borrower_pres_address_no->setValue($accntdetail->pres_address_no);
		$form->borrower_pres_address_st->setValue($accntdetail->pres_address_st);
		$this->view->borrower_pres_address_brgy = $accntdetail->pres_address_brgy;
		$this->view->borrower_pres_address_city = $accntdetail->pres_address_city;
		$form->borrower_pres_address_brgy->setValue($accntdetail->pres_address_brgy);
		$form->borrower_pres_address_city->setValue($accntdetail->pres_address_city);
		$form->borrower_pres_address_province->setValue($accntdetail->pres_address_province);
		$form->pres_zipcode->setValue($accntdetail->pres_zipcode);
		
		
		//Details Tab
		//$form->borrower_prev_address_no->setValue($accntdetail->prev_address_no);
		$form->borrower_prev_address_st->setValue($accntdetail->prev_address_st);
		$this->view->borrower_prev_address_brgy = $accntdetail->prev_address_brgy;
		$this->view->borrower_prev_address_city = $accntdetail->prev_address_city;
		$form->borrower_prev_address_brgy->setValue($accntdetail->prev_address_brgy);
		$form->borrower_prev_address_city->setValue($accntdetail->prev_address_city);
		
		$form->borrower_prev_address_province->setValue($accntdetail->prev_address_province);
		$form->prev_zipcode->setValue($accntdetail->prev_zipcode);
		$form->loan_purpose->setValue($accntdetail->loan_purpose);
		$form->email->setValue($accntdetail->email);
		$form->mobile->setValue($accntdetail->mobile);
		$form->gender->setValue($accntdetail->gender);
		$form->landline->setValue($accntdetail->landline);
		$form->civilstatus->setValue($accntdetail->civilstatus);
		$form->tin_id->setValue($accntdetail->tin_id);
		$form->creditanalyst->setValue($accntdetail->submitted_ca);
		//$form->creditofficer->setValue($accntdetail->submitted_co);
		$form->source_application->setValue($accntdetail->source_application);
		
		$form->dosri->setValue($accntdetail->dosri);
		$form->phone_ver->setValue($accntdetail->phone_ver);
		
		$form->lenght_months_prev->setValue($accntdetail->residence_months_prev);
		$form->lenghtstay_prev->setValue($accntdetail->residence_yrs_prev);
		$form->residencetype_prev->setValue($accntdetail->residence_type_prev);
		$this->view->prevcounter = 	$accntdetail->residence_yrs_prev;
	
		$form->lenghtstay->setValue($accntdetail->residence_yrs);
		$form->lenght_months->setValue($accntdetail->residence_months);
		
		$form->residencetype->setValue($accntdetail->residence_type);
		$form->neighborhoodtype->setValue($accntdetail->neighborhoodtype);
		$form->birthdate->setValue(date('m/d/Y',strtotime($accntdetail->birthdate)));
		//$form->birthdate->setValue($accntdetail->birthdate);
		$form->birthplace->setValue($accntdetail->birthplace);
		$form->maiden_name->setValue($accntdetail->maiden_name);

		//$this->view->age = $accntdetail->age;
		$form->age->setValue($accntdetail->age);
		$form->dependentno->setValue($accntdetail->dependentno);
		$form->citizenship->setValue($accntdetail->citizenship);
		$form->relation->setValue($accntdetail->relation);
		
		//Unit Tabs
		$form->dealer->addMultiOption($accntdetail->dealer,$accntdetail->dealer);
		$form->dealer->setValue($accntdetail->dealer);
		$form->dealer_agent->setValue($accntdetail->dealer_agent);
		$form->dealercoordinator->setValue($accntdetail->dealer_coordinator);
		$form->branch_refferror->setValue($accntdetail->branch_refferror);
		$form->branch->addMultiOption($accntdetail->branch,$accntdetail->branch);
		$form->branch->setValue($accntdetail->branch);
		$form->marketingofficer->setValue($accntdetail->submitted_mo);
		$this->view->counter_branch = $accntdetail->branch_refferror;
		$form->veh_brand->setValue($accntdetail->veh_brand);
		$form->veh_id->setValue($accntdetail->veh_id);
		
		$form->selling_price2->setValue($accntdetail->lcp);
		
		$form->veh_unit->setValue($accntdetail->veh_unit);
		$this->view->veh_unit = $accntdetail->veh_unit;
		//$this->view->veh_brand = $accntdetail->veh_brand;	
		$form->veh_status->setValue($accntdetail->veh_status);
		$form->veh_type->setValue($accntdetail->veh_type);
		$form->veh_yrmodel->setValue($accntdetail->veh_yrmodel);
		$form->veh_age->setValue($accntdetail->veh_age);
		$form->veh_use->setValue($accntdetail->veh_use);
		$form->appraisal_value->setValue($accntdetail->appraisal_value);
		$form->car_history->setValue($accntdetail->car_history);
		$form->appraisal_date->setValue($accntdetail->appraisal_date);
		$form->appraised_by->setValue($accntdetail->appraised_by);
		$form->appraisal_waver_request->setValue($accntdetail->appraisal_waver_request);
		
		//Adding Loan Details to the Edit Form
		$form->selling_price->setValue($accntdetail->selling_price);
		$form->veh_discount->setValue($accntdetail->veh_discount);
		$form->downpayment_actual->setValue($accntdetail->downpayment_actual);
		$form->downpayment_percent->setValue($accntdetail->downpayment_percent);
		$form->loanterm->setValue($accntdetail->loanterm);
		$form->amountloan->setValue($accntdetail->amountloan);
		$form->monthly_amortization->setValue($accntdetail->monthly_amortization);
		$form->gmi_ratio->setValue($accntdetail->gmi_ratio);
		$form->rate->setValue($accntdetail->rate);
		$form->effective_yield->setValue($accntdetail->effective_yield);
		$form->dealer_incentive->setValue($accntdetail->dealer_incentive);
		$form->dealer_incentive2->setValue($accntdetail->dealer_incentive2);

		$form->addon_rate1->setValue($accntdetail->addon_rate);
		
		//Appraisal Tab
		//$form->fmv->setValue($accntdetail->fmv);
		//$form->appraisal_value->setValue($accntdetail->appraisal_value);
		//$form->car_history->setValue($accntdetail->car_history);

		//Adding CV Detail to the Edit Form
		$cv = new Model_BorrowerCv();
		$select = $cv->select();
		$select->where('capno like ?',$capno);
		$cvdetail = $cv->fetchRow($select);
		// Remarks for CV
		//$form->remarks_bap->setValue($cvdetail->remarks_bap);
		$form->date_nfis->setValue($cvdetail->date_nfis);
		$form->remarks_nfis->setValue($cvdetail->remarks_nfis);
		$form->remarks_cmap->setValue($cvdetail->remarks_cmap);
		$form->remarks_bankref->setValue($cvdetail->remarks_bankref);
		$form->remarks_creditchk->setValue($cvdetail->remarks_creditchk);
		$form->remarks_pastdealings->setValue($cvdetail->remarks_pastdealings);
		//$form->remarks_srcincomever->setValue($cvdetail->remarks_srcincomever);
		$form->remarks_empver->setValue($cvdetail->remarks_empver);
		$form->remarks_busver->setValue($cvdetail->remarks_busver);
		$form->remarks_trdchk->setValue($cvdetail->remarks_trdchk);
		$form->remarks_backgrd->setValue($cvdetail->remarks_backgrd);
		$form->remarks_income->setValue($cvdetail->remarks_income);
		
		
		//$form->cv_bap->setValue($cvdetail->bap);
		$form->cv_nfis->setValue($cvdetail->nfis);
		$form->cv_cmap->setValue($cvdetail->cmap);
		//$form->cv_srcincomever->setValue($cvdetail->srcincomever);
		$form->cv_empver->setValue($cvdetail->empver);
		$form->cv_busver->setValue($cvdetail->busver);
		$form->cv_trdchk->setValue($cvdetail->trdchk);
		$form->cv_bap2->setValue($cvdetail->bap2);
		$form->cv_nfis2->setValue($cvdetail->nfis2);
		$form->cv_cmap2->setValue($cvdetail->cmap2);
		//$form->cv_srcincomever2->setValue($cvdetail->srcincomever2);
		$form->cv_empver2->setValue($cvdetail->empver2);
		$form->cv_busver2->setValue($cvdetail->busver2);
		$form->cv_trdchk2->setValue($cvdetail->trdchk2);
		$form->cv_backgrd->setValue($cvdetail->backgrd);
		$form->cv_bankref->setValue($cvdetail->bankref);
		$form->cv_creditchk->setValue($cvdetail->creditchk);
		$form->cv_pastdealings->setValue($cvdetail->pastdealings);
		$form->cv_income->setValue($cvdetail->income);
		$form->cv_income2->setValue($cvdetail->income2);
						
		
		//$form->model_cv_srcincomever->setValue($cvdetail->model_cv_srcincomever);
		$form->model_cv_empver->setValue($cvdetail->model_cv_empver);
		$form->model_cv_busver->setValue($cvdetail->model_cv_busver);
		$form->model_cv_trdchk->setValue($cvdetail->model_cv_trdchk);
		$form->model_cv_backgrd->setValue($cvdetail->model_cv_backgrd);

		if(login_user_role() != "CO"){
		//$form->model_cv_srcincomever->setAttrib('Style','display:none');
		$form->model_cv_empver->setAttrib('Style','display:none');
		$form->model_cv_busver->setAttrib('Style','display:none');
		$form->model_cv_trdchk->setAttrib('Style','display:none');
		$form->model_cv_backgrd->setAttrib('Style','display:none');
		}
		if(login_user_role() == "MA"){
		$form->phone_ver->setAttrib('Style','display:none');
		$form->dosri->setAttrib('Style','display:none');
		$form->borrower_lname->setAttrib('readonly','readonly');
		$form->borrower_fname->setAttrib('readonly','readonly');		
		$form->borrower_mname->setAttrib('readonly','readonly');		
		}
		
		//Adding CI Detail to the Edit Form
		$ci = new Model_BorrowerCi();
		$select = $ci->select();
		$select->where('capno like ?',$capno);
		$cidetail = $ci->fetchRow($select);
		// Remarks for CV=I
		$form->date_ci->setValue($cidetail->date_ci);
		//$form->remarks_srcincomever_ci->setValue($cidetail->remarks_srcincomever_ci);
		$form->remarks_empver_ci->setValue($cidetail->remarks_empver_ci);
		$form->remarks_busver_ci->setValue($cidetail->remarks_busver_ci);
		$form->remarks_trdchk_ci->setValue($cidetail->remarks_trdchk_ci);
		$form->remarks_backgrd_ci->setValue($cidetail->remarks_backgrd_ci);
		$form->remarks_residence_ci->setValue($cidetail->remarks_residence_ci);
		$form->remarks_income_ci->setValue($cidetail->remarks_income_ci);
		
		//$form->ci_srcincomever->setValue($cidetail->srcincomever_ci);
		$form->ci_empver->setValue($cidetail->empver_ci);
		$form->ci_busver->setValue($cidetail->busver_ci);
		$form->ci_trdchk->setValue($cidetail->trdchk_ci);
		$form->ci_backgrd->setValue($cidetail->backgrd_ci);
		$form->ci_residence->setValue($cidetail->residence_ci);
		$form->ci_income->setValue($cidetail->income_ci);
		
		//$form->ci_srcincomever2->setValue($cidetail->srcincomever_ci2);
		$form->ci_empver2->setValue($cidetail->empver_ci2);
		$form->ci_busver2->setValue($cidetail->busver_ci2);
		$form->ci_trdchk2->setValue($cidetail->trdchk_ci2);
		$form->ci_backgrd2->setValue($cidetail->backgrd_ci2);
		$form->ci_residence2->setValue($cidetail->residence_ci2);
		$form->ci_income2->setValue($cidetail->income_ci2);

		//$form->model_ci_srcincomever->setValue($cidetail->model_ci_srcincomever);
		$form->model_ci_empver->setValue($cidetail->model_ci_empver);
		$form->model_ci_busver->setValue($cidetail->model_ci_busver);
		$form->model_ci_trdchk->setValue($cidetail->model_ci_trdchk);
		$form->model_ci_backgrd->setValue($cidetail->model_ci_backgrd);
		
		//Appraisal Value and REmarks
		$form->ci_appraisal_report->setValue($cidetail->ci_appraisal_report);
		$form->remarks_appraisal->setValue($cidetail->remarks_appraisal);

		
		if(login_user_role() != "CO"){
		//$form->model_ci_srcincomever->setAttrib('Style','display:none');
		$form->model_ci_empver->setAttrib('Style','display:none');
		$form->model_ci_busver->setAttrib('Style','display:none');
		$form->model_ci_trdchk->setAttrib('Style','display:none');
		$form->model_ci_backgrd->setAttrib('Style','display:none');
		}

		//Adding Veh Detail to the Edit Form
		$veh = new Model_BorrowerVehDetails();
		$select = $veh->select();
		$select->where('capno like ?',$capno);
		$vehdetail = $veh->fetchRow($select);
		
		$form->motor_no->setValue($vehdetail->motor_no);
		$form->serial_no->setValue($vehdetail->serial_no);
		$form->veh_color->setValue($vehdetail->veh_color);
		$form->plate_no->setValue($vehdetail->plate_no);
		$form->lto_off->setValue($vehdetail->lto_off);
		$form->or_no->setValue($vehdetail->or_no);
		$form->cr_no->setValue($vehdetail->cr_no);
		$form->or_date->setValue($vehdetail->or_date);
		$form->cr_date->setValue($vehdetail->cr_date);
		$form->ref_doc->setValue($vehdetail->ref_doc);
		$form->ref_no->setValue($vehdetail->ref_no);
		$form->ref_date->setValue($vehdetail->ref_date);
		$form->date_delivered->setValue($vehdetail->date_delivered);
		$form->uv_date->setValue($vehdetail->uv_date);
		$form->last_seen->setValue($vehdetail->last_seen);
		
		/********************************/
		// Co Maker Profile
		if($accnt->isComaker($capno)){
			
		$form->source_application->setAttrib('style','display:none');	
		$form->loan_purpose->setAttrib('style','display:none');	
		
		// Unit Tabs
		$form->dealer->setAttrib('ReadOnly','ReadOnly');
		$form->dealer_agent->setAttrib('ReadOnly','ReadOnly');
		$form->dealercoordinator->setAttrib('ReadOnly','ReadOnly');
		$form->branch->setAttrib('ReadOnly','ReadOnly');
		$form->branch_refferror->setAttrib('ReadOnly','ReadOnly');
		$form->marketingofficer->setAttrib('ReadOnly','ReadOnly');
		$form->veh_brand->setAttrib('ReadOnly','ReadOnly');
		$form->veh_unit->setAttrib('ReadOnly','ReadOnly');
		$form->veh_type->setAttrib('ReadOnly','ReadOnly');
		$form->veh_status->setAttrib('ReadOnly','ReadOnly');
		$form->veh_age->setAttrib('ReadOnly','ReadOnly');
		$form->veh_use->setAttrib('ReadOnly','ReadOnly');
		$form->appraisal_value->setAttrib('ReadOnly','ReadOnly');
		$form->appraisal_waver_request->setAttrib('ReadOnly','ReadOnly');
		$form->car_history->setAttrib('ReadOnly','ReadOnly');
		$form->ci_appraisal_report->setAttrib('ReadOnly','ReadOnly');
		$form->appraisal_date->setAttrib('ReadOnly','ReadOnly');
		$form->appraised_by->setAttrib('ReadOnly','ReadOnly');
		$form->remarks_appraisal->setAttrib('ReadOnly','ReadOnly');
		$form->veh_yrmodel->setAttrib('ReadOnly','ReadOnly');
		// End of Unit Tab
		// Loan Tabs 
		$form->selling_price2->setAttrib('ReadOnly','ReadOnly');
		$form->veh_discount->setAttrib('ReadOnly','ReadOnly');
		$form->selling_price->setAttrib('ReadOnly','ReadOnly');
		$form->loanterm->setAttrib('ReadOnly','ReadOnly');
		$form->downpayment_actual->setAttrib('ReadOnly','ReadOnly');
		$form->downpayment_percent->setAttrib('ReadOnly','ReadOnly');
		$form->amountloan->setAttrib('ReadOnly','ReadOnly');
		$form->rate->setAttrib('ReadOnly','ReadOnly');
		$form->monthly_amortization->setAttrib('ReadOnly','ReadOnly');
		$form->addon_rate1->setAttrib('ReadOnly','ReadOnly');
		$form->dealer_incentive->setAttrib('ReadOnly','ReadOnly');
		$form->dealer_incentive2->setAttrib('ReadOnly','ReadOnly');
		$form->effective_yield->setAttrib('ReadOnly','ReadOnly');
		// End of Loan Tab
		$form->comaker_relation->setValue($accntdetail->comaker_relation);
		
		
		
		}
		/*******************************/
		//Adding Spouse & Coborrower information in the profile select object 
		
		$tables = new Model_BorrowerAccount();
		
		$sql = $tables->select()
	    ->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where('relation LIKE ?','Spouse')
		->order("capno ASC");
		

		$sql2 = $tables->select()
	    ->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where("relation = 'Coborrower' or relation = 'Co-Maker'")
		->order("capno ASC");

		//use in getting the number of coborrower main
		$sql3 = $tables->select()
	    ->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where("relation = 'Coborrower' or relation = 'Co-Maker'")
		->where('coborrower_type NOT LIKE ?','extend')
		->order("capno ASC");
		
		
		//If the number of spouse is 3 remove the option value for adding spouse
		// If RoleAccess Permits it 
	
		
		if(($tables->fetchAll($sql)->count() < 3) && ($this->_helper->roleAccess2('add_spouse'))){
		$form->profile->addMultiOption(BaseUrl().'/index/addspouse/cap/'.$capno ,'Add Spouse');
		}
		//If the number of coborrower is 6  remove the option value for adding spouse
				
		if(($tables->fetchAll($sql3)->count() < 3) && ($this->_helper->roleAccess2('add_coborrower')) && (!$tables->isComaker($capno))) {
		$form->profile->addMultiOption(BaseUrl().'/index/addcoborrower/cap/'.$capno ,'Add Coborrower');
		}
		if (!$tables->getComaker($capno) && (!$tables->isComaker($capno))) {
		$form->profile->addMultiOption(BaseUrl().'/index/addaccount/cap/'.$capno ,'Add Comaker');
		}

		// Display the Main Borrower if Comaker Profile
		if($tables->isComaker($capno)){
			$detail = $tables->fetchRowModel($tables->getMainCapno($capno));
		$form->profile->addMultiOption(BaseUrl().'/index/accountedit/cap/'.$detail->capno, 'Main Borrower'.' - '.$detail->borrower_lname.','.$detail->borrower_fname); 
		
		}
		//Display the spouse in the dropdown		
		 foreach ($tables->fetchAll($sql,"capno ASC") as $c) {
         $form->profile->addMultiOption(BaseUrl().'/index/accountedit/cap/'.$c->capno, 'Spouse'.' - '.$c->borrower_lname.','.$c->borrower_fname);} 
		//Display the coborrower
		 foreach ($tables->fetchAll($sql2,"capno ASC") as $c) {
		 	if($c->coborrower_type == 'comaker'){
		 		$title = 'Co-Maker';
		 	}else {
		 		$title = 'Coborrower '.$c->coborrower_type;
		 	}
        $form->profile->addMultiOption(BaseUrl().'/index/accountedit/cap/'.$c->capno, $title.' - '.$c->borrower_lname.','.$c->borrower_fname);} 
		
		// Display the Comaker if Exist
		if($tables->getComaker($capno)){
			$detail = $tables->fetchRowModel($tables->getComaker($capno));
			$form->profile->addMultiOption(BaseUrl().'/index/accountedit/cap/'.$detail->capno, 'Co-Maker'.' - '.$detail->borrower_lname.','.$detail->borrower_fname); 
		}		
		
		//end of Adding Spouse & Coborrower Info 
		
		
		$form->listmonth_pricelist->clearMultiOptions();
		$form->listmonth_pricelist->addMultiOption('','');
		$form->listmonth_pricelist->addMultiOption(date('n')- 1,date('F',strtotime("-1 months")) );
		$form->listmonth_pricelist->addMultiOption(date('n'),date('F'));
		
		$this->view->highcap = getHighest($accntdetail->capno);
		$this->view->form = $form;
		$this->view->capno = $accntdetail->capno;
		$this->view->borrower = $accntdetail;
		$this->view->application_date = date('m/d/Y',strtotime($accntdetail->application_date));
		$this->view->origcapno = capnosep($capno).'0'.capnorecon($capno);
		$this->view->totalincome = moneyformat(totalincome($capno));
		$this->view->totalcombinedincome = moneyformat(totalcombinedincome($capno));
		$this->view->comakerExist = $tables->isComaker($capno);
		//$this->view->isComaker = $accnt->isComaker($capno);


	if ($this->getRequest()->isPost()) {
   	 $formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)) {
	    	
			$account = new Model_BorrowerAccount();
	
			$capno = $this->_getParam('cap');	
			$where = "capno like '$capno'";

			if ($form->getValue('landline')){
			$landline = 1;}else {$landline = 0;}
			
			
			if ($form->getValue('relation') == 'Principal'){
			//Change the Account Status
				chgAccountStatus($capno);
				$this->_helper->UpdateAcntStatus($capno,'save');
			// Form Data Gatheiring if the Account is the Principal Borrower
				// Profile Page
				if($formData['save_profile']){
				$data = array(
				'borrower_lname' => strtoupper($form->getValue('borrower_lname')),
				'borrower_fname' => strtoupper($form->getValue('borrower_fname')),
				'borrower_mname' => strtoupper($form->getValue('borrower_mname')),
				//'pres_address_no'=> strtoupper($form->getValue('borrower_pres_address_no')),
				'pres_address_st' =>$form->getValue('borrower_pres_address_st'),
				'pres_address_brgy' =>$form->getValue('borrower_pres_address_brgy'),
				'pres_address_city' => $form->getValue('borrower_pres_address_city'),
				'pres_address_province' => $formData['borrower_pres_address_province'],
				'pres_zipcode' => $form->getValue('pres_zipcode'),
				'loan_purpose'=>$form->getValue('loan_purpose'),
				'landline' => $form->getValue('landline'),			
				'mobile' => $form->getValue('mobile'),
				'email' => $form->getValue('email'),
				'gender'=> $form->getValue('gender'),
				'civilstatus' => $form->getValue('civilstatus'),
				'tin_id' => $form->getValue('tin_id'),
				'source_application'=> $form->getValue('source_application'),
				//'submitted_ca'=> $form->getValue('creditanalyst'),
				//'submitted_co'=> $form->getValue('creditofficer'),
				'dosri' => $form->getValue('dosri'),
				'phone_ver' => $form->getValue('phone_ver'),
				'comaker_accnt_status'=> $form->getValue('comaker_accnt_status'),			
				'comaker_relation'=> $form->getValue('comaker_relation')
				);
				
				/**Audit Trail**/
				$accnt = new Model_BorrowerAccount();
				$select = $accnt->select();
				$select->where('capno like ?',$capno);
				$accntdetail = $accnt->fetchRow($select)->toArray();
				$this->_helper->AuditTrail($accntdetail,$data, $capno);
				/** End of Audit Trail **/
				
				$account->update($data,$where);	
				}
				
				// Details Tab
				if($formData['save_details']){
				$data = array(
				//'prev_address_no' => strtoupper($form->getValue('borrower_prev_address_no')),
				'prev_address_st' => strtoupper($form->getValue('borrower_prev_address_st')),
				'prev_address_brgy' =>$form->getValue('borrower_prev_address_brgy'),
				'prev_address_city' => $form->getValue('borrower_prev_address_city'),
				'prev_address_province' => $formData['borrower_prev_address_province'],
				'prev_zipcode' => $form->getValue('prev_zipcode'),

				'residence_yrs_prev' => $form->getValue('lenghtstay_prev'),
				'residence_type_prev' => $form->getValue('residencetype_prev'),
				'residence_months_prev' => $form->getValue('lenght_months_prev'),
				'residence_months' => $form->getValue('lenght_months'),
				'residence_yrs' => $form->getValue('lenghtstay'),
				
				'residence_type' => $form->getValue('residencetype'),
				'neighborhoodtype'=>$form->getValue('neighborhoodtype'),
			
				'birthdate' => date("Y-m-d",strtotime($form->getValue('birthdate'))),
				'birthplace' =>$form->getValue('birthplace'),
				'maiden_name' =>$form->getValue('maiden_name'),
				
				'dependentno' =>$form->getValue('dependentno'),
				'citizenship' =>$form->getValue('citizenship'),
				'tel_avail' => $landline,
				//'age'=>trim(round(dateDiff("/", date("m/d/Y", time()),date("m/d/Y",strtotime($form->getValue('birthdate'))))/365, 0),'.'),
				'age' => $form->getValue('age'),
				'relation' => 'Principal',
				);
				
				/**Audit Trail**/
				$accnt = new Model_BorrowerAccount();
				$select = $accnt->select();
				$select->where('capno like ?',$capno);
				$accntdetail = $accnt->fetchRow($select)->toArray();
				$this->_helper->AuditTrail($accntdetail,$data, $capno);
				/** End of Audit Trail **/
				
				$account->update($data,$where);			     
			}
			
			//Unit Tabs
			if($formData['save_unit']){
			$data = array(
			'dealer' => $form->getValue('dealer'),
			'dealer_agent' => $form->getValue('dealer_agent'),
			'branch_refferror' =>$form->getValue('branch_refferror'),
			'dealer_coordinator'=> $form->getValue('dealercoordinator'),
			'submitted_mo'=>$form->getValue('marketingofficer'),
			'branch' => $form->getValue('branch'),
			'veh_brand' => $form->getValue('veh_brand'),
			'veh_status' => $form->getValue('veh_status'),
			'veh_type' => $form->getValue('veh_type'),
			'veh_unit' => $form->getValue('veh_unit'),
			'veh_yrmodel' =>$form->getValue('veh_yrmodel'),
			'veh_age'=>$form->getValue('veh_age'),
			'veh_use'=>$form->getValue('veh_use'),
			'appraisal_value'=> moneyconvert($form->getValue('appraisal_value')),
			'car_history' => $form->getValue('car_history'),
			'appraisal_date'=> $form->getValue('appraisal_date'),
			'appraised_by'=> $form->getValue('appraised_by'),
			'appraisal_waver_request'=> $form->getValue('appraisal_waver_request'),
			'veh_id' =>$form->getValue('veh_id'),
			);
			
			/**Audit Trail**/
				$accnt = new Model_BorrowerAccount();
				$select = $accnt->select();
				$select->where('capno like ?',$capno);
				$accntdetail = $accnt->fetchRow($select)->toArray();
				$this->_helper->AuditTrail($accntdetail,$data, $capno);
			/** End of Audit Trail **/
			
			$data2 = array(
			//Get Form Data for Unit Other Details motor_no,or_no etc
			'capno' => $capno,
			//'dealer' => $form->getValue('dealer'),
			//'veh_brand' => $form->getValue('veh_brand'),
			//'veh_status' => $form->getValue('veh_status'),
			//'veh_type' => $form->getValue('veh_type'),
			//'veh_unit' => $form->getValue('veh_unit'),
			//'veh_yrmodel' =>$form->getValue('veh_yrmodel'),
			//'veh_age'=>$form->getValue('veh_age'),
			//'veh_use'=>$form->getValue('veh_use'),
			
			'motor_no' =>$form->getValue('motor_no'),
			'serial_no' =>$form->getValue('serial_no'),
			'veh_color'=>$form->getValue('veh_color'),
			'plate_no' =>$form->getValue('plate_no'),
			'lto_off' =>$form->getValue('lto_off'),
			'or_no' =>$form->getValue('or_no'),
			'cr_no' =>$form->getValue('cr_no'),
			'or_date' =>chkdate($form->getValue('or_date')),
			'cr_date' =>chkdate($form->getValue('cr_date')),
			'ref_doc' =>chkdate($form->getValue('ref_doc')),
			'ref_no' =>chkdate($form->getValue('ref_no')),
			'ref_date' =>chkdate($form->getValue('ref_date')),
			'date_delivered' =>chkdate($form->getValue('date_delivered')),
			'uv_date' =>chkdate($form->getValue('uv_date')),
			'last_seen' =>chkdate($form->getValue('last_seen')),
			);
			


			$vehdetails = new Model_BorrowerVehDetails();
			$sql = $vehdetails->select()->where('capno LIKE ?',$capno);

				if($vehdetails->fetchAll($sql)->count() == 0){
					$vehdetails->insert($data2);	
					//if no record found insert data
				}
				else{
					//else update using the capno
				$where = "capno like '$capno'";
				
			/**Audit Trail**/
				$accnt = new Model_BorrowerVehDetails();
				$select = $accnt->select();
				$select->where('capno like ?',$capno);
				$accntdetail = $accnt->fetchRow($select)->toArray();
				$this->_helper->AuditTrail($accntdetail,$data2, $capno);
			/** End of Audit Trail **/
				
				$vehdetails->update($data2,$where);						
				}
			$account->update($data,$where);			     

			}
			
			// Loan Tabs
			//**Default values for GMI Ratio If newly Added
			if($form->getValue('monthly_amortization') == 'NaN'){
				$monthlyamortization = 0.00;
			}else{
				$monthlyamortization = moneyconvert($form->getValue('monthly_amortization'));
			}

			
			if($formData['save_loan']){
			
				$existing_auto_loan = getExistLoan($capno,'totalvalueloan','Auto','CBCS');
				$existing_housing_loan = getExistLoan($capno,'totalvalueloan','Home','CBCS');
				$existing_personal_loan = getExistLoan($capno,'totalvalueloan','Personal','CBCS');
				$existing_business_loan = getExistLoan($capno,'totalvalueloan','Business','CBCS');
				$existing_other_loan = getExistLoan($capno,'totalvalueloan','Others','CBCS');		
				$total_bankproper_loan= $existing_auto_loan + $existing_housing_loan + $existing_personal_loan + $existing_business_loan + $existing_other_loan;						
			
			$data = array(
			'amountloan'=>moneyconvert($form->getValue('amountloan')),
			//Sept 13,2010 Aggretage Exposure 
			'amountloan2'=>moneyconvert($total_bankproper_loan+$form->getValue('amountloan')),
			//selling price is the appraisal value
			'selling_price'=>$form->getValue('selling_price'),
			'lcp'=>str_replace(',','',$form->getValue('selling_price2')),
			'veh_discount'=>$form->getValue('veh_discount'),
			'downpayment_actual'=>moneyconvert($form->getValue('downpayment_actual')),
			'downpayment_percent'=>$form->getValue('downpayment_percent'),
			'monthly_amortization'=>$monthlyamortization,
			//'gmi_ratio'=>$gmir,
			'loanterm'=>$form->getValue('loanterm'),
			'addon_rate'=>$form->getValue('addon_rate1'),
			'rate'=>$form->getValue('rate'),
			'effective_yield'=>$form->getValue('effective_yield'),
			'dealer_incentive'=>$form->getValue('dealer_incentive'),
			'dealer_incentive2'=>$form->getValue('dealer_incentive2'),
			
			);
			/**Audit Trail**/
				$accnt = new Model_BorrowerAccount();
				$select = $accnt->select();
				$select->where('capno like ?',$capno);
				$accntdetail = $accnt->fetchRow($select)->toArray();
				$this->_helper->AuditTrail($accntdetail,$data, $capno);
			/** End of Audit Trail **/
						
			$account->update($data,$where);	
			
			
			//$gmir = $form->getValue('gmi_ratio');
			$gmir = re_gmi_ratio($capno);
			$datagmi = array(
			'gmi_ratio'=> $gmir,
			);
			/**Audit Trail**/
				$accnt = new Model_BorrowerAccount();
				$select = $accnt->select();
				$select->where('capno like ?',$capno);
				$accntdetail = $accnt->fetchRow($select)->toArray();
				$this->_helper->AuditTrail($accntdetail,$datagmi, $capno);
			/** End of Audit Trail **/   
			
			$account->update($datagmi,$where);		
  
			}
			
			// Appraisal Tabs
			if($formData['save_appraisal']){
			$data = array(
 			//Appraisal
			'fmv' => moneyconvert($form->getValue('fmv')),
			'appraisal_value' => moneyconvert($form->getValue('appraisal_value')),
			'car_history' => $form->getValue('car_history'),
			);
			
			/**Audit Trail**/
				$accnt = new Model_BorrowerAccount();
				$select = $accnt->select();
				$select->where('capno like ?',$capno);
				$accntdetail = $accnt->fetchRow($select)->toArray();
				$this->_helper->AuditTrail($accntdetail,$data, $capno);
			/** End of Audit Trail **/
			
			$account->update($data,$where);			     
			}
			//'promo_fid'=> $form->getValue('promo'),
			//'source_application' => $form->getValue('source_application'),
			
			if($formData['save_cv']){
					
					$data3 = array(
					// Get Form data for CV Tabs
					'capno' => $capno,
					//'bap' => $formData['cv_bap'],
					'nfis' => $formData['cv_nfis'],
					'cmap' => $formData['cv_cmap'],
					'bankref' => $formData['cv_bankref'],
					'creditchk' => $formData['cv_creditchk'],
					'pastdealings' => $formData['cv_pastdealings'],
					//'srcincomever' => $formData['cv_srcincomever'],
					'empver' => $formData['cv_empver'],
					'busver' => $formData['cv_busver'],
					'trdchk' => $formData['cv_trdchk'],
					'backgrd' => $formData['cv_backgrd'],
					'income' => $formData['cv_income'],
					//'remarks_bap' => $formData['remarks_bap'],
					'remarks_nfis' => $formData['remarks_nfis'],
					'remarks_cmap' => $formData['remarks_cmap'],
					'remarks_bankref' => $formData['remarks_bankref'],
					'remarks_creditchk' => $formData['remarks_creditchk'],
					'remarks_pastdealings' => $formData['remarks_pastdealings'],
					//'remarks_srcincomever' => $formData['remarks_srcincomever'],
					'remarks_empver' => $formData['remarks_empver'],
					'remarks_busver' => $formData['remarks_busver'],
					'remarks_trdchk' => $formData['remarks_trdchk'],
					'remarks_backgrd' => $formData['remarks_backgrd'],
					'remarks_income' => $formData['remarks_income'],
					//'model_cv_srcincomever' => $formData['model_cv_srcincomever'],
					'model_cv_empver' => $formData['model_cv_empver'],
					'model_cv_busver' => $formData['model_cv_busver'],
					'model_cv_trdchk' => $formData['model_cv_trdchk'],
					'model_cv_backgrd' => $formData['model_cv_backgrd'],
					'date_nfis'=> $formData['date_nfis'],
					//'bap2' => $formData['cv_bap2'],
					'nfis2' => $formData['cv_nfis2'],
					'cmap2' => $formData['cv_cmap2'],
					//'srcincomever2' => $formData['cv_srcincomever2'],
					'empver2' => $formData['cv_empver2'],
					'busver2' => $formData['cv_busver2'],
					'trdchk2' => $formData['cv_trdchk2'],
					'income2' => $formData['cv_income2'],
				
					);
					
						$cvdetails = new Model_BorrowerCv();
						$sql2 = $cvdetails->select()->where('capno LIKE ?',$capno);
		
						if($cvdetails->fetchAll($sql2)->count() == 0){
						$cvdetails->insert($data3);	
						//if no record found insert data
						}
						else{
						//else update using the capno
						$where = "capno like '$capno'";
						
						/**Audit Trail**/
							$accnt = new Model_BorrowerCv();
							$select = $accnt->select();
							$select->where('capno like ?',$capno);
							$accntdetail = $accnt->fetchRow($select)->toArray();
							$this->_helper->AuditTrail($accntdetail,$data3, $capno);
						/** End of Audit Trail **/
						
						$cvdetails->update($data3,$where);						
						}	
				}
				if($formData['save_ci']){
					
					$data4 = array(
					// Get Form data for CI Tabs
					'capno' => $capno,
		
					//'srcincomever_ci' => $formData['ci_srcincomever'],
					'empver_ci' => $formData['ci_empver'],
					'busver_ci' => $formData['ci_busver'],
					'trdchk_ci' => $formData['ci_trdchk'],
					//'backgrd_ci' => $formData['ci_backgrd'],
					//'srcincomever_ci2' => $formData['ci_srcincomever2'],
					'empver_ci2' => $formData['ci_empver2'],
					'busver_ci2' => $formData['ci_busver2'],
					'trdchk_ci2' => $formData['ci_trdchk2'],
					'backgrd_ci2' => $formData['ci_backgrd2'],
					'residence_ci' =>  $formData['ci_residence'],
					'residence_ci2' =>  $formData['ci_residence2'],
					'income_ci' =>  $formData['ci_income'],
					'income_ci2' =>  $formData['ci_income2'],
					'date_ci'=> $formData['date_ci'],
					'remarks_residence_ci' => $formData['remarks_residence_ci'],
					//'remarks_srcincomever_ci' => $formData['remarks_srcincomever_ci'],
					'remarks_empver_ci' => $formData['remarks_empver_ci'],
					'remarks_busver_ci' => $formData['remarks_busver_ci'],
					'remarks_trdchk_ci' => $formData['remarks_trdchk_ci'],
					'remarks_backgrd_ci' => $formData['remarks_backgrd_ci'],
					'remarks_income_ci' => $formData['remarks_income_ci'],
					//'model_ci_srcincomever' => $formData['model_ci_srcincomever'],
					//'model_ci_empver' => $formData['model_ci_empver'],
					//'model_ci_busver' => $formData['model_ci_busver'],
					//'model_ci_trdchk' => $formData['model_ci_trdchk'],
					//'model_ci_backgrd' => $formData['model_ci_backgrd'],
					'remarks_appraisal'=> $form->getValue('remarks_appraisal'),
					'ci_appraisal_report'=> $form->getValue('ci_appraisal_report'),
					);
					
						$cidetails = new Model_BorrowerCi();
						$sql3 = $cidetails->select()->where('capno LIKE ?',$capno);
		
						if($cidetails->fetchAll($sql3)->count() == 0){
						$cidetails->insert($data4);	
						//if no record found insert data
						}
						else{
						//else update using the capno
						$where = "capno like '$capno'";
						
						/**Audit Trail**/
							$accnt = new Model_BorrowerCi();
							$select = $accnt->select();
							$select->where('capno like ?',$capno);
							$accntdetail = $accnt->fetchRow($select)->toArray();
							$this->_helper->AuditTrail($accntdetail,$data4, $capno);
						/** End of Audit Trail **/	
						$cidetails->update($data4,$where);						
						}	
					//Update the CV Details Base on the Model Basis
							/*
							if ($formData['model_ci_empver']){
								$model_cv_empver = 0;
							}else{ $model_cv_empver = 1;}
							if ($formData['model_ci_busver']){
								$model_cv_busver = 0;
							}else{ $model_cv_busver = 1;}
							if ($formData['model_ci_trdchk']){
								$model_cv_trdchk = 0;
							}else{ $model_cv_trdchk = 1;}*/
							if ($formData['ci_backgrd2']){
								$model_cv_backgrd = 0;
								$model_ci_backgrd = 1;
							}else{ $model_cv_backgrd = 1;
								   $model_ci_backgrd = 0;	
								}
							$where = "capno like '$capno'";

							$data9 = array(
							'model_ci_backgrd'=>$model_ci_backgrd,
							);
							$cidetails->update($data9,$where);	
		
							
							$data5 = array(
							//'model_cv_srcincomever' =>$model_cv_srcincomever,
							//'model_cv_empver'=>$model_cv_empver,
							//'model_cv_busver'=>$model_cv_busver,
							//'model_cv_trdchk'=>$model_cv_trdchk,
							'model_cv_backgrd'=>$model_cv_backgrd,
							);
							$cvdetails = new Model_BorrowerCv();
							
							/**Audit Trail**/
							$accnt = new Model_BorrowerCv();
							$select = $accnt->select();
							$select->where('capno like ?',$capno);
							$accntdetail = $accnt->fetchRow($select)->toArray();
							$this->_helper->AuditTrail($accntdetail,$data5, $capno);
							/** End of Audit Trail **/
							$cvdetails->update($data5,$where);	
						// End of Update
		
				}
				
				/**March 17,2010****/
				// For Comaker Update the Comaker if it exist
				$this->_helper->ComakerModule($capno);	
				/******/
				$this->_redirect('/index/accountedit/cap/'.$capno);
			
			} // End of Principal Relation
		else if ($form->getValue('relation') == 'Spouse'){
			// Form Data Gatheiring if the Account is a Spouse
			
				
			if($formData['save_profile']){

					$data = array(
					'borrower_lname' => strtoupper($form->getValue('borrower_lname')),
					'borrower_fname' => strtoupper($form->getValue('borrower_fname')),
					'borrower_mname' => strtoupper($form->getValue('borrower_mname')),
					//'pres_address_no' => strtoupper($form->getValue('borrower_pres_address_no')),
					'pres_address_st' => $form->getValue('borrower_pres_address_st'),
					'pres_address_brgy' => $form->getValue('borrower_pres_address_brgy'),
					'pres_address_city' => $form->getValue('borrower_pres_address_city'),
					'pres_address_province' => $formData['borrower_pres_address_province'],
					'pres_zipcode' => $form->getValue('pres_zipcode'),
			
					'gender' => $form->getValue('gender'),
					'landline' => $form->getValue('landline'),			
					'mobile' => $form->getValue('mobile'),
					'email' => $form->getValue('email'),
					'tin_id' => $form->getValue('tin_id'),
					);	
					
						/**Audit Trail**/
							$accnt = new Model_BorrowerAccount();
							$select = $accnt->select();
							$select->where('capno like ?',$capno);
							$accntdetail = $accnt->fetchRow($select)->toArray();
							$this->_helper->AuditTrail($accntdetail,$data, $capno);
						/** End of Audit Trail **/
					$account->update($data,$where);			     
				}// End of Save Profile			
			
				// Details Tab
			if($formData['save_details']){
				$data = array(
					//'prev_address_no' => $form->getValue('borrower_prev_address_no'),
					'prev_address_st' => $form->getValue('borrower_prev_address_st'),
					'prev_address_brgy' => $form->getValue('borrower_prev_address_brgy'),
					'prev_address_city' => $form->getValue('borrower_prev_address_city'),
					'prev_address_province' => $formData['borrower_prev_address_province'],
					'prev_zipcode' => $form->getValue('prev_zipcode'),
								
					'residence_yrs_prev' => $form->getValue('lenghtstay_prev'),
					'residence_type_prev' => $form->getValue('residencetype_prev'),
					'residence_months_prev' => $form->getValue('lenght_months_prev'),
					'residence_months' => $form->getValue('lenght_months'),
			
					
					'residence_yrs' => $form->getValue('lenghtstay'),
					'residence_type' => $form->getValue('residencetype'),
					'neighborhoodtype'=>$form->getValue('neighborhoodtype'),
					
					'birthdate' => date("Y-m-d",strtotime($form->getValue('birthdate'))),
					'birthplace' =>$form->getValue('birthplace'),
					'maiden_name' =>$form->getValue('maiden_name'),
					'citizenship' =>$form->getValue('citizenship'),
					'tel_avail' => $landline,
					//'age'=>trim(round(dateDiff("/", date("m/d/Y", time()),date("m/d/Y",strtotime($form->getValue('birthdate'))))/365, 0),'.'),
					'age' => $form->getValue('age'),
					'relation' =>'Spouse',
					);
					
						/**Audit Trail**/
							$accnt = new Model_BorrowerAccount();
							$select = $accnt->select();
							$select->where('capno like ?',$capno);
							$accntdetail = $accnt->fetchRow($select)->toArray();
							$this->_helper->AuditTrail($accntdetail,$data, $capno);
						/** End of Audit Trail **/
					$account->update($data,$where);	
			}
			if($formData['save_cv']){
			
					$data3 = array(
					// Get Form data for CV Tabs
					'capno' => $capno,
					//'bap' => $formData['cv_bap'],
					'nfis' => $formData['cv_nfis'],
					'cmap' => $formData['cv_cmap'],
					'bankref' => $formData['cv_bankref'],
					'creditchk' => $formData['cv_creditchk'],
					'pastdealings' => $formData['cv_pastdealings'],
					//'srcincomever' => $formData['cv_srcincomever'],
					'empver' => $formData['cv_empver'],
					'busver' => $formData['cv_busver'],
					'trdchk' => $formData['cv_trdchk'],
					'backgrd' => $formData['cv_backgrd'],
					'income' => $formData['cv_income'],
					//'remarks_bap' => $formData['remarks_bap'],
					'remarks_nfis' => $formData['remarks_nfis'],
					'remarks_cmap' => $formData['remarks_cmap'],
					'remarks_bankref' => $formData['remarks_bankref'],
					'remarks_creditchk' => $formData['remarks_creditchk'],
					'remarks_pastdealings' => $formData['remarks_pastdealings'],
					//'remarks_srcincomever' => $formData['remarks_srcincomever'],
					'remarks_empver' => $formData['remarks_empver'],
					'remarks_busver' => $formData['remarks_busver'],
					'remarks_trdchk' => $formData['remarks_trdchk'],
					'remarks_backgrd' => $formData['remarks_backgrd'],
					'remarks_income' => $formData['remarks_income'],
					//'model_cv_srcincomever' => $formData['model_cv_srcincomever'],
					'model_cv_empver' => $formData['model_cv_empver'],
					'model_cv_busver' => $formData['model_cv_busver'],
					'model_cv_trdchk' => $formData['model_cv_trdchk'],
					'model_cv_backgrd' => $formData['model_cv_backgrd'],
					'date_nfis'=> $formData['date_nfis'],
					//'bap2' => $formData['cv_bap2'],
					'nfis2' => $formData['cv_nfis2'],
					'cmap2' => $formData['cv_cmap2'],
					//'srcincomever2' => $formData['cv_srcincomever2'],
					'empver2' => $formData['cv_empver2'],
					'busver2' => $formData['cv_busver2'],
					'trdchk2' => $formData['cv_trdchk2'],
					'income2' => $formData['cv_income2'],
					);
					
						$cvdetails = new Model_BorrowerCv();
						$sql2 = $cvdetails->select()->where('capno LIKE ?',$capno);
		
						if($cvdetails->fetchAll($sql2)->count() == 0){
						$cvdetails->insert($data3);	
						//if no record found insert data
						}
						else{
						//else update using the capno
						$where = "capno like '$capno'";
						
						/**Audit Trail**/
							$accnt = new Model_BorrowerCv();
							$select = $accnt->select();
							$select->where('capno like ?',$capno);
							$accntdetail = $accnt->fetchRow($select)->toArray();
							$this->_helper->AuditTrail($accntdetail,$data3, $capno);
						/** End of Audit Trail **/
						$cvdetails->update($data3,$where);				

						}	
				}// End of CV
				
				if($formData['save_ci']){
			
					$data4 = array(
					// Get Form data for CI Tabs
					'capno' => $capno,
					//'srcincomever_ci' => $formData['ci_srcincomever'],
					'empver_ci' => $formData['ci_empver'],
					'busver_ci' => $formData['ci_busver'],
					'trdchk_ci' => $formData['ci_trdchk'],
					//'backgrd_ci' => $formData['ci_backgrd'],
					//'srcincomever_ci2' => $formData['ci_srcincomever2'],
					'empver_ci2' => $formData['ci_empver2'],
					'busver_ci2' => $formData['ci_busver2'],
					'trdchk_ci2' => $formData['ci_trdchk2'],
					'backgrd_ci2' => $formData['ci_backgrd2'],
					'residence_ci' =>  $formData['ci_residence'],
					'residence_ci2' =>  $formData['ci_residence2'],
					'income_ci' =>  $formData['ci_income'],
					'income_ci2' =>  $formData['ci_income2'],
					'date_ci'=> $formData['date_ci'],
					'remarks_residence_ci' => $formData['remarks_residence_ci'],
					//'remarks_srcincomever_ci' => $formData['remarks_srcincomever_ci'],
					'remarks_empver_ci' => $formData['remarks_empver_ci'],
					'remarks_busver_ci' => $formData['remarks_busver_ci'],
					'remarks_trdchk_ci' => $formData['remarks_trdchk_ci'],
					'remarks_backgrd_ci' => $formData['remarks_backgrd_ci'],
					'remarks_income_ci' => $formData['remarks_income_ci'],
					//'model_ci_srcincomever' => $formData['model_ci_srcincomever'],
					//'model_ci_empver' => $formData['model_ci_empver'],
					//'model_ci_busver' => $formData['model_ci_busver'],
					//'model_ci_trdchk' => $formData['model_ci_trdchk'],
					//'model_ci_backgrd' => $formData['model_ci_backgrd'],
					);
					
						$cidetails = new Model_BorrowerCi();
						$sql3 = $cidetails->select()->where('capno LIKE ?',$capno);
		
						if($cidetails->fetchAll($sql3)->count() == 0){
						$cidetails->insert($data4);	
						//if no record found insert data
						}
						else{
						//else update using the capno
						$where = "capno like '$capno'";
						
						/**Audit Trail**/
							$accnt = new Model_BorrowerCi();
							$select = $accnt->select();
							$select->where('capno like ?',$capno);
							$accntdetail = $accnt->fetchRow($select)->toArray();
							$this->_helper->AuditTrail($accntdetail,$data4, $capno);
						/** End of Audit Trail **/
						$cidetails->update($data4,$where);				
	
						}
						//Update the CV Details Base on the Model Basis
							/*
							if ($formData['model_ci_empver']){
								$model_cv_empver = 0;
							}else{ $model_cv_empver = 1;}
							if ($formData['model_ci_busver']){
								$model_cv_busver = 0;
							}else{ $model_cv_busver = 1;}
							if ($formData['model_ci_trdchk']){
								$model_cv_trdchk = 0;
							}else{ $model_cv_trdchk = 1;}*/
							if ($formData['ci_backgrd2']){
								$model_cv_backgrd = 0;
								$model_ci_backgrd = 1;
							}else{ $model_cv_backgrd = 1;
								   $model_ci_backgrd = 0;	
								}
							$where = "capno like '$capno'";

							$data9 = array(
							'model_ci_backgrd'=>$model_ci_backgrd,
							);
							$cidetails->update($data9,$where);
							$data5 = array(
							//'model_cv_srcincomever' =>$model_cv_srcincomever,
							'model_cv_empver'=>$model_cv_empver,
							'model_cv_busver'=>$model_cv_busver,
							'model_cv_trdchk'=>$model_cv_trdchk,
							'model_cv_backgrd'=>$model_cv_backgrd,
							);
							$cvdetails = new Model_BorrowerCv();
						/**Audit Trail**/
							$accnt = new Model_BorrowerCv();
							$select = $accnt->select();
							$select->where('capno like ?',$capno);
							$accntdetail = $accnt->fetchRow($select)->toArray();
							$this->_helper->AuditTrail($accntdetail,$data5, $capno);
						/** End of Audit Trail **/
							
							$cvdetails->update($data5,$where);	
						// End of Update	
				}// End of Save CI 
			$this->_redirect('/index/accountedit/cap/'.$capno);

			} // End of Spouse
			
			
			elseif ($form->getValue('relation') == 'Coborrower'){
			
			if($formData['save_profile']){

					$data = array(
					'borrower_lname' => strtoupper($form->getValue('borrower_lname')),
					'borrower_fname' => strtoupper($form->getValue('borrower_fname')),
					'borrower_mname' => strtoupper($form->getValue('borrower_mname')),
					//'pres_address_no' => strtoupper($form->getValue('borrower_pres_address_no')),
					'pres_address_st' => strtoupper($form->getValue('borrower_pres_address_st')),
					'pres_address_brgy' => $form->getValue('borrower_pres_address_brgy'),
					'pres_address_city' => $form->getValue('borrower_pres_address_city'),
					'pres_address_province' => $formData['borrower_pres_address_province'],
					'pres_zipcode' => $form->getValue('pres_zipcode'),					
					'landline' => $form->getValue('landline'),			
					'mobile' => $form->getValue('mobile'),
					'email' => $form->getValue('email'),
					'tin_id' => $form->getValue('tin_id'),
					);	
					
						/**Audit Trail**/
							$accnt = new Model_BorrowerAccount();
							$select = $accnt->select();
							$select->where('capno like ?',$capno);
							$accntdetail = $accnt->fetchRow($select)->toArray();
							$this->_helper->AuditTrail($accntdetail,$data, $capno);
						/** End of Audit Trail **/
						
					$account->update($data,$where);			     
				}// End of Save Profile			
			
				// Details Tab
			if($formData['save_details']){
				$data = array(
					//'prev_address_no' => strtoupper($form->getValue('borrower_prev_address_no')),
					'prev_address_st' => strtoupper($form->getValue('borrower_prev_address_st')),
					'prev_address_brgy' => $form->getValue('borrower_prev_address_brgy'),
					'prev_address_city' => $form->getValue('borrower_prev_address_city'),
					'prev_address_province' => $formData['borrower_prev_address_province'],
					'prev_zipcode' => $form->getValue('prev_zipcode'),
									
					'residence_yrs_prev' => $form->getValue('lenghtstay_prev'),
					'residence_type_prev' => $form->getValue('residencetype_prev'),
					'residence_months_prev' => $form->getValue('lenght_months_prev'),
					'residence_months' => $form->getValue('lenght_months'),
					
					'residence_yrs' => $form->getValue('lenghtstay'),
					'residence_type' => $form->getValue('residencetype'),
					'neighborhoodtype'=>$form->getValue('neighborhoodtype'),
					'gender'=>$form->getValue('gender'),
					'birthdate' => $form->getValue('birthdate'),
					'birthplace' =>$form->getValue('birthplace'),
					'maiden_name' =>$form->getValue('maiden_name'),
					'citizenship' =>$form->getValue('citizenship'),
					'civilstatus' => $form->getValue('civilstatus'),
					'dependentno' =>$form->getValue('dependentno'),
					'tel_avail' => $landline,
					//'age'=>trim(round(dateDiff("/", date("m/d/Y", time()),date("m/d/Y",strtotime($form->getValue('birthdate'))))/365, 0),'.'),
					'age' => $form->getValue('age'),
					'relation' =>'Coborrower',
					'coborrower_type'=> $form->getValue('coborrower_select'),
					'coborrower_relation'=> $form->getValue('coborrower_relation'),
					'coborrower_extend_relation'=>$form->getValue('coborrower_extend'),
					'coborrower_extend_capno'=>$form->getValue('coborrower_to'),
					);
					
						/**Audit Trail**/
							$accnt = new Model_BorrowerAccount();
							$select = $accnt->select();
							$select->where('capno like ?',$capno);
							$accntdetail = $accnt->fetchRow($select)->toArray();
							$this->_helper->AuditTrail($accntdetail,$data, $capno);
						/** End of Audit Trail **/
					$account->update($data,$where);	
			}
			if($formData['save_cv']){
			
					$data3 = array(
					// Get Form data for CV Tabs
					'capno' => $capno,
					//'bap' => $formData['cv_bap'],
					'nfis' => $formData['cv_nfis'],
					'cmap' => $formData['cv_cmap'],
					'bankref' => $formData['cv_bankref'],
					'creditchk' => $formData['cv_creditchk'],
					'pastdealings' => $formData['cv_pastdealings'],
					//'srcincomever' => $formData['cv_srcincomever'],
					'empver' => $formData['cv_empver'],
					'busver' => $formData['cv_busver'],
					'trdchk' => $formData['cv_trdchk'],
					'backgrd' => $formData['cv_backgrd'],
					'income' => $formData['cv_income'],
					//'remarks_bap' => $formData['remarks_bap'],
					'remarks_nfis' => $formData['remarks_nfis'],
					'remarks_cmap' => $formData['remarks_cmap'],
					'remarks_bankref' => $formData['remarks_bankref'],
					'remarks_creditchk' => $formData['remarks_creditchk'],
					'remarks_pastdealings' => $formData['remarks_pastdealings'],
					//'remarks_srcincomever' => $formData['remarks_srcincomever'],
					'remarks_empver' => $formData['remarks_empver'],
					'remarks_busver' => $formData['remarks_busver'],
					'remarks_trdchk' => $formData['remarks_trdchk'],
					'remarks_backgrd' => $formData['remarks_backgrd'],
					'remarks_income' => $formData['remarks_income'],
					//'model_cv_srcincomever' => $formData['model_cv_srcincomever'],
					'model_cv_empver' => $formData['model_cv_empver'],
					'model_cv_busver' => $formData['model_cv_busver'],
					'model_cv_trdchk' => $formData['model_cv_trdchk'],
					'model_cv_backgrd' => $formData['model_cv_backgrd'],
					'date_nfis'=> $formData['date_nfis'],
					//'bap2' => $formData['cv_bap2'],
					'nfis2' => $formData['cv_nfis2'],
					'cmap2' => $formData['cv_cmap2'],
					//'srcincomever2' => $formData['cv_srcincomever2'],
					'empver2' => $formData['cv_empver2'],
					'busver2' => $formData['cv_busver2'],
					'trdchk2' => $formData['cv_trdchk2'],
					'income2' => $formData['cv_income2'],
					);
					
						$cvdetails = new Model_BorrowerCv();
						$sql2 = $cvdetails->select()->where('capno LIKE ?',$capno);
		
						if($cvdetails->fetchAll($sql2)->count() == 0){
						$cvdetails->insert($data3);	
						//if no record found insert data
						}
						else{
						//else update using the capno
						$where = "capno like '$capno'";
						
						/**Audit Trail**/
							$accnt = new Model_BorrowerCv();
							$select = $accnt->select();
							$select->where('capno like ?',$capno);
							$accntdetail = $accnt->fetchRow($select)->toArray();
							$this->_helper->AuditTrail($accntdetail,$data3, $capno);
						/** End of Audit Trail **/
						$cvdetails->update($data3,$where);				
						}	
						


				}// End of CV
				
				if($formData['save_ci']){
			
					$data4 = array(
					// Get Form data for CI Tabs
					'capno' => $capno,
		
					//'srcincomever_ci' => $formData['ci_srcincomever'],
					'empver_ci' => $formData['ci_empver'],
					'busver_ci' => $formData['ci_busver'],
					'trdchk_ci' => $formData['ci_trdchk'],
					//'backgrd_ci' => $formData['ci_backgrd'],
					//'srcincomever_ci2' => $formData['ci_srcincomever2'],
					'empver_ci2' => $formData['ci_empver2'],
					'busver_ci2' => $formData['ci_busver2'],
					'trdchk_ci2' => $formData['ci_trdchk2'],
					'backgrd_ci2' => $formData['ci_backgrd2'],
					'residence_ci' =>  $formData['ci_residence'],
					'residence_ci2' =>  $formData['ci_residence2'],
					'income_ci' =>  $formData['ci_income'],
					'income_ci2' =>  $formData['ci_income2'],
					'date_ci'=> $formData['date_ci'],
					'remarks_residence_ci' => $formData['remarks_residence_ci'],
					//'remarks_srcincomever_ci' => $formData['remarks_srcincomever_ci'],
					'remarks_empver_ci' => $formData['remarks_empver_ci'],
					'remarks_busver_ci' => $formData['remarks_busver_ci'],
					'remarks_trdchk_ci' => $formData['remarks_trdchk_ci'],
					'remarks_backgrd_ci' => $formData['remarks_backgrd_ci'],
					'remarks_income_ci' => $formData['remarks_income_ci'],
					//'model_ci_srcincomever' => $formData['model_ci_srcincomever'],
					//'model_ci_empver' => $formData['model_ci_empver'],
					//'model_ci_busver' => $formData['model_ci_busver'],
					//'model_ci_trdchk' => $formData['model_ci_trdchk'],
					//'model_ci_backgrd' => $formData['model_ci_backgrd'],
					);
					
						$cidetails = new Model_BorrowerCi();
						$sql3 = $cidetails->select()->where('capno LIKE ?',$capno);
		
						if($cidetails->fetchAll($sql3)->count() == 0){
						$cidetails->insert($data4);	
						//if no record found insert data
						}
						else{
						//else update using the capno
						$where = "capno like '$capno'";
						
						/**Audit Trail**/
							$accnt = new Model_BorrowerCi();
							$select = $accnt->select();
							$select->where('capno like ?',$capno);
							$accntdetail = $accnt->fetchRow($select)->toArray();
							$this->_helper->AuditTrail($accntdetail,$data4, $capno);
						/** End of Audit Trail **/
						$cidetails->update($data4,$where);				
						}
						//Update the CV Details Base on the Model Basis
							/*if ($formData['model_ci_empver']){
								$model_cv_empver = 0;
							}else{ $model_cv_empver = 1;}
							if ($formData['model_ci_busver']){
								$model_cv_busver = 0;
							}else{ $model_cv_busver = 1;}
							if ($formData['model_ci_trdchk']){
								$model_cv_trdchk = 0;
							}else{ $model_cv_trdchk = 1;}*/
							if ($formData['ci_backgrd2']){
								$model_cv_backgrd = 0;
								$model_ci_backgrd = 1;
							}else{ $model_cv_backgrd = 1;
								   $model_ci_backgrd = 0;	
								}
							$where = "capno like '$capno'";

							$data9 = array(
							'model_ci_backgrd'=>$model_ci_backgrd,
							);
							$cidetails->update($data9,$where);	
							$data5 = array(
							//'model_cv_srcincomever' =>$model_cv_srcincomever,
							//'model_cv_empver'=>$model_cv_empver,
							//'model_cv_busver'=>$model_cv_busver,
							//'model_cv_trdchk'=>$model_cv_trdchk,
							'model_cv_backgrd'=>$model_cv_backgrd,
							);
							$cvdetails = new Model_BorrowerCv();
							
						/**Audit Trail**/
							$accnt = new Model_BorrowerCv();
							$select = $accnt->select();
							$select->where('capno like ?',$capno);
							$accntdetail = $accnt->fetchRow($select)->toArray();
							$this->_helper->AuditTrail($accntdetail,$data5, $capno);
						/** End of Audit Trail **/
							$cvdetails->update($data5,$where);	
						// End of Update
							
				}// End of Save CI
			
			$this->_redirect('/index/accountedit/cap/'.$capno);
					
			}// End of Coborrower

		} //End of IsValid
	} // End of Request
	
	
	
}

public function addspouseAction(){
	
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/menu.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		
		$this->_helper->viewRenderer('account-new-spouse'); 
		$capno = $this->_getParam('cap');



		
		$listcity = new Model_ListCity();
        $listcitydetail = $listcity->fetchAll();
	    $this->view->listcitydetail = $listcitydetail;
	
		$form = new Form_AccountPage();
		$this->view->form = $form;
		$this->view->origcapno = capnosep($capno).'0'.capnorecon($capno);
	
		
	if ($this->getRequest()->isPost()) {
    $formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)) {
	    	
			$capno = $this->_getParam('cap');
			$newcapno = capnospcoGen($capno, 'Spouse');
			
			if ($form->getValue('landline')){
			$landline = 1;}else {$landline = 0;}
			
			$data = array(
			'capno' => $newcapno,
			'borrower_lname' => strtoupper($form->getValue('borrower_lname')),
			'borrower_fname' => strtoupper($form->getValue('borrower_fname')),
			'borrower_mname' => strtoupper($form->getValue('borrower_mname')),
			
			//'pres_address_no' => strtoupper($form->getValue('borrower_pres_address_no')),
			'pres_address_st' => strtoupper($form->getValue('borrower_pres_address_st')),
			'pres_address_brgy' => strtoupper($form->getValue('borrower_pres_address_brgy')),
			'pres_address_city' => $form->getValue('borrower_pres_address_city'),
			'pres_address_province' => strtoupper($formData['borrower_pres_address_province']),
			'pres_zipcode' => $form->getValue('pres_zipcode'),
			
			//'prev_address_no' => strtoupper($form->getValue('borrower_prev_address_no')),
			'prev_address_st' => strtoupper($form->getValue('borrower_prev_address_st')),
			'prev_address_brgy' => strtoupper($form->getValue('borrower_prev_address_brgy')),
			'prev_address_city' => $form->getValue('borrower_prev_address_city'),
			'prev_address_province' => strtoupper($formData['borrower_prev_address_province']),
			'prev_zipcode' => $form->getValue('prev_zipcode'),
			
			
			'landline' => $form->getValue('landline'),			
			'mobile' => $form->getValue('mobile'),
			'email' => $form->getValue('email'),
			'tin_id' => $form->getValue('tin_id'),
			'gender' => $form->getValue('gender'),

			'residence_yrs' => $form->getValue('lenghtstay'),
			'residence_months' => $form->getValue('lenght_months'),
			'residence_yrs_prev' => $form->getValue('lenghtstay_prev'),
			'residence_type_prev' => $form->getValue('residencetype_prev'),
			'residence_months_prev' => $form->getValue('lenght_months_prev'),
		
			'residence_type' => $form->getValue('residencetype'),
			'neighborhoodtype'=>$form->getValue('neighborhoodtype'),
			
			'birthdate' => $form->getValue('birthdate'),
			'birthplace' =>$form->getValue('birthplace'),
			'maiden_name' =>$form->getValue('maiden_name'),
			'citizenship' =>$form->getValue('citizenship'),
			'tel_avail' => $landline,
			'age'=>trim(round(dateDiff("/", date("m/d/Y", time()), $form->getValue('birthdate'))/365, 0),'.'),
			'age'=>$form->getValue('age'),			
			'relation' =>'Spouse',
			'created_by'=>login_user(),
			);	
			    $spouse = new Model_BorrowerAccount();
				$spouse->insert($data); 
				
			/**Audit Trail**/
			$this->_helper->AuditTrail->add($data,$newcapno);
			/** End of Audit Trail **/	
				
						$data3 = array(
						'capno'=>$newcapno,						
						);
						$cvdetails = new Model_BorrowerCv();
						$cvdetails->insert($data3);	
			

				
				$this->_redirect('/index/accountedit/cap/'.$newcapno);
		} //End of IsValid
	} // End of Request
} // End of Add Spouse Action

public function addcoborrowerAction(){
	
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
        $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
	
		$this->_helper->viewRenderer('account-new-coborrower'); 
		$capno = $this->_getParam('cap');

		$listcity = new Model_ListCity();
        $listcitydetail = $listcity->fetchAll();
	    $this->view->listcitydetail = $listcitydetail;
	
		$form = new Form_AccountPage();
		$this->view->form = $form;
		$this->view->origcapno = capnosep($capno).'0'.capnorecon($capno);
		//Get the Coborrower List to be Rendered in View Form	
		$tables = new Model_BorrowerAccount();
	     $sql2 = $tables->select()
		->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where('relation LIKE ?','Coborrower')
		->order("capno ASC");	     
	 	foreach ($tables->fetchAll($sql2,"id ASC") as $c) {
         $form->coborrower_to->addMultiOption($c->capno, $c->borrower_lname.' '.$c->borrower_fname); }
         //$coborrower_extend->addMultiOption('Others', 'Others'); }
		
	if ($this->getRequest()->isPost()) {
    $formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)) {
	    	
			$capno = $this->_getParam('cap');
			
			if($form->getValue('coborrower_select') == 'main' || 
			$form->getValue('coborrower_select') == 'comaker'
			){
			$newcapno = capnospcoGen($capno, 'Coborrower');
			}else {
			$newcapno = capnocoexGen($form->getValue('coborrower_to'));	
			}
			
			if($form->getValue('coborrower_select') == 'comaker'){
				$cobomake = 'Co-Maker';
			}else {
				$cobomake = 'Coborrower';
			}
			if ($form->getValue('landline')){
			$landline = 1;}else {$landline = 0;}
			
			$data = array(
			'capno' => $newcapno,
			'borrower_lname' => strtoupper($form->getValue('borrower_lname')),
			'borrower_fname' => strtoupper($form->getValue('borrower_fname')),
			'borrower_mname' => strtoupper($form->getValue('borrower_mname')),
			
			'pres_address_st' => strtoupper($form->getValue('borrower_pres_address_st')),
			'pres_address_brgy' => $form->getValue('borrower_pres_address_brgy'),
			'pres_address_city' => $form->getValue('borrower_pres_address_city'),
			'pres_address_province' => $formData['borrower_pres_address_province'],
			'pres_zipcode' => $form->getValue('pres_zipcode'),
			
			'prev_address_st' => strtoupper($form->getValue('borrower_prev_address_st')),
			'prev_address_brgy' => $form->getValue('borrower_prev_address_brgy'),
			'prev_address_city' => $form->getValue('borrower_prev_address_city'),
			'prev_address_province' => $formData['borrower_prev_address_province'],
			'prev_zipcode' => $form->getValue('prev_zipcode'),
			
			
				
			'landline' => $form->getValue('landline'),			
			'mobile' => $form->getValue('mobile'),
			'email' => $form->getValue('email'),
			'tin_id' => $form->getValue('tin_id'),
			'gender' => $form->getValue('gender'),
			'civilstatus' => $form->getValue('civilstatus'),
			
			'residence_yrs' => $form->getValue('lenghtstay'),
			'residence_months' => $form->getValue('lenght_months'),
			'residence_yrs_prev' => $form->getValue('lenghtstay_prev'),
			'residence_type_prev' => $form->getValue('residencetype_prev'),
			'residence_months_prev' => $form->getValue('lenght_months_prev'),
		
			
			'residence_type' => $form->getValue('residencetype'),
			'neighborhoodtype'=>$form->getValue('neighborhoodtype'),
			
			'dependentno' => $form->getValue('dependentno'),
			'birthdate' => $form->getValue('birthdate'),
			'birthplace' =>$form->getValue('birthplace'),
			'maiden_name' =>$form->getValue('maiden_name'),
			'citizenship' =>$form->getValue('citizenship'),
			'tel_avail' => $landline,
			//'age'=>trim(round(dateDiff("/", date("m/d/Y", time()), $form->getValue('birthdate'))/365, 0),'.'),
			'age'=> $form->getValue('age'),						
			'relation' =>$cobomake,
			'coborrower_type'=> $form->getValue('coborrower_select'),
			'coborrower_relation'=> $form->getValue('coborrower_relation'),
			'coborrower_extend_relation'=>$form->getValue('coborrower_extend'),
			'coborrower_extend_capno'=>$form->getValue('coborrower_to'),
			'created_by'=>login_user(),		
			);	
			
			
			    $coborrower = new Model_BorrowerAccount();
				$coborrower->insert($data); 
					/**Audit Trail**/
					$this->_helper->AuditTrail->add($data,$newcapno);
					/** End of Audit Trail **/
				
						$data3 = array(
						'capno'=>$newcapno,						
						);
						$cvdetails = new Model_BorrowerCv();
						$cvdetails->insert($data3);	
						
				$this->_redirect('/index/accountedit/cap/'.$newcapno);
		} //End of IsValid
	} // End of Request
} // End of Add Coborrower Action

public function searchAction(){
		Zend_Layout::getMvcInstance()->assign('usedojo', 'true');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/table-style.css');

	
	$form = new Form_Search();
	foreach($form->getElements() as $element) {
	$element->removeDecorator('DtDdWrapper');
	$element->removeDecorator('Label');
	}
	$this->view->form = $form;
	
	if ($this->getRequest()->isPost()) {
    $formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)) {     
		
		$startdate = $form->getValue('startdate');
		if(!$startdate) { $startdate = '1999-12-30'; } 
		$enddate = $form->getValue('enddate');
		if(!$enddate) { $enddate = '2999-12-30'; } 
		
		$capno = $form->getValue('capno');
		$borrower_lname = strtoupper($form->getValue('borrower_lname'));
		$ma = $form->getValue('marketingassistant');
		$ca = $form->getValue('creditanalyst');
		$source = $form->getValue('source_application');
		$decision = $form->getValue('decision');
		$sortby = $form->getValue('sortby');
	
		$name_arr = explode(',',$borrower_lname);
		$table = new Model_BorrowerAccount;
		//for marketing
		//$user = Zend_Auth::getInstance()->getIdentity();
		$select = $table->select();

			$select->where('capno like ?',$capno.'%')
			->where('borrower_lname like ?',$name_arr[0].'%')
			//->where('application_date >= ?', $startdate)->where('application_date <= ?', $enddate)
			->where('relation like ?', 'Principal'); // Spouse Add

			if($name_arr[1]){
			$name_arr[1] = str_replace(' ','',$name_arr[1]);

			$select->where('borrower_fname like ?',$name_arr[1].'%');
			}

			if($startdate == $enddate){
				$enddate = $startdate." 24:00:00";
				$startdate = $startdate." 00:00:00";
				
			$select->where("application_date between '$startdate'  and '$enddate'");

			}else {
			$startdate = $startdate." 01:00:00";	// of the morning 
			$enddate = $enddate." 24:00:00";	 // of the evening
			$select->where("application_date between '$startdate' and '$enddate'");
			
			}
			
			if($ma){
			$select->where('created_by like ?',$ma.'%');
			}
			if($ca){
			$select->where('submitted_ca like ?',$ca.'%');				
			}
			if($source){
			$select->where('source_application like ?',$source.'%');				
			}
			if($decision){
			$select->where('account_status like ?',$decision.'%');				
			}
			

				

			if($sortby == 1){
			$select->order('borrower_lname');
			}
			
			
				
		$rowsDetail = $table->fetchAll($select);

		$this->view->rowResult = $rowsDetail;
		$this->view->totalaccounts = $rowsDetail->count();
		
		/*
		$rowsDetail = $table->fetchAll($select);
		$page=$this->_getParam('page',1);
    	$paginator = Zend_Paginator::factory($rowsDetail);
    	$paginator->setItemCountPerPage(20);
    	$paginator->setCurrentPageNumber($page);
    	$this->view->rowResult=$paginator;	
		*/


		} //End of IsValid
	} // End of Request
} // End of searchAction


public function employmentAction(){

		
	$capno = $this->_getParam('cap');	
	
	$this->view->capno = $capno;
	$page = $this->_getParam('page');	
	
	if($page == ''){
	$employment = new Model_BorrowerEmployment();
	$business = new Model_BorrowerBusiness();
	$trdbusref = new Model_BorrowerObTrdBusRef();
	$othermonthly = new Model_BorrowerIncomeMonthly();
	$othersource = new Model_BorrowerIncomeSource();
	}
	else if ($page == 'ma'){
	$employment = new Model_BorrowerEmploymentMa();
	$business = new Model_BorrowerBusinessMa();
	$trdbusref = new Model_BorrowerObTrdBusRefMa();
	$othermonthly = new Model_BorrowerIncomeMonthlyMa();
	$othersource = new Model_BorrowerIncomeSourceMa();
	}
	else if ($page == 'ca'){
	$employment = new Model_BorrowerEmploymentCa();
	$business = new Model_BorrowerBusinessCa();
	$trdbusref = new Model_BorrowerObTrdBusRefCa();
	$othermonthly = new Model_BorrowerIncomeMonthlyCa();
	$othersource = new Model_BorrowerIncomeSourceCa();
	}
	
	//Fetch Employment
	$select = $employment->select();
	$select->where('capno like ?',$capno);
	$empdetail = $employment->fetchAll($select);
	$this->view->empdetail = $empdetail;
	
	//Fetch Business
	$select = $business->select();
	$select->where('capno like ?', $capno);
	$busdetail = $business->fetchAll($select);
	$this->view->busdetail = $busdetail;
	
	//Fetch Trade and Business References
	$select = $trdbusref->select();
	$select->where('capno like ?',$capno);
	$trdbusrefdetail = $trdbusref->fetchAll($select);
	$this->view->trdbusrefdetail = $trdbusrefdetail;
	
	//Fetch Other Monthly Income
	$select = $othermonthly->select();
	$select->where('capno like ?',$capno);
	$othermonthlydetail = $othermonthly->fetchAll($select);
	$this->view->othermonthlydetail = $othermonthlydetail;
	
	//Fetch Other Source Income
	$select = $othersource->select();
	$select->where('capno like ?',$capno);
	$othersourcedetail = $othersource->fetchAll($select);
	$this->view->othersourcedetail = $othersourcedetail;

	$this->view->capno = $capno;

	
} // End of Employment Action

public function employmenteditAction(){
		Zend_Layout::getMvcInstance()->assign('usedojo', 'true');

		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
	
		$this->_helper->viewRenderer('employment-edit');
	
		$capno = $this->_getParam('cap');	
		$accnt = new Model_BorrowerAccount();
		$this->_helper->RolePrivileges($accnt->getMainCapno($capno));
	
		$this->view->capno = $capno;
		$select = $accnt->select();
		$select->where('capno like ?',$capno);
		$accntdetail = $accnt->fetchRow($select);

		//$relation = $this->_getParam('relation');	
		$this->view->relation = $accntdetail->relation;


		
		$employment = new Model_BorrowerEmployment();
		$select = $employment->select();
		$select->where('capno like ?',$capno);
		$empdetail = $employment->fetchAll($select);
		$this->view->empdetail = $empdetail;
	
		$business = new Model_BorrowerBusiness();
		$select = $business->select();
		$select->where('capno like ?', $capno);
		$busdetail = $business->fetchAll($select);
		$this->view->busdetail = $busdetail;
		
		//Fetch Trade and Business References
		$trdbusref = new Model_BorrowerObTrdBusRef();
		$select = $trdbusref->select();
		$select->where('capno like ?',$capno);
		$trdbusrefdetail = $trdbusref->fetchAll($select);
		$this->view->trdbusrefdetail = $trdbusrefdetail;
			
		//Fetch Other Monthly Income
		$othermonthly = new Model_BorrowerIncomeMonthly();
		$select = $othermonthly->select();
		$select->where('capno like ?',$capno);
		$othermonthlydetail = $othermonthly->fetchAll($select);
		$this->view->othermonthlydetail = $othermonthlydetail;
		
		//Fetch Other Source Income
		$othersource = new Model_BorrowerIncomeSource();
		$select = $othersource->select();
		$select->where('capno like ?',$capno);
		$othersourcedetail = $othersource->fetchAll($select);
		$this->view->othersourcedetail = $othersourcedetail;
			
		$this->view->capno = $capno;
	
		$form = new Form_Employment();
		$this->view->form = $form; 
		
		if ($this->getRequest()->isPost()) {
	    $formData = $this->getRequest()->getPost();
		    if ($form->isValid($formData)) {  
			
			$capno = $this->_getParam('cap');	
			$relation = $this->_getParam('relation');
			 if ($formData['submit'] == 'Add Employment'){
			 	
				$table = new Model_BorrowerEmployment();
				
				if (!$form->getValue('dateresigned')){
					$dateresigned = null;}
				else {
					$dateresigned = $form->getValue('dateresigned');
				}
					
				$data = array(
				'capno' => $capno,
				'relation' =>$accntdetail->relation,
				'employer' => $form->getValue('employer'),
				'date_resigned' => $dateresigned,
				'emp_name' => $form->getValue('emp_name'),
				'emp_industry' => $form->getValue('emp_industry'),
				//'emp_address' => $form->getValue('emp_address'),
				//'emp_no' => $form->getValue('borrower_prev_address_no'),
				'emp_street' => $form->getValue('borrower_prev_address_street'),
				'emp_brgy' => $form->getValue('borrower_prev_address_brgy'),
				'emp_city'=> $form->getValue('borrower_prev_address_city'),
				'emp_province'=>$form->getValue('borrower_prev_address_province'),
				'emp_zipcode' =>$form->getValue('emp_zipcode'),
				'emp_date'=>$form->getValue('emp_date'),
				'emp_telno' => $form->getValue('emp_telno'),
				'emp_pos' => $form->getValue('emp_pos'),
				'emp_status' => $form->getValue('emp_status'),
				'emp_yrs' => $form->getValue('emp_yrs'),
				'emp_months' =>$form->getValue('emp_months'),
				'emp_income' => moneyconvert($form->getValue('emp_income')),
				'emp_gsiss' => $form->getValue('emp_gsiss'),
				'emp_source_date' => $form->getValue('emp_source_date'),
				'emp_actual_position' => $form->getValue('emp_actual_position'),
				'emp_annual'=>$form->getValue('emp_annual'),
				'emp_multiplier'=>$form->getValue('emp_multiplier'),		
				);
				

				$table->insert($data);
					/**Audit Trail**/
					$this->_helper->AuditTrail->add($data,$capno);
					/** End of Audit Trail **/
				
				 }
			 elseif ($formData['submit'] == 'Add Business'){
			 	
				$table = new Model_BorrowerBusiness();
				$data = array(
				'capno' => $capno,
				'relation' => $accntdetail->relation,
				'bus_name'=>$form->getValue('bus_name'),
				//'bus_address'=>$form->getValue('bus_address'),
				//'bus_no' => $form->getValue('borrower_pres_address_no'),
				'bus_street' => $form->getValue('borrower_pres_address_street'),
				'bus_brgy' => $form->getValue('borrower_pres_address_brgy'),
				'bus_city'=> $form->getValue('borrower_pres_address_city'),
				'bus_province'=>$form->getValue('borrower_pres_address_province'),
				'bus_zipcode' =>$form->getValue('bus_zipcode'),
				'bus_telno'=>$form->getValue('bus_telno'),
				//'bus_pos'=>$form->getValue('bus_pos'),
				'bus_date'=>date("Y-m-d",strtotime($form->getValue('bus_date'))),
				'bus_srcincome'=>$form->getValue('bus_srcincome'),
				'bus_yrs'=>$form->getValue('bus_yrs'),
				'bus_months'=>$form->getValue('bus_months'),
				'bus_income'=>moneyconvert($form->getValue('bus_income')),
				'bus_nat'=>$form->getValue('bus_nat'),
				'bus_dti'=>$form->getValue('bus_dti'),		
				'total_gross_sales'	=>moneyconvert($form->getValue('total_gross_sales')),
				'total_net_income_before'=>moneyconvert($form->getValue('total_net_income_before')),
				'total_cost_sales'=>moneyconvert($form->getValue('total_cost_sales')),
				'bus_source_date'=>$form->getValue('bus_source_date'),
				'bus_annual'=>$form->getValue('bus_annual'),
				'bus_multiplier'=>$form->getValue('bus_multiplier'),

				);

				
				$table->insert($data);
					/**Audit Trail**/
					$this->_helper->AuditTrail->add($data,$capno);
					/** End of Audit Trail **/
			 }
			 elseif ($formData['submit'] == 'Add Trade and Business'){
				
				$table = new Model_BorrowerObTrdBusRef();
				
				$data = array(
				'capno' => $capno,
				'relation' => $accntdetail->relation,
				'name' => $form->getValue('name'),
				'contact_person' =>$form->getValue('contactperson'),
				'contact_no' => $form->getValue('contactno'),
				'nature_transaction' =>$form->getValue('nat_transact'),
				);
			 		$table->insert($data);
					/**Audit Trail**/
					$this->_helper->AuditTrail->add($data,$capno);
					/** End of Audit Trail **/
				
				//$this->_redirect('/index/obligationedit/cap/'.$capno);
			}
			 else if ($formData['submit'] == 'Add Other Monthly Income'){
				$table = new Model_BorrowerIncomeMonthly();
					$data = array(
					'capno'=> $capno,
					'relation' => $accnt->getRelation($capno),
					'type'=>$form->getValue('monthly_type'),
					'from'=>$form->getValue('monthly_from'),
					'amount'=>$form->getValue('monthly_amount'),
					'since'=>$form->getValue('monthly_since'),
					'remarks'=>$form->getValue('monthly_remarks'),
					);
					$table->insert($data);
					/**Audit Trail**/
					$this->_helper->AuditTrail->add($data,$capno);
					/** End of Audit Trail **/
			 }
			 
			 else if ($formData['submit'] == 'Add Other Source Income'){
				$table = new Model_BorrowerIncomeSource();
					$data = array(
					'capno'=> $capno,
					'relation' => $accnt->getRelation($capno),
					//'type'=>$form->getValue('monthly_type'),
					'from'=>$form->getValue('source_from'),
					'amount'=>$form->getValue('source_amount'),
					'since'=>$form->getValue('source_since'),
					'remarks'=>$form->getValue('source_remarks'),
					);
					$table->insert($data);

					/**Audit Trail**/
					$this->_helper->AuditTrail->add($data,$capno);
					/** End of Audit Trail **/
			 }
			//Update Total Income And EmpBus status on the Borrower Account
			$chk = chkEmpBusStatus($capno);
			$apptype = chkAppType($chk,$capno);
			$data = array(
			'total_income' => moneyconvert(totalincome($capno)),
			'empbus_status'=> $chk,
			'application_type' => $apptype,
			); 
			
			$accnt2 = new Model_BorrowerAccount();
			$where = "capno like '$capno'";
						/**Audit Trail**/
							$accnt = new Model_BorrowerAccount();
							$select = $accnt->select();
							$select->where('capno like ?',$capno);
							$accntdetail = $accnt->fetchRow($select)->toArray();
							$this->_helper->AuditTrail($accntdetail,$data, $capno);
						/** End of Audit Trail **/
			
			$accnt2->update($data, $where);
			//update GMI
			gmi_ratio($capno);

			//End of Update Total Income
			$this->_redirect('/index/employmentedit/cap/'.$capno);

					} //End of IsValid
		} // End of Request
	
	
	
}

public function editemploymentAction(){
		Zend_Layout::getMvcInstance()->assign('usedojo', 'true');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
	
	$id = $this->_getParam('id');
	$capno = $this->_getParam('cap');
	$this->_helper->viewRenderer('employment-edit-emp');
	$this->view->capno = $capno;
	$form = new Form_Employment();
	$this->view->form = $form;
	
		$employment = new Model_BorrowerEmployment();
		$select = $employment->select();
		$select->where('capno like ?',$capno)->where('id = ?', $id);
		$empdetail = $employment->fetchRow($select);
		

		$form->employer->setValue($empdetail->employer);
		$this->view->employer = $empdetail->employer;
		$form->emp_name->addMultiOption($empdetail->emp_name,$empdetail->emp_name);
		$form->emp_name->setValue($empdetail->emp_name);

		$form->dateresigned->setValue(date('m/d/Y',strtotime($empdetail->date_resigned)));
		$this->view->date_resigned = $empdetail->date_resigned;
		$form->emp_telno->setValue($empdetail->emp_telno);
		$form->emp_industry->setValue($empdetail->emp_industry);
		$form->borrower_prev_address_street->setValue($empdetail->emp_street);
		$form->borrower_prev_address_province->setValue($empdetail->emp_province);
		$this->view->borrower_prev_address_brgy = $empdetail->emp_brgy;
		$this->view->borrower_prev_address_city = $empdetail->emp_city;
		$form->emp_yrs->setValue($empdetail->emp_yrs);
		$form->emp_months->setValue($empdetail->emp_months);
		$form->emp_status->setValue($empdetail->emp_status);
		$form->emp_pos->setValue($empdetail->emp_pos);
		$form->emp_date->setValue(date('m/d/Y',strtotime($empdetail->emp_date)));
		$form->emp_income->setValue($empdetail->emp_income);
		$form->emp_gsiss->setValue($empdetail->emp_gsiss);
		$form->phone_ver->setValue($empdetail->phone_ver);
		$form->emp_source_date->setValue($empdetail->emp_source_date);
		$form->emp_multiplier->setValue($empdetail->emp_multiplier);
		$form->emp_actual_position->setValue($empdetail->emp_actual_position);
		$form->emp_percentage->setValue($empdetail->emp_percentage);
		$form->emp_annual->setValue($empdetail->emp_annual);

		
		
		if ($this->getRequest()->isPost()) {
	    $formData = $this->getRequest()->getPost();
		    if ($form->isValid($formData)) { 

			$id = $this->_getParam('id');
			$capno = $this->_getParam('cap');

			$employer = $form->getValue('employer');
			if($employer == 'Current' ){
				$dater = NULL;
			}else { $dater = $form->getValue('dateresigned'); }

			$data = array(
			'employer'=>$form->getValue('employer'),
			'emp_name'=>$form->getValue('emp_name'),
			'date_resigned'=> $dater,
			'emp_telno'=>$form->getValue('emp_telno'),
			'emp_industry'=>$form->getValue('emp_industry'),
			'emp_street'=>$form->getValue('borrower_prev_address_street'),
			'emp_brgy'=>$form->getValue('borrower_prev_address_brgy'),
			'emp_city'=>$form->getValue('borrower_prev_address_city'),			
			'emp_province'=>$form->getValue('borrower_prev_address_province'),			
			'emp_yrs'=>$form->getValue('emp_yrs'),
			'emp_months'=>$form->getValue('emp_months'),
			'emp_status'=>$form->getValue('emp_status'),
			'emp_pos'=>$form->getValue('emp_pos'),
			'emp_date'=>$form->getValue('emp_date'),
			'emp_income'=>$form->getValue('emp_income'),
			'emp_gsiss'=>$form->getValue('emp_gsiss'),
			'phone_ver'=>$form->getValue('phone_ver'),
			'emp_source_date'=>$form->getValue('emp_source_date'),
			'emp_multiplier'=>$form->getValue('emp_multiplier'),
			'emp_actual_position'=>$form->getValue('emp_actual_position'),
			'emp_percentage'=>$form->getValue('emp_percentage'),
			'emp_annual'=>$form->getValue('emp_annual')
			);
			
  	    $where = $employment->getAdapter()->quoteInto('id = ?', $id);
		
			/**Audit Trail**/
				$table = new Model_BorrowerEmployment();
				$select = $table->select();
				$select->where('id = ?',$id);
				$accntdetail = $table->fetchRow($select)->toArray();
				$this->_helper->AuditTrail($accntdetail,$data, $capno);
			/** End of Audit Trail **/
		
		$table->update($data,$where);
		
			//Update Total Income And EmpBus status on the Borrower Account
			$chk = chkEmpBusStatus($capno);
			$apptype = chkAppType($chk,$capno);
			$data = array(
			'total_income' => moneyconvert(totalincome($capno)),
			'empbus_status'=> $chk,
			'application_type' => $apptype,
			); 
			$accnt2 = new Model_BorrowerAccount();
			$where = "capno like '$capno'";
						/**Audit Trail**/
							$accnt = new Model_BorrowerAccount();
							$select = $accnt->select();
							$select->where('capno like ?',$capno);
							$accntdetail = $accnt->fetchRow($select)->toArray();
							$this->_helper->AuditTrail($accntdetail,$data, $capno);
						/** End of Audit Trail **/
			
			$accnt2->update($data, $where);
			//End of Update Total Income
			//Update GMI
			gmi_ratio($capno);
		$this->_redirect('/index/employmentedit/cap/'.$capno);			
			}
		}

}

public function editbusinessAction(){
		Zend_Layout::getMvcInstance()->assign('usedojo', 'true');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		
	$id = $this->_getParam('id');
	$capno = $this->_getParam('cap');
	$this->_helper->viewRenderer('employment-edit-bus');
	$this->view->capno = $capno;

	$form = new Form_Employment();
	$this->view->form = $form;
	
		$business = new Model_BorrowerBusiness();
		$select = $business->select();
		$select->where('capno like ?',$capno)->where('id = ?', $id);
		$busdetail = $business->fetchRow($select);
	
		$form->bus_name->setValue($busdetail->bus_name);
		$form->bus_nat->setValue($busdetail->bus_nat);
		$form->bus_telno->setValue($busdetail->bus_telno);
		$form->borrower_pres_address_street->setValue($busdetail->bus_street);
		$this->view->borrower_pres_address_brgy = $busdetail->bus_brgy;
		$this->view->borrower_pres_address_city = $busdetail->bus_city;
		$form->bus_zipcode->setValue($busdetail->bus_zipcode);
		$form->borrower_pres_address_province->setValue($busdetail->bus_province);
		$form->bus_yrs->setValue($busdetail->bus_yrs);	
		$form->bus_months->setValue($busdetail->bus_months);
		$form->bus_srcincome->setValue($busdetail->bus_srcincome);
		$form->bus_date->setValue(date('m/d/Y',strtotime($busdetail->bus_date)));		
		$form->bus_income->setValue($busdetail->bus_income);	
		$form->total_gross_sales->setValue($busdetail->total_gross_sales);
		$form->total_cost_sales->setValue($busdetail->total_cost_sales);
		$form->total_net_income_before->setValue($busdetail->total_net_income_before);
		$form->phone_ver->setValue($busdetail->phone_ver);
		$form->bus_source_date->setValue($busdetail->bus_source_date);
		$form->bus_multiplier->setValue($busdetail->bus_multiplier);
		$form->bus_annual->setValue($busdetail->bus_annual);

		if ($this->getRequest()->isPost()) {
	    $formData = $this->getRequest()->getPost();
		    if ($form->isValid($formData)) { 
			
			$id = $this->_getParam('id');
			$capno = $this->_getParam('cap');

			$data = array(
			'bus_name' => $form->getValue('bus_name'),
			'bus_nat' =>$form->getValue('bus_nat'),
			'bus_telno' =>$form->getValue('bus_telno'),
			'bus_street' =>$form->getValue('borrower_pres_address_street'),
			'bus_city' =>$form->getValue('borrower_pres_address_city'),
			'bus_brgy' =>$form->getValue('borrower_pres_address_brgy'),
			'bus_province' =>$form->getValue('borrower_pres_address_province'),
			'bus_zipcode' =>$form->getValue('bus_zipcode'),
			'bus_yrs' =>$form->getValue('bus_yrs'),
			'bus_months' =>$form->getValue('bus_months'),
			'bus_srcincome' =>$form->getValue('bus_srcincome'),
			'bus_income' => $form->getValue('bus_income'),
			'bus_date' =>$form->getValue('bus_date'),
			'bus_months' =>$form->getValue('bus_months'),
			'total_gross_sales' =>$form->getValue('total_gross_sales'),
			'total_cost_sales' =>$form->getValue('total_cost_sales'),
			'total_net_income_before' =>$form->getValue('total_net_income_before'),
			'phone_ver'=>$form->getValue('phone_ver'),
			'bus_source_date'=>$form->getValue('bus_source_date'),
			'bus_multiplier'=>$form->getValue('bus_multiplier'),
			'bus_annual'=>$form->getValue('bus_annual')

			);
  	   		 $where = $business->getAdapter()->quoteInto('id = ?', $id);
		
			/**Audit Trail**/ 
				$table = new Model_BorrowerBusiness();
				$select = $table->select();
				$select->where('id = ?',$id);
				$accntdetail = $table->fetchRow($select)->toArray();
				$this->_helper->AuditTrail($accntdetail,$data, $capno);
			/** End of Audit Trail **/
		
		$table->update($data,$where);
		
			//Update Total Income And EmpBus status on the Borrower Account
			$chk = chkEmpBusStatus($capno);
			$apptype = chkAppType($chk,$capno);
			$data = array(
			'total_income' => moneyconvert(totalincome($capno)),
			'empbus_status'=> $chk,
			'application_type' => $apptype,
			); 
			$accnt2 = new Model_BorrowerAccount();
			$where = "capno like '$capno'";
						/**Audit Trail**/
							$accnt = new Model_BorrowerAccount();
							$select = $accnt->select();
							$select->where('capno like ?',$capno);
							$accntdetail = $accnt->fetchRow($select)->toArray();
							$this->_helper->AuditTrail($accntdetail,$data, $capno);
						/** End of Audit Trail **/
			
			$accnt2->update($data, $where);
			//End of Update Total Income
			//Update GMI
		gmi_ratio($capno);
		$this->_redirect('/index/employmentedit/cap/'.$capno);	
			}
		}
}


public function delemploymentAction(){
    $this->_helper->viewRenderer->setNoRender(true);
	$id = $this->_getParam('id');
    $capno = $this->_getParam('cap');
    $employment = new Model_BorrowerEmployment();
    $where = $employment->getAdapter()->quoteInto('id = ?', $id);
   
	/**Audit Trail**/
	$accnt = new Model_BorrowerEmployment();
	$select = $accnt->select();
	$select->where('id = ?',$id);
	$accntdetail = $accnt->fetchRow($select)->toArray();
	$this->_helper->AuditTrail->delete($accntdetail,$capno);
	/** End of Audit Trail **/

    $employment->delete($where);

			//Update Total Income And EmpBus status on the Borrower Account
			$chk = chkEmpBusStatus($capno);
			$apptype = chkAppType($chk,$capno);
			$data = array(
			'total_income' => moneyconvert(totalincome($capno)),
			'empbus_status'=> chkEmpBusStatus($capno),
			'application_type' => $apptype,
			); 
			$accnt = new Model_BorrowerAccount();
			$where = "capno like '$capno'";
			$accnt->update($data, $where);
			//End of Update Total Income
			gmi_ratio($capno);
   $this->_redirect('/index/employmentedit/cap/'.$capno);	
}

public function delbusinessAction(){
    $this->_helper->viewRenderer->setNoRender(true);
	$id = $this->_getParam('id');
    $capno = $this->_getParam('cap');
    $business = new Model_BorrowerBusiness();
    $where = $business->getAdapter()->quoteInto('id = ?', $id);
    
		/**Audit Trail**/
		$accnt = new Model_BorrowerBusiness();
		$select = $accnt->select();
		$select->where('id = ?',$id);
		$accntdetail = $accnt->fetchRow($select)->toArray();
		$this->_helper->AuditTrail->delete($accntdetail,$capno);
		/** End of Audit Trail **/
		$business->delete($where);
	
			//Update Total Income And EmpBus status on the Borrower Account
			$chk = chkEmpBusStatus($capno);
			$apptype = chkAppType($chk,$capno);		
			$data = array(
			'total_income' => moneyconvert(totalincome($capno)),
			'empbus_status'=> chkEmpBusStatus($capno),
			'application_type' => $apptype,
			); 
			$accnt = new Model_BorrowerAccount();
			$where = "capno like '$capno'";
			$accnt->update($data, $where);
			//End of Update Total Income,
			gmi_ratio($capno);
    $this->_redirect('/index/employmentedit/cap/'.$capno);	
}

public function delmonthlyincomeAction(){
	/**
	 * Paolo Marco Manarang
	 * paolomanarang@gmail.com
	 * March 03,2010
	 * Function for Other Monthly Income for GMI Ratio Compute DEL
	*/
    $this->_helper->viewRenderer->setNoRender(true);
	 $id = $this->_getParam('id');
   	 $capno = $this->_getParam('cap');	

	 $table = new Model_BorrowerIncomeMonthly();	 
     $where = $table->getAdapter()->quoteInto('id = ?', $id);

			/**Audit Trail**/
			$accnt = new Model_BorrowerIncomeMonthly();
			$select = $accnt->select();
			$select->where('id = ?',$id);
			$accntdetail = $accnt->fetchRow($select)->toArray();
			$this->_helper->AuditTrail->delete($accntdetail,$capno);
			/** End of Audit Trail **/
	$table->delete($where);
	
			//Update Total Income And EmpBus status on the Borrower Account
			$chk = chkEmpBusStatus($capno);
			$apptype = chkAppType($chk,$capno);		
			$data = array(
			'total_income' => moneyconvert(totalincome($capno)),
			'empbus_status'=> chkEmpBusStatus($capno),
			'application_type' => $apptype,
			); 
			$accnt = new Model_BorrowerAccount();
			$where = "capno like '$capno'";
			$accnt->update($data, $where);
			//End of Update Total Income,
			gmi_ratio($capno);
	

    $this->_redirect('/index/employmentedit/cap/'.$capno);	

}

public function delsourceincomeAction(){
	/**
	 * Paolo Marco Manarang
	 * paolomanarang@gmail.com
	 * March 03,2010
	 * Function for Other Monthly Income for GMI Ratio Compute DELETE
	*/
	
    $this->_helper->viewRenderer->setNoRender(true);
	 $id = $this->_getParam('id');
   	 $capno = $this->_getParam('cap');	

		 $table = new Model_BorrowerIncomeSource();
	     $where = $table->getAdapter()->quoteInto('id = ?', $id);

			/**Audit Trail**/
			$accnt = new Model_BorrowerIncomeSource();
			$select = $accnt->select();
			$select->where('id = ?',$id);
			$accntdetail = $accnt->fetchRow($select)->toArray();
			$this->_helper->AuditTrail->delete($accntdetail,$capno);
			/** End of Audit Trail **/
	$table->delete($where);
	
    $this->_redirect('/index/employmentedit/cap/'.$capno);	

}



public function obligationAction(){

	
	$capno = $this->_getParam('cap');	
	$this->view->capno = $capno;
	$page = $this->_getParam('page');
	
	if($page == 'ma'){
	$creditcard = new Model_BorrowerObCreditCardMa();
	$loan = new Model_BorrowerObExistLoanMa();
	$bfliabilities = new Model_BorrowerObBfLiabilitiesCa();
	}else if($page == 'ca'){
	$creditcard = new Model_BorrowerObCreditCardCa();
	$loan = new Model_BorrowerObExistLoanCa();
	$bfliabilities = new Model_BorrowerObBfLiabilitiesCa();
	}else {
	$creditcard = new Model_BorrowerObCreditCard();
	$loan = new Model_BorrowerObExistLoan();
	$bfliabilities = new Model_BorrowerObBfLiabilities();
	
		
	}	

	//Fetch Credit Card Details
	$select = $creditcard->select();
	$select->where('capno like ?',$capno);
	$creditcarddetail = $creditcard->fetchAll($select);
	$this->view->creditcarddetail = $creditcarddetail;

	//Fetch Existing Loans
	$select = $loan->select();
	$select->where('capno like ?',$capno);
	$loandetail = $loan->fetchAll($select);
	$this->view->loandetail = $loandetail;
	
	//Fetch Existing Business Liabilities
	$select = $bfliabilities->select();
	$select->where('capno like ?',$capno);
	$bf_liabilities_detail = $bfliabilities->fetchAll($select);
	$this->view->bf_liabilities_detail = $bf_liabilities_detail;
	

}// End of Obligation Action

public function obligationeditAction(){
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
	
	
		$this->_helper->viewRenderer('obligation-edit');
	
		$capno = $this->_getParam('cap');	
		$this->view->capno = $capno;
	
		//Fetch Credit Card Details
		$creditcard = new Model_BorrowerObCreditCard();
		$select = $creditcard->select();
		$select->where('capno like ?',$capno);
		$creditcarddetail = $creditcard->fetchAll($select);
		$this->view->creditcarddetail = $creditcarddetail;
	
		//Fetch Existing Loans
		$loan = new Model_BorrowerObExistLoan();
		$select = $loan->select();
		$select->where('capno like ?',$capno);
		$loandetail = $loan->fetchAll($select);
		$this->view->loandetail = $loandetail;
		
		//Fetch Business Financial Liabilities
		$bf_liabilities = new Model_BorrowerObBfLiabilities();
		$select = $bf_liabilities->select();
		$select->where('capno like ?',$capno);
		$bf_liabilities_detail = $bf_liabilities->fetchAll($select);
		$this->view->bf_liabilities_detail = $bf_liabilities_detail;

		$form = new Form_Obligation();
		$this->view->form = $form;
	
		if ($this->getRequest()->isPost()) {
	    $formData = $this->getRequest()->getPost();
		    if ($form->isValid($formData)) {  
			
			$capno = $this->_getParam('cap');	

			$accnt = new Model_BorrowerAccount();
			$select = $accnt->select();
			$select->where('capno like ?',$capno);
			$accntdetail = $accnt->fetchRow($select);			
			
			$relation = $accntdetail->relation;
			if ($formData['submit'] == 'Add Credit Card Details'){
			 	
				$table = new Model_BorrowerObCreditCard();
				
				$data = array(
				'capno' => $capno,
				'relation' => $relation,
				'company' => $form->getValue('creditcomp'),
				'limit' => moneyconvert($form->getValue('creditlimit')),
				'expiry_date' => $form->getValue('expiry_date')			
				);
				$table->insert($data);
				$this->_redirect('/index/obligationedit/cap/'.$capno);	
			}
			elseif ($formData['submit'] == 'Add Exist Loan Detail'){
			 	
				$table = new Model_BorrowerObExistLoan();
				
				$data = array(
				'capno' => $capno,
				'relation' => $relation,
				'bank' =>$form->getValue('bank'),
				'facility_type' => $form->getValue('facility_type'),
				'amount' => moneyconvert($form->getValue('amount')),
				'collateral' => $form->getValue('collateral'),
				'monthly_amortization'=>moneyconvert($form->getValue('monthly_amortization')),
				'loan_amount'=>moneyconvert($form->getValue('loan_amount')),
				);	
				$table->insert($data);
				gmi_ratio($capno);
				$this->_redirect('/index/obligationedit/cap/'.$capno);
			}
			
			//save_update business finacial liabilities
			elseif ($formData['submit'] == 'Add Business Liabilities Details'){
			 	
				$table = new Model_BorrowerObBfLiabilities();
				
				$data = array(
				'capno' => $capno,
				'relation' => $relation,
				'business_liability' =>$form->getValue('business_liability'),
				'business_liability_emv' => moneyconvert($form->getValue('business_liability_emv')),
				'business_liability_remarks' => $form->getValue('business_liability_remarks'),
				);	
				$table->insert($data);
				$this->_redirect('/index/obligationedit/cap/'.$capno);
			}

			 
			}// End of isValid
		}// End Get Request
}// End of Obligation Edit Action

public function delbankAction(){
    $this->_helper->viewRenderer->setNoRender(true);
	$id = $this->_getParam('id');
    $capno = $this->_getParam('cap');
    $bank = new Model_BorrowerObBank();
    $where = $bank->getAdapter()->quoteInto('id = ?', $id);
    $bank->delete($where);
    $this->_redirect('/index/otherassetedit/cap/'.$capno);	
}

public function delcreditcardAction(){
    $this->_helper->viewRenderer->setNoRender(true);
	$id = $this->_getParam('id');
    $capno = $this->_getParam('cap');
    $credit = new Model_BorrowerObCreditCard();
    $where = $credit->getAdapter()->quoteInto('id = ?', $id);
    $credit->delete($where);
    $this->_redirect('/index/obligationedit/cap/'.$capno);	
}

public function delexistloanAction(){
	$this->_helper->viewRenderer->setNoRender(true);
	$id = $this->_getParam('id');
    $capno = $this->_getParam('cap');
	$existloan = new Model_BorrowerObExistLoan();
	$where = $existloan->getAdapter()->quoteInto('id = ?', $id);
    $existloan->delete($where);
	gmi_ratio($capno);
    $this->_redirect('/index/obligationedit/cap/'.$capno);	
}

public function deltrdbusrefAction(){
	$this->_helper->viewRenderer->setNoRender(true);
	$id = $this->_getParam('id');
    $capno = $this->_getParam('cap');
	$trdbusref = new Model_BorrowerObTrdBusRef();
	$where = $trdbusref->getAdapter()->quoteInto('id = ?', $id);
    $trdbusref->delete($where);
    $this->_redirect('/index/obligationedit/cap/'.$capno);	
}



public function delbfliabilitiesAction(){
	$this->_helper->viewRenderer->setNoRender(true);
	$id = $this->_getParam('id');
	$capno = $this->_getParam('cap');
	$bf_liabilities= new Model_BorrowerObBfLiabilities();
	$where = $bf_liabilities->getAdapter()->quoteInto('id = ?', $id);
    $bf_liabilities->delete($where);
    $this->_redirect('/index/obligationedit/cap/'.$capno);	
}

public function insuranceeditAction(){
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
        $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
	$this->_helper->viewRenderer('insurance-edit'); 

	$form = new Form_Insurance();
    $capno = $this->_getParam('cap');

	

	
		$table = new Model_BorrowerInsurancePolicy();
		$select = $table->select();
		$select->where('capno like ?',$capno);
		$insurancedetail = $table->fetchRow($select);
		//$this->view->insurancedetail = $insurancedetail;
		
	$form->policy_no->setValue($insurancedetail->policy_no);
	$form->ins_comp->setValue($insurancedetail->ins_comp);
	$form->ins_type->setValue($insurancedetail->ins_type);
	$form->issue_date->setValue((date('m/d/Y',strtotime($insurancedetail->issue_date))));
	$form->pronscured_by->setValue($insurancedetail->pronscured_by);
	$form->payment_terms->setValue($insurancedetail->payment_terms);
	$form->effectivity_date->setValue((date('m/d/Y',strtotime($insurancedetail->effectivity_date))));
	$form->expiry_date->setValue((date('m/d/Y',strtotime($insurancedetail->expiry_date))));
	$form->amount_coverage->setValue($insurancedetail->amount_coverage);
	$form->net_premium->setValue($insurancedetail->net_premium);
	$form->total_premium->setValue(calctotalpremium($capno));




	foreach($form->getElements() as $element) {
	$element->removeDecorator('DtDdWrapper');
	$element->removeDecorator('Label');
	}
	
		$charges = new Model_BorrowerInsuranceCharges();
		$select = $charges->select();
		$select->where('capno like ?',$capno);
		$miscdetail = $charges->fetchAll($select);
		$this->view->miscdetail = $miscdetail;
		
		$perils = new Model_BorrowerInsurancePerils();
		$select = $perils->select();
		$select->where('capno like ?',$capno);
		$perilsdetail = $perils->fetchAll($select);
		$this->view->perilsdetail = $perilsdetail;
	
	$this->view->form = $form;
	$this->view->capno = $capno;
	
	if ($this->getRequest()->isPost()) {
	    $formData = $this->getRequest()->getPost();
		    if ($form->isValid($formData)) {  
		    $capno = $this->_getParam('cap');

		if ($formData['button'] == 'Save'){
		
		$data = array(
		'capno' => $capno,
		'policy_no' => $form->getValue('policy_no'),
		'ins_comp' => $form->getValue('ins_comp'),
		'ins_type' => $form->getValue('ins_type'),
		'issue_date' => $form->getValue('issue_date'),
		'pronscured_by' => $form->getValue('pronscured_by'),
		'payment_terms' => $form->getValue('payment_terms'),
		'effectivity_date' => $form->getValue('effectivity_date'),
		'expiry_date' => $form->getValue('expiry_date'),
		'amount_coverage' => moneyconvert($form->getValue('amount_coverage')),
		'net_premium' => moneyconvert($form->getValue('net_premium')),
		'total_premium' => moneyconvert($form->getValue('total_premium')),
		
		);
		$table = new Model_BorrowerInsurancePolicy();
		$sql2 = $table->select()->where('capno LIKE ?',$capno);

				if($table->fetchAll($sql2)->count() == 0){
				$table->insert($data);	
				//if no record found insert data
				}
				else{
				//else update using the capno
				$where = "capno like '$capno'";
				$table->update($data,$where);						
				}	
			}
	else if($formData['button2'] == 'Submit'){
		
		$data = array(
		'capno'=>$capno,
		'desc' =>$form->getValue('desc'),
		'coverage'=>$form->getValue('coverage'),
		'net_premium'=>moneyconvert($form->getValue('net_premium2')),
		'net_commision'=>moneyconvert($form->getValue('net_commision')),
		);
		$table = new Model_BorrowerInsurancePerils();
		$table->insert($data);	

	}

	//else if($formData['button'] == 'Add'){
		else {	
		// if the button save is ADd for aaading Misc. Charges
			$data = array(
			'capno'=>$capno,
			'misce_charges'=>moneyconvert($form->getValue('misce_charges')),
			);
			$table = new Model_BorrowerInsuranceCharges();
			$table->insert($data);	
			}

	   $this->_redirect('/index/insuranceedit/cap/'.$capno);	

		
		
		}// End of Isvalid
	}// End of Action
	
	
	
	
}

public function craweditAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('craw-edit'); 
	/**Deprecated Function of Craw 
	 * Paolo Marco Manarang 
	 * Jan-01-2010 
	 * 
	*/
	$form = new Form_Craw();
	$capno = $this->_getParam('cap');
	

		
		$table = new Model_BorrowerCraw();
		$select = $table->select();
		$select->where('capno like ?',$capno);
		$crawdetail = $table->fetchRow($select);
		//$this->view->crawdetail = $crawdetail;
		
		$form->loan_purpose->setValue($crawdetail->loan_purpose);
		$form->solicitation->setValue($crawdetail->solicitation);
		$form->dealer_agent->setValue($crawdetail->dealer_agent);
		$form->branch_referror->setValue($crawdetail->branch_referror);
		$form->effective_yield->setValue(moneyformat($crawdetail->effective_yield));
		$form->balloon_amount->setValue(moneyformat($crawdetail->balloon_amount));
		$form->loan_remarks->setValue($crawdetail->loan_remarks);
		$form->applied_to_loanVal->setValue(moneyformat($crawdetail->applied_to_loanVal));
		$form->loan_to_ValCap->setValue(moneyformat($crawdetail->loan_to_ValCap));
		$form->financing_scheme->setValue($crawdetail->financing_scheme);
		$form->source_info->setValue($crawdetail->source_info);
		$form->collateral_remarks->setValue($crawdetail->collateral_remarks);
		$form->dealer_incentive->setValue(moneyformat($crawdetail->dealer_incentive));
		$form->total_trust->setValue(moneyformat($crawdetail->total_trust));
		$form->total_subsidiaries->setValue(moneyformat($crawdetail->total_subsidiaries));
		$form->total_related_accounts->setValue(moneyformat($crawdetail->total_related_accounts));
		$form->total_assets->setValue(moneyformat($crawdetail->total_assets));
		$form->total_liabilities->setValue(moneyformat($crawdetail->total_liabilities));
		$form->total_networth->setValue(moneyformat($crawdetail->total_networth));
		$form->busfinancial_remarks->setValue($crawdetail->busfinancial_remarks);
		$form->total_gross_sales->setValue(moneyformat($crawdetail->total_gross_sales));
		$form->total_netbefore_tax->setValue(moneyformat($crawdetail->total_netbefore_tax));
		$form->total_netafter_tax->setValue(moneyformat($crawdetail->total_netafter_tax));
		$form->recommendation_remarks->setValue($crawdetail->recommendation_remarks);
		
		$this->view->form=$form;
		$this->view->capno = $capno;
		$accnt = new Model_BorrowerAccount();
		$this->view->isComaker = $accnt->isComaker($capno);
		foreach($form->getElements() as $element) {
		$element->removeDecorator('DtDdWrapper');
		$element->removeDecorator('Label');
		}
				
				if ($this->getRequest()->isPost()) {
				$formData = $this->getRequest()->getPost();
					if ($form->isValid($formData)) {  
					$capno = $this->_getParam('cap');

		
					$data = array(
					'capno' => $capno,
					'loan_purpose' => $form->getValue('loan_purpose'),
					'solicitation' => $form->getValue('solicitation'),
					'dealer_agent' => $form->getValue('dealer_agent'),
					'branch_refferror' => $form->getValue('branch_refferror'),
					'effective_yield' => $form->getValue('effective_yield'),
					'balloon_amount' => moneyconvert($form->getValue('balloon_amount')),
					'loan_remarks' => $form->getValue('loan_remarks'),
					'applied_to_loanVal' => $form->getValue('applied_to_loanVal'),
					'loan_to_ValCap' => moneyconvert($form->getValue('loan_to_ValCap')),
					'financing_scheme' => $form->getValue('financing_scheme'),
					'source_info' => $form->getValue('source_info'),
					'collateral_remarks' => $form->getValue('collateral_remarks'),
					'dealer_incentive' => $form->getValue('dealer_incentive'),
					'total_trust' => moneyconvert($form->getValue('total_trust')),
					'total_subsidiaries' => moneyconvert($form->getValue('total_subsidiaries')),
					'total_related_accounts' => $form->getValue('total_related_accounts'),
					'total_assets' => moneyconvert($form->getValue('total_assets')),
					'total_liabilities' => moneyconvert($form->getValue('total_liabilities')),
					'total_networth' => moneyconvert($form->getValue('total_networth')),
					'busfinancial_remarks' => $form->getValue('busfinancial_remarks'),
					'total_gross_sales' => moneyconvert($form->getValue('total_gross_sales')),
					'total_netbefore_tax' => moneyconvert($form->getValue('total_netbefore_tax')),
					'total_netafter_tax' => moneyconvert($form->getValue('total_netafter_tax')),
					'recommendation_remarks' => $form->getValue('recommendation_remarks'),
					);
					$table = new Model_BorrowerCraw();
					$sql = $table->select()->where('capno LIKE ?',$capno);

					if($table->fetchAll($sql)->count() == 0){
					$table->insert($data);	
					//if no record found insert data
					}
					else{
					//else update using the capno
					$where = "capno like '$capno'";
					$table->update($data,$where);						
					}	
				

	    $this->_redirect('/index/crawedit/cap/'.$capno);	
	
		}// End of Isvalid
	}// End of Action
	
		
   } //end of craweditaction

public function delchargesAction(){
	$this->_helper->viewRenderer->setNoRender(true);
	$id = $this->_getParam('id');
    $capno = $this->_getParam('cap');
	$table = new Model_BorrowerInsuranceCharges();
	$where = $table->getAdapter()->quoteInto('id = ?', $id);
    $table->delete($where);
    $this->_redirect('/index/insuranceedit/cap/'.$capno);	
}

public function delperilsAction(){
	$this->_helper->viewRenderer->setNoRender(true);
	$id = $this->_getParam('id');
    $capno = $this->_getParam('cap');
	$table = new Model_BorrowerInsurancePerils();
	$where = $table->getAdapter()->quoteInto('id = ?', $id);
    $table->delete($where);
    $this->_redirect('/index/insuranceedit/cap/'.$capno);	
}

public function masubmitAction(){

			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');

			$this->_helper->viewRenderer('box-confirmation');
			
			
			$form = new Form_PopupBox();
			$this->view->form = $form;
			
			$capno = $this->_getParam('cap');
			$this->_helper->RolePrivileges($capno);
			$action = $this->_getParam('action');
			
			$this->_helper->ChkOnSubmit($capno);
			if ($action == 'submit'){
			$detail = 'Are you submitting this account to Credit Services ?';
			}
			
			
			$this->view->detail = $detail;
			$this->view->capno = $capno;
			
			$wDeviation = $this->_helper->chkDeviation($capno);
		
			$countDev = chkArray($wDeviation);

			$this->view->deviation = $wDeviation;	
						
	
			if ($this->getRequest()->isPost()) {
				$formData = $this->getRequest()->getPost();
					if ($form->isValid($formData)) { 
					
					$capno = $this->_getParam('cap');
					$borrower = new Model_BorrowerAccount();
					
					if($borrower->isComaker($capno)){
					$comakerStatus = 'MA - S - CMK';
					}else{
					$status = 'MA - S';	
					}
					
					//Choose the Model 1st
					$s = $this->_helper->ScoreModel($capno);
					$score = $this->_helper->$s($capno);
			
					$score_tag = $this->view->viewScore($score,getHighest($capno),'score');
					if ($countDev == 0){
					// without deviation
					$data = array(
					'account_status'=>$status,
					'score'=> $score,	
					//'score_tag'=>$score_tag,
					'submitted_ca_date'=>date('r'),
					'deviation' => 'false',
					'comaker_accnt_status'=>$comakerStatus,
					);
					$where = "capno like '$capno'";
					$borrower->update($data, $where);	
					
					$data2 = array(
					'capno'=>$capno,
					'remarks_downpayment'=>'',
					'remarks_loan_amount'=>'',
					'remarks_veh_age'=>'',
					'remarks_gmi'=>'',
					'remarks_totalcombine'=>'',
					'remarks_borrower_age'=>'',
					'remarks_residence_yrs'=>'',
					'remarks_employment_yrs'=>'',
					'remarks_employment_status'=>'',
					'remarks_business_yrs'=>'',
					'remarks_citizenship1'=>'',
					'remarks_sell_lcp'=>'',
					'remarks_sell_appraisal'=>'',				
					'remarks_loantermhigh'=>'',
					'remarks_loantermlow'=>'',
					'remarks_nfis'=>'',
					'remarks_cmap'=>'',
					'remarks_veh_yr' => '',
					'remarks_veh_tenor' =>'',
					'remarks_veh_car_history' =>'',
					'remarks_loan_purpose' =>'',
					'remarks_nfis_check'=>'',
					'remarks_ci_favorable'=> '',
					'remarks_ci_check'=>'',		
					'remarks_total_income'=>'',					
								
							
					// Coborrower Deviation
					'remarks_coborrower_age'=>'',
					'remarks_coresidence_yrs'=>'',
					'remarks_coemployment_yrs'=>'',
					'remarks_coemployment_status'=>'',
					'remarks_cobusiness_yrs'=>'',
					'remarks_citizenship2'=>'',
					'remarks_confis'=>'',
					'remarks_cocmap'=>'',	
					'remarks_confis_check'=>'',
					'remarks_coci_favorable'=> '',
					'remarks_coci_check'=>'',
					'remarks_cototal_income'=>'',					

					
					//Spouse Deviation
					'remarks_spouse_age'=>'',
					'remarks_sporesidence_yrs'=>'',
					'remarks_spocitizenship'=>'',
					'remarks_spoemployment_yrs'=>'',
					'remarks_spoemployment_status'=>'',
					'remarks_spobusiness_yrs'=>'',
					'remarks_spnfis'=>'',
					'remarks_spcmap'=>'',
					'remarks_spnfis_check'=>'',
					'remarks_spci_favorable'=>'' ,
					'remarks_spci_check'=>'',
					);	
					
					$deviation = new Model_BorrowerDeviation();
					$sql = $deviation->select()->where('capno LIKE ?',$capno);
		
						if($deviation->fetchAll($sql)->count()== 0){
						$deviation->insert($data2);	
						//if no record found insert data
						}
						else{
						//else update using the capno
						$where = "capno like '$capno'";
						$deviation->update($data2,$where);				
					}	
					
					}//end of Without Deviation
					else {
					//if with deviation
					$data = array(
					'account_status'=>$status,
					'submitted_ca_date'=>date('r'),
					'deviation' => 'true',
					'score'=> $score,
					'comaker_accnt_status'=>$comakerStatus,
					//'score_tag'=>$score_tag,
					);
					$where = "capno like '$capno'";
					$borrower->update($data, $where);				
					$data2 = array(
					'capno'=>$capno,
					'remarks_downpayment'=>$form->getValue('dev_downpayment'),
					'remarks_loan_amount'=>$form->getValue('dev_loan_amount'),
					'remarks_veh_age'=>$form->getValue('dev_veh_age'),
					'remarks_gmi'=>$form->getValue('dev_gmi'),
					'remarks_totalcombine'=>$form->getValue('dev_totalcombine'),
					'remarks_borrower_age'=>$form->getValue('dev_borrower_age'),
					'remarks_residence_yrs'=>$form->getValue('dev_residence_yrs'),
					'remarks_employment_yrs'=>$form->getValue('dev_employment_yrs'),
					'remarks_employment_status'=>$form->getValue('dev_employment_status'),
					'remarks_business_yrs'=>$form->getValue('dev_business_yrs'),
					'remarks_citizenship1'=>$form->getValue('dev_citizenship1'),
					'remarks_sell_lcp'=>$form->getValue('dev_sell_lcp'),
					'remarks_sell_appraisal'=>$form->getValue('dev_sell_appraisal'),			
					'remarks_loantermhigh'=>$form->getValue('dev_loantermhigh'),
					'remarks_loantermlow'=>$form->getValue('dev_loantermlow'),
					
					'remarks_veh_yr' => $form->getValue('dev_veh_yr'),
					'remarks_veh_tenor' => $form->getValue('dev_veh_tenor'),
					'remarks_veh_car_history' =>$form->getValue('dev_veh_car_history'),
					'remarks_loan_purpose' =>$form->getValue('dev_loan_purpose'),
					'remarks_total_income' =>$form->getValue('dev_total_income'),
				
					// Coborrower Deviation
					'remarks_coborrower_age'=>$form->getValue('dev_coborrower_age'),
					'remarks_coresidence_yrs'=>$form->getValue('dev_coresidence_yrs'),
					'remarks_coemployment_yrs'=>$form->getValue('dev_coemployment_yrs'),
					'remarks_coemployment_status'=>$form->getValue('dev_coemployment_status'),
					'remarks_cobusiness_yrs'=>$form->getValue('dev_cobusiness_yrs'),
					'remarks_citizenship2'=>$form->getValue('dev_citizenship2'),
					'remarks_cototal_income' =>$form->getValue('dev_cototal_income'),
					
					//Spouse Deviation
					'remarks_spouse_age'=>$form->getValue('dev_spouse_age'),
					'remarks_spocitizenship'=>$form->getValue('dev_spocitizenship'),
					'remarks_sporesidence_yrs'=>$form->getValue('dev_sporesidence_yrs'),
					'remarks_spoemployment_yrs'=>$form->getValue('dev_spoemployment_yrs'),
					'remarks_spoemployment_status'=>$form->getValue('dev_spoemployment_status'),
					'remarks_spobusiness_yrs'=>$form->getValue('dev_spobusiness_yrs'),
					);
					
					$deviation = new Model_BorrowerDeviation();
					$sql = $deviation->select()->where('capno LIKE ?',$capno);
		
						if($deviation->fetchAll($sql)->count()== 0){
						$deviation->insert($data2);	
						//if no record found insert data
						}
						else{
						//else update using the capno
						$where = "capno like '$capno'";
						$deviation->update($data2,$where);				
						}
				
					}// End of With Deviation

					//Insert Account History
					$history = new Model_AccountHistory();
					$select = $history->select();
					$select->where('capno like ?',$capno)->order('id DESC');
					$historyDetail = $history->fetchRow($select);
			
					$hdata = array (
					'capno'=>$capno,
					'status'=>'MA - S',
					'by'=>login_user(),
					'date'=>date("r"),
					'remarks'=>$form->getValue('submit_remarks'),
					'date_last'=>$historyDetail->date,
					);
					$history->insert($hdata);
					//End of History
					//Copy to borrower MA Table
					
					$this->_helper->CloneData($capno);					
					// insert in report per day
					$this->_helper->Accounts($capno,'MA - S');
					$this->view->isSubmit = "OK";	
					}// End of IsValid
			}// End of GetRequest
}

public function submitAction(){
	// CA submit to CO
	// Function for the CA Submit of the Deviation Box
			$script_start = microtime_float();
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
			
			$form = new Form_PopupBox();
			$this->view->form = $form;
			
			$capno = $this->_getParam('cap');
			$this->_helper->RolePrivileges($capno);
			$action = $this->_getParam('act');
			
			// Check the necessary information before submitting 
			$this->_helper->ChkOnSubmit($capno);
			// End of Check 
			$accnt = new Model_BorrowerAccount();
			$table = new Model_BorrowerDeviation();
			$deviationList = $table->fetchRowCapno($capno);
			//Populate the Box when it has already have remarks on it
			//01-28-2010 by : Paolo Marco Manarang
			$form->dev_downpayment->setValue($deviationList->remarks_downpayment);
			$form->dev_gmi->setValue($deviationList->remarks_gmi);
			$form->dev_loan_purpose->setValue($deviationList->remarks_loan_purpose);
			$form->loan_amount->setValue($deviationList->remarks_loan_amount);
			$form->dev_loantermhigh->setValue($deviationList->remarks_loantermhigh);
			$form->dev_loantermlow->setValue($deviationList->remarks_loantermlow);
			$form->dev_totalcombine->setValue($deviationList->remarks_totalcombine);
			$form->dev_sell_lcp->setValue($deviationList->remarks_sell_lcp);
			$form->dev_sell_appraisal->setValue($deviationList->remarks_sell_appraisal);
			$form->dev_veh_tenor->setValue($deviationList->remarks_veh_tenor);
			$form->dev_veh_car_history->setValue($deviationList->remarks_veh_car_history);
			$form->dev_veh_yr->setValue($deviationList->remarks_veh_yr);
			$form->dev_residence_yrs->setValue($deviationList->remarks_residence_yrs);
			$form->dev_employment_yrs->setValue($deviationList->remarks_employment_yrs);
			$form->dev_employment_status->setValue($deviationList->remarks_employment_status);
			$form->dev_business_yrs->setValue($deviationList->remarks_business_yrs);
			$form->dev_citizenship1->setValue($deviationList->remarks_citizenship1);
			$form->dev_borrower_age->setValue($deviationList->remarks_borrower_age);
			$form->dev_total_income->setValue($deviationList->remarks_total_income);

	
			$form->dev_nfis->setValue($deviationList->remarks_nfis);
			$form->dev_nfis_check->setValue($deviationList->remarks_nfis_check);
			$form->dev_ci_favorable->setValue($deviationList->remarks_ci_favorable);
			$form->dev_ci_check->setValue($deviationList->remarks_ci_check);

			$form->dev_spouse_age->setValue($deviationList->remarks_spouse_age);
			$form->dev_sporesidence_yrs->setValue($deviationList->remarks_sporesidence_yrs);
			$form->dev_spoemployment_yrs->setValue($deviationList->remarks_spoemployment_yrs);
			$form->dev_spoemployment_status->setValue($deviationList->remarks_spoemployment_status);
			$form->dev_spobusiness_yrs->setValue($deviationList->remarks_spobusiness_yrs);
			$form->dev_spocitizenship->setValue($deviationList->remarks_spocitizenship);

			$form->dev_spnfis->setValue($deviationList->remarks_spnfis);
			$form->dev_spnfis_check->setValue($deviationList->remarks_spnfis_check);
			$form->dev_spci_favorable->setValue($deviationList->remarks_spci_favorable);
			$form->dev_spci_check->setValue($deviationList->remarks_spci_check);

			$form->dev_coborrower_age->setValue($deviationList->remarks_coborrower_age);
			$form->dev_coresidence_yrs->setValue($deviationList->remarks_coresidence_yrs);
			$form->dev_coemployment_yrs->setValue($deviationList->remarks_coemployment_yrs);
			$form->dev_coemployment_status->setValue($deviationList->remarks_coemployment_status);
			$form->dev_cobusiness_yrs->setValue($deviationList->remarks_cobusiness_yrs);
			$form->dev_citizenship2->setValue($deviationList->remarks_citizenship2);
			
			$form->dev_confis->setValue($deviationList->remarks_confis);
			$form->dev_confis_check->setValue($deviationList->remarks_confis_check);
			$form->dev_coci_favorable->setValue($deviationList->remarks_coci_favorable);
			$form->dev_coci_check->setValue($deviationList->remarks_coci_check);
			$form->dev_cototal_income->setValue($deviationList->remarks_cototal_income);

			// End of Population
			if ($action == 'approval'){
			$detail ='Do you wish to recommend this application for Approval?';
			}
			else if ($action == 'rejection'){
			$detail = 'Do you wish to recommend this application for Rejection ?';
			}
			else if ($action == 'pass'){
			$detail = 'No Recommendation';
			}
			else if ($action == 'coReject'){
			$detail = 'Do you wish to submit this application as Rejected ?';
			}						
			else if ($action == 'coApprove'){
			$detail = 'Do you wish to submit this application as Approved ?';
			}			

			$this->view->detail = $detail;
			$this->view->capno = $accnt->getMainCapno($capno);

	
			
			$this->_helper->viewRenderer('box-confirmation');
			$wDeviation = $this->_helper->chkDeviation($capno);
			$countDev = chkArray($wDeviation);
			$this->view->deviation = $wDeviation;	


	
			if ($this->getRequest()->isPost()) {
				$formData = $this->getRequest()->getPost();
					if ($form->isValid($formData)) { 
					
					$capno = $this->_getParam('cap');
					$action = $this->_getParam('act');

					$borrower = new Model_BorrowerAccount();
					
					
					if(($action == 'approval') && (login_user_role()=='CA')){
						$account_status = 'CA - ReAp';
					}
					else if(($action == 'rejection') && (login_user_role()=='CA')){
						$account_status = 'CA - ReR';
					}
					else if(($action == 'pass') && (login_user_role()=='CA')){
						$account_status = 'CA - P';
					}
					else if(($action == 'comaker') && (login_user_role()=='CA')){
						$comaker_accnt_status = 'CA - S - CMK';
					}
					
					if ($countDev == 0){
					// without deviation
					$dataz = array(
					'deviation' => 'false',
					); } else {
					$dataz = array(
					'deviation' => 'true',
					); }
					$where = "capno like '$capno'";
					$borrower->update($dataz, $where);			
					
					$s = $this->_helper->ScoreModel($capno);
					$score = $this->_helper->$s($capno);
					$score_tag = $this->view->viewScore($score,getHighest($capno),'score');
					$route = $this->_helper->AutoRoute($capno,$score_tag);
					
					if ($countDev == 0){
					// without deviation
					$data = array(
					'account_status'=>$account_status,
					'score'=>$score,
					'score_tag'=>$score_tag,	
					'submitted_co_date'=>date('r'),
					'deviation' => 'false',
					'comaker_accnt_status'=>$comaker_accnt_status,
					'routetag_orig'=>$route,	
					'routetag'=>$route,
					);
					$where = "capno like '$capno'";
					$borrower->update($data, $where);	
					
					$data2 = array(
					'capno'=>$capno,
					'remarks_downpayment'=>'',
					'remarks_loan_amount'=>'',
					'remarks_veh_age'=>'',
					'remarks_gmi'=>'',
					'remarks_totalcombine'=>'',
					'remarks_borrower_age'=>'',
					'remarks_residence_yrs'=>'',
					'remarks_employment_yrs'=>'',
					'remarks_employment_status'=>'',
					'remarks_business_yrs'=>'',
					'remarks_citizenship1'=>'',
					'remarks_sell_lcp'=>'',
					'remarks_sell_appraisal'=>'',				
					'remarks_loantermhigh'=>'',
					'remarks_loantermlow'=>'',
					'remarks_nfis'=>'',
					'remarks_cmap'=>'',
					'remarks_nfis_check'=>'',
					'remarks_ci_favorable'=> '',
					'remarks_ci_check'=>'',
					//Vehicle Deviation
					'remarks_veh_yr' => '',
					'remarks_veh_tenor' => '',
					'remarks_veh_car_history' =>'',
					'remarks_loan_purpose' =>'',
					'remarks_total_income' =>'',
			
					// Coborrower Deviation
					'remarks_coborrower_age'=>'',
					'remarks_coresidence_yrs'=>'',
					'remarks_coemployment_yrs'=>'',
					'remarks_coemployment_status'=>'',
					'remarks_cobusiness_yrs'=>'',
					'remarks_citizenship2'=>'',
					'remarks_confis'=>'',
					'remarks_cocmap'=>'',	
					'remarks_confis_check'=>'',
					'remarks_coci_favorable'=> '',
					'remarks_coci_check'=>'',
					'remarks_cototal_income' =>'',

					//Spouse Deviation
					'remarks_spouse_age'=>'',
					'remarks_sporesidence_yrs'=>'',
					'remarks_spocitizenship'=>'',
					'remarks_spoemployment_yrs'=>'',
					'remarks_spoemployment_status'=>'',
					'remarks_spobusiness_yrs'=>'',
					'remarks_spnfis'=>'',
					'remarks_spcmap'=>'',
					'remarks_spnfis_check'=>'',
					'remarks_spci_favorable'=>'' ,
					'remarks_spci_check'=>'',

					);	
					
					$deviation = new Model_BorrowerDeviation();
					$sql = $deviation->select()->where('capno LIKE ?',$capno);
		
						if($deviation->fetchAll($sql)->count()== 0){
						$deviation->insert($data2);	
						//if no record found insert data
						}
						else{
						//else update using the capno
						$where = "capno like '$capno'";
						$deviation->update($data2,$where);				
					}
					
					}//end of Without Deviation
					else {
					//if with deviation
					$data = array(
					'account_status'=>$account_status,
					'submitted_co_date'=>date('r'),
					'deviation' => 'true',
					'score'=>$score,
					'score_tag'=>$score_tag,	
					'comaker_accnt_status'=>$comaker_accnt_status,
					'routetag_orig'=>$route,	
					'routetag'=>$route,					
					);
					$where = "capno like '$capno'";
					$borrower->update($data, $where);				
					$data2 = array(
					'capno'=>$capno,
					'remarks_downpayment'=>stripslashes($form->getValue('dev_downpayment')),
					'remarks_loan_amount'=>stripslashes($form->getValue('dev_loan_amount')),
					'remarks_veh_age'=>stripslashes($form->getValue('dev_veh_age')),
					'remarks_gmi'=>stripslashes($form->getValue('dev_gmi')),
					'remarks_totalcombine'=>stripslashes($form->getValue('dev_totalcombine')),
					'remarks_borrower_age'=>stripslashes($form->getValue('dev_borrower_age')),
					'remarks_residence_yrs'=>stripslashes($form->getValue('dev_residence_yrs')),
					'remarks_employment_yrs'=>stripslashes($form->getValue('dev_employment_yrs')),
					'remarks_employment_status'=>stripslashes($form->getValue('dev_employment_status')),
					'remarks_business_yrs'=>stripslashes($form->getValue('dev_business_yrs')),
					'remarks_citizenship1'=>stripslashes($form->getValue('dev_citizenship1')),
					'remarks_sell_lcp'=>stripslashes($form->getValue('dev_sell_lcp')),
					'remarks_sell_appraisal'=>stripslashes($form->getValue('dev_sell_appraisal')),
					'remarks_veh_yr' => stripslashes($form->getValue('dev_veh_yr')),
					'remarks_veh_tenor' => stripslashes($form->getValue('dev_veh_tenor')),
					'remarks_veh_car_history' =>stripslashes($form->getValue('dev_veh_car_history')),
					'remarks_loan_purpose' =>stripslashes($form->getValue('dev_loan_purpose')),
					'remarks_loantermhigh'=>stripslashes($form->getValue('dev_loantermhigh')),
					'remarks_loantermlow'=>stripslashes($form->getValue('dev_loantermlow')),
					'remarks_nfis'=>stripslashes($form->getValue('dev_nfis')),
					'remarks_cmap'=>stripslashes($form->getValue('dev_cmap')),
					'remarks_nfis_check'=>stripslashes($form->getValue('dev_nfis_check')),
					'remarks_ci_favorable'=> stripslashes($form->getValue('dev_ci_favorable')),
					'remarks_ci_check'=>stripslashes($form->getValue('dev_ci_check')),	
					'remarks_total_income'=>stripslashes($form->getValue('dev_total_income')),		
					
					// Coborrower Deviation
					'remarks_coborrower_age'=>stripslashes($form->getValue('dev_coborrower_age')),
					'remarks_coresidence_yrs'=>stripslashes($form->getValue('dev_coresidence_yrs')),
					'remarks_coemployment_yrs'=>stripslashes($form->getValue('dev_coemployment_yrs')),
					'remarks_coemployment_status'=>stripslashes($form->getValue('dev_coemployment_status')),
					'remarks_cobusiness_yrs'=>stripslashes($form->getValue('dev_cobusiness_yrs')),
					'remarks_citizenship2'=>stripslashes($form->getValue('dev_citizenship2')),
					'remarks_confis'=>stripslashes($form->getValue('dev_confis')),	
					'remarks_cocmap'=>stripslashes($form->getValue('dev_cocmap')),
					'remarks_confis_check'=>stripslashes($form->getValue('dev_confis_check')),
					'remarks_coci_favorable'=> stripslashes($form->getValue('dev_coci_favorable')),
					'remarks_coci_check'=>stripslashes($form->getValue('dev_coci_check')),					
					'remarks_cototal_income'=>stripslashes($form->getValue('dev_cototal_income')),		

					//Spouse Deviation
					'remarks_spouse_age'=>stripslashes($form->getValue('dev_spouse_age')),
					'remarks_spocitizenship'=>stripslashes($form->getValue('dev_spocitizenship')),
					'remarks_sporesidence_yrs'=>stripslashes($form->getValue('dev_sporesidence_yrs')),
					'remarks_spoemployment_yrs'=>stripslashes($form->getValue('dev_spoemployment_yrs')),
					'remarks_spoemployment_status'=>stripslashes($form->getValue('dev_spoemployment_status')),
					'remarks_spobusiness_yrs'=>stripslashes($form->getValue('dev_spobusiness_yrs')),
					'remarks_spnfis'=>stripslashes($form->getValue('dev_spnfis')),
					'remarks_spcmap'=>stripslashes($form->getValue('dev_spcmap')),
					'remarks_spnfis_check'=>stripslashes($form->getValue('dev_spnfis_check')),
					'remarks_spci_favorable'=> stripslashes($form->getValue('dev_spci_favorable')),
					'remarks_spci_check'=>stripslashes($form->getValue('dev_spci_check')),
					);
					
					$deviation = new Model_BorrowerDeviation();
					$sql = $deviation->select()->where('capno LIKE ?',$capno);
		
						if($deviation->fetchAll($sql)->count()== 0){
						$deviation->insert($data2);	
						//if no record found insert data
						}
						else{
						//else update using the capno
						$where = "capno like '$capno'";
						$deviation->update($data2,$where);				
						}
					}// End of With Deviation

					

		
					//Insert Account History
					$history = new Model_AccountHistory();
					$select = $history->select();
					$select->where('capno like ?',$capno)->order('id DESC');
					$historyDetail = $history->fetchRow($select);
					$hdata = array (
					'capno'=>$capno,
					'status'=>$account_status,
					'by'=>login_user(),
					'date'=>date("r"),
					'remarks'=>$form->getValue('submit_remarks'),
					'date_last'=>$historyDetail->date,
					);
					$history->insert($hdata);
					//End of History
					updateLoanAmount2($capno);
					$this->_helper->CloneData($capno);
					$this->_helper->Accounts($capno,$account_status);
					$this->view->isSubmit = "OK";	
					$script_end = microtime_float();
					echo "Script executed in ".bcsub($script_end, $script_start, 4)." seconds.";


					}// End of IsValid
			}// End of GetRequest
}

public function mabooksubmitAction(){
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
			$this->_helper->viewRenderer('box-booking');
		
			$this->_helper->RolePermissionHelper('index_mabooksubmit');

			$capno = $this->_getParam('cap');	
			$borrower = new Model_BorrowerAccount();
			
			//$this->_helper->RolePermissionHelper->isApproved($borrower->getAcntStatus($capno));
			$form = new Form_PopupBox();
			$this->view->form = $form;
			$this->view->capno = $capno;
			$this->view->message = "Submit this account to L&D for Booking?";
			
			//echo date("r",strtotime("-2 days"));
			if ($this->getRequest()->isPost()) {
				$formData = $this->getRequest()->getPost();
					if ($form->isValid($formData)) { 
					
					//Update in Borrower Account 
					$data = array(
					'account_status'=>'MA - PreB',
					'submitted_la'=>'adtolentino',
					'submitted_mala_date'=>date("r"),
					);
					$borrower = new Model_BorrowerAccount();
					$where = "capno like '$capno'";
					$borrower->update($data, $where);	
					// End of Insert in Borrower Account

					//Insert Account History
					$history = new Model_AccountHistory();
					$select = $history->select();
					$select->where('capno like ?',$capno)->order('id DESC');
					$historyDetail = $history->fetchRow($select);
					$hdata = array (
					'capno'=>$capno,
					'status'=>'MA - PreB',
					'by'=>login_user(),
					'date'=>date("r"),
					'remarks'=>$form->getValue('submit_remarks'),
					'date_last'=>$historyDetail->date,
					);
					$history->insert($hdata);
					//End of History
					
					
					$form->getValue('submit_remarks');			
					$this->view->isSubmit = "OK";	
					}
			}
			
}



public function lartsmabooksubmitAction(){
			//RTS to MA
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
			$this->_helper->viewRenderer('box-booking');
		
			$this->_helper->RolePermissionHelper('index_labooksubmit');
			
			$capno = $this->_getParam('cap');	
			$borrower = new Model_BorrowerAccount();
			
		
			$form = new Form_PopupBox();
			
			$this->view->form = $form;
			$this->view->capno = $capno;
			
			//$this->view->message = "Document Checked Done, Submit this account to the next LA?";
			if ($this->getRequest()->isPost()) {
				$formData = $this->getRequest()->getPost();
					if ($form->isValid($formData)) { 
					
					//$status = $this->_helper->updateAcntStatus->booking($borrower->getAcntStatus($capno));
					//Update in Borrower Account 
					$data = array(
					'account_status'=>'LA - RTMA',
					//'submitted_la'=>'jutan',
					'date_booked'=>date("r"),
					);
					$borrower = new Model_BorrowerAccount();
					$where = "capno like '$capno'";
					$borrower->update($data, $where);	
					// End of Insert in Borrower Account

					//Insert Account History
					$history = new Model_AccountHistory();
					$select = $history->select();
					$select->where('capno like ?',$capno)->order('id DESC');
					$historyDetail = $history->fetchRow($select);
					$hdata = array (
					'capno'=>$capno,
					'status'=>'LA - RTMA',
					'by'=>login_user(),
					'date'=>date("r"),
					'remarks'=>$form->getValue('submit_remarks'),
					'date_last'=>$historyDetail->date,
					);
					$history->insert($hdata);
					//End of History
					$form->getValue('submit_remarks');			
					$this->view->isSubmit = "OK";	
					}
			}
}

public function lortslabooksubmitAction(){
			//RTS to MA
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
			$this->_helper->viewRenderer('box-booking');
		
			$this->_helper->RolePermissionHelper('index_labooksubmit');
			
			$capno = $this->_getParam('cap');	
			$borrower = new Model_BorrowerAccount();
			
		
			$form = new Form_PopupBox();
			
			$this->view->form = $form;
			$this->view->capno = $capno;
			
			//$this->view->message = "Document Checked Done, Submit this account to the next LA?";
			if ($this->getRequest()->isPost()) {
				$formData = $this->getRequest()->getPost();
					if ($form->isValid($formData)) { 
					
					//$status = $this->_helper->updateAcntStatus->booking($borrower->getAcntStatus($capno));
					//Update in Borrower Account 
					$data = array(
					'account_status'=>'LO - RTLA',
					//'submitted_la'=>'jutan',
					'date_booked'=>date("r"),
					);
					$borrower = new Model_BorrowerAccount();
					$where = "capno like '$capno'";
					$borrower->update($data, $where);	
					// End of Insert in Borrower Account

					//Insert Account History
					$history = new Model_AccountHistory();
					$select = $history->select();
					$select->where('capno like ?',$capno)->order('id DESC');
					$historyDetail = $history->fetchRow($select);
					$hdata = array (
					'capno'=>$capno,
					'status'=>'LO - RTLA',
					'by'=>login_user(),
					'date'=>date("r"),
					'remarks'=>$form->getValue('submit_remarks'),
					'date_last'=>$historyDetail->date,
					);
					$history->insert($hdata);
					//End of History
					$form->getValue('submit_remarks');			
					$this->view->isSubmit = "OK";	
					}
			}
}

public function larecallsubmitAction(){
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
			$this->_helper->viewRenderer('box-booking');
		
			$this->_helper->RolePermissionHelper('index_labooksubmit');
			
			$capno = $this->_getParam('cap');	
			$borrower = new Model_BorrowerAccount();
			
		
			$form = new Form_PopupBox();
			
			$this->view->form = $form;
			$this->view->capno = $capno;
			
			//$this->view->message = "Document Checked Done, Submit this account to the next LA?";
			if ($this->getRequest()->isPost()) {
				$formData = $this->getRequest()->getPost();
					if ($form->isValid($formData)) { 
					
					$status = $this->_helper->updateAcntStatus->booking($borrower->getAcntStatus($capno));
					//Update in Borrower Account 
					$data = array(
					'account_status'=>$status,
					);
					$borrower = new Model_BorrowerAccount();
					$where = "capno like '$capno'";
					$borrower->update($data, $where);	
					// End of Insert in Borrower Account

					//Insert Account History
					$history = new Model_AccountHistory();
					$select = $history->select();
					$select->where('capno like ?',$capno)->order('id DESC');
					$historyDetail = $history->fetchRow($select);
					$hdata = array (
					'capno'=>$capno,
					'status'=>$status,
					'by'=>login_user(),
					'date'=>date("r"),
					'remarks'=>$form->getValue('submit_remarks'),
					'date_last'=>$historyDetail->date,
					);
					$history->insert($hdata);
					//End of History
					$form->getValue('submit_remarks');			
					$this->view->isSubmit = "OK";	
					}
			}
}

public function testsampleAction(){
    $this->_helper->viewRenderer->setNoRender(true);
    $this->_helper->AutoRouteSms('11012150900300','CO - ReAp','C5-OUTSIDE');
}

public function routesubmitAction(){
	//CO Auto Routing Starts
	//auto routine for the autorouting starting with the CO
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/md5.js');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');

			$userTable = new Model_Users();
			if($userTable->getdigipasswordstatus(login_user()) === false) {
			$this->_helper->redirector('digitalpassword','admin');		
			}	
		
		
			$form = new Form_PopupBox();
			$this->view->form = $form;
			
			$capno = $this->_getParam('cap');
			//$this->_helper->RolePrivileges($capno);
			$action = $this->_getParam('act');
			
			$table = new Model_BorrowerAccount();
			$select = $table->select();
			$select->where('capno like ?',$capno);
			$detail = $table->fetchRow($select);
			
			//Get the route tag
			$scorer =  $this->view->viewScore($detail->score,getHighest($capno),'view');
			$route = $this->_helper->AutoRoute($capno,$scorer);

			//echo $route;
			if ($action == 'approval'){
			$detail ='Do you wish to recommend this application for Approval?';
			}
			else if ($action == 'rejection'){
			$detail = 'Do you wish to recommend this application for Rejection ?';
			}
			else if ($action == 'coReject'){
			$detail = 'Do you wish to submit this application as Rejected ?';
			}			
			
			else if ($action == 'coApprove'){
			$detail = 'Do you wish to submit this application as Approved ?';
			}
			else if ($action == 'coPass'){
			$detail = 'Do you wish to Pass this Application to the next Level ?';
			}			

			$this->view->detail = $detail;
			$this->view->capno = $capno;
			$this->_helper->viewRenderer('box-confirmation-route');
			$this->view->targetRoute = $this->_helper->AutoRouteAttendance($route);

			$userAttendance = new Model_UsersTimeInfo();
			$approvers = new Model_Users();
			$approver = $approvers->returnUsernamebyRole('CSH');
			$nextapprover = $userAttendance->chkAbsent($approver);
			
			//checking
			//echo $nextapprover;
			if ($this->getRequest()->isPost()) {
				$formData = $this->getRequest()->getPost();
					if ($form->isValid($formData)) { 
					
					$capno = $this->_getParam('cap');
					$action = $this->_getParam('act');

					$table = new Model_BorrowerAccount();
					$select = $table->select();
					$select->where('capno like ?',$capno);
					$detail = $table->fetchRow($select);
					
					//Conflict if the tag does not occur in the CA so get the tag again upon implementation
					$score_tag = $this->view->viewScore($detail->score,getHighest($capno),'view');
					$user = login_user();
					$userAttendance = new Model_UsersTimeInfo();
					$approvers = new Model_Users;					
					
					if($action == 'approval'){
						$decision = 'Recommended for Approval';
						$approver = $approvers->returnUsernamebyRole('CSH');
						$nextapprover = $userAttendance->chkAbsent($approver);
						
						if($nextapprover == 'present'){
						//same route no absent
						$account_status = 'CO - ReAp';
						$route = $this->_helper->AutoRouteAttendance($route);
						
						//straight route sms database
						$this->_helper->AutoRouteSms($capno,$account_status,$route);
						
						}else{
						//absent csh change statusl
						$account_status = 'CO - ReAp - ABCSH';
						$route = $this->_helper->AutoRouteAttendance($route);
						}
					}
					else if($action == 'rejection'){
						$decision = 'Recommended for Rejection';
						$approver = $approvers->returnUsernamebyRole('CSH');
						$nextapprover = $userAttendance->chkAbsent($approver);					
						if($nextapprover == 'present'){	
						//same route no absent
						$account_status = 'CO - ReR';

						//straight route sms database
						$this->_helper->AutoRouteSms($capno,$account_status,$route);

						}else{
						//absent csh change approver level
						$account_status = 'CO - ReR - ABCSH';
						
						}
					}
					else if($action == 'coApprove'){
						$account_status = 'CO - Ap';
						$decision = 'Approved';
					}
					else if($action == 'coReject'){
						$account_status = 'CO - R';
						$decision = 'Dissaproved';
					}
					else if($action == 'coPass'){
						$account_status = $this->view->autoStatusPass($route);
						$decision = 'Pass';
						//$route = $this->view->autoRoutePass($route);
					}
					
					//Insert in Borrower Account 
					$data = array(
					'account_status'=>$account_status,
					'capbasis'=>getHighest($capno),
					'routetag_orig'=>$route,	
					'routetag'=>$route,		
					'score_tag'=>$score_tag,
					'date_decided'=>date("r"),
					);

					$borrower = new Model_BorrowerAccount();
					$where = "capno like '$capno'";
					$borrower->update($data, $where);	
					// End of Insert in Borrower Account
					
					//Insert in BorrowerCrawFormApprovalSection
					//Feb 15,2010 Paolo Marco Manarang
					$data = array(
					'capno'=>$capno,
					'decision'=>$decision,
					'approved_by'=>login_user(),
					'reason'=>$form->getValue('submit_remarks'),
					'date_approval'=>date("m-d-Y h:i a"),
					'date_approval2'=>date("r"),
					'date_type'=>3,
					);
					$crawformapproval = new Model_BorrowerCrawFormApprovalsection();
					$sql = $crawformapproval->select()->where('capno LIKE ?',$capno)->where('approved_by LIKE ?',$user);
						if($crawformapproval->fetchAll($sql)->count()== 0){
						$crawformapproval->insert($data);	
						//if no record found insert data
						}
						else{
						//else update using the capno
						$where = "capno like '$capno' AND approved_by like '$user'";
						$crawformapproval->update($data,$where);				
						}
					//End in Insert BorrowerCrawFormApprovalSection 
					
					//Insert Account History
					$history = new Model_AccountHistory();
					$select = $history->select();
					$select->where('capno like ?',$capno)->order('id DESC');
					$historyDetail = $history->fetchRow($select);
					$hdata = array (
					'capno'=>$capno,
					'status'=>$account_status,
					'by'=>login_user(),
					'date'=>date("r"),
					'remarks'=>$form->getValue('submit_remarks'),
					'date_last'=>$historyDetail->date,
					);
					$history->insert($hdata);
					//End of History
					$this->view->isSubmit = "Submitted";	
					$this->_helper->Accounts($capno,$account_status);
					/*
					$credit = array(
					'capno'=>$capno,
					'co_name'=> login_user(),
					'co_date'=>date("d-m-Y H:i"),
					'co_decision'=>$account_status,	
					
					'application_name'=>login_user(),
					'application_decision'=>$account_status,
					'application_date'=>date("d-m-Y H:i"),	
					);
					
					$credecision = new Model_CreditDecision();
					$sql = $credecision->select()->where('capno LIKE ?',$capno);
		
						if($credecision->fetchAll($sql)->count()== 0){
						$credecision->insert($credit);	
						//if no record found insert data
						}
						else{
						//else update using the capno
						$where = "capno like '$capno'";
						$credecision->update($credit,$where);				
						}
					*/
			}
			}
	
}

public function crawdecisionAction(){
$this->_helper->viewRenderer('craw-route');
$capno = $this->_getParam('cap');	
$this->view->capno = $capno;
}

public function creditdecisionAction(){
	//Manual Credit Decision for CO
	// And view Purpose 
	// With Autoroutin
	// March 03,2010
	// Paolo Marco Manarang
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv3.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/menu.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
 	
		$capno = $this->_getParam('cap');	
			
		$detail = new Model_BorrowerAccount();
		$status = $detail->getAcntStatus($capno);
		$this->view->status = $status;
			
			
			$table = new Model_CreditDecision();
			$form = new Form_CreditDecision();
		
		
			$crawform = new Model_BorrowerCrawFormApprovalsection();
			$select = $crawform->select();
			$select->where('capno like ?',$capno)->order('id ASC');
			$approveHistory = $crawform->fetchAll($select);
			$this->view->history = $approveHistory;
			$sql = $table->select()->where('capno LIKE ?',$capno);

				//if($table->fetchAll($sql)->count() == 0 && login_user_role() == "CO"){
				/*
				if(($status == 'CO - REV' || $status == 'CO - REVD' || $status == 'CSH - ReAp' || $status == 'CSH - ReR' || $status == 'CSH - P') && login_user_role() == "CO"){
					$this->_helper->viewRenderer('credit-decision-new');
					foreach($form->getElements() as $element) {
					$element->removeDecorator('DtDdWrapper');
					$element->removeDecorator('Label');
					}
					$this->view->form = $form;
				}
				else{
				*/
					//if records found display the appropriate depends if old or not
					$detail = $table->fetchRow($sql);
					if($detail->application_decision){	
					// old tagging for mam aline
					$this->_helper->viewRenderer('credit-decision-view');
					$select = $table->select();
					$select->where('capno like ?',$capno);
					$detail = $table->fetchRow($select);
					$this->view->detail = $detail;
					}
					else {
					// new tagging with autorouting
					//$this->_helper->viewRenderer('credit-decision-new-view');
					//$this->view->history = $approveHistory;
					$this->_redirect('/index/routedecisionview/cap/'.$capno);	
					
					}
			/*	} */	
	


	$this->view->capno = $capno;


	if ($this->getRequest()->isPost()) {
		$formData = $this->getRequest()->getPost();
		if ($form->isValid($formData)) {  
		$capno = $this->_getParam('cap');	
		
			if($formData['Submit'] == 'Submit'){
					$data = array(
					'account_status'=>$formData['application_decision'],
					);
					$where = "capno like '$capno'";
					$detail->update($data,$where);	
				
					//Insert Account History
					$history = new Model_AccountHistory();
					$select = $history->select();
					$select->where('capno like ?',$capno)->order('id DESC');
					$historyDetail = $history->fetchRow($select);
					
					$hdata = array (
					'capno'=>$capno,
					'status'=>$form->getValue('application_decision'),
					'by'=>$form->getValue('application_name'),
					'date'=>date("r"),
					'remarks'=>'Manual Approve - '.$form->getValue('submit_remarks'),
					'date_last'=>$historyDetail->date,
					);
					$history->insert($hdata);
					//End of History	
			}else if($formData['Submit'] == 'Add'){
				
					$data = array(
					'capno'=>$capno,
					'decision'=>$form->getValue('decision'),
					'approved_by'=>$form->getValue('approved_by'),
					'reason'=>$form->getValue('reason'),
					'date_approval'=>$formData['date'],
					'date_approval2'=>date("r"),
					'role'=> login_user_role(),
					'date_type'=>3,
					);
					$table = new Model_BorrowerCrawFormApprovalsection();
					$table->insert($data);	
				
			}


					$this->_redirect('/index/creditdecision/cap/'.$capno);	

					
		/*
		$data = array(
		'capno'=>$capno,
		
		'co_name'=>$form->getValue('co_name'),
		'co_date' => $form->getValue('co_date'),
		'co_decision' => $form->getValue('co_decision'),
		
		'csh_name'=>$form->getValue('csh_name'),
		'csh_date' => $form->getValue('csh_date'),
		'csh_decision' => $form->getValue('csh_decision'),
				
		'cmg_name'=>$form->getValue('cmg_name'),
		'cmg_date' =>$form->getValue('cmg_date'),
		'cmg_decision' => $form->getValue('cmg_decision'),
		
		'pres_name'=>$form->getValue('pres_name'),
		'pres_date' => $form->getValue('pres_date'),
		'pres_decision' => $form->getValue('pres_decision'),
		
		'subcrecom_date' => $form->getValue('subcrecom_date'),
		'subcrecom_decision' => $form->getValue('subcrecom_decision'),
		
		'crecom_date' => $form->getValue('crecom_date'),
		'crecom_decision' => $form->getValue('crecom_decision'),
		
		'board_date' => $form->getValue('board_date'),
		'board_decision' => $form->getValue('board_decision'),
		
		'application_name'=>$form->getValue('application_name'),
		'application_date' => $form->getValue('application_date'),
		'application_decision' => $form->getValue('application_decision'),

		'remarks'=>$form->getValue('submit_remarks'),
		);
		$table = new Model_CreditDecision();

		$sql = $table->select()->where('capno LIKE ?',$capno);

				if($table->fetchAll($sql)->count() == 0){
				$table->insert($data);	
				//if no record found insert data
				}
				else{
				//else update using the capno
				$where = "capno like '$capno'";
				$table->update($data,$where);						
				}	
		
			//Insert Account History
			$history = new Model_AccountHistory();
			$select = $history->select();
			$select->where('capno like ?',$capno)->order('id DESC');
			$historyDetail = $history->fetchRow($select);
			
			$hdata = array (
			'capno'=>$capno,
			'status'=>$form->getValue('application_decision'),
			'by'=>$form->getValue('application_name'),
			'date'=>date("r"),
			'remarks'=>$form->getValue('submit_remarks'),
			'date_last'=>$historyDetail->date,
			);
			$history->insert($hdata);
			//End of History		
			
			$accnt = new Model_BorrowerAccount();
			$data = array(
			'account_status' => $form->getValue('application_decision'),
			);
			$where = "capno like '$capno'";
			$accnt->update($data,$where);		
			*/
		}
	}

}
public function routedecisionviewAction(){
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv3.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');

		$this->_helper->viewRenderer('credit-decision-route-view');
		$capno = $this->_getParam('cap');
		$user = Zend_Auth::getInstance()->getIdentity();
		
		$this->view->user = $user->username;
		$this->view->role = $user->role_type;
		$this->view->capno = $capno;
		$this->view->capbasis = getHighest($capno);
		
		$borrower = new Model_BorrowerAccount();
		$select = $borrower->select();
		$select->where('capno like ?',$capno);
		$detail = $borrower->fetchRow($select);
		$this->view->detail = $detail; 
		
		$decision = new Model_CreditDecision();
		$select = $decision->select();
		$select->where('capno like ?',$capno);
		$creDetail= $decision->fetchRow($select);
		$this->view->creDetail = $creDetail; 
	
		$crawform = new Model_BorrowerCrawFormApprovalsection();
		$select = $crawform->select();
		$select->where('capno like ?',$capno)->order('date_approval ASC');
		$approveHistory = $crawform->fetchAll($select);
		$this->view->history = $approveHistory;
		
		$crecom = new Model_AutoRouting_AccountsCrecom();
		$select = $crecom->select();
		$select->where('capno like ?',$capno)->order('date_decision ASC');
		$crecomDetail = $crecom->fetchAll($select);
		$this->view->crecomDetail = $crecomDetail;
		
		$subcrecom = new Model_AutoRouting_AccountsSubCrecom();
		$select = $subcrecom->select();
		$select->where('capno like ?',$capno)->order('date_decision ASC');
		$subcrecomDetail = $subcrecom->fetchAll($select);
		$this->view->subcrecomDetail = $subcrecomDetail;
}

public function routedecisionAction(){
	//routine for csh and above auto routing decision
		//$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv3.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		//$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/md5.js');
		$this->_helper->viewRenderer('credit-decision-route');
	
		$userTable = new Model_Users();
		if($userTable->getdigipasswordstatus(login_user()) === false) {
		$this->_helper->redirector('digitalpassword','admin');		
		}	
		
		$capno = $this->_getParam('cap');
		$borrower = new Model_BorrowerAccount();
		$select = $borrower->select();
		$select->where('capno like ?',$capno);
		$detail = $borrower->fetchRow($select);
		$this->view->detail = $detail; 

		//restrict access if not CSH / CMGH / PRES
		$this->_helper->RolePermissionHelper('auto_route_decision_approver');
		
		$statusModel = new Model_Admin_AccountStatus();
		//return boolean
		if($detail->account_status =='Crecom - ENEXEBOD'){
		
		}
		else{
		if(strpos($detail->routetag,'-CRECOM')!== false || 
		strpos($detail->routetag,'-SUBCRECOM')!== false || 
		strpos($detail->routetag,'-EXE-BOD')!== false){	
		
			if(login_user_role() != 'CSH'){
			$this->_redirect('/autorouting/crecomdecision/cap/'.$capno);	
			}

		}else {
			if($statusModel->routeview($detail->account_status,'access_route_decision') === TRUE){
			$this->_redirect('/index/routedecisionview/cap/'.$capno);	
			}

		}
			//Prevents Dual Decision in Crecom
			if($this->_helper->CrecomHelper->chkifDecided($capno,$detail->routetag) > 0){
		    $this->_redirect('/index/routedecisionview/cap/'.$capno);	
			}
		}
		
		
		
		//restrict page if account status is same as the role
		if(strpos($detail->account_status,login_user_role())=== false){
		}else {
		// To be revices becoause of Endorse status
		//$this->_redirect('/error/denied/');	
		}
		//End of REstriction


		$capno = $this->_getParam('cap');
		$user = Zend_Auth::getInstance()->getIdentity();

		$this->view->user = $user->username;
		$this->view->role = $user->role_type;
		$this->view->capno = $capno;
		$this->view->capbasis = getHighest($capno);
		
		$borrower = new Model_BorrowerAccount();
		$select = $borrower->select();
		$select->where('capno like ?',$capno);
		$detail = $borrower->fetchRow($select);
		$this->view->detail = $detail; 
		$route = $detail->routetag;

		$decision = new Model_CreditDecision();
		$select = $decision->select();
		$select->where('capno like ?',$capno);
		$creDetail= $decision->fetchRow($select);
		$this->view->creDetail = $creDetail; 
		
		$crawform = new Model_BorrowerCrawFormApprovalsection();
		$select = $crawform->select();
		$select->where('capno like ?',$capno)->order('date_approval ASC');
		$approveHistory = $crawform->fetchAll($select);
		$this->view->history = $approveHistory;
		$this->view->targetRoute =  $this->_helper->AutoRouteAttendance($route);

		$crecomTable = new Model_AutoRouting_AccountsCrecom();
		$crecomDetail = $crecomTable->fetchAllModel($capno);
		$this->view->crecomDetail = $crecomDetail;

		$subcrecomTable = new Model_AutoRouting_AccountsSubCrecom();
		$subcrecomDetail = $subcrecomTable->fetchAllModel($capno);
		$this->view->subcrecomDetail = $subcrecomDetail;
		
	if ($this->getRequest()->isPost()) {
		$formData = $this->getRequest()->getPost();
			
					$capno = $this->_getParam('cap');
					$action = $formData['decisionBox'];
					$user = login_user();
					
			switch($action){
				case 'approve':
					if(login_user_role() == 'PRES'){
					$account_status = $this->_helper->AutoRoute->presApproval($capno);	
					}else {
					$account_status = login_user_role().' - Ap';
					}
				$alert =  "You have Approve this Account";
				$decision = 'Approved';
				break;
				case 'reject':
					if(login_user_role() == 'PRES'){
					$account_status = $this->_helper->AutoRoute->presDisapproval($capno);	
					}else {
					$account_status = login_user_role().' - R';
					}
				$alert =  "You have Dissaproved this Account";
				$decision = 'Dissaproved';
				break;
				case 'pass':
					
				if(login_user_role() =='CSH'){
				$account_status = $this->view->autoStatusPass($route);	
				}else{
				$account_status = login_user_role().' - P';
				}
				
				
				$decision = 'Pass';
				$route = $this->view->autoRoutePass($route);
				
				
				
				break;		
				case 'approval':
					
				if(login_user_role() == 'CSH'){
					$routex = $this->_helper->AutoRouteAttendance($route);
						if($route == $routex){
						//same route no absent
						$account_status = login_user_role().' - ReAp';
						$decision = 'Recommended for Approval';	
						
						
						//same route sms table
						$this->_helper->AutoRouteSms($capno,$account_status,$route);
						}else{
						//absent csh change approver level
						$account_status = 'CSH - ReAp - ABCMGH';
						$route = $routex;
						$decision = 'Recommended for Approval';	
						}
					
				}else{
					$account_status = login_user_role().' - ReAp';
					$alert = "You have Recommend this Account for Approval";
					$decision = 'Recommended for Approval';	
					$route = $this->view->autoRouteCmgh($route);
					
					//same route sms table
					$this->_helper->AutoRouteSms($capno,$account_status,$route);
					
				}
				
				

				break;			
				case 'rejection':
				if(login_user_role() == 'CSH'){
				$routex = $this->_helper->AutoRouteAttendance($route);
					
						if($route == $routex){
						//same route no absent
						$account_status = login_user_role().' - ReR';
						$decision = 'Recommended for Rejection';
					
						//same route sms table
						$this->_helper->AutoRouteSms($capno,$account_status,$route);
					
						}else{
						//absent csh change approver level
						$account_status = 'CSH - ReR - ABCMGH';
						$route = $routex;
						$decision = 'Recommended for Rejection';

						}
				
				}else {
				$account_status = login_user_role().' - ReR';
				$alert = "You have Recommend this Account for Rejection";
				$decision = 'Recommended for Rejection';
				$route = $this->view->autoRouteCmgh($route);
				//same route sms table
				$this->_helper->AutoRouteSms($capno,$account_status,$route);
				}	
				
				break;									
			}
			
			//Insert in BorrowerCrawFormApprovalSection
			//Feb 15,2010 Paolo Marco Manarang
			$data = array(
			'capno'=>$capno,
			'decision'=>$decision,
			'approved_by'=>login_user(),
			'reason'=>$formData['remarks'],
			'date_approval'=>date("m-d-Y h:i a"),
			'date_approval2'=>date("r"),
			'role'=> login_user_role(),
			'date_type'=>3,
			);
			
			$crawformapproval = new Model_BorrowerCrawFormApprovalsection();

			$crawformapproval->insert($data);	
			// use in crawform
			//if no record found insert data
			//End in Insert BorrowerCrawFormApprovalSection 
					
					$data = array(
					'account_status'=>$account_status,
					'routetag'=>$route,
					'date_decided'=>date("r"),
					);

					$borrower = new Model_BorrowerAccount();
					$where = "capno like '$capno'";
					$borrower->update($data, $where);	
					
					//Insert Account History
					$history = new Model_AccountHistory();
					$select = $history->select();
					$select->where('capno like ?',$capno)->order('id DESC');
					$historyDetail = $history->fetchRow($select);
					$hdata = array (
					'capno'=>$capno,
					'status'=>$account_status,
					'by'=>login_user(),
					'date'=>date("r"),
					'remarks'=>$formData['remarks'],
					'date_last'=>$historyDetail->date,
					);
					$history->insert($hdata);
					//End of History
					
					$this->_helper->Accounts($capno,$account_status);
					//Mode 1
					//$this->_helper->CrecomHelper->crecom($capno,$route,$account_status,$formData['remarks']);
					//Mode 2 
					$this->_helper->CrecomHelper->crecomMod2($capno,$route,$account_status,$formData['remarks']);
					
					$this->view->isSubmit = "Submitted";	
		
				
		    //$this->_redirect('/index/routedecisionview/cap/'.$capno);	
			
	}// end of get request
}


public function routeendorseAction(){
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv3.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/md5.js');

		$userTable = new Model_Users();
		if($userTable->getdigipasswordstatus(login_user()) === false) {
		$this->_helper->redirector('digitalpassword','admin');		
		}	
	
		$this->_helper->viewRenderer('credit-endorse-route');
		$capno = $this->_getParam('cap');
		$borrower = new Model_BorrowerAccount();
		$select = $borrower->select();
		$select->where('capno like ?',$capno);
		$detail = $borrower->fetchRow($select);
		$this->view->detail = $detail; 
		//restrict access if not CSH / CMGH / PRES
		if(login_user_role() != 'ALMH'){
		$this->_redirect('/error/denied/');	
		}			
		/*
		//restrict page if account status if not Rejected Accounts
		if($detail->account_status == 'CO - R'
		|| $detail->account_status == 'CSH - R'
		|| $detail->account_status == 'CMGH - R'
		|| $detail->account_status == 'PRES - R'
		){
		//$this->_redirect('/error/denied/');	
		}else {
		$this->_redirect('/error/denied/');		
		}		//End of Restrictions
		*/
		//$this->_helper->RolePermissionHelper->statusAccess($detail->account_status,'access_routeendorse');

		$this->_helper->RolePermissionHelper->statusAccess($detail->account_status,'access_routeendorse2');
		
		$days = $this->view->daysDifference(date('Y-m-d'),date('Y-m-d',strtotime($detail->date_decided)));
		//echo $days;	
		if($days > 6){
			$this->_redirect('/error/denied/');	
		}
		
		$capno = $this->_getParam('cap');
		$user = Zend_Auth::getInstance()->getIdentity();
		
		$this->view->user = $user->username;
		$this->view->role = $user->role_type;
		$this->view->capno = $capno;
		$this->view->capbasis = getHighest($capno);
		
		$borrower = new Model_BorrowerAccount();
		$select = $borrower->select();
		$select->where('capno like ?',$capno);
		$detail = $borrower->fetchRow($select);
		$this->view->detail = $detail; 

		$decision = new Model_CreditDecision();
		$select = $decision->select();
		$select->where('capno like ?',$capno);
		$creDetail= $decision->fetchRow($select);
		$this->view->creDetail = $creDetail; 
		
		$form = new Form_CreditDecision();
		/** Get the next level approver base on the status **/ 
		$decision = $detail->account_status;
		$routetag = $detail->routetag;
		$crawform = new Model_BorrowerCrawFormApprovalsection();
		$select = $crawform->select();
		$select->where('capno like ?',$capno)->order('id DESC');
		$approveHistory = $crawform->fetchAll($select);
		$this->view->history = $approveHistory;
		
		switch($decision){
			case 'CO - R':case 'CO - P':
			$endorse = 'ALMH - ENCSH';
			$nextapprover = $this->view->getUser('CSH'); 
			$message = 'ALMH endorsed to CSH';
			$route = 'EN-CSH';
			break;
			case 'CSH - R':case 'CSH - P':
			$endorse = 'ALMH - ENCMGH';
			$nextapprover = $this->view->getUser('CMGH'); 
			$message = 'ALMH endorsed to CMGH';
			$route = 'EN-CMGH';
			break;
			case 'CMGH - R':
			$endorse = 'ALMH - ENPRES';
			$nextapprover = $this->view->getUser('PRES');
			$message = 'ALMH endorsed to PRES';		
			$route = 'EN-PRES';
			break;
		/**********With RouteTag*********/
			case 'CSH - ReR':case 'CSH - RP':
			if(strpos($routetag,'-CMGH')!== false){
				$endorse = 'ALMH - ENCMGH';
				$nextapprover = $this->view->getUser('CMGH'); 
				$message = 'ALMH endorsed to CMGH';
				$route = 'EN-CMGH';
			}
			else if(strpos($routetag,'-PRES')!== false){
				$endorse = 'ALMH - ENPRES';
				$nextapprover = $this->view->getUser('PRES');
				$message = 'ALMH endorsed to PRES';		
				$route = 'EN-PRES';
			}
			else if(strpos($routetag,'-CRECOM')!== false || 
			strpos($routetag,'-EXE-BOD')!== false){
						$endorse = 'Crecom - InP';
						$nextapprover = 'CRECOM';
						$message = 'ALMH endorsed to CRECOM';	
					if(strpos($routetag,'-CRECOM')!== false){
	
						$route = 'EN-CRECOM';
					}
					if(strpos($routetag,'-EXE-BOD')!== false){
						$route = 'EN-CRECOM-EXEBOD';
					}
				

			}
			else if(strpos($routetag,'-SUBCRECOM')!== false){
				//TEMPORARY while no SUBCRECOM
				$endorse = 'Crecom - InP';
				$nextapprover = 'CRECOM';
				$message = 'ALMH endorsed to CRECOM';		
				$route = 'EN-CRECOM';
			}
			break;	
			case 'Crecom - R':
				if(strpos($routetag,'-CRECOM')!== false){
					$endorse = 'ALMH - ENEXEBOD';
					$nextapprover = 'BOARD';
					$message = 'ALMH endorsed to BOARD';		
					$route = 'EN-EXE-BOD';
				}
				else if(strpos($routetag,'-SUBCRECOM')!== false){
				
				}
			break;
		}
		
		$form->endorse_to->setValue($this->view->viewMa($nextapprover));
		$this->view->form = $form;

		if ($this->getRequest()->isPost()) {
	    $formData = $this->getRequest()->getPost();
		    if ($form->isValid($formData)) {  

			$capno = $this->_getParam('cap');

			//Update the account status 
			$accnt = new Model_BorrowerAccount();
			$data = array(
			'account_status'=>$endorse,
			'routetag'=>$route,
			'date_endorsed'=>date("r"),
			);
			$where = "capno like '$capno'";
			$accnt->update($data,$where);
			// End of Update to the Borrower Account

			//Insert in BorrowerCrawFormApprovalSection
			//For the Endorsement of ALMH 
			//Feb 22,2010 Paolo Marco Manarang
			$data = array(
			'capno'=>$capno,
			'decision'=>$message,
			'approved_by'=>login_user(),
			'reason'=>$formData['remarks'],
			'date_approval'=>date("m-d-Y h:i a"),
			'date_approval2'=>date("r"),
			'date_type'=>3,
			);
			$crawformapproval = new Model_BorrowerCrawFormApprovalsection();
			$crawformapproval->insert($data);	
			//use in crawform
			//End in Insert BorrowerCrawFormApprovalSection 
			
			//Insert Account History
			//Feb 22,2010 Paolo Marco Manarang
			$history = new Model_AccountHistory();
			$select = $history->select();
			$select->where('capno like ?',$capno)->order('id DESC');
			$historyDetail = $history->fetchRow($select);
			$hdata = array (
			'capno'=>$capno,
			'status'=>$endorse,
			'by'=>login_user(),
			'date'=>date("r"),
			'remarks'=>$formData['remarks'],
			'date_last'=>$historyDetail->date, 
			);
			$history->insert($hdata);
			//End of History
			$this->_helper->Accounts($capno,$endorse);

			$this->_helper->CrecomHelper->crecomMod2($capno,$route,'',$formData['remarks']);

			
		    $this->_redirect('/index/routedecisionview/cap/'.$capno);	

			}
		}
 
}

public function crawammendmentAction(){
		$this->_helper->viewRenderer->setNoRender(true);
		$capno = $this->_getParam('cap');
	
		$table = new Model_BorrowerAccount();
		$detail = $table->fetchRowModel($capno);
		
		if($table->chkIfRecon($capno)=== TRUE){
		    $this->_redirect('/index/amendmentform/cap/'.$capno.'/type/view');	
		}else{
		    $this->_redirect('/index/crawform/cap/'.$capno.'/type/print');	
		}
}


public function coscoreAction(){
		
		$this->_helper->viewRenderer->setNoRender(true);
		$capno = $this->_getParam('cap');
		
		$borrower = new Model_BorrowerAccount();
	
					$s = $this->_helper->ScoreModel($capno);
					$score = $this->_helper->$s($capno);
					$score_tag = $this->view->viewScore($score,getHighest($capno),'score');
					
					
					$data = array(
					'score'=>$score,
					'score_tag'=>$score_tag,		
					);
					$where = "capno like '$capno'";
					
					/**Audit Trail**/
							$accnt = new Model_BorrowerAccount();
							$select = $accnt->select();
							$select->where('capno like ?',$capno);
							$accntdetail = $accnt->fetchRow($select)->toArray();
							$this->_helper->AuditTrail($accntdetail,$data, $capno);
					/** End of Audit Trail **/
					$borrower->update($data, $where);

    $this->_redirect('/index/account/cap/'.$capno);	

}

public function rascoreAction(){
		
		$this->_helper->viewRenderer->setNoRender(true);
		$capno = $this->_getParam('cap');
		
			/*
			$s = $this->_helper->ScoreModel($capno);
			$score = $this->_helper->$s($capno);
		*/
			$s = 'ScoreModule';
			$s2 = 'ScoreModule2';
					$score = $this->_helper->$s($capno);
					$score2 = $this->_helper->$s2($capno);
		

   $this->_redirect('/index/account/cap/'.$capno);	

}
public function camartsAction(){
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');


			$this->_helper->viewRenderer('box-rts');
			
			$form = new Form_PopupBox();
			$this->view->form = $form;
			
			$capno = $this->_getParam('cap');
			$this->view->capno = $capno;
			$detail = 'Do you wish to return this application to the sender ?';
			$this->view->detail = $detail;
			
			if ($this->getRequest()->isPost()) {
				$formData = $this->getRequest()->getPost();
					if ($form->isValid($formData)) {
						
					$capno = $this->_getParam('cap');
					$borrower = new Model_BorrowerAccount();
					if (login_user_role() == 'CA'){
					$account_status = 'CA - RTMA';	
					}

					else if (login_user_role() == 'CO'){
					$account_status = 'CO - RTCA';	
					}

					else if (login_user_role() == 'CSH'){
					$account_status = 'CSH - RTCO';	
					}
					
					$this->_helper->ComakerModule->comakerRts($capno);
					
					//Change Account Status
					$data = array(
					'account_status'=>$account_status,
					);	
					$where = "capno like '$capno'";
					$borrower->update($data, $where);	
					//End of Change Account
					
					//Insert Account History
					$history = new Model_AccountHistory();
					$select = $history->select();
					$select->where('capno like ?',$capno)->order('id DESC');
					$historyDetail = $history->fetchRow($select);
								
					$hdata = array (
					'capno'=>$capno,
					'status'=>$account_status,
					'by'=>login_user(),
					'date'=>date("r"),
					'remarks'=>$form->getValue('submit_remarks'),
					'date_last'=>$historyDetail->date,
					);
					$history->insert($hdata);
					//End of History
					
					$this->view->isSubmit = "OK";
					}//End of isValid
			}//end of getRequest
			
			
			
	
	
}

public function reconAction(){
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		//$this->_helper->viewRenderer->setNoRender(true);
		
		$this->_helper->viewRenderer('recon-form');
		$capno = $this->_getParam('cap');
		
		$model = new Model_BorrowerAccount();
		/*
		if(!$model->chkApproved($capno)){
		$this->_redirect('/error/denied/');		
		}
		*/
		$statusModel = new Model_Admin_AccountStatus();
		$borrower = new Model_BorrowerAccount();
		$detailStatus = $borrower->getFieldValue($capno, 'account_status');
		if($statusModel->routeview($detailStatus,'view_recon_menu') === FALSE){
		$this->_redirect('/error/denied/');		
		}
		
		if(ifAlreadyRecon($capno)){
		$this->_redirect('/error/denied/');		
		}
		
		
		
		$form = new Form_Recon();
		//$this->_helper->ReconModule($capno);
		
		$this->view->form = $form;
	
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
				if ($form->isValid($formData)) {
				
				if(is_null($formData['reconvalues'])){
					echo "Please Check the fields to be change for Recon";
					exit;					
				}
				$capno = $this->_getParam('cap');
			

				$this->_helper->ReconModule($capno);
				$newrecon = capnorecon($capno)+1;
				$newcap = capnoseprecon($capno).$newrecon;	
				
				//Store the Checked Values in the database
				$string = implode(',',$formData['reconvalues']);
				$table = new Model_BorrowerAccount();
				
				//Check if ey and di is in the checkbox  				
				$recon_type ='normal';	
				if (in_array("effective_yield", $formData['reconvalues'])) {
				$recon_type = 'eydi';
				}
				if (in_array("dealer_incentive", $formData['reconvalues'])) {
				$recon_type = 'eydi';
				}
				// end of checking
				
				$array = array(
				'recon_fields' => $string, 
				'recon_type' => $recon_type
				);	
				$where = "capno like '$newcap'";
				$table->update($array, $where);	
				// End of Store
				
					//Insert Account History
					$history = new Model_AccountHistory();
					$select = $history->select();
					$select->where('capno like ?',$capno)->order('id DESC');
					$historyDetail = $history->fetchRow($select);
					$hdata = array (
					'capno'=>$newcap,
					'status'=>'MA - S',
					'by'=>login_user(),
					'date'=>date("r"),
					'remarks'=>'RECON '.$string,
					'date_last'=>$historyDetail->date,
					);
					$history->insert($hdata);
					//End of History
				
				$this->_redirect('/index/account/cap/'.$newcap);	

				}// end of isValid
		} //end of isPost
}

public function viewreconAction(){
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style.css');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
 	$this->_helper->viewRenderer('recon-view');
	$capno = $this->_getParam('cap');
	
	
		$oldcap = returnprevCap($capno);
	
		$this->view->oldcap = $oldcap; 
		$this->view->capno = $capno;
		
		$model = new Model_BorrowerAccount();
		$oldDetail = $model->fetchRowModel($oldcap);
		$currDetail = $model->fetchRowModel($capno);
		
		$form = new Form_Recon();		
		$form->submit_remarks->setValue($currDetail->recon_remarks_ca);
		$this->view->form = $form;
		$this->view->old = $oldDetail;
		$this->view->curr = $currDetail;
		$this->view->oldtotalcombine = totalcombinedincome($oldcap);
		$this->view->currtotalcombine = totalcombinedincome($capno);
		

		$this->view->reconfields = $this->_helper->ReconModule->chkreconChanges($capno,'fields');

		
		//echo nl2br($currDetail->recon_remarks_ca);
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
				if ($form->isValid($formData)) {
					
				$table = new Model_BorrowerAccount();
	
					$array = array(
					'recon_remarks_ca'=> $form->getValue('submit_remarks')					
					);
					
						
					$where = "capno like '$capno'";
					$table->update($array, $where);	
	
					$this->_redirect('/index/viewrecon/cap/'.$capno);	
		
					
					
				}// end of isValid
		}// end of isPost
		
}

public function chgrecontypeAction(){
	$this->_helper->viewRenderer->setNoRender(true);
	$capno = $this->_getParam('cap');
	
	$table = new Model_BorrowerAccount();
	$array = array(
	'recon_type' => 'eydi' 
	);
	
	$where = "capno like '$capno'";
	$table->update($array, $where);	
	
	$this->_redirect('/index/viewrecon/cap/'.$capno);	
	
}


public function amendmentformAction(){
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
	$capno = $this->_getParam('cap');

		$type = $this->_getParam('type');
		$save = $this->_getParam('save');
		$this->view->isSubmit = $save;
		
		$table = new Model_BorrowerAccount();
		$detail = $table->fetchRowModel($capno);
		if($type == 'prepare'){
		$this->_helper->viewRenderer('recon-amendment-prepare');
		$form = new Form_Recon();
		$form->submit_remarks->setValue($detail->recon_remarks_co);
		
		$crawform = new Model_BorrowerCrawForm();		
		$select = $crawform->select();
		$select->where("capno like ?",$capno);
		$crawformdetail = $crawform->fetchRow($select);
		$form->deviationfield->setValue($crawformdetail->deviationfield);
		
		$this->view->form = $form;
		$this->_helper->UpdateAcntStatus($capno,'edit');
		}else if ($type == 'view'){
			/*
			$otherdev = new Model_BorrowerCrawForm();
			$select = $otherdev->select();
			$select->where('capno like ?',$capno);
			$otherdevDetail = $otherdev->fetchRow($select);
			$this->view->otherdev = $otherdevDetail;
			*/		
		$crawform = new Model_BorrowerCrawForm();		
		$select = $crawform->select();
		$select->where("capno like ?",$capno);
		$crawformdetail = $crawform->fetchRow($select);
		$this->view->craw = $crawformdetail;
		
		$this->_helper->viewRenderer('recon-amendment-print');
		}
	
	$oldcap = returnprevCap($capno);


	$oldDetail = $table->fetchRowModel($oldcap);
	$amend = explode(',',$detail->recon_fields);

	$this->view->detail = $detail;
	$this->view->old = $oldDetail;
	$this->view->amend = $amend;
	$this->view->oldcap = $oldcap;
	$this->view->capno = $capno;
	$this->view->oldgross = ceil($oldDetail->monthly_amortization) * 1.03;
	$this->view->currgross = ceil($detail->monthly_amortization) * 1.03;
	$this->view->oldpn = ceil($oldDetail->monthly_amortization) * $oldDetail->loanterm;
	$this->view->currpn = ceil($detail->monthly_amortization) * $detail->loanterm;
	$this->view->highcap = getHighest($capno);
 	$this->view->oldtotalcombine = totalcombinedincome($oldcap);
	$this->view->currtotalcombine = totalcombinedincome($capno);


		
		//show remarks from ca
			$history = new Model_AccountHistory();
			$select = $history->select();
			$select->where('capno like ?',$capno);
			$select->where("status = 'CA - ReAp' OR status = 'CA - ReR' OR status = 'CA - OR' OR status = 'CA - P'");	
			$select->order('id DESC');
			$historyDetail = $history->fetchRow($select);
			$this->view->remark_ca = $historyDetail ;
		//
		
			$decision = new Model_BorrowerCrawFormApprovalsection();
			$select = $decision->select();
			$select->where('capno like ?',$capno)->order('id ASC');
			$approvaldetail= $decision->fetchAll($select);
			$this->view->approvaldetail = $approvaldetail;
	
			$table = new Model_BorrowerDeviation();
			$select = $table->select();
			$select->where('capno like ?',$capno);
			$row = $table->fetchRow($select);
			$this->view->remark = $row;
			
				$table = new Model_BorrowerAccount();

				if($table->getComaker($capno)){	
				$comakerCapno = $table->getComaker($capno);	
			
				$select = $table->select();
				$select->where('capno like ?',$comakerCapno);
				$row = $table->fetchRow($select);
				$this->view->comaker_remark = $row;
			
				}//end of comaker
			$this->view->prevcapno = returnprevCap($capno);
	
			if ($this->getRequest()->isPost()) {
				$formData = $this->getRequest()->getPost();
					if ($form->isValid($formData)) {
						
 	  			   $this->_helper->UpdateAcntStatus($capno,'save');
					$table = new Model_BorrowerAccount();

					if($formData['button'] == "Submit CO Remarks"){
					
					$array = array(
					'recon_remarks_co' => $form->getValue(submit_remarks),
					'recon_amendment_date' => $formData['date'],
					);
					
					$where = "capno like '$capno'";
					$table->update($array, $where);	
					
					//********* Deviation Field ***//
					$data = array(
					'deviationfield' => $form->getValue('deviationfield'),
					);
					
					$table = new Model_BorrowerCrawForm();
					$sql = $table->select()->where('capno LIKE ?',$capno);

					if($table->fetchAll($sql)->count() == 0){
					$table->insert($data);	
					//if no record found insert data
					}
					else{
					//else update using the capno
					$where = "capno like '$capno'";
					$table->update($data,$where);						
					}	
					/**** End of Deviation ****/
					}
					
					else if($formData['button'] == "Add"){
						
					$data = array(
					'capno'=>$capno,
					'decision'=>$form->getValue('decision'),
					'approved_by'=>$form->getValue('approved_by'),
					'reason'=>$form->getValue('reason'),
					'date_approval'=>date('m-d-Y h:i a'),	
					'date_approval2'=>date("r"),
					'date_type'=>3,
					);
					$table = new Model_BorrowerCrawFormApprovalsection();
					$table->insert($data);								
					}
					
					$this->_redirect('/index/amendmentform/cap/'.$capno.'/type/prepare/save/ok');	
					}// end of isValid
					
				}// End of getRequest
	
}

public function reprocessAction(){
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		//$this->_helper->viewRenderer->setNoRender(true);
		
		$this->_helper->viewRenderer('reprocess-form');
		$capno = $this->_getParam('cap');
		
		$model = new Model_BorrowerAccount();
		
		
		$form = new Form_Recon();
		$this->view->form = $form;
	
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
				if ($form->isValid($formData)) {
			
				$this->_helper->ReprocessModule($capno);
				$newrecon = capnorecon($capno)+1;
				$newcap = capnoseprecon($capno).$newrecon;	
				
					//Insert Account History
					$history = new Model_AccountHistory();
					$select = $history->select();
					$select->where('capno like ?',$capno)->order('id DESC');
					$historyDetail = $history->fetchRow($select);
					$hdata = array (
					'capno'=>$newcap,
					'status'=>'MA - S',
					'by'=>login_user(),
					'date'=>date("r"),
					'remarks'=>'REPROCESS '.$form->getValue('submit_remarks'),
					'date_last'=>$historyDetail->date,
					);
					$history->insert($hdata);
					//End of History
				
				$this->_redirect('/index/account/cap/'.$newcap);	

				}// end of isValid
		} //end of isPost
}
public function othersAction(){
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');

			$this->_helper->viewRenderer('box-rts');
			
			$form = new Form_PopupBox();
			$this->view->form = $form;
			
			$capno = $this->_getParam('cap');
			$act = $this->_getParam('act');

			$this->view->capno = $capno;
			if($act == "outrightreject"){
			$detail = 'Outright Reject this Application ?';
			}
			if($act == "noaction"){
			$detail = 'Mark this Application as NO ACTION ?';
			}
			if($act == "cancel"){
			$detail = 'Cancel this Application ?';
			}
			if($act == "reprocess"){
			$detail = 'Reprocess this Application ?';
			}
			if($act == "mareject"){
			$detail = 'Reject this Application ?';
			}
			
			if($act == "recon"){
			$detail = 'Recon this Application ?';
			}
			if($act == "recall"){
			$detail = 'Recall this Application ?';
			}
			$history = new Model_AccountHistory();
			$select = $history->select();
			$select->where('capno like ?',$capno);	
			$select->order('id DESC');
			$historyDetail = $history->fetchRow($select);					
			$this->view->lastremarks = $historyDetail->remarks;
			$this->view->lastremarksby = $historyDetail->by;
			$this->view->detail = $detail;
			
			if ($this->getRequest()->isPost()) {
				$formData = $this->getRequest()->getPost();
					if ($form->isValid($formData)) {
						
					$capno = $this->_getParam('cap');
					$act = $this->_getParam('act');
					$borrower = new Model_BorrowerAccount();

				if(login_user_role() == 'CA'){
					if($act == "outright"){
					$account_status = "CA - OR";
					}
					else if($act == "noaction"){
					$account_status = "CA - NA";
					}
					else if($act == "cancel"){
					$account_status = "CA - Cancel";
					}		
					else if($act == "recall"){
					$account_status = "CA - An";
					$addedRemarks = "System Stamp Recall";
					}			
					$data = array(
					'account_status'=>$account_status,
					'submitted_co_date'=>date("r"),
					);	
					
				} // End of CA
				else if(login_user_role() == 'CO'){
					if($act == "outright"){
					$account_status = "CO - OR";
					}
					else if($act == "noaction"){
					$account_status = "CO - NA";
					}
					else if($act == "cancel"){
					$account_status = "CO - Cancel";
					}		
					//Change Account Status for CO
					$data = array(
					'account_status'=>$account_status,
					'submitted_co_date'=>date("r"),
					);	
				} // End of CO
					
				else if(login_user_role() == 'MA'){
					if($act == "reprocess"){
					$account_status = "MA - E";
					
					$data = array(
					'account_status'=>$account_status,
					'account_status2'=>"Reprocess",
					);	
					$addedRemarks = "System Stamp Reprocess";
					}
					
					if($act == "mareject"){
					$account_status = "MA - R";
					
					$data = array(
					'account_status'=>$account_status,
					);	
					
					}
					
					if($act == "recon"){
					$account_status = "MA - E";
					
					$data = array(
					'account_status'=>$account_status,
					'account_status2'=>"Recon",
					);	
					}
					

					
				}// End of MA
			
					$where = "capno like '$capno'";
					$borrower->update($data, $where);	
					//End of Change Account
					
					//Insert Account History
					$history = new Model_AccountHistory();
					$select = $history->select();
					$select->where('capno like ?',$capno)->order('id DESC');
					$historyDetail = $history->fetchRow($select);
								
					$hdata = array (
					'capno'=>$capno,
					'status'=>$account_status,
					'by'=>login_user(),
					'date'=>date("r"),
					'remarks'=>$addedRemarks.' - '.$form->getValue('submit_remarks'),
					'date_last'=>$historyDetail->date,
					);
					$history->insert($hdata);
					//End of History
					}//End of isValid
			}//end of getRequest
			
			
			
	
	
}




public function confirmboxAction(){
	
	
			$this->_helper->viewRenderer('box-confirmation');
			
			$form = new Form_PopupBox();
			$this->view->form = $form;
			
			$capno = $this->_getParam('cap');
			$action = $this->_getParam('act');
			
			if ($action == 'submit'){
			$detail = 'Are you submitting this account to Credit Services ?';
			}
			else if($action == 'approval'){
			$detail = 'Are you recommending this account for approval to Credit Services ?';
			}		
			else if($action == 'reject'){
			$detail = 'Are you recommending this account for rejection to Credit Services ?';
			}
			else if($action == 'outrightreject'){
			$detail = 'Do you wish to mark this application as Outright Reject ?';
			}
			else if($action == 'conditionalreject'){
			$detail = 'Do you wish to mark this application as Conditional Reject ?';		
			}
			else if($action == 'recon'){
			$detail = 'Do you wish to mark this application as Recon ?';		
			}
			
			$this->view->detail = $detail;
			$this->view->capno = $capno;
			
			if ($this->getRequest()->isPost()) {
				$formData = $this->getRequest()->getPost();
					if ($form->isValid($formData)) { 
					
					$capno = $this->_getParam('cap');
					$action = $this->_getParam('act');
					
					$this->_helper->ChkDeviation($capno);
					
					// Update Borrower Table
					$borrower = new Model_BorrowerAccount();
					$data = array(
					'account_status'=>$action,
					'score'=>$this->_helper->ScoreModule($capno),	
					);
					$where = "capno like '$capno'";
					$borrower->update($data, $where);
					
					//Insert Account Status
				
					$accountstatus = new Model_AccountHistory();
					$data = array(
					'capno' =>$capno,
					'status' =>$action,
					'remarks' => $form->getValue('submit_remarks'),
					'date'=>date("r"),
					'by'=>  login_user(),
					);
					$accountstatus->insert($data);
					
					
					
					}// End of IsValid
			}// End of GetRequest
}

public function requestciboxAction(){
			$capno = $this->_getParam('cap');

	
			$this->_helper->viewRenderer('box-request-ci');
			
			$form = new Form_PopupBox();
  
  			$table = new Model_BorrowerEmployment();
			$sql = $table->select()->where('capno like ?',$capno)->order("id ASC");
		 	foreach ($table->fetchAll($sql,"id ASC") as $c) {
         	$form->employment_address->addMultiOption($c->emp_address, $c->emp_address);} 			
			
			$table = new Model_BorrowerAccount();
			$select = $table->select();
			$select->where('capno like ?',$capno);
			$accountdetail = $table->fetchRow($select);
			$this->view->row = $accountdetail;	
			
			$form->permanent_address->setValue($accountdetail->pres_address_no.' '.
			
			$accountdetail->pres_address_st.' '.
			$accountdetail->pres_address_brgy.' '.
			strtoupper($accountdetail->pres_address_city).' '.
			$accountdetail->pres_address_province
			 );

			$table = new Model_BorrowerEmployment();
			$sql = $table->select()->where('capno like ?',$capno)->order("id ASC");
		 	foreach ($table->fetchAll($sql,"id ASC") as $c) {
         	$form->employment_address->addMultiOption($c->emp_address, $c->emp_address);} 			
			
			$form->unit->setValue($accountdetail->veh_unit);
			
  			$table = new Model_BorrowerBusiness();
			$sql = $table->select()->where('capno like ?',$capno)->order("id ASC");
		 	foreach ($table->fetchAll($sql,"id ASC") as $c) {
         	$form->business_address->addMultiOption($c->bus_address, $c->bus_address);} 			
			
			

			
				
			$this->view->employmentdetail = $employmentdetail;	
			$this->view->businessdetail = $businessdetail;		
			$this->view->form = $form;
			$this->view->capno = $capno;
			
			if ($this->getRequest()->isPost()) {
				$formData = $this->getRequest()->getPost();
					if ($form->isValid($formData)) {  
					$capno = $this->_getParam('cap');
					$action = 'requestci';
					if ($form->getValue('chk_employer_address') == true){
					$address = $form->getValue('employment_address');
					}
					if ($form->getValue('chk_business_address') == true){
					$address = $form->getValue('business_address');
					}
					if ($form->getValue('chk_permanent_address') == true){
					$address = $form->getValue('permanent_address');
					}
	
					$data = array(
					'capno' => $capno,
					'manner_checking' => $form->getValue('manner_checking'),
					'address'=>$address,
					'date_assigned'=>date("r"),
					'unit'=>$form->getValue('unit'),
					'requested_by'=>login_user(),
					);
					
					$table = new Model_BorrowerRequestCI();
					$table->insert($data);
					
					// Update Borrower Table
					$borrower = new Model_BorrowerAccount();
					$data = array(
					'account_status'=>$action,	
					);
					$where = "capno like '$capno'";
					$borrower->update($data, $where);
					
					//Insert Account Status
				
					$accountstatus = new Model_AccountHistory();
					$data = array(
					'capno' =>$capno,
					'status' =>$action,
					//'remarks' => $form->getValue('submit_remarks'),
					'date'=>date("r"),
					'by'=>  login_user(),
					);
					$accountstatus->insert($data);
			
					}//End of IsValid 
			}// End of Ispost

} // End of Request Box

public function inboxsubmittedAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery.tablesorter.min.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tableheader.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('inbox-submitted'); 
		
		$accnt = new Model_BorrowerAccount();
		
		if(login_user_role() == "MA"){
		$select = $accnt->select();
		$select->where("account_status = 'MA - S' OR account_status = 'CA - An' OR account_status = 'CA - AnD'");
		$select->where('created_by like ?', login_user())->order('borrower_lname');;
		$accntdetail = $accnt->fetchAll($select);
		$this->_helper->viewRenderer('inbox-submitted-ma'); 		
		}
		
		if(login_user_role() == "LA"){
		$select = $accnt->select();
		$select->where("account_status = 'LA - ChkDoc'");
		$select->where('submitted_la like ?', login_user());
		$accntdetail = $accnt->fetchAll($select);			
		$this->_helper->viewRenderer('inbox-submitted-la'); 		
		}
		
		if(login_user_role() == "MO" || login_user_role() == "ALMH"){
		$select = $accnt->select();
		$select->where("account_status = 'LA - ChkDoc' OR account_status = 'MA - PreB' OR account_status = 'LO - RTLA'");
		//$select->where('submitted_la like ?', login_user());
		$accntdetail = $accnt->fetchAll($select);		
		$this->_helper->viewRenderer('inbox-submitted-mo-almh'); 		
		}		
		
		$this->view->rows = $accntdetail;
	}
	

	
public function inboxbookedAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery.tablesorter.min.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tableheader.css');
		$this->_helper->viewRenderer('inbox-booked'); 

		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		$select->where("account_status = 'Booked'");
		$select->order("date_booked DESC");
		$accntdetail = $accnt->fetchAll($select);	

		$this->view->rows = $accntdetail;


}
	public function inboxpendingAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery.tablesorter.min.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tableheader.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('inbox-pending'); 
		

		
		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		
		if(login_user_role() == "MA"){
		$select->where("account_status = 'MA - E' OR account_status = 'MA - EN' OR account_status = 'NA'");
		$select->where('relation like ?', 'Principal');
		$select->where('created_by like ?', login_user())->order('borrower_lname');
		$this->_helper->viewRenderer('inbox-pending-ma');
		}
		
		if(login_user_role() == "CA"){

		$select->where("account_status = 'MA - S' OR account_status = 'CA - An' OR account_status = 'CA - AnD'");
		$select->where('relation like ?', 'Principal');	
		$select->where('submitted_ca like ?', login_user());
		$select->order('submitted_ca_date DESC');
		$this->_helper->viewRenderer('inbox-pending-ca');
		}
		
		if(login_user_role() == "CO"){
		$select->where("account_status = 'CA - ReAp' OR account_status = 'CA - ReR' OR account_status = 'CO - REV' OR account_status = 'CO - REVD' OR account_status = 'CA - OR'
		OR account_status = 'CA - NA' OR account_status = 'CA - Cancel' OR account_status = 'CA - P'");
		$select->where('relation like ?', 'Principal');
		$select->order('submitted_co_date DESC');
		//$select->where('submitted_co like ?', login_user());
		$this->_helper->viewRenderer('inbox-pending-co');
		}
		
		if(login_user_role() == "LA"){
		
		/*	
		$select->where("account_status = 'CO - Ap' OR account_status = 'CSH - Ap' OR account_status = 'CMGH - Ap' OR account_status = 'PRES - Ap'
		OR account_status = 'Approved'
		");
		*/
		
		$select->where("account_status = 'MA - PreB' OR account_status = 'LA - Recall'");
		$select->where('relation like ?', 'Principal')->order("submitted_mala_date DESC");
		$select->where('submitted_la like ?', login_user());
		$this->_helper->viewRenderer('inbox-pending-la');
		}
		
		if(login_user_role() == "LO"){
		$select->where("account_status = 'LA - ChkDoc'");
		$select->where('relation like ?', 'Principal')->order("submitted_lalo_date DESC");
		//$select->where('submitted_lo like ?', login_user());
		$this->_helper->viewRenderer('inbox-pending-la');		

		}
		

		if(login_user_role() == 'CSH' || login_user_role() == 'CMGH'
		|| login_user_role() == 'PRES' || login_user_role() == 'ALMH'
		) {
       $this->_helper->redirector('indexroute','index');
		}
		
		$accntdetail = $accnt->fetchAll($select);
		//$this->view->accntdetail = $accntdetail;
		$page=$this->_getParam('page',1);
    	$paginator = Zend_Paginator::factory($accntdetail);
    	$paginator->setItemCountPerPage(10);
    	$paginator->setCurrentPageNumber($page);
    	$this->view->accntdetail=$paginator;
	

	}
	
	public function inboxrtsAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery.tablesorter.min.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tableheader.css');	
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		//$this->_helper->viewRenderer('inbox-rts'); 
		
		$action = $this->_getParam('act');
		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		$role = login_user_role();
		$user = Zend_Auth::getInstance()->getIdentity();

		if($action == 'pending'){
			if ($role == "MA"){
			$select->where("account_status = 'CA - RTMA' OR account_status = 'LA - RTMA'  ");
			$select->where('created_by like ?', $user->username);
			$select->where('relation like ?', 'Principal')->order('borrower_lname');
			$accntdetail = $accnt->fetchAll($select);
			$this->view->accntdetail = $accntdetail;
			$this->_helper->viewRenderer('inbox-rts-ma');
			}
			if ($role == "CA"){
	
			$select->where('account_status like ?','CO - RTCA');
			$select->where('relation like ?', 'Principal');
			$select->where('submitted_ca like ?', $user->username)->order('borrower_lname');
			$accntdetail = $accnt->fetchAll($select);
			$this->view->accntdetail = $accntdetail;	
			$this->_helper->viewRenderer('inbox-rts-ca');
			}
			
			if ($role == "CO"){
			$select->where('account_status like ?','CSH - RTCO');
			$select->where('relation like ?', 'Principal');
			//$select->where('submitted_co like ?', $user->username)->order('borrower_lname');
			$accntdetail = $accnt->fetchAll($select);
			$this->view->accntdetail = $accntdetail;	
			$this->_helper->viewRenderer('inbox-rts-co');
			}
			
			if ($role == "LA"){
			$select->where('account_status like ?','LO - RTLA');
			$select->where('relation like ?', 'Principal');
			//$select->where('submitted_co like ?', $user->username)->order('borrower_lname');
			$accntdetail = $accnt->fetchAll($select);
			$this->view->accntdetail = $accntdetail;	
			$this->_helper->viewRenderer('inbox-rts-la');
			}			
	
			
		}
		else if($action == 'submitted'){
			
		if ($role == "CA"){

		$select->where('account_status like ?','CA - RTMA');
		$select->where('relation like ?', 'Principal');
		$select->where('submitted_ca like ?', $user->username)->order('borrower_lname');
		$accntdetail = $accnt->fetchAll($select);
		$this->view->accntdetail = $accntdetail;	
		$this->_helper->viewRenderer('inbox-rts-ca');
		}
		
		if ($role == "CO"){
		$select->where('account_status like ?','CO - RTCA');
		$select->where('relation like ?', 'Principal');
		//$select->where('submitted_co like ?', $user->username)->order('borrower_lname');
		$accntdetail = $accnt->fetchAll($select);
		$this->view->accntdetail = $accntdetail;	
		$this->_helper->viewRenderer('inbox-rts-co');
		}	

		$this->view->act = $action;	
		}
	}
	
	public function manualapproveAction(){
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv3.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/menu.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
 	
		$capno = $this->_getParam('cap');	
			
		$detail = new Model_BorrowerAccount();
		$status = $detail->getAcntStatus($capno);
		$this->view->status = $status;
			
			if(login_user_role() == 'LA'){
			$table = new Model_CreditDecision();
			$form = new Form_CreditDecision();
		
		
			$crawform = new Model_BorrowerCrawFormApprovalsection();
			$select = $crawform->select();
			$select->where('capno like ?',$capno)->order('id ASC');
			$approveHistory = $crawform->fetchAll($select);
			$this->view->history = $approveHistory;
			$sql = $table->select()->where('capno LIKE ?',$capno);

				//if($table->fetchAll($sql)->count() == 0 && login_user_role() == "CO"){
			
					$this->_helper->viewRenderer('manual-approve');
					$detail = $table->fetchRow($sql);
					
					
					foreach($form->getElements() as $element) {
					$element->removeDecorator('DtDdWrapper');
					$element->removeDecorator('Label');
					}
					
					
				
			}
	
	$this->view->form = $form;

	$this->view->capno = $capno;


	if ($this->getRequest()->isPost()) {
		$formData = $this->getRequest()->getPost();
		if ($form->isValid($formData)) {  
		$capno = $this->_getParam('cap');	
				$detail = new Model_BorrowerAccount();

			if($formData['Submit'] == 'Submit'){
					$data = array(
					'account_status'=>$formData['application_decision'],
					'date_decided'=>date("r"),
					);
					$where = "capno like '$capno'";
					$detail->update($data,$where);	
				
					//Insert Account History
					$history = new Model_AccountHistory();
					$select = $history->select();
					$select->where('capno like ?',$capno)->order('id DESC');
					$historyDetail = $history->fetchRow($select);
					
					$hdata = array (
					'capno'=>$capno,
					'status'=>$form->getValue('application_decision'),
					'by'=>$form->getValue('application_name'),
					'date'=>date("r"),
					'remarks'=>'Manual Approve - '.$this->view->viewMa(login_user()).' '.$form->getValue('submit_remarks'),
					'date_last'=>$historyDetail->date,
					);
					$history->insert($hdata);
					//End of History	
					
					/**************************************************/
					$data = array(
					'account_status'=>'MA - PreB',
					'submitted_mala_date'=>date("r"),
					'submitted_la'=>'jutan'
					);
					$where = "capno like '$capno'";
					$detail->update($data,$where);	
				
					//Insert Account History
					$history = new Model_AccountHistory();
					$select = $history->select();
					$select->where('capno like ?',$capno)->order('id DESC');
					$historyDetail = $history->fetchRow($select);
					
					$hdata = array (
					'capno'=>$capno,
					'status'=>'MA - PreB',
					'by'=>login_user(),
					'date'=>date("r"),
					'remarks'=>'Manual MA PreBooking - '.$this->view->viewMa(login_user()).' '.$form->getValue('submit_remarks'),
					'date_last'=>$historyDetail->date,
					);
					$history->insert($hdata);
									
			}else if($formData['Submit'] == 'Add'){
				
					$data = array(
					'capno'=>$capno,
					'decision'=>$form->getValue('decision'),
					'approved_by'=>$form->getValue('approved_by'),
					'reason'=>$form->getValue('reason'),
					'date_approval'=>$formData['date'],
					'date_approval2'=>date("r"),
					'role'=> login_user_role(),
					'date_type'=>3,
					);
					$table = new Model_BorrowerCrawFormApprovalsection();
					$table->insert($data);	
				
			}

					$this->_redirect('/index/creditdecision/cap/'.$capno);	
		
		}
	}
		
		
		
		
	}
	
	public function inboxinprocessAction() {
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('inbox-inprocess'); 
		
		$accnt = new Model_BorrowerAccount();
		$statusTable = new Model_Admin_AccountStatus();
			$select = $accnt->select();
			foreach($statusTable->routeBox('inprocess_autorouting') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
			$select = $accnt->select();
			$select->order("date_decided DESC");	
		$select->where('relation like ?', 'Principal');
		$select->where(arrayString($condition));
		$accntdetail = $accnt->fetchAll($select);
		$page=$this->_getParam('page',1);
    	$paginator = Zend_Paginator::factory($accntdetail);
    	$paginator->setItemCountPerPage(20);
    	$paginator->setCurrentPageNumber($page);
    	$this->view->accntdetail=$paginator;	
	}
	public function inboxWithDecisionAction() {
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('inbox-with-decision'); 
		
		$status = $this->_getParam('status');

		$accnt = new Model_BorrowerAccount();
		$statusTable = new Model_Admin_AccountStatus();
		
		if($status == 'approved'){
			$select = $accnt->select();
			foreach($statusTable->routeBox('approve') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
		}
		else if($status == 'rejected'){
			$select = $accnt->select();
			foreach($statusTable->routeBox('reject') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
		}
		else {
			$select = $accnt->select();
			foreach($statusTable->routeBox('approve') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			foreach($statusTable->routeBox('reject') as $x){
			$select->orwhere('account_status like ?',$x->status);
			}
			$condition = $select->getPart(Zend_Db_Select::WHERE);
		}
		$select = $accnt->select()
		->order("date_decided DESC");	
			$startdate = date('Y-m-d',strtotime('-60 day'));
			$enddate = date('Y-m-d',strtotime('+1 day'));
			$select->where("date_decided between '$startdate' AND '$enddate'");
			$select->order("date_decided DESC");	
		$select->where('relation like ?', 'Principal');
		$select->where(arrayString($condition));

		if (login_user_role() == "MA"){
		//$select->where('created_by like ?', login_user());
		}
		if(login_user_role() == "MO" || login_user_role() == "ALMH"){
			
		}
		
		/*
			if($status == 'approved'){
				$select->where("account_status = 'CO - Ap' 
				OR account_status = 'CSH - Ap' 
				OR account_status = 'CMGH - Ap' 
				OR account_status = 'PRES - Ap'
				OR account_status = 'Approved'
				OR account_status = 'CRECOM - Ap'
				OR account_status = 'SUBCRECOM - Ap'
				OR account_status = 'EXEBOD - Ap'
				");
			}else if($status == 'rejected'){
				$select->where("account_status = 'CO - R' 
				OR account_status = 'CSH - R' 
				OR account_status = 'CMGH - R' 
				OR account_status = 'PRES - R'
				OR account_status = 'Rejected'
				OR account_status = 'CRECOM - R'
				OR account_status = 'SUBCRECOM - R'
				OR account_status = 'EXEBOD - R'
				");
			}else {
				$select->where("account_status = 'CO - Ap' 
				OR account_status = 'CSH - Ap' 
				OR account_status = 'CMGH - Ap' 
				OR account_status = 'PRES - Ap'
				OR account_status = 'Approved'
				OR account_status = 'CRECOM - Ap'
				OR account_status = 'SUBCRECOM - Ap'
				OR account_status = 'EXEBOD - Ap'
				OR account_status = 'CO - R' 
				OR account_status = 'CSH - R' 
				OR account_status = 'CMGH - R' 
				OR account_status = 'PRES - R'
				OR account_status = 'Rejected'
				OR account_status = 'CRECOM - R'
				OR account_status = 'SUBCRECOM - R'
				OR account_status = 'EXEBOD - R'
				");
				
			}	
			*/

		$accntdetail = $accnt->fetchAll($select);
		$page=$this->_getParam('page',1);
    	$paginator = Zend_Paginator::factory($accntdetail);
    	$paginator->setItemCountPerPage(20);
    	$paginator->setCurrentPageNumber($page);
    	$this->view->detail=$paginator;	
		
		
	}
	public function inboxRecommendedAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery.tablesorter.min.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tableheader.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('inbox-recommended'); 
		
		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		$role = login_user_role();
		$user = Zend_Auth::getInstance()->getIdentity();
		
		if ($role =="CA"){
		$select->where("account_status = 'CA - ReAp' OR account_status = 'CA - ReR'");
		$select->where('submitted_ca like ?', $user->username);
		}
		if ($role =="CO"){
		$select->where("account_status = 'CO - ReAp' OR account_status = 'CO - ReR' OR account_status = 'CSH - ReAp' OR
		account_status = 'CSH - ReR' OR account_status = 'CMGH - ReAp' OR account_status = 'CMGH - ReR'");
		}
		
		
		$accntdetail = $accnt->fetchAll($select);
		$this->view->accntdetail = $accntdetail;
	}
	public function inboxdecidedAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery.tablesorter.min.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tableheader.css');	
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('inbox-decided'); 
		
		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		$role = login_user_role();
		$user = Zend_Auth::getInstance()->getIdentity();
		
		if ($role == "CO"){
		$select->where("account_status = 'CO - Ap' OR account_status = 'CO - R' OR account_status = 'Approved' 
		OR account_status = 'Rejected'");
		$select->where('relation like ?', 'Principal');
		//$select->where('submitted_co like ?', $user->username);
		}
		
		$accntdetail = $accnt->fetchAll($select);
		//$this->view->rows = $accntdetail;
		
		$page=$this->_getParam('page',1);
    	$paginator = Zend_Paginator::factory($accntdetail);
    	$paginator->setItemCountPerPage(10);
    	$paginator->setCurrentPageNumber($page);
    	$this->view->rows=$paginator;		
	}
	
	public function inboxcancelAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery.tablesorter.min.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tableheader.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('inbox-cancel'); 
		$status = $this->_getParam('status');
		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		$role = login_user_role();
		$user = Zend_Auth::getInstance()->getIdentity();
		$select->where('relation like ?', 'Principal');

		/*
		if ($role == "MA"){
		$select->where("account_status = 'CO - Cancel' OR 
		account_status = 'CO - NA' OR account_status = 'CO - OR'
		OR account_status = 'MA - R'");
		//$select->where('created_by like ?', $user->username);
		}

		if ($role == "CO"){
		$select->where("account_status = 'CO - Cancel' OR 
		account_status = 'CO - NA' OR account_status = 'CO - OR'
		OR account_status = 'MA - R'
		");
		//$select->where('submitted_co like ?', $user->username);
		}*/
		
		if($status == 'cancel'){
		$select->where("account_status = 'CO - Cancel'");}
		else if($status == 'noaction'){
		$select->where("account_status = 'CO - NA'");}
		else if($status == 'outright'){
		$select->where("account_status = 'CO - OR'");}
		else if($status == 'mareject'){
		$select->where("account_status = 'MA - R'");}
		
		$accntdetail = $accnt->fetchAll($select);
		//$this->view->rows = $accntdetail;
		
		$page=$this->_getParam('page',1);
    	$paginator = Zend_Paginator::factory($accntdetail);
    	$paginator->setItemCountPerPage(10);
    	$paginator->setCurrentPageNumber($page);
    	$this->view->rows=$paginator;
	}
	
	public function assigntaskAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('assign-task'); 
	}
	
	public function assignmentcaAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('assignment-ca'); 
	}
	
	public function assignmentmaAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('assignment-ma'); 
	}
	
	public function bookingmelodyAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('booking-add'); 
		
		$form = new Form_Booking();
		//$capno = $this->_getParam('cap');
	
		$this->view->form=$form;
	}
	
public function reportAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('report'); 
		
		$form = new Form_Report();
		$this->view->form=$form;
		
		if(login_user_role() =='LA' || login_user_role()== 'LO'){
		$this->_redirect('/booking/report');	
		}else{
		$this->_redirect('/autorouting/accountsreport');	
		}
		
		
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {     
		
			$fromdate = $form->getValue('fromdate');
			if(!$fromdate) { $fromdate = '1999-12-30'; } 
			$todate = $form->getValue('todate');
			if(!$todate) { $todate = '2999-12-30'; } 
			
			$capno = $form->getValue('capno');
			$borrower_lname = strtoupper($form->getValue('search_borrower_lname'));
		
			$table = new Model_BorrowerAccount;
			//for marketing
			//$user = Zend_Auth::getInstance()->getIdentity();
			$select = $table->select();
				$select->where('capno like ?',$capno.'%')
				->where ('borrower_lname like ?',$borrower_lname.'%')
				->where('application_date >= ?', $fromdate)->where('application_date <= ?', $todate)
				->where('relation like ?', 'Principal') // Spouse Add
				->order('capno');
			$rows = $table->fetchAll($select);
			$this->view->rows = $rows;


		} //End of IsValid
	} // End of Request						
	}
	
	
	
	public function reportprintAction() {
	
		$this->_helper->viewRenderer('report-print'); 
		
		$table = new Model_BorrowerAccount;
		$select = $table->select();
		$rows = $table->fetchAll($select);
		$this->view->rows = $rows;
	
	}
	
	
	public function reportautoformAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('report-auto-form'); 
		
		$capno = $this->_getParam('cap');
		$this->view->capno = $capno;
		$this->view->date_printed = date('m/d/Y');
		
		$table = new Model_BorrowerAccount();
			$select = $table->select();
			$select->where('capno like ?',$capno);
			$accountdetail = $table->fetchRow($select);
			$this->view->row = $accountdetail;
			
		$employment = new Model_BorrowerEmployment();
			$select = $employment->select();
			$select->where('capno like ?',$capno);
			$empdetail = $employment->fetchAll($select);
			$this->view->empdetail = $empdetail;
			
		$business = new Model_BorrowerBusiness();
			$select = $business->select();
			$select->where('capno like ?', $capno);
			$busdetail = $business->fetchAll($select);
			$this->view->busdetail = $busdetail;
			
		$bank = new Model_BorrowerObBank();
			$select = $bank->select();
			$select->where('capno like ?',$capno);
			$bankdetail = $bank->fetchAll($select);
			$this->view->bankdetail = $bankdetail;
			
		$creditcard = new Model_BorrowerObCreditCard();
			$select = $creditcard->select();
			$select->where('capno like ?',$capno);
			$creditcarddetail = $creditcard->fetchAll($select);
			$this->view->creditcarddetail = $creditcarddetail;
			
		$loan = new Model_BorrowerObExistLoan();
			$select = $loan->select();
			$select->where('capno like ?',$capno);
			$loandetail = $loan->fetchAll($select);
			$this->view->loandetail = $loandetail;
	
	
		$trdbusref = new Model_BorrowerObTrdBusRef();
			$select = $trdbusref->select();
			$select->where('capno like ?',$capno);
			$trdbusrefdetail = $trdbusref->fetchAll($select);
			$this->view->trdbusrefdetail = $trdbusrefdetail;
			
		//Fetch Real Asset Details 
		$real = new Model_BorrowerOtherReal();
			$select = $real->select();
			$select->where('capno like ?',$capno);
			$realdetail = $real->fetchAll($select);
			$this->view->real_estatedetail = $realdetail;

		//Fetch AutoMobile Details 
			$auto= new Model_BorrowerOtherAuto();
			$select = $auto->select();
			$select->where('capno like ?',$capno);
			$autodetail = $auto->fetchAll($select);
			$this->view->automobiledetail = $autodetail;
		
		//Fetch SharesOfStock Details 
		$share= new Model_BorrowerOtherShare();
			$select = $share->select();
			$select->where('capno like ?',$capno);
			$sharedetail = $share->fetchAll($select);
			$this->view->sharesdetail = $sharedetail;		
		
//displaying spouse info		
		$accnt = new Model_BorrowerAccount();
			$select = $accnt->select()
				->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
				->where('relation LIKE ?','Spouse')
				->order("capno ASC");
			$accntdetail = $accnt->fetchAll($select);
			$this->view->detail = $accntdetail; 	
			
		$employment_spouse = new Model_BorrowerEmployment();
			$select = $employment_spouse ->select()
				->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
				->where('relation LIKE ?','Spouse')
				->order("capno ASC");
			$empdetail_spouse = $employment_spouse->fetchAll($select);
			$this->view->empdetail_sp = $empdetail_spouse;
			
		$business_spouse = new Model_BorrowerBusiness();
			$select = $business_spouse->select()
				->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
				->where('relation LIKE ?','Spouse')
				->order("capno ASC");
			$busdetail_spouse = $business_spouse->fetchAll($select);
			$this->view->busdetail_sp = $busdetail_spouse;
	
			
//displaying coborrower info			
		$accnt2 = new Model_BorrowerAccount();
			$select1 = $accnt2->select()
				->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
				->where('relation LIKE ?','Coborrower')
				->order("capno ASC");
			$accntdetail = $accnt2->fetchAll($select1);
			$this->view->detail3 = $accntdetail; 

		$employment_Cobo = new Model_BorrowerEmployment();
			$select = $employment_Cobo ->select()
				->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
				->where('relation LIKE ?','Coborrower')
				->order("capno ASC");
			$empdetail_Cobo = $employment_Cobo->fetchAll($select);
			$this->view->empdetail_cb = $empdetail_Cobo;
			
		$business_Cobo = new Model_BorrowerBusiness();
			$select = $business_Cobo->select()
				->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
				->where('relation LIKE ?','Coborrower')
				->order("capno ASC");
			$busdetail_Cobo = $business_Cobo->fetchAll($select);
			$this->view->busdetail_cb = $busdetail_Cobo;				
	}
		
		public function otherassetAction(){

		
			$this->_helper->viewRenderer('other-asset');
		
			$capno = $this->_getParam('cap');	
			$this->view->capno = $capno;
					
			$page = $this->_getParam('page');	
			
			if($page == 'ma'){
			$real = new Model_BorrowerOtherRealMa();
			$auto= new Model_BorrowerOtherAutoMa();
			$share= new Model_BorrowerOtherShareMa();		
			$bank = new Model_BorrowerObBankMa();
			$bf_asset = new Model_BorrowerOtherBusFinanceMa();
			}else if($page == 'ca'){
			$real = new Model_BorrowerOtherRealCa();
			$auto= new Model_BorrowerOtherAutoCa();
			$share= new Model_BorrowerOtherShareCa();	
			$bank = new Model_BorrowerObBankCa();
			$bf_asset = new Model_BorrowerOtherBusFinanceCa();
			}else {
			$real = new Model_BorrowerOtherReal();
			$auto= new Model_BorrowerOtherAuto();
			$share= new Model_BorrowerOtherShare();
			$bank = new Model_BorrowerObBank();
			$bf_asset = new Model_BorrowerOtherBusFinance();

			}

			//Fetch Real Asset Details 
			$select = $real->select();
			$select->where('capno like ?',$capno);
			$realdetail = $real->fetchAll($select);
			$this->view->real_estatedetail = $realdetail;
			
			//Fetch AutoMobile Details 
			$select = $auto->select();
			$select->where('capno like ?',$capno);
			$autodetail = $auto->fetchAll($select);
			$this->view->automobiledetail = $autodetail;
			
			//Fetch SharesOfStock Details 
			$select = $share->select();
			$select->where('capno like ?',$capno);
			$sharedetail = $share->fetchAll($select);
			$this->view->sharesdetail = $sharedetail;
			
			//Fetch Bank Details 
			$bank = new Model_BorrowerObBank();
			$select = $bank->select();
			$select->where('capno like ?',$capno);
			$bankdetail = $bank->fetchAll($select);
			$this->view->bankdetail = $bankdetail;
			
			//Fetch Business Financial Asset
			$select = $bf_asset->select();
			$select->where('capno like ?',$capno);
			$bfdetail = $bf_asset->fetchAll($select);
			$this->view->bfdetail = $bfdetail;
		}	
		
		public function otherasseteditAction(){
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		
			$this->_helper->viewRenderer('other-asset-edit');
		
			$capno = $this->_getParam('cap');	
			$this->view->capno = $capno;
					
			//Fetch Real Asset Details 
			$real = new Model_BorrowerOtherReal();
			$select = $real->select();
			$select->where('capno like ?',$capno);
			$realdetail = $real->fetchAll($select);
			$this->view->real_estatedetail = $realdetail;
			
			//Fetch AutoMobile Details 
			$auto= new Model_BorrowerOtherAuto();
			$select = $auto->select();
			$select->where('capno like ?',$capno);
			$autodetail = $auto->fetchAll($select);
			$this->view->automobiledetail = $autodetail;
			
			//Fetch SharesOfStock Details 
			$share= new Model_BorrowerOtherShare();
			$select = $share->select();
			$select->where('capno like ?',$capno);
			$sharedetail = $share->fetchAll($select);
			$this->view->sharesdetail = $sharedetail;
			
			//Fetch Bank Details 
			$bank = new Model_BorrowerObBank();
			$select = $bank->select();
			$select->where('capno like ?',$capno);
			$bankdetail = $bank->fetchAll($select);
			$this->view->bankdetail = $bankdetail;
			
			//Fetch Business Financial Details 
			$bus_finance = new Model_BorrowerOtherBusFinance();
			$select = $bus_finance->select();
			$select->where('capno like ?',$capno);
			$bus_finance_detail = $bus_finance->fetchAll($select);
			$this->view->bus_finance_detail = $bus_finance_detail;
			
			
			$form = new Form_OtherAsset();
			$this->view->form = $form;
			
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {  
			
			$capno = $this->_getParam('cap');	
			
			$accnt = new Model_BorrowerAccount();
			$select = $accnt->select();
			$select->where('capno like ?',$capno);
			$accntdetail = $accnt->fetchRow($select);			
			
			$relation = $accntdetail->relation;
				
				if ($formData['submit'] == 'Add Bank Details'){
		
				$table = new Model_BorrowerObBank(); 	
		
				$data = array(
				'capno' => $capno,
				'relation' => $relation,
				'bank' => $form->getValue('bank'),
				'branch' => strtoupper($form->getValue('branch')),
				'account_type' => $form->getValue('account_type'),
				'account_no' => $form->getValue('account_no'),
				'adb' => $form->getValue('adb'),
				'date_opened' => $form->getValue('date_opened')			
				);
				$table->insert($data);
				/**Audit Trail**/
				$this->_helper->AuditTrail->add($data,$capno);
				/** End of Audit Trail **/			
				
				$this->_redirect('/index/otherassetedit/cap/'.$capno);		
			 }

			 elseif ($formData['submit'] == 'Add Real Estate Details'){
		
				$table = new Model_BorrowerOtherReal(); 	
		
				$data = array(
				'capno' => $capno,
				'relation' => $relation,
				'location' => strtoupper($form->getValue('location')),
				'lot_area' => $form->getValue('lot_area'),
				'real_emv' => moneyconvert($form->getValue('real_emv')),
				);
				$table->insert($data);
				$this->_redirect('/index/otherassetedit/cap/'.$capno);		
			 }
			 elseif ($formData['submit'] == 'Add AutoMobile Details'){
			 	
				$table = new Model_BorrowerOtherAuto();
				
				$data = array(
				'capno' => $capno,
				'relation' => $relation,
				'year_make' => strtoupper($form->getValue('year_make')),
				'model' => strtoupper($form->getValue('model')),
				'auto_emv' => moneyconvert($form->getValue('auto_emv')),		
				);
				$table->insert($data);
				$this->_redirect('/index/otherassetedit/cap/'.$capno);	
			}
			elseif ($formData['submit'] == 'Add Shares of Stock'){
			 	
				$table = new Model_BorrowerOtherShare();
				
				$data = array(
				'capno' => $capno,
				'relation' => $relation,
				'company' =>strtoupper($form->getValue('company')),
				'num_share' => $form->getValue('num_share'),
				'shares_emv' => moneyconvert($form->getValue('shares_emv')),
				);	
				$table->insert($data);
				$this->_redirect('/index/otherassetedit/cap/'.$capno);
			}
			
			//for business_financial_asset
			elseif ($formData['submit'] == 'Add Business Financial Details'){
			 	
				$table = new Model_BorrowerOtherBusFinance();
				
				$data = array(
				'capno' => $capno,
				'relation' => $relation,
				'business_asset' =>strtoupper($form->getValue('business_asset')),
				'business_asset_emv' => moneyconvert($form->getValue('business_asset_emv')),
				'business_asset_remarks' =>strtoupper($form->getValue('business_asset_remarks')),
				
				);	
				$table->insert($data);
				$this->_redirect('/index/otherassetedit/cap/'.$capno);
			}
			
			 
			}// End of isValid
		}// End Get Request			
												
}// End of Other Asset Edit Action

public function delrealestateAction(){
    $this->_helper->viewRenderer->setNoRender(true);
	$id = $this->_getParam('id');
    $capno = $this->_getParam('cap');
    $real = new Model_BorrowerOtherReal();
    $where = $real ->getAdapter()->quoteInto('id = ?', $id);
    $real ->delete($where);
    $this->_redirect('/index/otherassetedit/cap/'.$capno);	
}


public function delautomobileAction(){
    $this->_helper->viewRenderer->setNoRender(true);
	$id = $this->_getParam('id');
    $capno = $this->_getParam('cap');
    $auto= new Model_BorrowerOtherAuto();
    $where = $auto->getAdapter()->quoteInto('id = ?', $id);
    $auto->delete($where);
    $this->_redirect('/index/otherassetedit/cap/'.$capno);	
}

public function delsharesstockAction(){
    $this->_helper->viewRenderer->setNoRender(true);
	$id = $this->_getParam('id');
    $capno = $this->_getParam('cap');
    $share= new Model_BorrowerOtherShare();
    $where = $share->getAdapter()->quoteInto('id = ?', $id);
    $share->delete($where);
    $this->_redirect('/index/otherassetedit/cap/'.$capno);	
}

//delete business finacial details
public function delbusfinanceAction(){
    $this->_helper->viewRenderer->setNoRender(true);
    $id = $this->_getParam('id');
    $capno = $this->_getParam('cap');
    $bus_finance = new Model_BorrowerOtherBusFinance();
    $where = $bus_finance ->getAdapter()->quoteInto('id = ?', $id);
    $bus_finance ->delete($where);
    $this->_redirect('/index/otherassetedit/cap/'.$capno);	
}

public function deviationlistAction(){
	$this->_helper->viewRenderer('deviation-list');
    $capno = $this->_getParam('cap');


	    $page = $this->_getParam('page');

		if($page == 'ma'){
		$dev = new Model_BorrowerDeviationMa();
		$table = new Model_BorrowerAccountMa();
		}else if($page == 'ca'){
		$dev = new Model_BorrowerDeviationCa();
		$table = new Model_BorrowerAccountCa();
		}else {
		$dev = new Model_BorrowerDeviation();
		$table = new Model_BorrowerAccount();
		}

		$select = $dev->select();
		$select->where('capno like ?',$capno);
		$devDetail = $dev->fetchRow($select);
		$this->view->devDetail = $devDetail;
	
		$select2 = $table->select();
		$select2->where('capno like ?',$capno);
		$detail = $table->fetchRow($select2);
		$this->view->detail = $detail;
	
		$devHelper = $this->_helper->chkDeviation($capno);
		$this->view->deviation = $devHelper;
		$this->view->devHelper = chkArray($devHelper);
		//$wDeviation = $this->_helper->chkDeviation($capno);
		//$countDev = chkArray($wDeviation);
		//echo $countDev;
		
	
}


public function audittrailAction(){
	$this->_helper->viewRenderer('audit-trail');
	
		$capno = $this->_getParam('cap');
		$this->view->capno = $capno;

		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		$select->where('capno like ?',$capno);
		$accntdetail = $accnt->fetchRow($select);
		$this->view->detail = $accntdetail; 

		$audit = new Model_AuditTrail();
		$select = $audit->select();
		$select->where('capno like ?',capnosep($capno).'_%');	
		$select->order('id DESC');
		$auditDetail = $audit->fetchAll($select);
		
		$this->view->auditDetail = $auditDetail;
}


public function accounthistoryAction(){
	$this->_helper->viewRenderer('account-history');
	$form = new Form_AccountHistory();
	foreach($form->getElements() as $element) {
	$element->removeDecorator('DtDdWrapper');
	$element->removeDecorator('Label');
	}
	
	$capno = $this->_getParam('cap');
	
	$accnt = new Model_BorrowerAccount();
	$select = $accnt->select();
	$select->where('capno like ?',$capno);
	$accntdetail = $accnt->fetchRow($select);
	$this->view->detail = $accntdetail; 
	
	$history = new Model_AccountHistory();
	$select = $history->select();
	$select->where('capno like ?',capnosep($capno).'_%');	
	$select->order('id DESC');
	$historyDetail = $history->fetchAll($select);

	$this->view->rows = $historyDetail;
	$this->view->form = $form;
	$this->view->capno = $capno;

}

public function mapageAction(){
	
	  	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/menu.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		
		$capno = $this->_getParam('cap');		
		
		$accnt = new Model_BorrowerAccountMa();
		$select = $accnt->select();
		$select->where('capno like ?',$capno);
		$accntdetail = $accnt->fetchRow($select);
		$this->view->detail = $accntdetail; 

		/*
		$veh = new Model_BorrowerVehDetails();
		$select = $veh->select();
		$select->where('capno like ?',$capno);
		$vehdetail = $veh->fetchRow($select);
		$this->view->vehdetail = $vehdetail; 
		
		
		$insurance = new Model_BorrowerInsurancePolicy();
		$select = $insurance->select();
		$select->where('capno like ?',$capno);
		$insurancedetail = $insurance->fetchRow($select);
		$this->view->insurancedetail = $insurancedetail; 
		
		
		$insurancecharges = new Model_BorrowerInsuranceCharges();
		$select = $insurancecharges->select();
		$select->where('capno like ?',$capno);
		$insurancecharges = $insurancecharges->fetchAll($select);
		$this->view->insurancecharges = $insurancecharges; 
		
		$insuranceperils = new Model_BorrowerInsurancePerils();
		$select = $insuranceperils->select();
		$select->where('capno like ?',$capno);
		$insuranceperils = $insuranceperils->fetchAll($select);
		$this->view->insuranceperils = $insuranceperils; 
		
		$craw = new Model_BorrowerCraw();
		$select = $craw->select();
		$select->where('capno like ?',$capno);
		$crawdetail = $craw->fetchRow($select);
		$this->view->crawdetail = $crawdetail; 
		*/		
		//Determine which layout to use depends on the relation		
		if ($accntdetail->relation == 'Spouse'){
			$this->_helper->viewRenderer('account-view-spouse-ma');
		}elseif ($accntdetail->relation == 'Coborrower'){
			$this->_helper->viewRenderer('account-view-coborrower-ma');
		}elseif ($accntdetail->relation == 'Principal'){
			$this->_helper->viewRenderer('account-view-borrower-ma');
		}else {
			$this->_helper->viewRenderer('error');
		}
		
		//Adding Spouse And Coborrower to the profile select
		$tables = new Model_BorrowerAccountMa();
		$form = new Form_AccountPageView();
		//$form->score->setValue($this->_helper->ScoreModule($capno));
		$form->car_history->setAttrib('Disabled','True')->setValue($accntdetail->car_history);
		$sql = $tables->select()
	    ->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where('relation LIKE ?','Spouse')
		->order("capno ASC");
		

		$sql2 = $tables->select()
	    ->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where('relation LIKE ?','Coborrower')
		->order("capno ASC");
		
		 foreach ($tables->fetchAll($sql,"capno ASC") as $c) {
         $form->profile->addMultiOption(BaseUrl().'/index/mapage/cap/'.$c->capno, strtoupper($c->relation).' - '.$c->borrower_lname.','.$c->borrower_fname);} 
		 
		 foreach ($tables->fetchAll($sql2,"capno ASC") as $c) {
         $form->profile->addMultiOption(BaseUrl().'/index/mapage/cap/'.$c->capno, strtoupper($c->relation).' - '.$c->borrower_lname.','.$c->borrower_fname);} 
		//end of Adding Spouse & Coborrower Info 
		
		$this->view->highcap = getHighest($capno);
		$this->view->form = $form;
		$this->view->capno = $capno;
		$this->view->application_date = date('m/d/Y',strtotime($accntdetail->application_date));
		$this->view->origcapno = capnosep($capno).'0'.capnorecon($capno);
	
	//Computer for the total income
	$sum = 0;
	$sum2 = 0;
	$capnosep = capnosep($capno);
	$recon = capnorecon($capno);
	
	$bus = new Model_BorrowerBusinessMa();
	$select = $bus->select();
	$select->where('capno like ?',$capno);
	$busdetail = $bus->fetchAll($select);
	
	foreach($busdetail as $detail){
    $sum += $detail->bus_income;
    }   
	
	
	$emp = new Model_BorrowerEmploymentMa();
	$select = $emp->select();
	$select->where('capno like ?',$capno)->where("employer = 'Current' or employer = 'Remittance' or employer = 'Investment' ");
	$empdetail = $emp->fetchAll($select);
	
	foreach($empdetail as $detail){
    $sum2 += $detail->emp_income;
    }   
	$this->view->totalincome = $sum+$sum2;
	//end of total income
	//start of totalcombinedincome
	$capnosep = capnosep($capno);
	$recon = capnorecon($capno);
	$sum = 0;
	$sum2 = 0;
	$bus = new Model_BorrowerBusinessMa();
	$select = $bus->select();
	$select->where('capno like ?',$capnosep.'_'.$recon)
	->where('relation NOT LIKE ?','Co-Maker');
	$busdetail = $bus->fetchAll($select);
	
	foreach($busdetail as $detail){
    $sum += $detail->bus_income;
    }   
	
	
	$emp = new Model_BorrowerEmploymentMa();
	$select = $emp->select();
	$select->where('capno like ?',$capnosep.'_'.$recon)->where("employer = 'Current' or employer = 'Remittance' or employer = 'Investment' ")
	->where('relation NOT LIKE ?','Co-Maker');
	$empdetail = $emp->fetchAll($select);
	
	foreach($empdetail as $detail){
    $sum2 += $detail->emp_income;
    }   
	$this->view->totalcombinedincome = $sum+$sum2;
	
	
	//end of totalcombineincome
}

public function capageAction(){
	
	  	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/menu.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');

		
		$capno = $this->_getParam('cap');		
		
		$accnt = new Model_BorrowerAccountCa();
		$select = $accnt->select();
		$select->where('capno like ?',$capno);
		$accntdetail = $accnt->fetchRow($select);
		$this->view->detail = $accntdetail; 

		$cv = new Model_BorrowerCVCa();
		$select = $cv->select();
		$select->where('capno like ?',$capno);
		$cvdetail = $cv->fetchRow($select);
		$this->view->cvdetail = $cvdetail; 
		
		$ci = new Model_BorrowerCICa();
		$select = $ci->select();
		$select->where('capno like ?',$capno);
		$cidetail = $ci->fetchRow($select);
		$this->view->cidetail = $cidetail; 
				
		/*
		$veh = new Model_BorrowerVehDetails();
		$select = $veh->select();
		$select->where('capno like ?',$capno);
		$vehdetail = $veh->fetchRow($select);
		$this->view->vehdetail = $vehdetail; 
		
		$insurance = new Model_BorrowerInsurancePolicy();
		$select = $insurance->select();
		$select->where('capno like ?',$capno);
		$insurancedetail = $insurance->fetchRow($select);
		$this->view->insurancedetail = $insurancedetail; 
		
		$insurancecharges = new Model_BorrowerInsuranceCharges();
		$select = $insurancecharges->select();
		$select->where('capno like ?',$capno);
		$insurancecharges = $insurancecharges->fetchAll($select);
		$this->view->insurancecharges = $insurancecharges; 
		
		$insuranceperils = new Model_BorrowerInsurancePerils();
		$select = $insuranceperils->select();
		$select->where('capno like ?',$capno);
		$insuranceperils = $insuranceperils->fetchAll($select);
		$this->view->insuranceperils = $insuranceperils; 
		
		$craw = new Model_BorrowerCraw();
		$select = $craw->select();
		$select->where('capno like ?',$capno);
		$crawdetail = $craw->fetchRow($select);
		$this->view->crawdetail = $crawdetail; 
		*/		
		//Determine which layout to use depends on the relation		
		if ($accntdetail->relation == 'Spouse'){
			$this->_helper->viewRenderer('account-view-spouse-ca');
		}elseif ($accntdetail->relation == 'Coborrower'){
			$this->_helper->viewRenderer('account-view-coborrower-ca');
		}elseif ($accntdetail->relation == 'Principal'){
			$this->_helper->viewRenderer('account-view-borrower-ca');
		}else {
			$this->_helper->viewRenderer('error');
		}
		
		//Adding Spouse And Coborrower to the profile select
		$tables = new Model_BorrowerAccountCa();
		$form = new Form_AccountPageView();
		//$form->score->setValue($this->_helper->ScoreModule($capno));
		$form->car_history->setAttrib('Disabled','True')->setValue($accntdetail->car_history);
		$sql = $tables->select()
	    ->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where('relation LIKE ?','Spouse')
		->order("capno ASC");
		

		$sql2 = $tables->select()
	    ->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where('relation LIKE ?','Coborrower')
		->order("capno ASC");
		
		 foreach ($tables->fetchAll($sql,"capno ASC") as $c) {
         $form->profile->addMultiOption(BaseUrl().'/index/capage/cap/'.$c->capno, strtoupper($c->relation).' - '.$c->borrower_lname.','.$c->borrower_fname);} 
		 
		 foreach ($tables->fetchAll($sql2,"capno ASC") as $c) {
         $form->profile->addMultiOption(BaseUrl().'/index/capage/cap/'.$c->capno, strtoupper($c->relation).' - '.$c->borrower_lname.','.$c->borrower_fname);} 
		//end of Adding Spouse & Coborrower Info 
		
		$this->view->highcap = getHighest($capno);
		$this->view->form = $form;
		$this->view->capno = $capno;
		$this->view->application_date = date('m/d/Y',strtotime($accntdetail->application_date));
		$this->view->origcapno = capnosep($capno).'0'.capnorecon($capno);
		//Computer for the total income
	$sum = 0;
	$sum2 = 0;
	$capnosep = capnosep($capno);
	$recon = capnorecon($capno);
	
	$bus = new Model_BorrowerBusinessCa();
	$select = $bus->select();
	$select->where('capno like ?',$capno);
	$busdetail = $bus->fetchAll($select);
	
	foreach($busdetail as $detail){
    $sum += $detail->bus_income;
    }   
	
	
	$emp = new Model_BorrowerEmploymentCa();
	$select = $emp->select();
	$select->where('capno like ?',$capno)->where("employer = 'Current' or employer = 'Remittance' or employer = 'Investment' ");
	$empdetail = $emp->fetchAll($select);
	
	foreach($empdetail as $detail){
    $sum2 += $detail->emp_income;
    }   
	$this->view->totalincome = $sum+$sum2;
	//end of total income
	//start of totalcombinedincome
	$capnosep = capnosep($capno);
	$recon = capnorecon($capno);
	$sum = 0;
	$sum2 = 0;
	$bus = new Model_BorrowerBusinessCa();
	$select = $bus->select();
	$select->where('capno like ?',$capnosep.'_'.$recon)
	->where('relation NOT LIKE ?','Co-Maker');
	$busdetail = $bus->fetchAll($select);
	
	foreach($busdetail as $detail){
    $sum += $detail->bus_income;
    }   
	
	
	$emp = new Model_BorrowerEmploymentCa();
	$select = $emp->select();
	$select->where('capno like ?',$capnosep.'_'.$recon)->where("employer = 'Current' or employer = 'Remittance' or employer = 'Investment' ")
	->where('relation NOT LIKE ?','Co-Maker');
	$empdetail = $emp->fetchAll($select);
	
	foreach($empdetail as $detail){
    $sum2 += $detail->emp_income;
    }   
	$this->view->totalcombinedincome = $sum+$sum2;
	
	
	//end of totalcombineincome
	
}

public function addressAction(){
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/menu.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
 
		$form = new Form_ListAddress();
		$this->view->form = $form;
		
		$field = $this->_getParam('field');	
		$this->view->field = $field;
	
	
	
}

public function vehicleAction(){
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv3.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/menu.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
 
		$form = new Form_Vehicle();
		$this->view->form = $form;
		
}


	
	public function crawformAction() {
		//Prepare Craw

	
		$capno = $this->_getParam('cap');
		$this->view->capno = $capno;
		$save = $this->_getParam('save');
		$this->view->isSubmit = $save;
		$type = $this->_getParam('type');
		
		if(login_user_role() == 'CO'){
			$this->_helper->viewRenderer('craw-form'); 	
		}
		
		if($type == 'prepare'){
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');	
		$this->_helper->viewRenderer('craw-form'); 	
		}else if ($type == 'print'){
			$otherdev = new Model_BorrowerCrawForm();
			$select = $otherdev->select();
			$select->where('capno like ?',$capno);
			$otherdevDetail = $otherdev->fetchRow($select);
			$this->view->otherdev = $otherdevDetail;
		$this->_helper->viewRenderer('craw-form-print'); 	
		}

 		$this->_helper->UpdateAcntStatus($capno,'edit');

		//show remarks from ca
			$history = new Model_AccountHistory();
			$select = $history->select();
			$select->where('capno like ?',$capno);
			$select->where("status = 'CA - ReAp' OR status = 'CA - ReR' OR status = 'CA - OR' OR status = 'CA - P'
			OR status = 'CA - Cancel' OR status = 'CA - NA'");	
			$select->order('id DESC');
			$historyDetail = $history->fetchRow($select);
			$this->view->remark_ca = $historyDetail ;
		//
			
		//to display justification----from deviation remarks
		$table = new Model_BorrowerDeviation();
		$select = $table->select();
		$select->where('capno like ?',$capno);
		$row = $table->fetchRow($select);
		$this->view->remark = $row;
		
		$form = new Form_CrawForm();
		$this->view->form=$form;
		
		//total business financial asset
		$this->view->total_asset = calctotalbfasset($capno);
		//total business financial liabilities
		$this->view->total_liabilities =calctotalbfliability($capno);
		
		//total existing loans from china bank/CBS
		$this->view->existing_auto = getExistLoan($capno,'totalvalue','Auto','CBCS');
		$this->view->existing_housing = getExistLoan($capno,'totalvalue','Home','CBCS');
		$this->view->existing_personal = getExistLoan($capno,'totalvalue','Personal','CBCS');
		$this->view->existing_business = getExistLoan($capno,'totalvalue','Business','CBCS');
		$this->view->existing_other = getExistLoan($capno,'totalvalue','Others','CBCS');
		$this->view->total_bankproper=$this->view->existing_auto + $this->view->existing_housing + $this->view->existing_personal + $this->view->existing_business + $this->view->existing_other;
		
		$this->view->existing_auto_loan = getExistLoan($capno,'totalvalueloan','Auto','CBCS');
		$this->view->existing_housing_loan = getExistLoan($capno,'totalvalueloan','Home','CBCS');
		$this->view->existing_personal_loan = getExistLoan($capno,'totalvalueloan','Personal','CBCS');
		$this->view->existing_business_loan = getExistLoan($capno,'totalvalueloan','Business','CBCS');
		$this->view->existing_other_loan = getExistLoan($capno,'totalvalueloan','Others','CBCS');		
		$this->view->total_bankproper_loan=$this->view->existing_auto_loan + $this->view->existing_housing_loan + $this->view->existing_personal_loan + $this->view->existing_business_loan + $this->view->existing_other_loan;		
		
		
		//total existing loans from chinabank in monthly amortization/CBS
		$this->view->auto_installment = getExistLoan($capno,'total','Auto','CBCS');
		$this->view->house_installment = getExistLoan($capno,'total','Home','CBCS');
		$this->view->personal_installment = getExistLoan($capno,'total','Personal','CBCS');
		$this->view->business_installment = getExistLoan($capno,'total','Business','CBCS');
		$this->view->other_installment = getExistLoan($capno,'total','Others','CBCS');
		
		$this->view->all_auto_installment = getExistLoan($capno,'all','Auto','CBCS');
		$this->view->all_house_installment = getExistLoan($capno,'all','Home','CBCS');
		$this->view->all_personal_installment = getExistLoan($capno,'all','Personal','CBCS');
		$this->view->all_business_installment = getExistLoan($capno,'all','Business','CBCS');
		$this->view->all_other_installment = getExistLoan($capno,'all','Others','CBCS');
		
		$this->view->total_installment_cbc = 
		getExistLoan($capno,'total','Auto','CBCS') + 
		getExistLoan($capno,'total','Home','CBCS')+ 
		getExistLoan($capno,'total','Personal','CBCS') + 
		getExistLoan($capno,'total','Business','CBCS')+ 
		getExistLoan($capno,'total','Others','CBCS');
		
		
		
		//total existing loans from other bank in monthly amortization
		$this->view->auto_other = getExistLoan($capno,'total','Auto', 'Others');
		$this->view->house_other = getExistLoan($capno,'total','Home','Others');
		$this->view->personal_other = getExistLoan($capno,'total','Personal','Others');
		$this->view->business_other = getExistLoan($capno,'total','Business','Others');
		$this->view->other_other = getExistLoan($capno,'total','Others','Others');
		
		$this->view->all_auto_other = getExistLoan($capno,'all','Auto', 'Others');
		$this->view->all_house_other = getExistLoan($capno,'all','Home','Others');
		$this->view->all_personal_other = getExistLoan($capno,'all','Personal','Others');
		$this->view->all_business_other = getExistLoan($capno,'all','Business','Others');
		$this->view->all_other_other = getExistLoan($capno,'all','Others','Others');
		
		$this->view->total_installment_others =	
		getExistLoan($capno,'total','Auto','Others') + 
		getExistLoan($capno,'total','Home','Others')+ 
		getExistLoan($capno,'total','Personal','Others') + 
		getExistLoan($capno,'total','Business','Others')+ 
		getExistLoan($capno,'total','Others','Others');
		
		//total existing loans both in monthly amortization
		$this->view->total_commitments_both=$this->view->total_installment_others + $this->view->total_installment_cbc;
		
		//total relationship deposit to cbs
		$this->view->total_relationship_deposit_cbs = caltotal_relationship_cbs($capno);
		
		//total gross_sales, total_cost_sales_total_net_income_before
		$this->view->total_gross_sales = caltotal_gross_sales($capno);
		$this->view->total_net_income_before = caltotal_net_income_before($capno);
		$this->view->total_cost_sales = caltotal_cost_sales($capno);
 		
		$this->view->highcap = getHighest($capno);
		
		$this->view->totalcombinedincome = totalcombinedincome($capno);
		$form->pn_amount->setValue($pn_amount->pn_amount);
		
		$table = new Model_BorrowerAccount();
			$select = $table->select();
			$select->where('capno like ?',$capno);
			$accountdetail = $table->fetchRow($select);
			$this->view->row = $accountdetail;
			
		$this->view->pn_amount =  ceil($accountdetail->monthly_amortization) * $accountdetail->loanterm;
		$this->view->applied_to_loanvalue =  100-$accountdetail->downpayment_percent;
		$this->view->penalty_charge=1.03;
		$this->view->gross_monthly_installment=ceil($accountdetail->monthly_amortization)* 1.03;
		
		
		$pn_amount=$this->view->pn_amount;
		$gross_monthly_installment=$this->view->gross_monthly_installment;
		$applied_to_loanvalue=$this->view->applied_to_loanvalue;
		$loan_to_ValCap=$this->view->loan_to_ValCap;
		
		//computation of total asset
		$total_autoemv=$this->view->total_autoemv;
		$total_realemv=$this->view->total_realemv;
		$total_shareemv=$this->view->total_shareemv;
		$total_asset=($total_autoemv)+($total_realemv)+(total_shareemv);
		
		$total_asset=$this->view->total_asset;
		
	
			//Fetch Cvremarks from cv tab regardless of relation
		$cv = new Model_BorrowerCv ();
			$select = $cv->select()
			->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
			->order("capno ASC");
			$cvdetail = $cv->fetchAll($select);
			$this->view->cvdetail = $cvdetail; 
		
		//Fetch CV for NFIS----mark's code
		$cv = new Model_BorrowerCv();
			$select = $cv->select()
			->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno));
			$cvdetail = $cv->fetchAll($select);
			foreach ($cvdetail as $detail){
			$nfisdetail[] = $detail->nfis;
			}
			
		//print_r_html($nfisdetail);
		$this->view->nfisdetail=$nfisdetail;

		// fetch ci remarks regardless or relation	
		$ci = new Model_BorrowerCI();
			$select = $ci->select()
			->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
			->order("capno ASC");
			$cidetail = $ci->fetchAll($select);
			$this->view->cidetail = $cidetail; 	
			
			
		//Fetch Existing Loans Remarks
		$loan = new Model_BorrowerObExistLoan();
			$select = $loan->select();
			$select->where('capno like ?',$capno);
			$loandetail = $loan->fetchAll($select);
			$this->view->loan= $loandetail;
		
		$this->view->savings_cbsdetail = getBank($capno,'all','SAVINGS','CBSI');
		$this->view->check_cbsdetail = getBank($capno,'all','CHECK','CBSI');
		$this->view->timedep_cbsdetail = getBank($capno,'all','TIME DEPOSIT','CBSI');	
		$this->view->others_cbsdetail = getBank($capno,'all','OTHERS','CBSI');	
		$this->view->total_all_bank = getBank($capno,'totalvalue','%','CBSI');
		//checking for loan_to ValCap
		if ($accountdetail->veh_status == '1') {
				$this->view->loan_to_ValCap= 80;
			}
			
		elseif($accountdetail->veh_status == '2') {
				$this->view->loan_to_ValCap= 70;
			}
		//---------check if values are within criteria, display yes no/na for veh_status
				
			if ($accountdetail->veh_status == '1' and $accntdetail->veh_age ==0){
				$this->view->within_veh_age= NA;
				$this->view->within_term= NA;
				$this->view->within_history= NA;}
		
			if ($accountdetail->veh_status == '1' ){
				$this->view->within_veh_age= NA;
				$this->view->within_term= NA;
				$this->view->within_history= NA;}

			elseif ($accountdetail->veh_status == '2' and $accntdetail->veh_age > 4){
				$this->view->within_veh_age= NO;}
			
			elseif ($accountdetail->veh_status == '2' and $accntdetail->loanterm > 36){
				$this->view->within_term= NO;}
			else {
				$this->view->within_term= YES;
				$this->view->within_veh_age= YES;
				$this->view->within_history= YES;
				}	
		//---------end of checking	
	
	
		$dev= new Model_BorrowerDeviation();
			$select=$dev->select();
			$select->where('capno like ?', $capno);
			$devdetail = $dev->fetchRow($select);
			$this->view->devdetail = $devdetail;	
		
		$employment = new Model_BorrowerEmployment();
			$select = $employment->select();
			$select->where('capno like ?',$capno)
				 ->where("employer = 'Current' or employer = 'Remittance' or employer = 'Investment'");
			$empdetail = $employment->fetchAll($select);
			$this->view->empdetail = $empdetail;	
			$this->view->countEmp = count($empdetail);
			
			$select = $employment->select();
			$select->where('capno like ?',$capno)
			 ->where("employer = 'Current'");
			$empdetail = $employment->fetchAll($select);
			$this->view->empdetailCurrent = $empdetail;	
			
			$select = $employment->select();
			$select->where('capno like ?',$capno)
			 ->where("employer = 'Remittance'");
			$empdetail = $employment->fetchAll($select);
			$this->view->empdetailOther = $empdetail;	
							
		
		$business = new Model_BorrowerBusiness();
			$select = $business->select();
			$select->where('capno like ?', $capno);
			$busdetail = $business->fetchAll($select);
			$this->view->busdetail = $busdetail;	
			$this->view->countBus = count($busdetail);
			
		$loan = new Model_BorrowerObExistLoan();
			$select = $loan->select();
			$select->where('capno like ?',$capno);
			$loandetail = $loan->fetchRow($select);
			$this->view->loandetail = $loandetail;	
		//check CV
		$cv = new Model_BorrowerCv();
			$select = $cv->select();
			$select->where('capno like ?',$capno);
			$cvdetail = $cv->fetchRow($select);
			$this->view->cvdetail = $cvdetail; 
		
		//Displaying spouse info		
		$accnt = new Model_BorrowerAccount();
			$select = $accnt->select()
				->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
				->where('relation LIKE ?','Spouse')
				->order("capno ASC");
			$accntdetailsp = $accnt->fetchAll($select);
			$this->view->detail = $accntdetailsp; 
			$this->view->countSp = count($accntdetailsp);
			//** Not Actually Use in Craw only the counting **//
			$employment_spouse = new Model_BorrowerEmployment();
			$select = $employment_spouse ->select()
				->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
				->where('relation LIKE ?','Spouse')
				 ->where("employer = 'Current' or employer = 'Remittance' or employer = 'Investment'")
				->order("capno ASC");
			$empdetail_spouse = $employment_spouse->fetchAll($select);
			$this->view->empdetail_sp = $empdetail_spouse;
			$this->view->countSpEmp = count($empdetail_spouse);
			//** Not Actually Use in Craw only the counting **//
			$business_spouse = new Model_BorrowerBusiness();
			$select = $business_spouse->select()
			->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
			->where('relation LIKE ?','Spouse')
				->order("capno ASC");
			$busdetail_spouse = $business_spouse->fetchAll($select);
			$this->view->busdetail_sp = $busdetail_spouse;	
			$this->view->countSpBus = count($busdetail_spouse);
			//** Not Actually Use in Craw only the counting **//
	
		// End of Spouse
					
		//Displaying Coborrower Info w Comaker			
			$accnt2 = new Model_BorrowerAccount();
			$select = $accnt2->select()
				->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
				->where("relation = 'Coborrower' OR relation = 'Co-Maker'")
				->order("id ASC");
			$accntdetailcb = $accnt2->fetchAll($select);
			$this->view->detail3 = $accntdetailcb; 	
			//Only Coborrower
			$select = $accnt2->select()
				->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
				->where("relation = 'Coborrower'")
				->order("id ASC");
			$accntdetailcbonly = $accnt2->fetchAll($select);
			$this->view->detail4 = $accntdetailcbonly; 	
			$this->view->countCo = count($accntdetailcbonly);
			
			//** Not Actually Use in Craw only the counting **//
			$employment_Cobo = new Model_BorrowerEmployment();
			$select = $employment_Cobo ->select()
				->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
				->where("relation = 'Coborrower' OR relation = 'Co-Maker'")
				->where("employer = 'Current' or employer = 'Remittance' or employer = 'Investment'")
				->order("capno ASC");
			$empdetail_Cobo = $employment_Cobo->fetchAll($select);
			$this->view->empdetail_cb = $empdetail_Cobo;
			$this->view->countCoEmp = count($empdetail_Cobo);
			//** Not Use Actually in Craw only the counting **//

			//** Not Actually Use in Craw only the counting **//
			$business_Cobo = new Model_BorrowerBusiness();
			$select = $business_Cobo->select()
			->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
			->where("relation = 'Coborrower' OR relation = 'Co-Maker'")
			->order("capno ASC");
			$busdetail_Cobo = $business_Cobo->fetchAll($select);
			$this->view->busdetail_cb = $busdetail_Cobo;	
			$this->view->countCoBus = count($busdetail_Cobo);
			//** Not Use Actually in Craw only the counting **//
		
			// End of Coborrower
		
		
		
		$table = new Model_BorrowerCrawForm();
		$select = $table->select();
		$select->where('capno like ?',$capno);
		$crawdetailform = $table->fetchRow($select);
		$this->view->craw = $crawdetailform;
		
			$form->collateral_remarks->setValue($crawdetailform->collateral_remarks);
			$form->recommendation_remarks->setValue($crawdetailform->recommendation_remarks);
			$form->personaldata_remarks->setValue($crawdetailform->personaldata_remarks);
			$form->loandetails_remarks->setValue($crawdetailform->loandetails_remarks);
			$form->debtincome_remarks->setValue($crawdetailform->debtincome_remarks);
			$form->recc_others->setValue($crawdetailform->recc_others);
			$form->recc_others_remarks->setValue($crawdetailform->recc_others_remarks);
					
			$form->prepared_by->setValue($crawdetailform->prepared_by);
			
			
			$form->ITR_within->setValue($crawdetailform->ITR_within);
			$form->PDC_within->setValue($crawdetailform->PDC_within);
			$form->CFUSCA_within->setValue($crawdetailform->CFUSCA_within);
			$form->bus_reg_within->setValue($crawdetailform->bus_reg_within);
			$form->bylaws_within->setValue($crawdetailform->bylaws_within);
			$form->board_resolution_within->setValue($crawdetailform->board_resolution_within);
			$form->Gen_infosheet_within->setValue($crawdetailform->Gen_infosheet_within);
	
			//Paolo Marco Manarang Update 
			$form->source_info->setvalue($crawdetailform->source_info);
			$form->deviationfield->setValue($crawdetailform->deviationfield);
		
			foreach($form->getElements() as $element) {
			$element->removeDecorator('DtDdWrapper');
			$element->removeDecorator('Label');
			}
			
			$decision = new Model_BorrowerCrawFormApprovalsection();
			$select = $decision->select();
			$select->where('capno like ?',$capno)->order('id ASC');
			$approvaldetail= $decision->fetchAll($select);
			$this->view->approvaldetail = $approvaldetail;
			
			$req = new Model_BorrowerCrawFormReq();
			$select = $req->select();
			$select->where('capno like ?',$capno);
			$requirementdetail= $req->fetchAll($select);
			$this->view->requirementdetail = $requirementdetail;
			
			/**
			 * Melody Balason Codes Above w/ Post 
			 * Paolo Manarang Update Starts Below
			**/
			//All Details of the Capno regardless of the principal, borrower, spouse
			$allmain = new Model_BorrowerAccount();
			$select = $allmain->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			$allmainDetail = $allmain->fetchAll($select);
			$this->view->allmainDetail = $allmainDetail;
			
			$allcv = new Model_BorrowerCv();
			$select = $allcv->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			//$select->where('relation NOT LIKE ?','Co-Maker');
			$allcvDetail = $allcv->fetchAll($select);
			//allcvDetail use in NFIS wihtin Guideline
			$this->view->allcvDetail = $allcvDetail;
			
			$allci = new Model_BorrowerCi();
			$select = $allci->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			//$select->where('relation NOT LIKE ?','Co-Maker');
			$allciDetail = $allci->fetchAll($select);
			$this->view->allciDetail = $allciDetail;
			
			/*****Sept 06,2010 Craw Minor REaarange Remarks*****/
			$allmain = new Model_BorrowerAccount();
			$select = $allmain->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			$select->where('relation like ?','Principal');
			$PrincipalCapno = $allmain->fetchAll($select);
			
			$select = $allmain->select();
			$select->where('relation like ?','Spouse');
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			$SpouseCapno = $allmain->fetchAll($select);			
			
			$select = $allmain->select();
			$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
			$select->where('relation like ?','Coborrower');
			$CoborrowerCapno = $allmain->fetchAll($select);			
			// end of getting the capno
			$this->view->principalCapno = $PrincipalCapno;
			$this->view->spouseCapno = $SpouseCapno;
			$this->view->coborrowerCapno = $CoborrowerCapno;
			/*****End of Reaarange Remarks******/
	    	
					
			
			//Addtional Deviation
			$adddev = new Model_BorrowerDeviationOthers();
			$select = $adddev->select();
			$select->where('capno like ?',$capno);
			$select->order('type');
			$adddevDetail = $adddev->fetchAll($select);
			$this->view->adddevDetail = $adddevDetail;
			$this->view->isComaker = $accnt->isComaker($capno);
			
			/**Comaker profile in Principal Craw****/
			//May 18,2010 
			$borrower = new Model_BorrowerAccount();
				if($borrower->getComaker($capno)){	
				$comakerCapno = $borrower->getComaker($capno);			
				$comakerDetail = $borrower->fetchRowModel($comakerCapno);
				$this->view->comakerDetail = $comakerDetail;	
	
				$select = $borrower->select();
				$select->where('capno like ?',capnosep($comakerCapno).'_'.capnorecon($comakerCapno));
				$select->where('relation like ?','Spouse');
				$comakerspDetail = $borrower->fetchRow($select);
				$this->view->comakerspDetail = $comakerspDetail;	
							
				$table = new Model_BorrowerDeviation();
				$select = $table->select();
				$select->where('capno like ?',$comakerCapno);
				$comakerRow = $table->fetchRow($select);
				$this->view->comaker_remark = $comakerRow;
				
				$this->view->comakertotalcombine = totalcombinedincome($comakerCapno);
				
				$select = $allcv->select();
				$select->where('capno like ?',capnosep($comakerCapno).'_'.capnorecon($comakerCapno));
				$allcvDetaill = $allcv->fetchAll($select);
				$this->view->comakerallcvDetail = $allcvDetaill;
				//print_r($allcvDetaill->toArray());
				$select = $cv->select();
				$select->where('capno like ?',$comakerCapno);
				$cvdetail = $cv->fetchRow($select);
				$this->view->comakercvdetail = $cvdetail; 
				
				$allci = new Model_BorrowerCi();
				$select = $allci->select();
				$select->where('capno like ?',capnosep($comakerCapno).'_'.capnorecon($comakerCapno));
				//$select->where('relation NOT LIKE ?','Co-Maker');
				$allcomakerciDetail = $allci->fetchAll($select);
				$this->view->allcomakerciDetail = $allcomakerciDetail;
							
			}	
			/***End of Comaker Section***/
			
			if ($this->getRequest()->isPost()) {
				$formData = $this->getRequest()->getPost();
					if ($form->isValid($formData)) {  
					$capno = $this->_getParam('cap');
					
	  			   $this->_helper->UpdateAcntStatus($capno,'save');
					if ($formData['button'] == 'Submit' || $formData['button'] == 'Save/Update 1' ||
					$formData['button'] == 'Save/Update 2' || $formData['button'] == 'Save/Update 3' ||
					$formData['button'] == 'Save/Update 4')  {
						
					$data = array(
					'capno' => $capno,
					'collateral_remarks' => $form->getValue('collateral_remarks'),
					'recommendation_remarks' => $form->getValue('recommendation_remarks'),
					'personaldata_remarks'=> $form->getValue('personaldata_remarks'),
					'loandetails_remarks'=> $form->getValue('loandetails_remarks'),
					'debtincome_remarks'=> $form->getValue('debtincome_remarks'),
					'recc_others'=> $form->getValue('recc_others'),
					'recc_others_remarks'=> $form->getValue('recc_others_remarks'),
															
					//'prepared_by' => $form->getValue('prepared_by'),
					'prepared_by' => login_user(),
					'pn_amount'=>moneyconvert($pn_amount),
					'gross_monthly_installment'=>moneyconvert($gross_monthly_installment),
					'applied_to_loanvalue'=>$applied_to_loanvalue,
					'loan_to_ValCap'=>$loan_to_ValCap,
					
					
					'ITR_within' => $form->getValue('ITR_within'),
					'PDC_within' => $form->getValue('PDC_within'),
					'CFUSCA_within' => $form->getValue('CFUSCA_within'),
					'bus_reg_within' => $form->getValue('bus_reg_within'),
					'bylaws_within' => $form->getValue('bylaws_within'),
					'board_resolution_within' => $form->getValue('board_resolution_within'),
					'Gen_infosheet_within' => $form->getValue('Gen_infosheet_within'),
					'deviationfield' => $form->getValue('deviationfield'),
					'date'=>$formData['crawdate'],
					'score'=>$formData['crawscore'],
					'source_info'=> $form->getValue('source_info'),

			
					);
					$table = new Model_BorrowerCrawForm();
					$sql = $table->select()->where('capno LIKE ?',$capno);

					if($table->fetchAll($sql)->count() == 0){
					$table->insert($data);	
					//if no record found insert data
					}
					else{
					//else update using the capno
					$where = "capno like '$capno'";
					$table->update($data,$where);						
					}	
					
					if($formData['button'] == 'Submit'){
					   $this->_redirect('/index/crawform/cap/'.$capno.'/save/ok#submit');	
					}
					else if ($formData['button'] == 'Save/Update 1'){
					   $this->_redirect('/index/crawform/cap/'.$capno.'/save/ok#saveupdate1');	
					}
					else if ($formData['button'] == 'Save/Update 2'){
					   $this->_redirect('/index/crawform/cap/'.$capno.'/save/ok#saveupdate2');	
					}
					else if ($formData['button'] == 'Save/Update 3'){
					   $this->_redirect('/index/crawform/cap/'.$capno.'/save/ok#saveupdate3');	
					}
					else if ($formData['button'] == 'Save/Update 4'){
					   $this->_redirect('/index/crawform/cap/'.$capno.'/save/ok#saveupdate4');	
					}

				}
				
				else if($formData['button'] == 'Add'){
					$data = array(
					'capno'=>$capno,
					'decision'=>$form->getValue('decision'),
					'approved_by'=>$form->getValue('approved_by'),
					'reason'=>$form->getValue('reason'),
					'date_approval'=>date('m-d-Y h:i a'),		
					'date_approval2'=>date("r"),
					'role'=> login_user_role(),	
					'date_type'=>3,
					);
					$table = new Model_BorrowerCrawFormApprovalsection();
					$table->insert($data);	
				   $this->_redirect('/index/crawform/cap/'.$capno.'/save/ok#add_approving_auth');	
					}
				
			//else if($formData['button'] == 'Add Requirement'){
				else if($formData['button'] == 'Add Requirement'){
				
					$data = array(
					'capno'=>$capno,
					'other_requirement' =>$form->getValue('other_requirement'),
					);
					$table = new Model_BorrowerCrawFormReq();
					$table->insert($data);	
					
				   $this->_redirect('/index/crawform/cap/'.$capno.'/save/ok#other_requirement');	

				}
				//Additional Deviation
				else if($formData['button'] == 'Add Deviation'){
				
					$data = array(
					'capno'=>$capno,
					'type' =>$form->getValue('deviation_type'),
					'deviation_name'=>$form->getValue('deviation_name'),
					'deviation_status'=>$form->getValue('deviation_status'),
					'deviation_value'=>$form->getValue('deviation_value'),
					'deviation_remarks'=>$form->getValue('deviation_remarks'),
					);
					$table = new Model_BorrowerDeviationOthers();
					$table->insert($data);	
				    $this->_redirect('/index/crawform/cap/'.$capno.'/save/ok#additional_deviation');	

				}
			
		}// End of Isvalid
	}// End of Action
	
	
		
	}
	
public function deldecisionAction(){
		$this->_helper->viewRenderer->setNoRender(true);
		$id = $this->_getParam('id');
		$capno = $this->_getParam('cap');
		$action = $this->_getParam('red');

		$table = new Model_BorrowerCrawFormApprovalsection();
		$where = $table->getAdapter()->quoteInto('id = ?', $id);
		$table->delete($where);
		
		if($action == 'craw'){
		$this->_redirect('/index/crawform/cap/'.$capno.'#add_approving_auth');	
		}
		else if($action == 'decision'){
		$this->_redirect('/index/creditdecision/cap/'.$capno);	
		}
		else if($action == 'ammend'){
		$this->_redirect('/index/amendmentform/cap/'.$capno.'/type/prepare');	
		}
		
}

public function delrequirementAction(){
		$this->_helper->viewRenderer->setNoRender(true);
		$id = $this->_getParam('id');
		$capno = $this->_getParam('cap');
		$table = new Model_BorrowerCrawFormReq();
		$where = $table->getAdapter()->quoteInto('id = ?', $id);
		$table->delete($where);
	   $this->_redirect('/index/crawform/cap/'.$capno.'#other_requirement');	
}

public function deladddevAction(){
		$this->_helper->viewRenderer->setNoRender(true);
		$id = $this->_getParam('id');
		$capno = $this->_getParam('cap');
		$table = new Model_BorrowerDeviationOthers();
		$where = $table->getAdapter()->quoteInto('id = ?', $id);
		$table->delete($where);
	    $this->_redirect('/index/crawform/cap/'.$capno.'#additional_deviation');	
}

public function businessnatureAction(){
	$this->_helper->viewRenderer('nature-of-business');
	}
	
public function specialoccupationAction(){
	$this->_helper->viewRenderer('special-occupation');
	}

public function deleteAction(){
	//delete the spouse and coborrower only
		
	$capno = $this->_getParam('cap');
	$table = new Model_BorrowerAccount();
	$select = $table->select();
	$select->where("capno like ?",$capno);
	$detail = $table->fetchRow($select);
	
	if($detail->relation == "Principal"){
		if($table->isComaker($capno)){
			$counter = 1;			
		}else{
			$counter = 0;
		}
		$returnCapno = $table->getMainCapno($capno);
	}else{
		
		$counter = 1;
		$returnCapno = $table->getPrincipalCapno($capno);
	}
	$relation = $table->getRelation($capno);
	if($counter == 1){
			$account = new Model_BorrowerAccount();
			$where = $account->getAdapter()->quoteInto('capno like ?', $capno);
	      	$account->delete($where);
						
			$emp = new Model_BorrowerEmployment();
			$where = $emp->getAdapter()->quoteInto('capno like ?', $capno);
	      	$emp->delete($where);
			
			$bus = new Model_BorrowerBusiness();
			$where = $bus->getAdapter()->quoteInto('capno like ?', $capno);
	      	$bus->delete($where);
			
			$ci = new Model_BorrowerCi();
			$where = $ci->getAdapter()->quoteInto('capno like ?', $capno);
	      	$ci->delete($where);
			
			$cv = new Model_BorrowerCv();
			$where = $cv->getAdapter()->quoteInto('capno like ?', $capno);
	      	$cv->delete($where);
			
			$bank = new Model_BorrowerObBank();
			$where = $bank->getAdapter()->quoteInto('capno like ?', $capno);
	      	$bank->delete($where);			
			
			$creditcard = new Model_BorrowerObCreditCard();
			$where = $creditcard->getAdapter()->quoteInto('capno like ?', $capno);
	      	$creditcard->delete($where);
			
			$existloan = new Model_BorrowerObExistLoan();
			$where = $existloan->getAdapter()->quoteInto('capno like ?', $capno);
	      	$existloan->delete($where);
						
			$trdbusref = new Model_BorrowerObTrdBusRef();
			$where = $trdbusref->getAdapter()->quoteInto('capno like ?', $capno);
	      	$trdbusref->delete($where);
						
			$otherauto = new Model_BorrowerOtherAuto();
			$where = $otherauto->getAdapter()->quoteInto('capno like ?', $capno);
	      	$otherauto->delete($where);
						
			$otherreal = new Model_BorrowerOtherReal();
			$where = $otherreal->getAdapter()->quoteInto('capno like ?', $capno);
	      	$otherreal->delete($where);
						
			$othershare = new Model_BorrowerOtherShare();
			$where = $othershare->getAdapter()->quoteInto('capno like ?', $capno);
	      	$othershare->delete($where);
						
			$busfin = new Model_BorrowerObBfLiabilities();
			$where = $busfin->getAdapter()->quoteInto('capno like ?', $capno);
	      	$busfin->delete($where);
						
			$busfn = new Model_BorrowerOtherBusFinance();
			$where = $busfn->getAdapter()->quoteInto('capno like ?', $capno);
	      	$busfn->delete($where);		

			//Add in the Account Audit Trail
			$accounthistory = new Model_AuditTrail();
			$data = array(
			'capno'=> $returnCapno,
			'values'=> $relation.' - '. $detail->borrower_lname,
			'from'=>'',
			'to'=>'Deleted Info',
			'change_by'=> login_user(),
			'date_change'=> date("r"),
			);
			$accounthistory->insert($data);
			gmi_ratio($returnCapno);
			
			}//end of counter
			
			$this->_redirect('/index/accountedit/cap/'.$returnCapno);
	 
}

public function deleteasapAction(){
	$this->_helper->viewRenderer->setNoRender(true);

	$capno = $this->_getParam('cap');
	
		$account = new Model_BorrowerAccount();
		$select = $account->select();
        $select->where('capno like ?', capnosep($capno).'_'.capnorecon($capno))->order('id ASC');
        $allBorrower = $account->fetchAll($select);

        $allArray = Array();
        foreach ($allBorrower as $detail) {
            $allArray[] = $detail->capno;
        }
		
		foreach ($allArray as $capno) {
		
		$account = new Model_BorrowerAccount();
		$where = $account->getAdapter()->quoteInto('capno like ?', $capno);
	    $account->delete($where);
	
		$deviation = new Model_BorrowerDeviation();
		$where = $deviation->getAdapter()->quoteInto('capno like ?', $capno);
		$deviation->delete($where);
		
        $table = new Model_BorrowerDeviationOthers();
		$where = $table->getAdapter()->quoteInto('capno like ?', $capno);
		$table->delete($where);
		
        $table = new Model_BorrowerBusiness();
		$where = $table->getAdapter()->quoteInto('capno like ?', $capno);
		$table->delete($where);
		
        $table = new Model_BorrowerEmployment();
		$where = $table->getAdapter()->quoteInto('capno like ?', $capno);
		$table->delete($where);

        $table = new Model_BorrowerCrawForm();
		$where = $table->getAdapter()->quoteInto('capno like ?', $capno);
		$table->delete($where);

        $table = new Model_BorrowerCi();
		$where = $table->getAdapter()->quoteInto('capno like ?', $capno);
		$table->delete($where);

        $table = new Model_BorrowerCv();
		$where = $table->getAdapter()->quoteInto('capno like ?', $capno);
		$table->delete($where);
		
        $table = new Model_BorrowerIncomeMonthly();
		$where = $table->getAdapter()->quoteInto('capno like ?', $capno);
		$table->delete($where);		

        $table = new Model_BorrowerIncomeSource();
		$where = $table->getAdapter()->quoteInto('capno like ?', $capno);
		$table->delete($where);	
		
        $table = new Model_BorrowerInsuranceCharges();
		$where = $table->getAdapter()->quoteInto('capno like ?', $capno);
		$table->delete($where);	
		
        $table = new Model_BorrowerInsurancePerils();
		$where = $table->getAdapter()->quoteInto('capno like ?', $capno);
		$table->delete($where);	
		
		$table = new Model_BorrowerInsurancePolicy();
		$where = $table->getAdapter()->quoteInto('capno like ?', $capno);
		$table->delete($where);	
		
		
        $table = new Model_BorrowerObBank();
		$where = $table->getAdapter()->quoteInto('capno like ?', $capno);
		$table->delete($where);	
		
		
        $table = new Model_BorrowerObBfLiabilities();
		$where = $table->getAdapter()->quoteInto('capno like ?', $capno);
		$table->delete($where);	
				
        $table = new Model_BorrowerObCreditCard();
		$where = $table->getAdapter()->quoteInto('capno like ?', $capno);
		$table->delete($where);	
				
        $table = new Model_BorrowerObExistLoan();
		$where = $table->getAdapter()->quoteInto('capno like ?', $capno);
		$table->delete($where);	
		
		
        $table = new Model_BorrowerObTrdBusRef();
		$where = $table->getAdapter()->quoteInto('capno like ?', $capno);
		$table->delete($where);		   
		
		
        $table = new Model_BorrowerOtherAuto();
		$where = $table->getAdapter()->quoteInto('capno like ?', $capno);
		$table->delete($where);	
				
        $table = new Model_BorrowerOtherBusFinance();
		$where = $table->getAdapter()->quoteInto('capno like ?', $capno);
		$table->delete($where);	
		
		
        $table = new Model_BorrowerOtherReal();
		$where = $table->getAdapter()->quoteInto('capno like ?', $capno);
		$table->delete($where);	
		
        $table = new Model_BorrowerOtherShare();
		$where = $table->getAdapter()->quoteInto('capno like ?', $capno);
		$table->delete($where);	
		

		echo "Deleted Successfully ";
	
		}
	
}

public function assigntaskcaAction(){
	$this->_helper->viewRenderer('assign-task-ca');
	$form = new Form_AssignTaskCa();
	$this->view->form=$form;
	
	$accnt = new Model_BorrowerAccount();
	$select = $accnt->select();
	$select->where("account_status = 'MA - S' OR account_status = 'CA - An' OR account_status = 'CA - AnD' OR account_status = 'CO - RTCA'");
	$select->where('relation like ?', 'Principal');	
	$row = $accnt->fetchAll($select);
	

	$this->view->row = $row;
	if ($this->getRequest()->isPost()) {
		$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {  
			$ca = $form->getValue('assigned_to');
			$capno =$formData['cap'];
			
			$account = new Model_BorrowerAccount();
			$where = "capno like '$capno'";
			$data = array(
			'submitted_ca'=>$ca,
			);
			
						/**Audit Trail**/
			$accnt = new Model_BorrowerAccount();
			$select = $accnt->select();
			$select->where('capno like ?',$capno);
			$accntdetail = $accnt->fetchRow($select)->toArray();
			$this->_helper->AuditTrail($accntdetail,$data, $capno);
			/** End of Audit Trail **/
			
			$account->update($data,$where);
		    $this->_redirect('/index/assigntaskca');	


			
			}
	}
	
	}
	
 public function  bookingAction(){
 /*** View Booking Details 
  *  Paolo Marco Manarang
  *  Feb 18, 2010   
 */
$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		
$this->_helper->viewRenderer('booking-view');

	$capno = $this->_getParam('cap');
	$table = new Model_BorrowerAccount();
	$select = $table->select();
	$select->where("capno like ?",$capno);
	$detail = $table->fetchRow($select);
	$this->view->detail = $detail;

 }
 
public function  editbookingAction(){
$this->_helper->viewRenderer('booking-edit');
$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
	
}

public function tempbookingAction(){
	//Action for temporary Booking to stamp the date
	$this->_helper->viewRenderer->setNoRender(true);
	$capno = $this->_getParam('cap');
	
	$data = array(
	'account_status'=>'LD - B'
	);
	$where = "capno like '$capno'";
	$table = new Model_BorrowerAccount();
	$table->update($data,$where);
	
					//Insert Account History
					$history = new Model_AccountHistory();
					$select = $history->select();
					$select->where('capno like ?',$capno)->order('id DESC');
					$historyDetail = $history->fetchRow($select);
								
					$hdata = array (
					'capno'=>$capno,
					'status'=>'LD - B',
					'by'=>login_user(),
					'date'=>date("r"),
					'remarks'=>'',
					'date_last'=>$historyDetail->date,
					);
					$history->insert($hdata);
					//End of History

		    $this->_redirect('/index/account/cap/'.$capno);	
	
}

public function riskexcelAction(){
	//Risk Management
	$this->_helper->viewRenderer->setNoRender(true);
	ini_set('max_execution_time', 0);
	
	$arrayCol = array(
	'capno',
	'date'
	);	
	
	
	$table = new Model_AccountHistory();
	$statusTable = new Model_Admin_AccountStatus();
	
	$startdate = "2009-09-01 01:00:00";	// of the morning 
	$enddate = "2010-10-01 24:00:00";	 // of the evening
	/*
	foreach($statusTable->routeBox('approve') as $x){
	$select->orwhere('status like ?',$x->status);
	$condition = $select->getPart(Zend_Db_Select::WHERE);
	}*/
	
	$select = $table->select()->from($table,$arrayCol);
	$select->where("status like ?","CO - REVD");
	$select->where("date between '$startdate' and '$enddate'");
	$select->order('date');		
	//$select->where(arrayString($condition));
	
	
	$data_array = $table->fetchAll($select)->toArray();
	
	$data_row = $table->fetchRow($select)->toArray();
	
	$data_headers=array();
	foreach($data_row as $key => $value){
	$data_headers[] = $key;	
	}
	$data_headers[] = 'Name';
	$data_headers[] = 'Amount Financed';
	$data_headers[] = 'Account Officer';
	$data_headers[] = 'Credit Score';

	$history = new Model_AccountHistory();

	$count = 0;
	$borrower = new Model_BorrowerAccount();
	foreach($data_array as $key => $val){

		$detail = $borrower->fetchRowModel($data_array[$key]['capno']);
		$dc = '';
		$mo = '';
		
	
		
		if($detail->dealer_coordinator != '0'){
			$dc = $this->view->viewMa($detail->dealer_coordinator);
		}
		if($detail->submitted_mo != '0'){
			$mo = $this->view->viewMa($detail->submitted_mo);			
		}
		
		$scoreSplit = explode(" ", $detail->score_tag);
		
		if($scoreSplit[2]){
		if($scoreSplit[2] == "Scoring"){
			$scoreTag = "OCS";
		}else{
			$scoreTag = $scoreSplit[2];
		}}else{
			$capbasis = getHighest($detail->capno);
			$scoreTag3 = '';
			$scoretag3 = $this->_helper->ExcelFetch->scoreRate($detail->score,$capbasis);
			$scoreTag = $scoretag3;
		}
		//$route = $this->_helper->AutoRoute($data_array[$key]['capno'],$data_array[$key]['score_tag']);
		//$data_array[$key]['req_caprecon'] = capnorecon($data_array[$key]['capno']);
		//$data_array[$key]['req_acctstatus2'] = $detail->account_status2;
		$data_array[$key]['req_name'] = $detail->borrower_lname.', '.$detail->borrower_fname.' '.$detail->borrower_mname;
		//$data_array[$key]['req_statusdate'] = $this->_helper->ExcelFetch->getApprove($data_array[$key]['capno']);
		$data_array[$key]['amountloan'] = $detail->amountloan;
		$data_array[$key]['Account Officer'] = $dc.' '.$mo;
		$data_array[$key]['score_tag'] = $scoreTag;
	}
	//print_r($data_array[6]);

	
	$html_string='<table>';
	$html_string.='<tr><td>'.implode('</td><td>',$data_headers).'</td></tr>';
	foreach($data_array as $k=>$v){
		$html_string.='<tr><td>'.implode('</td><td>',$v).'</td></tr>';
	}
	
	

	$html_string.='</table>';
	$xlsfile = "excel_example".date("m-d-Y-hiA").".xls";
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=$xlsfile");
	print  $html_string;
}



public function excelAction(){
	$this->_helper->viewRenderer->setNoRender(true);
	ini_set('max_execution_time', 0);
	$table = new Model_BorrowerAccount();
	$select = $table->select();
	$table2 = new Model_BorrowerCi();
	
	$startdate = "2010-06-16 01:00:00";	// of the morning 
	//$enddate = "2010-05-16 24:00:00";	 // of the evening
	$enddate = "2010-07-30 24:00:00";	 // of the evening
	//$enddate = "2010-01-01 24:00:00";	 // of the evening
	
	
	$select->where("application_date between '$startdate' and '$enddate'");
	$select->where('relation like ?','Principal');
	$select->where("account_status like 'CO%' OR account_status = 'Approved' OR account_status = 'Rejected'");
	$select->order('application_date');		

	$data_array = $table->fetchAll($select)->toArray();
	$data_row = $table->fetchRow($select)->toArray();
	
	$data_headers=array();
	foreach($data_row as $key => $value){
	$data_headers[] = $key;	
	}
	$data_headers[] = 'Total Combined Income';
	$data_headers[] = 'Cap Basis';
	$data_headers[] = 'Cap Basis Status';
	$data_headers[] = 'Emp Status';
	$data_headers[] = 'Emp Rank Position';
	$data_headers[] = 'Emp Years';
	$data_headers[] = 'Bus Nature';
	$data_headers[] = 'Bus Source Income';
	$data_headers[] = 'Bus Years';
	$data_headers[] = 'CV Source Income';
	$data_headers[] = 'CV Background';
	$data_headers[] = 'CV Past Dealings';
	$data_headers[] = 'CV Bank Ref';
	$data_headers[] = 'Custom Name';
	$data_headers[] = 'Custom Loan Term';
	//$data_headers[] = 'Initial Score (MA)';
	//$data_headers[] = 'Final Score (CA)';


	
	$count = 0;
	foreach($data_array as $key => $val){
		$capbasis = getHighest($data_array[$key]['capno']);
		$status = $this->_helper->ExcelFetch->getBusEmpStatus($capbasis);
		
		$emp_status = '';
		$emp_pos = '';
		$emp_yrs = '';
		$bus_nat = '';
		$bus_src = '';
		$bus_yrs = '';
		if($status == 'E'){
			$emp_status = $this->_helper->ExcelFetch->getEmpFields($capbasis, 'values','status');
			$emp_pos = $this->_helper->ExcelFetch->getEmpFields($capbasis, 'values','position');
			$emp_yrs = $this->_helper->ExcelFetch->getEmpFields($capbasis, 'values','years');
		}
		if($status == 'SE'){
			$bus_nat = $this->_helper->ExcelFetch->getBusField($capbasis, 'values','nature');	
			$bus_src = $this->_helper->ExcelFetch->getBusField($capbasis, 'values','sourceincome');	 
			$bus_yrs = $this->_helper->ExcelFetch->getBusField($capbasis, 'busyrs','busyears');	
		}
		$srcincome = $this->_helper->ExcelFetch->getCvFields($capbasis, 'sourceincome');	
		$backgrd =  $this->_helper->ExcelFetch->getCvFields($capbasis, 'background');	
		$pastdealings =   $this->_helper->ExcelFetch->getCvFields($capbasis, 'pastdealing');	
		$bankref =   $this->_helper->ExcelFetch->getCvFields($capbasis, 'bankref');	
		//$s = $this->_helper->ScoreModel($data_array[$key]['capno']);
		$scoretag3 = $this->_helper->ExcelFetch->scoreRate($data_array[$key]['score'],$capbasis);
		
		$data_array[$key]['gender'] = $this->view->viewGender($data_array[$key]['gender']);
		$data_array[$key]['loantype'] = $this->view->viewTypeLoan($data_array[$key]['loantype']);
		$data_array[$key]['civilstatus'] = $this->view->viewCivilStatus($data_array[$key]['civilstatus']);
		$data_array[$key]['citizenship'] = $this->view->viewCitizenship($data_array[$key]['citizenship']);
		$data_array[$key]['residence_type'] = $this->view->viewResidenceType($data_array[$key]['residence_type']);
		$data_array[$key]['neighborhoodtype'] = $this->view->viewNeighborhoodType($data_array[$key]['neighborhoodtype']);
		$data_array[$key]['veh_status'] = $this->view->viewVehStatus($data_array[$key]['veh_status']);
		
		
		$data_array[$key]['totalcombineincome'] =totalcombinedincome($data_array[$key]['capno']);
		$data_array[$key]['capbasisCapno'] = $capbasis;
		$data_array[$key]['capbasisstatus'] = $status;
		$data_array[$key]['capbasisEmpStatus'] = $emp_status;
		$data_array[$key]['capbasisEmpRankPos'] = $emp_pos;
		$data_array[$key]['capbasisEmpYears'] = $emp_yrs;
		
		$data_array[$key]['capbasisBusNature'] = $bus_nat;
		$data_array[$key]['capbasisEBusSource'] = $bus_src;
		$data_array[$key]['capbasisBusYears'] = $bus_yrs;
		
		$data_array[$key]['capbasissrcincome'] = $srcincome;
		$data_array[$key]['capbasisbackground'] = $backgrd;
		$data_array[$key]['capbasispastdealings'] = $pastdealings;
		$data_array[$key]['capbasisbankref'] = $bankref;
		$data_array[$key]['custom name'] = $data_array[$key]['borrower_lname'].', '.$data_array[$key]['borrower_fname'].' '.$data_array[$key]['borrower_mname'];
		$data_array[$key]['custom_loanterm'] = $data_array[$key]['loanterm'];			
		//$data_array[$key]['custom_score'] = $this->_helper->$s($data_array[$key]['capno']);	
		$data_array[$key]['score2'] = $data_array[$key]['score'];			
		$data_array[$key]['scoretag2'] = $data_array[$key]['score_tag'];			
		$data_array[$key]['scoretag3'] = $data_array[$key]['score'].' '.$scoretag3;			
		$data_array[$key]['acc_stat'] = $data_array[$key]['account_status'];			
		$data_array[$key]['acc_stat2'] = $data_array[$key]['account_status2'];
	}
	
		//print_r($data_array[6]);


	$html_string='<table>';
	$html_string.='<tr><td>'.implode('</td><td>',$data_headers).'</td></tr>';
	foreach($data_array as $k=>$v){
		$html_string.='<tr><td>'.implode('</td><td>',$v).'</td></tr>';
	}
	
	

	$html_string.='</table>';
	$xlsfile = "excel_example".date("m-d-Y-hiA").".xls";
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=$xlsfile");
	print  $html_string;

}

public function excel2Action(){
	//irene fetch routine
	$this->_helper->viewRenderer->setNoRender(true);
	ini_set('max_execution_time', 0);
	$table = new Model_BorrowerAccount();
	$select = $table->select();
	$select2 = $table->select();

	$table2 = new Model_BorrowerCi();
	
	$array = array();
	$array[] ='1100105281000400';
	$array[] ='1100104141000300';
	//$array[] ='1100103031000800';

	$arrayCol = array(
	'capno',
	'borrower_lname',
	'borrower_fname',
	'borrower_mname',
	'downpayment_percent',
	'loanterm',
	'monthly_amortization',
	'veh_brand',
	'veh_status',
	'veh_type',
	'submitted_ca_date',
	'account_status',
	'account_status2',	
	
	);
	

	$select->where('relation like ?','Principal');
	$data_array = array();
	foreach($array as $x){
	$select='';
	$select = $table->select()->from($table,$arrayCol);
	$select->where('capno like ?',$x);	
	$data_array[] = $table->fetchRow($select)->toArray();
	}
	
	
	foreach($data_array as $key => $val){
	$capbasis = getHighest($data_array[$key]['capno']);
	$status = $this->_helper->ExcelFetch->getBusEmpStatus($capbasis);
		$emp_status = '';
		$emp_pos = '';
		$emp_yrs = '';
		$bus_nat = '';
		$bus_src = '';
		$bus_yrs = '';
		if($status == 'E'){
			$emp_status = $this->_helper->ExcelFetch->getEmpFields($capbasis, 'values','status');
			//$emp_pos = $this->_helper->ExcelFetch->getEmpFields($capbasis, 'values','position');
			//$emp_yrs = $this->_helper->ExcelFetch->getEmpFields($capbasis, 'values','years');
		}
		if($status == 'SE'){
			$bus_nat = $this->_helper->ExcelFetch->getBusField($capbasis, 'values','nature');	
			$bus_yrs = $this->_helper->ExcelFetch->getBusField($capbasis, 'busyrs','busyears');	
		}	
		$backgrd = $this->_helper->ExcelFetch->getCvFields($capbasis, 'background');	
		$length = $this->_helper->ExcelFetch->getLenghtStay($capbasis);
		$neighbor = $this->_helper->ExcelFetch->getNeighborHood($capbasis);

		$data_array[$key]['veh_status'] = $this->view->viewVehStatus($data_array[$key]['veh_status']);
		$data_array[$key]['totalcombineincome'] = totalcombinedincome($data_array[$key]['capno']);
		$data_array[$key]['custom name'] = $data_array[$key]['borrower_lname'].', '.$data_array[$key]['borrower_fname'].' '.$data_array[$key]['borrower_mname'];
		//$data_array[$key]['capbasisCapnoLOS'] = $data_array[$key]['capbasis'];
		$data_array[$key]['capbasisCapno'] = $capbasis;
		$data_array[$key]['capbasisbackground'] = $backgrd;
		$data_array[$key]['capbasisstatus'] = $status;
		$data_array[$key]['capbasisBusNature'] = $bus_nat;
		$data_array[$key]['capbasisBusYears'] = $bus_yrs;	
		$data_array[$key]['capbasisEmpStatus'] = $emp_status;
		$data_array[$key]['neighborhoodtype'] = $length;
		$data_array[$key]['lenthofstay'] = $this->view->viewNeighborhoodType($neighbor);

	}
	
	//Table Headers Set
	$select2 = $table->select()->from($table,$arrayCol);
	$select2->where('capno like ?','1100105281000400');	
	$data_row = $table->fetchRow($select2)->toArray();
	$data_headers=array();
	foreach($data_row as $key => $value){
	$data_headers[] = $key;	
	}
	$data_headers[] = 'Total Combined Income';
	$data_headers[] = 'Custom Name';
	//$data_headers[] = 'CapbasisLOS';
	$data_headers[] = 'Capbasis2';
	$data_headers[] = 'Background';
	$data_headers[] = 'EMP/BUS';
	$data_headers[] = 'Bus Nature';
	$data_headers[] = 'Bus Years';
	$data_headers[] = 'Emp Status';
	$data_headers[] = 'NeigborhoodType';
	$data_headers[] = 'Length Stay';

	
	


	//Table Header Set

	$html_string='<table>';
	$html_string.='<tr><td>'.implode('</td><td>',$data_headers).'</td></tr>';
	foreach($data_array as $k=>$v){
		$html_string.='<tr><td>'.implode('</td><td>',$v).'</td></tr>';
	}
	
	$html_string.='</table>';
	$xlsfile = "excel_example".date("m-d-Y-hiA").".xls";
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=$xlsfile");
	print  $html_string;

}

	public function samplecityAction(){
        $this->_helper->Layout->disableLayout(); // Will not load the layout
        $this->_helper->viewRenderer->setNoRender(); //Will not render view

		$table = new Model_ChainAddress();
		
		$select = $table->select();
		$detail = $table->fetchAll($select);
		$data=array();
		foreach($detail as $x){
		$data[] = $x->brgy;	
		}
		//$jsonResponse = Zend_Json::encode($data);	
		
	 //$jsonResponse = json_encode($this->getStore());
        //$this->getResponse()->setHeader('Content-Type', 'application/json')
      //                      ->setBody($jsonResponse)
      //                      ->sendResponse();

	    //echo Zend_Json::prettyPrint($json, array("indent" => " "));
		echo '[' . implode(',', $data) . ']';
	}

	public function testAction(){
	$this->_helper->viewRenderer->setNoRender(true);

	$this->_helper->ReconModule('11012080901400');	
	
	}
	
	public function sampledata2Action(){
	$this->_helper->viewRenderer->setNoRender(true);
	
	echo date('Y-m-d',strtotime('-60 day'));
	}
	
	public function testrouteboxAction(){
	$this->_helper->viewRenderer->setNoRender(true);
	$status = new Model_Admin_AccountStatus();
	$select = $status->select();
	foreach($status->routeBox('pres_level') as $x){
		echo $x->status.'<br>';
		$select->orwhere('account_status like ?',$x->status);
	}
	$condition =$select->getPart(Zend_Db_Select::WHERE);
	foreach($condition as $x){
		$string = $string.$x;
	}
	echo $string;
	
	//$select->where("account_status = 'yeah' OR account_status = 'yeah2'");
	$select = $status->select();
	$select->where("routetag like '%PRES%' OR routetag like '%$username%'");
	$select->where($condition);
	
	echo $select.'<br>';
	$select = $status->select();
	$select->where("account_status like 'ALMH - ENPRES' OR account_status = 'CMGH - P' OR account_status = 'CSH - P' OR account_status = 'CSH - ReR' OR account_status = 'CSH - ReAp' OR account_status = 'CMGH - ReAp' OR account_status = 'CMGH - ReR' OR account_status = 'CSH - ReAp - ABCMGH' OR account_status = 'CSH - ReR - ABCMGH'");
	$select->where("routetag like '%PRES%' OR routetag like '%$username%'");
	echo $select;	
	}
	public function resetAction(){
	
	$this->_helper->viewRenderer->setNoRender(true);
	$capno = array();
	$capno[] = '1100105281000400';

	$borrower = new Model_BorrowerAccount();
	foreach($capno as $x){
		$data = array(
		'account_status'=>'CA - AnD',
		);
		$where = "capno like '$x'";
		$borrower->update($data,$where);
	}
	echo "Success";
		
	}
	
	public function resetclaAction(){
	
	$this->_helper->viewRenderer->setNoRender(true);
	$capno = array();
	$capno[] = '1100106181000500';
	$capno[] = '1100106211000202';

	$borrower = new Model_BorrowerAccount();
	foreach($capno as $x){
		$data = array(
		'account_status'=>'CA - AnD',
		);
		$where = "capno like '$x'";
		$borrower->update($data,$where);
	}
	echo "Success";
		
	}
	
	public function resetdataAction(){
	
	$this->_helper->viewRenderer->setNoRender(true);
	$capno = array();
	$capno[] = '1100101081000600';
	$capno[] = '1100106171000500';
	$capno[] = '1100106181000601';
	$capno[] = '1100106031000400';
	$capno[] = '1100106071001001';
	$capno[] = '1100107161000100';
	$capno[] = '1100106101000501';
	$borrower = new Model_BorrowerAccount();
	foreach($capno as $x){
		$data = array(
		'account_status'=>'CSH - ReAp',
		);
		$where = "capno like '$x'";
		$borrower->update($data,$where);
	}
	echo "Success";
		
	}
	
	public function resetdata2Action(){
	$this->_helper->viewRenderer->setNoRender(true);
	$borrower = new Model_BorrowerAccount();

		$x = '1100106181000600';
		$data = array(
		'account_status'=>'PRES - R',
		);
		$where = "capno like '$x'";
		$borrower->update($data,$where);
		
		$x = '1100106291000800';
		$data = array(
		'account_status'=>'CO - R',
		);
		$where = "capno like '$x'";
		$borrower->update($data,$where);
		
		$x = '1100106171000400';
		$data = array(
		'account_status'=>'CSH - R',
		);
		$where = "capno like '$x'";
		$borrower->update($data,$where);
		
		echo "Success";
	}
	
	
	public function capnoAction(){
	$this->_helper->viewRenderer->setNoRender(true);


	$capno = "11108120901600";
	echo $capno."<br>";
	echo strlen($capno)."<br>";
	$curr = substr($capno,9,-2);
	echo $curr;
	
	$loantype = 1;
	$indivcorp = 1;
	$retail = 1;
	$branch = '01';
    $date = date(mdy);
   	$crescore = new Model_BorrowerAccount();
    $select= $crescore->select();
    $select->where('relation like ?','Principal');
	$select->order('application_date DESC');
   
	 //11108120901600
	 
	$rows = $crescore->fetchAll($select);
	echo $rows[0]->capno;

    $curr = getCurr($rows[0]->capno);
    $lastdate = getLastDate($rows[0]->capno);
    
	echo 'Curr'.$curr;
	$currdate = $date;
	echo 'lastdate '.$lastdate;
	echo 'currdate '.$currdate;
    $nextseq = $curr+1;
	echo 'NextSeq'.$nextseq;
    $nextseq = str_pad($nextseq,3,'0', STR_PAD_LEFT);
    if ($lastdate ==  $currdate){
    $stringx = $retail.$loantype.$indivcorp.$branch.$date.$nextseq.'00';} //change 0 when recon is implemented by counting how many changes are made 
	 //substr($string,0,12); 10625090000
   else {
   $stringx = $retail.$loantype.$indivcorp.$branch.$date.'00100';   
   }  
   echo '<br>Capno'.$stringx;
   echo	'<br>CapnoSep'.capnosep($stringx);
   echo	'<br>CapnoRecon'.capnorecon($stringx);	
   echo '<br>CapnoCurr'.capnocurr($stringx);
   echo '<br>Capno Spouse'.capnospcoGen($stringx,'Spouse');
   echo '<br>Capno Coborrower'.capnospcoGen($stringx,'Coborrower');
   
   echo '<br>Capno Coborrower Kasunod'.capnospcoGen('11001061000160','Coborrower');
   echo '<br>Capno Coborrower Extend'.capnocoexGen('11001061000140');
   
   echo date('M-d-Y',mktime(0,0,0,01,05,10));

   echo date('M-d-Y',strtotime('11/09/2009'));
   
   //$table = new Model_CustomerInfo();
   //print_r($table->fetchAllModel());
   
   $try = new Model_CustInfo();
   $row = $try->fetchRow('id=1');
   //print_r($row->findDependentRowset('BorrowerInfo')); 
   
   echo 'rere'.ceil(989);
   
   $number = 123.398989;
   $explode = explode('.',$number);
   echo $explode[0];
   echo $explode[1];
   
   echo "return ".$explode[0].'.'.substr($explode[1],0,2); 

  echo "<br><br>";
  $route = "A3-CMGH";
  $pos = strpos($route,'-');
  $cut = substr($route,0,$pos);
  	echo $cut.'P-PRES';
	echo "<br><br>";
	echo "Testing Business Emplyment Check Status <br>";
	$busSum = 40000;
	$busYrs = 5;
	$empSum = 40000;
	$empYrs = 6;
	$otherSum= 20000;
	echo "Business:".$busSum."<br>";
	echo "Business Yrs:".$busYrs."<br>";
	echo "Employment:".$empSum."<br>";
	echo "Employment Yrs:".$empYrs."<br>";
	echo "Other:".$otherSum."<br>";
	if ($busSum > $empSum){
		
		if($busSum >= $otherSum){
		return "SE"; }
		else { 
		return "O";	
		}
	}
	else if($busSum < $empSum){
		
		if($empSum > $otherSum){
		echo "E"; }
		else { 
		echo "O";	
		}
	}
	else if($busSum == $empSum){
		//business and employment is equal chk the yrs varied
		if($busYrs > $empYrs){
			if($busSum >= $otherSum){
			echo "SE"; }
			else { 
			echo "O";	
			}			
		}
		else if($busYrs < $empYrs) {
			if($empSum >= $otherSum){
			echo "E"; }
			else { 
			echo "O";	
			}
		}
		else if($busYrs == $empYrs){
			echo "E";
		}
		
		
		echo "<br><br><br>";
		$currD = new DateTime(date("r"));
		$dateStamp= new DateTime('2010-03-12');
		//echo $currD.' '.$dateStamp;
		//echo $date1.'  '.$date2;
		if ($currD >= $dateStamp){
			echo "yay";
		}else {echo "yey";}
		
		
		
	}
	
	echo "<br><br>";
	
	/*
	echo $table->getMainCapno('1100102231001000');
	
	echo "<br><br>";
	$data = array('fdfd'=>3, 'ddfd'=>5);
	$json = Zend_Json::encode($data);	
	

    echo Zend_Json::prettyPrint($json, array("indent" => " "));
	*/
	$string = 'Model_BorrowerAccount()';
	$tablex =  "new ".$string;
	//$table = eval($tablex);
	
	//$select = $table->select();
	//$detail = $table->fetchRow($select)->toarray();
	
	print_r(array_keys($detail));
	//print_r($table->info($key = null));
	//$string = " \$curr_model->addSelect_{$table}($id,$value,$wt);";
	//eval($string);
		
	if ($handle = opendir(APPLICATION_PATH.'\models')) {
    echo "Directory handle: $handle\n";
    echo "Files:\n";

    /* This is the correct way to loop over the directory. */
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            if(strpos($file,'.tmp')=== false){
			echo str_replace('.php','',$file).'<br>'; }
        }
	}



    closedir($handle);
	
}

		
	// End of Function
   }
   


} // End of Index Controller

function getCurr($capno){
	//Use to Determine the Sequenceo of the Applica Per Day
	$count = strlen($capno);
	if($count == 14){
	 $currSeq = substr($capno,9,-2);
	}
	else if ($count == 16){
	$currSeq = substr($capno,11,-2);	
	}
	return $currSeq;	
}

function getLastDate($capno){
	$count = strlen($capno);
	if($count == 14){
	$lastDate = substr($capno,3,-5);
	}
	else if ($count == 16){
	$lastDate = substr($capno,5,-5);
	}
	return $lastDate;	
	
	
}

 function capnoGen($loantype,$indivcorp)
    {
   	$retail = 1;
	$branch = '01';
    $crescore = new Model_BorrowerAccount();
    $select= $crescore->select();
    $date = date(mdy);
   	$select->where('relation like ?','Principal');
	$select->order('application_date DESC');
   
	 //11108120901600
	$rows = $crescore->fetchAll($select);
    $curr = getCurr($rows[0]->capno);
    $lastdate = getLastDate($rows[0]->capno);
    $currdate = $date;
    $nextseq = $curr+1;
    $nextseq = str_pad($nextseq,3,'0', STR_PAD_LEFT);
    if ($lastdate ==  $currdate){
    $string = $retail.$loantype.$indivcorp.$branch.$date.$nextseq.'00';} //change 0 when recon is implemented by counting how many changes are made 
	 //substr($string,0,12); 10625090000
   else {
   $string = $retail.$loantype.$indivcorp.$branch.$date.'00100';   
   }  
   return $string;
   }
	
  
 function capnospcoGen($capno,$relation)
    {
    //capno Generation for Spouse and Coborrower
    //11008120901600
	
	
	$recon = capnorecon($capno);
	$capnosep = capnosep($capno);
	
    if ($relation == 'Spouse'){
    	
		$spouses = new Model_BorrowerAccount();
		$sql = $spouses->select()
	    ->where('capno LIKE ?',$capnosep.'_'.$recon)
		->where('relation LIKE ?','Spouse')
		->order('capno');
		$result = $spouses->fetchRow($sql,"capno ASC");
		
		if(count($result) == 0){ // No Spouse Found
		$nextseq = 1;
		}
		else {
	  
		if(capnocurr($result->capno) == 1){
			$nextseq = 2;
		}else if (capnocurr($result->capno) == 2){
			$nextseq = 1;
			
		}
  		//if $curr == 2 limit of adding spouse
    	//add another if for another spouse
		}
	}
	//capno Generation for Coborrower
	elseif ($relation == 'Coborrower'){

		$coborrower = new Model_BorrowerAccount();
		$sql = $coborrower->select()
	    ->where('capno LIKE ?',$capnosep.'_'.$recon)
		->where("relation = 'Coborrower' OR relation = 'Co-Maker'")
		->order('capno');
		$result = $coborrower->fetchAll($sql,"capno ASC");

		if(count($result) == 0){ // No Coborrower Found
		$nextseq = 4;
		}
		else {
			/**
			Capno Generation for Coborrower Start with 4 until 9
			
			-Coborrower Main and Extend partner number 
			
			 - 4 & 5, 6 & 7 , 8 & 9
			
			-Old values should be filled up 1st before using a new borrower id
			
			*/
			$x = 4;
			$y = count($result)+1;
			
			for($z = 0;$z <= $y;$z++){ 
			//echo $capnosep.$x.$recon.'<br>';
			//echo $result[$z][capno].'<br>';
			if($result[$z][capno] != $capnosep.$x.$recon){
 			$nextseq = $x;
			//echo "<br>";
			break;
			}
			//echo $x.'<br>';
			$x = $x + 2;			
			}
		}

		
	}// End of Coborrower Capno Generation

   $string = $capnosep.$nextseq.$recon;  
	
   return $string;
   }
   
function capnocoexGen($capno){
	//Generation of Capno for the Extend of Coborrower ex. spouse
	
	$capnosep = capnosep($capno);
	$curr = capnocurr($capno);
	$recon = capnorecon($capno);
	
	$curr++;
	
	return $capnosep.$curr.$recon;
	
}

function dateDiff($dformat, $endDate, $beginDate){
	//Age Calculator
	$date_parts1=explode($dformat, $beginDate);
	$date_parts2=explode($dformat, $endDate);
	$start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);
	$end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);
	return $end_date - $start_date;
	}


function moneyconvert($amount){
	

	$amount = number_format($amount,2,'.', '');
	return $amount;
}


function moneyformat($amount){
	
	if($amount){
	
	$amount = number_format($amount,2);
	return $amount;
	} else{
	return '0.00';
	}
	
}

function capnosep($capno){
	
	$capnosep = substr($capno,0,-2);
	
	return $capnosep;
	
}

function capnoseprecon($capno){
	// Use in cutting till the end before the recon number
	// example fullcap 1100101271000403
	// with function 110010127100040
	$capnoseprecon = substr($capno,0,-1);
	
	return $capnoseprecon;
	
}

function capnocurr($capno){
	//use in determining the spouse or coborrower!
	$count = strlen($capno);

	if($count == 14){
	$capnocurr = substr($capno,12,1);
	}
	else if($count ==16){
	$capnocurr = substr($capno,14,1);
	}
	return $capnocurr;
}

function capnorecon($capno){
	//use in determining the recon
	$capnorecon = substr($capno,-1);
	
	return $capnorecon;
	
}

function totalincome($capno){
	$sum = 0;
	$sum2 = 0;
	$sum3 = 0;
	$capnosep = capnosep($capno);
	$recon = capnorecon($capno);
	
	$bus = new Model_BorrowerBusiness();
	$select = $bus->select();
	$select->where('capno like ?',$capno);
	$busdetail = $bus->fetchAll($select);
	
	foreach($busdetail as $detail){
    $sum += $detail->bus_income;
    }   
	
	
	$emp = new Model_BorrowerEmployment();
	$select = $emp->select();
	$select->where('capno like ?',$capno)->where("employer = 'Current' or employer = 'Remittance'");
	$empdetail = $emp->fetchAll($select);
	
	foreach($empdetail as $detail){
    $sum2 += $detail->emp_income;
    }   
	
	$other = new Model_BorrowerIncomeMonthly();
	$select = $other->select();
	$select->where('capno like ?',$capno);
	$otherdetail = $other->fetchAll($select);
	
	foreach($otherdetail as $detail){
    $sum3 += $detail->amount;
    }   
	
	return $sum+$sum2+$sum3;
}

function totalcombinedincome($capno){
	
	$capnosep = capnosep($capno);
	$recon = capnorecon($capno);
	$sum = 0;
	$sum2 = 0;
	$sum3 = 0;
	$bus = new Model_BorrowerBusiness();
	$select = $bus->select();
	$select->where('capno like ?',$capnosep.'_'.$recon)
	->where('relation NOT LIKE ?','Co-Maker');
	$busdetail = $bus->fetchAll($select);
	
	foreach($busdetail as $detail){
    $sum += $detail->bus_income;
    }   	
	
	$emp = new Model_BorrowerEmployment();
	$select = $emp->select();
	$select->where('capno like ?',$capnosep.'_'.$recon)->where("employer = 'Current' or employer = 'Remittance'")
	->where('relation NOT LIKE ?','Co-Maker');
	$empdetail = $emp->fetchAll($select);
	
	foreach($empdetail as $detail){
    $sum2 += $detail->emp_income;
    }   
	
	$other = new Model_BorrowerIncomeMonthly();
	$select = $other->select();
	$select->where('capno like ?',$capnosep.'_'.$recon);
	//->where('relation NOT LIKE ?','Co-Maker')

	$otherdetail = $other->fetchAll($select);
	foreach($otherdetail as $detail){
	  	$sum3+= $detail->amount;
	}
	
	
	return $sum+$sum2+$sum3;
	
}

function totalcombinedincomeSpouse($capno){
	
	$capnosep = capnosep($capno);
	$recon = capnorecon($capno);
	$sum = 0;
	$sum2 = 0;
	$sum3 = 0;
	$bus = new Model_BorrowerBusiness();
	$select = $bus->select();
	$select->where('capno like ?',$capnosep.'_'.$recon)
	->where("relation ='Principal' OR relation = 'Spouse'");
	$busdetail = $bus->fetchAll($select);
	
	foreach($busdetail as $detail){
    $sum += $detail->bus_income;
    }   	
	
	$emp = new Model_BorrowerEmployment();
	$select = $emp->select();
	$select->where('capno like ?',$capnosep.'_'.$recon)->where("employer = 'Current' or employer = 'Remittance'")
	->where("relation ='Principal' OR relation = 'Spouse'");
	$empdetail = $emp->fetchAll($select);
	
	foreach($empdetail as $detail){
    $sum2 += $detail->emp_income;
    }   
	
	$other = new Model_BorrowerIncomeMonthly();
	$select = $other->select();
	$select->where('capno like ?',$capnosep.'_'.$recon);
	//->where('relation NOT LIKE ?','Co-Maker')

	$otherdetail = $other->fetchAll($select);
	foreach($otherdetail as $detail){
	  	$sum3+= $detail->amount;
	}
	
	
	return $sum+$sum2+$sum3;
	
}

function chkEmpBusStatus($capno){
	// function to get the status of the highest income from employment and business
	// Getting the highest business income
	$bus = new Model_BorrowerBusiness();
	$select = $bus->select();
	$select->where('capno like ?',$capno);
	$busdetail = $bus->fetchAll($select);
	$busCount = $bus->fetchAll($select)->count();
	$busYrs =0;
	$busSum = 0;
	foreach($busdetail as $detail){	
	if ($detail->bus_income > $busSum){
    $busSum = $detail->bus_income;
	$busYrs = $detail->bus_yrs + ($detail->bus_months / 12);
	}	
	}   
	//end of business
	// Getting the highest income in the employment table
	$emp = new Model_BorrowerEmployment();
	$select = $emp->select();
	$select->where('capno like ?',$capno);
	$select->where('employer NOT LIKE ?','Previous');
	$empdetail = $emp->fetchAll($select);
	$empCount = $emp->fetchAll($select)->count();
	$empSum = 0;
	$empYrs = 0;
	foreach($empdetail as $detail){
    if ($detail->emp_income > $empSum){
    $empSum = $detail->emp_income;
	$emp_status = $detail->employer;
	$empYrs = $detail->emp_yrs + ($detail->emp_yrs / 12);
	}	
	// If Remittance Application is = to the current it is taken as Current
	else if($detail->emp_income == $empSum){
		$emp_status = 'Current';
    }   
	// End of Employment Income
	/**
	 * Paolo Marco Manarang
	 * March 03,2010
	 * New Procedure adding other income to be computed in gmi ratio
	 */
    }   
	// Getting the Highest Income in the Other Monthly Income
	$other = new Model_BorrowerIncomeMonthly();
	$select = $other->select();
	$select->where('capno like ?',$capno);
	$otherdetail = $other->fetchAll($select);
	$otherCount = $other->fetchAll($select)->count();
	$otherSum = 0;
	foreach($otherdetail as $detail){
	  if ($detail->amount > $otherSum){
	  	$otherSum = $detail->amount;
	  }
	}
	// End of Other Income
	/**
	 * Paolo Marco Manarang
	 * paolomanarang@gmail.com
	 * March 03,2010
	 * 
	 * if business income is greater than or equal to employment income
	 * return SE if Business income is also greater than or equal to the other income
	 * 
	 * if employment income is greater than the business income return E if
	 * employment income is also greater than or equal to the other income
	 */
	if($busCount > 0 && $empCount == 0){
		if($busSum >= $otherSum){
			return "SE"; }
		else { 
			return "O";	
		}		
	}
	else if($empCount > 0 && $busCount == 0){
		if($empSum >= $otherSum){
			return "E"; }
		else { 
			return "O";	
		}
	}
	else if($empCount == 0 && $busCount == 0 && $otherCount > 0){
		return "O";	
	}
	else if($empCount > 0 && $busCount > 0){
		/**/
	if ($busSum > $empSum){
		
		if($busSum >= $otherSum){
		return "SE"; }
		else { 
		return "O";	
		}
	}
	else if($busSum < $empSum){
		
		if($empSum > $otherSum){
		return "E"; }
		else { 
		return "O";	
		}
	}

	else if($busSum == $empSum){
		//business and employment is equal chk the yrs varied
		if($busYrs > $empYrs){
			if($busSum >= $otherSum){
			return "SE"; }
			else { 
			return "O";	
			}			
		}
		else if($busYrs < $empYrs) {
			if($empSum >= $otherSum){
			return "E"; }
			else { 
			return "O";	
			}
		}
		else if($busYrs == $empYrs){
			// check if there is no employment or business = Other
			// while busyrs and emp yrs is equal take it as E
			if(($otherSum > $empSum) && ($otherSum > $busSum)){
			return "O";		
			}
			else {
			return "E";
			}
		}
	} /**/
	
	
	}
	/*****/
	
		/*
		if($emp_status == 'Current') {
		return "E"; } 
		// old way of getting if its a remittance or other application
		else if($emp_status == 'Remittance'){
		// O for other source of income
		return "O";				
		}
		*/
	
	/*
	else if($busSum == $empSum){
		return "SE";
		// change to more depth....
	}*/
		
}
function chkdate($date){
	
	if ($date){
		return $date;	
	}
	else {
		return null;
	}
	
}

function calctotalpremium($capno){
	
	$table = new Model_BorrowerInsuranceCharges();
	$select = $table->select();
	$select->where('capno like ?',$capno);
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
    $sum += $detail->misce_charges;
    }   
	
	$table2 = new Model_BorrowerInsurancePolicy();
	$select = $table2->select();
	$select->where('capno like ?',$capno);
	$tabledetail2 = $table2->fetchRow($select);
	
	$sum = $sum + $tabledetail2->amount_coverage + $tabledetail2->net_premium;
	return moneyconvert($sum);
	
}

function login_user(){
	
	$user = Zend_Auth::getInstance()->getIdentity();
	return $user->username;
}

function login_user_role(){
	
	$user = Zend_Auth::getInstance()->getIdentity();
	return $user->role_type;
}

function chkArray($array){
	
	$count = 0;
	foreach ($array as $key => $value) {
		
		if ($value){
			
			$count++;
		}
		
	}
	return $count;
	
	
	
}

function chgAccountStatus($capno){
	
			$account = new Model_BorrowerAccount();
			$where = "capno like '$capno'";
			if(!$account->isComaker($capno)){
			if(login_user_role() == "MA"){
			$data = array(
			'account_status'=>'MA - E',
			'submitted_ma_save'=>date("r"),
			);
			$account->update($data,$where);
						
			//Account History
			$history = new Model_AccountHistory();
			$select = $history->select();
			$select->where('capno like ?',$capno)->order('id DESC');
			$historyDetail = $history->fetchRow($select);
			
			$data2 = array(
			'capno' =>$capno,
			'status' =>'MA - E',
			//'remarks' => $form->getValue('submit_remarks'),
			'date'=>date("r"),
			'by'=>  login_user(),
			'date_last'=>$historyDetail->date,
			);

			$history->insert($data2);
			}
			}
	
}

function rdmCA(){
	
	$ca = new Model_Users();
	$select = $ca->select();
	$select->where('role_type like ?','CA');
	$cadetail = $ca->fetchAll($select);
	
	$caArray = array();
	foreach($cadetail as $detail) {
		
		array_push($caArray, $detail->username);
		
	}
	
	$caRand = array_rand($caArray);
	
	//return $caArray[$caRand];
	// For testing purposes
	if(login_user() == 'testma'){
	return 'testca';
	}else {
	return 'mpalbania';
	}
}

function rdmCO(){
	
	$co = new Model_Users();
	$select = $co->select();
	$select->where('role_type like ?','CO');
	$codetail = $co->fetchAll($select);
	
	$coArray = array();
	foreach($codetail as $detail) {
		
		array_push($coArray, $detail->username);
		
	}
	
	$coRand = array_rand($coArray);
	
	//return $coArray[$coRand];
	//for testing purposes
	if(login_user() == 'testma'){
	return 'testco';	
	}else {
	return 'chkatipunan';
	}
}

function getHighest($capno){
		/* Borrower > Spouse > Co borrower
		 If borrower have equivalnet total income between Spouse or Coborrower
		 Borrower would be the model basis
		 
		 if Borrower total income is less than Spouse and Coborrower which 
		have equivalent total_income Spouse would be the model basis
		
		If borrower total income is less than spouse income
		and spouse income is less than co borrower 
		but if 2 Co borrowers have the same total_income
		Self Employer > Emplyer but 
		if the Coborrower is employed look for the Emp Yrs then Emp Position
		
		
		*/
		
		$capnosep = capnosep($capno);
		$recon = capnorecon($capno);
	
		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		$select->where('capno like ?',$capnosep.'_'.$recon);
		$accntdetail = $accnt->fetchAll($select);
	
		$high = 0.00;
		$highcap = $capno;
		$lasthigh = $capno;
		foreach($accntdetail as $details) : 

		if ($details->total_income > $high){
			$high = $details->total_income;
			$lasthigh = $highcap;
			$highcap = $details->capno;
		} 
		else if($details->total_income == $high){
			$highcap = $lasthigh;
		} 
		endforeach;

		return $highcap;
		
		//return $this->_helper->GetHighest($capno);
	}

function gmi_ratio($capno){
	//Use after deleting an employment or business 
	// automatically UPDATE the gmi of the pass capno
	$capnosep = capnosep($capno);
	$recon = capnorecon($capno);
	
	$accnt = new Model_BorrowerAccount();
	$select = $accnt->select();
	$select->where('capno like ?',$capnosep.'_'.$recon);
	$select->where('relation like ?','Principal');
	$detail = $accnt->fetchRow($select);
	$capno = $detail->capno;
	
	$existloan = new Model_BorrowerObExistLoan();
	$select2 = $existloan->select();
	$select2->where('capno like ?',$capnosep.'_'.$recon); 
	$existDetail = $existloan->fetchAll($select2);
	
	$totalexistloan = 0;
	foreach($existDetail as $x){
	$totalexistloan += $x->monthly_amortization;   
    }  
	
	//$monthly = (($detail->amountloan * $detail->rate) + $detail->amountloan) / $detail->loanterm;
	$totalcombine = totalcombinedincome($detail->capno);
	if($totalcombine > 0){
	$gmi = (($detail->monthly_amortization + $totalexistloan)  / $totalcombine) * 100;
	}
	else {
	$gmi = 1000.00;	
	}
	$data = array(
	'gmi_ratio'=>number_format($gmi, 2, '.', ''),
	//'gmi_ratio'=>$gmi,
	);
	$where = "capno like '$capno'";
	$accnt->update($data,$where);
	
}

function re_gmi_ratio($capno){
	//Use when updating the borrower profile
	//return the computer gmi of a given capno
	$capnosep = capnosep($capno);
	$recon = capnorecon($capno);
	
	$accnt = new Model_BorrowerAccount();
	$select = $accnt->select();
	$select->where('capno like ?',$capnosep.'_'.$recon);
	$select->where('relation like ?','Principal');
	$detail = $accnt->fetchRow($select);
	
	$existloan = new Model_BorrowerObExistLoan();
	$select2 = $existloan->select();
	$select2->where('capno like ?',$capnosep.'_'.$recon); 
	$existDetail = $existloan->fetchAll($select2);
	
	$totalexistloan = 0;
	foreach($existDetail as $x){
	$totalexistloan += $x->monthly_amortization;   
    }   
	
	//$monthly = (($detail->amountloan * $detail->rate) + $detail->amountloan) / $detail->loanterm;
	$totalcombine = totalcombinedincome($detail->capno);
	if($totalcombine > 0){
	$gmi = (($detail->monthly_amortization + $totalexistloan)  / $totalcombine) * 100;
	return number_format($gmi, 2, '.', '');
	}
	else {
	return 1000.00;	
	}
}
function ifnull($value){
	
	if(!$value){
		return '0';
	}
	else {
		
		return $value;
	}
	
}

function print_r_html ($array) {
        /*
		?><pre><?
        print_r($arr);
        ?></pre><?
		*/
		foreach ($array AS $key => $value) {
 		//echo "<strong>$key</strong>";
 		echo "<br />";
		echo $value;
		echo "<br />";
}

}

function chkAppType($chk,$capno){
	
	if($chk == 'SE'){
		
		return "Regular";
		
	}
	else if($chk == 'O'){
		
		return "Regular";
		
	}
	else if($chk == 'E'){

	$name = getHighEmpName($capno);
	
	if($name){
			$employment = new Model_ChainCompany();
			$select = $employment->select();
			$select->where('company_name like ?',$name);
			$count = $employment->fetchAll($select)->count();	
			
			if($count == 0){
			return "Regular";				
			}	
			else {
			return "Fast Track";
			}
		}
	else {
			return "Regular";				
	}
	}
	else {
		return "Regular";
	}	
}

function getHighEmpName($capno){
			
			//Determine the Highest Salary among the employments and get 
			//its Emp ID to be use in the Deviation
			
			$emp = new Model_BorrowerEmployment();
			$select = $emp->select();
			$select->where('capno like ?',$capno);
			$empdetail = $emp->fetchAll($select);
			

			foreach($empdetail as $detail){
				
		    if ($detail->emp_income > $sum){
		    	$sum = $detail->emp_income;
			    $emp_name = $detail->emp_name;
				}
			}
			
		return $emp_name;

}



function calctotalautoemv($capno){
	
	$table = new Model_BorrowerOtherAuto();
	$select = $table->select();
	$select->where('capno like ?',$capno);
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->auto_emv;
    
    }   
    return moneyconvert($sum);
}

function calctotalrealemv($capno){
	
	$table = new Model_BorrowerOtherReal();
	$select = $table->select();
	$select->where('capno like ?',$capno);
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->real_emv;
    
    }   
    return moneyconvert($sum);
}

function calctotalshareemv($capno){
	
	$table = new Model_BorrowerOtherShare();
	$select = $table->select();
	$select->where('capno like ?',$capno);
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->shares_emv;
    
    }   
    return moneyconvert($sum);
}


function calctotalbank($capno){
	
	$table = new Model_BorrowerObBank();
	$select = $table->select();
	$select->where('capno like ?',$capno);
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->adb;
    
    }   
    return moneyconvert($sum);
}


function calctotalexistingloans($capno){
	
	$table = new Model_BorrowerObExistLoan();
	$select = $table->select();
	$select->where('capno like ?',$capno);
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->amount;
    
    }   
    return moneyconvert($sum);
}


function calcexisting_autoloan($capno){
	$table = new Model_BorrowerObExistLoan();
	$select = $table->select();
	$select->where('capno like ?',$capno)
	       //->where('bank LIKE ?','CHINA BANKING CORP') 
	       ->where("bank='CHINA BANKING CORP' or bank = 'CHINA BANK SAVINGS'")
		->where('facility_type LIKE ?','Auto');
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->amount;
    }   
    return moneyconvert($sum);
}

function calcexisting_houseloan($capno){
	$table = new Model_BorrowerObExistLoan();
	$select = $table->select();
	$select->where('capno like ?',$capno)
	       ->where("bank='CHINA BANKING CORP' or bank = 'CHINA BANK SAVINGS'")
		->where('facility_type LIKE ?','Home');
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->amount;
    }   
    return moneyconvert($sum);
}

function calcexisting_personalloan($capno){
	$table = new Model_BorrowerObExistLoan();
	$select = $table->select();
	$select->where('capno like ?',$capno)
	       ->where("bank='CHINA BANKING CORP' or bank = 'CHINA BANK SAVINGS'")
		->where('facility_type LIKE ?','Personal');
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->amount;
    }   
    return moneyconvert($sum);
}

function calcexisting_businessloan($capno){
	$table = new Model_BorrowerObExistLoan();
	$select = $table->select();
	$select->where('capno like ?',$capno)
	       ->where("bank='CHINA BANKING CORP' or bank = 'CHINA BANK SAVINGS'")
		->where('facility_type LIKE ?','Business');
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->amount;
    }   
    return moneyconvert($sum);
}

function getExistLoan($capno,$process,$type,$bank){
   //Updated function for Craw getting the Existing Loan
   //Paolo Marco Manarang
	$table = new Model_BorrowerObExistLoan();
	$select = $table->select();
	$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno))
		->where('facility_type LIKE ?',$type)
	    ->order('bank');
	
	if($bank =='Others'){
    $select->where("bank !='CHINA BANKING CORP' AND bank != 'CHINA BANK SAVINGS'");
	}else {
    $select->where("bank='CHINA BANKING CORP' or bank = 'CHINA BANK SAVINGS'");
	}
	
	
	$tabledetail = $table->fetchAll($select);
	
	if($process == 'all'){
		return $tabledetail;
	}
	else if($process == 'totalvalue') { 
		foreach($tabledetail as $detail){
		$sum += $detail->amount;
	    }   
	    return moneyconvert($sum);
	}
	else if($process == 'totalvalueloan') { 
		foreach($tabledetail as $detail){
		//$sum += $detail->amount;
		$sum += $detail->loan_amount;
	    }   
	    return moneyconvert($sum);
	}
	else { 
		foreach($tabledetail as $detail){
		$sum += $detail->monthly_amortization;
	    }   
	    return moneyconvert($sum);
	}
}


function getBank($capno,$process,$type,$bank){
   //Updated function for Craw getting the Bank
   //Paolo Marco Manarang
	$table = new Model_BorrowerObBank();
	$select = $table->select();
	$select->where('capno like ?',capnosep($capno).'_'.capnorecon($capno))
		->where('account_type LIKE ?',$type);
	    
	
	if($bank =='Others'){
    $select->where("bank !='CHINA BANKING CORP' AND bank != 'CHINA BANK SAVINGS'");
	}else {
    $select->where("bank = 'CHINA BANK SAVINGS'");
	}
	
	
	$tabledetail = $table->fetchAll($select);
	
	if($process == 'all'){
		return $tabledetail;
	}
	
	else if($process == 'totalvalue') { 
		foreach($tabledetail as $detail){
		$sum += $detail->adb;
	    }   
	    return moneyconvert($sum);
	}
	/*
	else { 
		foreach($tabledetail as $detail){
		$sum += $detail->monthly_amortization;
	    }   
	    return moneyconvert($sum);
	}
	*/
}

function calcexisting_otherloan($capno){
	$table = new Model_BorrowerObExistLoan();
	$select = $table->select();
	$select->where('capno like ?',$capno)
	       ->where("bank='CHINA BANKING CORP' or bank = 'CHINA BANK SAVINGS'")
		->where('facility_type LIKE ?','Others');
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->amount;
    }   
    return moneyconvert($sum);
}


function calcexisting_autoinstallment($capno){
	$table = new Model_BorrowerObExistLoan();
	$select = $table->select();
	$select->where('capno like ?',$capno)
	         ->where("bank='CHINA BANKING CORP' or bank = 'CHINA BANK SAVINGS'")
		->where('facility_type LIKE ?','Auto');
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->monthly_amortization;
    }   
    return moneyconvert($sum);
}



function calcexisting_houseinstallment($capno){
	$table = new Model_BorrowerObExistLoan();
	$select = $table->select();
	$select->where('capno like ?',$capno)
	       ->where("bank='CHINA BANKING CORP' or bank = 'CHINA BANK SAVINGS'")
		->where('facility_type LIKE ?','Home');
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->monthly_amortization;
    }   
    return moneyconvert($sum);
}

function calcexisting_personalinstallment($capno){
	$table = new Model_BorrowerObExistLoan();
	$select = $table->select();
	$select->where('capno like ?',$capno)
	         ->where("bank='CHINA BANKING CORP' or bank = 'CHINA BANK SAVINGS'")
		->where('facility_type LIKE ?','Personal');
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->monthly_amortization;
    }   
    return moneyconvert($sum);
}

function calcexisting_businessinstallment($capno){
	$table = new Model_BorrowerObExistLoan();
	$select = $table->select();
	$select->where('capno like ?',$capno)
	         ->where("bank='CHINA BANKING CORP' or bank = 'CHINA BANK SAVINGS'")
		->where('facility_type LIKE ?','Business');
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->monthly_amortization;
    }   
    return moneyconvert($sum);
}


function calcexisting_otherinstallment($capno){
	$table = new Model_BorrowerObExistLoan();
	$select = $table->select();
	$select->where('capno like ?',$capno)
	        ->where("bank='CHINA BANKING CORP' or bank = 'CHINA BANK SAVINGS'")
		->where('facility_type LIKE ?','Others');
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->monthly_amortization;
    }   
    return moneyconvert($sum);
}

function calcexisting_auto_other($capno){
	$table = new Model_BorrowerObExistLoan();
	$select = $table->select();
	$select->where('capno like ?',$capno)
	 //   ->where('bank not LIKE ?','CHINA BANKING CORP')
		->where("bank !='CHINA BANKING CORP' AND bank != 'CHINA BANK SAVINGS'")
		->where('facility_type  LIKE ?','Auto');
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->monthly_amortization;
    }   
    return moneyconvert($sum);
}

function calcexisting_house_other($capno){
	$table = new Model_BorrowerObExistLoan();
	$select = $table->select();
	$select->where('capno like ?',$capno)
	       ->where("bank !='CHINA BANKING CORP' AND bank != 'CHINA BANK SAVINGS'")
		->where('facility_type  LIKE ?','Home');
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->monthly_amortization;
    }   
    return moneyconvert($sum);
}

function calcexisting_personal_other($capno){
	$table = new Model_BorrowerObExistLoan();
	$select = $table->select();
	$select->where('capno like ?',$capno)
	        ->where("bank !='CHINA BANKING CORP' AND bank != 'CHINA BANK SAVINGS'")
		->where('facility_type  LIKE ?','Personal');
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->monthly_amortization;
    }   
    return moneyconvert($sum);
}

function calcexisting_business_other($capno){
	$table = new Model_BorrowerObExistLoan();
	$select = $table->select();
	$select->where('capno like ?',$capno)
	       ->where("bank !='CHINA BANKING CORP' AND bank != 'CHINA BANK SAVINGS'")
		->where('facility_type  LIKE ?','Business');
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->monthly_amortization;
    }   
    return moneyconvert($sum);
}


function calcexisting_other_other($capno){
	$table = new Model_BorrowerObExistLoan();
	$select = $table->select();
	$select->where('capno like ?',$capno)
	        ->where("bank !='CHINA BANKING CORP' AND bank != 'CHINA BANK SAVINGS'")
		->where('facility_type  LIKE ?','Others');
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->monthly_amortization;
    }   
    return moneyconvert($sum);
}


function caltotal_relationship_cbs($capno){
	$table = new Model_BorrowerObBank();
	$select = $table->select();
	$select->where('capno like ?',$capno)
	       ->where('bank LIKE ?','CHINA BANK SAVINGS');
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->adb;
    }   
    return moneyconvert($sum);
}

function caltotal_gross_sales($capno){
	$table = new Model_BorrowerBusiness();
	$select = $table->select();
	$select->where('capno like ?',$capno);
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->total_gross_sales;
    }   
    return moneyconvert($sum);
}

function caltotal_cost_sales($capno){
	$table = new Model_BorrowerBusiness();
	$select = $table->select();
	$select->where('capno like ?',$capno);
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->total_cost_sales;
    }   
    return moneyconvert($sum);
}

function caltotal_net_income_before($capno){
	$table = new Model_BorrowerBusiness();
	$select = $table->select();
	$select->where('capno like ?',$capno);
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->total_net_income_before;
    }   
    return moneyconvert($sum);
}

function updateTotalIncome($capno){
	//Update the Total income per Client Principal,CoBorrower, Spouse
	$capnosep = capnosep($capno);
	$recon = capnorecon($capno);
	
	$table = new Model_BorrowerAccount();
	$select = $table->select();
	$select->where('capno like ?',$capnosep.'_'.$recon);
	$tableDetail = $table->fetchAll($select);
	
	$emp = new Model_BorrowerEmployment();
	$bus = new Model_BorrowerBusiness();
	
	foreach($tableDetail as $detail){
		$tempcap = $detail->capno;
		$where = "capno like '$tempcap'";
		//echo totalincome($detail->capno);
		
		 $arr = array(
		 'total_income' => totalincome($detail->capno)
		 );
		 
		 $table->update($arr,$where);	
	}
	
}

function microtime_float()
{
    list($utime, $time) = explode(" ", microtime());
    return ((float)$utime + (float)$time);
}


//calculate total business financial asset
function calctotalbfasset($capno){
	
	$table = new Model_BorrowerOtherBusFinance();
	$select = $table->select()
	->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno));
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->business_asset_emv;
    
    }   
    return moneyconvert($sum);
}


//calculate total business financial liability
function calctotalbfliability($capno){
	
	$table = new Model_BorrowerObBfLiabilities();
	$select = $table->select()
	->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno));
	$tabledetail = $table->fetchAll($select);
	
	foreach($tabledetail as $detail){
	$sum += $detail->business_liability_emv;
    
    }   
    return moneyconvert($sum);
}

function returnprevCap($capno){
		$newrecon = capnorecon($capno)-1;
		return capnoseprecon($capno).$newrecon;		
}

function returnnextCap($capno){
		$newrecon = capnorecon($capno)+1;
		return capnoseprecon($capno).$newrecon;		
}

function ifAlreadyRecon($capno){
	// if the account is already recon
	$next = returnnextCap($capno);
	
	$model = new Model_BorrowerAccount();
	$detail = $model->fetchRowModel($next);
	
	if($detail->capno){
		return true;
	}else {
		return false;
	}
	
	
}

function updateLoanAmount2($capno){
	$table = new Model_BorrowerAccount();
	$detail = $table->fetchRowModel($capno);

	$existing_auto_loan = getExistLoan($capno,'totalvalueloan','Auto','CBCS');
	$existing_housing_loan = getExistLoan($capno,'totalvalueloan','Home','CBCS');
	$existing_personal_loan = getExistLoan($capno,'totalvalueloan','Personal','CBCS');
	$existing_business_loan = getExistLoan($capno,'totalvalueloan','Business','CBCS');
	$existing_other_loan = getExistLoan($capno,'totalvalueloan','Others','CBCS');		
	$total_bankproper_loan= $existing_auto_loan + $existing_housing_loan + $existing_personal_loan + $existing_business_loan + $existing_other_loan;			

	$data = array(
	'amountloan2'=>moneyconvert($total_bankproper_loan+$detail->amountloan),
	);
	$where = "capno like '$capno'";
	$table->update($data,$where);
	
}

function arrayString($array){
	foreach($array as $x){
		$string = $string.$x;
		
	}
	return $string;
	
}

