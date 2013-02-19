<?php

class AdminController extends Zend_Controller_Action
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
	// $this->_helper->redirector('login','auth');

    }
	

    public function attendanceAction(){
		
		if(getUserType() == 'CO'){
				$this->_redirect('/admin/attendancelist');		

		}
		
		$form = new Form_Admin_Attendance();
		$date = date('Y-m-d');
		$startdate = $date." 08:00:00";	// of the morning 
		$enddate = $date." 21:30:00";	 // of the afternoon
	
		$table = new Model_UsersTimeInfo();	 	   	
		$select = $table->select();
		$select->where("type = 'absent' OR type = 'pre-absent'");
		$select->where("datetime between '$startdate' and '$enddate'");
		$detail = $table->fetchAll($select);
		$this->view->detail = $detail;
		
		$this->view->form = $form;
		if ($this->getRequest()->isPost()) {
  		  $formData = $this->getRequest()->getPost();
	 	   if ($form->isValid($formData)) {

			if(getUserType() == 'CA'){
			$status = 'pre-'.$form->getValue('status');
			}
			$table = new Model_UsersTimeInfo();	 	   	
			$data = array(
			'username'=>$form->getValue('approver'),
			'type'=>$status,
			'approver_alternative'=>$form->getValue('alternate'),
			'remarks'=>$form->getValue('remarks'),
			'set_by'=>getUser(),
			'datetime'=>date("r"),
			);
			$table->insert($data);
			
			$this->_redirect('/admin/attendance');		

		   }
		}
		
		
	}
	
	public function attendancelistAction(){
		
		$this->_helper->RolePermissionHelper('admin_attendancelist');


		$this->_helper->viewRenderer('attendance-co');
		$form = new Form_Admin_Attendance();
		$date = date('Y-m-d');
		$startdate = $date." 08:00:00";	// of the morning 
		$enddate = $date." 21:30:00";	 // of the afternoon
	
		$table = new Model_UsersTimeInfo();	 	   	
		$select = $table->select();
		$select->where("type = 'absent' OR type = 'pre-absent'");
		$select->where("datetime between '$startdate' and '$enddate'")
		->order('type ASC');
		$detail = $table->fetchAll($select);
		$this->view->detail = $detail;
		
		
	}
	
	public function attendanceapproveAction(){
	$this->_helper->viewRenderer->setNoRender(true);
	$this->_helper->RolePermissionHelper('admin_attendanceapprove');

	$id = $this->_getParam('id');
	
			$table = new Model_UsersTimeInfo();	 	   	
			$data = array(
			'type'=>'absent',
			'approve_by'=>getUser(),
			);
			$where = "id =".$id;
			$table->update($data,$where);
			
			$this->_redirect('/admin/attendancelist');		
		
	}
	
	public function attendancerejectAction(){
	$this->_helper->viewRenderer->setNoRender(true);
	$this->_helper->RolePermissionHelper('admin_attendancereject');

	$id = $this->_getParam('id');
	
			$table = new Model_UsersTimeInfo();	 	   	
			$data = array(

			'type'=>'reject-absent',
			'approve_by'=>getUser(),
			);
			$where = "id =".$id;
			$table->update($data,$where);
			
			$this->_redirect('/admin/attendancelist');	

		
	}
    public function roleAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('role'); 
		
		$form = new Form_AdminRole();
		//$capno = $this->_getParam('cap');
	
		$this->view->form=$form;
	}
	
	public function promoAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('promo'); 
		
		$form = new Form_AdminPromo();
		//$capno = $this->_getParam('cap');
	
		$this->view->form=$form;
	}
	
	public function userrightsAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('user-rights'); 
		
		$form = new Form_AdminUserRights();
		//$capno = $this->_getParam('cap');
	
		$this->view->form=$form;
	}
	
	public function loginattendanceAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('login-attendance'); 
	}
	
	public function deviationAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('deviation'); 
		
		$form = new Form_AdminDeviation();
		$this->view->form=$form;
	}
	
	public function weights2Action() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('weights2'); 
	}
	
	public function digitalpasswordAction(){
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/md5.js');

	$this->_helper->RolePermissionHelper('admin_digitalpassword');


	$this->_helper->viewRenderer('digitalpassword'); 


	if ($this->getRequest()->isPost()) {
		$formData = $this->getRequest()->getPost();

			$user = new Model_Users();
			$logged = getUser();
			
			$data = array(
			'digital_password'=>md5($formData['newdigiPass']),
			'change_default_digi_password'=>true,
			);

				$where = "username like '$logged'";
				$user->update($data,$where);
			$auditTrail = new Model_UsersTrail();
			$auditTrail->digipasswordchange($logged);
			
			$this->view->success ="Password Successfully Changed";
		
	}// end of getData
}

 public function resetpasswordAction(){
 	
		$this->_helper->RolePermissionHelper('admin_resetpassword');

		
		$this->_helper->viewRenderer('users/resetpassword'); 
		
	if ($this->getRequest()->isPost()) {
   	 $formData = $this->getRequest()->getPost();
		
		$id = $this->_getParam('id');
		
			// Generation of Password	 	   	
			$num1 = rand(0,9);			
	 	   	$num2 = rand(0,9);			
	 	   	$num3 = rand(0,9);			
	 	   	$num4 = rand(0,9);			
	 	   	$letter1 = randLetter();
	 	   	$letter2 = randLetter();
	 	   	$letter3 = randLetter();	
	 	   	$letter4 = randLetter();
			$genPassword = $num1.$letter1.$num2.$letter2.$num3.$letter3.$num4.$letter4;
			// End of Generation of Password
			
		$this->view->newpassword = $genPassword;
		$table = new Model_Users();
		$username = $table->returnUsernamebyId($id);
		$this->view->username = $username;
		$data = array(
		'password'=>md5($genPassword),
		'change_default_password'=>0
		);
		
		$where = $table->getAdapter()->quoteInto('id = ?', $id);
		$table->update($data,$where);
		
		$audit = new Model_UsersTrail();
		$data2 = array(
		'datetime'=>date("r"),
		'user'=>$username,
		'by'=>getUser(),
		'type'=>'Admin Reset User Password'		
		);
		$audit->insert($data2);
		}
		
		}

