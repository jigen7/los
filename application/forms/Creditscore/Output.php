<?php

class Form_CreditScore_Output extends Zend_Form
{
	public function __construct($options=null)
	{
		parent::__construct($options);
		$this->setName('OUTPUT');
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->removeDecorator('DtDdWrapper')
			   ->setAttrib('id','submitbutton');
		$this->addElement($submit);		

	}
}
?>