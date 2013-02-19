<?php

class Form_CreditScore_OCS extends Zend_Form
{
	public function __construct($options=null)
	{
		parent::__construct($options);
		
		$cfields = new Zend_Form_Element_Select('cfields');
		$cfields->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->setAttrib('width','15')
			   ->setAttrib('onChange','getCSelected()')
			   ->addMultiOption('','');
		$this->addElement($cfields);

		$nfields1 = new Zend_Form_Element_Select('nfields1');
		$nfields1->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->setAttrib('width','15')
			   ->setAttrib('onChange','getNSelected()')
			   ->addMultiOption('','');
		$this->addElement($nfields1);
		
		$nfields2 = new Zend_Form_Element_Select('nfields2');
		$nfields2->removeDecorator('label')
		         ->removeDecorator('HtmlTag')
			     ->setAttrib('width','15')
			     ->setAttrib('onChange','getNSelected()')
			     ->addMultiOption('','');
		$this->addElement($nfields2);		
		
		$attribs = new Zend_Form_Element_Select('attribs');
		$attribs->removeDecorator('label')
		        ->removeDecorator('HtmlTag')
			    ->setAttrib('width','15');
		$this->addElement($attribs);		

		$compare1 = new Zend_Form_Element_Select('compare1');
		$compare1->removeDecorator('label')
		     	 ->removeDecorator('HtmlTag')
				 ->setAttrib('width','15')
				 ->addMultiOptions(array(
							''	 => '',				   
							'<'	 => '<',
							'<=' => '<=',
							'>'  => '>',
							'>=' =>	'>=' 
				 ));  
		$this->addElement($compare1);
		
		$compare2 = new Zend_Form_Element_Select('compare2');
		$compare2->removeDecorator('label')
		     	 ->removeDecorator('HtmlTag')
				 ->setAttrib('width','15')
				 ->addMultiOptions(array(
							''	 => '',				   
							'<'	 => '<',
							'<=' => '<=',
							'>'  => '>',
							'>=' =>	'>=' 
				 ));  
		$this->addElement($compare2);
		
		$logic = new Zend_Form_Element_Select('logic');
		$logic->removeDecorator('label')
		      ->removeDecorator('HtmlTag')
			  ->setAttrib('width','15')
			  ->addMultiOptions(array(
							''	 => '',				   
							'&&' => 'AND',
							'||' => 'OR'
			  ));  
		$this->addElement($logic);				
			
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->removeDecorator('DtDdWrapper')
		       ->setAttrib('id','submitbutton');
		$this->addElement($submit);		

	}
}
?>