public function resetdigipasswordAction(){
	
		$this->_helper->RolePermissionHelper('admin_resetdigipassword');

		
		$this->_helper->viewRenderer('users/resetdigipassword'); 
	if ($this->getRequest()->isPost()) {
   	 $formData = $this->getRequest()->getPost();
		$id = $this->_getParam('id');
		
			// Generation of Password	 	   	
			$num1 = rand(0,9);			
	 	   	$num2 = rand(0,9);			
	 	   	$num3 = rand(0,9);			
	 	   	$num4 = rand(0,9);			
	 	   	$letter1 = randLetter();
	 	   	$letter2 = randLetter();
	 	   	$letter3 = randLetter();	
	 	   	$letter4 = randLetter();
			$genPassword = $num1.$letter1.$num2.$letter2.$num3.$letter3.$num4.$letter4;
			// End of Generation of Password
			
		$this->view->newpassword = $genPassword;
		$table = new Model_Users();
		$username = $table->returnUsernamebyId($id);
		$this->view->username = $username;
		$data = array(
		'digital_password'=>md5($genPassword),
		'change_default_digi_password'=>0
		);
		
		$where = $table->getAdapter()->quoteInto('id = ?', $id);
		$table->update($data,$where);
		
		$audit = new Model_UsersTrail();
		$data2 = array(
		'datetime'=>date("r"),
		'user'=>$username,
		'by'=>getUser(),
		'type'=>'Admin Reset User Digital Password'		
		);
		$audit->insert($data2);
		}
		}

	public function weightsAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		$this->_helper->viewRenderer('weights'); 
		
		$form = new Form_AdminWeights();
		$this->view->form=$form;
	}
	
	public function usersAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv2.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/Admin/user-role.js');

		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		
		$this->_helper->RolePermissionHelper('admin_users');

		
		$this->_helper->viewRenderer('users/usersnew'); 

		$form = new Form_Admin_Users();
		
		$this->view->form = $form;
		$users = new Model_Users();
		$select = $users->select()->order("role_type");
		$detail = $users->fetchAll($select);
		$this->view->details = $detail;
		
		
		if ($this->getRequest()->isPost()) {
  		  $formData = $this->getRequest()->getPost();
	 	   if ($form->isValid($formData)) {

			// Generation of Password	 	   	
			$num1 = rand(0,9);			
	 	   	$num2 = rand(0,9);			
	 	   	$num3 = rand(0,9);			
	 	   	$num4 = rand(0,9);			
	 	   	$letter1 = randLetter();
	 	   	$letter2 = randLetter();
	 	   	$letter3 = randLetter();	
	 	   	$letter4 = randLetter();
			$genPassword = $num1.$letter1.$num2.$letter2.$num3.$letter3.$num4.$letter4;
			// End of Generation of Password
		
		$day =  date('d',strtotime($form->getValue('birthdate')));
		$year = date('Y',strtotime($form->getValue('birthdate')));
		$month = date('m',strtotime($form->getValue('birthdate')));
				
		$table = new Model_Users();
		$data = array(
		'name'=>$form->getValue('name'),
		'username'=>$form->getValue('username'),
		'password'=>md5($genPassword),
		'digital_password'=> md5($year.$month.$day),
		'birthdate'=>$form->getValue('birthdate'),	
		'department'=>$form->getValue('group'),	
		
		'role_type'=>$form->getValue('role'),			
		'emp_no'=>$form->getValue('emp_no'),	
		'tin_no'=>$form->getValue('tin_no'),		
	
		'active'=>true,
		'created_date'=>date("r"),
		'created_by'=>getUser(),
		'change_default_password'=>0,
		'change_default_digi_password'=>0,
		'branch'=>'01'

		);
		$table->insert($data);
		
		$audit = new Model_UsersTrail();
		$data2 = array(
		'datetime'=>date("r"),
		'user'=>$form->getValue('username'),
		'by'=>getUser(),
		'type'=>'User Account Creation'		
		);
		$audit->insert($data2);
		$this->_redirect('/admin/users');
		
		   }// end of isValid
		} // end of getRequest

		}
		
	public function enabledisableAction(){
		    $this->_helper->RolePermissionHelper('admin_enabledisable');

		
		    $this->_helper->viewRenderer->setNoRender(true);
		    $id = $this->_getParam('id');
		 	$act = $this->_getParam('act');
			
			$users = new Model_Users();
			$data = array(
			'active'=>$act
			);
			$where = $users->getAdapter()->quoteInto('id = ?', $id);
		    $users->update($data,$where);
		    $this->_redirect('/admin/users');	
			
		
	}	
	
	public function accounttrailAction(){
		
		if(getUserType() != 'ADMIN'){
			$this->_redirect('/error/denied/');	
		}
		$this->_helper->viewRenderer('users/account-trail'); 
		
		$table = new Model_UsersTrail();
		$select = $table->select();
		$detail = $table->fetchAll($select);
		$page=$this->_getParam('page',1);
    	$paginator = Zend_Paginator::factory($detail);
    	$paginator->setItemCountPerPage(10);
    	$paginator->setCurrentPageNumber($page);
    	$this->view->details=$paginator;
		
	}
	
	public function logintrailAction(){
		
		if(getUserType() != 'ADMIN'){
			$this->_redirect('/error/denied/');	
		}
		$this->_helper->viewRenderer('users/login-trail'); 
		
		$table = new Model_UsersTimeInfo();
		$select = $table->select();
		$detail = $table->fetchAll($select);
		
		$page=$this->_getParam('page',1);
    	$paginator = Zend_Paginator::factory($detail);
    	$paginator->setItemCountPerPage(10);
    	$paginator->setCurrentPageNumber($page);
    	$this->view->details=$paginator;
	}
	
	public function usersoldAction() {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv2.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');

		if(getUserType() != 'ADMIN'){
			$this->_redirect('/error/denied/');	
		}
		
		$userTable = new Model_Users();
		$select = $userTable->select();
		$select->order('id ASC');
		$userDetail = $userTable->fetchAll($select);
		$this->view->userDetail = $userDetail; 
		
		
		
		$form = new Form_AdminUser();
		$this->view->form=$form;
		
		if ($this->getRequest()->isPost()) {
  		  $formData = $this->getRequest()->getPost();
	 	   if ($form->isValid($formData)) {
	 	   	
			$userTable = new Model_Users();
			
			if($form->getValue("roles") == "MA"){
				$data = array(
				'name' => $form->getValue("fullname"),
				'department'=>$form->getValue("department"),
				'branch'=>$form->getValue("branch"),
				'username'=>$form->getValue("username"),
				'password'=>md5($form->getValue("password")),
				'created_date'=>date("r"),
				'created_by'=>getUser(),
				'role_type'=>$form->getValue("roles"),
				'almh'=>$form->getValue("almh"),
				'dh'=>$form->getValue("dh"),
				'ah'=>$form->getValue("ah"),
				'mo'=>$form->getValue("mo"),
				'active'=>true,
				);
			}
			else if($form->getValue("roles") == "MO"){
				$data = array(
				'name' => $form->getValue("fullname"),
				'department'=>$form->getValue("department"),
				'branch'=>$form->getValue("branch"),
				'username'=>$form->getValue("username"),
				'password'=>md5($form->getValue("password")),
				'created_date'=>date("r"),
				'created_by'=>getUser(),
				'role_type'=>$form->getValue("roles"),
				'almh'=>$form->getValue("almh"),
				'dh'=>$form->getValue("dh"),
				'ah'=>$form->getValue("ah"),
				'active'=>true,
				);
			}
			else if($form->getValue("roles") == "AH"){
				$data = array(
				'name' => $form->getValue("fullname"),
				'department'=>$form->getValue("department"),
				'branch'=>$form->getValue("branch"),
				'username'=>$form->getValue("username"),
				'password'=>md5($form->getValue("password")),
				'created_date'=>date("r"),
				'created_by'=>getUser(),
				'role_type'=>$form->getValue("roles"),
				'almh'=>$form->getValue("almh"),
				'dh'=>$form->getValue("dh"),
				'active'=>true,
				);
			}
			else if($form->getValue("roles") == "DH"){
				$data = array(
				'name' => $form->getValue("fullname"),
				'department'=>$form->getValue("department"),
				'branch'=>$form->getValue("branch"),
				'username'=>$form->getValue("username"),
				'password'=>md5($form->getValue("password")),
				'created_date'=>date("r"),
				'created_by'=>getUser(),
				'role_type'=>$form->getValue("roles"),
				'almh'=>$form->getValue("almh"),
				'active'=>true,
				);
			}
			else if(($form->getValue("roles") == "CA") || ($form->getValue("roles") == "AP") ||  ($form->getValue("roles") == "CI")){
				$data = array(
				'name' => $form->getValue("fullname"),
				'department'=>$form->getValue("department"),
				'branch'=>$form->getValue("branch"),
				'username'=>$form->getValue("username"),
				'password'=>md5($form->getValue("password")),
				'created_date'=>date("r"),
				'created_by'=>getUser(),
				'role_type'=>$form->getValue("roles"),
				'csh'=>$form->getValue("csh"),
				'co'=>$form->getValue("co"),
				'active'=>true,
				);
			}
			else if($form->getValue("roles") == "CO"){
				$data = array(
				'name' => $form->getValue("fullname"),
				'department'=>$form->getValue("department"),
				'branch'=>$form->getValue("branch"),
				'username'=>$form->getValue("username"),
				'password'=>md5($form->getValue("password")),
				'created_date'=>date("r"),
				'created_by'=>getUser(),
				'role_type'=>$form->getValue("roles"),
				'csh'=>$form->getValue("csh"),
				'active'=>true,
				);
			}
			else if($form->getValue("roles") == "LA"){
				$data = array(
				'name' => $form->getValue("fullname"),
				'department'=>$form->getValue("department"),
				'branch'=>$form->getValue("branch"),
				'username'=>$form->getValue("username"),
				'password'=>md5($form->getValue("password")),
				'created_date'=>date("r"),
				'created_by'=>getUser(),
				'role_type'=>$form->getValue("roles"),
				'lo'=>$form->getValue("lo"),
				'active'=>true,
				);
			}
			else if($form->getValue("roles") == "CRAA"){
				$data = array(
				'name' => $form->getValue("fullname"),
				'department'=>$form->getValue("department"),
				'branch'=>$form->getValue("branch"),
				'username'=>$form->getValue("username"),
				'password'=>md5($form->getValue("password")),
				'created_date'=>date("r"),
				'created_by'=>getUser(),
				'role_type'=>$form->getValue("roles"),
				'crah'=>$form->getValue("crah"),
				'active'=>true,
				);
			}
			else{
				$data = array(
				'name' => $form->getValue("fullname"),
				'department'=>$form->getValue("department"),
				'branch'=>$form->getValue("branch"),
				'username'=>$form->getValue("username"),
				'password'=>md5($form->getValue("password")),
				'created_date'=>date("r"),
				'created_by'=>getUser(),
				'role_type'=>$form->getValue("roles"),
				'active'=>true,
				);
				
			}

			$userTable->insert($data);
			$this->_redirect('/admin/usersold');


		   }// IS Valid
		}// Is Post
	}
	
	
	
	public function vehiclelistAction() {
		$this->_helper->viewRenderer('vehicle-list'); 
		$form = new Form_VehicleBrandList();
		$this->view->form=$form;
		
		$brandlist = new Model_ChainVehicleBrand();
			$select = $brandlist->select();
			$branddetail = $brandlist->fetchAll($select);
			$this->view->branddetail = $branddetail;	
		
	//	$form->newbrand1->setValue($branddetail ->brand);
			
				if ($this->getRequest()->isPost()) {
				$formData = $this->getRequest()->getPost();
					if ($form->isValid($formData)) {  
				
					if ($formData['button'] == 'Add')  {
					
					$data = array(
					'brand' => $form->getValue('newbrand'),
					'seq' => $form->getValue('newseq'),
					);
					$table = new Model_ChainVehicleBrand();
					
					$table->insert($data);	
					//if no record found insert data
				}
			}
		}
	
	
}
public function delbrandlistAction(){
		   $this->_helper->viewRenderer->setNoRender(true);
		    $id = $this->_getParam('id');
		    $brand= new Model_ChainVehicleBrand();
		    $where = $brand->getAdapter()->quoteInto('id = ?', $id);
		    $brand->delete($where);
		    $this->_redirect('/admin/vehicle-list');	
}

