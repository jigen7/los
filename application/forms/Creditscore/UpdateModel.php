<?php

class Form_CreditScore_UpdateModel extends Zend_Dojo_Form
{
	public function __construct($options=null)
	{
		parent::__construct($options);
		
		$name = new Zend_Form_Element_Text('name');
		$name->setRequired(true)
			 ->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
			 ->addFilter('StripTags')
			 ->addFilter('StringTrim')
			 ->setAttrib('onmouseover','Tip("Model Name")')
			 ->setAttrib('readonly', '')
			 ->setAttrib('onmouseout','UnTip()')
			 ->setAttrib('onKeypress','return alphaNumOnly(event)')
	 		 ->addValidator('NotEmpty');
		$this->addElement($name);
		
		$search = new Zend_Form_Element_Text('search');
		$search->setRequired(true)
			   ->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->addFilter('StripTags')
			   ->addFilter('StringTrim')
			   ->setAttrib('onmouseover','Tip("Model Name")')
			   ->setAttrib('onmouseout','UnTip()')
			   ->setAttrib('onKeypress','return alphaNumOnly(event)')
	 		   ->addValidator('NotEmpty');
		$this->addElement($search);		
/*
		$vpfrom = new Zend_Form_Element_Text('vpfrom');
		$vpfrom->setRequired(true)
			   ->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->addFilter('StripTags')
			   ->addFilter('StringTrim')
			   ->setValue('MM/DD/YYYY')
			   ->setAttrib('value','MM/DD/YYYY')				 
			   ->setAttrib('onkeyup','datef(this.value)')
			   ->setAttrib('onmouseover','Tip("MM/DD/YYYY")')
			   ->setAttrib('onmouseout','UnTip()')
			   ->setAttrib('Maxlength',10)
			   ->setAttrib('onKeypress','return numOnlySlash(event)')
			   ->setAttrib('onBlur','compage(this)')
			   ->setAttrib('size','15')
			   ->addValidator('NotEmpty');				
		$this->addElement($vpfrom);		
		
		$vpto = new Zend_Form_Element_Text('vpto');
		$vpto->setRequired(true)
			 ->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
			 ->addFilter('StripTags')
			 ->addFilter('StringTrim')
		     ->setValue('MM/DD/YYYY')
			 ->setAttrib('value','MM/DD/YYYY')				 
			 ->setAttrib('onkeyup','datef(this.value)')
			 ->setAttrib('onmouseover','Tip("MM/DD/YYYY")')
			 ->setAttrib('onmouseout','UnTip()')
			 ->setAttrib('Maxlength',10)
			 ->setAttrib('onKeypress','return numOnlySlash(event)')
			 ->setAttrib('onBlur','compage(this)')
			 ->setAttrib('size','15')
			 ->addValidator('NotEmpty');				
		$this->addElement($vpto);
*/
		$this->addElement('DateTextBox','vpfrom',array('style' => 'width:100px',));
		$this->vpfrom->removeDecorator('HtmlTag', array('tag' => 'dd')); 
		$this->vpfrom->removeDecorator('label'); 			
		$this->vpfrom->removeDecorator('DtDdWrapper'); 
	    
		$this->addElement('DateTextBox','vpto',array('style' => 'width:100px'));
		$this->vpto->removeDecorator('HtmlTag', array('tag' => 'dd'));
		$this->vpto->removeDecorator('label'); 
		$this->vpto->removeDecorator('DtDdWrapper'); 
		
		$busctr = new Zend_Form_Element_Select('busctr');
		$busctr->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->setattrib('DISABLED', '')
			   ->addMultiOptions(array(
					''	=> '',			   
                   	'Metro Manila' => 'Metro Manila',
                    'bacolod' => 'Bacolod', 
					'Cebu' => 'Cebu'
               ));  
		$this->addElement($busctr);
		
		$regpro = new Zend_Form_Element_Select('regpro');
		$regpro->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->setattrib('DISABLED', '')
			   ->addMultiOptions(array(
					'' => '',				 
                    'Regular' => 'Regular',
                    'Promo' => 'Promo1' 
               ));  
		$this->addElement($regpro);		

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id','submitbutton')
		       ->removeDecorator('label')
			   ->removeDecorator('DtDdWrapper')
		       ->removeDecorator('HtmlTag');
		$this->addElement($submit);		

	}
}
?>