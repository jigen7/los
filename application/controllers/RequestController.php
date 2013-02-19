<?php

class RequestController extends Zend_Controller_Action
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

	public function indexAction()
	{
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/table-style.css');
	
	$this->_redirect('/request/request');

	
	}
	
	public function requestAction()
	{
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/table-style.css');
	$this->_helper->viewRenderer('request-list'); 
	$form = new Form_Request_FormFields();
	$this->view->form = $form;
	
	$model = new Model_Request_Request();
	$select= $model->select()->order("id DESC");
	$request = $model->fetchAll($select);
	$this->view->request = $request;
	
	if ($this->getRequest()->isPost()) {
    $formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)) {
		$data = array(
		'by'=>$this->view->loggedInUser(),
		'request_name'=>$form->getValue('request_name'),
		'remarks'=>$form->getValue('request_remarks'),
		'date_request'=>date("r"),
		'type'=>$formData['request_type'],
		);
		$model->insert($data);
		$this->_redirect('/request/request');
		}
	}
	
	}
	
	public function replyAction(){
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/table-style.css');
	$this->_helper->viewRenderer('request-reply'); 
	$form = new Form_Request_FormFields();
	$this->view->form = $form;
	$id = $this->_getParam('id');

	$model = new Model_Request_Request();
	$select= $model->select()->where("id = ?",$id);
	$requestDetail = $model->fetchRow($select);
	
	$this->view->requestDetail = $requestDetail;
		
	if ($this->getRequest()->isPost()) {
    $formData = $this->getRequest()->getPost();
	    if ($form->isValid($formData)) {
	    	
			$data = array(
			'date_finished'=>date("r"),
			'done_by'=>$this->view->loggedInUser(),
			'status'=>$formData['status'],
			'finished_remarks'=>$formData['finished_remarks'],
			);			
			
			
		$where = "id = ".$id;	
		$model->update($data,$where);
		
		$this->_redirect('/request/request');
		}
	}
	
	}
	
	
	public function minutesAction()
	{
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/table-style.css');
	
	
	
	}
	
	
	
	
	

}
