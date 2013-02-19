<?php

class Form_CreditScore_PendingModels extends Zend_Form
{
	public function __construct($options=null)
	{
		parent::__construct($options);
		$this->setName('CREDIT - SCORING - AUTO - PENDING MODELS');
		
		$modelName = new Zend_Form_Element_Text('modelName');
		$modelName->setRequired(true)
				  ->removeDecorator('label')
		     	  ->removeDecorator('HtmlTag')
			   	  ->addFilter('StripTags')
			      ->addFilter('StringTrim')
				  ->setAttrib('onmouseover','Tip("Model Name")')
				  ->setAttrib('onmouseout','UnTip()')
				  ->setAttrib('onKeypress','return alphaNumOnly(event)')
	 		      ->addValidator('NotEmpty');
		$this->addElement($modelName);

		$status = new Zend_Form_Element_Select('status');
		$status->setRequired(true)
			   	  ->addFilter('StripTags')
			      ->addFilter('StringTrim')
	 		      ->addValidator('NotEmpty')
				  ->removeDecorator('label')
		     	  ->removeDecorator('HtmlTag')
				  ->addMultiOptions(array(
						''			=>	'',				  
						'APPROVED' 	=> 	'APPROVED',
						'RTS' 		=> 	'RTS', 
						'EDIT'		=>	'FOR EDITING',
						'APPROVAL'	=>	'FOR APPROVAL'

				  ));  
		$this->addElement($status);

		$status2 = new Zend_Form_Element_Select('status2');
		$status2->setRequired(true)
			   	  ->addFilter('StripTags')
			      ->addFilter('StringTrim')
	 		      ->addValidator('NotEmpty')
				  ->removeDecorator('label')
		     	  ->removeDecorator('HtmlTag')
				  ->addMultiOptions(array(
						''			=>	'',				  
						'APPROVED' 	=> 	'APPROVED',
						'RTS' 		=> 	'RTS', 
						'EDIT'		=>	'FOR EDITING'
				  ));  
		$this->addElement($status2);
		
		$status3 = new Zend_Form_Element_Select('status3');
		$status3->setRequired(true)
			   	  ->addFilter('StripTags')
			      ->addFilter('StringTrim')
	 		      ->addValidator('NotEmpty')
				  ->removeDecorator('label')
		     	  ->removeDecorator('HtmlTag')
				  ->addMultiOptions(array(
						''			=>	'',				  
						'APPROVED' 	=> 	'APPROVED',
						'APPROVAL'	=>	'FOR APPROVAL'
				  ));  
		$this->addElement($status3);		

		$search = new Zend_Form_Element_Submit('search');
		$search->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->removeDecorator('DtDdWrapper')
			   ->setAttrib('id','searchbutton');
		$this->addElement($search);		
	}
}
?>