<?php

class IndexController extends Zend_Controller_Action
{

    public function preDispatch()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
          	}
		else {
            $this->_helper->redirector('login','auth');
            }
			
    }
	
    public function init()
    {
        /* Initialize action controller here */
		
    }

    public function indexAction()
    {
        // action body
		$this->view->urll = $this->_getParam('id');
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
		
		 if ($this->getRequest()->isPost()) { // Start of getRequest()->isPost()
	     $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {  
			
			$capno = capnoGen($form->getValue('typeloan'),0);

			$accnt = new Model_BorrowerAccount();
			
			if ($form->getValue('landline')){
			$landline = 1;}else {$landline = 0;}
			
			//data to be insert to Borrower Accounts
			$data  = array(
			'capno' => $capno,
			'application_date' => date("r"), // change to Y-m-d h:i:s if 12hrs or h
			'borrower_lname' => strtoupper($form->getValue('borrower_lname')),
			'borrower_fname' => strtoupper($form->getValue('borrower_fname')),
			'borrower_mname' => strtoupper($form->getValue('borrower_mname')),
			'birthdate' => $form->getValue('birthdate'),
			'pres_address_no' => strtoupper($form->getValue('borrower_pres_address_no')),
			'pres_address_st' => strtoupper($form->getValue('borrower_pres_address_st')),
			'pres_address_brgy' => strtoupper($form->getValue('borrower_pres_address_brgy')),
			'pres_address_city' => strtoupper($form->getValue('borrower_pres_address_city')),
			'pres_address_province' => strtoupper($formData['borrower_pres_address_province']),
			'pres_zipcode' => $form->getValue('pres_zipcode'),
			'source_application' => $form->getValue('source_application'),
			'landline' => $form->getValue('landline'),
			'mobile' => $form->getValue('mobile'),
			'email' => $form->getValue('email'),
			'age'=>trim(round(dateDiff("/", date("m/d/Y", time()), date("m/d/Y",strtotime($form->getValue('birthdate'))))/365, 0),'.'),
			'relation' => 'Principal',
			'loantype' => $form->getValue('typeloan'),
			'promo_fid'=> $form->getValue('promo'),
			'gender'=> $form->getValue('gender'),
			'tel_avail' => $landline,
			'civilstatus' => $form->getValue('civilstatus'),
			'account_status' => 'created',
			'created_by'=>login_user(),
			);
			$accnt->insert($data);		
			
			
			
			
			//adding Spouse if Civil Status is Married or S;eparated
			if ($form->getValue('civilstatus') == 2 || $form->getValue('civilstatus') == 3){
			$data2 = array(
			'capno' =>capnospcoGen($capno,'Spouse'),
			'borrower_lname' =>$form->getValue(borrower_spouse_lname),
			'borrower_fname' =>$form->getValue(borrower_spouse_fname),
			'borrower_mname' =>$form->getValue(borrower_spouse_mname),
			'birthdate' => $form->getValue(birthdate_spouse),
			'relation' => 'Spouse'
			);
			$accnt->insert($data2);	
				
			}
			
			$status = new Model_AccountStatus();
			$data3 = array(
			'capno'=>$capno,
			'status'=>'created',
			'by' => login_user(),
			'date' =>date("r"),
			);
			$status->insert($data3);
					
			$this->_redirect('/index/accountedit/cap/'.$capno);
			
			
			}
		 } // End of getRequest()->isPost()
		
    }
	
public function accountAction(){
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
		$cvdetail = $veh->fetchRow($select);
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
		$form = new Form_AccountPage();
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
         $form->profile->addMultiOption(BaseUrl().'/index/account/cap/'.$c->capno, strtoupper($c->relation).' - '.$c->borrower_lname.','.$c->borrower_fname);} 
		 
		 foreach ($tables->fetchAll($sql2,"capno ASC") as $c) {
         $form->profile->addMultiOption(BaseUrl().'/index/account/cap/'.$c->capno, strtoupper($c->relation).' - '.$c->borrower_lname.','.$c->borrower_fname);} 
		//end of Adding Spouse & Coborrower Info 
		
		$this->view->form = $form;
		$this->view->capno = $capno;
		$this->view->application_date = date('m/d/Y',strtotime($accntdetail->application_date));
		$this->view->origcapno = capnosep($capno).'0'.capnorecon($capno);
		$this->view->totalincome = moneyformat(totalincome($capno));
		$this->view->totalcombinedincome = moneyformat(totalcombinedincome($capno));
	
	}


