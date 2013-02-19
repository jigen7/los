<?php

class ReportController extends Zend_Controller_Action
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
	
	public function reportAction(){
		$this->_helper->viewRenderer->setNoRender();

		
		switch(login_user_role()){
			
			case 'LA':
			$this->_redirect('/booking/report');	
			break;	
			case 'LO':
			$this->_redirect('/booking/report');		
			break;
			case 'CA':
			$this->_redirect('/report/accountspending');	
			break;
			case 'CO':
			$this->_redirect('/report/accountspending');	
			break;
			case 'MA':
			$this->_redirect('/report/mabooktrack');	
			break;
			case 'AO':
			$this->_redirect('/report/mabooktrack');	
			break;
			case 'ALMH':
			$this->_redirect('/report/mabooktrack');	
			break;
			default:
			$this->_redirect('/report/accountspending');
			break;
		}


		
	}

	public function mabooktrackAction(){
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery.tablesorter.min.js');
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tableheader.css');
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/menu.js');
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		
		$this->_helper->viewRenderer('report-booktrack-ma'); 
		$this->view->reportTitle = "Booking Tracking - Marketing Dept.";
		Zend_Layout::getMvcInstance()->assign('usedojo', 'true');
		$form = new Form_Report_FormFields();
		$this->view->form = $form;
		foreach($form->getElements() as $element) {
		$element->removeDecorator('DtDdWrapper');
		$element->removeDecorator('Label');
		}
		
		
	if ($this->getRequest()->isPost()) {
	    $formData = $this->getRequest()->getPost();
		    if ($form->isValid($formData)) {
			//Zend_Debug::dump($formData);
	
			$table = new Model_BorrowerAccount();
			$select = $table->select();
			$select->where('account_status like ?','Booked');
			$startdate = $form->getValue('startdate');
			
			$criteriaArr = array();
			
			if(!$startdate) { $startdate = '1999-12-30'; } 
			$enddate = $form->getValue('enddate');
			if(!$enddate) { $enddate = '2999-12-30'; } 
		
			if($startdate == $enddate){
				$enddate = $startdate." 24:00:00";
				$startdate = $startdate." 00:00:00";
			$select->where("date_booked between '$startdate'  and '$enddate'");
			}else {
			$startdate = $startdate." 01:00:00";	// of the morning 
			$enddate = $enddate." 24:00:00";	 // of the evening
			$select->where("date_booked between '$startdate' and '$enddate'");
			}
			
			$rowsTotal = $table->fetchAll($select)->count();
			
			if($formData['submitted_ao']){
			$select->where("submitted_ao like ?",$formData['submitted_ao']);	
			
			$criteriaArr[] = "AO: ".$this->view->viewMa($formData['submitted_ao']);
			}
			
			if($formData['dealer']){
			$select->where("dealer like ?",$formData['dealer']);	
			$criteriaArr[] = "Dealer: ".$formData['dealer'];
			}
			
			if($formData['loanterm']){
			$select->where("loanterm = ?",$formData['loanterm']);	
			$criteriaArr[] = "Term: ".$formData['loanterm'];
			}
			
			if($formData['veh_brand']){
			$select->where("veh_brand like ?",$formData['veh_brand']);
			$criteriaArr[] = "Brand: ".$formData['veh_brand'];
	
			}
			
			if($formData['approved_by']){
			$select->where("routetag ilike ?",'%-'.$formData['approved_by'].'%');	
			$criteriaArr[] = "Approved By: ".$formData['approved_by'];
			}
			
			if($formData['approval_level']){
			$select->where("routetag_orig ilike ?",'%-'.$formData['approval_level']);	
			$criteriaArr[] = "Approved Level: ".$formData['approval_level'];
			}
			
			if($formData['source_application']){
			$select->where("source_application like ?",$formData['source_application']);	
			$criteriaArr[] = "Source: ".$formData['source_application'];
			}
			
			$downTo = $formData['downpayment_to'];
			$downFrom = $formData['downpayment_from'];
		
			if(($downTo) || ($downFrom)){
				if(!$downTo){
					$downTo = 100;
				}
				if(!$downFrom){
					$downFrom = 0;
				}
			$select->where("downpayment_percent between '$downFrom' and '$downTo'");
			$criteriaArr[] = "DP: ".$downFrom."% - ".$downTo."%";
			}
			
			$select->order("date_booked DESC");
			
			
			$rowsDetail = $table->fetchAll($select);
			$totalaccounts = $rowsDetail->count();
			$this->view->totalaccounts = $totalaccounts;
			$this->view->rowsTotal = $rowsTotal;
			$this->view->criteriaArr = $criteriaArr;
			$criteriaPercent = $totalaccounts / $rowsTotal;
			$this->view->criteriaPercent = number_format($criteriaPercent * 100,2) ;

			if($formData['button'] == 'Submit'){
			$this->view->rowResult = $rowsDetail;

			/*
			$form->startdate->setValue($form->getValue('startdate'));
			$form->enddate->setValue($form->getValue('enddate'));	
			$form->source_application->setValue($formData['source_application']);
			$form->submitted_ao->setValue($formData['submitted_ao']);
			$form->loanterm->setValue($formData['loanterm']);
			$form->approved_by->setValue($formData['approved_by']);
			$form->approval_level->setValue($formData['approval_level']);
			*/
			// Set Default for form elements
			$form->dealer->setValue($formData['dealer']);
			
			$this->view->isSubmit = "OK";	
			} // End of Submit 
/*********************************End of Submit****************************/
/*********************************Start of Excel****************************/
					
			if($formData['button'] == 'Export as Excel'){
					$this->_helper->layout->disableLayout();
    				$this->_helper->viewRenderer->setNoRender();
			
						
						$data_array = $rowsDetail->toArray();
						$data_headers[] = "Date";
						$data_headers[] = "Name";
						$data_headers[] = "AO";
						$data_headers[] = "CSD Decision";
						$data_headers[] = "Approved By";
						$data_headers[] = "Brand";
						$data_headers[] = "Term";
						$data_headers[] = "Downpayment %";
						$data_headers[] = "Source Application";
						$data_headers[] = "Dealer";
						$data_headers[] = "Branch";
						
						$ArrData = array();
						foreach($data_array as $key => $val){
							$arrData[$key]['date'] = date('m-d-Y',strtotime($data_array[$key]['date_booked']));
							$arrData[$key]['name'] =  $data_array[$key]['borrower_lname'].', '.$data_array[$key]['borrower_fname'].' '.$data_array[$key]['borrower_mname'];
							$arrData[$key]['ao'] =  $this->view->viewMa($data_array[$key]['submitted_ao']);
							$arrData[$key]['csdRecommend'] = $this->view->csdRecommend($data_array[$key]['capno']);
							$arrData[$key]['approvedBy'] = $this->view->approvalLevel($data_array[$key]['routetag'],'level');
							$arrData[$key]['vhBrand'] = $data_array[$key]['veh_brand'];
							$arrData[$key]['loanterm'] = $data_array[$key]['loanterm'];
							$arrData[$key]['downpayment'] = number_format($data_array[$key]['downpayment_percent'],2);
							$arrData[$key]['source'] =  $data_array[$key]['source_application'];
							$arrData[$key]['dealer'] = $data_array[$key]['dealer'];
							$arrData[$key]['branch'] =  $data_array[$key]['branch'];

						}
						
						
						$html_string='<table>';
						$html_string.='<tr><td><b>'.implode('</b></td><td><b>',$data_headers).'</b></td></tr>';
						foreach($arrData as $k=>$v){
						$html_string.='<tr><td>'.implode('</td><td>',$v).'</td></tr>';
						}

						$html_string.='</table>';
						$xlsfile = "Marketing-BookingTracking".date("m-d-Y-hiA").".xls";
						header('Content-type: application/vnd.ms-excel');
						header("Content-Disposition: attachment; filename=$xlsfile");
						print  $html_string;
			}
/*********************************End of ExceL****************************/
/*********************************Start of PDF****************************/
			
			if($formData['button'] == 'Export as PDF'){
					$this->_helper->layout->disableLayout();
    				$this->_helper->viewRenderer->setNoRender();
					
					$data_array = $rowsDetail->toArray();
					$ArrData = array();
					foreach($data_array as $key => $val){
					$arrData[$key]['date'] = date('m-d-Y',strtotime($data_array[$key]['date_booked']));
					$arrData[$key]['name'] =  $data_array[$key]['borrower_lname'].', '.$data_array[$key]['borrower_fname'].' '.$data_array[$key]['borrower_mname'];
					$arrData[$key]['ao'] =  $data_array[$key]['submitted_ao'];
					$arrData[$key]['csdRecommend'] = $this->view->csdRecommend($data_array[$key]['capno']);
					$arrData[$key]['approvedBy'] = $this->view->approvalLevel($data_array[$key]['routetag'],'level');
					$arrData[$key]['vhBrand'] = $data_array[$key]['veh_brand'];
					$arrData[$key]['loanterm'] = $data_array[$key]['loanterm'];
					$arrData[$key]['downpayment'] = number_format($data_array[$key]['downpayment_percent'],2);
					$arrData[$key]['source'] =  $data_array[$key]['source_application'];
					$arrData[$key]['dealer'] = $data_array[$key]['dealer'];
					$arrData[$key]['branch'] =  $data_array[$key]['branch'];
					}
					
					$filename = "Marketing-BookingTracking-".date('m-d-Y-hiA');
					$pdf = new Zend_Pdf();
					$page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4_LANDSCAPE);
				    $pdf->pages[] = $page;
				    $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES), 8);		
					//Start of  Initial Header
					$page->drawText('LOS Booking Tracking - Marketing Dept.',10, 570);
					$page->drawText('Generated Date: '.date('m-d-Y h:i a'),650, 570);
					$page->drawText('Generated By: '.$this->view->viewMa(login_user()),650, 560);
					if(!$formData['startdate'] || !$formData['enddate']){
					}else{
					$page->drawText('Date Range : '.date('m-d-Y',strtotime($formData['startdate'])).' - '.date('m-d-Y',strtotime($formData['enddate'])),10, 560);
					}
					$page->drawText('Borrower: ',70, 520);
					$page->drawText('AO: ',230, 520);
					$page->drawText('CSD Decision: : ',270, 520);
					$page->drawText('Approval By: ',360, 520);
					$page->drawText('Brand: ',420, 520);
					$page->drawText('Term: ',480, 520);
					$page->drawText('DP: ',520, 520);	
					$page->drawText('Date Booked: ',555, 520);	
					$page->drawText('Source',610, 520);
					$page->drawText('Dealer',648, 520);
					$page->drawText('Branch',740, 520);
					// End of Initial Header
					/*****Content Start******/
					$x = 500;
			foreach($arrData as $arr){		
					
					if($x == 50){
					$page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4_LANDSCAPE);
					$pdf->pages[] = $page;
					$page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES), 8);		
					//Start of  Following Page Header
					$page->drawText('LOS Booking Tracking - Marketing Dept.',10, 570);
					$page->drawText('Generated Date: '.date('m-d-Y h:i a'),650, 570);
					$page->drawText('Generated By: '.$this->view->viewMa(login_user()),650, 560);
					if(!$formData['startdate'] || !$formData['enddate']){
					}else{
					$page->drawText('Date Range : '.date('m-d-Y',strtotime($formData['startdate'])).' - '.date('m-d-Y',strtotime($formData['enddate'])),10, 560);
					}
					$page->drawText('Borrower: ',70, 520);
					$page->drawText('AO: ',230, 520);
					$page->drawText('CSD Decision: : ',270, 520);
					$page->drawText('Approval By: ',360, 520);
					$page->drawText('Brand: ',420, 520);
					$page->drawText('Term: ',480, 520);
					$page->drawText('DP: ',520, 520);	
					$page->drawText('Date Booked: ',555, 520);	
					$page->drawText('Source',610, 520);
					$page->drawText('Dealer',648, 520);
					$page->drawText('Branch',740, 520);
					// End of Following Header
					$x=500;
					}
					// Data Row
					$x = $x-10;
					$page->drawText($arr['name'],30, $x);	
					$page->drawText($arr['ao'],225, $x);	
					$page->drawText($arr['csdRecommend'],270, $x);
					$page->drawText($arr['approvedBy'],360, $x);
					$page->drawText($arr['vhBrand'],420, $x);	
					$page->drawText($arr['loanterm'],480, $x);
					$page->drawText($arr['downpayment'].' %',520, $x);	
					$page->drawText($arr['date'],555, $x);	
					$page->drawText($arr['source'],610, $x);
					$page->drawText($arr['dealer'],648, $x);
					$page->drawText($arr['branch'],740, $x);
					
			}// End for Each
					
					
					
					header("Content-type: application/pdf");
					header("Content-Disposition: attachment; filename= $filename");
					echo $pdf->render();
			}
	
			}
		}
		
	} // End of MA BOOKING TRACKING