public function pricelistAction(){
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery.tablesorter.min.js');
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tableheader.css');	
	$this->_helper->viewRenderer('pricelist/pricelist'); 
	
	if ($this->view->loggedInUserType() != 'CA' && $this->view->loggedInUserType() != 'CRAA' && $this->view->loggedInUserType() != 'ADMIN'){
            $this->_helper->redirector('denied','error');
		}
	
	$form = new Form_Admin_PriceList();
	$this->view->form = $form;
	
	if ($this->getRequest()->isPost()) {
	$formData = $this->getRequest()->getPost();
		if ($form->isValid($formData)) {  
		
			$table = new Model_ChainVehicle();

		if($formData['button'] == 'Retrieve'){
		$dealer = $form->getValue('dealer');
		$brand = $form->getValue('veh_brand');
		$year = $form->getValue('listyear');
		$month = $form->getValue('listmonth');

		$select = $table->select();
		
		if($dealer){
		$select->where('dealer like ?',$dealer.'%');
		}
		if($brand){
		$select->where('brand like ?',$brand.'%');
		}
		if($year){
		$select->where('year like ?',$year.'%');
		}
		if($month){
		$select->where('month like ?',$month.'%');
		}
		$select->where('status like ?','approved');
		$detail = $table->fetchAll($select);

		$this->view->detail = $detail;
		/////////////////////////////////
		$this->view->dealerTemp = $dealer;
		$this->view->yearTemp = $year;
		$this->view->monthTemp = $month;
		$this->view->brandTemp = $brand;
		$this->view->ifPost = true;


		}//End of Retrieve
		else if($formData['button'] == 'Duplicate'){
		
		$dealerTemp = $formData['dealerTemp'];
		$yearTemp = $formData['yearTemp'];
		$monthTemp = $formData['monthTemp'];
		$brandTemp = $formData['brandTemp'];
		
		$listyear =  $formData['listyear2'];
		$listmonth =  $formData['listmonth2'];
		$listdealer = $formData['dealer2'];
		$select = $table->select();
		
			if($dealerTemp){
			$select->where('dealer like ?',$dealerTemp.'%');
			}
			if($brandTemp){
			$select->where('brand like ?',$brandTemp.'%');
			}
			if($yearTemp){
			$select->where('year like ?',$yearTemp.'%');
			}
			if($monthTemp){
			$select->where('month like ?',$monthTemp.'%');
			}
		$detail = $table->fetchAll($select)->toArray();
		
		foreach($detail as $row){
			$row['id'] = null;
			$row['dealer'] = $listdealer;
			$row['month'] = $listmonth;
			$row['year'] = $listyear;
			$row['status'] = 'duplicate';
			$row['duplicate_by'] = $this->view->loggedInUser();
			$row['duplicate_date']=date('m-d-Y');
			$row['checked'] = 0;
			$table->insert($row);
		}// end of foreach
		
		//print_r($row);
		$this->_redirect('admin/pricelistupdate');

		
		}// End of Duplicate


		}// End of isValid
	}// End of Get REquest
}// End of pricelistAction 