public function accounteditAction(){

		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/menu.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
 

		 
		$listcity = new Model_ListCity();
		$listcitydetail = $listcity->fetchAll();
		$this->view->listcitydetail = $listcitydetail;
		
		$capno = $this->_getParam('cap');		
		
		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		$select->where('capno like ?',$capno);
		$accntdetail = $accnt->fetchRow($select);
		//$this->view->empdetail = $empdetails; use in view account
		
				
		//Determine which layout to use depends on the relation		
		if ($accntdetail->relation == 'Spouse'){
			$this->_helper->viewRenderer('account-edit-spouse');
		}elseif ($accntdetail->relation == 'Coborrower'){
			$this->_helper->viewRenderer('account-edit-coborrower');
		}elseif ($accntdetail->relation == 'Principal'){
			$this->_helper->viewRenderer('account-edit-borrower');
		}
		
		$form = new Form_AccountPage();
		
		$form->borrower_fname->setValue($accntdetail->borrower_fname);
		$form->borrower_lname->setValue($accntdetail->borrower_lname);
		$form->borrower_mname->setValue($accntdetail->borrower_mname);
		$form->typeloan->setValue($accntdetail->loantype);
		$form->borrower_pres_address_no->setValue($accntdetail->pres_address_no);
		$form->borrower_pres_address_st->setValue($accntdetail->pres_address_st);
		$form->borrower_pres_address_brgy->setValue($accntdetail->pres_address_brgy);
		$this->view->borrower_pres_address_city = $accntdetail->pres_address_city;
		$this->view->borrower_pres_address_province = $accntdetail->pres_address_province;
		$form->pres_zipcode->setValue($accntdetail->pres_zipcode);
		
		//Details Tab
		$form->borrower_prev_address_no->setValue($accntdetail->prev_address_no);
		$form->borrower_prev_address_st->setValue($accntdetail->prev_address_st);
		$form->borrower_prev_address_brgy->setValue($accntdetail->prev_address_brgy);
		$this->view->borrower_prev_address_province = $accntdetail->prev_address_province;
		$form->prev_zipcode->setValue($accntdetail->prev_zipcode);
			
		$form->email->setValue($accntdetail->email);
		$form->mobile->setValue($accntdetail->mobile);
		$form->gender->setValue($accntdetail->gender);
		$form->landline->setValue($accntdetail->landline);
		$form->civilstatus->setValue($accntdetail->civilstatus);
		$form->tin_id->setValue($accntdetail->tin_id);
		
		$form->lenghtstay->setValue($accntdetail->residence_yrs);
		$form->residencetype->setValue($accntdetail->residence_type);
		$form->neighborhoodtype->setValue($accntdetail->neighborhoodtype);
		$form->birthdate->setValue(date('m/d/Y',strtotime($accntdetail->birthdate)));
		//$form->birthdate->setValue($accntdetail->birthdate);
		$form->birthplace->setValue($accntdetail->birthplace);
		$form->maiden_name->setValue($accntdetail->maiden_name);

		$this->view->age = $accntdetail->age;
		$form->dependentno->setValue($accntdetail->dependentno);
		$form->citizenship->setValue($accntdetail->citizenship);
		$form->relation->setValue($accntdetail->relation);
		
		//Unit Tabs
		$form->dealer->setValue($accntdetail->dealer);
		$form->veh_brand->setValue($accntdetail->veh_brand);
		$form->veh_status->setValue($accntdetail->veh_status);
		$form->veh_type->setValue($accntdetail->veh_type);
		$form->veh_unit->setValue($accntdetail->veh_unit);
		$form->veh_yrmodel->setValue($accntdetail->veh_yrmodel);
		$form->veh_age->setValue($accntdetail->veh_age);
		$form->veh_use->setValue($accntdetail->veh_use);
		
		//Adding Loan Details to the Edit Form
		$form->selling_price->setValue($accntdetail->selling_price);
		$form->downpayment_actual->setValue($accntdetail->downpayment_actual);
		$form->downpayment_percent->setValue($accntdetail->downpayment_percent);
		$form->loanterm->setValue($accntdetail->loanterm);
		$form->amountloan->setValue($accntdetail->amountloan);
		$form->monthly_amortization->setValue($accntdetail->monthly_amortization);
		$form->gmi_ratio->setValue($accntdetail->gmi_ratio);
		$form->rate->setValue($accntdetail->rate);
		$form->addon_rate->setValue($accntdetail->addon_rate);
		
		//Appraisal Tab
		$form->fmv->setValue($accntdetail->fmv);
		$form->appraisal_value->setValue($accntdetail->appraisal_value);
		$form->car_history->setValue($accntdetail->car_history);


		



		//Adding CV Detail to the Edit Form
		$cv = new Model_BorrowerCv();
		$select = $cv->select();
		$select->where('capno like ?',$capno);
		$cvdetail = $cv->fetchRow($select);
		// Remarks for CV
		$form->remarks_bap->setValue($cvdetail->remarks_bap);
		$form->remarks_nfis->setValue($cvdetail->remarks_nfis);
		$form->remarks_cmap->setValue($cvdetail->remarks_cmap);
		$form->remarks_bankref->setValue($cvdetail->remarks_bankref);
		$form->remarks_creditchk->setValue($cvdetail->remarks_creditchk);
		$form->remarks_pastdealings->setValue($cvdetail->remarks_pastdealings);
		$form->remarks_srcincomever->setValue($cvdetail->remarks_srcincomever);
		$form->remarks_empver->setValue($cvdetail->remarks_empver);
		$form->remarks_busver->setValue($cvdetail->remarks_busver);
		$form->remarks_trdchk->setValue($cvdetail->remarks_trdchk);
		$form->remarks_backgrd->setValue($cvdetail->remarks_backgrd);
		
		$this->view->bap = $cvdetail->bap;
		$this->view->nfis = $cvdetail->nfis;
		$this->view->cmap = $cvdetail->cmap;
		$this->view->bankref = $cvdetail->bankref;
		$this->view->creditchk = $cvdetail->creditchk;
		$this->view->pastdealings = $cvdetail->pastdealings;
		$this->view->srcincomever = $cvdetail->srcincomever;
		$this->view->empver = $cvdetail->empver;
		$this->view->busver = $cvdetail->busver;
		$this->view->trdchk = $cvdetail->trdchk;
		$this->view->backgrd = $cvdetail->backgrd;
		
		$form->model_cv_srcincomever->setValue($cvdetail->model_cv_srcincomever);
		$form->model_cv_empver->setValue($cvdetail->model_cv_empver);
		$form->model_cv_busver->setValue($cvdetail->model_cv_busver);
		$form->model_cv_trdchk->setValue($cvdetail->model_cv_trdchk);
		$form->model_cv_backgrd->setValue($cvdetail->model_cv_backgrd);

		
		//Adding CI Detail to the Edit Form
		$ci = new Model_BorrowerCi();
		$select = $ci->select();
		$select->where('capno like ?',$capno);
		$cidetail = $ci->fetchRow($select);
		// Remarks for CV

		$form->remarks_srcincomever_ci->setValue($cidetail->remarks_srcincomever_ci);
		$form->remarks_empver_ci->setValue($cidetail->remarks_empver_ci);
		$form->remarks_busver_ci->setValue($cidetail->remarks_busver_ci);
		$form->remarks_trdchk_ci->setValue($cidetail->remarks_trdchk_ci);
		$form->remarks_backgrd_ci->setValue($cidetail->remarks_backgrd_ci);
		

		$this->view->srcincomever_ci = $cidetail->srcincomever_ci;
		$this->view->empver_ci = $cidetail->empver_ci;
		$this->view->busver_ci = $cidetail->busver_ci;
		$this->view->trdchk_ci = $cidetail->trdchk_ci;
		$this->view->backgrd_ci = $cidetail->backgrd_ci;
		
		$form->model_ci_srcincomever->setValue($cidetail->model_ci_srcincomever);
		$form->model_ci_empver->setValue($cidetail->model_ci_empver);
		$form->model_ci_busver->setValue($cidetail->model_ci_busver);
		$form->model_ci_trdchk->setValue($cidetail->model_ci_trdchk);
		$form->model_ci_backgrd->setValue($cidetail->model_ci_backgrd);
		

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
		

		
			    
		//Adding Spouse & Coborrower information in the profile select object 
		
		$tables = new Model_BorrowerAccount();
		
		$sql = $tables->select()
	    ->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where('relation LIKE ?','Spouse')
		->order("capno ASC");
		

		$sql2 = $tables->select()
	    ->where('capno LIKE ?',capnosep($capno).'_'.capnorecon($capno))
		->where('relation LIKE ?','Coborrower')
		->order("capno ASC");
		
		
		//If the number of spouse is 2 remove the option value for adding spouse
		if($tables->fetchAll($sql)->count() < 2){
		$form->profile->addMultiOption(BaseUrl().'/index/addspouse/cap/'.$capno ,'Add Spouse');
		}
		//If the number of coborrower is 9  remove the option value for adding spouse
		if($tables->fetchAll($sql2)->count() < 9){
		$form->profile->addMultiOption(BaseUrl().'/index/addcoborrower/cap/'.$capno ,'Add Coborrower');
		}
		
		 foreach ($tables->fetchAll($sql,"capno ASC") as $c) {
         $form->profile->addMultiOption(BaseUrl().'/index/accountedit/cap/'.$c->capno, strtoupper($c->relation).' - '.$c->borrower_lname.','.$c->borrower_fname);} 
		 
		 foreach ($tables->fetchAll($sql2,"capno ASC") as $c) {
         $form->profile->addMultiOption(BaseUrl().'/index/accountedit/cap/'.$c->capno, strtoupper($c->relation).' - '.$c->borrower_lname.','.$c->borrower_fname);} 
		//end of Adding Spouse & Coborrower Info 
		
		
		$this->view->form = $form;
		$this->view->capno = $accntdetail->capno;
		$this->view->application_date = date('m/d/Y',strtotime($accntdetail->application_date));
		$this->view->origcapno = capnosep($capno).'0'.capnorecon($capno);
		$this->view->totalincome = moneyformat(totalincome($capno));
		$this->view->totalcombinedincome = moneyformat(totalcombinedincome($capno));

	if ($this->getRequest()->isPost()) {
   	 $formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)) {
	    	
			$account = new Model_BorrowerAccount();

			$capno = $this->_getParam('cap');	
				
			if ($form->getValue('landline')){
			$landline = 1;}else {$landline = 0;}
			
			
			if ($form->getValue('relation') == 'Principal'){
			
			// Form Data Gatheiring if the Account is the Principal Borrower
			
				if($formData['save_profile']){
				$data = array(
				'borrower_lname' => strtoupper($form->getValue('borrower_lname')),
				'borrower_fname' => strtoupper($form->getValue('borrower_fname')),
				'borrower_mname' => strtoupper($form->getValue('borrower_mname')),
				'pres_address_no' => strtoupper($form->getValue('borrower_pres_address_no')),
				'pres_address_st' => strtoupper($form->getValue('borrower_pres_address_st')),
				'pres_address_brgy' => strtoupper($form->getValue('borrower_pres_address_brgy')),
				'pres_address_city' => $form->getValue('borrower_pres_address_city'),
				'pres_address_province' => strtoupper($formData['borrower_pres_address_province']),
				'pres_zipcode' => $form->getValue('pres_zipcode'),
			
				'landline' => $form->getValue('landline'),			
				'mobile' => $form->getValue('mobile'),
				'email' => $form->getValue('email'),
				'gender'=> $form->getValue('gender'),
				'civilstatus' => $form->getValue('civilstatus'),
				'tin_id' => $form->getValue('tin_id'),
				);
				$where = "capno like '$capno'";
				$account->update($data,$where);			     
				}
			
				'prev_address_no' => strtoupper($form->getValue('borrower_prev_address_no')),
				'prev_address_st' => strtoupper($form->getValue('borrower_prev_address_st')),
				'prev_address_brgy' => strtoupper($form->getValue('borrower_prev_address_brgy')),
				'prev_address_city' => strtoupper($form->getValue('borrower_prev_address_city')),
				'prev_address_province' => strtoupper($formData['borrower_prev_address_province']),
				'prev_zipcode' => $form->getValue('prev_zipcode'),
			
				'residence_yrs' => $form->getValue('lenghtstay'),
				'residence_type' => $form->getValue('residencetype'),
				'neighborhoodtype'=>$form->getValue('neighborhoodtype'),
			
				'birthdate' => $form->getValue('birthdate'),
				'birthplace' =>$form->getValue('birthplace'),
				'maiden_name' =>$form->getValue('maiden_name'),
				
				'dependentno' =>$form->getValue('dependentno'),
				'citizenship' =>$form->getValue('citizenship'),
				'tel_avail' => $landline,
				'age'=>trim(round(dateDiff("/", date("m/d/Y", time()), date("m/d/Y",strtotime($form->getValue('birthdate'))))/365, 0),'.'),
				'loantype' => $form->getValue('typeloan'),
				'relation' => 'Principal',
			
			//Unit
			'dealer' => $form->getValue('dealer'),
			'veh_brand' => $form->getValue('veh_brand'),
			'veh_status' => $form->getValue('veh_status'),
			'veh_type' => $form->getValue('veh_type'),
			'veh_unit' => $form->getValue('veh_unit'),
			'veh_yrmodel' =>$form->getValue('veh_yrmodel'),
			'veh_age'=>$form->getValue('veh_age'),
			'veh_use'=>$form->getValue('veh_use'),
			
			
			// Loan Tabs
			'amountloan'=>moneyconvert($form->getValue('amountloan')),
			'selling_price'=>moneyconvert($form->getValue('selling_price')),
			'downpayment_actual'=>moneyconvert($form->getValue('downpayment_actual')),
			'downpayment_percent'=>$form->getValue('downpayment_percent'),
			'monthly_amortization'=>moneyconvert($form->getValue('monthly_amortization')),
			'gmi_ratio'=>$form->getValue('gmi_ratio'),
			'loanterm'=>$form->getValue('loanterm'),
			'addon_rate'=>$form->getValue('addon_rate'),
			'rate'=>$form->getValue('rate'),
			
			//CV TAbs 
			'srcincomever' => $formData['srcincomever'],
			'empver' => $formData['empver'],
			'busver' => $formData['busver'],
			'trdchk' => $formData['trdchk'],
			'backgrd' => $formData['backgrd'],
			
			//Appraisal
			'fmv' => moneyconvert($form->getValue('fmv')),
			'appraisal_value' => moneyconvert($form->getValue('appraisal_value')),
			'car_history' => $form->getValue('car_history'),
			
			
		
			//'promo_fid'=> $form->getValue('promo'),
			//'source_application' => $form->getValue('source_application'),
			);	
			
			$data2 = array(
			//Get Form Data for Unit Other Details motor_no,or_no etc
			'capno' => $capno,
			'dealer' => $form->getValue('dealer'),
			'veh_brand' => $form->getValue('veh_brand'),
			'veh_status' => $form->getValue('veh_status'),
			'veh_type' => $form->getValue('veh_type'),
			'veh_unit' => $form->getValue('veh_unit'),
			'veh_yrmodel' =>$form->getValue('veh_yrmodel'),
			'veh_age'=>$form->getValue('veh_age'),
			'veh_use'=>$form->getValue('veh_use'),
			
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
				$vehdetails->update($data2,$where);						
				}
			
			$data3 = array(
			// Get Form data for CV Tabs
			'capno' => $capno,
			'bap' => $formData['bap'],
			'nfis' => $formData['nfis'],
			'cmap' => $formData['cmap'],
			'bankref' => $formData['bankref'],
			'creditchk' => $formData['creditchk'],
			'pastdealings' => $formData['pastdealings'],
			'srcincomever' => $formData['srcincomever'],
			'empver' => $formData['empver'],
			'busver' => $formData['busver'],
			'trdchk' => $formData['trdchk'],
			'backgrd' => $formData['backgrd'],
			'remarks_bap' => $formData['remarks_bap'],
			'remarks_nfis' => $formData['remarks_nfis'],
			'remarks_cmap' => $formData['remarks_cmap'],
			'remarks_bankref' => $formData['remarks_bankref'],
			'remarks_creditchk' => $formData['remarks_creditchk'],
			'remarks_pastdealings' => $formData['remarks_pastdealings'],
			'remarks_srcincomever' => $formData['remarks_srcincomever'],
			'remarks_empver' => $formData['remarks_empver'],
			'remarks_busver' => $formData['remarks_busver'],
			'remarks_trdchk' => $formData['remarks_trdchk'],
			'remarks_backgrd' => $formData['remarks_backgrd'],
			'model_cv_srcincomever' => $formData['model_cv_srcincomever'],
			'model_cv_empver' => $formData['model_cv_empver'],
			'model_cv_busver' => $formData['model_cv_busver'],
			'model_cv_trdchk' => $formData['model_cv_trdchk'],
			'model_cv_backgrd' => $formData['model_cv_backgrd'],

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
				$cvdetails->update($data3,$where);						
				}	
			
			$data4 = array(
			// Get Form data for CI Tabs
			'capno' => $capno,

			'srcincomever_ci' => $formData['srcincomever_ci'],
			'empver_ci' => $formData['empver_ci'],
			'busver_ci' => $formData['busver_ci'],
			'trdchk_ci' => $formData['trdchk_ci'],
			'backgrd_ci' => $formData['backgrd_ci'],

			'remarks_srcincomever_ci' => $formData['remarks_srcincomever_ci'],
			'remarks_empver_ci' => $formData['remarks_empver_ci'],
			'remarks_busver_ci' => $formData['remarks_busver_ci'],
			'remarks_trdchk_ci' => $formData['remarks_trdchk_ci'],
			'remarks_backgrd_ci' => $formData['remarks_backgrd_ci'],
			
			'model_ci_srcincomever' => $formData['model_ci_srcincomever'],
			'model_ci_empver' => $formData['model_ci_empver'],
			'model_ci_busver' => $formData['model_ci_busver'],
			'model_ci_trdchk' => $formData['model_ci_trdchk'],
			'model_ci_backgrd' => $formData['model_ci_backgrd'],
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
				$cidetails->update($data4,$where);						
				}	
			
				$this->_redirect('/index/accountedit/cap/'.$capno);
				


		

			
			} // End of Principal Relation
			
			elseif ($form->getValue('relation') == 'Spouse'){
			// Form Data Gatheiring if the Account is a Spouse
			$data = array(
			'borrower_lname' => strtoupper($form->getValue('borrower_lname')),
			'borrower_fname' => strtoupper($form->getValue('borrower_fname')),
			'borrower_mname' => strtoupper($form->getValue('borrower_mname')),
			
			'pres_address_no' => strtoupper($form->getValue('borrower_pres_address_no')),
			'pres_address_st' => strtoupper($form->getValue('borrower_pres_address_st')),
			'pres_address_brgy' => strtoupper($form->getValue('borrower_pres_address_brgy')),
			'pres_address_city' => $form->getValue('borrower_pres_address_city'),
			'pres_address_province' => strtoupper($formData['borrower_pres_address_province']),
			'pres_zipcode' => $form->getValue('pres_zipcode'),
			
			'prev_address_no' => strtoupper($form->getValue('borrower_prev_address_no')),
			'prev_address_st' => strtoupper($form->getValue('borrower_prev_address_st')),
			'prev_address_brgy' => strtoupper($form->getValue('borrower_prev_address_brgy')),
			'prev_address_city' => $form->getValue('borrower_prev_address_city'),
			'prev_address_province' => strtoupper($formData['borrower_prev_address_province']),
			'prev_zipcode' => $form->getValue('prev_zipcode'),
			
			'landline' => $form->getValue('landline'),			
			'mobile' => $form->getValue('mobile'),
			'email' => $form->getValue('email'),
			'tin_id' => $form->getValue('tin_id'),
			
			'residence_yrs' => $form->getValue('lenghtstay'),
			'residence_type' => $form->getValue('residencetype'),
			'neighborhoodtype'=>$form->getValue('neighborhoodtype'),
			
			'birthdate' => $form->getValue('birthdate'),
			'birthplace' =>$form->getValue('birthplace'),
			'maiden_name' =>$form->getValue('maiden_name'),
			'citizenship' =>$form->getValue('citizenship'),
			'tel_avail' => $landline,
			'age'=>trim(round(dateDiff("/", date("m/d/Y", time()), date("m/d/Y",strtotime($form->getValue('birthdate'))))/365, 0),'.'),
			'relation' =>'Spouse',
			);	
			} // End of Spouse
			elseif ($form->getValue('relation') == 'Coborrower'){
			
			$data = array(
			'borrower_lname' => strtoupper($form->getValue('borrower_lname')),
			'borrower_fname' => strtoupper($form->getValue('borrower_fname')),
			'borrower_mname' => strtoupper($form->getValue('borrower_mname')),
			
			'pres_address_no' => strtoupper($form->getValue('borrower_pres_address_no')),
			'pres_address_st' => strtoupper($form->getValue('borrower_pres_address_st')),
			'pres_address_brgy' => strtoupper($form->getValue('borrower_pres_address_brgy')),
			'pres_address_city' => $form->getValue('borrower_pres_address_city'),
			'pres_address_province' => strtoupper($formData['borrower_pres_address_province']),
			'pres_zipcode' => $form->getValue('pres_zipcode'),
			
			'prev_address_no' => strtoupper($form->getValue('borrower_prev_address_no')),
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
			'civilstatus' => $form->getValue('civilstatus'),
			
			'residence_yrs' => $form->getValue('lenghtstay'),
			'residence_type' => $form->getValue('residencetype'),
			'neighborhoodtype'=>$form->getValue('neighborhoodtype'),
			
			'dependentno' => $form->getValue('dependentno'),
			'birthdate' => $form->getValue('birthdate'),
			'birthplace' =>$form->getValue('birthplace'),
			'maiden_name' =>$form->getValue('maiden_name'),
			'citizenship' =>$form->getValue('citizenship'),
			'tel_avail' => $landline,
			'age'=>trim(round(dateDiff("/", date("m/d/Y", time()), date("m/d/Y",strtotime($form->getValue('birthdate'))))/365, 0),'.'),
			'relation' =>'Coborrower',
			);	
				
			}// End of Coborrower
		

		


		} //End of IsValid
	} // End of Request
	
	
	
}

