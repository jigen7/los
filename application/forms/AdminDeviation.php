<?
class Form_AdminDeviation extends Zend_Dojo_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
	
	
	$devfield = new Zend_Form_Element_Text('devfield');
	$devfield->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($devfield);
	
	$add_deviation = new Zend_Form_Element_Text('add_deviation');
	$add_deviation->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($add_deviation);
	
	$modify_deviation = new Zend_Form_Element_Text('modify_deviation');
	$modify_deviation->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($modify_deviation);
	
	$minval = new Zend_Form_Element_Text('minval');
	$minval->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($minval);
	
	$maxval = new Zend_Form_Element_Text('maxval');
	$maxval->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($maxval);
	
	$exactval = new Zend_Form_Element_Text('exactval');
	$exactval->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($exactval);
	
	
	}
}


?>
