<?
class Form_CrawForm extends Zend_Dojo_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
	// 
	$cv_remarks= new Zend_Form_Element_Text('cv_remarks');
	$cv_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
			//	 ->addFilter('StringToUpper')
				 ->setAttrib('size','90');
	$this->addElement($cv_remarks);
	
	$loan_remarks= new Zend_Form_Element_Text('loan_remarks');
	$loan_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','80');
	$this->addElement($loan_remarks);
	
	$collateral_remarks= new Zend_Form_Element_Text('collateral_remarks');
	$collateral_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','80');
	$this->addElement($collateral_remarks);
	
	$dir_income_remarks= new Zend_Form_Element_Text('dir_income_remarks');
	$dir_income_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','80');
	$this->addElement($dir_income_remarks);
	
	$dir_income_reference= new Zend_Form_Element_Text('dir_income_reference');
	$dir_income_reference->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','80');
	$this->addElement($dir_income_reference);
	
	$dir_business_remarks= new Zend_Form_Element_Text('dir_business_remarks');
	$dir_business_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','80');
	$this->addElement($dir_business_remarks);
	
	$dir_business_reference= new Zend_Form_Element_Text('dir_business_reference');
	$dir_business_reference->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','80');
	$this->addElement($dir_business_reference);
	
	$existingloan_remarks= new Zend_Form_Element_Text('existingloan_remarks');
	$existingloan_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','80');
	$this->addElement($existingloan_remarks);
	
	$existingloan_reference= new Zend_Form_Element_Text('existingloan_reference');
	$existingloan_reference->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','80');
	$this->addElement($existingloan_reference);

	$penalty_charge= new Zend_Form_Element_Text('penalty_charge');
	$penalty_charge->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				  ->setAttrib('onKeypress','return numOnlyHyphen(event)')
				 ->setAttrib('size','25');
	$this->addElement($penalty_charge);
	
	$pn_amount= new Zend_Form_Element_Text('pn_amount');
	$pn_amount->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				  ->setAttrib('onKeypress','return numOnlyHyphen(event)')
				 ->setAttrib('size','25');
	$this->addElement($pn_amount);

	$recommendation_remarks= new Zend_Form_Element_TextArea('recommendation_remarks');
	$recommendation_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('rows','4');
	$this->addElement($recommendation_remarks);

	
	$approved_by = new Zend_Form_Element_Select('approved_by');
	$approved_by->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('0' ,'')
		 
		 ->setAttrib('style','width: 180px')
	    	  ->setValue('0');
		 $table = new Model_Users();
		 $sql = $table->select()->where("role_type = 'CO' or  role_type='CSH' or  role_type='CMGH' or  role_type='PRES' or  role_type='ALMH'")->order("id ASC");
		 foreach ($table->fetchAll($sql,"id") as $c) {
         $approved_by->addMultiOption($c->username, $c->name);} 
	 $approved_by->addMultiOption('Sub CreCom','Sub CreCom')
			->addMultiOption('CreCom','CreCom')
			->addMultiOption('Board','Board');
		
			
	$this->addElement($approved_by);
	

	
	$decision = new Zend_Form_Element_Select('decision');
	$decision->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('0' ,'')
		 ->addMultiOption('Approved' ,'Approved')
		 ->addMultiOption('Approved with Deviation' ,'Approved with Deviation')
		  ->addMultiOption('Approved' ,'Approved')
		 ->addMultiOption('Passed' ,'Passed')
		 ->addMultiOption('Disapproved' ,'Disapproved')
		->addMultiOption('Recommended' ,'Recommended')
		->addMultiOption('Endorsed' ,'Endorsed')
		->addMultiOption('Approved______Disapproved______' ,'Approved______Disapproved______')
		 ->setAttrib('style','width: 180px')
	   	 ->setValue('0');
	$this->addElement($decision);
	
	$reason = new Zend_Form_Element_Text('reason');
	$reason ->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				->addFilter('StringToUpper')
				->setAttrib('size','45');
	$this->addElement($reason);

	$prepared_by = new Zend_Form_Element_Select('prepared_by');
	$prepared_by->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption(0 ,'')
		  ->addMultiOption(1,'')
		 ->setAttrib('style','width: 180px')
	         ->setValue('0');
		 $table = new Model_Users();
		$sql = $table->select()	    ->where('role_type LIKE ?', 'CO')->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $prepared_by->addMultiOption($c->username, $c->name);} 
	$this->addElement($prepared_by);
	
	
	
	$ITR_within = new Zend_Form_Element_Select('ITR_within');
	$ITR_within->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('YES' ,'YES')
		 ->addMultiOption('NO' ,'NO')
		 ->addMultiOption('NA' ,'NA')
		 ->setAttrib('style','width: 50px')
	   	 ->setValue('YES');
	$this->addElement($ITR_within);
	
	
	
	$PDC_within = new Zend_Form_Element_Select('PDC_within');
	$PDC_within->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('YES' ,'YES')
		 ->addMultiOption('NO' ,'NO')
		 ->addMultiOption('NA' ,'NA')
		 ->setAttrib('style','width: 50px')
	   	 ->setValue('YES');
	$this->addElement($PDC_within);
	
	

	$CFUSCA_within = new Zend_Form_Element_Select('CFUSCA_within');
	$CFUSCA_within->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('YES' ,'YES')
		 ->addMultiOption('NO' ,'NO')
		 ->addMultiOption('NA' ,'NA')
		 ->setAttrib('style','width: 50px')
	   	 ->setValue('YES');
	$this->addElement($CFUSCA_within);
	
	
	$bus_reg_within = new Zend_Form_Element_Select('bus_reg_within');
	$bus_reg_within->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('NA' ,'NA')
		 ->addMultiOption('YES' ,'YES')
		 ->addMultiOption('NO' ,'NO')
		 ->setAttrib('style','width: 50px')
	   	 ->setValue('NA');
	$this->addElement($bus_reg_within);
	
	$bylaws_within = new Zend_Form_Element_Select('bylaws_within');
	$bylaws_within->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('NA' ,'NA')
		 ->addMultiOption('YES' ,'YES')
		 ->addMultiOption('NO' ,'NO')
		 ->setAttrib('style','width: 50px')
	   	 ->setValue('NA');
	$this->addElement($bylaws_within);
	
	$board_resolution_within = new Zend_Form_Element_Select('board_resolution_within');
	$board_resolution_within->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('NA' ,'NA')
		 ->addMultiOption('YES' ,'YES')
		 ->addMultiOption('NO' ,'NO')
		 ->setAttrib('style','width: 50px')
	   	 ->setValue('NA');
	$this->addElement($board_resolution_within);
	
	$Gen_infosheet_within = new Zend_Form_Element_Select('Gen_infosheet_within');
	$Gen_infosheet_within->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('NA' ,'NA')
		 ->addMultiOption('YES' ,'YES')
		 ->addMultiOption('NO' ,'NO')
		 ->setAttrib('style','width: 50px')
	   	 ->setValue('NA');
	$this->addElement($Gen_infosheet_within);
	
	
	
	$other_requirement = new Zend_Form_Element_Text('other_requirement');
	$other_requirement->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 250px');
	   
	$this->addElement($other_requirement);
	
	
	$deviationfield= new Zend_Form_Element_Text('deviationfield');
	$deviationfield->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','30');
	$this->addElement($deviationfield);
	
	$deviation_name= new Zend_Form_Element_Text('deviation_name');
	$deviation_name->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','50');
	$this->addElement($deviation_name);
	

	
	$deviation_value= new Zend_Form_Element_Text('deviation_value');
	$deviation_value->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','30');
	$this->addElement($deviation_value);
	
	$deviation_status= new Zend_Form_Element_Text('deviation_status');
	$deviation_status->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','5');
	$this->addElement($deviation_status);
	
	$deviation_remarks= new Zend_Form_Element_Text('deviation_remarks');
	$deviation_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','30');
	$this->addElement($deviation_remarks);
	
	$deviation_type= new Zend_Form_Element_Select('deviation_type');
	$deviation_type->removeDecorator('label')
				->removeDecorator('HtmlTag')
				->addFilter('StringTrim')
				->addFilter('StripTags')
				->addMultiOption('additional' ,'Deviation')
		 		->addMultiOption('others' ,'Others');
	$this->addElement($deviation_type);
	
	$source_info= new Zend_Form_Element_Text('source_info');
	$source_info->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','20');
	$this->addElement($source_info);

	$personaldata_remarks= new Zend_Form_Element_TextArea('personaldata_remarks');
	$personaldata_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('rows','3');
	$this->addElement($personaldata_remarks);
	
	$loandetails_remarks = new Zend_Form_Element_TextArea('loandetails_remarks');
	$loandetails_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('rows','3');
	$this->addElement($loandetails_remarks);
	
	$debtincome_remarks = new Zend_Form_Element_TextArea('debtincome_remarks');
	$debtincome_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('rows','3');
	$this->addElement($debtincome_remarks);
	
	$recc_others = new Zend_Form_Element_Select('recc_others');
	$recc_others->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('RECOMMENDATION' ,'RECOMMENDATION')
		 ->addMultiOption('OTHERS' ,'OTHERS')
		 ->setAttrib('style','width: 180px');
	$this->addElement($recc_others);
	
	$recc_others_remarks = new Zend_Form_Element_Text('recc_others_remarks');
	$recc_others_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','60');
	$this->addElement($recc_others_remarks);
	}
}


?>