public function addspouseAction(){
	
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
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
			
			'pres_address_no' => strtoupper($form->getValue('borrower_pres_address_no')),
			'pres_address_st' => strtoupper($form->getValue('borrower_pres_address_st')),
			'pres_address_brgy' => strtoupper($form->getValue('borrower_pres_address_brgy')),
			'pres_address_city' => $form->getValue('borrower_pres_address_city'),
			'pres_address_province' => strtoupper($formData['borrower_pres_address_province']),
			'pres_zipcode' => $form->getValue('pres_zipcode'),
			
			'prev_address_no' => strtoupper($form->getValue('borrower_prev_address_no')),
			'prev_address_st' => strtoupper($form->getValue('borrower_prev_address_st')),
			'prev_address_brgy' => strtoupper($form->getValue('borrower_prev_address_brgy')),
			'prev_address_city' => $form->getValue('borrower_prev_address_city'),
			'prev_address_province' => strtoupper($formData['borrower_prev_address_province']),
			'prev_zipcode' => $form->getValue('prev_zipcode'),
			
			
			'landline' => $form->getValue('landline'),			
			'mobile' => $form->getValue('mobile'),
			'email' => $form->getValue('email'),
			'tin_id' => $form->getValue('tin_id'),
			
			'residence_yrs' => $form->getValue('lenghtstay'),
			'residence_type' => $form->getValue('residencetype'),
			'neighborhoodtype'=>$form->getValue('neighborhoodtype'),
			
			'birthdate' => $form->getValue('birthdate'),
			'birthplace' =>$form->getValue('birthplace'),
			'maiden_name' =>$form->getValue('maiden_name'),
			'citizenship' =>$form->getValue('citizenship'),
			'tel_avail' => $landline,
			'age'=>trim(round(dateDiff("/", date("m/d/Y", time()), $form->getValue('birthdate'))/365, 0),'.'),
			'relation' =>'Spouse',
			);	
			    $spouse = new Model_BorrowerAccount();
				$spouse->insert($data); 
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
	
		
		
	if ($this->getRequest()->isPost()) {
    $formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)) {
	    	
			$capno = $this->_getParam('cap');
			$newcapno = capnospcoGen($capno, 'Coborrower');
			
			if ($form->getValue('landline')){
			$landline = 1;}else {$landline = 0;}
			
			$data = array(
			'capno' => $newcapno,
			'borrower_lname' => strtoupper($form->getValue('borrower_lname')),
			'borrower_fname' => strtoupper($form->getValue('borrower_fname')),
			'borrower_mname' => strtoupper($form->getValue('borrower_mname')),
			
			'pres_address_no' => strtoupper($form->getValue('borrower_pres_address_no')),
			'pres_address_st' => strtoupper($form->getValue('borrower_pres_address_st')),
			'pres_address_brgy' => strtoupper($form->getValue('borrower_pres_address_brgy')),
			'pres_address_city' => $form->getValue('borrower_pres_address_city'),
			'pres_address_province' => strtoupper($formData['borrower_pres_address_province']),
			'pres_zipcode' => $form->getValue('pres_zipcode'),
			
			'prev_address_no' => strtoupper($form->getValue('borrower_prev_address_no')),
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
			'civilstatus' => $form->getValue('civilstatus'),
			
			'residence_yrs' => $form->getValue('lenghtstay'),
			'residence_type' => $form->getValue('residencetype'),
			'neighborhoodtype'=>$form->getValue('neighborhoodtype'),
			
			'dependentno' => $form->getValue('dependentno'),
			'birthdate' => $form->getValue('birthdate'),
			'birthplace' =>$form->getValue('birthplace'),
			'maiden_name' =>$form->getValue('maiden_name'),
			'citizenship' =>$form->getValue('citizenship'),
			'tel_avail' => $landline,
			'age'=>trim(round(dateDiff("/", date("m/d/Y", time()), $form->getValue('birthdate'))/365, 0),'.'),
			'relation' =>'Coborrower',
			);	
			    $coborrower = new Model_BorrowerAccount();
				$coborrower->insert($data); 
				$this->_redirect('/index/accountedit/cap/'.$newcapno);
		} //End of IsValid
	} // End of Request
} // End of Add Coborrower Action




