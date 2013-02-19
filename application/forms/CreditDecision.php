<?
class Form_CreditDecision extends Zend_Dojo_Form
{
	

    public function __construct($options = null)
    {
     parent::__construct($options);


    //$approving_authority = new Zend_Form_Element_Select('approving_authority');
	$approving_authority = new Zend_Form_Element_Text('approving_authority');
	$approving_authority->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 //->addMultiOption(0 ,'')
		 ->setAttrib('style','width: 190px');
	      //->setValue('0');
	$this->addElement($approving_authority);
	
	$decision = new Zend_Form_Element_Select('decision');
	$decision->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		 ->addMultiOption('CO Approved' ,'Approved')
		 ->addMultiOption('CO Rejected' ,'Rejected')
		 ->setAttrib('style','width: 190px')
	      ->setValue('0');
	$this->addElement($decision);
	
	/*$this->addElement(
                'DateTextBox',
                'date_decide',
                array('style' => 'width:150px',)
            );
	$this->date_decide->removeDecorator('HtmlTag', array('tag' => 'dd')); 
	*/
	

	$submit_remarks = new Zend_Form_Element_Textarea('submit_remarks');
	$submit_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				->setAttrib('id','submit_remarks')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('rows','4')
				 ->setAttrib('style','width:370px');
				 //->setAttrib('onKeypress','return alphaNumSpaceOnly(event)');
	$this->addElement($submit_remarks);
	
	$co_name = new Zend_Form_Element_Select('co_name');
	$co_name->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		 ->setAttrib('style','width: 120px');
		 $table = new Model_Users();
		$sql = $table->select()
	    ->where("role_type = 'CO'")->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $co_name->addMultiOption($c->username, $c->name);} 
	$this->addElement($co_name);
	
	$csh_name = new Zend_Form_Element_Select('csh_name');
	$csh_name->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		 ->setAttrib('style','width: 120px');

