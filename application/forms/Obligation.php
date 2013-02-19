<?
class Form_Obligation extends Zend_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
	
	
	
	$creditcomp = new Zend_Form_Element_Text('creditcomp');
	$creditcomp->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 //->addMultiOption('0','')
		 //->addMultiOption('MASTER CARD','MASTER CARD')
		 ->setAttrib('style','width: 155px');
         //->setValue('0');
		 /*
         $table = new Model_CategoryValues();
		 $sql = $table->select()
	    ->where('name LIKE ?', 'BusinessNature')->order("seq ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $emp_industry->addMultiOption($c->seq, $c->values);} 
         */
	$this->addElement($creditcomp);
	
	$creditlimit = new Zend_Form_Element_Text('creditlimit');
	$creditlimit->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25')
				 ->setAttrib('onKeypress','return numOnlyPeriod(event)');
	$this->addElement($creditlimit);	

	$expiry_date = new Zend_Form_element_Text('expiry_date');
	$expiry_date->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
		     ->setAttrib('value','MM/DD/YYYY')
		     ->setAttrib('onmouseover','Tip("MM/DD/YYYY")')
			 ->setAttrib('onmouseout','UnTip()')
			// ->setAttrib('onKeypress','return numOnlySlash(event)')
		     //->setAttrib('onclick','this.value=""')
		     ->setAttrib('size','25');
	$this->addElement($expiry_date);
	
	
	
	$facility_type = new Zend_Form_Element_Select('facility_type');
	$facility_type->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption(' ' ,'')
		 ->addMultiOption('Auto' ,'Auto')
		 ->addMultiOption('Home' ,'Home')
		 ->addMultiOption('Personal' ,'Personal')
		 ->addMultiOption('Business' ,'Business')
		->addMultiOption('Others' ,'Others')
		 ->setAttrib('style','width: 90px')
	   	 ->setValue(' ');
	$this->addElement($facility_type);
	
	$amount = new Zend_Form_Element_Text('amount');
	$amount->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25')
				 ->setAttrib('onKeypress','return numOnlyPeriod(event)');
	$this->addElement($amount);	
	
	$monthly_amortization = new Zend_Form_Element_Text('monthly_amortization');
	$monthly_amortization->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25')
				 ->setAttrib('onKeypress','return numOnlyPeriod(event)');
	$this->addElement($monthly_amortization);
	
	$loan_amount = new Zend_Form_Element_Text('loan_amount');
	$loan_amount->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25')
				 ->setAttrib('onKeypress','return numOnlyPeriod(event)');
	$this->addElement($loan_amount);
	
	$collateral = new Zend_Form_Element_Text('collateral');
	$collateral->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25');
				 //->setAttrib('onKeypress','return alphaNumSpaceOnly(event)');
	$this->addElement($collateral);
	
		$bank = new Zend_Form_Element_Select('bank');
	$bank->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('0','')
		 ->setAttrib('style','width: 155px')
         ->setValue('0');
         $table = new Model_ChainBank();
		 $sql = $table->select()->order("name ASC");
	     foreach ($table->fetchAll($sql,"name ASC") as $c) {
         $bank->addMultiOption($c->name, $c->name);} 
	$this->addElement($bank);
	
	//textfields for liabilities_business_financials
	$business_liability = new Zend_Form_Element_Text('business_liability');
	$business_liability->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25');
	$this->addElement($business_liability);
	
	$business_liability_emv = new Zend_Form_Element_Text('business_liability_emv');
	$business_liability_emv->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25')
				  ->setAttrib('onKeypress','return numOnlyPeriod(event)');
			//	 ->setAttrib('onKeypress','return numOnlyHyphen(event)');
	$this->addElement($business_liability_emv);
	
	
	$business_liability_remarks = new Zend_Form_Element_Text('business_liability_remarks');
	$business_liability_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','60');
	$this->addElement($business_liability_remarks);
	
	
	
	}
}


?>