public function searchAction(){
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
        $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
	
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
		$borrower_lname = strtoupper($form->getValue('search_borrower_lname'));
	
		$table = new Model_BorrowerAccount;
		//for marketing
		//$user = Zend_Auth::getInstance()->getIdentity();
		$select = $table->select();
			$select->where('capno like ?',$capno.'%')
			->where ('borrower_lname like ?',$borrower_lname.'%')
			->where('application_date >= ?', $startdate)->where('application_date <= ?', $enddate)
			->where('relation like ?', 'Principal') // Spouse Add
			->order('capno');
		$rows = $table->fetchAll($select);
				
		
		$this->view->rows = $rows;

	
	/*
	if ($user->roles == 1){
		$select->where('cap_no like ?',$cap_no.'%')
		->where ('borrower_lname like ?',$borrower_lname.'%')
		->where ('created_by like ?',$user->username.'%')
		->order('cap_no')		
		->where('application_date >= ?', $startdate)->where('application_date <= ?', $enddate);
		}
	else {
	$select->where('cap_no like ?',$cap_no.'%')
		->where ('borrower_lname like ?',$borrower_lname.'%')
		->order('cap_no')
		->where('application_date >= ?', $startdate)->where('application_date <= ?', $enddate);
	}
	*/

		} //End of IsValid
	} // End of Request
} // End of searchAction


