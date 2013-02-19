<?
class Form_AccountPage extends Zend_Dojo_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);



	$borrower_fname = new Zend_Form_Element_Text('borrower_fname');
	$borrower_fname->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25')
				 ->setAttrib('id','paolo')
				 ->setAttrib('onmouseover','Tip("First Name")')
				 ->setAttrib('onmouseout','UnTip()')
				 ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($borrower_fname);
	
	$borrower_lname = new Zend_Form_Element_Text('borrower_lname');
	$borrower_lname->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25')
				 ->setAttrib('onmouseover','Tip("Last Name")')
				 ->setAttrib('onmouseout','UnTip()')
				 ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($borrower_lname);
	
	
	$birthdate = new Zend_Form_element_Text('birthdate');
	$birthdate->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
		     ->setAttrib('value','MM/DD/YYYY')
		     ->setValue('MM/DD/YYYY')
 			 ->setAttrib('onkeyup','datef(this.value)')
			 ->setAttrib('onmouseover','Tip("MM/DD/YYYY")')
			 ->setAttrib('onmouseout','UnTip()')
			 ->setAttrib('Maxlength',10)
			 ->setAttrib('onKeypress','return numOnlySlash(event)')
			 ->setAttrib('onBlur','compage(this)')
		     //->setAttrib('onclick','this.value=""')
		     ->setAttrib('size','15');
	$this->addElement($birthdate);
	
	/*
		$this->addElement(
                'DateTextBox',
                'birthdate',
                array(
                 'style' => 'width:100px',
                )
            );
		$this->birthdate->removeDecorator('HtmlTag', array('tag' => 'dt')); 
		$this->birthdate->removeDecorator('Label'); 
		*/

			
	$birthplace = new Zend_Form_Element_Text('birthplace');
	$birthplace->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('onKeypress','return alphaOnly(event)')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','15');
	$this->addElement($birthplace);
	
	$borrower_mname = new Zend_Form_Element_Text('borrower_mname');
	$borrower_mname->removeDecorator('label')
				 ->removeDecorator('HtmlTag')
				  ->addFilter('StringTrim')
				  ->addFilter('StripTags')
				  ->addFilter('StringToUpper')
				  ->setAttrib('size','25')
  				 ->setAttrib('onmouseover','Tip("Middle Name")')
				 ->setAttrib('onmouseout','UnTip()')
				  ->setAttrib('onKeypress','return alphaOnlyPeriod(event)');
	$this->addElement($borrower_mname);
	
	$nickname = new Zend_Form_Element_Text('nickname');
	$nickname->removeDecorator('label')
				 ->removeDecorator('HtmlTag')
				  ->addFilter('StringTrim')
				  ->addFilter('StripTags')
				  ->addFilter('StringToUpper')
				  ->setAttrib('size','15')
				  ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($nickname);

	$borrower_pres_address_no = new Zend_Form_Element_Text('borrower_pres_address_no');
	$borrower_pres_address_no->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','2');
	$this->addElement($borrower_pres_address_no);
	
	$borrower_pres_address_st = new Zend_Form_Element_Text('borrower_pres_address_st');
	$borrower_pres_address_st->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','30');
	$this->addElement($borrower_pres_address_st);
	
	/*
	$borrower_pres_address_brgy = new Zend_Form_Element_Text('borrower_pres_address_brgy');
	$borrower_pres_address_brgy->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','30');
	$this->addElement($borrower_pres_address_brgy);
	*/
	/*
	 $borrower_pres_address_city = new Zend_Form_Element_Text('borrower_pres_address_city');
	 $borrower_pres_address_city->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','35');
	$this->addElement($borrower_pres_address_city);
	*/
	

	
	
	$borrower_pres_address_brgy = new Zend_Form_Element_Select('borrower_pres_address_brgy');
	$borrower_pres_address_brgy->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		->setRegisterInArrayValidator(false)
		 ->addFilter('StringTrim')
		 ->setAttrib('onchange','startZip()')
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
		 ->setAttrib('id', 'categorySelect');
		  //->setAttrib('readonly','readonly');
		$table = new Model_ChainAddressProvince();
		$sql = $table->select()->order("seq ASC");
 		//$sql = $table->select()->where(' like ?','Metro Manila')->order("id ASC");
		 foreach ($table->fetchAll($sql,"province ASC") as $c) {
		$borrower_pres_address_province->addMultiOption($c->province, $c->province);} 
		
	$this->addElement($borrower_pres_address_province);
	
	 $pres_zipcode = new Zend_Form_Element_Text('pres_zipcode');
	 $pres_zipcode->removeDecorator('label')
				//->setRegisterInArrayValidator(false)
				->removeDecorator('HtmlTag')
				->setAttrib('id', 'zipSelect')
				->setAttrib('size','5')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('readonly','readonly');
	$this->addElement($pres_zipcode);

	$typeloan = new Zend_Form_Element_Select('typeloan');
	$typeloan->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
	   	 ->addMultiOption('0','Please Select...')
		 ->addMultiOption('1','Auto Loan')
		 ->addMultiOption('2','Housing Loan')
		 ->setValue('0');
	$this->addElement($typeloan);

	$source_application = new Zend_Form_Element_Select('source_application');
	$source_application->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('onchange','srcapp(this.value)')
         ->setValue('0');
         $table = new Model_ListSelectValues();
	$sql = $table->select()->where('type like ?','SourceApplication')->
	where("value = 'Branch' or value = 'Dealer'")->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $source_application->addMultiOption($c->value, $c->value);}
	$this->addElement($source_application);
	

	$promo_avail = new Zend_Form_Element_Select('promo_avail');
	$promo_avail->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('onChange','displaySubs(this.options[this.selectedIndex].text)')
		 ->setValue('0')
		->addMultiOption('0','No')
		->addMultiOption('1','Yes');	
		     
	$this->addElement($promo_avail);
	
	$promo = new Zend_Form_Element_Select('promo');
	$promo->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
	     ->setValue('1');
         $table = new Model_Promo();
		 foreach ($table->fetchAll(null,"id ASC") as $c) {
         $promo->addMultiOption($c->id, $c->promo_name);} 
	$this->addElement($promo);
	
	$landline = new Zend_Form_Element_Text('landline');
	$landline->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnlyTel(event)')
				 ->setAttrib('size','15');
	$this->addElement($landline);
	
	$mobile = new Zend_Form_Element_Text('mobile');
	$mobile->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnlyTel(event)')
				 ->setAttrib('size','15');
	$this->addElement($mobile);
	
	$email = new Zend_Form_Element_Text('email');
	$email->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('class','html-text-box')
				 ->setAttrib('size','15');
				 
	$this->addElement($email);
	
	$gender = new Zend_Form_Element_Select('gender');
	$gender->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption(0 ,'')
	     ->setValue('0');
		 $table = new Model_CategoryValues();
		 $sql = $table->select()
	    ->where('name LIKE ?', 'Gender')->order("seq ASC");
		 foreach ($table->fetchAll($sql,"seq ASC") as $c) {
         $gender->addMultiOption($c->seq, $c->values);} 
	$this->addElement($gender);
	
	$civilstatus = new Zend_Form_Element_Select('civilstatus');
	$civilstatus->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption(0 ,'')
	     ->setValue('0')
 		 ->setAttrib('onChange','displaySubs(this.options[this.selectedIndex].text)');
		 $table = new Model_CategoryValues();
		 $sql = $table->select()
	    ->where('name LIKE ?', 'CivilStatus')->order("seq ASC");
		 foreach ($table->fetchAll($sql,"seq ASC") as $c) {
         $civilstatus->addMultiOption($c->seq, $c->values);} 
	$this->addElement($civilstatus);
	
	$borrower_spouse_fname = new Zend_Form_Element_Text('borrower_spouse_fname');
	$borrower_spouse_fname->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','30')
				 ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($borrower_spouse_fname);
	
	$borrower_spouse_lname = new Zend_Form_Element_Text('borrower_spouse_lname');
	$borrower_spouse_lname->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','30')
				 ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($borrower_spouse_lname);
	
	$borrower_spouse_mname = new Zend_Form_Element_Text('borrower_spouse_mname');
	$borrower_spouse_mname->removeDecorator('label')
				 ->removeDecorator('HtmlTag')
				  ->addFilter('StringTrim')
				  ->addFilter('StripTags')
				  ->addFilter('StringToUpper')
				  ->setAttrib('size','30')
				  ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($borrower_spouse_mname);
	
	$birthdate_spouse = new Zend_Form_element_Text('birthdate_spouse');
	$birthdate_spouse->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
		     ->setAttrib('onkeyup','datef(this.value)')
		     ->setAttrib('value','MM/DD/YYYY')
		     ->setValue('MM/DD/YYYY')
		     //->setAttrib('onclick','this.value=""')
			 //->addValidator('regex',false,'(0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])[- /.](19|20)[0-9]{2}')
		     ->setAttrib('onKeypress','return numOnlySlash(event)')
			 ->setAttrib('size','15');
	$this->addElement($birthdate_spouse);
	
	$score = new Zend_Form_Element_Text('score');
	$score->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
			 ->setAttrib('disabled','disabled')
			 ->setAttrib('size','10');
	$this->addElement($score);
	
	$dealercoordinator = new Zend_Form_Element_Select('dealercoordinator');
	$dealercoordinator->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption(0 ,'')
		 ->setAttrib('style','width: 120px')
	     ->setValue('0');
		 $table = new Model_Users();
		$sql = $table->select()
	    ->where('role_type LIKE ?', 'DC')->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $dealercoordinator->addMultiOption($c->username, $c->name);} 
	$this->addElement($dealercoordinator);
	
	$marketingassistant = new Zend_Form_Element_Select('marketingassistant');
	$marketingassistant->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption(0 ,'')
		 ->setAttrib('style','width: 120px')
	     ->setValue('0');
		 /* Add Marketing Assistant
		 $table = new Model_ListDealer();
		 foreach ($table->fetchAll(null,"id ASC") as $c) {
         $marketingassistant->addMultiOption($c->id, $c->name);} 
         */
	$this->addElement($marketingassistant);
	
	$creditanalyst = new Zend_Form_Element_Select('creditanalyst');
	$creditanalyst->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption(0 ,'')
		 ->setAttrib('style','width: 120px')
	     ->setValue('0');
		 $table = new Model_Users();
		$sql = $table->select()
	    ->where('role_type LIKE ?', 'CA')->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $creditanalyst->addMultiOption($c->username, $c->name);} 
         
	$this->addElement($creditanalyst);
	
	$creditofficer = new Zend_Form_Element_Select('creditofficer');
	$creditofficer->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption(0 ,'')
		 ->setAttrib('style','width: 120px')
	     ->setValue('0');
		 $table = new Model_Users();
		$sql = $table->select()	    ->where('role_type LIKE ?', 'CO')->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $creditofficer->addMultiOption($c->username, $c->name);} 
         
	$this->addElement($creditofficer);
	
	$lenghtstay = new Zend_Form_Element_Text('lenghtstay');
	$lenghtstay->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('id', 'lenghtstay')
				  ->setAttrib('onKeypress','return numOnlyPeriod(event)')
				  ->setAttrib('onBlur','javascript:clearprev(this.value)')
				->setAttrib('onmouseover','Tip("Year")')
				 ->setAttrib('onmouseout','UnTip()')
				 //->setAttrib('size','1');
				  ->setAttrib('style','width: 20px');
	$this->addElement($lenghtstay);
	
	$lenght_months = new Zend_Form_Element_Select('lenght_months');
	$lenght_months->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('onmouseover','Tip("Months")')
		->setAttrib('onmouseout','UnTip()')
		->setAttrib('onchange','clearprev(0)')
		 ->addMultiOption(0 ,'')
		 ->setAttrib('style','width: 50px')

		 ->setValue('0');
	for ($counter = 1; $counter <= 11; $counter += 1) {
        $lenght_months->addMultiOption($counter, $counter);} 
	$this->addElement($lenght_months);
	
	
	$borrower_prev_address_no = new Zend_Form_Element_Text('borrower_prev_address_no');
	$borrower_prev_address_no->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','2');
	$this->addElement($borrower_prev_address_no);
	
	$borrower_prev_address_st = new Zend_Form_Element_Text('borrower_prev_address_st');
	$borrower_prev_address_st->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('id', 'prevstreetSelect')
				 ->setAttrib('size','30');
	$this->addElement($borrower_prev_address_st);
	
	$borrower_prev_address_brgy = new Zend_Form_Element_Select('borrower_prev_address_brgy');
	$borrower_prev_address_brgy->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->setRegisterInArrayValidator(false)
		->setAttrib('onchange','startZipPrev()')
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
		
	
	 $prev_zipcode = new Zend_Form_Element_Text('prev_zipcode');
	 $prev_zipcode->removeDecorator('label')
				//->setRegisterInArrayValidator(false)
				->removeDecorator('HtmlTag')
				->setAttrib('id', 'prevzipSelect')
				->setAttrib('readonly','readonly')
				 ->setAttrib('size','5')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags');
	$this->addElement($prev_zipcode);
	
	$maiden_name = new Zend_Form_Element_Text('maiden_name');
	$maiden_name->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('onKeypress','return alphaOnly(event)')
		 ->setAttrib('size','25')
		 ->addFilter('StripTags');
	$this->addElement($maiden_name);
	
	$residencetype = new Zend_Form_Element_Select('residencetype');
	$residencetype->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 100px')
		 ->addMultiOption(0 ,'')
		->setValue('0');
		 $table = new Model_CategoryValues();
		 $sql = $table->select()
	    ->where('name LIKE ?', 'ResidenceType')->order("seq ASC");
		 foreach ($table->fetchAll($sql,"seq ASC") as $c) {
         $residencetype->addMultiOption($c->seq, $c->values);} 
	$this->addElement($residencetype);
	
	$neighborhoodtype = new Zend_Form_Element_Select('neighborhoodtype');
	$neighborhoodtype->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 100px')
		 ->addMultiOption(0 ,'')
	     ->setValue('0');
		 $table = new Model_CategoryValues();
		 $sql = $table->select()
	    ->where('name LIKE ?', 'NeighborhoodType')
		->order("seq ASC");
		 foreach ($table->fetchAll($sql,"seq ASC") as $c) {
         $neighborhoodtype->addMultiOption($c->seq, $c->values);} 
	$this->addElement($neighborhoodtype);
	
	$dependentno = new Zend_Form_Element_Text('dependentno');
	$dependentno->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnly(event)')
				 ->setAttrib('size','1');
	$this->addElement($dependentno);
	
	$citizenship = new Zend_Form_Element_Select('citizenship');
	$citizenship->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 100px')
		 ->addMultiOption(0 ,'')
	     ->setValue('0');
		 $table = new Model_CategoryValues();
		 $sql = $table->select()
	    ->where('name LIKE ?', 'Citizenship')->order("seq ASC");
		 foreach ($table->fetchAll($sql,"seq ASC") as $c) {
         $citizenship->addMultiOption($c->seq, $c->values);} 
	$this->addElement($citizenship);
	
	$tin_id = new Zend_Form_Element_Text('tin_id');
	$tin_id->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','10')
				 ->setAttrib('onKeypress','return numOnlyHyphen(event)');
	$this->addElement($tin_id);
	
	$relation = new Zend_Form_Element_Hidden('relation');
	$relation->removeDecorator('label')
		     ->removeDecorator('HtmlTag');
	$this->addElement($relation);
	
	$profile = new Zend_Form_Element_Select('profile');
	$profile->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 290px')
		 ->setAttrib('onChange',"window.location=this.options[this.selectedIndex].value")
		 ->setAttrib('id', 'prof')
		 ->addMultiOption(0 ,'Select Profile')
		 ->setValue('0');

	$this->addElement($profile);
	
	/*
	
	
	
	$veh_brand = new Zend_Form_Element_Text('veh_brand');
	$veh_brand->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
			->setAttrib('onBlur','javascript: copyunit();calcActDownpayment();')
			->setAttrib('onChange','calcActDownpayment();')
		 ->setAttrib('id','vehbrandSelect')
		 ->setAttrib('style','width: 150px')
	 	//->setAttrib('onChange','displaySubs0(this.options[this.selectedIndex].text)')
		 ->addMultiOption('0','');
		$table = new Model_ChainVehicleBrand();
		$sql = $table->select()->order("brand ASC")->distinct();
		foreach ($table->fetchAll($sql,"brand ASC") as $c) {
	$veh_brand->addMultiOption($c->brand, $c->brand);} 
	$this->addElement($veh_brand);
	
	$veh_unit = new Zend_Form_Element_Select('veh_unit');
	$veh_unit->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 150px')
			->setAttrib('id','vehunitSelect')
			->setAttrib('onBlur','javascript: copyunit();calcActDownpayment();')
			->setAttrib('onChange','calcActDownpayment();')
 			//->setAttrib('onBlur','javascript: copyunit()')
			->addMultiOption(0,'');
	$this->addElement($veh_unit);
	
	$selling_price2 = new Zend_Form_Element_Select('selling_price2');
	$selling_price2->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		->setRegisterInArrayValidator(false)	 
		 ->setAttrib('id','vehsellSelect')
		 ->addMultiOption(0, '');
		 
		// ->setAttrib('onChange','javascript:calcActDownpayment();calcActDownpayment();');
 		// ->setAttrib('onChange','displaySubs2(this.options[this.selectedIndex].text)')

		 //->setAttrib('style','width: 150px');
		 $this->addElement($selling_price2);
		 
		 	$veh_type = new Zend_Form_Element_Select('veh_type');
	$veh_type->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 120px')
		 ->addMultiOption(0 ,'')
		 ->setAttrib('id','vehtypeSelect')
	     ->setValue('0');
	     /*
		 $table = new Model_CategoryValues();
		 $sql = $table->select()
	     ->where('name LIKE ?', 'VehType')->order("seq ASC");
		 foreach ($table->fetchAll($sql,"seq ASC") as $c) {
         $veh_type->addMultiOption($c->seq, $c->values);} 
	$this->addElement($veh_type);
	*/
	/****
	New Pricelist Form Elements March 30,2010
	*/
	$veh_brand = new Zend_Form_Element_Text('veh_brand');
	$veh_brand->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('ReadOnly','ReadOnly')
		 ->setAttrib('id','vehbrandSelect');
	$this->addElement($veh_brand);
	
	$veh_unit = new Zend_Form_Element_Text('veh_unit');
	$veh_unit->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 150px')
			->setAttrib('ReadOnly','ReadOnly')
			->setAttrib('id','vehunitSelect');
	$this->addElement($veh_unit);
	
	$selling_price2 = new Zend_Form_Element_Text('selling_price2');
	$selling_price2->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		  ->setAttrib('style','width: 80px')
		 ->setAttrib('ReadOnly','ReadOnly')
		 ->setAttrib('id','vehsellSelect');
		 $this->addElement($selling_price2);
		 
	$veh_year = new Zend_Form_Element_Text('veh_year');
	$veh_year->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('ReadOnly','ReadOnly');
	$this->addElement($veh_year);
	
	$veh_month = new Zend_Form_Element_Text('veh_month');
	$veh_month->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('ReadOnly','ReadOnly');
	$this->addElement($veh_month);
	
	$veh_id = new Zend_Form_Element_Hidden('veh_id');
	$veh_id->removeDecorator('label')
			->setAttrib('id','vehunitId')
			->removeDecorator('HtmlTag');
	$this->addElement($veh_id);
	
	
	/****
	End of New Pricelist Form Elements March 30,2010
	*/
	/*
	$dealer = new Zend_Form_Element_Text('dealer');
	$dealer->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('ReadOnly','ReadOnly')
			->setAttrib('id','vehdealer')
			->setAttrib('style','width: 150px');
	$this->addElement($dealer);
	*/
	$dealer = new Zend_Dojo_Form_Element_ComboBox('dealer');
	$dealer->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 150px')
			->addMultiOption(0,'');
		$table = new Model_ListDealer();
		$sql = $table->select()->order("name ASC");
		 foreach ($table->fetchAll($sql,"seq ASC") as $c) {
         $dealer->addMultiOption($c->name, $c->name);} 
	$this->addElement($dealer);
	
	$veh_type = new Zend_Form_Element_Text('veh_type');
	$veh_type->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 120px')
 		 ->setAttrib('ReadOnly','ReadOnly')
		 ->setAttrib('id','vehtypeSelect');
	$this->addElement($veh_type);
	
	$selling_price = new Zend_Form_Element_Text('selling_price');
	$selling_price->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('id','sellprice')
				 ->setAttrib('ReadOnly','ReadOnly')
				->setAttrib('onKeypress','return numOnly(event)')
				//->setAttrib('style','display:none')
				->setAttrib('size','10');
	$this->addElement($selling_price);
	
	
	
	$veh_discount = new Zend_Form_Element_Text('veh_discount');
	$veh_discount->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('onBlur','javascript: vehdiscountvalue();')
				 //->setAttrib('readonly','readonly')
				 ->setAttrib('size','10');
	$this->addElement($veh_discount);

	$veh_yrmodel = new Zend_Form_Element_Text('veh_yrmodel');
	$veh_yrmodel->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','5')
				 ->setAttrib('maxlength',4)
				 ->setAttrib('onKeyprespress','return numOnly(event)');
	$this->addElement($veh_yrmodel);
	
	$veh_status = new Zend_Form_Element_Select('veh_status');
	$veh_status->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 100px')
		 ->addMultiOption(0 ,'')
 		 ->setAttrib('onchange','yrmodel(this.value)')
		->setValue('0');
		 $table = new Model_CategoryValues();
		 $sql = $table->select()
	     ->where('name LIKE ?', 'VehStatus')->order("seq ASC");
		 foreach ($table->fetchAll($sql,"seq ASC") as $c) {
         $veh_status->addMultiOption($c->seq, $c->values);} 
	$this->addElement($veh_status);
	
	$veh_age = new Zend_Form_Element_Text('veh_age');
	$veh_age->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','3')
				->setAttrib('maxlength',3)
				->setAttrib('onblur','comvehage(this.value)')
				->setAttrib('onKeypress','return numOnly(event)');
	$this->addElement($veh_age);
	

	
	$veh_use = new Zend_Form_Element_Select('veh_use');
	$veh_use->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 120px')
		 ->addMultiOption(0 ,'')
	     ->setValue('0');
		 $table = new Model_CategoryValues();
		 $sql = $table->select()
	     ->where('name LIKE ?', 'VehUse')->order("seq ASC");
		 foreach ($table->fetchAll($sql,"seq ASC") as $c) {
         $veh_use->addMultiOption($c->seq, $c->values);}  
		
	$this->addElement($veh_use);
	
	$motor_no = new Zend_Form_Element_Text('motor_no');
	$motor_no->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','14')
				 ->setAttrib('onKeypress','return numOnlyHyphen(event)');
	$this->addElement($motor_no);
	
	$serial_no = new Zend_Form_Element_Text('serial_no');
	$serial_no->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','14')
				 ->setAttrib('onKeypress','return numOnlyHyphen(event)');
	$this->addElement($serial_no);
	
	$or_no = new Zend_Form_Element_Text('or_no');
	$or_no->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','14')
				 ->setAttrib('onKeypress','return numOnlyHyphen(event)');
	$this->addElement($or_no);
	
	$cr_no = new Zend_Form_Element_Text('cr_no');
	$cr_no->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','14')
				 ->setAttrib('onKeypress','return numOnlyHyphen(event)');
	$this->addElement($cr_no);
	
	$plate_no = new Zend_Form_Element_Text('plate_no');
	$plate_no->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','14')
				 ->setAttrib('onKeypress','return numOnlyHyphen(event)');
	$this->addElement($plate_no);
	
			$this->addElement(
                'DateTextBox',
                'or_date',
                array(
                 'style' => 'width:100px',
                )
            );
		$this->or_date->removeDecorator('HtmlTag', array('tag' => 'dt')); 
		$this->or_date->removeDecorator('Label'); 
	
				$this->addElement(
                'DateTextBox',
                'cr_date',
                array(
                 'style' => 'width:100px',
                )
            );
		$this->cr_date->removeDecorator('HtmlTag', array('tag' => 'dt')); 
		$this->cr_date->removeDecorator('Label'); 
		
		$this->addElement(
                'DateTextBox',
                'ref_date',
                array(
                 'style' => 'width:100px',
                )
            );
		$this->ref_date->removeDecorator('HtmlTag', array('tag' => 'dt')); 
		$this->ref_date->removeDecorator('Label'); 
		
		$this->addElement(
                'DateTextBox',
                'uv_date',
                array(
                 'style' => 'width:100px',
                )
            );
		$this->uv_date->removeDecorator('HtmlTag', array('tag' => 'dt')); 
		$this->uv_date->removeDecorator('Label'); 
		
		$this->addElement(
                'DateTextBox',
                'date_delivered',
                array(
                 'style' => 'width:100px',
                )
            );
		$this->date_delivered->removeDecorator('HtmlTag', array('tag' => 'dt')); 
		$this->date_delivered->removeDecorator('Label'); 
		
		$this->addElement(
                'DateTextBox',
                'last_seen',
                array(
                 'style' => 'width:100px',
                )
            );
		$this->last_seen->removeDecorator('HtmlTag', array('tag' => 'dt')); 
		$this->last_seen->removeDecorator('Label'); 
	
		$ref_no = new Zend_Form_Element_Text('ref_no');
		$ref_no->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','14')
				 ->setAttrib('onKeypress','return numOnlyHyphen(event)');
		$this->addElement($ref_no);
		
	$ref_doc = new Zend_Form_Element_Text('ref_doc');
	$ref_doc->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','14')
				 ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($ref_doc);
	
	$lto_off = new Zend_Form_Element_Text('lto_off');
	$lto_off->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','40');
	$this->addElement($lto_off);
	
	$veh_color = new Zend_Form_Element_Text('veh_color');
	$veh_color->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','14');
	$this->addElement($veh_color);
	


		
	$downpayment_actual = new Zend_Form_Element_Text('downpayment_actual');
	$downpayment_actual->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
 				 ->setAttrib('onblur','return calcPerDownpayment()')
				  ->setAttrib('onKeypress','return numOnly(event)')
				 ->setAttrib('size','15');
	$this->addElement($downpayment_actual);
	
	$downpayment_percent = new Zend_Form_Element_Text('downpayment_percent');
	$downpayment_percent->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('onKeypress','return numOnly(event)')
				 ->setAttrib('onblur','return calcActDownpayment()')
				 ->setAttrib('size','1');
	$this->addElement($downpayment_percent);

	$loanterm = new Zend_Form_Element_Text('loanterm');
	$loanterm->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('onchange','calcActDownpayment()')
				  ->setAttrib('onKeypress','return numOnlyPeriod(event)')
				 ->setAttrib('size','15');
	$this->addElement($loanterm);
	
	$amountloan = new Zend_Form_Element_Text('amountloan');
	$amountloan->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('readonly','readonly')
				  ->setAttrib('onKeypress','return numOnly(event)')
				 ->setAttrib('size','15');
	$this->addElement($amountloan);
	
	$gmi_ratio = new Zend_Form_Element_Text('gmi_ratio');
	$gmi_ratio->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('readonly','readonly')
				 //->setAttrib('onmouseover','calcfields()')
 				 //->setAttrib('onblur','calcfields()')
				  ->setAttrib('onKeypress','return numOnly(event)')
				 ->setAttrib('size','5');
	$this->addElement($gmi_ratio);
	
	$monthly_amortization = new Zend_Form_Element_Text('monthly_amortization');
	$monthly_amortization->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('readonly','readonly')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnly(event)')
				 ->setAttrib('size','15');
	$this->addElement($monthly_amortization);
	
	$addon_rate1 = new Zend_Form_Element_Radio('addon_rate1');
	$addon_rate1->removeDecorator('label')
			->removeDecorator('HtmlTag')
			//->setAttrib('Disabled','Disabled')
			->addMultiOption('OMA','OMA')
			->addMultiOption('Standard','Standard');
	$this->addElement($addon_rate1);
	
	$addon_rate2 = new Zend_Form_Element_Radio('addon_rate2');
	$addon_rate2->removeDecorator('label')
			->removeDecorator('HtmlTag')
			//->setAttrib('Disabled','Disabled')
			->addMultiOption('arrears','Arrears');
			
	$this->addElement($addon_rate2);
	
	$rate = new Zend_Form_Element_Text('rate');
	$rate->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
  				 ->setAttrib('onchange','calcActDownpayment()')
   				  ->setAttrib('onKeypress','return numOnlyPeriod(event)')
				 ->setAttrib('size','5');
	$this->addElement($rate);

	$totalcombineincome = new Zend_Form_Element_Hidden('totalcombineincome');
	$totalcombineincome->removeDecorator('label')
		     ->removeDecorator('HtmlTag');
	$this->addElement($totalcombineincome);
	

	$remarks_bap = new Zend_Form_Element_Text('remarks_bap');
	$remarks_bap->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','50');
				// ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($remarks_bap);
	
	$remarks_nfis = new Zend_Form_Element_Text('remarks_nfis');
	$remarks_nfis->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')

				 ->setAttrib('size','64');
				// ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($remarks_nfis);
	
	$remarks_cmap = new Zend_Form_Element_Text('remarks_cmap');
	$remarks_cmap->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','64');
				 //->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($remarks_cmap);
	
	$remarks_srcincomever = new Zend_Form_Element_Text('remarks_srcincomever');
	$remarks_srcincomever->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','50');
				 //->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($remarks_srcincomever);
	
	$remarks_empver = new Zend_Form_Element_Text('remarks_empver');
	$remarks_empver->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','64');
				 //->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($remarks_empver);
	
	$remarks_busver = new Zend_Form_Element_Text('remarks_busver');
	$remarks_busver->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','64');
				 //->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($remarks_busver);
	
	$remarks_trdchk = new Zend_Form_Element_Text('remarks_trdchk');
	$remarks_trdchk->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','64');
				 //->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($remarks_trdchk);
	
	
	$remarks_backgrd = new Zend_Form_Element_Text('remarks_backgrd');
	$remarks_backgrd->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','64');
				 //->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($remarks_backgrd);
	
	$remarks_bankref = new Zend_Form_Element_Text('remarks_bankref');
	$remarks_bankref->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','64');
				 //->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($remarks_bankref);
		
	$remarks_creditchk = new Zend_Form_Element_Text('remarks_creditchk');
	$remarks_creditchk->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','64');
				// ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($remarks_creditchk);
	
	$remarks_pastdealings = new Zend_Form_Element_Text('remarks_pastdealings');
	$remarks_pastdealings->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','64');
				 //->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($remarks_pastdealings);
	
	$bap = new Zend_Form_Element_Radio('bap');
	$bap->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setValue('arrears')
			->addMultiOption('favorable','')
			->addMultiOption('unfavorable','');
	$this->addElement($bap);
	
	
		
	$remarks_srcincomever_ci = new Zend_Form_Element_Text('remarks_srcincomever_ci');
	$remarks_srcincomever_ci->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','50');
				 //->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($remarks_srcincomever_ci);
	
	$remarks_empver_ci = new Zend_Form_Element_Text('remarks_empver_ci');
	$remarks_empver_ci->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','64');
				 //->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($remarks_empver_ci);
	
	$remarks_busver_ci = new Zend_Form_Element_Text('remarks_busver_ci');
	$remarks_busver_ci->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','64');
				 //->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($remarks_busver_ci);
	
	$remarks_trdchk_ci = new Zend_Form_Element_Text('remarks_trdchk_ci');
	$remarks_trdchk_ci->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','64');
				 //->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($remarks_trdchk_ci);
	
	
	$remarks_backgrd_ci = new Zend_Form_Element_Text('remarks_backgrd_ci');
	$remarks_backgrd_ci->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','64');
				 //->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($remarks_backgrd_ci);
	
	$model_cv_srcincomever = new Zend_Form_Element_Checkbox('model_cv_srcincomever');
	$model_cv_srcincomever->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('id','model_cv_srcincomever')
			->setAttrib('onchange','javascript:document.getElementById("model_ci_srcincomever").checked = false;');
	$this->addElement($model_cv_srcincomever);
	
	$model_cv_empver = new Zend_Form_Element_Checkbox('model_cv_empver');
	$model_cv_empver->removeDecorator('label')
			->setAttrib('id','model_cv_empver')
			->setAttrib('onchange','javascript:document.getElementById("model_ci_empver").checked = false;')
			->removeDecorator('HtmlTag');
	$this->addElement($model_cv_empver);
	
	$model_cv_busver = new Zend_Form_Element_Checkbox('model_cv_busver');
	$model_cv_busver->removeDecorator('label')
			->setAttrib('id','model_cv_busver')
			->setAttrib('onchange','javascript:document.getElementById("model_ci_busver").checked = false;')
			->removeDecorator('HtmlTag');
	$this->addElement($model_cv_busver);
	
	$model_cv_trdchk = new Zend_Form_Element_Checkbox('model_cv_trdchk');
	$model_cv_trdchk->removeDecorator('label')
			->setAttrib('id','model_cv_trdchk')
			->setAttrib('onchange','javascript:document.getElementById("model_ci_trdchk").checked = false;')
			->removeDecorator('HtmlTag');
			$this->addElement($model_cv_trdchk);
	
	$model_cv_backgrd = new Zend_Form_Element_Checkbox('model_cv_backgrd');
	$model_cv_backgrd->removeDecorator('label')
			->setAttrib('id','model_cv_backgrd')
			->setAttrib('onchange','javascript:document.getElementById("model_ci_backgrd").checked = false;')
			->setAttrib('Style','Display:None')
			->removeDecorator('HtmlTag');
	$this->addElement($model_cv_backgrd);
	
	$model_ci_srcincomever = new Zend_Form_Element_Checkbox('model_ci_srcincomever');
	$model_ci_srcincomever->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('id','model_ci_srcincomever')
			->setAttrib('onchange','javascript:document.getElementById("model_cv_srcincomever").checked = false;');
	$this->addElement($model_ci_srcincomever);
	
	$model_ci_empver = new Zend_Form_Element_Checkbox('model_ci_empver');
	$model_ci_empver->removeDecorator('label')
			->setAttrib('onchange','javascript:document.getElementById("model_cv_empver").checked = false;')
			->removeDecorator('HtmlTag');
	$this->addElement($model_ci_empver);
	
	$model_ci_busver = new Zend_Form_Element_Checkbox('model_ci_busver');
	$model_ci_busver->removeDecorator('label')
			->setAttrib('onchange','javascript:document.getElementById("model_cv_busver").checked = false;')
			->setAttrib('id','model_ci_busver')
			->removeDecorator('HtmlTag');
	$this->addElement($model_ci_busver);
	
	$model_ci_trdchk = new Zend_Form_Element_Checkbox('model_ci_trdchk');
	$model_ci_trdchk->removeDecorator('label')
			->setAttrib('onchange','javascript:document.getElementById("model_cv_trdchk").checked = false;')
			->setAttrib('id','model_ci_trdchk')
			->removeDecorator('HtmlTag');
	$this->addElement($model_ci_trdchk);
	
	$model_ci_backgrd = new Zend_Form_Element_Checkbox('model_ci_backgrd');
	$model_ci_backgrd->removeDecorator('label')
			->setAttrib('onchange','javascript:document.getElementById("model_cv_backgrd").checked = false;')
			->setAttrib('id','model_ci_backgrd')
			->setAttrib('Style','Display:None')
			->removeDecorator('HtmlTag');
	$this->addElement($model_ci_backgrd);
	
	$app_form = new Zend_Form_Element_Checkbox('app_form');
	$app_form->removeDecorator('label')	
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($app_form);
	
	$valid_id = new Zend_Form_Element_Checkbox('valid_id');
	$valid_id->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($valid_id);
	
	$latest_itr = new Zend_Form_Element_Checkbox('latest_itr');
	$latest_itr->removeDecorator('label')
				->setAttrib('disabled','true')
				->removeDecorator('HtmlTag');
	$this->addElement($latest_itr);
	
	$assets_liabilities = new Zend_Form_Element_Checkbox('assets_liabilities');
	$assets_liabilities->removeDecorator('label')
			->setAttrib('disabled','true')
			  ->removeDecorator('HtmlTag');
	$this->addElement($assets_liabilities);
	

	
	$tax_cert = new Zend_Form_Element_Checkbox('tax_cert');
	$tax_cert->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($tax_cert);
	
	$valid_id = new Zend_Form_Element_Checkbox('valid_id');
	$valid_id->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($valid_id);
	
	$cert_employment = new Zend_Form_Element_Checkbox('cert_employment');
	$cert_employment->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($cert_employment);	
	
	
	$authority_credit = new Zend_Form_Element_Checkbox('authority_credit');
	$authority_credit->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($authority_credit);	
	
	$postdate_check = new Zend_Form_Element_Checkbox('postdate_check');
	$postdate_check->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($postdate_check);	
	
	$authority_debt = new Zend_Form_Element_Checkbox('authority_debt');
	$authority_debt->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($authority_debt);	
	
	$signature_card = new Zend_Form_Element_Checkbox('signature_card');
	$signature_card->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($signature_card);
		
	$sec_registration = new Zend_Form_Element_Checkbox('sec_registration');
	$sec_registration->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($sec_registration);
	
	$dti_registration = new Zend_Form_Element_Checkbox('dti_registration');
	$dti_registration->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($dti_registration);
	
	$board_resolution = new Zend_Form_Element_Checkbox('board_resolution');
	$board_resolution->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($board_resolution);
	
	$secretary_cert = new Zend_Form_Element_Checkbox('secretary_cert');
	$secretary_cert->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($secretary_cert);
	
	$partnership_resolution = new Zend_Form_Element_Checkbox('partnership_resolution');
	$partnership_resolution->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($partnership_resolution);
	
	$financial_statement = new Zend_Form_Element_Checkbox('financial_statement');
	$financial_statement->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($financial_statement);
	
	$incorporation_amend = new Zend_Form_Element_Checkbox('incorporation_amend');
	$incorporation_amend->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($incorporation_amend);
	
	$by_laws = new Zend_Form_Element_Checkbox('by_laws');
	$by_laws->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($by_laws);
	
	$pn_chm = new Zend_Form_Element_Checkbox('pn_chm');
	$pn_chm->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($pn_chm);
	
	$deed_assignment = new Zend_Form_Element_Checkbox('deed_assignment');
	$deed_assignment->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($deed_assignment);
	
	$sales_incvoice = new Zend_Form_Element_Checkbox('sales_incvoice');
	$sales_incvoice->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($sales_incvoice);
	
	$delivery_receipt = new Zend_Form_Element_Checkbox('delivery_receipt');
	$delivery_receipt->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($delivery_receipt);
	
	$receipt_downpayment = new Zend_Form_Element_Checkbox('receipt_downpayment');
	$receipt_downpayment->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($receipt_downpayment);
	
	$receipt_fullpayment = new Zend_Form_Element_Checkbox('receipt_fullpayment');
	$receipt_fullpayment->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($receipt_fullpayment);
	
	$stencils_onionskin = new Zend_Form_Element_Checkbox('stencils_onionskin');
	$stencils_onionskin->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($stencils_onionskin);
	
	$dealer_undertaking = new Zend_Form_Element_Checkbox('dealer_undertaking');
	$dealer_undertaking->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($dealer_undertaking);
	
	$lto_cr = new Zend_Form_Element_Checkbox('lto_cr');
	$lto_cr->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($lto_cr);
	
	$lto_or = new Zend_Form_Element_Checkbox('lto_or');
	$lto_or->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($lto_or);
	
	$disclosure_statement = new Zend_Form_Element_Checkbox('disclosure_statement');
	$disclosure_statement->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($disclosure_statement);
	
	$insurance_policy = new Zend_Form_Element_Checkbox('insurance_policy');
	$insurance_policy->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($insurance_policy);
	
	$or_insurance = new Zend_Form_Element_Checkbox('or_insurance');
	$or_insurance->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($or_insurance);
	
	$appraisal_report = new Zend_Form_Element_Checkbox('appraisal_report');
	$appraisal_report->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($appraisal_report);
	
	$deed_absolutesale = new Zend_Form_Element_Checkbox('deed_absolutesale');
	$deed_absolutesale->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($deed_absolutesale);
	
	$car_historyreport = new Zend_Form_Element_Checkbox('car_historyreport');
	$car_historyreport->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($car_historyreport);
	
	$vehicle_clearance = new Zend_Form_Element_Checkbox('vehicle_clearance');
	$vehicle_clearance->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($vehicle_clearance);
	
	$po = new Zend_Form_Element_Checkbox('po');
	$po->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($po);
	
	$authority_deliver = new Zend_Form_Element_Checkbox('authority_deliver');
	$authority_deliver->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($authority_deliver);
	
	$inspection_report = new Zend_Form_Element_Checkbox('inspection_report');
	$inspection_report->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($inspection_report);
	
	$power_attorney = new Zend_Form_Element_Checkbox('power_attorney');
	$power_attorney->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($power_attorney);
	
	$affidavit_denial = new Zend_Form_Element_Checkbox('affidavit_denial');
	$affidavit_denial->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($affidavit_denial);
	
	$affidavit_same = new Zend_Form_Element_Checkbox('affidavit_same');
	$affidavit_same->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($affidavit_same);
	
	$affidavit_nontax = new Zend_Form_Element_Checkbox('affidavit_nontax');
	$affidavit_nontax->removeDecorator('label')
			->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($affidavit_nontax);
	
	//Appraisal Tab
	$fmv = new Zend_Form_Element_Text('fmv');
	$fmv->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				->setAttrib('onKeypress','return numOnly(event)')
				 ->setAttrib('size','15');
	$this->addElement($fmv);
	
	$appraisal_value = new Zend_Form_Element_Text('appraisal_value');
	$appraisal_value->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('Onblur','copyunitappraisal();calcActDownpayment();')
				->setAttrib('onKeypress','return numOnlyPeriod(event)')
				 ->setAttrib('size','15');
	$this->addElement($appraisal_value);
	
	$car_history = new Zend_Form_Element_Select('car_history');
	$car_history->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->addMultiOption('0','Ok')
			->addMultiOption('1','Not Ok');
	$this->addElement($car_history);

	//CV
	$cv_bap = new Zend_Form_Element_Select('cv_bap');
	$cv_bap->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		->addMultiOption('Positive' ,'Positive')
		->addMultiOption('Negative' ,'Negative')	
			->addMultiOption('N/A' ,'N/A')	

		//->setValue('0')
		 ->setAttrib('style','width: 120px');
	$this->addElement($cv_bap);	
	
	$cv_bap2 = new Zend_Form_Element_Select('cv_bap2');
	$cv_bap2->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		->addMultiOption('Verified' ,'Verified')
		->addMultiOption('Unverified' ,'Unverified')	
			->addMultiOption('N/A' ,'N/A')	
		//->setValue('0')
		 ->setAttrib('style','width: 120px');
	$this->addElement($cv_bap2);	
	
	$cv_nfis = new Zend_Form_Element_Select('cv_nfis');
	$cv_nfis->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		->addMultiOption('Positive' ,'Positive')
		->addMultiOption('Negative' ,'Negative')	
		->addMultiOption('Beyond 5 Years' ,'Beyond 5 Years')	
		->addMultiOption('Beyond 10 Years' ,'Beyond 10 Years')
		->addMultiOption('Positive Clear' ,'Positive Clear')
		->addMultiOption('N/A' ,'N/A')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($cv_nfis);	
	
	$cv_nfis2 = new Zend_Form_Element_Select('cv_nfis2');
	$cv_nfis2->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('id','cv_nfis2')
		 ->addMultiOption('' ,'')
		->addMultiOption('Verified' ,'Verified')
		->addMultiOption('Unverified' ,'Unverified')		
		->addMultiOption('N/A' ,'N/A')			
		 ->setAttrib('style','width: 120px');
	$this->addElement($cv_nfis2);	
	
	$cv_cmap = new Zend_Form_Element_Select('cv_cmap');
	$cv_cmap->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		->addMultiOption('Positive' ,'Positive')
		->addMultiOption('Positive - Dismissed' ,'Positive - Dismissed')
		->addMultiOption('Positive - Settled' ,'Positive - Settled')
		->addMultiOption('Negative' ,'Negative')	
		->addMultiOption('N/A' ,'N/A')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($cv_cmap);	
	
	$cv_cmap2 = new Zend_Form_Element_Select('cv_cmap2');
	$cv_cmap2->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('id','cv_cmap2')
		 ->addMultiOption('' ,'')
		->addMultiOption('Verified' ,'Verified')
		->addMultiOption('Unverified' ,'Unverified')	
		->addMultiOption('N/A' ,'N/A')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($cv_cmap2);
	
	$cv_srcincomever = new Zend_Form_Element_Select('cv_srcincomever');
	$cv_srcincomever->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		->addMultiOption('Verified' ,'Verified')
		->addMultiOption('Unverified' ,'Unverified')	
		->addMultiOption('N/A' ,'N/A')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($cv_srcincomever);	
	
	$cv_empver = new Zend_Form_Element_Select('cv_empver');
	$cv_empver->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('id','cv_empver')
		 ->addMultiOption('' ,'')
		->addMultiOption('Verified' ,'Verified')
		->addMultiOption('Unverified' ,'Unverified')	
		->addMultiOption('N/A' ,'N/A')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($cv_empver);	
	
	$cv_busver = new Zend_Form_Element_Select('cv_busver');
	$cv_busver->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('id','cv_busver')
		 ->addMultiOption('' ,'')
		->addMultiOption('Verified' ,'Verified')
		->addMultiOption('Unverified' ,'Unverified')	
		->addMultiOption('N/A' ,'N/A')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($cv_busver);
	
	$cv_trdchk = new Zend_Form_Element_Select('cv_trdchk');
	$cv_trdchk->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('id','cv_trdchk') 
		 ->addMultiOption('' ,'')
		->addMultiOption('Verified' ,'Verified')
		->addMultiOption('Unverified' ,'Unverified')	
		->addMultiOption('N/A' ,'N/A')	
		->setAttrib('style','width: 120px');
	$this->addElement($cv_trdchk);
	

	$cv_backgrd= new Zend_Form_Element_Select('cv_backgrd');
	$cv_backgrd->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('id','cv_backgrd')
		 ->addMultiOption('' ,'')
		->addMultiOption('Favorable' ,'Favorable')
		->addMultiOption('Unfavorable' ,'Unfavorable')	
		->addMultiOption('None' ,'None')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($cv_backgrd);
	
	$cv_bankref = new Zend_Form_Element_Select('cv_bankref');
	$cv_bankref->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('id','cv_bankref')
		 ->addMultiOption('' ,'')
		->addMultiOption('Favorable' ,'Favorable')
		->addMultiOption('Unfavorable' ,'Unfavorable')	
		->addMultiOption('None' ,'None')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($cv_bankref);
	
	$cv_creditchk = new Zend_Form_Element_Select('cv_creditchk');
	$cv_creditchk->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('id','cv_creditchk')
		 ->addMultiOption('' ,'')
		->addMultiOption('Favorable' ,'Favorable')
		->addMultiOption('Unfavorable' ,'Unfavorable')	
		->addMultiOption('None' ,'None')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($cv_creditchk);
	
	$cv_pastdealings = new Zend_Form_Element_Select('cv_pastdealings');
	$cv_pastdealings->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('id','cv_pastdealings')
		 ->addMultiOption('' ,'')
		->addMultiOption('Favorable' ,'Favorable')
		->addMultiOption('Unfavorable' ,'Unfavorable')	
		->addMultiOption('None' ,'None')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($cv_pastdealings);
	
	$ci_srcincomever = new Zend_Form_Element_Select('ci_srcincomever');
	$ci_srcincomever->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		->addMultiOption('Verified' ,'Verified')
		->addMultiOption('Unverified' ,'Unverified')	
		->addMultiOption('N/A' ,'N/A')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($ci_srcincomever);	
	
	$ci_empver = new Zend_Form_Element_Select('ci_empver');
	$ci_empver->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		->addMultiOption('Verified' ,'Verified')
		->addMultiOption('Unverified' ,'Unverified')	
		->addMultiOption('N/A' ,'N/A')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($ci_empver);	
	
	$ci_busver = new Zend_Form_Element_Select('ci_busver');
	$ci_busver->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		->addMultiOption('Verified' ,'Verified')
		->addMultiOption('Unverified' ,'Unverified')	
		->addMultiOption('N/A' ,'N/A')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($ci_busver);
	
	$ci_trdchk = new Zend_Form_Element_Select('ci_trdchk');
	$ci_trdchk->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		->addMultiOption('Verified' ,'Verified')
		->addMultiOption('Unverified' ,'Unverified')	
		->addMultiOption('N/A' ,'N/A')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($ci_trdchk);
	
	
	$ci_backgrd= new Zend_Form_Element_Select('ci_backgrd');
	$ci_backgrd->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		->addMultiOption('Verified' ,'Verified')
		->addMultiOption('Unverified' ,'Unverified')	
		->addMultiOption('None' ,'None')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($ci_backgrd);
	
	$age = new Zend_Form_Element_Text('age');
	$age->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnly(event)')
				 ->setAttrib('size','1');
	$this->addElement($age);
	
	$residencetype_prev = new Zend_Form_Element_Select('residencetype_prev');
	$residencetype_prev->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 100px')
		 ->addMultiOption(0 ,'')
	     ->setValue('0');
		 $table = new Model_CategoryValues();
		 $sql = $table->select()
	    ->where('name LIKE ?', 'ResidenceType')->order("seq ASC");
		 foreach ($table->fetchAll($sql,"seq ASC") as $c) {
         $residencetype_prev->addMultiOption($c->seq, $c->values);} 
	$this->addElement($residencetype_prev);
	
	$lenghtstay_prev = new Zend_Form_Element_Text('lenghtstay_prev');
	$lenghtstay_prev->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('id', 'lenghtstay_prev')
				  ->setAttrib('onKeypress','return numOnlyPeriod(event)')
				->setAttrib('onmouseover','Tip("Year")')
				 ->setAttrib('onmouseout','UnTip()')
				 //->setAttrib('size','1');
				  ->setAttrib('style','width: 20px');
	$this->addElement($lenghtstay_prev);
	
	$lenght_months_prev = new Zend_Form_Element_Select('lenght_months_prev');
	$lenght_months_prev->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('onmouseover','Tip("Months")')
		->setAttrib('onmouseout','UnTip()')
		 ->addMultiOption(0 ,'')
		 ->setAttrib('style','width: 50px')

		 ->setValue('0');
	for ($counter = 1; $counter <= 11; $counter += 1) {
        $lenght_months_prev->addMultiOption($counter, $counter);} 
	$this->addElement($lenght_months_prev);
	
	$cv_srcincomever2 = new Zend_Form_Element_Select('cv_srcincomever2');
	$cv_srcincomever2->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		->addMultiOption('Favorable' ,'Favorable')
		->addMultiOption('Unfavorable' ,'Unfavorable')	
		->addMultiOption('N/A' ,'N/A')	
		->setAttrib('style','width: 120px');
	$this->addElement($cv_srcincomever2);	
	
	$cv_empver2 = new Zend_Form_Element_Select('cv_empver2');
	$cv_empver2->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('id','cv_empver2')
		 ->addMultiOption('' ,'')
		->addMultiOption('Favorable' ,'Favorable')
		->addMultiOption('Unfavorable' ,'Unfavorable')	
		->addMultiOption('N/A' ,'N/A')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($cv_empver2);	
	
	$cv_busver2 = new Zend_Form_Element_Select('cv_busver2');
	$cv_busver2->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('id','cv_busver2')
		 ->addMultiOption('' ,'')
		->addMultiOption('Favorable' ,'Favorable')
		->addMultiOption('Unfavorable' ,'Unfavorable')	
		->addMultiOption('N/A' ,'N/A')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($cv_busver2);
	
	$cv_trdchk2 = new Zend_Form_Element_Select('cv_trdchk2');
	$cv_trdchk2->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('id','cv_trdchk2')
		 ->addMultiOption('' ,'')
		->addMultiOption('Favorable' ,'Favorable')
		->addMultiOption('Unfavorable' ,'Unfavorable')	
		->addMultiOption('N/A' ,'N/A')	
		->setAttrib('style','width: 120px');
	$this->addElement($cv_trdchk2);
	
	$ci_srcincomever2 = new Zend_Form_Element_Select('ci_srcincomever2');
	$ci_srcincomever2->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		->addMultiOption('Favorable' ,'Favorable')
		->addMultiOption('Unfavorable' ,'Unfavorable')	
		->addMultiOption('N/A' ,'N/A')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($ci_srcincomever2);	
	
	$ci_empver2 = new Zend_Form_Element_Select('ci_empver2');
	$ci_empver2->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		->addMultiOption('Favorable' ,'Favorable')
		->addMultiOption('Unfavorable' ,'Unfavorable')	
		->addMultiOption('N/A' ,'N/A')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($ci_empver2);	
	
	$ci_busver2 = new Zend_Form_Element_Select('ci_busver2');
	$ci_busver2->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		->addMultiOption('Favorable' ,'Favorable')
		->addMultiOption('Unfavorable' ,'Unfavorable')	
		->addMultiOption('N/A' ,'N/A')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($ci_busver2);
	
	$ci_trdchk2 = new Zend_Form_Element_Select('ci_trdchk2');
	$ci_trdchk2->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		->addMultiOption('Favorable' ,'Favorable')
		->addMultiOption('Unfavorable' ,'Unfavorable')	
		->addMultiOption('N/A' ,'N/A')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($ci_trdchk2);
	
	$ci_backgrd2= new Zend_Form_Element_Select('ci_backgrd2');
	$ci_backgrd2->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		->addMultiOption('Favorable' ,'Favorable')
		->addMultiOption('Unfavorable' ,'Unfavorable')	
		->addMultiOption('None' ,'None')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($ci_backgrd2);
	
	$dealer_agent = new Zend_Form_Element_Text('dealer_agent');
	$dealer_agent->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('onKeypress','return alphaOnly(event)')
				 ->setAttrib('size','24');
	$this->addElement($dealer_agent);
	
	$branch_refferror = new Zend_Form_Element_Text('branch_refferror');
	$branch_refferror->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('onKeypress','return alphaOnly(event)')
				 ->setAttrib('size','24');
				 $this->addElement($branch_refferror);
				 
	$effective_yield = new Zend_Form_Element_Text('effective_yield');
	$effective_yield->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
   				  ->setAttrib('onKeypress','return numOnlyPeriod(event)')
				 ->setAttrib('size','5');
	$this->addElement($effective_yield);
	
	$dealer_incentive = new Zend_Form_Element_Text('dealer_incentive');
	$dealer_incentive->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('onblur','calcfields()')
   				  ->setAttrib('onKeypress','return numOnlyPeriod(event)')
				 ->setAttrib('size','5');
	$this->addElement($dealer_incentive); 	
	
	$dealer_incentive2 = new Zend_Form_Element_Text('dealer_incentive2');
	$dealer_incentive2->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
   				->setAttrib('onKeypress','return numOnlyPeriod(event)')
				
				->setAttrib('size','8');
	$this->addElement($dealer_incentive2); 	
	
	$loan_purpose = new Zend_Form_Element_Select('loan_purpose');
	$loan_purpose->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 100px');
		 $table = new Model_ListSelectValues();
		 $sql = $table->select()
	     ->where('type LIKE ?', 'LoanPurpose')->order("seq ASC");
		 foreach ($table->fetchAll($sql,"seq ASC") as $c) {
         $loan_purpose->addMultiOption($c->value, $c->value);} 
	$this->addElement($loan_purpose);
	
	$branch = new Zend_Dojo_Form_Element_ComboBox('branch');
	$branch->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption(0,'')
		 ->setAttrib('style','width: 150');
		 $table = new Model_ListSelectValues();
		 $sql = $table->select()
	     ->where('type LIKE ?', 'Branch')->order("seq ASC");
		 foreach ($table->fetchAll($sql,"seq ASC") as $c) {
         $branch->addMultiOption($c->value, $c->value);} 
	$this->addElement($branch);
	
		$marketingofficer = new Zend_Form_Element_Select('marketingofficer');
	$marketingofficer->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption(0 ,'')
		 ->setAttrib('style','width: 120px')
	     ->setValue('0');
		 $table = new Model_Users();
		$sql = $table->select()
	    ->where("role_type = 'MO' OR role_type = 'DC' OR role_type = 'ALMH'")->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $marketingofficer->addMultiOption($c->username, $c->name);} 
	$this->addElement($marketingofficer);
	
	
	$remarks_residence_ci = new Zend_Form_Element_Text('remarks_residence_ci');
	$remarks_residence_ci->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','64')
				 ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($remarks_residence_ci);
	
	$ci_residence = new Zend_Form_Element_Select('ci_residence');
	$ci_residence->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		->addMultiOption('Verified' ,'Verified')
		->addMultiOption('Unverified' ,'Unverified')	
		->addMultiOption('N/A' ,'N/A')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($ci_residence);
	
	$ci_residence2 = new Zend_Form_Element_Select('ci_residence2');
	$ci_residence2->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		->addMultiOption('Favorable' ,'Favorable')
		->addMultiOption('Unfavorable' ,'Unfavorable')	
			->addMultiOption('N/A' ,'N/A')	
		//->setValue('0')
		 ->setAttrib('style','width: 120px');
	$this->addElement($ci_residence2);	
	
	$remarks_residence_ci = new Zend_Form_Element_Text('remarks_residence_ci');
	$remarks_residence_ci->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','64');
	$this->addElement($remarks_residence_ci);
	

	
	$cv_income = new Zend_Form_Element_Select('cv_income');
	$cv_income->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->addFilter('StringTrim')
		->setAttrib('id','cv_income')
		->addMultiOption('' ,'')
		->addMultiOption('Favorable' ,'Favorable')
		->addMultiOption('Unfavorable' ,'Unfavorable')	
		->addMultiOption('N/A' ,'N/A')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($cv_income);	

	$cv_income2 = new Zend_Form_Element_Select('cv_income2');
	$cv_income2->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->addFilter('StringTrim')
		->setAttrib('id','cv_income2')
		->addMultiOption('' ,'')
		->addMultiOption('Verified' ,'Verified')
		->addMultiOption('Claimed & Estimated' ,'Claimed & Estimated')	
		->addMultiOption('Claimed' ,'Claimed')
		->addMultiOption('Estimated' ,'Estimated')
		->addMultiOption('Per Submitted Doc' ,'Per Submitted Doc')
		->addMultiOption('N/A' ,'N/A')	
		->setAttrib('style','width: 120px');
	$this->addElement($cv_income2);
	
	$remarks_income = new Zend_Form_Element_Text('remarks_income');
	$remarks_income->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','64');
	$this->addElement($remarks_income);
	
	$ci_income = new Zend_Form_Element_Select('ci_income');
	$ci_income->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->addFilter('StringTrim')
		->addMultiOption('' ,'')
		->addMultiOption('Favorable' ,'Favorable')
		->addMultiOption('Unfavorable' ,'Unfavorable')	
		->addMultiOption('N/A' ,'N/A')	
		 ->setAttrib('style','width: 120px');
	$this->addElement($ci_income);	

	$ci_income2 = new Zend_Form_Element_Select('ci_income2');
	$ci_income2->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->addFilter('StringTrim')
		->addMultiOption('' ,'')
		->addMultiOption('Verified' ,'Verified')
		->addMultiOption('Claimed & Estimated' ,'Claimed & Estimated')	
		->addMultiOption('Claimed' ,'Claimed')
		->addMultiOption('Estimated' ,'Estimated')
		->addMultiOption('Per Submitted Doc' ,'Per Submitted Doc')
		->setAttrib('style','width: 120px');
	$this->addElement($ci_income2);
	
	$remarks_income_ci = new Zend_Form_Element_Text('remarks_income_ci');
	$remarks_income_ci->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','64');
	$this->addElement($remarks_income_ci);
	
	$date_nfis = new Zend_Form_element_Text('date_nfis');
	$date_nfis->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
		     ->setAttrib('onmouseover','Tip("MM/DD/YYYY")')
		     ->setAttrib('onmouseout','UnTip()')
		     ->setAttrib('Maxlength',10)
		     ->setAttrib('onKeypress','return numOnlySlash(event)')
		     ->setAttrib('size','6');
	$this->addElement($date_nfis);
	

	
	$date_ci = new Zend_Form_element_Text('date_ci');
	$date_ci->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
		     ->setAttrib('onmouseover','Tip("MM/DD/YYYY")')
		     ->setAttrib('onmouseout','UnTip()')
		     ->setAttrib('Maxlength',10)
		     ->setAttrib('onKeypress','return numOnlySlash(event)')
		     ->setAttrib('size','6');
	$this->addElement($date_ci);
	
	$dosri = new Zend_Form_Element_Select('dosri');
	$dosri->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->addFilter('StringTrim')
		->addMultiOption('DOSRI / NON-DOSRI' ,'DOSRI / NON-DOSRI')
		->addMultiOption('DOSRI' ,'DOSRI')
		->addMultiOption('NON-DOSRI' ,'NON-DOSRI')	
		->setAttrib('style','width: 155px');
	$this->addElement($dosri);

	$phone_ver = new Zend_Form_Element_Select('phone_ver');
	$phone_ver->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->addFilter('StringTrim')
		->addMultiOption('' ,'')
		->addMultiOption('NL' ,'NL')
		->addMultiOption('CTD' ,'CTD')	
		->setAttrib('style','width: 50px');
	$this->addElement($phone_ver);
	
	$waverRequest = new Zend_Form_Element_Checkbox('waverRequest');
	$waverRequest->removeDecorator('label')
			//->setAttrib('disabled','true')
			->removeDecorator('HtmlTag');
	$this->addElement($waverRequest);

	
	
	$coborrower_select = new Zend_Form_Element_Select('coborrower_select');
	$coborrower_select->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		 ->setAttrib('style','width: 150px')
		 ->setAttrib('onchange','chgCoborrower(this.value)')
	     ->setValue('0');
         $coborrower_select->addMultiOption('main', 'Coborrower Main');
         $coborrower_select->addMultiOption('extend', 'Coborrower Extend');
	 $this->addElement($coborrower_select);
	 
	$coborrower_relation = new Zend_Form_Element_Select('coborrower_relation');
	$coborrower_relation->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		 ->setAttrib('style','width: 120px')
	     ->setValue('0');
         $coborrower_relation->addMultiOption('Parent', 'Parent');
         $coborrower_relation->addMultiOption('Child', 'Child');
         $coborrower_relation->addMultiOption('Aunt', 'Aunt');
         $coborrower_relation->addMultiOption('Cousin', 'Cousin');
	 //$coborrower_relation->addMultiOption('Common Law', 'Common Law');
	  $coborrower_relation->addMultiOption('Sibling', 'Sibling');
	 $coborrower_relation->addMultiOption('Uncle', 'Uncle');
	 $this->addElement($coborrower_relation);

	$coborrower_extend = new Zend_Form_Element_Select('coborrower_extend');
	$coborrower_extend->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		 ->setAttrib('style','width: 100px')
	     ->setValue('0');
         $coborrower_extend->addMultiOption('Spouse', 'Spouse');
         //$coborrower_extend->addMultiOption('Others', 'Others');
	 $this->addElement($coborrower_extend);
	 
	$coborrower_to = new Zend_Form_Element_Select('coborrower_to');
	$coborrower_to->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		 ->setAttrib('style','width: 120px');
	 $this->addElement($coborrower_to);
	 
	$appraisal_date = new Zend_Form_element_Text('appraisal_date');
	$appraisal_date->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
		     ->setAttrib('onmouseover','Tip("MM/DD/YYYY")')
		     ->setAttrib('onmouseout','UnTip()')
		     ->setAttrib('Maxlength',10)
		     ->setAttrib('onKeypress','return numOnlySlash(event)')
		     ->setAttrib('size','6');
	$this->addElement($appraisal_date);

	$appraised_by = new Zend_Form_Element_Text('appraised_by');
	$appraised_by->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 120px');
	 $this->addElement($appraised_by);
	 
	$remarks_appraisal= new Zend_Form_Element_Text('remarks_appraisal');
	$remarks_appraisal->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','50');
	$this->addElement($remarks_appraisal);
	
	$appraisal_waver_request = new Zend_Form_Element_Select('appraisal_waver_request');
	$appraisal_waver_request->removeDecorator('label')
			//->setAttrib('disabled','true')

			->addMultiOption('No', 'No')
			->addMultiOption('Yes', 'Yes');
	$this->addElement($appraisal_waver_request);
	
	$ci_appraisal_report = new Zend_Form_Element_Select('ci_appraisal_report');
	$ci_appraisal_report->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		->removeDecorator('HtmlTag')
		->setAttrib('id','ci_appraisal_report')	 
		 ->addMultiOption('' ,'')
		 ->addMultiOption('N/A' ,'N/A')	
		->addMultiOption('Favorable' ,'Favorable')
		->addMultiOption('Unfavorable' ,'Unfavorable')	

		 ->setAttrib('style','width: 120px');
	$this->addElement($ci_appraisal_report);
	
	$comaker_accnt_status = new Zend_Form_Element_Hidden('comaker_accnt_status');
	$comaker_accnt_status->removeDecorator('label')
		     ->removeDecorator('HtmlTag');
	$this->addElement($comaker_accnt_status);

	$dealer_pricelist = new Zend_Form_Element_Select('dealer_pricelist');
	$dealer_pricelist->removeDecorator('label')
			 ->setAttrib('id', 'dealer_pricelist')
			 ->setAttrib('onchange','chainselectBrand()')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 150px')			
			->addMultiOption('','');
			//->addMultiOption('OLD','OLD');
		/*$table = new Model_ListDealer();
		$sql = $table->select()->order("name ASC");
		 foreach ($table->fetchAll($sql,"seq ASC") as $c) {
         $dealer_pricelist->addMultiOption($c->name, $c->name);} */
	
	$table = new Model_ChainVehicle();	
	$select = $table->select();
	$currmonth = date('n');
	$prevmonth = date('n') - 1;
	$select->where("month like '$currmonth' OR month like '$prevmonth'")
	->order('dealer ASC')->distinct('dealer');		
	$select->where('status like ?','approved');
	foreach ($table->fetchAll($select,"seq ASC") as $c) {
    $dealer_pricelist->addMultiOption($c->dealer, $c->dealer);}		
	$this->addElement($dealer_pricelist);

	$dealer_pricelist2 = new Zend_Form_Element_Select('dealer_pricelist2');
	$dealer_pricelist2->removeDecorator('label')
   			 ->setAttrib('id', 'dealer_pricelist2')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 150px')			
			->addMultiOption('','');
		$table = new Model_ListDealer();
		$sql = $table->select()->order("name ASC");
		 foreach ($table->fetchAll($sql,"seq ASC") as $c) {
         $dealer_pricelist2->addMultiOption($c->name, $c->name);} 
	$this->addElement($dealer_pricelist2);

	$listmonth_pricelist = new Zend_Form_Element_Select('listmonth_pricelist');
	$listmonth_pricelist->removeDecorator('label')
		 ->setAttrib('id', 'listmonth_pricelist')
		 ->setAttrib('onchange','chainselectBrand()')
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
	$this->addElement($listmonth_pricelist);
	
	$listyear_pricelist = new Zend_Form_Element_Select('listyear_pricelist');
	$listyear_pricelist->removeDecorator('label')
		->setAttrib('id', 'listyear_pricelist')
		->setAttrib('onchange','chainselectBrand()')
		 ->removeDecorator('HtmlTag')
		 ->setRegisterInArrayValidator(false)
		 ->addFilter('StringTrim')
		 ->addMultiOption('','')	
 		->addMultiOption('2011','2011')		 
		->addMultiOption('2010','2010');
	$this->addElement($listyear_pricelist);
	
	$veh_unit_pricelist = new Zend_Form_Element_Select('veh_unit_pricelist');
	$veh_unit_pricelist->removeDecorator('label')
			->setAttrib('onchange','chainselectType();chainselectPrice();')
			->setAttrib('id','veh_unit_pricelist')
			->setRegisterInArrayValidator(false)
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 150px')
			->setAttrib('id','veh_unit_pricelist')
			->addMultiOption(0,'');
	$this->addElement($veh_unit_pricelist);
	
	$veh_type_pricelist = new Zend_Form_Element_Select('veh_type_pricelist');
	$veh_type_pricelist->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 120px')
		 ->setRegisterInArrayValidator(false)
		 ->addMultiOption(0 ,'')
		 ->setAttrib('id','veh_type_pricelist')
	     ->setValue('0');
	$this->addElement($veh_type_pricelist);
	
	$selling_price_pricelist = new Zend_Form_Element_Select('selling_price_pricelist');
	$selling_price_pricelist->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		->setRegisterInArrayValidator(false)	 
		 ->setAttrib('id','selling_price_pricelist')
		 ->addMultiOption(0, '');
		 $this->addElement($selling_price_pricelist);
		 
	$veh_brand_pricelist = new Zend_Form_Element_Select('veh_brand_pricelist');
	$veh_brand_pricelist->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->setAttrib('onchange','chainselectUnit()')
		 ->addFilter('StringTrim')
		 ->setAttrib('id','veh_brand_pricelist')
		 ->setRegisterInArrayValidator(false)
		 ->setAttrib('style','width: 150px')
	 	//->setAttrib('onChange','displaySubs0(this.options[this.selectedIndex].text)')
		 ->addMultiOption('0','');
		 /*
		$table = new Model_ChainVehicleBrand();
		$sql = $table->select()->order("brand ASC")->distinct();
		foreach ($table->fetchAll($sql,"brand ASC") as $c) {
	$veh_brand->addMultiOption($c->brand, $c->brand);} */
	$this->addElement($veh_brand_pricelist);
	
	$comaker_relation = new Zend_Form_Element_Select('comaker_relation');
	$comaker_relation->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				->addMultiOption('Parents','Parents')
				->addMultiOption('First Consanguinity','First Consanguinity')
				->addMultiOption('Second Consanguinity','Second Consanguinity')
				->addMultiOption('Friends','Friends')
				->addMultiOption('Others','Others');
	$this->addElement($comaker_relation);
	
	
	}
	
}




function BaseUrl(){
		return Zend_Controller_Front::getInstance()->getBaseUrl();
	}

?>
