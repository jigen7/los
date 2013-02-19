<?
class Form_AccountHistory extends Zend_Dojo_Form
{
	

    public function __construct($options = null)
    {
     parent::__construct($options);


     $capno = new Zend_Form_Element_Text('capno');
	 $capno->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
		     ->setAttrib('size','11')
		     ->setAttrib('onKeypress','return numOnly(event)');
	$this->addElement($capno);
	
	$borrower_lname = new Zend_Form_Element_Text('borrower_lname');
	$borrower_lname->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','30')
				 ->setAttrib('onmouseover','Tip("Borrower Last Name")')
				 ->setAttrib('onmouseout','UnTip()')
				 ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($borrower_lname);

	
	}

}
?>
