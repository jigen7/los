<?php

class Form_CreditScore_ViewModel extends Zend_Form
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
			 ->setAttrib('onmouseout','UnTip()')
			 ->setAttrib('onKeypress','return alphaNumOnly(event)')
	 		 ->addValidator('NotEmpty');
		$this->addElement($name);

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

		$modelName = new Zend_Form_Element_Text('modelName');
		$modelName->setRequired(true)
				  ->removeDecorator('label')
		     	  ->removeDecorator('HtmlTag')
			   	  ->addFilter('StripTags')
			      ->addFilter('StringTrim')
				  ->setAttrib('onmouseover','Tip("Model Name")')
				  ->setAttrib('onmouseout','UnTip()')
				  ->setAttrib('onKeypress','return alphaNumOnly(event)')
	 		      ->addValidator('NotEmpty');
		$this->addElement($modelName);

		$status = new Zend_Form_Element_Select('status');
		$status->setRequired(true)
			   	  ->addFilter('StripTags')
			      ->addFilter('StringTrim')
	 		      ->addValidator('NotEmpty')
				  ->removeDecorator('label')
		     	  ->removeDecorator('HtmlTag')
				  ->addMultiOptions(array(
						''			=>	'',	
						'APPROVAL'	=>	'APPROVAL',
						'APPROVED' 	=> 	'APPROVED',
						'CURRENT' 	=> 	'CURRENT',
						'CURWUPD' 	=>	'CURRENT WITH UPDATES',
						'EDIT'		=>	'EDIT',
						'EXPIRED'	=>	'EXPIRED',
						'RTS'		=>	'RTS'
				  ));  
		$this->addElement($status);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id','submitbutton')
		       ->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->removeDecorator('DtDdWrapper');
		$this->addElement($submit);		
		
		$hidden = new Zend_Form_Element_Hidden('hidden');
		$hidden->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->removeDecorator('DtDdWrapper');
		$this->addElement($hidden);		
	}
}
?>