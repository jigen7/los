<?
class Form_Booking extends Zend_Dojo_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
	
       $capno = new Zend_Form_Element_Text('capno');
	$capno->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('disabled','disabled')
				 ->setAttrib('size','25');
	$this->addElement($capno);
	
	$borrowers_name = new Zend_Form_Element_Text('borrowers_name');
	$borrowers_name->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('disabled','disabled')
				 ->setAttrib('size','60');
	$this->addElement($borrowers_name);
	
	$permanent_address = new Zend_Form_Element_Text('permanent_address');
	$permanent_address->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('disabled','disabled')
				 ->setAttrib('size','60');
	$this->addElement($permanent_address);
	
	$city_municipality = new Zend_Form_Element_Text('city_municipality');
	$city_municipality->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('disabled','disabled')
				 ->setAttrib('size','55');
	$this->addElement($city_municipality);
	
	$zip_code = new Zend_Form_Element_Text('zip_code');
	$zip_code->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('disabled','disabled')
				 ->setAttrib('size','25');
	$this->addElement($zip_code);
	
	$home_landline = new Zend_Form_Element_Text('home_landline');
	$home_landline->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('disabled','disabled')
				 ->setAttrib('size','25');
	$this->addElement($home_landline);
	
	$mobileno = new Zend_Form_Element_Text('mobileno');
	$mobileno->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('disabled','disabled')
				 ->setAttrib('size','25');
	$this->addElement($mobileno);
	
	$emailadd = new Zend_Form_Element_Text('emailadd');
	$emailadd->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('disabled','disabled')
				 ->setAttrib('size','25');
	$this->addElement($emailadd);
	
	$iden_num = new Zend_Form_Element_Text('iden_num');
	$iden_num->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($iden_num);
	
	$short_name = new Zend_Form_Element_Text('short_name');
	$short_name->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($short_name);
	
	$date_of_birth = new Zend_Form_Element_Text('date_of_birth');
	$date_of_birth->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('disabled','disabled')
				 ->setAttrib('size','25');
	$this->addElement($date_of_birth);
	
	$citizenship = new Zend_Form_Element_Text('citizenship');
	$citizenship->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('disabled','disabled')
				 ->setAttrib('size','25');
	$this->addElement($citizenship);
	
	$gender = new Zend_Form_Element_Text('gender');
	$gender->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('disabled','disabled')
				 ->setAttrib('size','25');
	$this->addElement($gender);
	
	$civil_status = new Zend_Form_Element_Text('civil_status');
	$civil_status->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('disabled','disabled')
				 ->setAttrib('size','25');
	$this->addElement($civil_status);
	
	$iden_type = new Zend_Form_Element_Text('iden_type');
	$iden_type->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($iden_type);
	
	$doc_id = new Zend_Form_Element_Text('doc_id');
	$doc_id->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($doc_id);
	
	$customer_since = new Zend_Form_Element_Text('customer_since');
	$customer_since->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($customer_since);
	
	$legal_formof_business = new Zend_Form_Element_Text('legal_formof_business');
	$legal_formof_business->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($legal_formof_business);
	
	$customer_cat = new Zend_Form_Element_Text('customer_cat');
	$customer_cat->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($customer_cat);
	
	$office_address = new Zend_Form_Element_Text('office_address');
	$office_address->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($office_address);
	
	$office_landline = new Zend_Form_Element_Text('office_landline');
	$office_landline->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($office_landline);
	
	
	$faxno = new Zend_Form_Element_Text('faxno');
	$faxno->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($faxno);
	
	$position = new Zend_Form_Element_Text('position');
	$position->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($position);
	
	$sic = new Zend_Form_Element_Text('sic');
	$sic->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($sic);
	
	$pn = new Zend_Form_Element_Text('pn');
	$pn->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($pn);
	
	$pn = new Zend_Form_Element_Text('pn');
	$pn->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($pn);
	
	$loantype = new Zend_Form_Element_Text('loantype');
	$loantype->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($loantype);
	
	$loanclass = new Zend_Form_Element_Text('loanclass');
	$loanclass->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($loanclass);
	
	$branch = new Zend_Form_Element_Text('branch');
	$branch->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($branch);
	
	$gltable = new Zend_Form_Element_Text('gltable');
	$gltable->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($gltable);
	
	$borrowertype = new Zend_Form_Element_Text('borrowertype');
	$borrowertype->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($borrowertype);
	
	$sizeoffirm = new Zend_Form_Element_Text('sizeoffirm');
	$sizeoffirm->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($sizeoffirm);
	
	$location_code = new Zend_Form_Element_Text('location_code');
	$location_code->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($location_code);
	
	$purpose_code = new Zend_Form_Element_Text('purpose_code');
	$purpose_code->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($purpose_code);
	
	$industry_type = new Zend_Form_Element_Text('industry_type');
	$industry_type->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($industry_type);
	
	$rediscount_date = new Zend_Form_Element_Text('rediscount_date');
	$rediscount_date->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($rediscount_date);
	
	$type_of_credit = new Zend_Form_Element_Text('type_of_credit');
	$type_of_credit->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($type_of_credit);
	
	$profit_cost = new Zend_Form_Element_Text('profit_cost');
	$profit_cost->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($profit_cost);
	
	$origin = new Zend_Form_Element_Text('origin');
	$origin->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($origin);
	
	$origin_maturity = new Zend_Form_Element_Text('origin_maturity');
	$origin_maturity->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($origin_maturity);
	
	
	
	
	
	
	
	
	
	
	
	
	
	}
}


?>
