<?php

class Zend_Controller_Action_Helper_OCSSelectHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct($field, $setup)
	{	
		$form = new Form_Creditscore_OCS();
		$this->view->form = $form;
		
		$famodel = new Model_Creditscore_Fieldsattributes();
		$ftmodel = new Model_Creditscore_FieldsTable();
		$otmodel = new Model_Creditscore_OCSTable();
		
		$fttable = $ftmodel->getFields();		
		if($setup == 'first'){
			foreach($fttable as $ftrow){
				if($ftrow->type == 'String'){ 
					$form->cfields->addMultiOption($ftrow->field, $ftrow->name);
				}else{
					$form->nfields1->addMultiOption($ftrow->field, $ftrow->name);	
					$form->nfields2->addMultiOption($ftrow->field, $ftrow->name);
				}	
			}

		}else{
			$fatable = $famodel->getFieldsAndAttribs($field);
			foreach($fatable as $farow){
				$form->attribs->addMultiOption($farow->values, $farow->values);
			}	
			$formArr['attribs'] = $form->attribs;	

			foreach($fttable as $ftrow){
				if($ftrow->type == 'String') {
					$form->cfields->addMultiOption($ftrow->field, $ftrow->name);
					if($ftrow->field == $field) $form->cfields->setValue($ftrow->field, $ftrow->name);
				}else{
					$form->nfields1->addMultiOption($ftrow->field, $ftrow->name);
					$form->nfields2->addMultiOption($ftrow->field, $ftrow->name);	
				}
			}		
		}
		$formArr['cfields'] = $form->cfields;
		$formArr['nfields1'] = $form->nfields1;
		$formArr['nfields2'] = $form->nfields2;
	
		return $formArr;		
    }

}