public function pricelistupdate2Action(){
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery.tablesorter.min.js');
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tableheader.css');	
	
	$this->_helper->viewRenderer('pricelist/pricelist-update2'); 
	
	if ($this->view->loggedInUserType() != 'CA' && $this->view->loggedInUserType() != 'CRAA' && $this->view->loggedInUserType() != 'ADMIN'){
            $this->_helper->redirector('denied','error');
		}
	
	$form = new Form_Admin_PriceList();
	$this->view->form = $form;
	 
	$table = new Model_ChainVehicle();
	$select = $table->select();
	$select->where('status like ?','duplicate')->order('dealer ASC');
	$detail = $table->fetchAll($select);
	
	$this->view->detail = $detail;
	
	if ($this->getRequest()->isPost()) {
	$formData = $this->getRequest()->getPost();
	
		$table = new Model_ChainVehicle();

		if ($form->isValid($formData)) {  
		
		if($formData['button'] == 'Add Unit'){
			
			$data = array(
			'dealer'=>$form->getValue('dealer'),
			'brand'=>$form->getValue('veh_brand'),
			'year'=>$form->getValue('listyear'),
			'month'=>$form->getValue('listmonth'),
			'type'=>$form->getValue('veh_type'),
			'unit'=>strtoupper($form->getValue('veh_unit')),
			'selling_price'=>number_format($form->getValue('selling_price'),2,'.',','),
			'duplicate_by'=>$this->view->loggedInUser(),
			'duplicate_date'=>date('m-d-Y'),
			'status'=>'duplicate',
			);
			$table->insert($data);
			
			$this->_redirect('admin/pricelistupdate2');
		} // end of Add Unit
		elseif($formData['button'] == 'Update'){
			foreach($this->_request->getPost('row') as $row){
				
				if($row['chkbox']){
				$num = str_replace(',','',$row['selling_price']);
				$selling_price = number_format($num,2,'.',',');	
				$data = array(
				'selling_price' => $selling_price,
				'status'=>'update',
				'update_by' => $this->view->loggedInUser(),
				'update_date' =>date('m-d-Y'),
				);	
				$where = "id = ".$row['id'];
				$table->update($data, $where);
				

				}// end of checkbox	
			} // end for each
			$this->_redirect('admin/pricelistupdate2');

		}
		elseif($formData['button'] == 'Delete'){
		
			foreach($this->_request->getPost('row') as $row){
			echo $row['id'];
			echo $row['selling_price'];
			echo $row['chkbox'];
			echo '<br>';
					
			if($row['chkbox']){
				$where = "id = ".$row['id'];
		  		$table->delete($where);
			}
		
			}// end for each
			$this->_redirect('admin/pricelistupdate2');
		}
		
		}// end of isValid 		
	} // end of getRequest
	
}

