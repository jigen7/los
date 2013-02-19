<?php

class Form_Creditscore_ViewResults extends Zend_Form
{
	public function __construct($options=null)
	{
		parent::__construct($options);
		$this->setName('VIEW RESULTS');
		
		$output = new Zend_Form_Element_Text('output');
		$output->setRequired(true)
			   ->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->addFilter('StripTags')
			   ->addFilter('StringTrim')
			   ->setAttrib('onmouseover','Tip("File Name")')
			   ->setAttrib('onmouseout','UnTip()')
			   ->setAttrib('onKeypress','return alphaNumOnly(event)')
	 		   ->addValidator('NotEmpty');
		$this->addElement($output);
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id','submitbutton')
			   ->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->removeDecorator('DtDdWrapper');
		$this->addElement($submit);		

	}
}
?>