<?php

class Form_CreditScore_Rules extends Zend_Form
{
	public function __construct($options=null)
	{
		parent::__construct($options);
		
		$ruleName = new Zend_Form_Element_Text('ruleName');
		$ruleName->setRequired(true)
			     ->removeDecorator('label')
		     	 ->removeDecorator('HtmlTag')
			   	 ->addFilter('StripTags')
			     ->addFilter('StringTrim')
				 ->setAttrib('onmouseover','Tip("Rule Name")')
				 ->setAttrib('onmouseout','UnTip()')
				 ->setAttrib('onKeypress','return alphaNumOnly(event)')
	 		     ->addValidator('NotEmpty');
		$this->addElement($ruleName);

		$ruleDesc = new Zend_Form_Element_Text('ruleDesc');
		$ruleDesc->setRequired(true)
			     ->removeDecorator('label')
		     	 ->removeDecorator('HtmlTag')
			   	 ->addFilter('StripTags')
			     ->addFilter('StringTrim')
				 ->setAttrib('size','45')
				 ->setAttrib('onmouseover','Tip("Rule Description")')
				 ->setAttrib('onmouseout','UnTip()')
				 ->setAttrib('onKeypress','return alphaNumOnly(event)')
	 		     ->addValidator('NotEmpty');
		$this->addElement($ruleDesc);				

	}
}
?>