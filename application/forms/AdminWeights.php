<?
class Form_AdminWeights extends Zend_Dojo_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
	
	
	$weight_field = new Zend_Form_Element_Select('weight_field');
	$weight_field->removeDecorator('label')
				 ->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addMultiOption(0 ,'')
				 ->setAttrib('style','width: 200px')
				 ->setValue('0');
	$this->addElement($weight_field);
	
	$id = new Zend_Form_Element_Text('id');
	$id->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($id);
	
	$field_value = new Zend_Form_Element_Text('field_value');
	$field_value->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($field_value);
	
	$field_weight = new Zend_Form_Element_Text('field_weight');
	$field_weight->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($field_weight);
	
	}
}


?>