public function pricelistupdateAction(){
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery.tablesorter.min.js');
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tableheader.css');	
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');

	
	$this->_helper->viewRenderer('pricelist/pricelist-update'); 
	
	if ($this->view->loggedInUserType() != 'CA' && $this->view->loggedInUserType() != 'CRAA' && $this->view->loggedInUserType() != 'ADMIN'){
            $this->_helper->redirector('denied','error');
		}
	
	$dealer = $this->_getParam('dealer');
	$month = $this->_getParam('month');
	if(!$month){
	$month = $this->view->getMonthPriceList('updatePriceList','');	
	}
	$form = new Form_Admin_PriceList();
	
	
	$table = new Model_ChainVehicle();

	if($dealer){
	$select = $table->select();
	$select->where('dealer like ?',$dealer);
	$select->where('month like ?',"$month");
	$select->where('year like ?',date('Y'));
	$select->where("status like 'duplicate'")->order('id ASC');
	$detail = $table->fetchAll($select);
	
	$select = $table->select();
	$select->where('dealer like ?',$dealer);
	$select->where('month like ?',"$month");
	$select->where('year like ?',date('Y'));
	$select->where("status like 'new'")->order('id ASC');
	$detailNew = $table->fetchAll($select);
	
	}
	$form->dealer_new->setValue($dealer);
	/**********Set Default Value for the form elements********************/
	
	$form->dealer->setValue($dealer);
	$form->listyear->setValue(date('Y'));
	$form->listmonth->clearMultiOptions();
	$form->listmonth->addMultiOption('','');
	$form->listmonth->addMultiOption(date('n'),date('F'));
	$form->listmonth->addMultiOption(date('n')+ 1,date('F',strtotime("+1 months")) );
	$form->listmonth->setValue('');
	$this->view->form = $form;
	
	$select = $table->select();
	$select->where("status like 'duplicate' OR status like 'new'")->order('dealer ASC')->distinct('dealer');
	$detailx = $table->fetchAll($select);
	
	foreach($detailx as $x){
		$form->dealer_new->addMultiOption($x->dealer,$x->dealer);
	}
	$select = $table->select();
	$select->where("status like 'new' OR status like 'duplicate'")->order('dealer ASC')->distinct('month');
	$detailxx = $table->fetchAll($select);
	$form->listmonth2->clearMultiOptions();
	foreach($detailxx as $x){
		$targetMonth = date("Y-".$x->month."-d");
		$form->listmonth2->addMultiOption($x->month,date('F',strtotime($targetMonth)));
	}
	/***************************************************************/
	$this->view->detail = $detail;
	$this->view->detailNew = $detailNew;
	$this->view->dealerTemp = $dealer;
	
	$this->view->postDealer = $dealer;
	$this->view->postMonth = $month;
	$this->view->postYear = date('Y');
	
	if($dealer){
	$remarksTable = new Model_PricelistHistory();
	$select = $remarksTable->select();
	$select->where('month like ?',$month.'');
	$select->where('year like ?',date('Y'));
	$select->where('dealer like ?',$dealer);
	$select->where('process like ?','Return Pricelist');
	$remarksDetail = $remarksTable->fetchRow($select);
	$this->view->remarksDetail = $remarksDetail;
	}
	if ($this->getRequest()->isPost()) {
	$formData = $this->getRequest()->getPost();
	
		$table = new Model_ChainVehicle();

		if ($form->isValid($formData)) {  
		
		if($formData['button'] == 'Add Unit'){
			
			$data = array(
			'dealer'=>$form->getValue('dealer'),
			'brand'=>$form->getValue('veh_brand'),
			'year'=>$form->getValue('listyear'),
			'month'=>$form->getValue('listmonth'),
			'type'=>$form->getValue('veh_type'),
			'unit'=>strtoupper($form->getValue('veh_unit')),
			'selling_price'=>number_format($form->getValue('selling_price'),2,'.',','),
			'duplicate_by'=>$this->view->loggedInUser(),
			'duplicate_date'=>date('m-d-Y'),
			'status'=>'new',
			);
			$table->insert($data);
			//$this->_redirect('admin/pricelistupdate/dealer/'.$form->getValue('dealer_new').'/month/'.$form->getValue('listmonth2'));

			$this->_redirect('admin/pricelistupdate/dealer/'.$form->getValue('dealer').'/month/'.$formData['listmonth']);
		} // end of Add Unit
		
		
	elseif($formData['button'] == 'Save Checked'){

			foreach($this->_request->getPost('row') as $row){
				$num = str_replace(',','',$row['selling_price']);
				$selling_price = number_format($num,2,'.',',');	
				$data = array(
				'selling_price' => $selling_price,
				'checked' => true
				);	
					if($row['chkbox']){
					$where = "id = ".$row['id'];
					$table->update($data, $where);
					}

			} // end for each

			foreach($this->_request->getPost('rowNew') as $row){
				$num = str_replace(',','',$row['selling_price']);
				$selling_price = number_format($num,2,'.',',');	
				$data = array(
				'selling_price' => $selling_price,
				'checked' => true

				);	
					if($row['chkbox']){
					$where = "id = ".$row['id'];
					$table->update($data, $where);
					}
			} // end for each
			
				$history = new Model_PricelistHistory();
				$his = array(
				'dealer'=>$formData['postDealer'],
				'month'=> $formData['postMonth'],
				'year'=> $formData['postYear'],
				'process' =>'Save Pricelist',
				'date'=>date("r"),
				'by'=> $this->view->loggedInUser()
				);
				$history->insert($his);
			//$this->_redirect('admin/pricelistupdate/dealer/'.$dealer);
			$this->_redirect('admin/pricelistupdate/dealer/'.$formData['postDealer'].'/month/'.$formData['postMonth']);

		}
		elseif($formData['button'] == 'Submit All'){

			foreach($this->_request->getPost('row') as $row){
				$num = str_replace(',','',$row['selling_price']);
				$selling_price = number_format($num,2,'.',',');	
				$data = array(
				'selling_price' => $selling_price,
				'status'=>'update',
				'update_by' => $this->view->loggedInUser(),
				'update_date' =>date('m-d-Y'),
				'checked' => 0
				);	
				$where = "id = ".$row['id'];
				$table->update($data, $where);

			} // end for each

			foreach($this->_request->getPost('rowNew') as $row){
				$num = str_replace(',','',$row['selling_price']);
				$selling_price = number_format($num,2,'.',',');	
				$data = array(
				'selling_price' => $selling_price,
				'status'=>'update-new',
				'update_by' => $this->view->loggedInUser(),
				'update_date' =>date('m-d-Y'),
				'checked' => 0				
				);	
				$where = "id = ".$row['id'];
				$table->update($data, $where);

			} // end for each
			
				$history = new Model_PricelistHistory();
				$his = array(
				'dealer'=>$formData['postDealer'],
				'month'=> $formData['postMonth'],
				'year'=> $formData['postYear'],
				'process' =>'Submit Pricelist',
				'date'=>date("r"),
				'by'=> $this->view->loggedInUser()
				);
				$history->insert($his);
			$this->_redirect('admin/pricelistupdate/dealer/'.$dealer);

		}		
		elseif($formData['button'] == 'Submit Checked'){

			foreach($this->_request->getPost('row') as $row){
				$num = str_replace(',','',$row['selling_price']);
				$selling_price = number_format($num,2,'.',',');	
				$data = array(
				'selling_price' => $selling_price,
				'status'=>'update',
				'update_by' => $this->view->loggedInUser(),
				'update_date' =>date('m-d-Y'),
				'checked' => 0
				);	
				if($row['chkbox']){
				$where = "id = ".$row['id'];
				$table->update($data, $where);
				}
			} // end for each

			foreach($this->_request->getPost('rowNew') as $row){
				$num = str_replace(',','',$row['selling_price']);
				$selling_price = number_format($num,2,'.',',');	
				$data = array(
				'selling_price' => $selling_price,
				'status'=>'update-new',
				'update_by' => $this->view->loggedInUser(),
				'update_date' =>date('m-d-Y'),
				'checked' => 0
				);	
				if($row['chkbox']){
				$where = "id = ".$row['id'];
				$table->update($data, $where);
				}
			} // end for each
			
				$history = new Model_PricelistHistory();
				$his = array(
				'dealer'=>$formData['postDealer'],
				'month'=> $formData['postMonth'],
				'year'=> $formData['postYear'],
				'process' =>'Save Pricelist',
				'date'=>date("r"),
				'by'=> $this->view->loggedInUser()
				);
				$history->insert($his);
			$this->_redirect('admin/pricelistupdate/dealer/'.$dealer.'/month/'.$formData['postMonth']);

		}
		
		elseif($formData['button'] == 'Delete Checked'){
		
			foreach($this->_request->getPost('row') as $row){
			echo $row['id'];
			echo $row['selling_price'];
			echo $row['chkbox'];
			echo '<br>';
					
			if($row['chkbox']){
				$where = "id = ".$row['id'];
		  		$table->delete($where);
			}
			}	//end for each	
		
			foreach($this->_request->getPost('rowNew') as $row){
			echo $row['id'];
			echo $row['selling_price'];
			echo $row['chkbox'];
			echo '<br>';
					
			if($row['chkbox']){
				$where = "id = ".$row['id'];
		  		$table->delete($where);
			}
		
			}// end for each
			//$this->_redirect('admin/pricelistupdate/dealer/'.$dealer);
			$this->_redirect('admin/pricelistupdate/dealer/'.$form->getValue('dealer_new').'/month/'.$form->getValue('listmonth2'));

		}
		else if($formData['button'] == 'Go'){
				$this->_redirect('admin/pricelistupdate/dealer/'.$form->getValue('dealer_new').'/month/'.$form->getValue('listmonth2'));
		 }
		
		}// end of isValid 		
	} // end of getRequest
	
}

