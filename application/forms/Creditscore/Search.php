<?php

class Form_CreditScore_Search extends Zend_Form
{
	public function __construct($options=null)
	{
		parent::__construct($options);
		$this->setName('SEARCH');
		
		$capNo = new Zend_Form_Element_Text('capNo');
		$capNo->setRequired(true)
			  ->removeDecorator('label')
		      ->removeDecorator('HtmlTag')
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim');
		$this->addElement($capNo);
		
		$bLastName = new Zend_Form_Element_Text('bLastName');
		$bLastName->setRequired(true)
			  	  ->removeDecorator('label')
		          ->removeDecorator('HtmlTag')
				  ->addFilter('StripTags')
			  	  ->addFilter('StringTrim');
		$this->addElement($bLastName);
	
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id','submitbutton')
			   ->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->removeDecorator('DtDdWrapper');
		$this->addElement($submit);		

	}
}
?>