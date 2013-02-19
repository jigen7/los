<?php

class EdocsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
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
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()) {
   	 	$formData = $this->getRequest()->getPost();
	    	if ($form->isValid($formData)) {
	    		

	 	$capno =  $form->getValue('capno');
		$borrower = $form->getValue('borrower_lname');
				
		$table = new Model_BorrowerAccount;
		//for marketing
		//$user = Zend_Auth::getInstance()->getIdentity();
		$select = $table->select();
			$select->where('capno like ?',$capno.'%')
			->where ('borrower_lname like ?',$borrower.'%')
			->where('relation like ?', 'Principal') // Spouse Add
			->order('capno');
		$rows = $table->fetchAll($select);
				
		
		$this->view->rows = $rows;

				
			}
		}
		
		
    }
	
	    public function accountAction()
    {
        // action body
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
		
		$this->_helper->viewRenderer('account-edit');

		$capno = $this->_getParam('cap');
		$user = Zend_Auth::getInstance()->getIdentity();
		
		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		$select->where('capno like ?',$capno);
		$accntdetail = $accnt->fetchRow($select);
	
		$image = new Model_Images();
		$select = $image ->select();
		$select->where('capno like ?',$capno);
		$select->where('doctab like ?','borrower');
		$select->order('docu_type');
		$imagedetail = $image->fetchAll($select);
		$this->view->imageborrower  = $imagedetail;
	

		$select = $image ->select();
		$select->where('capno like ?',$capno);
		$select->where('doctab like ?','collateral');
		$select->order('docu_type');
		$imagedetail = $image->fetchAll($select);
		$this->view->imagecollateral  = $imagedetail;
		
		$select = $image ->select();
		$select->where('capno like ?',$capno);
		$select->where('doctab like ?','ci');
		$select->order('docu_type');
		$imagedetail = $image->fetchAll($select);
		$this->view->imageci  = $imagedetail;
		
		$select = $image ->select();
		$select->where('capno like ?',$capno);
		$select->where('doctab like ?','cv');
		$select->order('docu_type');
		$imagedetail = $image->fetchAll($select);
		$this->view->imagecv  = $imagedetail;
		
		$select = $image ->select();
		$select->where('capno like ?',$capno);
		$select->where('doctab like ?','employ');
		$select->order('docu_type');
		$imagedetail = $image->fetchAll($select);
		$this->view->imageemploy  = $imagedetail;
		
		$select = $image ->select();
		$select->where('capno like ?',$capno);
		$select->where('doctab like ?','others');
		$select->order('docu_type');
		$imagedetail = $image->fetchAll($select);
		$this->view->imageothers  = $imagedetail;
		
		$form = new Form_EdocsPage();
	
		$this->view->form = $form;
		$this->view->capno = $capno;
		$this->view->accntdetail = $accntdetail;
	
		if ($this->getRequest()->isPost()) {
   	 	$formData = $this->getRequest()->getPost();
	    	if ($form->isValid($formData)) {
	    		
		$capno = $this->_getParam('cap');
		$uploadedData = $form->getValues();

		if ($formData['hiddentype'] == 'borrower'){
			$doctab = 'borrower';
			$docutype = $uploadedData['sel_doc_borrower'];
			}
		else if ($formData['hiddentype'] == 'collateral'){
			$doctab = 'collateral';
			$docutype = $uploadedData['sel_doc_collateral'];
			}
		else if ($formData['hiddentype'] == 'cv'){
			$doctab = 'cv';
			$docutype = $uploadedData['sel_doc_cv'];
			}
		else if ($formData['hiddentype'] == 'ci'){
			$doctab = 'ci';
			$docutype = $uploadedData['sel_doc_ci'];
			}
		else if ($formData['hiddentype'] == 'others'){
			$doctab = 'others';
			$docutype = $uploadedData['sel_doc_others'];
			}
		else if ($formData['hiddentype'] == 'employ'){
			$doctab = 'employ';
			$docutype = $uploadedData['sel_doc_employ'];
			}
		
		$host = $this->_helper->EdocsHelper->config('host');
		$dbname = $this->_helper->EdocsHelper->config('dbname');
		$username = $this->_helper->EdocsHelper->config('user');
		$pass = $this->_helper->EdocsHelper->config('password');		
		$dbconn = pg_connect('host='.$host.' dbname='.$dbname.' user='.$username.' password='.$pass);

        //Zend_Debug::dump($uploadedData, '$uploadedData');
    
		// Read in a binary file
		$data = file_get_contents('tmpfiles/'.$uploadedData['file']);
		// Escape the binary data
		$escaped = pg_escape_bytea($data);
		
		$date = date('r');
		$ext = substr($uploadedData['file'], strrpos($uploadedData['file'], '.') );
		
		$name = strtoupper($uploadedData['desc']);
		
		if ($ext == '.jpg' || $ext == '.JPG'){
		//Create Thumbnails
		$thumb = new Model_Thumbnail('tmpfiles/'.$uploadedData['file']);
		$thumb->resize(100, 100);
		$thumb->save('tmpfiles/thumb_'.$uploadedData['file'], 100);
		
		//End of Create Thumbnails
		
		$type = 'img';
		//Read binary thumb File
		$data2 = file_get_contents('tmpfiles/thumb_'.$uploadedData['file']);
		$thumbpic = pg_escape_bytea($data2);
				
		// Insert it into the database
		pg_query("INSERT INTO borrower_documents_images (description, image, image_thumb,capno,filetype,docu_type,uploaded_date,doctab,uploaded_by) VALUES ('$name', '{$escaped}','{$thumbpic}',$capno,'$type','$docutype','$date','$doctab','$user->username')");
		
		//Delete Temp Files
		unlink('tmpfiles/'.$uploadedData['file']);
		unlink('tmpfiles/thumb_'.$uploadedData['file']);
		}
	
		else {
		$type = 'pdf';
		pg_query("INSERT INTO borrower_documents_images (description, image,capno,filetype,uploaded_date,docu_type,uploaded_by,doctab) VALUES ('$name', '{$escaped}',$capno,'$type','$date','$docutype','$user->username','$doctab')");
		unlink('tmpfiles/'.$uploadedData['file']);
		}
		$this->_redirect('edocs/account/cap/'.$capno);
			
			} //End of IsValid
		else {$form->populate($formData);}
		} // End of Request

    }//End of Edocs Account Action
	
	  public function accountviewAction()
    {
        // action body
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/hideshowdiv.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery-1.3.2.min.js');
	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/tabber.js');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/dropdown.css');
        $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/tabber.css');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/style-input.css');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/default-style.css');
		
		$this->_helper->viewRenderer('account-view');

		$capno = $this->_getParam('cap');
		$user = Zend_Auth::getInstance()->getIdentity();
		
		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		$select->where('capno like ?',$capno);
		$accntdetail = $accnt->fetchRow($select);
	
		$image = new Model_Images();
		$select = $image ->select();
		$select->where('capno like ?',$capno);
		$select->where('doctab like ?','borrower');
		$select->order('docu_type');
		$imagedetail = $image->fetchAll($select);
		$this->view->imageborrower  = $imagedetail;
	

		$select = $image ->select();
		$select->where('capno like ?',$capno);
		$select->where('doctab like ?','collateral');
		$select->order('docu_type');
		$imagedetail = $image->fetchAll($select);
		$this->view->imagecollateral  = $imagedetail;
		
		$select = $image ->select();
		$select->where('capno like ?',$capno);
		$select->where('doctab like ?','ci');
		$select->order('docu_type');
		$imagedetail = $image->fetchAll($select);
		$this->view->imageci  = $imagedetail;
		
		$select = $image ->select();
		$select->where('capno like ?',$capno);
		$select->where('doctab like ?','cv');
		$select->order('docu_type');
		$imagedetail = $image->fetchAll($select);
		$this->view->imagecv  = $imagedetail;
		
		$select = $image ->select();
		$select->where('capno like ?',$capno);
		$select->where('doctab like ?','employ');
		$select->order('docu_type');
		$imagedetail = $image->fetchAll($select);
		$this->view->imageemploy  = $imagedetail;
		
		$select = $image ->select();
		$select->where('capno like ?',$capno);
		$select->where('doctab like ?','others');
		$select->order('docu_type');
		$imagedetail = $image->fetchAll($select);
		$this->view->imageothers  = $imagedetail;
		
		$form = new Form_EdocsPage();
	
		$this->view->form = $form;
		$this->view->capno = $capno;
		$this->view->accntdetail = $accntdetail;
	
		

    }//End of Edocs Account Action
	
	
	  public function showAction(){
	$this->_helper->layout->disableLayout();
	$this->_helper->viewRenderer->setNoRender(true);
	
	$id = $this->_getParam('id', 0);
	$type = $this->_getParam('type', 0);
		$host = $this->_helper->EdocsHelper->config('host');
		$dbname = $this->_helper->EdocsHelper->config('dbname');
		$user = $this->_helper->EdocsHelper->config('user');
		$pass = $this->_helper->EdocsHelper->config('password');		
		$dbconn = pg_connect('host='.$host.' dbname='.$dbname.' user='.$user.' password='.$pass);

	// Get the bytea data
	pg_query('SET bytea_output = "escape";');
	$res = pg_query("SELECT image FROM borrower_documents_images WHERE id = '$id'");  
	$raw = pg_fetch_result($res, 'image');
	
	 if ($type == 'img') {
	// Convert to binary and send to the browser
	header('Content-type: image/jpeg'); } 
	else if ($type == 'pdf') {
	header('Content-type: application/pdf'); } 
	echo pg_unescape_bytea($raw);
	
    }
	
	 public function showthumbAction(){
	$this->_helper->layout->disableLayout();
	$this->_helper->viewRenderer->setNoRender(true);
	
	$id = $this->_getParam('id', 0);
		$host = $this->_helper->EdocsHelper->config('host');
		$dbname = $this->_helper->EdocsHelper->config('dbname');
		$user = $this->_helper->EdocsHelper->config('user');
		$pass = $this->_helper->EdocsHelper->config('password');		
		$dbconn = pg_connect('host='.$host.' dbname='.$dbname.' user='.$user.' password='.$pass);

	// Get the bytea data
	pg_query('SET bytea_output = "escape";');
	$res = pg_query("SELECT image_thumb FROM borrower_documents_images WHERE id = '$id'");  
	$raw = pg_fetch_result($res, 'image_thumb');
	
	 
	// Convert to binary and send to the browser
	header('Content-type: image/jpeg');
	
	echo pg_unescape_bytea($raw);
	
    }
	
public function deleteAction() {
    $this->_helper->layout->disableLayout();
	$this->_helper->viewRenderer->setNoRender(true);

	$id = $this->_getParam('id', 0);
	$capno = $this->_getParam('cap');

	$table = new Model_Images();
	$where = $table->getAdapter()->quoteInto('id = ?', $id);
	$table->delete($where);
	
	$this->_redirect('edocs/account/cap/'.$capno);
    }


}