public function employmentAction(){
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
        $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
	

	
	$capno = $this->_getParam('cap');	
	$this->view->capno = $capno;

	
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
	
	$this->view->capno = $capno;

	
} // End of Employment Action

public function employmenteditAction(){
	
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
		$this->view->capno = $capno;
	
		
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
				//'relation' =>$relation,
				'employer' => $form->getValue('employer'),
				'date_resigned' => $dateresigned,
				'emp_name' => $form->getValue('emp_name'),
				'emp_industry' => $form->getValue('emp_industry'),
				'emp_address' => $form->getValue('emp_address'),
				'emp_telno' => $form->getValue('emp_telno'),
				'emp_pos' => $form->getValue('emp_pos'),
				'emp_status' => $form->getValue('emp_status'),
				'emp_yrs' => $form->getValue('emp_yrs'),
				'emp_income' => moneyconvert($form->getValue('emp_income')),
				'emp_gsiss' => $form->getValue('emp_gsiss'),
				);
				$table->insert($data);
				$this->_redirect('/index/employmentedit/cap/'.$capno);
				
			 }
			 elseif ($formData['submit'] == 'Add Business'){
			 	
				$table = new Model_BorrowerBusiness();
				$data = array(
				'capno' => $capno,
				//'relation' => $relation,
				'bus_name'=>$form->getValue('bus_name'),
				'bus_address'=>$form->getValue('bus_address'),
				'bus_telno'=>$form->getValue('bus_telno'),
				'bus_pos'=>$form->getValue('bus_pos'),
				'bus_srcincome'=>$form->getValue('bus_srcincome'),
				'bus_yrs'=>$form->getValue('bus_yrs'),
				'bus_income'=>moneyconvert($form->getValue('bus_income')),
				'bus_nat'=>$form->getValue('bus_nat'),
				'bus_dti'=>$form->getValue('bus_dti'),			
				);
				$table->insert($data);
				$this->_redirect('/index/employmentedit/cap/'.$capno);
			 }
			
					} //End of IsValid
		} // End of Request
	
	
	
}

