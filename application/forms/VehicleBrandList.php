<?
class Form_VehicleBrandList extends Zend_Dojo_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
	
    
	
	$newid = new Zend_Form_Element_Text('newid');
	$newid->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 40px');
	$this->addElement($newid);
	
	
	
	$newbrand = new Zend_Form_Element_Text('newbrand');
	$newbrand->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','30')
				 ->setAttrib('onKeypress','return numOnlyPeriod(event)');
	$this->addElement($newbrand);
	
	
	$newseq = new Zend_Form_Element_Text('newseq');
	$newseq->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','5')
				 ->setAttrib('onKeypress','return numOnlyPeriod(event)');
	$this->addElement($newseq);	
	
	//-------------------------display fields
	$newid1 = new Zend_Form_Element_Text('newid1');
	$newid1->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 40px');
	$this->addElement($newid1);
	
	$newbrand1 = new Zend_Form_Element_Text('newbrand1');
	$newbrand1->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 150px');
	$this->addElement($newbrand1);	
	
	$newseq1 = new Zend_Form_Element_Text('newseq1');
	$newseq1->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 150px');
	$this->addElement($newseq1);	
	
	
	
		
	}
}


?>
