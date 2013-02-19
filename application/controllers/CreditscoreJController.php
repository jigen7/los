<?php

class CreditscoreController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }
	
	public function homeAction()
	{
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/creditscore/prototype.js');
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/creditscore/effects.js');
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/creditscore/accordion.js');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/creditscore/home.css');
	    $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/creditscore/accordion.css');		
		
		$csmodel = new Model_Creditscore_CSModel();
		$cstable = $csmodel->fetchAll();
		foreach($cstable as $csrow){
			$dateTo = $csrow->vpto;
			$dateFrom = $csrow->vpfrom;
			if(strtotime($dateTo) < strtotime(date("Y-m-d"))){
				if($csrow->status == 'CURRENT' || $csrow->status == 'CURWUPD'){
					$csmodel->setUpdateStatus($csrow->id, 'EXPIRED');
				}
			}
			if(strtotime($dateFrom) == strtotime(date("Y-m-d"))){
				if($csrow->status == 'APPROVED'){
					$csmodel->setUpdateStatus($csrow->id, 'CURRENT');
				}
			}
			if(strtotime($dateFrom) <= strtotime(date("Y-m-d")) && strtotime($dateTo) >= strtotime(date("Y-m-d"))){
				if($csrow->status == 'APPROVED'){
					$csmodel->setUpdateStatus($csrow->id, 'CURRENT');
				}
			}			
		}
		
	}

 	public function addmodelAction()
    {
 	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
  	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');
		
		$username = "alexis";
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;

		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - ADD MODEL";
		$this->view->headTitle($this->view->title);
			
		$form = new Form_Creditscore_AddModel();
		$form->submit->setLabel('Define Fields');
		$this->view->form = $form;
		
		$csmodel = new Model_Creditscore_CSModel();
		$ahmodel = new Model_Creditscore_Accounthistory();
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {			
			$modelName = $formData['modelName'];
			$dateFrom = $formData['dateFrom'];
			$dateTo = $formData['dateTo'];
			$businessCenter = $formData['businessCenter'];
			$regularPromo = $formData['regularPromo'];
						
			$isNameInvalid = $csmodel->checkNameVer($modelName, $prodType);
			$isDateInvalid = $csmodel->checkDate($dateFrom, $dateTo);
			$isBusCtrInvalid = $csmodel->checkSelect((string)$businessCenter);
			$isRegProInvalid = $csmodel->checkSelect((string)$regularPromo);

			if($isNameInvalid === true || $isDateInvalid === true || $isBusCtrInvalid === true || $isRegProInvalid === true){
				$form->populate($formData);
				$this->view->errorModelName = ($isNameInvalid)? "Model exist": "";
				$this->view->errorDateName = ($isDateInvalid)? "wrong date": "";
				$this->view->errorBusCtr = ($isBusCtrInvalid)? "select among the choices": "";
				$this->view->errorRegPro = ($isRegProInvalid)? "select among the choices": "";				
			}else{
				$ahmodel->setAccountHistory(strtolower($modelName), 1, 'CRA Add New Model', $username,'none');		
				$csmodel->addModel(strtolower($modelName), $username, $dateFrom, $dateTo, $businessCenter, $regularPromo, $prodType);				
				$this->_redirect('/creditscore/addmodeldefinefields/prod/'.$prodType.'/namever/'.strtolower($modelName).' 1');	
			}					
		}else{
			$form->populate($formData);
		}
	}   	    
 
  	public function addmodeldefinefieldsAction()
    {
		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$username = "alexis";
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;

		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - ADD MODEL - DEFINE FIELDS";
		$this->view->headTitle($this->view->title);
		
		$form = new Form_Creditscore_ModelDefineFields();
		$add = new Zend_Form_Element_Submit('button');
		$add->removeDecorator('label')
		    ->removeDecorator('HtmlTag')
			->removeDecorator('DtDdWrapper')
			->setLabel('Add')
		    ->setAttrib('id','button');
		$this->view->add = $add;	
		
		$submit =  new Zend_Form_Element_Submit('button');
		$submit->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->removeDecorator('DtDdWrapper')
			   ->setLabel('Submit')
		       ->setAttrib('id','button');
		$this->view->submit = $submit;

		$csmodel = new Model_Creditscore_CSModel();
		$atmodel = new Model_Creditscore_Audittrail();
		$ftmodel = new Model_Creditscore_FieldsTable();
		$ahmodel = new Model_Creditscore_Accounthistory();
		$fsmodel = new Model_Creditscore_Fieldsselected();
		$famodel = new Model_Creditscore_Fieldsattributes();

		$table = $ftmodel->getFields();
		foreach($table as $row){
			$form->fields->addMultiOption($row->field, $row->name);
		}
		$this->view->fields = $form->fields;
		$this->view->afields = $fsmodel->addedCNFields($namever);
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {		
			if($formData['button'] == "Add"){				
				$fieldSelected = $formData['fields'];	
				$fieldName = $ftmodel->getField($fieldSelected);
				if($fsmodel->checkFieldType($fieldSelected) == "String"){
					if($fsmodel->checkCField($namever, $fieldSelected)) {
						$fsmodel->addCategoryField($fieldSelected, $namever);
					    $atmodel->fieldAuditTrail($namever, $prodType, $username, $fieldName->name, 'Added', '');	
					}
				}else{
					if($fsmodel->checkNField($namever, $fieldSelected)) {
						$fsmodel->addNumericField($fieldSelected, $namever);
					    $atmodel->fieldAuditTrail($namever, $prodType, $username, $fieldName->name, 'Added', '');
					}
				}
				$this->view->afields = $fsmodel->addedCNFields($namever);	
			}else if($formData['button'] == "Submit"){
				$csrow = $csmodel->getModel($namever, $prodType);				
				$check = $ahmodel->checkAccountHistory($namever, $prodType, 'CRA Encodes');
				if($check == "true") $ahmodel->updateAccountHistory($namever, $prodType, 'CRA Encodes');					
				else $ahmodel->setAccountHistory($csrow->name, 1, 'CRA Encodes', $username,'none');	
				$this->_redirect('/creditscore/addmodeldefineweights/prod/'.$prodType.'/namever/'.$namever);		
			}		
		}else{
			$form->populate($formData);
		}
	}    
    
  	public function addmodeldefineweightsAction()
	{    	
  	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js'); 
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$username = "alexis";
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;

		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - ADD MODEL - DEFINE WEIGHTS";
		$this->view->headTitle($this->view->title);

		$form = new Form_Creditscore_ModelDefineWeights();
		$form->submit->setLabel('Submit');
		$this->view->form = $form;

		$csmodel = new Model_Creditscore_CSModel();
		$atmodel = new Model_Creditscore_Audittrail();
		$fsmodel = new Model_Creditscore_Fieldsselected();		
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
					//$this->_helper->InputHelper($formData, $namever, $prodType, $username);
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
					//$this->_helper->InputHelper($formData, $namever, $prodType, $username);
					if($formData['button']){ 
						$this->_helper->RepDelHelper($formData, $prodType, $username);
					}	
					$csmodel->setEdit($namever);
					$ahmodel->updateAccountHistory($namever, $prodType, 'CRA Encodes');	
				}else{
					$this->view->errorAttribute = "Please check the range!";	
				}				
				$this->view->fieldscat = $mcmodel->getFieldsAndAttrib($namever);
				$this->view->fieldsnum = $mnmodel->getFieldsAndAttrib($namever);
				$form->populate($formData);	
			}
		}else{
			$form->populate($formData);
		} 
	} 
	
	public function approvemodelAction()
	{
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
			$modelName = $formData['modelName'];	
			$model = $csmodel->searchApprovalModel($modelName, $prodType);
			$this->view->table = $model;
		}else{
			$form->populate($formData);
		} 		
	}	
		
	public function approvemodelspecificAction()
	{
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$username = 'alexis';
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;	
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;	
		$place = $this->_getParam('place');
		$this->view->place = $place;
					
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - APPROVE MODEL";
		$this->view->headTitle($this->view->title);

		$form = new Form_Creditscore_ApproveModel();
		$form->approve->setLabel('Approve');
		$form->returnToCRA->setLabel('Return to CRA');
		$this->view->form = $form;
		
		$csmodel = new Model_Creditscore_CSModel();
		$csrow = $csmodel->getModel($namever, $prodType);
	
		$this->view->name = $csrow->name;
		$this->view->vpfrom = $csrow->vpfrom;
		$this->view->vpto = $csrow->vpto;
		$this->view->busctr = $csrow->busctr;
		$this->view->regpro = $csrow->regpro;	
		$this->view->version = $csrow->version;
		
		$fsmodel = new Model_Creditscore_Fieldsselected();
		$this->view->afields = $fsmodel->viewModelSpecific($namever);
		
		$ahmodel = new Model_Creditscore_Accounthistory();
		$check = $ahmodel->checkAccountHistory($namever, $prodType, 'CRO Reviews');
		if($check == "true") $ahmodel->updateAccountHistory($namever, $prodType, 'CRO Reviews');
		else $ahmodel->setAccountHistory($csrow->name, $csrow->version, 'CRO Reviews', $username,'none');

	}
	
	public function returntocraAction()
	{
		$username = 'alexis';
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
			//$this->view->returntocra = "Successfully returned to the sender!";
		}else{
			$form->populate($formData);
		} 	 
	}
	
	public function returnapproveAction()
	{
		$username = "alexis";
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

			$ahmodel->setAccountHistory($csrow->name, $csrow->version, 'CRO Approved', $username,'none');							
			$ahmodel->remarkAccountHistory($namever, $prodType, 'CRO Approved', $remark);
		} 	
	}
		
	public function accounthistoryAction()
	{
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');
 
 		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;	
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		$place = $this->_getParam('place');
		$this->view->place = $place;
		
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - ACCOUNT HISTORY - ".ucfirst($namever);
		$this->view->headTitle($this->view->title);
		
		$csmodel = new Model_Creditscore_CSModel();
		$csrow = $csmodel->getModel($namever, $prodType);
		$this->view->specific = ($csrow->status == 'APPROVAL')? 'approvemodelspecific' : 'viewmodelspecific';
		
		$acmodel = new Model_Creditscore_AccountHistory();
		$this->view->history = $acmodel->getAccountHistory($namever, $prodType);
		
	}
	
	public function audittrailAction()
	{
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;	
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;	
		$place = $this->_getParam('place');
		$this->view->place = $place;
		
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - AUDIT TRAIL - ".ucfirst($namever);
		$this->view->headTitle($this->view->title);

		$csmodel = new Model_Creditscore_CSModel();
		$csrow = $csmodel->getModel($namever, $prodType);
		$this->view->specific = ($csrow->status == 'APPROVAL')? 'approvemodelspecific' : 'viewmodelspecific';

		$atmodel = new Model_Creditscore_Audittrail();
		$this->view->audit = $atmodel->getAuditTrail($namever, $prodType);
	}	

	public function pendingmodelsAction()
    {
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
			$modelName = $formData['modelName'];
			$status = $formData['status'];			
			$table = $model->searchPendingModel($modelName, $status);
			$this->view->table = $table;			
		}else{
			$form->populate($formData);
		}    
	}	

	public function editmodelAction()
	{
		$username = "alexis";
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;	
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		$place = $this->_getParam('place');
		$this->view->place = $place;

		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - EDIT MODEL";
		$this->view->headTitle($this->view->title);
		
		$form = new Form_Creditscore_EditModel();
		$form->submit->setLabel('EDIT FIELDS');
		$this->view->form = $form;
			
		$csmodel = new Model_Creditscore_CSModel();	
		$atmodel = new Model_Creditscore_AuditTrail();
		$ahmodel = new Model_Creditscore_Accounthistory();
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			$modelName = $formData['name'];
			$dateFrom = $formData['vpfrom'];
			$dateTo = $formData['vpto'];
			$businessCenter = $formData['busctr'];
			$regularPromo = $formData['regpro'];
			$dateFrom = date("Y-m-d", strtotime($dateFrom));
			$dateTo = date("Y-m-d", strtotime($dateTo));
		
			$isDateInvalid = $csmodel->checkDate($dateFrom, $dateTo);
			$isBusCtrInvalid = $csmodel->checkSelect((string)$businessCenter);
			$isRegProInvalid = $csmodel->checkSelect((string)$regularPromo);	
			$formData = $this->getRequest()->getPost();			

			if($isDateInvalid === true || $isBusCtrInvalid === true || $isRegProInvalid === true){
				$form->populate($formData);
				$this->view->errorDateName = ($isDateInvalid)? "wrong date": "";
				$this->view->errorBusCtr = ($isBusCtrInvalid)? "select among the choices": "";
				$this->view->errorRegPro = ($isRegProInvalid)? "select among the choices": "";				
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
				if($csrow->busctr != $businessCenter){ $atmodel->changesAuditTrail($modelName, $csrow->version, $username, 'Business Center', 'Changed', $csrow->busctr, $businessCenter);	}		
				if($csrow->regpro != $regularPromo){ $atmodel->changesAuditTrail($modelName, $csrow->version, $username, 'Regular Promo', 'Changed', $csrow->regpro, $regularPromo);	}		
				
				if($csrow->status == 'RTS'){
					$ahmodel->setAccountHistory($csrow->name, $csrow->version, 'CRA Encodes', $username,'none');							
				}else{
					$check = $ahmodel->checkAccountHistory($namever, $prodType, 'CRA Encodes');
					if($check == "true") $ahmodel->updateAccountHistory($namever, $prodType, 'CRA Encodes');	
					else $ahmodel->setAccountHistory($csrow->name, $csrow->version, 'CRA Encodes', $username,'none');							
				}
				$csmodel->setEditModel($csrow->id, $modelName, $username, $dateFrom, $dateTo, $businessCenter, $regularPromo, $prodType);				
				
				$this->_redirect('/creditscore/editmodeldefinefields/prod/'.$prodType.'/namever/'.$modelName.' 1');	
			}
		}else{
			$csrow = $csmodel->getModel($namever, $prodType);
			$this->view->name = $form->name->setValue($csrow->name);
			$this->view->vpfrom = $form->vpfrom->setValue(date("m/d/Y", strtotime($csrow->vpfrom)));
			$this->view->vpto = $form->vpto->setValue(date("m/d/Y", strtotime($csrow->vpto)));
			$this->view->busctr = $form->busctr->setValue($csrow->busctr);
			$this->view->regpro = $form->regpro->setValue($csrow->regpro);
		}  			
	}

  	public function editmodeldefinefieldsAction()
    {
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$username = 'alexis';
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - EDIT MODEL - EDIT FIELDS";
		$this->view->headTitle($this->view->title);

		$form = new Form_Creditscore_ModelDefineFields();
		$add =  new Zend_Form_Element_Submit('button');
		$add->removeDecorator('label')
		    ->removeDecorator('HtmlTag')
			->removeDecorator('DtDdWrapper')
			->setLabel('Add')
		    ->setAttrib('id','button');
		$this->view->add = $add;	
		
		$submit =  new Zend_Form_Element_Submit('button');
		$submit->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->removeDecorator('DtDdWrapper')
			   ->setLabel('Submit')
		       ->setAttrib('id','button');
		$this->view->submit = $submit;	

		$atmodel = new Model_Creditscore_Audittrail();
		$ftmodel = new Model_Creditscore_FieldsTable();
		$ahmodel = new Model_Creditscore_Accounthistory();	
		$fsmodel = new Model_Creditscore_Fieldsselected();
		$famodel = new Model_Creditscore_Fieldsattributes();
		
		$table = $ftmodel->getFields();
		foreach($table as $row){
			$form->fields->addMultiOption($row->field, $row->name);
		}
		$this->view->fields = $form->fields;
		$this->view->afields = $fsmodel->addedCNFields($namever);

		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {	
			if($formData['button'] == "Add"){				
				$fieldSelected = $formData['fields'];			
				$fieldName = $ftmodel->getField($fieldSelected);
				if($fsmodel->checkFieldType($fieldSelected) == "String"){
					if($fsmodel->checkCField($namever, $fieldSelected)) {
						$fsmodel->addCategoryField($fieldSelected, $namever);
					    $atmodel->fieldAuditTrail($namever, $prodType, $username, $fieldName->name, 'Added', '');	
					}
				}else{
					if($fsmodel->checkNField($namever, $fieldSelected)) {
						$fsmodel->addNumericField($fieldSelected, $namever);
					    $atmodel->fieldAuditTrail($namever, $prodType, $username, $fieldName->name, 'Added', '');
					}
				}
				$this->view->afields = $fsmodel->addedCNFields($namever);									
			}else if($formData['button'] == "Submit"){
				$ahmodel->updateAccountHistory($namever, $prodType, 'CRA Encodes');	
				$this->_redirect('/creditscore/editmodeldefineweights/prod/'.$prodType.'/namever/'.$namever);	
			}
		}else{
			$form->populate($formData);
		}   
	} 
	
  	public function editmodeldefineweightsAction()
    {
  	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js'); 		
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$username = 'alexis'; 
 		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;		
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;

		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - EDIT MODEL - EDIT WEIGHTS";
		$this->view->headTitle($this->view->title);

		$form = new Form_Creditscore_ModelDefineWeights();
		$form->submit->setLabel('Submit');
		$this->view->form = $form;
		
		$csmodel = new Model_Creditscore_CSModel();
		$atmodel = new Model_Creditscore_Audittrail();	
		$fsmodel = new Model_Creditscore_Fieldsselected();	
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
					//$this->_helper->InputHelper($formData, $namever, $prodType, $username);
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
					//$this->_helper->InputHelper($formData, $namever, $prodType, $username);
					if($formData['button']){ 
						$this->_helper->RepDelHelper($formData, $prodType, $username);
					}		
					$csmodel->setEdit($namever);
					$ahmodel->updateAccountHistory($namever, $prodType, 'CRA Encodes');	
				}else{
					$this->view->errorAttribute = "Please check the range!";	
				}	
				$this->view->fieldscat = $mcmodel->getFieldsAndAttrib($namever);
				$this->view->fieldsnum = $mnmodel->getFieldsAndAttrib($namever);
				$form->populate($formData);									
			}
		}else{
			$form->populate($formData);
		}  
	}
	
	public function selectaccountsAction()
	{
	// Edit PMMM
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');
 
 		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		//$sesid = $this->_getParam('sesid');
		//$this->view->sesid = $sesid;
			
		$this->view->title = "MULTIPLE SCORING - ".strtoupper($prodType)." - SELECT ACCOUNTS";
		$this->view->headTitle($this->view->title);
 
		$form = new Form_Creditscore_SelectAccounts();
		$form->retrieve->setLabel('Retrieve Accounts');
		$this->view->form = $form;
		
		$shmodel = new Model_Creditscore_ScoreHistory();
		$bamodel = new Model_Sample_BorrowerAccount();
		$borrower = new Model_BorrowerAccount();
		$select = $borrower->select()->limit(100,0);
		
		$page=$this->_getParam('page',1);
		$paginator = Zend_Paginator::factory($borrower->fetchAll($select));
		$paginator->setItemCountPerPage(20);
		$paginator->setCurrentPageNumber($page);
		$this->view->accounts=$paginator;
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			$sesid = date("mdyHis");
			if($formData['retrieve'] == 'Retrieve Accounts'){
			
			$detail = $borrower->search($formData['capNo'],$formData['bLastName'],$formData['decision'],$formData['dateFrom'],$formData['dateTo']);
			
			$page=$this->_getParam('page',1);
	    	$paginator = Zend_Paginator::factory($detail);
	    	$paginator->setItemCountPerPage(20);
	    	$paginator->setCurrentPageNumber($page);
	    	$this->view->accounts=$paginator;
			
			//print_r($detail);
			}
			else {
			
			foreach($formData['row'] as $key => $value){
				$shmodel->setAccount($key, $sesid);
			
			}
			$this->_redirect('/creditscore/selectmodels/prod/'.$prodType.'/sesid/'.$sesid);	
			
			
			}
			
		}else{
			$form->populate($formData);
		}  			
	}	
	
	public function selectmodelsAction()
	{
		// Edit PMMM

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
		$this->view->table = $csmodel->fetchAll();
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			if($formData['button'] == 'Search'){
				$name = $formData['name'];
				$status = $formData['status'];
				$regpro = $formData['regpro'];
				$busctr = $formData['busctr'];
				
				$this->view->table = $csmodel->selectModels($name, $status, $regpro, $busctr);
			}else{
				//Alexis Code 
				$shmodel = new Model_Creditscore_ScoreHistory();
				$models = "";
				foreach($formData['row'] as $key => $value){
					$csrow = $csmodel->getModel($key, $prodType);
					$models = $models.$csrow->id.",";
				$shmodel->setModels($models, $sesid);
				}
				$this->_redirect('/creditscore/output/prod/'.$prodType.'/sesid/'.$sesid);	
				
			}
		}else{
			$form->populate($formData);
		}  				
	}	

	public function outputAction(){
		// Scoring Process will create the save session already
		// Edit PMMM

		
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;	
		$sesid = $this->_getParam('sesid');
		$process = $this->_getParam('act'); // score || view 	// Edit PMMM

		$this->view->sesid = $sesid;
		$this->view->title = "MULTIPLE SCORING - ".strtoupper($prodType)." - OUTPUT";
		
		$csmodel = new Model_Creditscore_CSModel(); // To get Models
		$fsmodel = new Model_Creditscore_Fieldsselected(); // Fields within Model
		$shmodel = new Model_Creditscore_ScoreHistory(); // Selected Accounts & Model
		$atable = $shmodel->getAccounts($sesid);
		
		//Per Accounts
		$accountArr = array();
		$counter = 0;
		
		foreach($atable as $accounts){
		$accountArr[$counter]['account'] = $accounts->account;
		$accountArr[$counter]['data'] = $this->_helper->CreditScore->scoreMultipleAccount($accounts,$sesid,$process);
		$counter++;
		
		}
		//preint_r($accountArr);		
		
		$this->view->arr = $accountArr;
		$select = $shmodel->select();
		$select->where("session = ?",$sesid);
		$countData = $shmodel->fetchrow($select);
		$this->view->accounts = $atable;
		$countModel = explode(',',$countData->model_use);
		$this->view->countModel = $countModel;
			
	}


	public function scorecardAction()
	{
		$this->view->title = "MULTIPLE SCORING - ".$prodType." - OUTPUT - SCORECARD";
		$this->view->headTitle($this->view->title);

		$modelver = $this->_getParam('modelver');
		$capno = $this->_getParam('cap');
		$id = $this->_getParam('scoreid');
		$this->view->modelver = $modelver;	
		$this->view->capno = $capno;	

		$this->view->prodType = $prodType;	

		$scoreAtt = new Model_Creditscore_ScoreAttributes();
		$select = $scoreAtt->select();
		$select->where("id = ?",$id);
		$scoreDetail = $scoreAtt->fetchRow($select)->toArray();
		
		//preint_r($scoreDetail);
		parse_str($scoreDetail[score_attributes],$outputScore);
		parse_str($scoreDetail[attributes],$outputAttr);

		$this->view->outputWt = $outputScore;
		$this->view->outputAttr = $outputAttr;
		$this->view->datetime = $scoreDetail[datetime];
		$this->view->by = $scoreDetail[by];

		
		if($this->getRequest()->isPost()) {
		$formData = $this->getRequest()->getPost();
				
		}else{
			//$form->populate($formData);
		}  	
	}

	public function searchAction()
	{
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
			$modelName = $form->getValue('capNo');
			$status = $form->getValue('bLastName');
			
			$this->_redirect('/creditscore/home/');	
		}else{
			$form->populate($formData);
		}   			
	}	

 	public function uploadmemoAction()
    {
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
		}else{
			$form->populate($formData);
		}     
	}
	
	public function updatemodelAction()
    {
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - UPDATE MODEL";
		$this->view->headTitle($this->view->title);

		$form = new Form_Creditscore_UpdateModel();
		$form->submit->setLabel('Search');
		$this->view->form = $form;
		
		$model = new Model_Creditscore_CSModel();
		$this->view->table = $model->getUpdateModels($prodType);
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			$modelName = $formData['search'];
			$table = $model->getCurrentModel($modelName, $prodType);
			$this->view->table = $table;
		}else{
			$form->populate($formData);
		}     
	} 
	
	public function updatemodelspecificAction()
	{
		$username = 'alexis';
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - UPDATE MODEL";
		$this->view->headTitle($this->view->title);

		$form = new Form_Creditscore_UpdateModel();
		$form->submit->setLabel('EDIT FIELDS');
		$this->view->form = $form;

		$ahmodel = new Model_Creditscore_Accounthistory();		
		$csmodel = new Model_Creditscore_CSModel();
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			$data = $csmodel->getUpdateModel($namever, $prodType);
			(int)$version = $data['version'];	
			$id = $data['id'];			
			$name = $data['name'];
			$regpro = $data['regpro'];
			$busctr	= $data['busctr'];
			$dateFrom = $formData['vpfrom'];
			$dateTo = $formData['vpto'];
			$status = $data['status'];			
			
			$isDateInvalid = $csmodel->checkDate($dateFrom, $dateTo);
			$isModelUpdated = $csmodel->checkIfUpdated($namever, $prodType);
			if($status != 'RTS') $isDateConflict = $csmodel->isDateConflict($data['vpto'],$dateFrom);
			if($isDateInvalid == true || $isModelUpdated == true || $isDateConflict == true)
			{
				$this->view->errorUpdate = ($isModelUpdated)? "Model Updated!" : "";
				$this->view->errorDateName = ($isDateInvalid)? "wrong date": "";
				if($status != 'RTS') $this->view->dateConflict = ($isDateConflict)? "Date Conflict": "";
				
				$csrow = $csmodel->getModel($namever, $prodType);
				$this->view->name = $form->name->setValue($csrow->name);
				$this->view->vpfrom = $form->vpfrom->setValue(date("m/d/Y", strtotime($csrow->vpfrom)));
				$this->view->vpto = $form->vpto->setValue(date("m/d/Y", strtotime($csrow->vpto)));
				$this->view->busctr = $form->busctr->setValue($csrow->busctr);
				$this->view->regpro = $form->regpro->setValue($csrow->regpro);
			}else{
				$name = $data['name'];
				$regpro = $data['regpro'];
				$busctr	= $data['busctr'];
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
				if($status != 'RTS'){
					$csmodel->setCURWUPD($id);
					$csmodel->setUpdateModel($name, $regpro, $busctr, $dateFrom, $dateTo, $version, $id, $prodType);			
					$this->_redirect('/creditscore/copyoldversion/prod/'.$prodType.'/namever/'.$name." ".++$version);												
	
				}else{
					$csmodel->setRTSModel($csrow->id, $namever, $username, $dateFrom, $dateTo, $busctr, $regpro, $prodType);				
					$this->_redirect('/creditscore/updatemodeldefinefields/prod/'.$prodType.'/namever/'.$namever);								
				}			
			}
		}else{
			$csrow = $csmodel->getModel($namever, $prodType);
			$this->view->name = $form->name->setValue($csrow->name);
			$this->view->vpfrom = $form->vpfrom->setValue(date("m/d/Y", strtotime($csrow->vpfrom)));
			$this->view->vpto = $form->vpto->setValue(date("m/d/Y", strtotime($csrow->vpto)));
			$this->view->busctr = $form->busctr->setValue($csrow->busctr);
			$this->view->regpro = $form->regpro->setValue($csrow->regpro);
			$this->view->statss = $csrow->status;
		}     	
	}

  	public function updatemodeldefinefieldsAction()
    {
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$username = 'alexis';
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - UPDATE MODEL - EDIT FIELDS";
		$this->view->headTitle($this->view->title);

		$form = new Form_Creditscore_ModelDefineFields();
		$add =  new Zend_Form_Element_Submit('button');
		$add->removeDecorator('label')
		    ->removeDecorator('HtmlTag')
			->removeDecorator('DtDdWrapper')
			->setLabel('Add')
		    ->setAttrib('id','button');
		$this->view->add = $add;	

		$submit =  new Zend_Form_Element_Submit('button');
		$submit->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->removeDecorator('DtDdWrapper')
			   ->setLabel('Submit')
		       ->setAttrib('id','button');
		$this->view->submit = $submit;

		$csmodel = new Model_Creditscore_CSModel();
		$atmodel = new Model_Creditscore_Audittrail();
		$ftmodel = new Model_Creditscore_FieldsTable();
		$fsmodel = new Model_Creditscore_Fieldsselected();	
		$famodel = new Model_Creditscore_Fieldsattributes();

		$table = $ftmodel->getFields();
		foreach($table as $row){
			$form->fields->addMultiOption($row->field, $row->name);
		}
		$this->view->fields = $form->fields;
		$this->view->afields = $fsmodel->addedCNFields($namever);

		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			
			if($formData['button'] == "Add"){				
				$fieldSelected = $formData['fields'];			
				$fieldName = $ftmodel->getField($fieldSelected);
				if($fsmodel->checkFieldType($fieldSelected) == "String"){
					if($fsmodel->checkCField($namever, $fieldSelected)) {
						$fsmodel->addCategoryField($fieldSelected, $namever);
					    $atmodel->fieldAuditTrail($namever, $prodType, $username, $fieldName->name, 'Added', '');	
					}
				}else{
					if($fsmodel->checkNField($namever, $fieldSelected)) {
						$fsmodel->addNumericField($fieldSelected, $namever);
					    $atmodel->fieldAuditTrail($namever, $prodType, $username, $fieldName->name, 'Added', '');
					}
				}
				$this->view->afields = $fsmodel->addedCNFields($namever);		
			}else if($formData['button'] == "Submit"){
				$csrow = $csmodel->getModel($namever, $prodType);				
				$ahmodel = new Model_Creditscore_Accounthistory();
				$check = $ahmodel->checkAccountHistory($namever, $prodType, 'CRA Encodes');
				if($check == "true") $ahmodel->updateAccountHistory($namever, $prodType, 'CRA Encodes');					
				else $ahmodel->setAccountHistory($csrow->name, $csrow->version, 'CRA Encodes', $username,'none');			
				
				$this->_redirect('/creditscore/updatemodeldefineweights/prod/'.$prodType.'/namever/'.$namever);	
			}			
		}else{
			$form->populate($formData);
		}      
	} 
	
  	public function updatemodeldefineweightsAction()
    {
  	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js'); 		
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$username = 'alexis';
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;		
		
		$this->view->title = "CREDIT SCORING - ".strtoupper($prodType)." - UPDATE MODEL - EDIT WEIGHT";
		$this->view->headTitle($this->view->title);

		$form = new Form_Creditscore_ModelDefineWeights();
		$form->submit->setLabel('Submit');
		$this->view->form = $form;

		$csmodel = new Model_Creditscore_CSModel();
		$atmodel = new Model_Creditscore_Audittrail();		
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
					//$this->_helper->UpdateHelper($formData, $namever, $prodType, $username);
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
					//$this->_helper->InputHelper($formData, $namever, $prodType, $username);
					if($formData['button']){ 
						$this->_helper->RepDelHelper($formData, $prodType, $username);
					}			
					$csmodel->setEdit($namever);
					$ahmodel->updateAccountHistory($namever, $prodType, 'CRA Encodes');	
				}else{
					$this->view->errorAttribute = "Please check the range!";	
				}
				$this->view->fieldscat = $mcmodel->getFieldsAndAttrib($namever);
				$this->view->fieldsnum = $mnmodel->getFieldsAndAttrib($namever);
				$form->populate($formData);															
			}	
		}else{
			$form->populate($formData);
		}     
	}
	
	public function viewmodelAction()
	{
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
			$modelName = $formData['modelName'];
			$status = $formData['status'];
			
			$table = $csmodel->searchViewModel($modelName, $status);
			$this->view->table = $table;
		}else{
			$form->populate($formData);
		} 	
	}
	
	public function viewmodelspecificAction()
	{
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
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
			$hiddens = $formData['hidden'];
			$this->view->hidden = $form->hidden->setValue($hiddens);
			$this->view->hiddenFields = $hiddens;	
			//$this->_redirect('/creditscore/viewmodelspecific/prod/'.$prodType.'/namever/'.$namever.'/place/viewmodel');	
		} 
				
		$fsmodel = new Model_Creditscore_Fieldsselected();
		$this->view->afields = $fsmodel->viewModelSpecific($namever);

		
	}
	
	public function viewmemoAction()
	{
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		$place = $this->_getParam('place');
		$this->view->place = $place;
		
		$csmodel = new Model_Creditscore_CSModel();
		$csrow = $csmodel->getModel($namever, $prodType);
		$this->view->specific = ($csrow->status == 'APPROVAL')? 'approvemodelspecific' : 'viewmodelspecific';		
		
		$this->view->title = "eDOCS - ".strtoupper($prodType)." - VIEW MEMO";	
		$this->view->headTitle($this->view->title); 
	}	
	
	public function viewresultAction()
	{
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');
 
 		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;	
		$this->view->title = "MULTIPLE SCORING - ".strtoupper($prodType)." - VIEW RESULT";
		$this->view->headTitle($this->view->title);
 
		$form = new Form_Creditscore_ViewResults();
		$form->submit->setLabel('Retrieve Scorecards');
		$this->view->form = $form;
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {
				
			$this->_redirect('/creditscore/home/');	
		}else{
			$form->populate($formData);
		} 				
	}	
	
	public function addmodelocsAction()
	{
	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
  	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');		
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;

		$this->view->title = "OCS - ".strtoupper($prodType)." - ADD - ".ucfirst($namever);
		$form = new Form_Creditscore_OCS();
		$this->view->hiddenField = $form->hidden->setValue('hiddenField');
		$this->view->hiddenAtt = $form->hidden->setValue('hiddenAtt');
		$this->view->form = $form;
		
		$famodel = new Model_Creditscore_Fieldsattributes();
		$ftmodel = new Model_Creditscore_FieldsTable();
		$ocmodel = new Model_Creditscore_OCSCategory();
		$onmodel = new Model_Creditscore_OCSNumeric();
		
		$fttable = $ftmodel->getFields();
		foreach($fttable as $ftrow){
			if($ftrow->type == 'String') 
				$form->cfields->addMultiOption($ftrow->field, $ftrow->name);
			else
				$form->nfields->addMultiOption($ftrow->field, $ftrow->name);	
		}
		$this->view->cfields = $form->cfields;
		$this->view->nfields = $form->nfields;
		$this->view->octable = $ocmodel->getCategories($namever);		
		$this->view->ontable = $onmodel->getCategories($namever);
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {

			$hField = $formData['hiddenField'];
			$hAtt = $formData['hiddenAtt'];
			$hR1 = $formData['hiddenR1'];
			$hR2 = $formData['hiddenR2'];
			$hC1 = $formData['hiddenC1'];
			$hC2 = $formData['hiddenC2'];
			$logic = $formData['hiddenL'];
			$del = $formData['hiddenD'];
//			echo "<br>(".$hField.")[".$hAtt."]{".$logic."}--".$hR1."--".$hR2."--".$hC1."--".$hC2."--".$logic."--";

			$ftrow = $ftmodel->getType($hField);
			if($formData['button'] == 'Add'){
				   
					if($ftrow->type == 'String'){ 
						if($ocmodel->checkCategory($namever, $hField, $hAtt))
							$ocmodel->addCategory($namever, $hField, $hAtt);									
					}else{
						if($hR1 != "" || $hR2 != "" || $hC1 != "" || $hC2 != "" || $logic != ""){
							if($onmodel->checkCategory($namever, $hField, $hR1, $hR2, $hC1, $hC2, $logic))
								$onmodel->addCategory($namever, $hField, $hR1, $hR2, $hC1, $hC2, $logic);
						}else{ $this->view->checkInput = "Please check your input";}
					}
			}else if($formData['button'] == 'Delete'){
				if($ftrow->type == 'String'){
					$ocmodel->delCategory($namever, $hField, $hAtt);								
				}else{
					$onmodel->delCategory($del);												
				}
			}
	
			$fatable = $famodel->getFieldsAndAttribs($hField);
			foreach($fatable as $farow){
				$form->attribs->addMultiOption($farow->values, $farow->values);
			}	
			$this->view->attribs = $form->attribs;	

			foreach($fttable as $ftrow){
				if($ftrow->type == 'String') {
					$form->cfields->addMultiOption($ftrow->field, $ftrow->name);
					if($ftrow->field == $hField) $form->cfields->setValue($ftrow->field, $ftrow->name);
				}else{
					$form->nfields->addMultiOption($ftrow->field, $ftrow->name);	
				}
			}
			$this->view->cfields = $form->cfields;
			$this->view->nfields = $form->nfields;
			$this->view->octable = $ocmodel->getCategories($namever);		
			$this->view->ontable = $onmodel->getCategories($namever);
		
			//$this->_redirect('/creditscore/addmodelocs/prod/'.$prodType.'/namever/'.$namever);							
		}else{
			$form->populate($formData);
		} 	

	}
	
	public function editmodelocsAction()
	{
 	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_validator.js');
  	    $this->view->headScript()->appendFile($this->view->baseUrl().'/js/js_keypress_validation.js');		
   		$this->view->headScript()->appendFile($this->view->baseUrl().'/js/scrollabletable.js');
 		$this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/scrollabletable.css');

		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;

		$this->view->title = "OCS - ".strtoupper($prodType)." - ADD - ".ucfirst($namever);
		$form = new Form_Creditscore_OCS();
		$this->view->hiddenField = $form->hidden->setValue('hiddenField');
		$this->view->hiddenAtt = $form->hidden->setValue('hiddenAtt');
		$this->view->form = $form;
		
		$famodel = new Model_Creditscore_Fieldsattributes();
		$ftmodel = new Model_Creditscore_FieldsTable();
		$ocmodel = new Model_Creditscore_OCSCategory();
		$onmodel = new Model_Creditscore_OCSNumeric();
		
		$fttable = $ftmodel->getFields();
		foreach($fttable as $ftrow){
			if($ftrow->type == 'String') 
				$form->cfields->addMultiOption($ftrow->field, $ftrow->name);
			else
				$form->nfields->addMultiOption($ftrow->field, $ftrow->name);	
		}
		$this->view->cfields = $form->cfields;
		$this->view->nfields = $form->nfields;
		$this->view->octable = $ocmodel->getCategories($namever);		
		$this->view->ontable = $onmodel->getCategories($namever);
		
		$formData = $this->getRequest()->getPost();
		if($this->getRequest()->isPost()) {

			$hField = $formData['hiddenField'];
			$hAtt = $formData['hiddenAtt'];
			$hR1 = $formData['hiddenR1'];
			$hR2 = $formData['hiddenR2'];
			$hC1 = $formData['hiddenC1'];
			$hC2 = $formData['hiddenC2'];
			$logic = $formData['hiddenL'];
			$del = $formData['hiddenD'];
//			echo "<br>(".$hField.")[".$hAtt."]{".$logic."}--".$hR1."--".$hR2."--".$hC1."--".$hC2."--".$logic."--";

			$ftrow = $ftmodel->getType($hField);
			if($formData['button'] == 'Add'){
				   
					if($ftrow->type == 'String'){ 
						if($ocmodel->checkCategory($namever, $hField, $hAtt))
							$ocmodel->addCategory($namever, $hField, $hAtt);									
					}else{
						if($hR1 != "" || $hR2 != "" || $hC1 != "" || $hC2 != "" || $logic != ""){
							if($onmodel->checkCategory($namever, $hField, $hR1, $hR2, $hC1, $hC2, $logic))
								$onmodel->addCategory($namever, $hField, $hR1, $hR2, $hC1, $hC2, $logic);
						}else{ $this->view->checkInput = "Please check your input";}
					}
			}else if($formData['button'] == 'Delete'){
				if($ftrow->type == 'String'){
					$ocmodel->delCategory($namever, $hField, $hAtt);								
				}else{
					$onmodel->delCategory($del);												
				}
			}
	
			$fatable = $famodel->getFieldsAndAttribs($hField);
			foreach($fatable as $farow){
				$form->attribs->addMultiOption($farow->values, $farow->values);
			}	
			$this->view->attribs = $form->attribs;	

			foreach($fttable as $ftrow){
				if($ftrow->type == 'String') {
					$form->cfields->addMultiOption($ftrow->field, $ftrow->name);
					if($ftrow->field == $hField) $form->cfields->setValue($ftrow->field, $ftrow->name);
				}else{
					$form->nfields->addMultiOption($ftrow->field, $ftrow->name);	
				}
			}
			$this->view->cfields = $form->cfields;
			$this->view->nfields = $form->nfields;
			$this->view->octable = $ocmodel->getCategories($namever);		
			$this->view->ontable = $onmodel->getCategories($namever);
		
			//$this->_redirect('/creditscore/addmodelocs/prod/'.$prodType.'/namever/'.$namever);							
		}else{
			$form->populate($formData);
		} 	
	}		
	
	public function updatemodelocsAction()
	{
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		$place = $this->_getParam('place');
		$this->view->place = $place;		
	}

	public function viewocsAction()
	{
		$prodType = $this->_getParam('prod');
		$this->view->prodType = $prodType;
		$namever = $this->_getParam('namever');
		$this->view->namever = $namever;
		$place = $this->_getParam('place');
		$this->view->place = $place;		
	}
	

	public function deletefieldAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$prodType = $this->_getParam('prod');
		$namever = $this->_getParam('namever');
		$func = $this->_getParam('func');
		$nameField = explode("-",$namever);		
		$username = 'alexis';
		
		$fsmodel  = new Model_Creditscore_Fieldsselected();
		$fsmodel->deleteCNField($nameField[0], $nameField[1]);
		
		$atmodel = new Model_Creditscore_Audittrail();
		$atmodel->fieldAuditTrail($nameField[0], $prodType, $username, $nameField[1], 'deleted', '');

		//$this->_redirect('/creditscore/'.$func.'/prod/'.$prodType.'/table/'.$table.'/namever/'.$nameField[0]);	
		$this->_redirect('/creditscore/'.$func.'/prod/'.$prodType.'/namever/'.$nameField[0]);	
	}
	
	public function deletecnfieldAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$prodType = $this->_getParam('prod');
		$namever = $this->_getParam('namever');
		$func = $this->_getParam('func');
		$nameField = explode("-",$namever);		
		$username = 'alexis';
		
		$fsmodel  = new Model_Creditscore_Fieldsselected();
		$fsmodel->deleteCNField($nameField[0], $nameField[1]);
		
		$atmodel = new Model_Creditscore_Audittrail();
		$atmodel->fieldAuditTrail($nameField[0], $prodType, $username, $nameField[1], 'deleted', '');

		//$this->_redirect('/creditscore/'.$func.'/prod/'.$prodType.'/table/'.$table.'/namever/'.$nameField[0]);	
		$this->_redirect('/creditscore/'.$func.'/prod/'.$prodType.'/namever/'.$nameField[0]);	
	}	
	
	public function copyoldversionAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$prodType = $this->_getParam('prod');
		$namever = $this->_getParam('namever');
		$nameField = explode(" ",$namever);	
		$name = $nameField[0];
		$ver = $nameField[1];	
		$ver = (int)$ver;

		$fsmodel = new Model_Creditscore_Fieldsselected();
		$mcmodel = new Model_Creditscore_ModelFieldsCategory();
		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();		
		$copy1 = $mcmodel->getFieldsAndAttrib($name." ".--$ver);
		$copy2 = $mnmodel->getFieldsAndAttrib($name." ".$ver);			
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
		$this->_redirect('/creditscore/updatemodeldefinefields/prod/'.$prodType.'/namever/'.$namever);								
	}

	public function replicateAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$prodType = $this->_getParam('prod');
		$namever = $this->_getParam('namever');
		$func = $this->_getParam('func');
		$nameField = explode("-",$namever);		
		$username = 'alexis';
		
		$fsmodel = new Model_Creditscore_Fieldsselected();
		$fsmodel->addNumericField($nameField[1],$nameField[0]);
		
		$this->_redirect('/creditscore/'.$func.'/prod/'.$prodType.'/namever/'.$nameField[0]);			
	}
	
	public function deletereplicateAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$prodType = $this->_getParam('prod');
		$namever = $this->_getParam('namever');
		$func = $this->_getParam('func');
		$nameField = explode("-",$namever);		
		$username = 'alexis';
		
		//$fsmodel  = new Model_Creditscore_Fieldsselected();
		//$fsmodel->deleteReplicateField($nameField[1]);
		$mnmodel = new Model_Creditscore_ModelFieldsNumeric();
		$mnmodel->deletereplicatefield($nameField[1]);
		
		$atmodel = new Model_Creditscore_Audittrail();
		$atmodel->fieldAuditTrail($nameField[0], $prodType, $username, $nameField[1], 'deleted', '');

		$this->_redirect('/creditscore/'.$func.'/prod/'.$prodType.'/namever/'.$nameField[0]);	
	}	



}

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

function login_user_role(){
	
	$user = Zend_Auth::getInstance()->getIdentity();
	return $user->role_type;
}

function login_user(){
	
	$user = Zend_Auth::getInstance()->getIdentity();
	return $user->username;
}