public function delemploymentAction(){
    $this->_helper->viewRenderer->setNoRender(true);
	$id = $this->_getParam('id');
    $capno = $this->_getParam('cap');
    $employment = new Model_BorrowerEmployment();
    $where = $employment->getAdapter()->quoteInto('id = ?', $id);
    $employment->delete($where);
    $this->_redirect('/index/employmentedit/cap/'.$capno);	
}

public function delbusinessAction(){
    $this->_helper->viewRenderer->setNoRender(true);
	$id = $this->_getParam('id');
    $capno = $this->_getParam('cap');
    $business = new Model_BorrowerBusiness();
    $where = $business->getAdapter()->quoteInto('id = ?', $id);
    $business->delete($where);
    $this->_redirect('/index/employmentedit/cap/'.$capno);	
}

public function obligationAction(){
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
        $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
	
	$capno = $this->_getParam('cap');	
	$this->view->capno = $capno;
	
	//Fetch Bank Details 
	$bank = new Model_BorrowerObBank();
	$select = $bank->select();
	$select->where('capno like ?',$capno);
	$bankdetail = $bank->fetchAll($select);
	$this->view->bankdetail = $bankdetail;
	
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
	
	//Fetch Trade and Business References
	$trdbusref = new Model_BorrowerObTrdBusRef();
	$select = $trdbusref->select();
	$select->where('capno like ?',$capno);
	$trdbusrefdetail = $trdbusref->fetchAll($select);
	$this->view->trdbusrefdetail = $trdbusrefdetail;
	
	
}// End of Obligation Action

