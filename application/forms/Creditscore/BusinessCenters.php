<?php

class Form_Creditscore_BusinessCenters extends Zend_Form
{
	public function __construct($options=null)
	{
		parent::__construct($options);
		$this->setName('Business Center');
		
		$busctr = new Zend_Form_Element_Select('busctr');
		$busctr->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->setattrib('id','busctr')
			   ->setAttrib('width','15');
		$this->addElement($busctr);		
				
		$add = new Zend_Form_Element_Submit('button');
		$this->addElement($add);							

	}
}
?>