		 $table = new Model_Users();
		$sql = $table->select()
	    ->where("role_type = 'CSH'")->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $csh_name->addMultiOption($c->username, $c->name);} 
	$this->addElement($csh_name);
	
	$cmg_name =  new Zend_Form_Element_Select('cmg_name');
	$cmg_name->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		 ->setAttrib('style','width: 120px');
		 $table = new Model_Users();
		$sql = $table->select()
	    ->where("role_type = 'CMGH'")->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $cmg_name->addMultiOption($c->username, $c->name);} 
	$this->addElement($cmg_name);
	
	$pres_name =  new Zend_Form_Element_Select('pres_name');
	$pres_name->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		 ->setAttrib('style','width: 120px');
		 $table = new Model_Users();
		$sql = $table->select()
	    ->where("role_type = 'PRES'")->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $pres_name->addMultiOption($c->username, $c->name);} 
	$this->addElement($pres_name);
	
	$pres_decision =  new Zend_Form_Element_Select('pres_decision');
	$pres_decision->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 120px')
	     ->setValue('0')
		->addMultiOption('','')
		->addMultiOption('Approved','Approve')
		->addMultiOption('Disapprove','Dissaprove')
		->addMultiOption('Pass','Pass');	
	$this->addElement($pres_decision);
	
	$co_decision =  new Zend_Form_Element_Select('co_decision');
	$co_decision->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 120px')
	     ->setValue('0')
		->addMultiOption('','')
		->addMultiOption('Recommend for Approval','Recommend for Approval')
		->addMultiOption('Recommend for Rejection','Recommend for Rejection')
		->addMultiOption('Approved','Approve')
		->addMultiOption('Disapprove','Dissaprove')
		->addMultiOption('Pass','Pass');	
	$this->addElement($co_decision);
	
	$cmg_decision =  new Zend_Form_Element_Select('cmg_decision');
	$cmg_decision->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 120px')
	     ->setValue('0')
		->addMultiOption('','')
		->addMultiOption('Approved','Approve')
		->addMultiOption('Disapprove','Dissaprove')
		->addMultiOption('Pass','Pass');	
	$this->addElement($cmg_decision);
	
	$csh_decision =  new Zend_Form_Element_Select('csh_decision');
	$csh_decision->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 120px')
	     ->setValue('0')
		->addMultiOption('','')
		->addMultiOption('Recommend for Approval','Recommend for Approval')
		->addMultiOption('Recommend for Rejection','Recommend for Rejection')
		->addMultiOption('Approved','Approve')
		->addMultiOption('Disapprove','Dissaprove')
		->addMultiOption('Pass','Pass');	
	$this->addElement($csh_decision);
	
	$co_date= new Zend_Form_Element_Text('co_date');
	$co_date->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
			 ->setAttrib('Maxlength',10)
			 ->setAttrib('onclick','this.value = ""')
			 ->setAttrib('onKeypress','return numOnlySlash(event)')
		     ->setAttrib('size','7');
	$this->addElement($co_date);
	
	$csh_date= new Zend_Form_Element_Text('csh_date');
	$csh_date->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
			 ->setAttrib('Maxlength',10)
			 ->setAttrib('onclick','this.value = ""')
			 ->setAttrib('onKeypress','return numOnlySlash(event)')
		     ->setAttrib('size','7');
	$this->addElement($csh_date);
	
	$cmg_date= new Zend_Form_Element_Text('cmg_date');
	$cmg_date->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
			 ->setAttrib('Maxlength',10)
			 ->setAttrib('onclick','this.value = ""')
			 ->setAttrib('onKeypress','return numOnlySlash(event)')
		     ->setAttrib('size','7');
	$this->addElement($cmg_date);
	
	$pres_date= new Zend_Form_Element_Text('pres_date');
	$pres_date->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
			 ->setAttrib('Maxlength',10)
			 ->setAttrib('onclick','this.value = ""')
			 ->setAttrib('onKeypress','return numOnlySlash(event)')
		     ->setAttrib('size','7');
	$this->addElement($pres_date);
	
	$subcrecom_date= new Zend_Form_Element_Text('subcrecom_date');
	$subcrecom_date->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
			 ->setAttrib('Maxlength',10)
			 ->setAttrib('onclick','this.value = ""')
			 ->setAttrib('onKeypress','return numOnlySlash(event)')
		     ->setAttrib('size','7');
	$this->addElement($subcrecom_date);
	
	$crecom_date= new Zend_Form_Element_Text('crecom_date');
	$crecom_date->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
		     
			 ->setAttrib('Maxlength',10)
			 ->setAttrib('onclick','this.value = ""')
			 ->setAttrib('onKeypress','return numOnlySlash(event)')
		     ->setAttrib('size','7');
	$this->addElement($crecom_date);
	
	$board_date= new Zend_Form_Element_Text('board_date');
	$board_date->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
			 ->setAttrib('Maxlength',10)
			 ->setAttrib('onclick','this.value = ""')
			 ->setAttrib('onKeypress','return numOnlySlash(event)')
		     ->setAttrib('size','7');
	$this->addElement($board_date);
	
	
	$subcrecom_decision =  new Zend_Form_Element_Select('subcrecom_decision');
	$subcrecom_decision->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 120px')
		->addMultiOption('','')
		->addMultiOption('Approved','Approve')
		->addMultiOption('Disapprove','Dissaprove')
		->addMultiOption('Pass','Pass');	
	$this->addElement($subcrecom_decision);
	
	$crecom_decision =  new Zend_Form_Element_Select('crecom_decision');
	$crecom_decision->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 120px')
		->addMultiOption('','')
		->addMultiOption('Approved','Approve')
		->addMultiOption('Disapprove','Dissaprove')
		->addMultiOption('Pass','Pass');	
	$this->addElement($crecom_decision);
	
	$board_decision =  new Zend_Form_Element_Select('board_decision');
	$board_decision->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 120px')
	     ->setValue('0')
		->addMultiOption('','')
		->addMultiOption('Approved','Approve')
		->addMultiOption('Disapprove','Dissaprove');	
	$this->addElement($board_decision);
	
	$application_decision =  new Zend_Form_Element_Select('application_decision');
	$application_decision->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 120px')
	     ->setValue('0')
		->addMultiOption('','')
		->addMultiOption('Approved','Approve')
		->addMultiOption('Rejected','Dissaprove');	
	$this->addElement($application_decision);
	
	$application_date= new Zend_Form_Element_Text('application_date');
	$application_date->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
			 ->setAttrib('Maxlength',10)
			 ->setAttrib('onclick','this.value = ""')
			 ->setAttrib('onKeypress','return numOnlySlash(event)')
		     ->setAttrib('size','7');
	$this->addElement($application_date);
	
	
	$application_name =  new Zend_Form_Element_Select('application_name');
	$application_name->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		 ->setAttrib('style','width: 120px')
	     ->setValue('0');
		 $table = new Model_Users();
		$sql = $table->select()
	    ->where("role_type = 'CMGH' OR role_type = 'CO' OR role_type = 'CSH' OR role_type = 'PRES' ")->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $application_name->addMultiOption($c->username, $c->name);}
		 $application_name->addMultiOption('Sub Crecom', 'Sub Crecom');
		 $application_name->addMultiOption('Crecom', 'Crecom');
		 $application_name->addMultiOption('Board', 'Board'); 
	$this->addElement($application_name);
	
	$endorse_to =  new Zend_Form_Element_Text('endorse_to');
	$endorse_to->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 120px')
		 ->setAttrib('Readonly','Readonly');
	$this->addElement($endorse_to);
	
	
	
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
	}
}
?>
