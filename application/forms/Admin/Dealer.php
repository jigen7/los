<?
class Form_Admin_Dealer extends Zend_Dojo_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
	
	$dealer = new Zend_Form_Element_Text('dealer');
	$dealer->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('size','40');
	$this->addElement($dealer);
	
	
	}
}


?>
