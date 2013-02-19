<?
class Form_OtherAsset extends Zend_Form
{
    public function __construct($options = null)
    {
    
	$location = new Zend_Form_Element_Text('location');
	$location->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25');
	$this->addElement($location);
	
	$lot_area = new Zend_Form_Element_Text('lot_area');
	$lot_area->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25');
	$this->addElement($lot_area);
	
	$real_emv = new Zend_Form_Element_Text('real_emv');
	$real_emv->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25')
				->setAttrib('onKeypress','return numOnlyPeriod(event)');
	$this->addElement($real_emv);
	
	$year_make = new Zend_Form_Element_Text('year_make');
	$year_make->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25');
	$this->addElement($year_make);
	
	$model = new Zend_Form_Element_Text('model');
	$model->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25');
	$this->addElement($model);
	
	$auto_emv = new Zend_Form_Element_Text('auto_emv');
	$auto_emv->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25')
				 ->setAttrib('onKeypress','return numOnlyPeriod(event)');
	$this->addElement($auto_emv);
	
	$company = new Zend_Form_Element_Text('company');
	$year_make->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25');
	$this->addElement($company);
	
	$num_share = new Zend_Form_Element_Text('num_share');
	$num_share->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25');
	$this->addElement($num_share);
	
	$shares_emv = new Zend_Form_Element_Text('shares_emv');
	$shares_emv->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25')
				 ->setAttrib('onKeypress','return numOnlyPeriod(event)');
	$this->addElement($shares_emv);
	
	
	$bank = new Zend_Form_Element_Select('bank');
	$bank->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('0','')
		 //->addMultiOption('CHINABANK SAVINGS','CHINABANK SAVINGS')
		 ->setAttrib('style','width: 155px')
         ->setValue('0');
		 
         $table = new Model_ChainBank();
		 $sql = $table->select()->order("name ASC");
	     foreach ($table->fetchAll($sql,"name ASC") as $c) {
         $bank->addMultiOption($c->name, $c->name);} 
         
	$this->addElement($bank);
	
	$branch = new Zend_Form_Element_Text('branch');
	$branch->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25');
				 //->setAttrib('onKeypress','return alphaNumSpaceOnly(event)');
	$this->addElement($branch);
	
	$account_type = new Zend_Form_Element_Select('account_type');
	$account_type->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('0','')
		 ->addMultiOption('SAVINGS','SAVINGS')
		 ->addMultiOption('CHECK','CHECK')
		 ->addMultiOption('TIME DEPOSIT','TIME DEPOSIT')
		 ->addMultiOption('OTHERS','OTHERS')
		 ->setAttrib('style','width: 130px')
         ->setValue('0');
		 /*
         $table = new Model_CategoryValues();
		 $sql = $table->select()
	    ->where('name LIKE ?', 'BusinessNature')->order("seq ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $emp_industry->addMultiOption($c->seq, $c->values);} 
         */
	$this->addElement($account_type);
	
	$account_no = new Zend_Form_Element_Text('account_no');
	$account_no->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25')
				 ->setAttrib('onKeypress','return numOnlyHyphen(event)');
	$this->addElement($account_no);

	$adb = new Zend_Form_Element_Text('adb');
	$adb->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25');
	$this->addElement($adb);	
	
	$date_opened = new Zend_Form_element_Text('date_opened');
	$date_opened->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
		     ->setAttrib('value','MM/DD/YYYY')
		     ->setAttrib('onmouseover','Tip("MM/DD/YYYY")')
			 ->setAttrib('onmouseout','UnTip()')
			 ->setAttrib('onKeypress','return numOnlySlash(event)')
		     //->setAttrib('onclick','this.value=""')
		     ->setAttrib('size','25');
	$this->addElement($date_opened);
	
	//textfields for asset_business_financials
	$business_asset = new Zend_Form_Element_Text('business_asset');
	$business_asset->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25');
	$this->addElement($business_asset);
	
	$business_asset_emv = new Zend_Form_Element_Text('business_asset_emv');
	$business_asset_emv->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25')
				 ->setAttrib('onKeypress','return numOnlyPeriod(event)');
			
	$this->addElement($business_asset_emv);
	
	
	$business_asset_remarks = new Zend_Form_Element_Text('business_asset_remarks');
	$business_asset_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','60');
	$this->addElement($business_asset_remarks);
	
	
	}
}


?>
