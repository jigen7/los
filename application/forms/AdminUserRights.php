<?
class Form_AdminUserRights extends Zend_Dojo_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
	
	
	$username = new Zend_Form_Element_Text('username');
	$username->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($username);
	
	$role = new Zend_Form_Element_Text('role');
	$role->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($role);
	
	
        $report = new Zend_Form_Element_Checkbox('report');
	$report->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($report);
	
	$search = new Zend_Form_Element_Checkbox('search');
	$search->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($search);
	
	$add_auto_individ = new Zend_Form_Element_Checkbox('add_auto_individ');
	$add_auto_individ->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($add_auto_individ);
	
	$add_auto_partner = new Zend_Form_Element_Checkbox('add_auto_partner');
	$add_auto_partner->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($add_auto_partner);
	
	$add_housing_individ = new Zend_Form_Element_Checkbox('add_housing_individ');
	$add_housing_individ->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($add_housing_individ);
	
	$add_housing_partner = new Zend_Form_Element_Checkbox('add_housing_partner');
	$add_housing_partner->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($add_housing_partner);
	
	$pending = new Zend_Form_Element_Checkbox('pending');
	$pending->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($pending);
	
	$cs_pending = new Zend_Form_Element_Checkbox('cs_pending');
	$cs_pending->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($cs_pending);
	
	$asign_task = new Zend_Form_Element_Checkbox('asign_task');
	$asign_task->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($asign_task);
	
	$submitted = new Zend_Form_Element_Checkbox('submitted');
	$submitted->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($submitted);
	
	$recommended = new Zend_Form_Element_Checkbox('recommended');
	$recommended->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($recommended);
	
	$decided = new Zend_Form_Element_Checkbox('decided');
	$decided->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($decided);
	
	$with_decision = new Zend_Form_Element_Checkbox('with_decision');
	$with_decision->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($with_decision);
	
	$rts = new Zend_Form_Element_Checkbox('rts');
	$rts->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($rts);
	
	$credit_decision = new Zend_Form_Element_Checkbox('credit_decision');
	$credit_decision->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($credit_decision);
	
	$account_history = new Zend_Form_Element_Checkbox('account_history');
	$account_history->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($account_history);
	
	$audit_trail = new Zend_Form_Element_Checkbox('audit_trail');
	$audit_trail->removeDecorator('label')
			->removeDecorator('HtmlTag');
	$this->addElement($audit_trail);
	
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
