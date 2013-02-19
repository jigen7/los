<?
class Form_Insurance extends Zend_Dojo_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
	
	$policy_no = new Zend_Form_Element_Text('policy_no');
	$policy_no->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnlyHyphen(event)')
				 ->setAttrib('size','25');
	$this->addElement($policy_no);
	
	$ins_comp = new Zend_Form_Element_Text('ins_comp');
	$ins_comp->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($ins_comp);
	
	$ins_type = new Zend_Form_Element_Text('ins_type');
	$ins_type->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($ins_type);
	

	
	$issue_date = new Zend_Form_element_Text('issue_date');
	$issue_date->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
		     ->setAttrib('onmouseover','Tip("MM/DD/YYYY")')
		     ->setAttrib('onmouseout','UnTip()')
		     ->setAttrib('value','MM/DD/YYYY')
		     //->setAttrib('onclick','this.value=""')
			 //->addValidator('regex',false,'(0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])[- /.](19|20)[0-9]{2}')
			->setAttrib('onKeypress','return numOnlySlash(event)')
			 ->setAttrib('size','15');
	$this->addElement($issue_date);
		
	$pronscured_by = new Zend_Form_Element_Text('pronscured_by');
	$pronscured_by->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($pronscured_by);
	
	$payment_terms = new Zend_Form_Element_Text('payment_terms');
	$payment_terms->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($payment_terms);
	/*
	$this->addElement(
                'DateTextBox',
                'effectivity_date',
                array(
                 'style' => 'width:100px',
                )
            );
		$this->effectivity_date->removeDecorator('HtmlTag', array('tag' => 'dt')); 
		$this->effectivity_date->removeDecorator('Label'); 
		
	$this->addElement(
                'DateTextBox',
                'expiry_date',
                array(
                 'style' => 'width:100px',
                )
            );
		$this->expiry_date->removeDecorator('HtmlTag', array('tag' => 'dt')); 
		$this->expiry_date->removeDecorator('Label'); 
		
	*/
	$effectivity_date = new Zend_Form_element_Text('effectivity_date');
	$effectivity_date->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
		     ->setAttrib('onmouseover','Tip("MM/DD/YYYY")')
		    ->setAttrib('onmouseout','UnTip()')
		     ->setAttrib('value','MM/DD/YYYY')
		     //->setAttrib('onclick','this.value=""')
			 //->addValidator('regex',false,'(0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])[- /.](19|20)[0-9]{2}')
			->setAttrib('onKeypress','return numOnlySlash(event)')
			 ->setAttrib('size','15');
	$this->addElement($effectivity_date);
	
	$expiry_date = new Zend_Form_element_Text('expiry_date');
	$expiry_date->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
		     ->setAttrib('onmouseover','Tip("MM/DD/YYYY")')
		     ->setAttrib('onmouseout','UnTip()')
		     ->setAttrib('value','MM/DD/YYYY')
		     //->setAttrib('onclick','this.value=""')
			 //->addValidator('regex',false,'(0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])[- /.](19|20)[0-9]{2}')
			->setAttrib('onKeypress','return numOnlySlash(event)')
			 ->setAttrib('size','15');
	$this->addElement($expiry_date);
		
	$amount_coverage = new Zend_Form_Element_Text('amount_coverage');
	$amount_coverage->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnly(event)')
				 ->setAttrib('size','16');
	$this->addElement($amount_coverage);
	
	$net_premium = new Zend_Form_Element_Text('net_premium');
	$net_premium->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnly(event)')
				 ->setAttrib('size','16');
	$this->addElement($net_premium);
	
	
	$total_premium = new Zend_Form_Element_Text('total_premium');
	$total_premium->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnly(event)')
				 ->setAttrib('size','16');
	$this->addElement($total_premium);
	
	$misce_charges = new Zend_Form_Element_Text('misce_charges');
	$misce_charges->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnly(event)')
				 ->setAttrib('size','16');
	$this->addElement($misce_charges);
	
	$net_commision = new Zend_Form_Element_Text('net_commision');
	$net_commision->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnly(event)')
				 ->setAttrib('size','16');
	$this->addElement($net_commision);
	
	$net_premium2 = new Zend_Form_Element_Text('net_premium2');
	$net_premium2->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnly(event)')
				 ->setAttrib('size','16');
	$this->addElement($net_premium2);
	
	$coverage = new Zend_Form_Element_Text('coverage');
	$coverage->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($coverage);
	
	$desc = new Zend_Form_Element_Text('desc');
	$desc->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($desc);
	


	
	}
}


?>