public function pricelistapproveAction(){
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery.tablesorter.min.js');
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tableheader.css');
		
	$this->_helper->viewRenderer('pricelist/pricelist-approve'); 
	
	if ($this->view->loggedInUserType() != 'CSH' && $this->view->loggedInUserType() != 'CRAA' && $this->view->loggedInUserType() != 'ADMIN' && $this->view->loggedInUserType() != 'CA'
	&& $this->view->loggedInUserType() != 'CO'
	){
         $this->_helper->redirector('denied','error');
	}
	if ($this->view->loggedInUserType() == 'CA'){
	$this->view->counter = 1;
	//remove the button approve and delete if CA is accessing the page
	}else { 
	$this->view->counter = 0;
	}
	
	$dealer = $this->_getParam('dealer');	
	$month = $this->_getParam('month');
	
	if(!$month){
	$month = $this->view->getMonthPriceList('updatePriceList','');	
	}
		
	$form = new Form_Admin_PriceList();
	$this->view->form = $form;
	$table = new Model_ChainVehicle();
	
	if($dealer){
	$select = $table->select();
	$select->where('dealer like ?',$dealer);
	$select->where('month like ?',"$month");
	$select->where('year like ?',date('Y'));
		if(getUserType() == "CO" || getUserType() == "CA" || getUserType() == "CRAA"){
		$select->where("status like 'update'")->order('id ASC'); }
		else if (getUserType() == "CSH") {
		$select->where("status like 'semi-approved'")->order('id ASC'); }
		
	$detail = $table->fetchAll($select);
	
	$select = $table->select();
	$select->where('dealer like ?',$dealer);
	$select->where('month like ?',"$month");
	$select->where('year like ?',date('Y'));
		if(getUserType() == "CO" || getUserType() == "CA" || getUserType() == "CRAA"){
		$select->where("status like 'update-new'")->order('id ASC'); }
		else if (getUserType() == "CSH") {
		$select->where("status like 'semi-approved-new'")->order('id ASC'); }	
	$detailNew = $table->fetchAll($select);
		
	}
	
	//$form->dealer_new->setAttrib('onchange','window.location="'.$this->view->baseUrl().'/admin/pricelistapprove/dealer/"+this.options[this.selectedIndex].value');
	$form->dealer_new->setValue($dealer);
	$form->listmonth->setValue($month);
	$this->view->detail = $detail;
	$this->view->detailNew = $detailNew;
	$this->view->targetMonth = $month;
	
	$this->view->postDealer = $dealer;
	$this->view->postMonth = $month;
	$this->view->postYear = date('Y');
	

/*****************Populate Select**********************/
	//  Dealer Dropdown
	$select = $table->select();
	if(getUserType() == "CO" || getUserType() == "CA" || getUserType() == "CRAA"){
	$select->where("status like 'update' OR status like 'update-new'")->order('dealer ASC')->distinct('dealer');
	}
	else if(getUserType() == "CSH"){
	$select->where("status like 'semi-approved' OR status like 'semi-approved-new'")->order('dealer ASC')->distinct('dealer');
	}
	$detailx = $table->fetchAll($select);
	foreach($detailx as $x){
		$form->dealer_new->addMultiOption($x->dealer,$x->dealer);
	}
	
	
	$select = $table->select();
	// Month Dropdown
	if(getUserType() == "CO" || getUserType() == "CA" || getUserType() == "CRAA"){
	$select->where("status like 'update' OR status like 'update-new'")->order('dealer ASC')->distinct('month');
	}
	else if(getUserType() == "CSH"){
	$select->where("status like 'semi-approved' OR status like 'semi-approved-new'")->order('dealer ASC')->distinct('month');
	}

	$detailxx = $table->fetchAll($select);
	$form->listmonth->clearMultiOptions();
	foreach($detailxx as $x){
		$targetMonth = date("Y-".$x->month."-d");
		$form->listmonth->addMultiOption($x->month,date('F',strtotime($targetMonth)));
	}
/*****************End Populate Select**********************/
	$this->view->detail = $detail;
	
	if ($this->getRequest()->isPost()) {
	$formData = $this->getRequest()->getPost();
	
		$table = new Model_ChainVehicle();

		if ($form->isValid($formData)) { 
		 
		 if($formData['button'] == 'Approve Pricelist'){

		 		if(getUserType() == "CO" || getUserType() == "ADMIN"){
		 			
				foreach($this->_request->getPost('row') as $row){
				$data = array(
				'status'=>'semi-approved',
				'approve_by' => $this->view->loggedInUser(),
				'approve_date' =>date('m-d-Y'),
				'checked' => 0				
				);	
				$where = "id = ".$row['id'];
				$table->update($data, $where);
				}// end for each 
				
				foreach($this->_request->getPost('rowNew') as $row){
				$data = array(
				'status'=>'semi-approved-new',
				'approve_by' => $this->view->loggedInUser(),
				'approve_date' =>date('m-d-Y'),
				'checked' => 0
				);	
				$where = "id = ".$row['id'];
				$table->update($data, $where);
				}// end for each 
				
				$history = new Model_PricelistHistory();
				$his = array(
				'dealer'=>$formData['postDealer'],
				'month'=> $formData['postMonth'],
				'year'=> $formData['postYear'],
				'process' =>'Semi Approve Pricelist',
				'date'=>date("r"),
				'by'=> $this->view->loggedInUser()
				);
				$history->insert($his);
				} // end of CO 
				else if(getUserType() == "CSH"){
				foreach($this->_request->getPost('row') as $row){
				$data = array(
				'status'=>'approved',
				'approve_by2' => $this->view->loggedInUser(),
				'approve_date2' =>date('m-d-Y'),
				'checked' => 0				
				);	
				$where = "id = ".$row['id'];
				$table->update($data, $where);
				}// end for each 
				
				foreach($this->_request->getPost('rowNew') as $row){
				$data = array(
				'status'=>'approved',
				'approve_by2' => $this->view->loggedInUser(),
				'approve_date2' =>date('m-d-Y'),
				'checked' => 0				
				);	
				$where = "id = ".$row['id'];
				$table->update($data, $where);
				}// end for each 
				
				$history = new Model_PricelistHistory();
				$his = array(
				'dealer'=>$formData['postDealer'],
				'month'=> $formData['postMonth'],
				'year'=> $formData['postYear'],
				'process' =>'Approve Pricelist',
				'date'=>date("r"),
				'by'=> $this->view->loggedInUser()
				);
				$history->insert($his);
				}//end of CSH
				
				
				$this->_redirect('admin/pricelistapprove');
		 }
		else if($formData['button'] == 'Approve Checked'){

		 		if(getUserType() == "CO" || getUserType() == "ADMIN"){
		 			
				foreach($this->_request->getPost('row') as $row){
					if($row['chkbox']){
					$data = array(
					'status'=>'semi-approved',
					'approve_by' => $this->view->loggedInUser(),
					'approve_date' =>date('m-d-Y'),
					'checked' => 0
					);	
					$where = "id = ".$row['id'];
					$table->update($data, $where); }
				}// end for each 
				
				foreach($this->_request->getPost('rowNew') as $row){
					if($row['chkbox']){
					$data = array(
					'status'=>'semi-approved-new',
					'approve_by' => $this->view->loggedInUser(),
					'approve_date' =>date('m-d-Y'),
					'checked' => 0
					);	
					$where = "id = ".$row['id'];
					$table->update($data, $where); }
					}// end for each  
				
				$history = new Model_PricelistHistory();
				$his = array(
				'dealer'=>$formData['postDealer'],
				'month'=> $formData['postMonth'],
				'year'=> $formData['postYear'],
				'process' =>'Semi Approve Pricelist',
				'date'=>date("r"),
				'by'=> $this->view->loggedInUser()
				);
				$history->insert($his);
				} // end of CO 
				else if(getUserType() == "CSH"){
				foreach($this->_request->getPost('row') as $row){
				if($row['chkbox']){
				$data = array(
				'status'=>'approved',
				'approve_by2' => $this->view->loggedInUser(),
				'approve_date2' =>date('m-d-Y'),
				'checked' => 0				
				);	
				$where = "id = ".$row['id'];
				$table->update($data, $where); }
				}// end for each 
				
				foreach($this->_request->getPost('rowNew') as $row){
				if($row['chkbox']){
				$data = array(
				'status'=>'approved',
				'approve_by2' => $this->view->loggedInUser(),
				'approve_date2' =>date('m-d-Y'),
				'checked' => 0				
				);	
				$where = "id = ".$row['id'];
				$table->update($data, $where); }
				}// end for each 
				
				$history = new Model_PricelistHistory();
				$his = array(
				'dealer'=>$formData['postDealer'],
				'month'=> $formData['postMonth'],
				'year'=> $formData['postYear'],
				'process' =>'Approve Pricelist',
				'date'=>date("r"),
				'by'=> $this->view->loggedInUser()
				);
				$history->insert($his);
				}//end of CSH
				
				
				//$this->_redirect('admin/pricelistapprove');
	 			$this->_redirect('admin/pricelistapprove/dealer/'.$formData['postDealer'].'/month/'.$formData['postMonth']);

		 }
		 else if($formData['button'] == 'Return Pricelist'){
				foreach($this->_request->getPost('row') as $row){
				$data = array(
				'status'=>'duplicate',
				'checked' => 0				
				);	
				$where = "id = ".$row['id'];
				$table->update($data, $where);
				}// end for each 
				
				foreach($this->_request->getPost('rowNew') as $row){
				$data = array(
				'status'=>'new',
				'checked' => 0				
				);	
				$where = "id = ".$row['id'];
				$table->update($data, $where);
				}// end for each 
				
				$history = new Model_PricelistHistory();
				$his = array(
				'dealer'=>$formData['postDealer'],
				'month'=> $formData['postMonth'],
				'year'=> $formData['postYear'],
				'process' =>'Return Pricelist',
				'date'=>date("r"),
				'by'=> $this->view->loggedInUser(),
				'remarks'=>$formData['remarks']
				);
				$history->insert($his);				
				$this->_redirect('admin/pricelistapprove');
				
		 }
		  elseif($formData['button'] == 'Save Check'){
			foreach($this->_request->getPost('row') as $row){
				if($row['chkbox']){
				$data = array(
				'checked' => true,
				);	
				$where = "id = ".$row['id'];
				$table->update($data, $where);

				}// end of checkbox	
			} // end for each
			
			foreach($this->_request->getPost('rowNew') as $row){
				if($row['chkbox']){
				$data = array(
				'checked' => true,
				);	
				$where = "id = ".$row['id'];
				$table->update($data, $where);

				}// end of checkbox	
			} // end for each
			
			$this->_redirect('admin/pricelistapprove/dealer/'.$form->getValue('dealer_new').'/month/'.$form->getValue('listmonth'));

		}
		 else if($formData['button'] == 'Go'){
				$this->_redirect('admin/pricelistapprove/dealer/'.$form->getValue('dealer_new').'/month/'.$form->getValue('listmonth'));
		 }
		
		
		}// End of isValid	
	}// End of getREquest
} // end of Action

