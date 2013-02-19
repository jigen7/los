<?php

class Form_Creditscore_ModelDefineFields extends Zend_Form
{
	public function __construct($options=null)
	{
		parent::__construct($options);
		$this->setName('DEFINE FIELDS');
		
		$table = new Zend_Form_Element_Select('table');
		$table->removeDecorator('label')
		      ->removeDecorator('HtmlTag')
			  ->setAttrib('id','selecttable')
			  ->setAttrib('onchange','genFields()')
			  ->setAttrib('width','15');
		$this->addElement($table);
		
		$fields = new Zend_Form_Element_Select('fields');
		$fields->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->setattrib('id','selectfields')
			   ->setAttrib('width','15');
		$this->addElement($fields);		
				
		$add = new Zend_Form_Element_Submit('button');
		$this->addElement($add);						
					
		$delete = new Zend_Form_Element_Submit('button');
		$this->addElement($delete);					
				
		$submit = new Zend_Form_Element_Submit('button');
		$this->addElement($submit);		

	}
}
?>