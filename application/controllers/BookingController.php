<?php

class BookingController extends Zend_Controller_Action
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
	
	   public function resetdataAction()
    {
    $this->_helper->viewRenderer->setNoRender(true);
	$capno = array();
	$capno[] = '1100106281000200';
	$capno[] = '1100103231000901';

	$borrower = new Model_BorrowerAccount();
	foreach($capno as $x){
		$data = array(
		'account_status'=>'MA - PreB',
		);
		$where = "capno like '$x'";
		$borrower->update($data,$where);
	}
	echo "Success";


    }
	
	public function bookingviewAction(){
	
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');	
			$this->_helper->viewRenderer('box-booking-view');
			$this->_helper->RolePermissionHelper('index_labooksubmit');
	
			$capno = $this->_getParam('cap');	
			$borrower = new Model_BorrowerAccount();
			$booking = new Model_Booking_BookingAuto();
			$detail = $borrower->fetchRowModel($capno);
			$detailBooking = $booking->fetchRowModel($capno);			
			
			$this->view->borrowerName = $detail->borrower_fname.' '.$detail->borrower_mname.' '.$detail->borrower_lname;
			$this->view->capno = $capno;
			$this->view->detailBooking = $detailBooking;
			$this->view->status = $detail->account_status;
	}
	
	public function labooksubmitAction(){
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
			$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
			$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');

	
			$this->_helper->viewRenderer('box-booking');
		
			$this->_helper->RolePermissionHelper('index_labooksubmit');
			
			$capno = $this->_getParam('cap');	
			$borrower = new Model_BorrowerAccount();
			$booking = new Model_Booking_BookingAuto();
			$detail = $borrower->fetchRowModel($capno);
			$detailBooking = $booking->fetchRowModel($capno);
			$form = new Form_Booking_PopupBox();
			
			if(login_user_role() == 'LA'){
				if(!$detailBooking){
				$form->bookdate->setValue(date("m/d/Y"));
				$form->pn_amount->setValue(number_format(ceil($detail->monthly_amortization) * $detail->loanterm,2));
				$form->amount_financed->setValue(number_format($detail->amountloan,2));
				$form->loanterm->setValue($detail->loanterm);
				}else{
				$form->bookdate->setValue(date('m-d-Y',strtotime($detailBooking->bookdate)));
				$form->pn_amount->setValue(number_format($detailBooking->pn_amount,2));		
				$form->amount_financed->setValue(number_format($detailBooking->amount_financed,2));		
				$form->loanterm->setValue($detailBooking->loanterm);
				$form->pn_number->setValue($detailBooking->pn_number);
				$form->submit_remarks->setValue($detailBooking->remarks_la);
				}

				
			}
			else if(login_user_role() == 'LO'){
				$form->bookdate->setValue(date('m-d-Y',strtotime($detailBooking->bookdate)));
				$form->pn_amount->setValue(number_format($detailBooking->pn_amount,2));		
				$form->amount_financed->setValue(number_format($detailBooking->amount_financed,2));		
				$form->loanterm->setValue($detailBooking->loanterm);
				$form->pn_number->setValue($detailBooking->pn_number);
				//Read Only
				$form->bookdate->setAttrib('readonly','readonly');
				$form->pn_amount->setAttrib('readonly','readonly');
				$form->amount_financed->setAttrib('readonly','readonly');
				$form->loanterm->setAttrib('readonly','readonly');
				$form->pn_number->setAttrib('readonly','readonly');


			}
			$this->view->remarks_la = $detailBooking->remarks_la;
			$this->view->borrowerName = $detail->borrower_fname.' '.$detail->borrower_mname.' '.$detail->borrower_lname;
			$this->view->loanterm = $detail->loanterm;
			$this->view->form = $form;
			$this->view->capno = $capno;
			
			//$this->view->message = "Document Checked Done, Submit this account to the next LA?";
			if ($this->getRequest()->isPost()) {
				$formData = $this->getRequest()->getPost();
					if ($form->isValid($formData)) { 
					$capno = $this->_getParam('cap');	
					$status = $this->_helper->updateAcntStatus->booking($borrower->getAcntStatus($capno));
					//Update in Borrower Account 
					
					if(login_user_role() == 'LO'){
					$data = array(
					'account_status'=>$status,
					'date_booked'=>date("r"),
					'submitted_lo'=>login_user(),
					);
					$borrower = new Model_BorrowerAccount();
					$where = "capno like '$capno'";
					$borrower->update($data, $where);	
					
					/****Booking Auto Table****/
					$booking = new Model_Booking_BookingAuto();
					$data = array(
					'remarks_lo'=> $form->getValue('submit_remarks'),					
					'submitted_lo'=>login_user(),
					'submitted_lo_date'=>date("r"),					
					);			
					$where = "capno like '$capno'";
					$booking->update($data,$where);	
						
					/****End of Booking Auto Table****/
					
					}
					else if(login_user_role() == 'LA'){
					$data = array(
					'account_status'=>$status,
					'submitted_lalo_date'=>date("r"),
					//'submitted_lo'=>'msisanan'
					'submitted_la'=>login_user(),
					);
					$borrower = new Model_BorrowerAccount();
					$where = "capno like '$capno'";
					$borrower->update($data, $where);	
					
					/****Booking Auto Table****/
					$booking = new Model_Booking_BookingAuto();
					$data = array(
					'capno'=>$capno,
					'pn_amount'=>str_replace(',','',$form->getValue('pn_amount')),
					'amount_financed'=>str_replace(',','',$form->getValue('amount_financed')),
					'pn_number'=>$form->getValue('pn_number'),
					'loanterm'=>$form->getValue('loanterm'),
					'bookdate'=>$form->getValue('bookdate'),
					'remarks_la'=> $form->getValue('submit_remarks'),					
					'submitted_la'=>login_user(),
					'submitted_la_date'=>date("r"),					
					);			
						$sql2 = $booking->select()->where('capno LIKE ?',$capno);
		
						if($booking->fetchAll($sql2)->count() == 0){
							$booking->insert($data);	
						}
						else{
							$where = "capno like '$capno'";
							$booking->update($data,$where);	
						}
					/****End of Booking Auto Table****/
					}
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
					
					$this->_helper->Accounts($capno,$status);
		
					$this->view->isSubmit = "OK";	
					}
			}
}


public function reportAction(){
		Zend_Layout::getMvcInstance()->assign('usedojo', 'true');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/table-style.css');

			$this->_helper->viewRenderer('ld-report');

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
