<?php

class Form_CreditScore_SelectModels extends Zend_Dojo_Form
{
	public function __construct($options=null)
	{
		parent::__construct($options);
		$this->setName('SELECT MODELS');
		
		$name = new Zend_Form_Element_Text('name');
		$name->setRequired(true)
				  ->removeDecorator('label')
		          ->removeDecorator('HtmlTag')
			   	  ->addFilter('StripTags')
			      ->addFilter('StringTrim')
				  ->setAttrib('onmouseover','Tip("Model Name")')
				  ->setAttrib('onmouseout','UnTip()')
				  ->setAttrib('onKeypress','return alphaNumOnly(event)');
		$this->addElement($name);

		$bLastName = new Zend_Form_Element_Text('bLastName');
		$bLastName->setRequired(true)
				  ->removeDecorator('label')
		          ->removeDecorator('HtmlTag')
			  	  ->addFilter('StripTags')
			  	  ->addFilter('StringTrim')
				  ->setAttrib('onmouseover','Tip("Model Name")')
				  ->setAttrib('onmouseout','UnTip()')
				  ->setAttrib('onKeypress','return alphaOnly(event)');
		$this->addElement($bLastName);
/*
		$dateFrom = new Zend_Form_Element_Text('dateFrom');
		$dateFrom->setRequired(true)
		         ->removeDecorator('label')
		     	 ->removeDecorator('HtmlTag')
			  	 ->addFilter('StripTags')
			  	 ->addFilter('StringTrim')
			  	 ->addValidator('NotEmpty')
				 ->setAttrib('value','MM/DD/YYYY')
				 ->setValue('MM/DD/YYYY')
				 ->setAttrib('onkeyup','datef(this.value)')
				 ->setAttrib('onmouseover','Tip("MM/DD/YYYY")')
				 ->setAttrib('onmouseout','UnTip()')
				 ->setAttrib('Maxlength',10)
				 ->setAttrib('onKeypress','return numOnlySlash(event)')
				 ->setAttrib('onBlur','compage(this)')
				 ->setAttrib('size','15');				
		$this->addElement($dateFrom);
		
		$dateTo = new Zend_Form_Element_Text('dateTo');
		$dateTo->setRequired(true)
		       	 ->removeDecorator('label')
				 ->removeDecorator('HtmlTag')
				 ->addFilter('StripTags')
				 ->addFilter('StringTrim')
				 ->addValidator('NotEmpty')
				 ->setAttrib('value','MM/DD/YYYY')
				 ->setValue('MM/DD/YYYY')
				 ->setAttrib('onkeyup','datef(this.value)')
				 ->setAttrib('onmouseover','Tip("MM/DD/YYYY")')
				 ->setAttrib('onmouseout','UnTip()')
				 ->setAttrib('Maxlength',10)
				 ->setAttrib('onKeypress','return numOnlySlash(event)')
				 ->setAttrib('onBlur','compage(this)')
				 ->setAttrib('size','15');				
		$this->addElement($dateTo);
*/
		$this->addElement('DateTextBox','dateFrom',array('style' => 'width:100px',));
		$this->dateFrom->removeDecorator('HtmlTag', array('tag' => 'dd'));
		$this->dateFrom->removeDecorator('label');  
		$this->dateFrom->removeDecorator('DtDdWrapper'); 
    
		$this->addElement('DateTextBox','dateTo',array('style' => 'width:100px'));
		$this->dateTo->removeDecorator('HtmlTag', array('tag' => 'dd'));
		$this->dateTo->removeDecorator('DtDdWrapper'); 
		$this->dateTo->removeDecorator('label'); 
		$this->dateTo->setAttrib('readonly','');			
		
		$status = new Zend_Form_Element_Select('status');
		$status->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			    ->addMultiOptions(array(
					''			=>	'',					
                    'APPROVED' 	=> 	'APPROVED',
                    'RTS' 		=> 	'RTS', 
					'CURRENT' 	=> 	'CURRENT',
					'CURWUPD' 	=>	'CURRENT WITH UPDATES',
					'EDIT'		=>	'FOR EDITING',
					'APPROVAL'	=>	'FOR APPROVAL',
					'EXPIRED'	=>	'EXPIRED'
               )); 			
		$this->addElement($status);		
		
		$busctr = new Zend_Form_Element_Select('busctr');
		$busctr->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->addMultiOptions(array(
							'' => '',				   
							'Metro Manila' => 'Metro Manila',
							'bacolod' => 'Bacolod', 
							'Cebu' => 'Cebu'
                      ));  
		$this->addElement($busctr);
		
		$regpro = new Zend_Form_Element_Select('regpro');
		$regpro->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->addMultiOptions(array(
							'' => '',
							'Regular' => 'Regular',
							'Promo' => 'Promo1' 
                   	 ));  
		$this->addElement($regpro);		
		
		$submit = new Zend_Form_Element_Submit('button');
		$submit->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->removeDecorator('DtDdWrapper')
			   ->setAttrib('id','submitbutton');
		$this->addElement($submit);		

	}
}
?>