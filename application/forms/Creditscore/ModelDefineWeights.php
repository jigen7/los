<?php

class Form_CreditScore_ModelDefineWeights extends Zend_Form
{
	public function __construct($options=null)
	{
		parent::__construct($options);
		$this->setName('DEFINE WEIGHTS');

		$weightTo = new Zend_Form_Element_Text('weightTo');
		$weightTo->setRequired(true)
				 ->removeDecorator('label')
		     	 ->removeDecorator('HtmlTag')
			   	 ->addFilter('StripTags')
			     ->addFilter('StringTrim')
				 ->setAttrib('onmouseover','Tip("Original Weight")')
				 ->setAttrib('onmouseout','UnTip()')
				 ->setAttrib('onKeypress','return numOnlyTel(event)')
	 		     ->addValidator('NotEmpty');
		$this->addElement($weightTo);

		$weightFrom = new Zend_Form_Element_Text('weightFrom');
		$weightFrom->setRequired(true)
		           ->removeDecorator('label')
		     	   ->removeDecorator('HtmlTag')
			   	   ->addFilter('StripTags')
			       ->addFilter('StringTrim')
				   ->setAttrib('onmouseover','Tip("Change Weight")')
				   ->setAttrib('onmouseout','UnTip()')
				   ->setAttrib('onKeypress','return numOnlyTel(event)')   
	 		       ->addValidator('NotEmpty');
		$this->addElement($weightFrom);

		$submit = new Zend_Form_Element_Button('submit');
		$submit->setAttrib('onclick','submitModel()')
			   ->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->removeDecorator('DtDdWrapper')
		       ->setAttrib('id','submitbutton');
		$this->addElement($submit);		
	}
}
?>