/*************************************************************************/
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
	$this->view->reportTitle = "Accounts Tracking - ";

	$form = new Form_Search();
	$this->view->form = $form;
		foreach($form->getElements() as $element) {
	$element->removeDecorator('DtDdWrapper');
	$element->removeDecorator('Label');
	}
	
	
	//$userAttendance = new Model_UsersTimeInfo();
	//echo 'fd'.$userAttendance->chkAbsent('jrpimentel');
	
	
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
				$select = $acct->select();
				$selectStatus = $statusTable->select();
				$selectStatus->where('"current_user" like ?', 'SUBCRECOM');
				$arrStatus = $statusTable->fetchAll($selectStatus);
					foreach($arrStatus as $x){
						$select->orwhere('account_status like ?',$x->status);
					}
				$condition = $select->getPart(Zend_Db_Select::WHERE);
				$select = $acct->select();
				$select->where(arrayString($condition));	
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
				$select = $acct->select();
				$selectStatus = $statusTable->select();
				$selectStatus->where('"current_user" like ?', 'BOD');
				$arrStatus = $statusTable->fetchAll($selectStatus);
					foreach($arrStatus as $x){
						$select->orwhere('account_status like ?',$x->status);
					}
				$condition = $select->getPart(Zend_Db_Select::WHERE);
				$select = $acct->select();
				$select->where(arrayString($condition));	
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
/***************************************************************************/
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
	$this->_helper->viewRenderer('accounts-process');
	$this->view->reportTitle = "Accounts Process - ";

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
	}// end of accounts process
