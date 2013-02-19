<?php

class SmsController extends Zend_Controller_Action
{

    public function preDispatch()
    {
    	/*
        if (Zend_Auth::getInstance()->hasIdentity()) {
          	}
		else {
            $this->_helper->redirector('login','auth');
            }
	$this->_helper->RoleAccess();
	*/

	}
    public function init()
    {
        /* Initialize action controller here */
		
    }

    public function indexAction()
    {
	   $this->_redirect('/sms/view');	
    }
	
	public function viewAction(){
		
		Zend_Layout::getMvcInstance()->assign('usedojo', 'true');

		$smsSending = new Model_Sms_SmsSending();
		$smsHistory = new Model_Sms_SmsHistory();
		
		$form = new Form_Search();
		foreach($form->getElements() as $element) {
		$element->removeDecorator('DtDdWrapper');
		$element->removeDecorator('Label');
		}
		$this->view->form = $form;
		$date = $this->_getParam('date');
		
		if(!$date){
			$date = date("Y-m-d");
		}
		$startdate = $date." 01:00:00";	// of the morning 
		$enddate = $date." 24:00:00";	 // of the evening
		
		$select = $smsSending->select();
		$select->order("id ASC");
		$select->where("time_last_sent between '$startdate' and '$enddate'");
		$currDetail = $smsSending->fetchAll($select);
		$this->view->detail = $currDetail;
		
		$select = $smsHistory->select();
		$select->order("id ASC");
		$select->where("time_1st_sent between '$startdate' and '$enddate'");
		$currDetail2 = $smsHistory->fetchAll($select);
		$this->view->historyDetail = $currDetail2;
		
		if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
	    	if ($form->isValid($formData)) {   
			echo $formData['startdate'];
			echo $formData['enddate'];
			
			$startdate = $formData['startdate']." 01:00:00";	// of the morning 
			$enddate =  $formData['enddate']." 24:00:00";	 // of the evening
			
			//Current
			$select = $smsSending->select();
			$select->order("id ASC");
			$select->where("time_last_sent between '$startdate' and '$enddate'");
			$currDetail = $smsSending->fetchAll($select);
			$this->view->detail = $currDetail;
			//History
			$select = $smsHistory->select();
			$select->order("id ASC");
			$select->where("time_1st_sent between '$startdate' and '$enddate'");
			$currDetail2 = $smsHistory->fetchAll($select);
			$this->view->historyDetail = $currDetail2;
			}
		}
		
	}
	
	public function currentAction(){
		$smsSending = new Model_Sms_SmsSending();
		$smsHistory = new Model_Sms_SmsHistory();
		
		
		$select = $smsSending->select();
		$select->order("id ASC");
		$currDetail = $smsSending->fetchAll($select);
		$this->view->detail = $currDetail;
		
	}
	
	public function historyAction(){
		$smsHistory = new Model_Sms_SmsHistory();
		
		$select = $smsHistory->select();
		$select->order("id ASC");
		$currDetail = $smsHistory->fetchAll($select);
		$this->view->detail = $currDetail;
	}
	
	
	
}