public function obligationeditAction(){
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
        $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
	
		$this->_helper->viewRenderer('obligation-edit');
	
		$capno = $this->_getParam('cap');	
		$this->view->capno = $capno;
	
		//Fetch Bank Details 
		$bank = new Model_BorrowerObBank();
		$select = $bank->select();
		$select->where('capno like ?',$capno);
		$bankdetail = $bank->fetchAll($select);
		$this->view->bankdetail = $bankdetail;
		
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
		
		//Fetch Trade and Business References
		$trdbusref = new Model_BorrowerObTrdBusRef();
		$select = $trdbusref->select();
		$select->where('capno like ?',$capno);
		$trdbusrefdetail = $trdbusref->fetchAll($select);
		$this->view->trdbusrefdetail = $trdbusrefdetail;
	
		$form = new Form_Obligation();
		$this->view->form = $form;
	
		if ($this->getRequest()->isPost()) {
	    $formData = $this->getRequest()->getPost();
		    if ($form->isValid($formData)) {  
			
			$capno = $this->_getParam('cap');	
			$relation = $this->_getParam('relation');
			
			 if ($formData['submit'] == 'Add Bank Details'){
		
				$table = new Model_BorrowerObBank(); 	
		
				$data = array(
				'capno' => $capno,
				'relation' => $relation,
				'bank' => $form->getValue('bank'),
				'branch' => strtoupper($form->getValue('branch')),
				'account_type' => $form->getValue('account_type'),
				'account_no' => $form->getValue('account_no'),
				'adb' => moneyconvert($form->getValue('adb')),
				'date_opened' => $form->getValue('date_opened')			
				);
				$table->insert($data);
				$this->_redirect('/index/obligation/cap/'.$capno);		
			 }
			 elseif ($formData['submit'] == 'Add Credit Card Details'){
			 	
				$table = new Model_BorrowerObCreditCard();
				
				$data = array(
				'capno' => $capno,
				'relation' => $relation,
				'company' => $form->getValue('creditcomp'),
				'limit' => moneyconvert($form->getValue('creditlimit')),
				'expiry_date' => $form->getValue('expiry_date')			
				);
				$table->insert($data);
				$this->_redirect('/index/obligation/cap/'.$capno);	
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
				);	
				$table->insert($data);
				$this->_redirect('/index/obligation/cap/'.$capno);
			}
			elseif ($formData['submit'] == 'Add Trade and Business'){
				
				$table = new Model_BorrowerObTrdBusRef();
				
				$data = array(
				'capno' => $capno,
				'relation' => $relation,
				'name' => $form->getValue('name'),
				'contact_person' =>$form->getValue('contactperson'),
				'contact_no' => $form->getValue('contactno'),
				'nature_transaction' =>$form->getValue('nat_transact'),
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
    $this->_redirect('/index/obligationedit/cap/'.$capno);	
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
					'branch_referror' => $form->getValue('branch_referror'),
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
					
					// Update Borrower Table
					$borrower = new Model_BorrowerAccount();
					$data = array(
					'account_status'=>$action,	
					);
					$where = "capno like '$capno'";
					$borrower->update($data, $where);
					
					//Insert Account Status
				
					$accountstatus = new Model_AccountStatus();
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
				
					$accountstatus = new Model_AccountStatus();
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
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('inbox-submitted'); 
	}
	public function inboxpendingAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('inbox-pending'); 
	}
	
	public function inboxrtsAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('inbox-rts'); 
	}
	public function inboxWithDecisionAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('inbox-with-decision'); 
	}
	public function inboxRecommendedAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('inbox-recommended'); 
	}
	public function inboxDecidedAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('inbox-decided'); 
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
	
	public function bookingAction() {
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
	}


} // End of Index Controller



 function capnoGen($loantype,$indivcorp)
    {
   	$retail = 1;
    $crescore = new Model_BorrowerAccount();
    $select= $crescore->select();
    $date = date(mdy);
   	$select->where('relation like ?','Principal');
	$select->order('capno DESC');
   
	 //11108120901600
	$rows = $crescore->fetchAll($select);
    $curr = substr($rows[0]->capno,9,-2);
    $lastdate = substr($rows[0]->capno,3,-5);
    $currdate = $date;
    $nextseq = $curr+1;
    $nextseq = str_pad($nextseq,3,'0', STR_PAD_LEFT);
    if ($lastdate ==   $currdate){
    $string = $retail.$loantype.$indivcorp.$date.$nextseq.'00';} //change 0 when recon is implemented by counting how many changes are made 
	 //substr($string,0,12); 10625090000
   else {
   $string = $retail.$loantype.$indivcorp.$date.'00100';   
   }  
   return $string;
   }
	
  
 function capnospcoGen($capno,$relation)
    {
    //capno Generation for Spouse
    //11008120901600
	
	
	$recon = capnorecon($capno);
	$capnosep = capnosep($capno);
	
    if ($relation == 'Spouse'){
    	
		$spouses = new Model_BorrowerAccount();
		$sql = $spouses->select()
	    ->where('capno LIKE ?',$capnosep.'_'.$recon)
		->where('relation LIKE ?','Spouse')
		->order('capno');;
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
		->where('relation LIKE ?','Coborrower')
		->order('capno');
		$result = $coborrower->fetchAll($sql,"capno ASC");

		if(count($result) == 0){ // No Coborrower Found
		$nextseq = 3;
		}
		else {
			/**
			Capno Generation for Coborrower Start with 3 until 9
			
			-if a coborrower with a 3 as his coborrower id the new 
			capno should be 4
			
			-else if 4 or 5 already exist while 3 is deleted the newcapno 
			borrower id must start again to 3.
			
			-Old values should be filled up 1st before using a new borrower id
			
			*/
			$x = 3;
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
			$x++;			
			}
		}

		
	}// End of Coborrower Capno Generation

   $string = $capnosep.$nextseq.$recon;  
	
   return $string;
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

