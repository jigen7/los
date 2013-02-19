<?
class Form_AdminPromo extends Zend_Dojo_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
	
	
	$addpromo = new Zend_Form_Element_Text('addpromo');
	$addpromo->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($addpromo);
	
	$modifypromo = new Zend_Form_Element_Text('modifypromo');
	$modifypromo->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($modifypromo);
	
	
	
	$AP_auto_individ = new Zend_Form_Element_Checkbox('AP_auto_individ');
	$AP_auto_individ->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($AP_auto_individ);
	
	$AP_auto_partner = new Zend_Form_Element_Checkbox('AP_auto_partner');
	$AP_auto_partner->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($AP_auto_partner);
	
	$AP_housing_individ = new Zend_Form_Element_Checkbox('AP_housing_individ');
	$AP_housing_individ->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($AP_housing_individ);
	
	$AP_housing_partner = new Zend_Form_Element_Checkbox('AP_housing_partner');
	$AP_housing_partner->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($AP_housing_partner);
	
	$borowers_profile = new Zend_Form_Element_Checkbox('borowers_profile');
	$borowers_profile->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($borowers_profile);
	
	$bo_employment = new Zend_Form_Element_Checkbox('bo_employment');
	$bo_employment->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($bo_employment);
	
	$bo_obligations = new Zend_Form_Element_Checkbox('bo_obligations');
	$bo_obligations->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($bo_obligations);
	
	$bo_loan = new Zend_Form_Element_Checkbox('bo_loan');
	$bo_loan->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($bo_loan);
	
	$bo_unit = new Zend_Form_Element_Checkbox('bo_unit');
	$bo_unit->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($bo_unit);
	
	$bo_cv = new Zend_Form_Element_Checkbox('bo_cv');
	$bo_cv->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($bo_cv);
	
	$bo_ci = new Zend_Form_Element_Checkbox('bo_ci');
	$bo_ci->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($bo_ci);
	
	$bo_craw = new Zend_Form_Element_Checkbox('bo_craw');
	$bo_craw->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($bo_craw);
	
	$bo_appraisal = new Zend_Form_Element_Checkbox('bo_appraisal');
	$bo_appraisal->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($bo_appraisal);
	
	$bo_documents = new Zend_Form_Element_Checkbox('bo_documents');
	$bo_documents->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($bo_documents);
	
	$spouse_profile = new Zend_Form_Element_Checkbox('spouse_profile');
	$spouse_profile->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($spouse_profile);
	
	$spouse_employment = new Zend_Form_Element_Checkbox('spouse_employment');
	$spouse_employment->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($spouse_employment);
	
	$spouse_obligations = new Zend_Form_Element_Checkbox('spouse_obligations');
	$spouse_obligations->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($spouse_obligations);
	
	$spouse_cv = new Zend_Form_Element_Checkbox('spouse_cv');
	$spouse_cv->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($spouse_cv);
	
	$spouse_ci = new Zend_Form_Element_Checkbox('spouse_ci');
	$spouse_ci->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($spouse_ci);
	
	$coborowers_profile = new Zend_Form_Element_Checkbox('coborowers_profile');
	$coborowers_profile->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($coborowers_profile);
	
	$cobo_employment = new Zend_Form_Element_Checkbox('cobo_employment');
	$cobo_employment->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($cobo_employment);
	
	$cobo_obligations = new Zend_Form_Element_Checkbox('cobo_obligations');
	$cobo_obligations->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($cobo_obligations);
	
	$cobo_cv = new Zend_Form_Element_Checkbox('cobo_cv');
	$cobo_cv->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($cobo_cv);
	
	$cobo_ci = new Zend_Form_Element_Checkbox('cobo_ci');
	$cobo_ci->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($cobo_ci);
	
	$cobo_ci = new Zend_Form_Element_Checkbox('cobo_ci');
	$cobo_ci->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($cobo_ci);
	
	}
}


?>
