<?
class Form_Vehicle extends Zend_Dojo_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
	
    
	
	$veh_brand = new Zend_Form_Element_Select('veh_brand');
	$veh_brand->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('id','vehbrandSelect')
		 ->setAttrib('style','width: 150px')
	 	//->setAttrib('onChange','displaySubs0(this.options[this.selectedIndex].text)')
		 ->addMultiOption('0','');
		$table = new Model_ChainVehicle();
		$sql = $table->select()->where('category like ?','Brand')->order("id ASC");
		foreach ($table->fetchAll($sql,"id ASC") as $c) {
		$veh_brand->addMultiOption($c->element, $c->element);} 
		
	$this->addElement($veh_brand);
	
	$other_brand = new Zend_Form_Element_Text('other_brand');
	$other_brand->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 150px');
	$this->addElement($other_brand);	
	
	$veh_unit = new Zend_Form_Element_Select('veh_unit');
	$veh_unit->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 150px')
			->setAttrib('id','vehunitSelect')
 		 	//->setAttrib('onChange','displaySubs1(this.options[this.selectedIndex].text)')
			->addMultiOption(0,'');
	$this->addElement($veh_unit);
	
	$other_unit = new Zend_Form_Element_Text('other_unit');
	$other_unit->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 150px');
	$this->addElement($other_unit);

	$veh_sell = new Zend_Form_Element_Select('veh_sell');
	$veh_sell->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('id','vehsellSelect')
 		// ->setAttrib('onChange','displaySubs2(this.options[this.selectedIndex].text)')
		 ->setAttrib('style','width: 150px');
	$this->addElement($veh_sell);
	
	$other_sell = new Zend_Form_Element_Text('other_sell');
	$other_sell->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 150px');
	$this->addElement($other_sell);
	
		
	}
}


?>
