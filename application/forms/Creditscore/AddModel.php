<?php

class Form_CreditScore_AddModel extends Zend_Dojo_Form
{
	public function __construct($options=null)
	{
		parent::__construct($options);
		
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

		$product = new Zend_Form_Element_Text('product');
		$product->setRequired(true)
			   	->addFilter('StripTags')
			    ->addFilter('StringTrim')
	 		    ->addValidator('NotEmpty');
		$this->addElement($product);

/*		$dateFrom = new Zend_Form_Element_Text('dateFrom');
		$dateFrom->setRequired(true)
			     ->removeDecorator('label')
		     	 ->removeDecorator('HtmlTag')
			  	 ->addFilter('StripTags')
			  	 ->addFilter('StringTrim')
			  	 ->addValidator('NotEmpty')
				 ->setAttrib('value','MM/DD/YYYY')
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
		
		$businessCenter = new Zend_Form_Element_Select('businessCenter');
		$businessCenter->removeDecorator('label')
		     	       ->removeDecorator('HtmlTag')
					   ->setAttrib('width','15')
					   ->addMultiOptions(array(
							''	=> '',				   
							'Metro Manila' => 'Metro Manila',
							'bacolod' => 'Bacolod', 
							'Cebu' => 'Cebu'
						));  
		$this->addElement($businessCenter);
		
		$regularPromo = new Zend_Form_Element_Select('regularPromo');
		$regularPromo->removeDecorator('label')
		     	     ->removeDecorator('HtmlTag')
					 ->addMultiOptions(array(
						   '' => '',					 
						  'Regular' => 'Regular',
						  'Promo' => 'Promo1' 
                       ));  
		$this->addElement($regularPromo);		
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->removeDecorator('DtDdWrapper')
		       ->setAttrib('id','submitbutton');
		$this->addElement($submit);		

	}
}
?>