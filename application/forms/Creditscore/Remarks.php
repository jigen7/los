<?php

class Form_Creditscore_Remarks extends Zend_Form
{
	public function __construct($options=null)
	{
		parent::__construct($options);
		
		$remark = new Zend_Form_Element_Textarea('remark');
		$remark->removeDecorator('label')
				->removeDecorator('HtmlTag')
				->setAttrib('id','submit_remarks')
				->addFilter('StringTrim')
				->addFilter('StripTags')
				->setAttrib('rows','5')
				->setAttrib('style','width:250px');
		$this->addElement($remark);

		$password = new Zend_Form_Element_Password('password');
		$password->setRequired(true)
			     ->removeDecorator('label')
		     	 ->removeDecorator('HtmlTag')
			   	 ->addFilter('StripTags')
			     ->addFilter('StringTrim')
				 ->setAttrib('onmouseover','Tip("Digital Password")')
				 ->setAttrib('onmouseout','UnTip()')
				 ->setAttrib('onKeypress','return alphaNumOnly(event)')
	 		     ->addValidator('NotEmpty');
		$this->addElement($password);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->removeDecorator('DtDdWrapper')
		       ->setAttrib('id','submitbutton');
		$this->addElement($submit);		

	}
}
?>