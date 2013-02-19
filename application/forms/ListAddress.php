<?
class Form_ListAddress extends Zend_Form
{
    public function __construct($options = null)
    {
	
	parent::__construct($options);
	
	$pres_address_brgy = new Zend_Form_Element_Select('pres_address_brgy');
	$pres_address_brgy->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->setRegisterInArrayValidator(false)
		 ->addFilter('StringTrim')
		->setAttrib('id', 'brgySelect')
		->setValue('2');
	$this->addElement($pres_address_brgy);
	
	$pres_address_city = new Zend_Form_Element_Select('pres_address_city');
	$pres_address_city->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->setRegisterInArrayValidator(false)
		 ->addFilter('StringTrim')
		->addMultiOption('0','Please Select...')		 
		->setAttrib('id', 'citySelect');

	$this->addElement($pres_address_city);
	
	$pres_address_province = new Zend_Form_Element_Select('pres_address_province');
	$pres_address_province->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->setRegisterInArrayValidator(false)
		 ->addFilter('StringTrim')
		 ->setAttrib('id', 'categorySelect')
		 ->addMultiOption('0','Please Select...');
		$table = new Model_ChainAddress();
		$sql = $table->select()->where('category like ?','Province')->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
		$pres_address_province->addMultiOption($c->element, $c->element);} 
		$this->addElement($pres_address_province);
	
	
	 $pres_zipcode = new Zend_Form_Element_Select('pres_zipcode');
	 $pres_zipcode->removeDecorator('label')
				->setRegisterInArrayValidator(false)
				->removeDecorator('HtmlTag')
				->setAttrib('id', 'zipSelect')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				  ->setValue('0');
	$this->addElement($pres_zipcode);
	

	
	

	
	$prev_address_city = new Zend_Form_Element_Select('prev_address_city');
	$prev_address_city->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->setRegisterInArrayValidator(false)
		 ->addFilter('StringTrim')
 	   	->addMultiOption('0','Please Select...')
		->setAttrib('id', 'citySelect2');


	$this->addElement($prev_address_city);
	
	$prev_address_province = new Zend_Form_Element_Select('prev_address_province');
	$prev_address_province->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->setRegisterInArrayValidator(false)
		 ->addFilter('StringTrim')
		 ->setAttrib('id', 'categorySelect2')
	   	->addMultiOption('0','Please Select...');
		$table = new Model_ChainAddress();
		$sql = $table->select()->where('category like ?','Province')->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
		$prev_address_province->addMultiOption($c->element, $c->element);} 
		$this->addElement($prev_address_province);
	
	
	 $prev_zipcode = new Zend_Form_Element_Select('prev_zipcode');
	 $prev_zipcode->removeDecorator('label')
				->setRegisterInArrayValidator(false)
				->removeDecorator('HtmlTag')
				->setAttrib('id', 'zipSelect2')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				  ->setValue('0');
	$this->addElement($prev_zipcode);
	
		

	
	}
}


?>