/************************************************************************/

public function ldbooktrackAction(){
		Zend_Layout::getMvcInstance()->assign('usedojo', 'true');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/table-style.css');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');

		
		$this->_helper->viewRenderer('report-booktrack-ld');

	$form = new Form_Search();
	foreach($form->getElements() as $element) {
	$element->removeDecorator('DtDdWrapper');
	$element->removeDecorator('Label');
	}
	$this->view->form = $form;
	
	if ($this->getRequest()->isPost()) {
    $formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)) {     
		
	//	echo $formData['submit'];

		//$table = new Model_Booking_BookingAuto();
		$table = new Model_BorrowerAccount();
		$select = $table->select();
		$select->where('account_status like ?','Booked');
		$startdate = $form->getValue('startdate');
		if(!$startdate) { $startdate = '1999-12-30'; } 
		$enddate = $form->getValue('enddate');
		if(!$enddate) { $enddate = '2999-12-30'; } 
		
		if($startdate == $enddate){
				$enddate = $startdate." 24:00:00";
				$startdate = $startdate." 00:00:00";
			$select->where("date_booked between '$startdate'  and '$enddate'");
			}else {
			$startdate = $startdate." 01:00:00";	// of the morning 
			$enddate = $enddate." 24:00:00";	 // of the evening
			$select->where("date_booked between '$startdate' and '$enddate'");
			
			}
			
		

		$rowsDetail = $table->fetchAll($select);
		
		if($formData['submit'] == 'Search'){
		$this->view->rowResult = $rowsDetail;
		$this->view->totalaccounts = $rowsDetail->count();
		$form->startdate->setValue($form->getValue('startdate'));
		$form->enddate->setValue($form->getValue('enddate'));
		
		$this->view->isSubmit = "OK";
		}
		else if($formData['submit'] == 'Export as PDF'){
		
		$totalPn = number_format($formData['totalPn'],2);
		$totalLoan = number_format($formData['totalLoan'],2);
		
		
		$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
		$pdf = new Zend_Pdf();
		$page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4_LANDSCAPE);
	    $pdf->pages[] = $page;
	    $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES), 9);		
		//Start of Header
		$page->drawText('LOS Booking Report',10, 570);
		$page->drawText('Generated Date: '.date('m-d-Y'),650, 570);
		$page->drawText('Generated By: '.$this->view->viewMa(login_user()),650, 560);
		$page->drawText('Date Range : '.date('m-d-Y',strtotime($form->getValue('startdate'))).' - '.date('m-d-Y',strtotime($form->getValue('enddate'))),10, 560);
		$page->drawText('Total PN amount: P'.$totalPn,10, 550);
		$page->drawText('Total Amount Financed: P'.$totalLoan,10, 540);		


		$page->drawText('Capno: ',50, 520);
		$page->drawText('Borrower: ',150, 520);
		$page->drawText('Loan Type: ',280, 520);
		$page->drawText('Application Type: ',350, 520);
		$page->drawText('PN Number: ',430, 520);
		$page->drawText('PN Amount: ',490, 520);
		$page->drawText('Amount Financed: ',550, 520);	
		$page->drawText('Date Booked: ',640, 520);	
		// End of Header
		/*****Content Start******/
		$x = 500;
		foreach($rowsDetail as $detail){
			$bookTable = new Model_Booking_BookingAuto();
			$bookDetail = $bookTable->fetchRowModel($detail->capno);
		/***PDF Creation ***/
		if($x == 50){
		$page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4_LANDSCAPE);
	    $pdf->pages[] = $page;
	    $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES), 9);		
		//Start of Header
		$page->drawText('LOS Booking Report',10, 570);
		$page->drawText('Generated Date: '.date('m-d-Y'),650, 570);
		$page->drawText('Generated By: '.$this->view->viewMa(login_user()),650, 560);
		$page->drawText('Date Range : '.date('m-d-Y',strtotime($form->getValue('startdate'))).' - '.date('m-d-Y',strtotime($form->getValue('enddate'))),10, 560);
		$page->drawText('Total PN amount: P'.$totalPn,10, 550);
		$page->drawText('Total Amount Financed: P'.$totalLoan,10, 540);	
		
		
			
		$page->drawText('Capno: ',50, 520);
		$page->drawText('Borrower: ',130, 520);
		$page->drawText('Loan Type: ',280, 520);
		$page->drawText('Application Type: ',350, 520);
		$page->drawText('PN Number: ',430, 520);
		$page->drawText('PN Amount: ',490, 520);
		$page->drawText('Amount Financed: ',550, 520);	
		$page->drawText('Date Booked: ',640, 520);	
		// End of Header
		$x=500;
		}	
			$page->drawText($detail->capno,30, $x);
			$page->drawText($detail->borrower_lname.','.$detail->borrower_fname.' '.substr($detail->borrower_mname, 0,1).'.',130, $x);
			$page->drawText($this->view->viewTypeLoan($detail->loantype),290, $x);
			$page->drawText($detail->application_type,360, $x);
			$page->drawText($bookDetail->pn_number,430, $x);
			$page->drawText('P'.number_format($bookDetail->pn_amount,2),490, $x);
			$page->drawText('P'.number_format($bookDetail->amount_financed,2),550, $x);
			$page->drawText(date('M-d-Y',strtotime($detail->date_booked)),640, $x);
			//$page->drawText(date('M-d-Y',strtotime($bookDetail->bookdate)),690, $x);
			$x = $x-10;
		}
		

		
		/*****Content End*******/
		
		header("Content-type: application/pdf");
		echo $pdf->render();
		}
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
/************************************************************************/



/************************************************************************/
/************************************************************************/
/************************************************************************/
/************************************************************************/
/************************************************************************/

}
function login_user(){
	$user = Zend_Auth::getInstance()->getIdentity();
	return $user->username;
}

function login_user_role(){
	
	$user = Zend_Auth::getInstance()->getIdentity();
	return $user->role_type;
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

function arrayString($array){
	foreach($array as $x){
		$string = $string.$x;
		
	}
	return $string;
	
}
