<?php

class Form_Creditscore_RegularPromo extends Zend_Form
{
	public function __construct($options=null)
	{
		parent::__construct($options);
		$this->setName('Regular Promo');
		
		$regpro = new Zend_Form_Element_Select('regpro');
		$regpro->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->setattrib('id','regpro')
			   ->setAttrib('width','15');
		$this->addElement($regpro);		
				
		$add = new Zend_Form_Element_Submit('button');
		$this->addElement($add);							

	}
}
?>