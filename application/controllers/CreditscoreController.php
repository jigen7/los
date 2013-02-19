<?php

class CreditscoreController extends Zend_Controller_Action
{

    public function init(){/* Initialize action controller here */}
	
	public function preDispatch()
    {
   	 	$this->_helper->switchSsl();
        if (Zend_Auth::getInstance()->hasIdentity()) {}
		else{ $this->_helper->redirector('login','auth');}
		$this->_helper->RoleAccess();
    }
	
	public function homeAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_homepage');
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/creditscore/prototype.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/creditscore/effects.js');
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/creditscore/accordion.js');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/creditscore/home.css');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/creditscore/accordion.css');			
		
		$this->_helper->UpdateStatusHelper(); //update the status of models everytime the home page loads
		$this->view->sesid = date("mdyHis");
		Zend_Session::namespaceUnset('select');
	
		$dFrom = date("Y-m-d H:i:s", strtotime("2010-08-08 17:29:59"));
		$dTo = date("Y-m-d H:i:s", strtotime("2010-09-08 08:30:00"));

		$this->_helper->Levenshtein("delllllllll Cruz", "dela Cruz");
		echo $this->_helper->NoSatSun($dFrom, $dTo);		
/*		$this->_helper->ScoreMatrix('O C Scoring','379000.00');
		$this->_helper->ScoreMatrix('E 123 P2','379000.00');
		$this->_helper->ScoreMatrix('E 123 F3 D','1000001.00');
		$this->_helper->ScoreMatrix('E 123 F3','759000.00');
		$this->_helper->ScoreMatrix('E 123 P1','2499999.00');
		$this->_helper->ScoreMatrix('E 123 P1','4000000.00');
*//*		$arr = array('1100108231000200','1100208231000300','1100308231000400','1100408231000500','1100508231000600');
		for($i = 0; $i != count($arr); $i++){
			//$this->_helper->ModelSelectionHelper->selectModel($arr[$i]);	
			//$rowArr = $this->_helper->ScoreModule2->storeattr($arr[$i],'single');
			$this->_helper->CreditScore->scoreIndividualAccount($arr[$i],'','single');	
		}
*/	}

 	public function addmodelAction()
    {
		Zend_Layout::getMvcInstance()->assign('usedojo', 'true');			
		$this->_helper->RolePermissionHelper('creditscore_addmodel');	
 	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
  	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
				
		$username = $this->_helper->GetUsername();
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - ADD MODEL";
		$this->view->headTitle($this->view->title);
			
		$form = new Form_Creditscore_AddModel();
		$form->submit->setLabel('Define Filters');
		$this->view->form = $form;
		
		$csmodel = new Model_Creditscore_CSModel();
		$ahmodel = new Model_Creditscore_Accounthistory();
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {			
			$isNameInvalid = $csmodel->checkNameVer($formData['modelName'], $prodType);
			$isDateInvalid = $csmodel->checkDate($formData['dateFrom'], $formData['dateTo']);
			if($isNameInvalid === true || $isDateInvalid === true){
				$form->populate($formData);
				$this->view->errorModelName = ($isNameInvalid)? "Model exist": "";
				$this->view->errorDateName = ($isDateInvalid)? "Please check your date": "";
			}else{
				$ahmodel->setAccountHistory(strtolower($formData['modelName']), 1, 'CRA Add New Model', $username,'none');		
				$csmodel->addModel(strtolower($formData['modelName']), $username, $formData['dateFrom'], $formData['dateTo'], $prodType);				
				$this->_redirect('/creditscore/filters/prod/'.$prodType.'/namever/'.strtolower($formData['modelName']).' 1'.'/place/addmodeldefinefields');	
			}					
		}else{$form->populate($formData);}
	}  
	 
  	public function addmodeldefinefieldsAction()
    {
		$this->_helper->RolePermissionHelper('creditscore_addmodeldefinefields');	
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$username = $this->_helper->GetUsername();
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		$place = $this->_getParam('place');
		$this->view->place = $place;		
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - ADD MODEL - DEFINE FIELDS";
		$this->view->headTitle($this->view->title);
		
		$form = new Form_Creditscore_ModelDefineFields();
		$csmodel = new Model_Creditscore_CSModel();
		$ahmodel = new Model_Creditscore_Accounthistory();
		$fsmodel = new Model_Creditscore_Fieldsselected();
	
		$this->view->fields = $this->_helper->DefineFieldSelectHelper();
		$this->view->afields = $fsmodel->addedCNFields($namever);
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {		
			if($formData['button'] == "Add"){				
				$this->_helper->DefineFieldHelper($formData['fields'], $namever, $prodType, $username);
				$this->view->afields = $fsmodel->addedCNFields($namever);	
			}else if($formData['button'] == "Define Weights"){
				$this->_helper->AccountHistoryHelper($namever, $prodType, $username, 'CRA Encodes', '');	
				$this->_redirect('/creditscore/addmodeldefineweights/prod/'.$prodType.'/namever/'.$namever.'/place/'.$place);		
			}		
		}else{$form->populate($formData);}
	}     
    
  	public function addmodeldefineweightsAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_addmodeldefineweights');    	
  	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js'); 
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$username = $this->_helper->GetUsername();
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		$place = $this->_getParam('place');
		$this->view->place = $place;			
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - ADD MODEL - DEFINE WEIGHTS";
		$this->view->headTitle($this->view->title);

		$form = new Form_Creditscore_ModelDefineWeights();
		$this->view->form = $form;

		$csmodel = new Model_Creditscore_CSModel();	
		$ahmodel = new Model_Creditscore_Accounthistory();
		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();
		$mcmodel = new Model_Creditscore_ModelFieldsCategory();

		$this->view->fieldscat = $mcmodel->getFieldsAndAttrib($namever);
		$this->view->fieldsnum = $mnmodel->getFieldsAndAttrib($namever);
	
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			if($formData['button'] == "Submit"){
				$this->_helper->CWeightHelper($formData, $namever, $prodType, $username);
				if($this->_helper->RangeHelper($formData)){
					$this->_helper->NWeightHelper($formData, $namever, $prodType, $username);
					$csmodel->setApprovalModel($namever);
					$csrow = $csmodel->getModel($namever, $prodType);
					$ahmodel->setAccountHistory($csrow->name, 1, 'CRA Submit', $username,'none');
					$this->_redirect('/creditscore/home/');						
				}else{
					$this->view->errorAttribute = "Please check the range!";	
				}	
			}else{	
				$this->_helper->CWeightHelper($formData, $namever, $prodType, $username);
				if($this->_helper->RangeHelper($formData)){
					$this->_helper->NWeightHelper($formData, $namever, $prodType, $username);
					$this->_helper->RepDelHelper($formData, $prodType, $username);					
					$csmodel->setEdit($namever);
					$ahmodel->updateAccountHistory($namever, $prodType, 'CRA Encodes');	
				}else{
					$this->view->errorAttribute = "Please check the range!";	
				}				
				$this->view->fieldscat = $mcmodel->getFieldsAndAttrib($namever);
				$this->view->fieldsnum = $mnmodel->getFieldsAndAttrib($namever);
			}
		}else{$form->populate($formData);} 
	} 

	public function editmodelAction()
	{
		Zend_Layout::getMvcInstance()->assign('usedojo', 'true');		
		$this->_helper->RolePermissionHelper('creditscore_editmodel');				
	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
  	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');		
		
		$username = $this->_helper->GetUsername();
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;	
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		$place = $this->_getParam('place');
		$this->view->place = $place;
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - EDIT MODEL";
		$this->view->headTitle($this->view->title);
		
		$form = new Form_Creditscore_EditModel();
		$form->submit->setLabel('EDIT FILTERS');
		$this->view->form = $form;
			
		$csmodel = new Model_Creditscore_CSModel();	
		$atmodel = new Model_Creditscore_AuditTrail();
		$ahmodel = new Model_Creditscore_Accounthistory();
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			$modelName = $formData['name'];
			$dateFrom = $formData['vpfrom'];
			$dateTo = $formData['vpto'];
			$dateFrom = date("Y-m-d", strtotime($dateFrom));
			$dateTo = date("Y-m-d", strtotime($dateTo));
		
			$isDateInvalid = $csmodel->checkDate($dateFrom, $dateTo);
			$formData = $this->getRequest()->getPost();			

			if($isDateInvalid === true){
				$form->populate($formData);
				$this->view->errorDateName = ($isDateInvalid)? "Please check your date": "";
			}else{
				$csrow = $csmodel->getModel($namever, $prodType);	
				if($csrow->name != $modelName){ 
					$isNameInvalid = $csmodel->checkNameVer($modelName, $prodType);
					if($isNameInvalid === true){
						$this->view->errorModelName = ($isNameInvalid)? "Model exist": "";
					}else{
						$atmodel->changesAuditTrail($modelName, $csrow->version, $username, 'Model Name', 'Renamed', $csrow->name, $modelName);
						$this->_helper->RenameHelper($namever, $modelName);
						$namever = $modelName.' '.$csrow->version;
						$csmodel->setEditModel($csrow->id, $modelName, $username, $dateFrom, $dateTo, $businessCenter, $regularPromo, $prodType);				
						$csrow = $csmodel->getModel($namever, $prodType);
					}
				}
				if($csrow->vpfrom != $dateFrom){ $atmodel->changesAuditTrail($modelName, $csrow->version, $username, 'Date From', 'Date changed', $csrow->vpfrom, $dateFrom);	}		
				if($csrow->vpto != $dateTo){ $atmodel->changesAuditTrail($modelName, $csrow->version, $username, 'Date To', 'Date changed', $csrow->vpto, $dateTo);}			
				
				if($csrow->status == 'RTS'){$ahmodel->setAccountHistory($csrow->name, $csrow->version, 'CRA Encodes', $username,'none');}
				else{$this->_helper->AccountHistoryHelper($namever, $prodType, $username, 'CRA Encodes', '');}
				$csmodel->setEditModel($csrow->id, $modelName, $username, $dateFrom, $dateTo, $prodType);										
				$this->_redirect('/creditscore/filters/prod/'.$prodType.'/namever/'.strtolower($modelName).' 1'.'/place/editmodeldefinefields');					
			}
		}else{
			$csrow = $csmodel->getModel($namever, $prodType);
			$this->view->name = $form->name->setValue($csrow->name);
			$this->view->vpfrom = $form->vpfrom->setValue(date("m/d/Y", strtotime($csrow->vpfrom)));
			$this->view->vpto = $form->vpto->setValue(date("m/d/Y", strtotime($csrow->vpto)));
		}  			
	}

  	public function editmodeldefinefieldsAction()
    {
		$this->_helper->RolePermissionHelper('creditscore_editmodeldefinefields');						
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$username = $this->_helper->GetUsername();
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		$place = $this->_getParam('place');
		$this->view->place = $place;				
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - EDIT MODEL - EDIT FIELDS";
		$this->view->headTitle($this->view->title);

		$form = new Form_Creditscore_ModelDefineFields();
		$ahmodel = new Model_Creditscore_Accounthistory();	
		$fsmodel = new Model_Creditscore_Fieldsselected();
		
		$this->view->fields = $this->_helper->DefineFieldSelectHelper();
		$this->view->afields = $fsmodel->addedCNFields($namever);

		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {	
			if($formData['button'] == "Add"){				
				$this->_helper->DefineFieldHelper($formData['fields'], $namever, $prodType, $username);
				$this->view->afields = $fsmodel->addedCNFields($namever);									
			}else if($formData['button'] == "Edit Weights"){
				$ahmodel->updateAccountHistory($namever, $prodType, 'CRA Encodes');	
				$this->_redirect('/creditscore/editmodeldefineweights/prod/'.$prodType.'/namever/'.$namever.'/place/'.$place);	
			}
		}else{$form->populate($formData);}   
	} 
	
  	public function editmodeldefineweightsAction()
    {
		$this->_helper->RolePermissionHelper('creditscore_editmodeldefineweights');								
  	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js'); 		
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$username = $this->_helper->GetUsername(); 
 		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;		
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		$place = $this->_getParam('place');
		$this->view->place = $place;		
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - EDIT MODEL - EDIT WEIGHTS";
		$this->view->headTitle($this->view->title);

		$form = new Form_Creditscore_ModelDefineWeights();
		$this->view->form = $form;
		
		$csmodel = new Model_Creditscore_CSModel();	
		$ahmodel = new Model_Creditscore_Accounthistory();
		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();
		$mcmodel = new Model_Creditscore_ModelFieldsCategory();

		$this->view->fieldscat = $mcmodel->getFieldsAndAttrib($namever);
		$this->view->fieldsnum = $mnmodel->getFieldsAndAttrib($namever);

		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			if($formData['button'] == "Submit"){
				$this->_helper->CWeightHelper($formData, $namever, $prodType, $username);
				if($this->_helper->RangeHelper($formData)){
					$this->_helper->NWeightHelper($formData, $namever, $prodType, $username);
					$csmodel->setApprovalModel($namever);
					$csrow = $csmodel->getModel($namever, $prodType);
					$ahmodel->setAccountHistory($csrow->name, 1, 'CRA Submit', $username,'none');
					$this->_redirect('/creditscore/home/');		
				}else{
					$this->view->errorAttribute = "Please check the range!";	
				}			
			}else{
				$this->_helper->CWeightHelper($formData, $namever, $prodType, $username);
				if($this->_helper->RangeHelper($formData)){
					$this->_helper->NWeightHelper($formData, $namever, $prodType, $username);
					$this->_helper->RepDelHelper($formData, $prodType, $username);	
					$csmodel->setEdit($namever);
					$ahmodel->updateAccountHistory($namever, $prodType, 'CRA Encodes');	
				}else{
					$this->view->errorAttribute = "Please check the range!";	
				}	
				$this->view->fieldscat = $mcmodel->getFieldsAndAttrib($namever);
				$this->view->fieldsnum = $mnmodel->getFieldsAndAttrib($namever);							
			}
		}else{$form->populate($formData);}  
	}

	public function updatemodelAction()
    {
		$this->_helper->RolePermissionHelper('creditscore_updatemodel');																	
	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
  	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');		
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - UPDATE MODEL";
		$this->view->headTitle($this->view->title);

		$form = new Form_Creditscore_UpdateModel();
		$form->submit->setLabel('Search');
		$this->view->form = $form;
		
		$csmodel = new Model_Creditscore_CSModel();
		$this->view->table = $csmodel->getUpdateModels($prodType);
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			$this->view->table  = $csmodel->getCurrentModel($formData['search'], $prodType);
		}else{$form->populate($formData);}     
	} 
	
	public function updatemodelspecificAction()
	{
		Zend_Layout::getMvcInstance()->assign('usedojo', 'true');		
		$this->_helper->RolePermissionHelper('creditscore_updatemodelspecific');	
		$username = $this->_helper->GetUsername();
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;				
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - UPDATE MODEL";
		$this->view->headTitle($this->view->title);

		$form = new Form_Creditscore_UpdateModel();
		$form->submit->setLabel('EDIT FILTERS');
		$this->view->form = $form;

		$ahmodel = new Model_Creditscore_Accounthistory();		
		$csmodel = new Model_Creditscore_CSModel();
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			$data = $csmodel->getUpdateModel($namever, $prodType);
			(int)$version = $data['version'];	
			$id = $data['id'];			
			$name = $data['name'];
			$dateFrom = $formData['vpfrom'];
			$dateTo = $formData['vpto'];
			$status = $data['status'];			
			
			$isDateInvalid = $csmodel->checkDate($dateFrom, $dateTo);
			$isModelUpdated = $csmodel->checkIfUpdated($namever, $prodType);
			if($status == 'RTS' || $status == 'EDIT'){
				$arrName = explode(" ",$namever);
				$pVer = (int)$arrName[1];
				$pVer = $pVer - 1;
				$pData = $csmodel->getUpdateModel($arrName[0]." ".$pVer, $prodType);
				$isDateConflict = $csmodel->isDateConflict($pData['vpfrom'],$pData['vpto'],$dateFrom);		
			}else{
				$isDateConflict = $csmodel->isDateConflict($data['vpfrom'],$data['vpto'],$dateFrom);
			} 
			if($isDateInvalid == true || $isModelUpdated == true || $isDateConflict == true)
			{
				$this->view->errorUpdate = ($isModelUpdated)? "Model Updated!" : "";
				$this->view->errorDateName = ($isDateInvalid)? "Please check your date": "";
				$this->view->dateConflict = ($isDateConflict)? "Date Conflict": "";
				
				$csrow = $csmodel->getModel($namever, $prodType);
				$this->view->name = $form->name->setValue($csrow->name);
				$this->view->vpfrom = $form->vpfrom->setValue(date("m/d/Y", strtotime($csrow->vpfrom)));
				$this->view->vpto = $form->vpto->setValue(date("m/d/Y", strtotime($csrow->vpto)));
			}else{
				$name = $data['name'];
				$dateFrom = $formData['vpfrom'];
				$dateTo = $formData['vpto'];

				$csrow = $csmodel->getModel($namever, $prodType);
				if($csrow->status == 'CURRENT'){
					$vers = $version + 1;
					$ahmodel->setAccountHistory($name, $vers, 'CRA Update Model', $username, 'none');							
				}
				else if($csrow->status == 'RTS'){
					$ahmodel->setAccountHistory($name, $version, 'CRA Encodes', $username, 'none');							
				}
				else if($csrow->status == 'EDIT'){
					$ahmodel->updateAccountHistory($namever, $prodType, 'CRA Encodes');							
				}
				if($status == 'RTS' || $status == 'EDIT'){
					$csmodel->setRTSModel($csrow->id, $namever, $username, $dateFrom, $dateTo, $prodType);				
					$this->_redirect('/creditscore/filters/prod/'.$prodType.'/namever/'.$namever.'/place/updatemodeldefinefields');									
				}else{
					$csmodel->setCURWUPD($id);
					$csmodel->setUpdateModel($name, $regpro, $dateTo, $version, $id, $prodType);			
					$this->_redirect('/creditscore/copyoldversion/prod/'.$prodType.'/namever/'.$name." ".++$version.'/place/updatemodeldefinefields');												
				}			
			}
		}else{
			$csrow = $csmodel->getModel($namever, $prodType);
			$this->view->name = $form->name->setValue($csrow->name);
			$this->view->vpfrom = $form->vpfrom->setValue(date("m/d/Y", strtotime($csrow->vpfrom)));
			$this->view->vpto = $form->vpto->setValue(date("m/d/Y", strtotime($csrow->vpto)));
			$this->view->statss = $csrow->status;
		}     	
	}

  	public function updatemodeldefinefieldsAction()
    {
		$this->_helper->RolePermissionHelper('creditscore_updatemodeldefinefields');																		
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$username = $this->_helper->GetUsername();
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;	
		$place = $this->_getParam('place');
		$this->view->place = $place;				
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - UPDATE MODEL - EDIT FIELDS";
		$this->view->headTitle($this->view->title);

		$form = new Form_Creditscore_ModelDefineFields();
		$csmodel = new Model_Creditscore_CSModel();
		$fsmodel = new Model_Creditscore_Fieldsselected();	

		$this->view->fields = $this->_helper->DefineFieldSelectHelper();
		$this->view->afields = $fsmodel->addedCNFields($namever);

		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {			
			if($formData['button'] == "Add"){				
				$this->_helper->DefineFieldHelper($formData['fields'], $namever, $prodType, $username);
				$this->view->afields = $fsmodel->addedCNFields($namever);		
			}else if($formData['button'] == "Edit Weights"){
				$this->_helper->AccountHistoryHelper($namever, $prodType, $username, 'CRA Encodes', 'none');
				$this->_redirect('/creditscore/updatemodeldefineweights/prod/'.$prodType.'/namever/'.$namever.'/place/'.$place);	
			}			
		}else{$form->populate($formData);}      
	} 
	
  	public function updatemodeldefineweightsAction()
    {
		$this->_helper->RolePermissionHelper('creditscore_updatemodeldefineweights');																			
  	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js'); 		
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$username = $this->_helper->GetUsername();
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		$place = $this->_getParam('place');
		$this->view->place = $place;					
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - UPDATE MODEL - EDIT WEIGHT";
		$this->view->headTitle($this->view->title);

		$form = new Form_Creditscore_ModelDefineWeights();
		$this->view->form = $form;

		$csmodel = new Model_Creditscore_CSModel();
		$ahmodel = new Model_Creditscore_Accounthistory();
		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();
		$mcmodel = new Model_Creditscore_ModelFieldsCategory();

		$this->view->fieldscat = $mcmodel->getFieldsAndAttrib($namever);
		$this->view->fieldsnum = $mnmodel->getFieldsAndAttrib($namever);

		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			if($formData['button'] == "Submit"){	
				$this->_helper->CUpdateHelper($formData, $namever, $prodType, $username);
				if($this->_helper->RangeHelper($formData)){
					$this->_helper->NUpdateHelper($formData, $namever, $prodType, $username);
					$nameField = explode(" ",$namever);	
					$id = $nameField[1]; $id = (int)$id; $iddec = $id - 1;
					$csrow = $csmodel->getModel($nameField[0]." ".$iddec, $prodType);			
					$csmodel->setCURWUPD($csrow->id);
					$csmodel->setApprovalModel($namever);
					$ahmodel->setAccountHistory($nameField[0], $id, 'CRA Submit', $username, 'none');							
					$this->_redirect('/creditscore/home/');		
				}else{
					$this->view->errorAttribute = "Please check the range!";	
				}							
			}else{	
				$this->_helper->CUpdateHelper($formData, $namever, $prodType, $username);
				if($this->_helper->RangeHelper($formData)){
					$this->_helper->NUpdateHelper($formData, $namever, $prodType, $username);
					$this->_helper->RepDelHelper($formData, $prodType, $username);		
					$csmodel->setEdit($namever);
					$ahmodel->updateAccountHistory($namever, $prodType, 'CRA Encodes');	
				}else{
					$this->view->errorAttribute = "Please check the range!";	
				}
				$this->view->fieldscat = $mcmodel->getFieldsAndAttrib($namever);
				$this->view->fieldsnum = $mnmodel->getFieldsAndAttrib($namever);														
			}	
		}else{$form->populate($formData);}     
	}

	public function filtersAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_filters');	
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$username = $this->_helper->GetUsername();
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		$place = $this->_getParam('place');
		$this->view->place = $this->_helper->ReturnHelper('filters', $place);
		$setPlace = $this->_helper->DefineTitleHelper('filters', $place);
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - ".$setPlace." FILTERS";
		$this->view->headTitle($this->view->title);
		
		$form = new Form_Creditscore_BusinessCenters();
		$atmodel = new Model_Creditscore_Audittrail();
		$bcmmodel = new Model_Creditscore_BusinessCentersModel();
		$rpmmodel = new Model_Creditscore_RegularPromoModel();
	
		$this->view->busctr = $this->_helper->BusinessCenterSelectHelper();
		$this->view->busctrs = $bcmmodel->addedBusinessCenter($namever);
		$this->view->regpro = $this->_helper->RegularPromoSelectHelper();
		$this->view->regpros = $rpmmodel->addedRegularPromo($namever);
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {		
			$this->_helper->FilterHelper($formData, $namever, $username, $prodType);
			$this->view->busctrs = $bcmmodel->addedBusinessCenter($namever);
			$this->view->regpros = $rpmmodel->addedRegularPromo($namever);
			$this->_helper->AccountHistoryHelper($namever, $prodType, $username, 'CRA Encodes', '');	
			if($formData['button'] == "Define Fields" || $formData['button'] == "Edit Fields"){
				$this->_redirect('/creditscore/'.$place.'/prod/'.$prodType.'/namever/'.$namever.'/place/'.$place);		
			}
		}else{$form->populate($formData);}	
	}	 	    

	public function rulesAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_rules');
	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
  	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');		
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		$place = $this->_getParam('place');
		$this->view->place = $place;
		$title = $this->_helper->DefineTitleHelper('rules', $place);
		$this->view->title = "OCS - ".strtoupper($prodType)." - ".$title." - ".strtoupper($namever);		
		$this->view->headTitle($this->view->title);
		$this->view->weights = $this->_helper->DefineTitleHelper('weights', $place);

		$form = new Form_Creditscore_Rules();
		$this->view->form = $form;
		
		$rtmodel = new Model_Creditscore_RulesTable();
		$this->view->rules = $rtmodel->getRules($namever);

		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			$rule = $formData['hRuleName'];
			$desc = $formData['hRuleDesc'];
			
			if($rtmodel->checkRule($namever, $rule))
				$rtmodel->addRule($namever, $rule, $desc);
			$this->view->rules = $rtmodel->getRules($namever);
		}else{$form->populate($formData);} 	
	}
	
	public function ocsAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_ocs');
	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
  	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');		
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		$rule = $this->_getParam('rule');
		$this->view->rule = $rule;
		$place = $this->_getParam('place');
		$this->view->place = $place;
		$title = $this->_helper->DefineTitleHelper('ocs', $place);		
		$this->view->title = "OCS - ".strtoupper($prodType)." - ".$title." - ".strtoupper($namever);
		$this->view->headTitle($this->view->title);

		$otmodel = new Model_Creditscore_OCSTable();		
		$form1 = new Form_Creditscore_OCS();
		$this->view->form = $form1;

		$form = $this->_helper->OCSSelectHelper('','first');
		$this->view->cfields = $form['cfields'];
		$this->view->nfields1 = $form['nfields1'];
		$this->view->nfields2 = $form['nfields2'];
		$this->view->octable = $otmodel->getCatNum($namever, 'String', $rule);		
		$this->view->ontable = $otmodel->getCatNum($namever, 'Numeric', $rule);
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			$this->view->checkInput = $this->_helper->OCSHelper($formData, $namever, $rule);

			$form = $this->_helper->OCSSelectHelper($formData['hiddenField1'],'second');
			$this->view->attribs = $form['attribs'];	
			$this->view->cfields = $form['cfields'];
			$this->view->nfields1 = $form['nfields1'];
			$this->view->nfields2 = $form['nfields2'];
			$this->view->octable = $otmodel->getCatNum($namever, 'String', $rule);		
			$this->view->ontable = $otmodel->getCatNum($namever, 'Numeric', $rule);					
		}else{$form1->populate($formData);} 
	}		

	public function approvemodelAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_approvemodel');
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

 		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - APPROVE MODEL";
		$this->view->headTitle($this->view->title);
		
		$form = new Form_Creditscore_ApproveModel();
		$form->search->setLabel('Search');
		$this->view->form = $form;

		$csmodel = new Model_Creditscore_CSModel();
		$this->view->table = $csmodel->getApprovalModels($prodType);

		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			$this->view->table = $csmodel->searchApprovalModel($formData['modelName'], $prodType);
		}else{$form->populate($formData);} 		
	}	
		
	public function approvemodelspecificAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_approvemodelspecific');
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$username = $this->_helper->GetUsername();
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;	
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;	
		$place = $this->_getParam('place');
		$this->view->place = $place;				
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - APPROVE MODEL";
		$this->view->headTitle($this->view->title);
		
		$csmodel = new Model_Creditscore_CSModel();
		$csrow = $csmodel->getModel($namever, $prodType);
		$this->view->name = $csrow->name;
		$this->view->vpfrom = $csrow->vpfrom;
		$this->view->vpto = $csrow->vpto;
		$this->view->busctr = $csrow->busctr;
		$this->view->regpro = $csrow->regpro;	
		$this->view->version = $csrow->version;
		$this->view->status = $csrow->status;
		
		$fsmodel = new Model_Creditscore_Fieldsselected();
		$this->view->afields = $fsmodel->viewModelSpecific($namever);
		$this->_helper->AccountHistoryHelper($namever, $prodType, $username, 'CRO Reviews', 'none');
	}
	
	public function returntocraAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_returntocra');	
		$username = $this->_helper->GetUsername();
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;	
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;

		$form = new Form_Creditscore_Remarks();
		$this->view->form = $form;
		
		$csmodel = new Model_Creditscore_CSModel();
		$ahmodel = new Model_Creditscore_Accounthistory();
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()){	
			$csrow = $csmodel->getModel($namever, $prodType);
			$remark = $formData['remark'];		
			$csmodel->updateRemark($namever, $prodType, $remark);		
			$ahmodel->setAccountHistory($csrow->name, $csrow->version, 'CRO Return to CRA', $username,'none');							
			$ahmodel->remarkAccountHistory($namever, $prodType, 'CRO Return to CRA', $remark);
		}else{$form->populate($formData);} 	 
	}
	
	public function returnapproveAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_returnapprove');	
		$username = $this->_helper->GetUsername();
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;	
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;	
		
		$form = new Form_Creditscore_Remarks();
		$this->view->form = $form;
		
		$csmodel = new Model_Creditscore_CSModel();
		$ahmodel = new Model_Creditscore_Accounthistory();

		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			$remark = $formData['remark'];
			$csmodel->setApprove($namever, $prodType, $remark);	
			$csrow = $csmodel->getModel($namever, $prodType);
			
			$arrName = explode(" ", $namever);
			$version = $arrName[1];
			$version = (int)$version;
			if($version > 1){
				$version = $version - 1;				
				$csrow2 = $csmodel->getModel($arrName[0]." ".$version, $prodType);
				$csmodel->setUpdateDate($csrow2->id, $csrow->vpfrom);					
			}
			$ahmodel->setAccountHistory($csrow->name, $csrow->version, 'CRO Approved', $username,'none');							
			$ahmodel->remarkAccountHistory($namever, $prodType, 'CRO Approved', $remark);
		} 	
	}
		
	public function accounthistoryAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_accounthistory');		
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');
 
 		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;	
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		$place = $this->_getParam('place');
		$this->view->place = $place;		
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - ACCOUNT HISTORY - ".strtoupper($namever);
		$this->view->headTitle($this->view->title);
		
		$csmodel = new Model_Creditscore_CSModel();
		$csrow = $csmodel->getModel($namever, $prodType);
		$role = login_user_role();
		$check = ($csrow->status == 'APPROVAL' && ($role == 'CRO' || $role == 'CRAH') && $place == 'approvemodel')? true : false;
		$this->view->specific = ($check)? 'approvemodelspecific' : 'viewmodelspecific';
		
		$acmodel = new Model_Creditscore_AccountHistory();
		$this->view->history = $acmodel->getAccountHistory($namever, $prodType);		
	}
	
	public function audittrailAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_audittrail');		
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;	
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;	
		$place = $this->_getParam('place');
		$this->view->place = $place;		
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - AUDIT TRAIL - ".strtoupper($namever);
		$this->view->headTitle($this->view->title);

		$csmodel = new Model_Creditscore_CSModel();
		$csrow = $csmodel->getModel($namever, $prodType);
		$role = login_user_role();
		$check = ($csrow->status == 'APPROVAL' && ($role == 'CRO' || $role == 'CRAH') && $place == 'approvemodel')? true : false;
		$this->view->specific = ($check)? 'approvemodelspecific' : 'viewmodelspecific';

		$atmodel = new Model_Creditscore_Audittrail();
		$this->view->audit = $atmodel->getAuditTrail($namever, $prodType);
	}	

	public function pendingmodelsAction()
    {
		$this->_helper->RolePermissionHelper('creditscore_pendingmodels');		
	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
  	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');		
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');
 
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;				
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - PENDING MODELS";
		$this->view->headTitle($this->view->title);

		$form = new Form_Creditscore_PendingModels();
		$form->search->setLabel('Search');
		$this->view->form = $form;
		
		$model = new Model_Creditscore_CSModel();
		$this->view->table = $model->getPendingModels($prodType);
	
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {	
			$this->view->table = $model->searchPendingModel($formData['modelName'], $formData['status'], $prodType);		
		}else{$form->populate($formData);}    
	}	
		
	public function viewmodelAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_viewmodel');																					
  		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - VIEW MODEL";
		$this->view->headTitle($this->view->title);

		$form = new Form_Creditscore_ViewModel();
		$form->submit->setLabel('Search');
		$this->view->form = $form;

		$csmodel = new Model_Creditscore_CSModel();
		$this->view->table = $csmodel->getViewModels($prodType);
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {	
			$this->view->table = $csmodel->searchViewModel($formData['modelName'], $formData['status'], $prodType);
		}else{$form->populate($formData);} 	
	}
	
	public function viewmodelspecificAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_viewmodelspecific');																							
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;	
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		$place = $this->_getParam('place');
		$this->view->place = $place;
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - VIEW MODEL";
		$this->view->headTitle($this->view->title); 
		
		$form = new Form_Creditscore_ViewModel();
		$this->view->form = $form;
	
		$csmodel = new Model_Creditscore_CSModel();	
		$table = $csmodel->getModel($namever, $prodType);
		$this->view->namever = $table->namever;
		$this->view->name = $table->name;
		$this->view->vpfrom = $table->vpfrom;
		$this->view->vpto = $table->vpto;
		$this->view->busctr = $table->busctr;
		$this->view->regpro = $table->regpro;
		$this->view->status = $table->status;
		$this->view->version = $table->version;
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			$hiddens = $formData['hidden'];
			$this->view->hidden = $form->hidden->setValue($hiddens);
			$this->view->hiddenFields = $hiddens;	
		} 			
		$fsmodel = new Model_Creditscore_Fieldsselected();
		$this->view->afields = $fsmodel->viewModelSpecific($namever);
	}
	
	public function viewocsAction()
	{
		//$this->_helper->RolePermissionHelper('creditscore_audittrail');		
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;	
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;	
		$place = $this->_getParam('place');
		$this->view->place = $place;		
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - OCS - ".strtoupper($namever);
		$this->view->headTitle($this->view->title);
		
		$allData = array();
		$data = array();
		$otmodel = new Model_Creditscore_OCSTable();
		$rtmodel = new Model_Creditscore_RulesTable();
		$rttable = $rtmodel->getRules($namever);
		foreach($rttable as $rtrow){
			$data['rule'] = $rtrow->rule;
			$data['desc'] = $rtrow->description;
			$rules = array();
			$ottable = $otmodel->getCatNum($namever, 'String', $rtrow->rule);
			foreach($ottable as $otrow){
				$rules[] = $this->_helper->RenameFieldHelper($otrow->field1)." = ".$otrow->value1;
			}
			$ottable = $otmodel->getCatNum($namever, 'Numeric', $rtrow->rule);
			foreach($ottable as $otrow){
				$rules[] = $this->_helper->RenameFieldHelper($otrow->field1)." ".$otrow->compare1." ".$otrow->value1." ".$otrow->logic." ".$this->_helper->RenameFieldHelper($otrow->field2)." ".$otrow->compare2." ".$otrow->value2;
			}
			$data['data'] = $rules;
			$allData[] = $data;
		}
		$this->view->deviation = $allData;
		
		$role = login_user_role();
		$check = ($csrow->status == 'APPROVAL' && ($role == 'CRO' || $role == 'CRAH') && $place == 'approvemodel')? true : false;
		$this->view->specific = ($check)? 'approvemodelspecific' : 'viewmodelspecific';		
	}

 	public function uploadmemoAction()
    {
		$this->_helper->RolePermissionHelper('creditscore_uploadmemo');															
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');
 
 		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$func = $this->_getParam('func');
		$this->view->func = $func;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		
		$this->view->title = "eDOCS - ".strtoupper($prodType)." - UPLOAD MEMO";
		$this->view->prodType = $prodType;	
		$this->view->headTitle($this->view->title);
		
		$form = new Form_Creditscore_UploadMemo();		
		$form->browse->setLabel('Browse');
		$form->upload->setLabel('Upload');
		$this->view->form = $form;

		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			$modelName = $form->getValue('modelName');
			$uploadName = $form->getValue('uploadName');
		}else{$form->populate($formData);}     
	}
	
	public function viewmemoAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_viewmemo');																									
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		$place = $this->_getParam('place');
		$this->view->place = $place;		
		$this->view->title = "eDOCS - ".strtoupper($prodType)." - VIEW MEMO";	
		$this->view->headTitle($this->view->title); 		
		
		$csmodel = new Model_Creditscore_CSModel();
		$csrow = $csmodel->getModel($namever, $prodType);
		$role = login_user_role();
		$check = ($csrow->status == 'APPROVAL' && ($role == 'CRO' || $role == 'CRAH') && $place == 'approvemodel')? true : false;
		$this->view->specific = ($check)? 'approvemodelspecific' : 'viewmodelspecific';
	}	
			
	public function deleteruleAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_deleterule');
		$this->_helper->viewRenderer->setNoRender(true);
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		$place = $this->_getParam('place');
		$this->view->place = $place;		
		$id = $this->_getParam('id');
		
		$rtmodel = new Model_Creditscore_RulesTable();
		$rtrow = $rtmodel->getRule($id);
		$rtmodel->deleteRule($id);

		$otmodel = new Model_Creditscore_OCSTable();
		$otmodel->delWithRule($namever, $rtrow->rule);
		
		$this->_redirect('/creditscore/rules/prod/'.$prodType.'/namever/'.$namever.'/place/'.$place);				
	}	

	public function deletefieldAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_deletefield');		
		$this->_helper->viewRenderer->setNoRender(true);
		$username = $this->_helper->GetUsername();
		$prodType = $this->_getParam('prod');
		$namever = $this->_getParam('namever');
		$func = $this->_getParam('func');
		$nameField = explode("-",$namever);		

		$fsmodel  = new Model_Creditscore_Fieldsselected();
		$fsmodel->deleteCNField($nameField[0], $nameField[1]);
		
		$atmodel = new Model_Creditscore_Audittrail();
		$atmodel->fieldAuditTrail($nameField[0], $prodType, $username, $nameField[1], 'deleted', '');

		$this->_redirect('/creditscore/'.$func.'/prod/'.$prodType.'/namever/'.$nameField[0].'/place/'.$func);	
	}
	
	public function deletecnfieldAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_deletecnfield');		
		$this->_helper->viewRenderer->setNoRender(true);
		$username = $this->_helper->GetUsername();
		$prodType = $this->_getParam('prod');
		$namever = $this->_getParam('namever');
		$func = $this->_getParam('func');
		
		$nameField = explode("-",$namever);		
		$fsmodel  = new Model_Creditscore_Fieldsselected();
		$fsmodel->deleteCNField($nameField[0], $nameField[1]);
		
		$atmodel = new Model_Creditscore_Audittrail();
		$atmodel->fieldAuditTrail($nameField[0], $prodType, $username, $nameField[1], 'deleted', '');

		$this->_redirect('/creditscore/'.$func.'/prod/'.$prodType.'/namever/'.$nameField[0].'/place/'.$func);	
	}	
	
	public function copyoldversionAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$prodType = $this->_getParam('prod');
		$namever = $this->_getParam('namever');
		$place = $this->_getParam('place');
		$nameField = explode(" ",$namever);	
		$name = $nameField[0];
		$ver = $nameField[1];	
		$ver = (int)$ver;

		$fsmodel = new Model_Creditscore_Fieldsselected();
		$mcmodel = new Model_Creditscore_ModelFieldsCategory();
		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();	
		$otmodel = new Model_Creditscore_OCSTable();
		$rtmodel = new Model_Creditscore_RulesTable();
		$bcmodel = new Model_Creditscore_BusinessCentersModel();
		$rpmodel = new Model_Creditscore_RegularPromoModel();
		
		$copy1 = $mcmodel->getFieldsAndAttrib($name." ".--$ver);					
		foreach($copy1 as $row){
			$data = array(
						'namever'	=>	$namever,
						'field'		=>	$row->field,
						'attribute'	=>	$row->attribute,
						'seq'		=> 	$row->seq,
						'wfrom'		=>	$row->wfrom,
						'wto'		=>	$row->wto,
						'namefield'	=>	$row->namefield
					);
			$mcmodel->copyFieldsAndAttrib($data);
		}
		$copy2 = $mnmodel->getFieldsAndAttrib($name." ".$ver);			
		foreach($copy2 as $row){
			$data = array(
						'namever'	=>	$namever,
						'field'		=>	$row->field,
						'attribute'	=>	$row->attribute,
						'wfrom'		=>	$row->wfrom,
						'wto'		=>	$row->wto,
						'rfrom'		=>	$row->rfrom,
						'rto'		=>	$row->rto,
						'namefield'	=>	$row->namefield
					);
			$mnmodel->copyFieldsAndAttrib($data);
		}
		$copy3 = $otmodel->getAllCatNum($name." ".$ver);				
		foreach($copy3 as $row){
			$data = array(
						'namever'	=>	$namever,
						'field1'	=>	$row->field1,
						'compare1'	=>	$row->compare1,
						'value1'	=>	$row->value1,
						'logic'		=>	$row->logic,
						'field2'	=>	$row->field2,
						'compare2'	=>	$row->compare2,
						'value2'	=>	$row->value2,
						'rule'		=>	$row->rule,
						'type'		=>	$row->type				
					);
			$otmodel->copyCatNum($data);
		}
		$copy4 = $rtmodel->getRules($name." ".$ver);		
		foreach($copy4 as $row){
			$data = array(
						'namever'		=>	$namever,
						'rule'			=>	$row->rule,
						'description'	=>	$row->description
					);
			$rtmodel->copyRules($data);
		}	
		$copy5 = $bcmodel->addedBusinessCenter($name." ".$ver);
		foreach($copy5 as $row){
			$data = array(
						'namever'	=>	$namever, 
						'busctr'	=>	$row->busctr,
						'code'		=>	$row->code	
					);
			$bcmodel->copyBusinessCenters($data);
		}	
		$copy6 = $rpmodel->addedRegularPromo($name." ".$ver);
		foreach($copy6 as $row){
			$data = array(
						'namever'	=>	$namever, 
						'regpro'	=>	$row->regpro,
						'code'		=>	$row->code	
					);
			$rpmodel->copyRegularPromo($data);		
		}
		$this->_redirect('/creditscore/filters/prod/'.$prodType.'/namever/'.$namever.'/place/updatemodeldefinefields');								
	}

	public function replicateAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_replicate');						
		$this->_helper->viewRenderer->setNoRender(true);
		$username = $this->_helper->GetUsername();
		$prodType = $this->_getParam('prod');
		$namever = $this->_getParam('namever');
		$func = $this->_getParam('func');
		$nameField = explode("-",$namever);		

		$fsmodel = new Model_Creditscore_Fieldsselected();
		$fsmodel->addNumericField($nameField[1],$nameField[0]);
		
		$this->_redirect('/creditscore/'.$func.'/prod/'.$prodType.'/namever/'.$nameField[0].'/place/'.$func);			
	}
	
	public function deletereplicateAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_deletereplicate');								
		$this->_helper->viewRenderer->setNoRender(true);
		$username = $this->_helper->GetUsername();		
		$prodType = $this->_getParam('prod');
		$namever = $this->_getParam('namever');
		$func = $this->_getParam('func');
		$nameField = explode("-",$namever);		

		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();
		$mnmodel->deletereplicatefield($nameField[1]);
		
		$atmodel = new Model_Creditscore_Audittrail();
		$atmodel->fieldAuditTrail($nameField[0], $prodType, $username, $nameField[1], 'deleted', '');

		$this->_redirect('/creditscore/'.$func.'/prod/'.$prodType.'/namever/'.$nameField[0].'/place/'.$func);	
	}		
	
	public function selectaccountsAction()
	{
		Zend_Layout::getMvcInstance()->assign('usedojo', 'true');		
		$this->_helper->RolePermissionHelper('creditscore_selectaccounts');									
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');
 
 		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$sesid = $this->_getParam('sesid');
		$this->view->sesid = $sesid;		
		$this->view->title = "MULTIPLE SCORING - ".strtoupper($prodType)." - SELECT ACCOUNTS";
		$this->view->headTitle($this->view->title);
 
		$form = new Form_Creditscore_SelectAccounts();
		$form->retrieve->setLabel('Retrieve Accounts');
		$this->view->form = $form;
		
		$this->view->busctr = $this->_helper->BusinessCenterSelectHelper();
		$this->view->regpro = $this->_helper->RegularPromoSelectHelper();		
		
		$sh2model = new Model_Creditscore_ScoreHistory2();
		$shmodel = new Model_Creditscore_ScoreHistory();
		$borrower = new Model_BorrowerAccount();
		
		$sessionSelect = new Zend_Session_Namespace('select');
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			$sesid = ($sesid)? $sesid : date("mdyHis");
			if($formData['button'] == 'Retrieve Accounts'){				
				$sessionSelect->select = $borrower->search($formData['capNo'],$formData['bLastName'],$formData['decision'],
														   $formData['dateFrom'],$formData['dateTo'],$formData['regpro']);		
			}else if($formData['button'] == 'Add Accounts' && !$formData['row']){	
			}else if($formData['button'] == 'Add Accounts' && $formData['row']){		
				foreach($formData['row'] as $key => $value){
					if($sh2model->checkAccount($key, $sesid))
						$sh2model->addAccount($key, $sesid);
				}
			}else{		
				if($sh2model->hasAccounts($sesid)){
					$sh2table = $sh2model->getAccounts($sesid);
					foreach($sh2table as $sh2row){
						if($shmodel->hasAccount($sh2row->capno, $sesid))
							$shmodel->setAccount($sh2row->capno, $sesid);
					}
					$this->_redirect('/creditscore/selectmodels/prod/'.$prodType.'/sesid/'.$sesid);				
				}
			}

		}
		if(!isset($sessionSelect->select)){
			$select = $borrower->select()->where('relation LIKE ?', 'Principal')->limit(100,0);
			$sessionSelect->select = $select;
		} 
		$select = $sessionSelect->select;
		$select = $borrower->fetchAll($select);	
		
		$page=$this->_getParam('page',1);
		$paginator = Zend_Paginator::factory($select);
		$paginator->setItemCountPerPage(10);
		$paginator->setCurrentPageNumber($page);
		$this->view->accounts=$paginator;		
	}		
	
	public function selectaccountsdeleteAction()
	{
		$sesid = $this->_getParam('sesid');
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');
		
		$shmodel = new Model_Creditscore_ScoreHistory();
		$sh2model = new Model_Creditscore_ScoreHistory2();
		$sh2table = $sh2model->getAccounts($sesid);
		$accArr = array();
		foreach($sh2table as $sh2row){
			$accArr[] = $sh2row->capno;
		}
		$batable= array();
		if($accArr){
			$bamodel = new Model_BorrowerAccount();
			$select = $bamodel->select()->where('capno IN (?)', $accArr);	
			$batable = $bamodel->fetchAll($select);		
		}
		$this->view->accounts = $batable;
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			if($formData['button'] == 'Delete'){
				$capno = $formData['hiddenDel'];
				$sh2model->deleteAccount($capno, $sesid);
				$shmodel->deleteAccount($capno, $sesid);
			}
			$batable = array();
			$sh2table = $sh2model->getAccounts($sesid);
			$accArr = array();
			foreach($sh2table as $sh2row){
				$accArr[] = $sh2row->capno;
			}
			if($accArr){
				$bamodel = new Model_BorrowerAccount();
				$select = $bamodel->select()->where('capno IN (?)', $accArr);	
				$batable = $bamodel->fetchAll($select);	
			}
			$this->view->accounts = $batable;		
		}else{}
	}
	
	public function selectmodelsAction()
	{
		// Edit PMMM
		$this->_helper->RolePermissionHelper('creditscore_selectmodels');									
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');
 
 		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;	
 		$sesid = $this->_getParam('sesid');
		$this->view->sesid = $sesid;	
				
		$this->view->title = "MULTIPLE SCORING - ".strtoupper($prodType)." - SELECT MODELS";
		$this->view->headTitle($this->view->title);

		$form = new Form_Creditscore_SelectModels();
		$form->button->setLabel('Search');
		$this->view->form = $form;
		
		$csmodel = new Model_Creditscore_CSModel();
		$this->view->table = $csmodel->viewSelectModels($prodType);		
		$this->view->busctr = $this->_helper->BusinessCenterSelectHelper();
		$this->view->regpro = $this->_helper->RegularPromoSelectHelper();
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			if($formData['button'] == 'Search'){
				$name = $formData['name'];
				$status = $formData['status'];
				$regpro = $formData['regpro'];
				$busctr = $formData['busctr'];
				
				$this->view->table = $csmodel->selectModels($name, $status, $regpro, $busctr, $prodType);
			}else{
				//Alexis Code 
				$shmodel = new Model_Creditscore_ScoreHistory();
				$models = "";
				foreach($formData['row'] as $key => $value){
					if($key != ""){
						$csrow = $csmodel->getModel($key, $prodType);
						$models = $models.$csrow->id.",";
					}
				$shmodel->setModels($models, $sesid);
				}
				$this->_redirect('/creditscore/output/prod/'.$prodType.'/sesid/'.$sesid.'/act/save');		
			}
		}else{$form->populate($formData);}  				
	}	
	
	public function viewresultAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_viewresult');																											
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');
 
 		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;	
		$this->view->title = "MULTIPLE SCORING - ".strtoupper($prodType)." - VIEW RESULT";
		$this->view->headTitle($this->view->title);
 
		$form = new Form_Creditscore_ViewResults();
		$form->submit->setLabel('Retrieve Scorecards');
		$this->view->form = $form;
		
		$ofmodel = new Model_Creditscore_OutputFile();
		$this->view->files = $ofmodel->getFiles($prodType);
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {	
			$file = $formData['output'];
			$this->view->files = $ofmodel->searchFile($file, $prodType);		
		}else{$form->populate($formData);} 				
	}	
	
	public function outputAction(){
		// Scoring Process will create the save session already
		// Edit PMMM
		// $this->_helper->RolePermissionHelper('creditscore_output');									
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');
		
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;	
		$sesid = $this->_getParam('sesid');
		$this->view->sesid = $sesid;
		$process = $this->_getParam('act'); // score || view 	// Edit PMMM
		$this->view->title = "MULTIPLE SCORING - ".strtoupper($prodType)." - OUTPUT";
		$this->view->headTitle($this->view->title);
		
		$csmodel = new Model_Creditscore_CSModel(); // To get Models
		$fsmodel = new Model_Creditscore_Fieldsselected(); // Fields within Model
		$shmodel = new Model_Creditscore_ScoreHistory(); // Selected Accounts & Model
		$atable = $shmodel->getAccounts($sesid);
		
		//Per Accounts
		$accountArr = array();
		$counter = 0;
		
		foreach($atable as $accounts){
			$accountArr[$counter]['account'] = $accounts->account;
			$accountArr[$counter]['data'] = $this->_helper->CreditScore->scoreMultipleAccount($accounts,$sesid,$process,$prodType);
			$counter++;		
		}
		//preint_r($accountArr);		
		
		$this->view->arr = $accountArr;
		$select = $shmodel->select();
		$select->where("session = ?",$sesid);
		$countData = $shmodel->fetchrow($select);
		$this->view->accounts = $atable;
		$countModel = explode(',',$countData->model_use);
		array_pop($countModel);
		$this->view->countModel = $countModel;
		
		$this->view->models = $counter;
		$this->view->accounts = count($countModel);	
	}
	
	public function scorecardAction()
	{
		//$this->_helper->RolePermissionHelper('creditscore_scorecard');	
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$prodType = $this->_getParam('prod');		
		$this->view->prodType = $prodType;		
		$type = $this->_getParam('type');
		$this->view->type = $type;										
		if($type == 'multi') $this->view->title = "MULTIPLE SCORING - ".strtoupper($prodType)." - OUTPUT - SCORECARD";
		else $this->view->title = strtoupper($prodType)." - SCORECARD";
		$this->view->headTitle($this->view->title);
		$modelver = $this->_getParam('modelver');
		$this->view->modelver = $modelver;	
		$capno = $this->_getParam('cap');
		$this->view->capno = $capno;	
			
		$id = $this->_getParam('scoreid');

		if($type == "multi"){
			$samodel = new Model_Creditscore_ScoreAttributes();
			$scoreDetail = $samodel->getScorecard($id);
		}else{
			$samodel = new Model_CreditScoreAttr;
			$scoreDetail = $samodel->getScoreCard($id);		
		}
		
		parse_str($scoreDetail[score_attributes],$outputScore);
		parse_str($scoreDetail[attributes],$outputAttr);	
		
//		$outputAttr2 = $this->_helper->CreditScore->renameField($outputAttr);		
//		preint_r($outputAttr);
//		preint_r($outputScore);
//		$counter = $this->_helper->CreditScore->checkOCS($modelver, $outputAttr2);

		$this->view->outputWt = $outputScore;
		$this->view->outputAttr = $this->_helper->CreditScore->renameField($outputAttr);
		$this->view->datetime = $scoreDetail[datetime];
		$this->view->by = $scoreDetail[by];
		$this->view->scoretag = $scoreDetail[scoretag];
		
		if($scoreDetail[scoretag] == "Outside Credit Scoring Model Range for Manual Evaluation"){
			$ohmodel = new Model_Creditscore_OCSHistory();
			$this->view->ohtable = $ohmodel->getOCS($id);
		}
		
	//echo $this->_helper->CreditScore->scoreIndivdualAccount($scoreDetail,'');
	//echo $scoreDetail[attributes];
	}
		
	public function outputfilenameAction()
	{
		$username = $this->_helper->GetUsername();
		$prod = $this->_getParam('prod');
		$accounts = $this->_getParam('accounts');
		$models = $this->_getParam('models');
		$sesid = $this->_getParam('sesid');
		$this->view->sesid = $sesid;
	
		if($this->getRequest()->isPost()){
			$formData = $this->getRequest()->getPost();
			$file = $formData['filename'];
			
			$ofmodel = new Model_Creditscore_OutputFile();
			$samodel = new Model_Creditscore_ScoreAttributes();
			$check = $ofmodel->checkFiles($file);
			if($check){
				$this->view->filenameExist = "Please use other name";
			}else{
				$ofmodel->saveFile($sesid, $file, $username, $models, $accounts, $prod);
				$samodel->setFileName($sesid, $file);
			}				
		}else{} 		
	}
	
	public function exportAction()
	{
		$sesid = $this->_getParam('sesid');
		if($this->getRequest()->isPost()){
			$formData = $this->getRequest()->getPost();
			$file = $formData['filename'];
			if($file == "") $file = "Default";	
			$this->_redirect('/creditscore/print/file/'.$file.'/session/'.$sesid);		
		}else{} 	
	}	
	
	public function printAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->viewRenderer->setNeverRender(true);
		$file = $this->_getParam('file');
		$sesid = $this->_getParam('session');
			
		header('Content-type: application/octet-stream'); 
    	header('Content-Disposition: attachment; filename="'.$file.".csv".'"'); 

		$bamodel = new Model_BorrowerAccount();
		$csmodel = new Model_Creditscore_CSModel();	
		$shmodel = new Model_Creditscore_ScoreHistory();
		$samodel = new Model_Creditscore_ScoreAttributes();	
		
		$shrow = $shmodel->getModels($sesid);
		$countModel = explode(',', $shrow->model_use);
		array_pop($countModel);
		$outputStr = "Accounts,"; 

		for($i = 0; $i != count($countModel); $i++){
			$name = $csmodel->getModelById($countModel[$i]);
			$outputStr.="\"".$name."\"";
			if($i != (count($countModel)-1)) $outputStr.=",";			
		}
		$outputStr.="\n";

		$satable = $shmodel->getAccounts($sesid);
		foreach($satable as $accounts){
			$barow = $bamodel->getAccount($accounts->account);
			$name = $barow['borrower_lname'].", ".$barow['borrower_fname']." ".$barow['borrower_mname'];
			$outputStr .="\"".$name."\",";
			$satable2 = $samodel->getData($accounts->account, $sesid);
			$ctr = 1;
			foreach($satable2 as $sarow2){
				parse_str($sarow2['score_attributes'],$parsed);
				$score = $parsed['totalscore'];
				$outputStr .= "\"".$score."\"";
				if(count($satable2) != $ctr) $outputStr.=",";
				$ctr++;
			}
			$outputStr.="\n";
		}		
	   	echo $outputStr; 					
	}
	
	
	public function viewoutputAction()
	{
  		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');
		
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;	
		$file = $this->_getParam('file');
		$this->view->file = $file;
		$this->view->title = "MULTIPLE SCORING - ".strtoupper($prodType)." - VIEW RESULT - ".strtoupper($file);
		$this->view->headTitle($this->view->title);

		$shmodel = new Model_Creditscore_ScoreHistory();
		$samodel = new Model_Creditscore_ScoreAttributes();		
		
		$sarow = $samodel->getSession($file);
		$shrow = $shmodel->getModels($sarow->session);
		$countModel = explode(',', $shrow->model_use);
		array_pop($countModel);
		$this->view->countModel = $countModel;
			
		$accountArr = array();
		$tempArr = array();
		$counter = 0;
		$satable = $shmodel->getAccounts($sarow->session);
		
		foreach($satable as $accounts){
			$accountArr[$counter]['account'] = $accounts->account;
			$satable = $samodel->getData($accounts->account, $sarow->session);
			foreach($satable as $sarow){
				parse_str($sarow['score_attributes'],$parsed);
				$parsed['lastid'] = $sarow->id;
				$tempArr[] = $parsed;
			}
			$accountArr[$counter]['data'] = $tempArr;
			$tempArr = array();
			$counter++;		
		}		
		$this->view->arr = $accountArr;
	}

	public function searchAction()
	{
		$this->_helper->RolePermissionHelper('creditscore_search');													
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - SEARCH";
		$this->view->headTitle($this->view->title);

		$form = new Form_Creditscore_Search();
		$form->submit->setLabel('Retrieved Scorecard');
		$this->view->form = $form;
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			$capno = $formData['capNo'];
			$blast = $formData['bLastName'];
			if($capno != "" || $blast != ""){
//				$samodel = new Model_Creditscore_ScoreAttributes();
//				$this->view->scorecards = $samodel->getScorecards($capno, $blast, $prodType);
				$csamodel = new Model_CreditScoreAttr();
				$this->view->scorecards = $csamodel->getScorecards($capno, $blast, $prodType);
			}		
		}else{$form->populate($formData);}   			
	}	


}

//###########################--E-N-D--C-O-N-T-R-O-L-L-E-R--##########################

//Additional LOS function 
   function preint_r($array)
   {
      echo '<pre>';
      print_r($array);
      echo '</pre>';
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
	
	function capnosep($capno){
		$capnosep = substr($capno,0,-2);
		return $capnosep;	
	}
	
	function capnorecon($capno){
		//use in determining the recon
		$capnorecon = substr($capno,-1);
		return $capnorecon;	
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
		$select->where('capno like ?',$capnosep.'_'.$recon)
			   ->where("employer = 'Current' or employer = 'Remittance'")
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

	function login_user_role(){
		$user = Zend_Auth::getInstance()->getIdentity();
		return $user->role_type;
	}
	
	function login_user(){
		$user = Zend_Auth::getInstance()->getIdentity();
		return $user->username;
	}