<?
class Form_AddAccount extends Zend_Dojo_Form
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
				 ->setAttrib('size','30')
				 ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($borrower_fname);
	
	$borrower_lname = new Zend_Form_Element_Text('borrower_lname');
	$borrower_lname->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','30')
				 ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($borrower_lname);
	
	
	$birthdate = new Zend_Form_element_Text('birthdate');
	$birthdate->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
		     ->setAttrib('value','MM/DD/YYYY')
		     ->setValue('MM/DD/YYYY')
			 ->setAttrib('Maxlength',10)
			 ->setAttrib('onKeypress','return numOnlySlash(event)')
		     ->setAttrib('onclick','this.value=""')
 			 ->setAttrib('onBlur','compage(this)')
			 ->setAttrib('onkeyup','datef(this.value)')
		     ->setAttrib('size','15');
	$this->addElement($birthdate);
	
	$borrower_mname = new Zend_Form_Element_Text('borrower_mname');
	$borrower_mname->removeDecorator('label')
				 ->removeDecorator('HtmlTag')
				  ->addFilter('StringTrim')
				  ->addFilter('StripTags')
				  ->addFilter('StringToUpper')
				  ->setAttrib('size','30')
				  ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($borrower_mname);

    
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
	
		
	$borrower_pres_address_brgy = new Zend_Form_Element_Select('borrower_pres_address_brgy');
	$borrower_pres_address_brgy->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		//->setAttrib('onchange','javascript:alert(document.forms[0].borrower_pres_address_city.options[document.forms[0].borrower_pres_address_city.selectedIndex].value)')
		//->setAttrib('onchange','javascript:alert("ye")')
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
		 ->setAttrib('id', 'categorySelect');
		  //->setAttrib('readonly','readonly');
		 
		$table = new Model_ChainAddressProvince();
		$sql = $table->select()->order("seq ASC");
 		//$sql = $table->select()->where('province like ?','Metro Manila')->order("id ASC");
		 foreach ($table->fetchAll($sql,"province ASC") as $c) {
		$borrower_pres_address_province->addMultiOption($c->province, $c->province);} 
		
	$this->addElement($borrower_pres_address_province);
	
	 $pres_zipcode = new Zend_Form_Element_Text('pres_zipcode');
	 $pres_zipcode->removeDecorator('label')
				//->setRegisterInArrayValidator(false)
				->removeDecorator('HtmlTag')
				->setAttrib('id', 'zipSelect')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','5')
				 ->setAttrib('readonly','readonly');
	$this->addElement($pres_zipcode);

	$typeloan = new Zend_Form_Element_Select('typeloan');
	$typeloan->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
	   	 ->addMultiOption('0','Please Select...')
		 ->addMultiOption('1','Auto Loan')
		 //->addMultiOption('2','Housing Loan')
		 ->setValue('0');
	$this->addElement($typeloan);

	$source_application = new Zend_Form_Element_Select('source_application');
	$source_application->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('0','')
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
				  ->setAttrib('onKeypress','return numOnlyHyphen(event)')
				 ->setAttrib('size','20');
	$this->addElement($landline);
	
	$mobile = new Zend_Form_Element_Text('mobile');
	$mobile->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnlyHyphen(event)')
				 ->setAttrib('size','20');
	$this->addElement($mobile);
	
	$email = new Zend_Form_Element_Text('email');
	$email->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','20');
	$this->addElement($email);
	
	$gender = new Zend_Form_Element_Select('gender');
	$gender->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption(0 ,'')
	     ->setValue('0');
		 $table = new Model_CategoryValues();
		 $sql = $table->select()
	    ->where('name LIKE ?', 'Gender');
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
	    ->where('name LIKE ?', 'CivilStatus');
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
		     ->setAttrib('value','MM/DD/YYYY')
			 ->setAttrib('onkeyup','datefs(this.value)')
		     ->setValue('MM/DD/YYYY')
 			 ->setAttrib('onBlur','comsppage(this)')
 			 ->setAttrib('Maxlength',10)
		     ->setAttrib('onclick','this.value=""')
			 //->addValidator('regex',false,'(0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])[- /.](19|20)[0-9]{2}')
		     ->setAttrib('onKeypress','return numOnlySlash(event)')
			 ->setAttrib('size','15');
	$this->addElement($birthdate_spouse);
	
	$new_account = new Zend_Form_Element_Select('new_account');
	$new_account->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setValue('0')
		 ->addMultiOption('0','Yes')
		 ->addMultiOption('1','No');	
	$this->addElement($new_account);
	
	$loan_purpose = new Zend_Form_Element_Select('loan_purpose');
	$loan_purpose->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 150')
		 ->addMultiOption(0 ,'Please Select...')
		->setValue('0');
		 $table = new Model_ListSelectValues();
		 $sql = $table->select()
	     ->where('type LIKE ?', 'LoanPurpose')->order("seq ASC");
		 foreach ($table->fetchAll($sql,"seq ASC") as $c) {
         $loan_purpose->addMultiOption($c->value, $c->value);} 
	$this->addElement($loan_purpose);
	
	$age = new Zend_Form_Element_Text('age');
	$age->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('style','display:none')
				  ->setAttrib('onKeypress','return numOnly(event)')
				 ->setAttrib('size','1');
	$this->addElement($age);
	
	$spage = new Zend_Form_Element_Text('spage');
	$spage->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('style','display:none')
				  ->setAttrib('onKeypress','return numOnly(event)')
				 ->setAttrib('size','1');
	$this->addElement($spage);
	}
}


?>
