<?
class Form_Admin_PriceList extends Zend_Dojo_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
	
	$dealer = new Zend_Form_Element_Select('dealer');
	$dealer->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 150px')
			->addMultiOption('','')
			->addMultiOption('OLD','OLD');
		$table = new Model_ListDealer();
		$sql = $table->select()->order("name ASC");
		 foreach ($table->fetchAll($sql,"name ASC") as $c) {
         $dealer->addMultiOption($c->name, $c->name);} 
	$this->addElement($dealer);
	

	
	
	$veh_brand = new Zend_Form_Element_Select('veh_brand');
	$veh_brand->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('id','vehbrandSelect')
		 ->addMultiOption('','');
		$table = new Model_ChainVehicleBrand();
		$sql = $table->select()->order("brand ASC")->distinct();
		foreach ($table->fetchAll($sql,"brand ASC") as $c) {
	$veh_brand->addMultiOption($c->brand, $c->brand);} 
	$this->addElement($veh_brand);
	
	$listmonth = new Zend_Form_Element_Select('listmonth');
	$listmonth->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('id','vehbrandSelect')
		 ->addMultiOption('','')
		->addMultiOption('1','January')
		->addMultiOption('2','February')
		->addMultiOption('3','March')
		->addMultiOption('4','April')
		->addMultiOption('5','May')
		->addMultiOption('6','June')
		->addMultiOption('7','July')
		->addMultiOption('8','August')
		->addMultiOption('9','September')
		->addMultiOption('10','October')
		->addMultiOption('11','November')
		->addMultiOption('12','December')
		;
	$this->addElement($listmonth);
	
	$listyear = new Zend_Form_Element_Select('listyear');
	$listyear->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('id','vehbrandSelect')
		 ->addMultiOption('','')
  		->addMultiOption('2011','2011')
		->addMultiOption('2010','2010')
		;
	$this->addElement($listyear);
	
	$listmonth2 = new Zend_Form_Element_Select('listmonth2');
	$listmonth2->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('','')
		->addMultiOption('1','January')
		->addMultiOption('2','February')
		->addMultiOption('3','March')
		->addMultiOption('4','April')
		->addMultiOption('5','May')
		->addMultiOption('6','June')
		->addMultiOption('7','July')
		->addMultiOption('8','August')
		->addMultiOption('9','September')
		->addMultiOption('10','October')
		->addMultiOption('11','November')
		->addMultiOption('12','December')
		;
	$this->addElement($listmonth2);
	
	$listyear2 = new Zend_Form_Element_Select('listyear2');
	$listyear2->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('id','vehbrandSelect')
		 ->addMultiOption('','')
		->addMultiOption('2011','2011')
		->addMultiOption('2010','2010')
		;
	$this->addElement($listyear2);
	
	$veh_type = new Zend_Form_Element_Select('veh_type');
	$veh_type->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('id','vehbrandSelect')
		 ->addMultiOption('','');
	$table = new Model_CategoryValues();
		 $sql = $table->select()->where('name LIKE ?', 'VehType')->order("seq ASC");
		 foreach ($table->fetchAll($sql,"seq ASC") as $c) {
         $veh_type->addMultiOption($c->values, $c->values);} 
		 $this->addElement($veh_type);
		 
	$veh_unit = new Zend_Form_Element_Text('veh_unit');
	$veh_unit->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','95')
 				 ->setAttrib('onKeypress','return alphaNumSpaceOnly(event)')
				 ->addFilter('StringToUpper');
	$this->addElement($veh_unit);
	
	$selling_price = new Zend_Form_Element_Text('selling_price');
	$selling_price->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags');
	$this->addElement($selling_price);	 
	
	$button_action = new Zend_Form_Element_Submit('button_action');
	$button_action->removeDecorator('label')
				->removeDecorator('DtDdWrapper')
				->addFilter('StringTrim')
				 ->addFilter('StripTags')
				->removeDecorator('HtmlTag')
				->setLabel('Approve')
				->setAttrib('onclick',"return window.confirm('Are you sure you want to Update all checked rows?')");
	$this->addElement($button_action);	 
	
	$button_delete= new Zend_Form_Element_Submit('button_delete');
	$button_delete->removeDecorator('label')
				->removeDecorator('DtDdWrapper')
				->addFilter('StringTrim')
				 ->addFilter('StripTags')
				->removeDecorator('HtmlTag')
				->setLabel('Delete')
				->setAttrib('onclick',"return window.confirm('Are you sure you want to Delete all checked rows?')");
	$this->addElement($button_delete);	
	
	$dealer2 = new Zend_Form_Element_Select('dealer2');
	$dealer2->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 150px')
			->addMultiOption('','');
		$table = new Model_ListDealer();
		$sql = $table->select()->order("name ASC");
		 foreach ($table->fetchAll($sql,"name ASC") as $c) {
         $dealer2->addMultiOption($c->name, $c->name);} 
	$this->addElement($dealer2);
	
	
	/*************************************************************/
	
	$dealer_new = new Zend_Form_Element_Select('dealer_new');
	$dealer_new->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 150px')
			->addMultiOption('','');
			/*
		$table = new Model_ListDealer();
		$sql = $table->select()->order("name ASC");
		 foreach ($table->fetchAll($sql,"name ASC") as $c) {
         $dealer_new->addMultiOption($c->name, $c->name);} 
         */
	$this->addElement($dealer_new);
	
	}
}


?>
