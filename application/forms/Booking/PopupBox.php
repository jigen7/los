<?
class Form_Booking_PopupBox extends Zend_Form
{
	//Form for Booking Box
    public function __construct($options = null)
    {
	$submit_remarks = new Zend_Form_Element_Textarea('submit_remarks');
	$submit_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				->setAttrib('id','submit_remarks')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('rows','4')
				 ->setAttrib('style','width:370px');
				 //->setAttrib('onKeypress','return alphaNumSpaceOnly(event)');
	$this->addElement($submit_remarks);
	
	$bookdate = new Zend_Form_element_Text('bookdate');
	$bookdate->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
		     ->setAttrib('value','MM/DD/YYYY')
		     ->setValue('MM/DD/YYYY')
			 ->setAttrib('onmouseover','Tip("MM/DD/YYYY")')
			 ->setAttrib('onmouseout','UnTip()')
			 ->setAttrib('Maxlength',10)
			 ->setAttrib('onKeypress','return numOnlySlash(event)')
		     //->setAttrib('onclick','this.value=""')
		     ->setAttrib('size','15');
	$this->addElement($bookdate);
	
	$pn_amount = new Zend_Form_Element_Text('pn_amount');
	$pn_amount->removeDecorator('label')
				 ->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('onKeypress','return numOnlyPeriod(event)')
				 ->setAttrib('size','15');
	$this->addElement($pn_amount);
	
	$amount_financed = new Zend_Form_Element_Text('amount_financed');
	$amount_financed->removeDecorator('label')
				 ->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('onKeypress','return numOnly(event)')
				 ->setAttrib('size','15');
	$this->addElement($amount_financed);
	
	$pn_number = new Zend_Form_Element_Text('pn_number');
	$pn_number->removeDecorator('label')
				 ->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('onKeypress','return numHyphenOnly(event)')
				 ->setAttrib('size','15');
	$this->addElement($pn_number);
	
	$loanterm = new Zend_Form_Element_Text('loanterm');
	$loanterm->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnlyPeriod(event)')
				 ->setAttrib('size','15');
	$this->addElement($loanterm);
	}
	

}


?>
