<?
class Form_Admin_Weights extends Zend_Dojo_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
	
	$tables = new Zend_Form_Element_Select('tables');
	$tables->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 150px')
			->addMultiOption('','');
	$this->addElement($tables);
	
	$fields = new Zend_Form_Element_Select('fields');
	$fields->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 150px');
	$this->addElement($fields);
	
	
	
	}
}


?>
