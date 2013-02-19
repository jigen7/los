<?
class Form_Employment extends Zend_Dojo_Form
{
    public function __construct($options = null)
    {

	$employer = new Zend_Form_Element_Select('employer');
	$employer->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		->setAttrib('style','width: 155px')
		->setAttrib('onChange','displaySubs(this.options[this.selectedIndex].text)')
		->addMultiOption('0','Please Select...')
		->addMultiOption('Current','Current')
		->addMultiOption('Previous','Previous');
		//->addMultiOption('Remittance','Remittance/Investment/Interest Income/Pensioner')
	$this->addElement($employer);
	
	$dateresigned = new Zend_Form_element_Text('dateresigned');
	$dateresigned->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
			 ->setAttrib('id','dater')
		     ->setAttrib('value','MM/DD/YYYY')
		     ->setAttrib('onmouseover','Tip("MM/DD/YYYY")')
			 ->setAttrib('onmouseout','UnTip()')
			 ->setAttrib('Maxlength',10)
			 ->setAttrib('onKeypress','return numOnlySlash(event)')
		     //->setAttrib('onclick','this.value=""')
		     ->setAttrib('size','25');
	$this->addElement($dateresigned);

	$emp_name = new Zend_Dojo_Form_Element_Combobox('emp_name');
	$emp_name->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addMultiOption('', '')
				 ->setAttrib('onChange','document.forms[0].borrower_prev_address_province.value="Metro Manil"')
				 ->setAttrib('size','25');
				 $table = new Model_ChainCompany();
	//->setAttrib('onKeypress','return alphaNumSpaceOnly(event)');
	$sql = $table->select();
	foreach ($table->fetchAll($sql,"id ASC") as $c) {
    $emp_name->addMultiOption($c->company_name, $c->company_name);} 
	$this->addElement($emp_name);
	
	$emp_industry = new Zend_Form_Element_Select('emp_industry');
	$emp_industry->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('0','')
		 ->setAttrib('style','width: 155px')
         ->setValue('0');
         $table = new Model_CategoryValues();
		 $sql = $table->select()
	    ->where('name LIKE ?', 'BusinessNature')->order("seq ASC")
		->where('values NOT LIKE ?','N/A');
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $emp_industry->addMultiOption($c->seq, $c->values);} 
	$this->addElement($emp_industry);
	
	$emp_address = new Zend_Form_Element_Text('emp_address');
	$emp_address->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25');
				 //->setAttrib('onKeypress','return alphaNumSpaceOnly(event)');
	$this->addElement($emp_address);
	
	$emp_telno = new Zend_Form_Element_Text('emp_telno');
	$emp_telno->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25')
				  ->setAttrib('onKeypress','return numOnlyTel(event)');
	$this->addElement($emp_telno);
	
	$emp_pos = new Zend_Form_Element_Select('emp_pos');
	$emp_pos->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('0','')
		 ->setAttrib('style','width: 155px')
         ->setValue('0');
         $table = new Model_CategoryValues();
		 $sql = $table->select()
	    ->where('name LIKE ?', 'EmpPosition')->order("seq ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $emp_pos->addMultiOption($c->seq, $c->values);} 
	$this->addElement($emp_pos);
	
	$emp_status = new Zend_Form_Element_Select('emp_status');
	$emp_status->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('0','')
		 ->setAttrib('style','width: 155px')
         ->setValue('0');
         $table = new Model_CategoryValues();
		 $sql = $table->select()
	    ->where('name LIKE ?', 'EmpStatus');
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $emp_status->addMultiOption($c->seq, $c->values);} 
	$this->addElement($emp_status);
	
	$emp_income = new Zend_Form_Element_Text('emp_income');
	$emp_income->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25')
				 ->setAttrib('onKeypress','return numOnlyPeriod(event)')
  				 ->setAttrib('onBlur','empmonthlytoannual()');
	$this->addElement($emp_income);	
	
	$emp_multiplier = new Zend_Form_Element_Text('emp_multiplier');
	$emp_multiplier->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','5')
				 ->setValue(1)
				 ->setAttrib('onKeypress','return numOnlyPeriod(event)');
	$this->addElement($emp_multiplier);	
	
	$emp_annual = new Zend_Form_Element_Text('emp_annual');
	$emp_annual->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25')
 				 ->setAttrib('onKeypress','return numOnlyPeriod(event)')
				 ->setAttrib('onBlur','empannualtomonthly()');
	$this->addElement($emp_annual);		
	
