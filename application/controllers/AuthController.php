<?php

class AuthController extends Zend_Controller_Action
{

    public function preDispatch()
    {
   		$this->_helper->switchSsl();
	}
	
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {   	
		$this->_helper->redirector('login','auth');
    }

    public function loginAction()
    {

		Zend_Layout::getMvcInstance()->assign('nodojo', 'true');

   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
	/*
      $flash = $this->_helper->getHelper('flashMessenger');
	if ($flash->hasMessages()) {
	$this->view->message = $flash->getMessages();
	} 
	*/  
      	$this->view->title = "Login";
       	$this->view->headTitle($this->view->title, 'PREPEND');
       	$users = new Model_Users();
	
       	$form = new Form_AuthLogin();
	//Remove DD DT tags
		foreach($form->getElements() as $element) {
			$element->removeDecorator('DtDdWrapper');
			$element->removeDecorator('Label'); 
		}
	//End of Remove DD Dt tags
        $this->view->form= $form;
	
	 	if($this->getRequest()->isPost()){
			$formData = $this->getRequest()->getPost();
         	if ($form->isValid($formData)) {    
				 $username= $this->getRequest()->getPost('username');
				 $password= $this->getRequest()->getPost('password');
				 $newpassword =$this->getRequest()->getPost('newpassword');
				 $confirmpassword =$this->getRequest()->getPost('confirmpassword');

				$authAdapter = new Zend_Auth_Adapter_DbTable($users->getAdapter(),
    														'users',
															'username',
															'password',
															'MD5(?) AND active = TRUE'
                    										);
				
				$authAdapter->setIdentity($username)->setCredential($password);           
       			$result= $authAdapter->authenticate();
				if ($result->isValid()) {        
					if(($newpassword)&&($confirmpassword)){ // if newpassword & confirmpassword has value make change password routine
			 			if ($newpassword != $confirmpassword) { //check if newpassword and confirmpassword is the same
							$this->view->errorMessage = "Password does not match";
	 					}
						else{ // password match change password
							if(strlen($newpassword) < 8 || strlen($newpassword) > 12){
								$this->view->errorMessage = "Minimum of 8 Characters & Maximum of 12 Characters";	
							}
							else if(strlen($confirmpassword) < 8 || strlen($confirmpassword) > 12){
								$this->view->errorMessage = "Minimum of 8 Characters & Maximum of 12 Characters";	
							}			
							else{
								$data= array ('password' => md5($newpassword));
								$where= $users->getAdapter()->quoteInto('username = ?', $username);
								$users->update($data,$where);
			
								$userTrail = new Model_UsersTrail();
								$userTrail->passwordchange($username);
								$this->view->errorMessage = "Your New Password is Accepted";			
							}
							$form->reset();
						}		
					}
					else { // else do login
						$data= array ('last_access' => date('Y-m-d H:i:s'));
						$where= $users->getAdapter()->quoteInto('username = ?', $username);
						$users->update($data,$where);
					
						$storage = new Zend_Auth_Storage_Session();
						$storage->write($authAdapter->getResultRowObject(null,'password'));
    
						$authNamespace = new Zend_Session_Namespace('Zend_Auth');
						$authNamespace->user = $username;
						$authNamespace->setExpirationSeconds(32000);
						
						//save to database information
						$logindb = new Model_UsersTimeInfo();
						$logindb->loginDB($username,login_user_role());
		
						if (login_user_role()== 'CSH' || login_user_role()== 'PRES' || login_user_role()=='CMGH' || login_user_role()=='ALMH') {
							$this->_redirect('index/indexroute/');
						}else if (login_user_role()== 'CSH' || login_user_role()== 'CRA' || login_user_role()=='CRO') {
							$this->_redirect('creditscore/home/');
						}				
						else {
							$this->_redirect('index/');
						}
					}
				}
				else {
					$table = new Model_UsersTrail();

					if($users->checkifexist($username) === false){			
						$string = "Invalid username";	
					}else{
						if($users->checkpassword($username,md5($password)) === false){	
						$string = "Invalid password";
						$table->chkloginfailed($username);
					}else{
						if($users->checkactive($username) === false){	
							$string = "Account Lock";
						}
			 		}
				}
				$table->failedlogin($username,$string);
				//$this->view->errorMessage = "Invalid username or password. Please try again.";
				$this->view->errorMessage = $string;
				$form->reset();
				}
			}
		}
	}
	 
	public function logoutAction() {
		//save to database information
		$logoutdb = new Model_UsersTimeInfo();
		$logoutdb->logoutDB(login_user(),login_user_role());
       	$storage = new Zend_Auth_Storage_Session();
        $storage->clear();
        $this->_redirect('auth/login');
    }
	
	public function dojoAction(){
		$form = new Form_Dojo();
		$this->view->form = $form;
	}

	public function changepasswordAction(){
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');		
		$form= new Form_AuthLogin();
		//Remove DD DT tags
		foreach($form->getElements() as $element) {
			$element->removeDecorator('DtDdWrapper');
			$element->removeDecorator('Label'); 
		}
		//End of Remove DD Dt tags
		$this->view->form= $form;
		if($this->getRequest()->isPost()){
			$formData = $this->getRequest()->getPost();
			 
			$username= login_user();
			$newpassword =$this->getRequest()->getPost('newpassword2');
			$users = new Model_Users();
				$data= array(
					'password' => md5($newpassword),
					'change_default_password'=>true			
				);
				$where= $users->getAdapter()->quoteInto('username = ?', $username);
				$users->update($data,$where);
				
				$userTrail = new Model_UsersTrail();
				$userTrail->passwordchange($username);

				$this->_redirect('index/');		
		 } // end of getRequest
	}

	
}
	
	
	function login_user_role(){
		$user = Zend_Auth::getInstance()->getIdentity();
		return $user->role_type;
	}
		
	function login_user(){
		$user = Zend_Auth::getInstance()->getIdentity();
		return $user->username;
	}

    