public function pricelistapprove2Action(){
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery.tablesorter.min.js');
	$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tableheader.css');
		
	$this->_helper->viewRenderer('pricelist/pricelist-approve2'); 
	
	if ($this->view->loggedInUserType() != 'CSH' && $this->view->loggedInUserType() != 'CRAA' && $this->view->loggedInUserType() != 'ADMIN' && $this->view->loggedInUserType() != 'CA'){
         $this->_helper->redirector('denied','error');
	}
	if ($this->view->loggedInUserType() == 'CA'){
	$this->view->counter = 1;
	//remove the button approve and delete if CA is accessing the page
	}else { 
	$this->view->counter = 0;
	}
	
	$form = new Form_Admin_PriceList();
	$this->view->form = $form;
	
	$table = new Model_ChainVehicle();
	$select = $table->select();
	$select->where('status like ?','update');
	$detail = $table->fetchAll($select);
	
	$this->view->detail = $detail;
	
	if ($this->getRequest()->isPost()) {
	$formData = $this->getRequest()->getPost();
	
		$table = new Model_ChainVehicle();

		if ($form->isValid($formData)) { 
		 
		 if($formData['button'] == 'Approve'){
				foreach($this->_request->getPost('row') as $row){

				if($row['chkbox']){
				$data = array(
				'status'=>'approved',
				'approve_by' => $this->view->loggedInUser(),
				'approve_date' =>date('m-d-Y'),
				);	
				$where = "id = ".$row['id'];
				$table->update($data, $where);
				} // end if 
				}// end for each 
		 }
				
		elseif($formData['button'] == 'Delete'){
			foreach($this->_request->getPost('row') as $row){
					
			if($row['chkbox']){
				$where = "id = ".$row['id'];
		  		$table->delete($where);
			}
		
			}// end for each
		}
				
			$this->_redirect('admin/pricelistapprove2');

		}// End of isValid	
	}// End of getREquest
} // end of Action

public function pricelistviewAction(){
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
	$this->_helper->viewRenderer('pricelist/pricelist-view'); 
	$form = new Form_Admin_PriceList();
	$this->view->form = $form;
		
	if ($this->getRequest()->isPost()) {
	$formData = $this->getRequest()->getPost();
		if ($form->isValid($formData)) {  
		
		$table = new Model_ChainVehicle();
		if($formData['button'] == 'View Pricelist'){
		$dealer = $form->getValue('dealer');
		$brand = $form->getValue('veh_brand');
		$year = $form->getValue('listyear');
		$month = $form->getValue('listmonth');

		$select = $table->select();
		
		if($dealer){
		$select->where('dealer like ?',$dealer.'%');
		}
		if($brand){
		$select->where('brand like ?',$brand.'%');
		}
		if($year){
		$select->where('year like ?',$year.'%');
		}
		if($month){
		$select->where('month like ?',$month.'%');
		}
		//$select->where('status like ?','approved');
		$detail = $table->fetchAll($select);

		$this->view->detail = $detail;
		/////////////////////////////////
		$this->view->dealerTemp = $dealer;
		$this->view->yearTemp = $year;
		$this->view->monthTemp = $month;
		$this->view->brandTemp = $brand;
		$this->view->ifPost = true;
		}
		elseif($formData['button'] == 'Export as PDF'){
		
		echo $dealerTemp = $formData['dealerTemp'];
		echo $yearTemp = $formData['yearTemp'];
		echo $monthTemp = $formData['monthTemp'];
		echo $brandTemp = $formData['brandTemp'];
		
			$select = $table->select();

			if($dealerTemp){
			$select->where('dealer like ?',$dealerTemp.'%');
			}
			if($brandTemp){
			$select->where('brand like ?',$brandTemp.'%');
			}
			if($yearTemp){
			$select->where('year like ?',$yearTemp.'%');
			}
			if($monthTemp){
			$select->where('month like ?',$monthTemp.'%');
			}
			
		$detailx = $table->fetchAll($select);

		/**output in browser**/
		$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
	
		$pdf = new Zend_Pdf();
		$page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4_LANDSCAPE);
	    $pdf->pages[] = $page;
	    $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES), 9);		
		//Start of Header
		$page->drawText('LOS Pricelist Generation',10, 570);
		$page->drawText('Generated By: '.$this->view->viewMa($this->view->loggedInUser()),10, 560);
		$page->drawText('Generated Date: '.date('m-d-Y'),10, 550);
		// End of Header
		$page->drawText('By: ',50, 520);
		$page->drawText('Date: ',100, 520);
		$page->drawText('Dealer: ',150, 520);
		$page->drawText('Brand: ',280, 520);
		$page->drawText('Type: ',350, 520);
		$page->drawText('Selling Price: ',450, 520);
		$page->drawText('Unit: ',520, 520);
		
		$x = 500;
		foreach($detailx as $detail){
		/***PDF Creation ***/
		if($x == 50){
		$page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4_LANDSCAPE);
	    $pdf->pages[] = $page;
	    $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES), 9);		
		//Start of Header
		$page->drawText('LOS Pricelist Generation',10, 570);
		$page->drawText('Generated By: '.$this->view->viewMa($this->view->loggedInUser()),10, 560);
		$page->drawText('Generated Date: '.date('m-d-Y'),10, 550);
		// End of Header
		$page->drawText('By: ',50, 520);
		$page->drawText('Date: ',100, 520);
		$page->drawText('Dealer: ',150, 520);
		$page->drawText('Brand: ',280, 520);
		$page->drawText('Type: ',350, 520);
		$page->drawText('Selling Price: ',450, 520);
		$page->drawText('Unit: ',520, 520);
		$x=500;
		}	     			
			//$page->drawText($x,30, $x);
			$page->drawText(ucfirst($detail->approve_by),50, $x);
			$page->drawText($this->view->viewMonths($detail->month,'shortcut').'-'.$detail->year,100, $x);
			$page->drawText(ucfirst($detail->dealer),150, $x);
			$page->drawText($detail->brand,280, $x);
			$page->drawText($detail->type,350, $x);
			$page->drawText('P.'.$detail->selling_price,450, $x);
			$page->drawText($detail->unit,520, $x);
			$x = $x-10;
		}
	   
	   	header("Content-type: application/pdf");
		echo $pdf->render();			
	}
		
		}// end isValid		
	}// end of getRequest
	
	
}