function capnocurr($capno){
	//use in determining the spouse or coborrower!
	$capnocurr = substr($capno,12,1);
	
	return $capnocurr;
	
}

function capnorecon($capno){
	//use in determining the spouse or coborrower!
	$capnorecon = substr($capno,-1);
	
	return $capnorecon;
	
}

function totalincome($capno){
	
	$bus = new Model_BorrowerBusiness();
	$select = $bus->select();
	$select->where('capno like ?',$capno);
	$busdetail = $bus->fetchAll($select);
	
	foreach($busdetail as $detail){
    $sum += $detail->bus_income;
    }   
	
	
	$emp = new Model_BorrowerEmployment();
	$select = $emp->select();
	$select->where('capno like ?',$capno);
	$empdetail = $emp->fetchAll($select);
	
	foreach($empdetail as $detail){
    $sum2 += $detail->emp_income;
    }   
	return $sum+$sum2;
}

function totalcombinedincome($capno){
	
	$capnosep = capnosep($capno);
	$recon = capnorecon($capno);
	
	$bus = new Model_BorrowerBusiness();
	$select = $bus->select();
	$select->where('capno like ?',$capnosep.'_'.$recon);
	$busdetail = $bus->fetchAll($select);
	
	foreach($busdetail as $detail){
    $sum += $detail->bus_income;
    }   
	
	
	$emp = new Model_BorrowerEmployment();
	$select = $emp->select();
	$select->where('capno like ?',$capnosep.'_'.$recon);
	$empdetail = $emp->fetchAll($select);
	
	foreach($empdetail as $detail){
    $sum2 += $detail->emp_income;
    }   
	return $sum+$sum2;
	
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