	$emp_yrs = new Zend_Form_Element_Text('emp_yrs');
	$emp_yrs->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','1')
				 ->setAttrib('onblur','empyrs(this.value)')
				 ->setAttrib('onKeypress','return numOnlyPoint(event)');
	$this->addElement($emp_yrs);	
	
	$emp_gsiss = new Zend_Form_Element_Text('emp_gsiss');
	$emp_gsiss->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25')
				 ->setAttrib('onKeypress','return numOnlyHyphen(event)');
	$this->addElement($emp_gsiss);
	
	
	
	$bus_name = new Zend_Form_Element_Text('bus_name');
	$bus_name->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25');
				 $this->addElement($bus_name);
				 
	$bus_address = new Zend_Form_Element_Text('bus_address');
	$bus_address->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25');				 
	$this->addElement($bus_address);
	
	$bus_telno = new Zend_Form_Element_Text('bus_telno');
	$bus_telno->removeDecorator('label')
			    ->removeDecorator('HtmlTag')
		   		->addFilter('StringTrim')
				->setAttrib('size','25')
	   	        ->setAttrib('onKeypress','return numOnlyTel(event)')
				->addFilter('StripTags');
	$this->addElement($bus_telno);
	
	$bus_srcincome = new Zend_Form_Element_Select('bus_srcincome');
	$bus_srcincome->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('0','')
		 ->setAttrib('style','width: 155px')
         ->setValue('0');
         $table = new Model_CategoryValues();
		 $sql = $table->select()
	    ->where('name LIKE ?', 'BusinessSrcIncome')->order("seq ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $bus_srcincome->addMultiOption($c->seq, $c->values);} 
	$this->addElement($bus_srcincome);
	
	$bus_yrs = new Zend_Form_Element_Text('bus_yrs');
	$bus_yrs->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('onblur','busyrs(this.value)')
				 ->setAttrib('size','1')
				 ->setAttrib('onKeypress','return numOnlyPoint(event)');
	$this->addElement($bus_yrs);	
	
	$bus_income = new Zend_Form_Element_Text('bus_income');
	$bus_income->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25')
				 ->setAttrib('onKeypress','return numOnlyPeriod(event)')
   				 ->setAttrib('onBlur','busmonthlytoannual()');
	$this->addElement($bus_income);	
	
	$bus_multiplier = new Zend_Form_Element_Text('bus_multiplier');
	$bus_multiplier->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','5')
				 ->setValue(1)
 				 ->setAttrib('onKeypress','return numOnlyPeriod(event)');
	$this->addElement($bus_multiplier);	
	
	$bus_annual = new Zend_Form_Element_Text('bus_annual');
	$bus_annual->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25')
 				 ->setAttrib('onKeypress','return numOnlyPeriod(event)')
				 ->setAttrib('onBlur','busannualtomonthly()');
	$this->addElement($bus_annual);		
	
	$bus_pos = new Zend_Form_Element_Select('bus_pos');
	$bus_pos->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('0','')
		 ->setAttrib('style','width: 155px')
         ->setValue('0');
         $table = new Model_CategoryValues();
		 $sql = $table->select()
	    ->where('name LIKE ?', 'EmpPosition')
		->order("seq ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $bus_pos->addMultiOption($c->seq, $c->values);} 
	$this->addElement($bus_pos);
	
	
	$bus_nat = new Zend_Form_Element_Select('bus_nat');
	$bus_nat->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('0','')
		 ->setAttrib('style','width: 155px')
         ->setValue('0');
         $table = new Model_CategoryValues();
		 $sql = $table->select()
	    ->where('name LIKE ?', 'BusinessNature')->order("values ASC")
		->where('values NOT LIKE ?','N/A');
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $bus_nat->addMultiOption($c->seq, $c->values);} 
	$this->addElement($bus_nat);
	
	$bus_dti = new Zend_Form_Element_Text('bus_dti');
	$bus_dti->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25')
				 ->setAttrib('onKeypress','return numOnlyHyphen(event)');
	$this->addElement($bus_dti);
	
	$borrower_pres_address_no = new Zend_Form_Element_Text('borrower_pres_address_no');
	$borrower_pres_address_no->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','2');
	$this->addElement($borrower_pres_address_no);
	
	$borrower_pres_address_street = new Zend_Form_Element_Text('borrower_pres_address_street');
	$borrower_pres_address_street->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','30');
	$this->addElement($borrower_pres_address_street);

	$borrower_pres_address_brgy = new Zend_Form_Element_Select('borrower_pres_address_brgy');
	$borrower_pres_address_brgy->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->setAttrib('onchange','startZip()')
		 ->setRegisterInArrayValidator(false)
		 ->addFilter('StringTrim')
		->setAttrib('id', 'brgySelect');
	$this->addElement($borrower_pres_address_brgy);
	
	$borrower_pres_address_city = new Zend_Form_Element_Select('borrower_pres_address_city');
	$borrower_pres_address_city->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		->setRegisterInArrayValidator(false)
		 ->addFilter('StringTrim')
		->setAttrib('id', 'citySelect');
		//->setAttrib('readonly','readonly');
		$this->addElement($borrower_pres_address_city);
	
	$borrower_pres_address_province = new Zend_Form_Element_Select('borrower_pres_address_province');
	$borrower_pres_address_province->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->setRegisterInArrayValidator(false)
		 ->addFilter('StringTrim')
		 ->setAttrib('id', 'categorySelect')
		 		  ->setAttrib('onChange','clearrr(this.options[this.selectedIndex].value)')
		 ->addMultiOption('0', '');
		  //->setAttrib('readonly','readonly');
		 
		$table = new Model_ChainAddressProvince();
		$sql = $table->select()->order("seq ASC");
 		//$sql = $table->select()->where('element like ?','Metro Manila')->order("id ASC");
		 foreach ($table->fetchAll($sql,"province ASC") as $c) {
		$borrower_pres_address_province->addMultiOption($c->province, $c->province);} 
	
		
	$this->addElement($borrower_pres_address_province);


	
	$borrower_prev_address_no = new Zend_Form_Element_Text('borrower_prev_address_no');
	$borrower_prev_address_no->removeDecorator('label')
			  	 ->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','2');
	$this->addElement($borrower_prev_address_no);
	
	$borrower_prev_address_street = new Zend_Form_Element_Text('borrower_prev_address_street');
	$borrower_prev_address_street->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','30');
	$this->addElement($borrower_prev_address_street);
	
	$borrower_prev_address_brgy = new Zend_Form_Element_Select('borrower_prev_address_brgy');
	$borrower_prev_address_brgy->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->setAttrib('onchange','startZipPrev()')
		 ->setRegisterInArrayValidator(false)
		 ->addFilter('StringTrim')
		->setAttrib('id', 'prevbrgySelect');
	$this->addElement($borrower_prev_address_brgy);
	
	$borrower_prev_address_city = new Zend_Form_Element_Select('borrower_prev_address_city');
	$borrower_prev_address_city->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->setRegisterInArrayValidator(false)
		 ->addFilter('StringTrim')
		->setAttrib('id', 'prevcitySelect');
		 //->setAttrib('readonly','readonly');
	$this->addElement($borrower_prev_address_city);
	
	$borrower_prev_address_province = new Zend_Form_Element_Select('borrower_prev_address_province');
	$borrower_prev_address_province->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->setRegisterInArrayValidator(false)
		 ->addFilter('StringTrim')
		 ->setAttrib('id', 'prevcategorySelect')
		  ->setAttrib('onChange','clearr(this.options[this.selectedIndex].value)')
		 ->addMultiOption('0', '');
		  //->setAttrib('readonly','readonly')
		  
		$table = new Model_ChainAddressProvince();
		$sql = $table->select()->order("seq ASC");
 		//$sql = $table->select()->where('element like ?','Metro Manila')->order("id ASC");
		 foreach ($table->fetchAll($sql,"province ASC") as $c) {
		$borrower_prev_address_province->addMultiOption($c->province, $c->province);} 
	
		
		$this->addElement($borrower_prev_address_province);
	
	 $emp_zipcode = new Zend_Form_Element_Text('emp_zipcode');
	 $emp_zipcode->removeDecorator('label')
				//->setRegisterInArrayValidator(false)
				->removeDecorator('HtmlTag')
				->setAttrib('id', 'prevzipSelect')
				->setAttrib('readonly','readonly')
				->addFilter('StringTrim')
			 	->setAttrib('size','5')
				->addFilter('StripTags');
	$this->addElement($emp_zipcode);
	
	$bus_zipcode = new Zend_Form_Element_Text('bus_zipcode');
	$bus_zipcode->removeDecorator('label')
				//->setRegisterInArrayValidator(false)
				->removeDecorator('HtmlTag')
				->setAttrib('id', 'zipSelect')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
 				 ->setAttrib('size','5')
				 ->setAttrib('readonly','readonly');
	$this->addElement($bus_zipcode);	
	
	$emp_date = new Zend_Form_element_Text('emp_date');
	$emp_date->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
		     ->setAttrib('value','MM/DD/YYYY')
		     ->setValue('MM/DD/YYYY')
			 ->setAttrib('onmouseover','Tip("MM/DD/YYYY")')
			 ->setAttrib('onmouseout','UnTip()')
			 ->setAttrib('Maxlength',10)
  			 ->setAttrib('onkeyup','datefe(this.value)')
			 ->setAttrib('onclick','this.value=""')
			 ->setAttrib('onKeypress','return numOnlySlash(event)')
			 ->setAttrib('onBlur','ecompage(this.value)')
		     //->setAttrib('onclick','this.value=""')
		     ->setAttrib('size','15');
	$this->addElement($emp_date);
	
	$bus_date = new Zend_Form_element_Text('bus_date');
	$bus_date->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
		     ->setAttrib('value','MM/DD/YYYY')
		     ->setValue('MM/DD/YYYY')
			 ->setAttrib('Maxlength',10)
 			 ->setAttrib('onkeyup','datefs(this.value)')
			 ->setAttrib('onmouseover','Tip("MM/DD/YYYY")')
			 ->setAttrib('onmouseout','UnTip()')
			 ->setAttrib('onclick','this.value=""')
			 ->setAttrib('onKeypress','return numOnlySlash(event)')
			 ->setAttrib('onBlur','bcompage(this)')
		     //->setAttrib('onclick','this.value=""')
		     ->setAttrib('size','15');
	$this->addElement($bus_date);
	
	$name = new Zend_Form_Element_Text('name');
	$name->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25');
				 //->setAttrib('onKeypress','return alphaNumSpaceOnly(event)');
	$this->addElement($name);
	
	$contactperson = new Zend_Form_Element_Text('contactperson');
	$contactperson->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25');
				 //->setAttrib('onKeypress','return alphaNumSpaceOnly(event)');
	$this->addElement($contactperson);
	
	$contactno = new Zend_Form_Element_Text('contactno');
	$contactno->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25')
				 ->setAttrib('onKeypress','return numOnlyHyphen(event)');
	$this->addElement($contactno);
	
	$nat_transact = new Zend_Form_Element_Text('nat_transact');
	$nat_transact->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25');
				 //->setAttrib('onKeypress','return alphaNumSpaceOnly(event)');
	$this->addElement($nat_transact);
	
	$emp_months = new Zend_Form_Element_Select('emp_months');
	$emp_months->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('onmouseover','Tip("Months")')
		->setAttrib('onmouseout','UnTip()')
		 ->addMultiOption(0 ,'')
		 ->setAttrib('style','width: 50px')
		 ->setValue('0');
	for ($counter = 1; $counter <= 11; $counter += 1) {
        $emp_months->addMultiOption($counter, $counter);} 
	$this->addElement($emp_months);
	
	$bus_months = new Zend_Form_Element_Select('bus_months');
	$bus_months->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('onmouseover','Tip("Months")')
		->setAttrib('onmouseout','UnTip()')
		 ->addMultiOption(0 ,'')
		 ->setAttrib('style','width: 50px')
		 ->setValue('0');
	for ($counter = 1; $counter <= 11; $counter += 1) {
        $bus_months->addMultiOption($counter, $counter);} 
	$this->addElement($bus_months);
	
	$total_gross_sales = new Zend_Form_Element_Text('total_gross_sales');
	$total_gross_sales->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','10')
				 ->setAttrib('onKeypress','return numOnlyPoint(event)');
	$this->addElement($total_gross_sales);		
	
	$total_cost_sales = new Zend_Form_Element_Text('total_cost_sales');
	$total_cost_sales->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','10')
				 ->setAttrib('onKeypress','return numOnlyPoint(event)');
	$this->addElement($total_cost_sales);	

	$total_net_income_before = new Zend_Form_Element_Text('total_net_income_before');
	$total_net_income_before->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','10')
				 ->setAttrib('onKeypress','return numOnlyPoint(event)');
	$this->addElement($total_net_income_before);		
	
	$phone_ver = new Zend_Form_Element_Select('phone_ver');
	$phone_ver->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->addFilter('StringTrim')
		->addMultiOption('' ,'')
		->addMultiOption('NL' ,'NL')
		->addMultiOption('CTD' ,'CTD')	
		->setAttrib('style','width: 50px');
	$this->addElement($phone_ver);
	
	$emp_source_date = new Zend_Form_Element_Text('emp_source_date');
	$emp_source_date->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','40');
	$this->addElement($emp_source_date);
	
	$bus_source_date = new Zend_Form_Element_Text('bus_source_date');
	$bus_source_date->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','40');
	$this->addElement($bus_source_date);
	

	

	
	$emp_actual_position = new Zend_Form_Element_Text('emp_actual_position');
	$emp_actual_position->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','32');
				 $this->addElement($emp_actual_position);
				 
				 
	$emp_percentage = new Zend_Form_Element_Text('emp_percentage');
	$emp_percentage->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','5')
				 ->setAttrib('onKeypress','return numOnlyPeriod(event)');
	$this->addElement($emp_percentage);	
	
	$bus_percentage = new Zend_Form_Element_Text('bus_percentage');
	$bus_percentage->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','5')
				 ->setAttrib('onKeypress','return numOnlyPeriod(event)');
	$this->addElement($bus_percentage);	
	
	/**
	 * Paolo Marco Manarang
	 * paolomanarang@gmail.com
	 * March 02,2010
	 * Form Elements for Other Source Income
	*/
	
	$source_type = new Zend_Form_Element_Select('source_type');
	$source_type->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->addFilter('StringTrim')
		->addMultiOption('Tips' ,'Tips')
		->addMultiOption('Business Share' ,'Business Share')	
		->setAttrib('style','width: 50px');
	$this->addElement($source_type);
	
	$source_from = new Zend_Form_Element_Text('source_from');
	$source_from->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25');
	$this->addElement($source_from);
	
	$source_amount = new Zend_Form_Element_Text('source_amount');
	$source_amount->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25')
				 ->setAttrib('onKeypress','return numOnlyPeriod(event)');
	$this->addElement($source_amount);	
	
	$source_since = new Zend_Form_element_Text('source_since');
	$source_since->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
		     ->setAttrib('value','MM/DD/YYYY')
		     ->setValue('MM/DD/YYYY')
			 ->setAttrib('onmouseover','Tip("MM/DD/YYYY")')
			 ->setAttrib('onmouseout','UnTip()')
			 ->setAttrib('Maxlength',10)
			 ->setAttrib('onclick','this.value=""')
			 ->setAttrib('onKeypress','return numOnlySlash(event)')
		     ->setAttrib('size','10');
	$this->addElement($source_since);
	
	$source_remarks = new Zend_Form_Element_Text('source_remarks');
	$source_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','50');
	$this->addElement($source_remarks);
	
	/**
	 * Paolo Marco Manarang
	 * paolomanarang@gmail.com
	 * March 02,2010
	 * Form Elements for Other Monthly Income
	*/
	$monthly_type = new Zend_Form_Element_Select('monthly_type');
	$monthly_type->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->addFilter('StringTrim')
		->addMultiOption('' ,'Please Select..')
		->addMultiOption('Remittance' ,'Remittance')
		->addMultiOption('Deposit' ,'Deposit')	
		->addMultiOption('Interest Income' ,'Interest Income')	
		->addMultiOption('Pension' ,'Pension')	
		->addMultiOption('Others' ,'Others')	
		->setAttrib('style','width: 140px');
	$this->addElement($monthly_type);
	
	$monthly_from = new Zend_Form_Element_Text('monthly_from');
	$monthly_from->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25');
	$this->addElement($monthly_from);
	
	$monthly_amount = new Zend_Form_Element_Text('monthly_amount');
	$monthly_amount->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','25')
				 ->setAttrib('onKeypress','return numOnlyPeriod(event)');
	$this->addElement($monthly_amount);	
	
	$monthly_since = new Zend_Form_element_Text('monthly_since');
	$monthly_since->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
		     ->setAttrib('value','MM/DD/YYYY')
		     ->setValue('MM/DD/YYYY')
			 ->setAttrib('onmouseover','Tip("MM/DD/YYYY")')
			 ->setAttrib('onmouseout','UnTip()')
			 ->setAttrib('Maxlength',10)
			 ->setAttrib('onclick','this.value=""')
			 ->setAttrib('onKeypress','return numOnlySlash(event)')
		     ->setAttrib('size','10');
	$this->addElement($monthly_since);
	
	$monthly_remarks = new Zend_Form_Element_Text('monthly_remarks');
	$monthly_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','50');
	$this->addElement($monthly_remarks);
	

	}
	
}


?>