public function dealerAction(){
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
	$this->_helper->viewRenderer('dealer/dealer-view'); 
	
	$form = new Form_Admin_Dealer();
	$table = new Model_ListDealer();
	$select = $table->select();
	$select->order("name ASC");
	$detail = $table->fetchAll($select);
	$this->view->details = $detail;
	$this->view->form = $form;
	
	if ($this->getRequest()->isPost()) {
	$formData = $this->getRequest()->getPost();
		if ($form->isValid($formData)) {  
			 if($formData['button'] == 'Add'){
			 	$data = array(
				'name' => ucwords(strtolower($form->getValue('dealer'))),				
				);
				
				$table->insert($data);
				
				
			 }// end of Add 
	 		$this->_redirect('admin/dealer');

		} // End of isValid
	}// End of Request 

}
public function dealerdeleteAction(){
	/**
	 * Paolo Marco Manarang
	 * April 19,2009
	 * Function to delete the dealer 
	 * 
	 */
	$this->_helper->viewRenderer->setNoRender(true);
	
	$id = $this->_getParam('id');
	$table = new Model_ListDealer();
	
	$where = "id =".$id;
	$table->delete($where);
	$this->_redirect('admin/dealer');
	
	
}
public function weightmaintenanceAction(){
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
	$this->_helper->viewRenderer('weightmaintenance/weightmaintenance-index'); 
	/***
	 * Paolo Marco M. Manarang
	 * April 05,2010
	 * controller for the weights maintenance
	 ***/	
		if(getUserType() != 'ADMIN'){
			$this->_redirect('/error/denied/');	
		}
	 
	 $form = new Form_Admin_Weights();
	
	if ($handle = opendir(APPLICATION_PATH.'\models')) {
    //echo "Directory handle: $handle\n";
    // echo "Files:\n";
    /* This is the correct way to loop over the directory. */
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            if(strpos($file,'.tmp')=== false){
			$filex = str_replace('.php','',$file); 
			 $form->tables->addmultiOption($filex,$filex);
			}// end of strpos if 
        } // end of if
	} // end of while
   closedir($handle);
	} // end of initial if 
	 
	 $this->view->form = $form;
	 
	 if ($this->getRequest()->isPost()) {
		$formData = $this->getRequest()->getPost();
		if ($form->isValid($formData)) {  
		
		$table = new Model_ChainVehicle();
		if($formData['button'] == 'Select Table'){
			
			$selTable = $form->getValue('tables');
			$string = " \$modelTable = new Model_{$selTable}();";
			eval($string);
			$select = $modelTable->select();
			$detail = $modelTable->fetchRow($select)->toArray();
			$form->fields->addmultiOption(0,'Select Column');
			$cols = array_keys($detail);
			foreach($cols as $name){
			$form->fields->addmultiOption($name,$name);
			}


		}// end of Select Table
		
		} // end of isValid
		}//end of get Request
		
}


public function testpdfAction($x) {

    $this->_helper->layout->disableLayout();
    $this->_helper->viewRenderer->setNoRender();

    $pdf = new Zend_Pdf();
    $page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4_LANDSCAPE);
    $pdf->pages[] = $page;
    $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES), 9);
	
	$id = 43;
	$table = new Model_ChainVehicle();
	$select = $table->select();
	$select->where('id ='.$id);	
	$detail = $table->fetchRow($select);
	//Start of Header
	$page->drawText('LOS Pricelist Generation',10, 570);
	$page->drawText('Generated By: '.$this->view->viewMa($this->view->loggedInUser()),10, 560);
	$page->drawText('Generated Date: '.date('m-d-Y'),10, 550);
	// End of Header
	$page->drawText('By: ',50, 520);
	$page->drawText('Date: ',100, 520);
	$page->drawText('Dealer: ',150, 520);
	$page->drawText('Brand: ',280, 520);
	$page->drawText('Type: ',350, 520);
	$page->drawText('Selling Price: ',400, 520);
	$page->drawText('Unit: ',470, 520);
	
	$page->drawText(ucfirst($detail->approve_by),50, 500);
	$page->drawText($this->view->viewMonths($detail->month,'shortcut').'-'.$detail->year,100, 500);
	$page->drawText(ucfirst($detail->dealer),150, 500);
	$page->drawText($detail->brand,280, 500);
	$page->drawText($detail->type,350, 500);
	$page->drawText('P.'.$detail->selling_price,400, 500);
	$page->drawText($detail->unit,470, 500);
	header("Content-type: application/pdf");
	echo $pdf->render();

    } 




} // End of Admin Controller

function getUser(){
	
	$user = Zend_Auth::getInstance()->getIdentity();
	return $user->username;
	
}
    
function getUserType(){
	$user = Zend_Auth::getInstance()->getIdentity();
	return $user->role_type;
}
    
function randLetter(){
  //$int = rand(0,51);
   $int = rand(0,25);
   
	//$a_z = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $a_z = "abcdefghijklmnopqrstuvwxyz";
   
	$rand_letter = $a_z[$int];
    return $rand_letter;

}







