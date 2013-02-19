<?
class Form_Search extends Zend_Dojo_Form
{
	

    public function __construct($options = null)
    {
     parent::__construct($options);


     $capno = new Zend_Form_Element_Text('capno');
	 $capno->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
		     ->setAttrib('size','11')
		     ->setAttrib('onKeypress','return numOnly(event)');
	$this->addElement($capno);
	
	$borrower_lname = new Zend_Form_Element_Text('borrower_lname');
	$borrower_lname->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','30')
				 ->setAttrib('onmouseover','Tip("Borrower Last Name")')
				 ->setAttrib('onmouseout','UnTip()')
				 ->setAttrib('onKeypress','return alphacommaOnly(event)');
	$this->addElement($borrower_lname);

	$typeloan = new Zend_Form_Element_Select('typeloan');
	$typeloan->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
	   	 ->addMultiOption('0','Please Select...')
		 ->addMultiOption('1','Auto Loan')
		 ->addMultiOption('2','Housing Loan')
		 ->setValue('0');
	$this->addElement($typeloan);
	
	$this->addElement(
                'DateTextBox',
                'startdate',
                array(
                 'style' => 'width:100px',

                )
            );
	$this->startdate->removeDecorator('HtmlTag', array('tag' => 'dd')); 
	
	    
	$this->addElement(
                'DateTextBox',
                'enddate',
                array(
                 'style' => 'width:100px',
                )
            );
	$this->enddate->removeDecorator('HtmlTag', array('tag' => 'dd')); 

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
	
	$marketingassistant = new Zend_Form_Element_Select('marketingassistant');
	$marketingassistant->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption(0 ,'')
		 ->setAttrib('style','width: 120px')
	     ->setValue('0');
		 //Add Marketing Assistant
		 $table = new Model_Users();
		$sql = $table->select()
	    ->where('role_type LIKE ?', 'MA')->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $marketingassistant->addMultiOption($c->username, $c->name);}
         
	$this->addElement($marketingassistant);
	
	$area = new Zend_Form_Element_Text('area');
	$area->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','30')
				 ->setAttrib('onmouseover','Tip("Area")')
				 ->setAttrib('onmouseout','UnTip()')
				 ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($area);
	
	$decision = new Zend_Form_Element_Select('decision');
	$decision->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		 ->addMultiOption('CO - OR' ,'Outright Reject')
		 ->addMultiOption('CO - NA' ,'No Action')
 		 ->addMultiOption('CO - Cancel' ,'Cancel')
		 ->addMultiOption('Approved' ,'Approved')
		 ->addMultiOption('Rejected','Rejected')
		  ->addMultiOption('Booked','Booked')
		 ->setAttrib('style','width: 120px')
	     ->setValue('0');
	$this->addElement($decision);
	
	$sortby = new Zend_Form_Element_Select('sortby');
	$sortby->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'Please Select')
 		 ->addMultiOption(1 ,'Alphabetically')
		 ->setAttrib('style','width: 120px');
	$this->addElement($sortby);

	$source_application = new Zend_Form_Element_Select('source_application');
	$source_application->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->setAttrib('style','width: 120px')
		 ->addMultiOption('' ,'');

         $table = new Model_ListSelectValues();
	$sql = $table->select()->where('type like ?','SourceApplication')->where("value ='Branch' or value = 'Dealer'")->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $source_application->addMultiOption($c->value, $c->value);} 
	$this->addElement($source_application);

	$area = new Zend_Form_Element_Text('area');
	$area->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','18')
				 ->setAttrib('onmouseover','Tip("Area")')
				 ->setAttrib('onmouseout','UnTip()')
				 ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($area);
	
	$this->addElement(
		'SubmitButton',
		'submit',
		array(
		'ignore' => true,
		'label' => 'Search',
		'style' => 'width:200px',
			)
	);
	
	 $totalaccounts = new Zend_Form_Element_Text('totalaccounts');
	 $totalaccounts->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
			 ->setAttrib('disabled','disabled')
		     ->setAttrib('size','20');
	$this->addElement($totalaccounts);
	
	$totalloans = new Zend_Form_Element_Text('totalloans');
	$totalloans->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
			 ->setAttrib('disabled','disabled')
		     ->setAttrib('size','20');
	$this->addElement($totalloans);

	$acctProcess = new Zend_Form_Element_Select('acctProcess');
	$acctProcess->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'All')
		 ->addMultiOption('MA' ,'MA')
		 ->addMultiOption('CA' ,'CA')
		 ->addMultiOption('CO' ,'CO')
		 ->addMultiOption('CSH' ,'CSH')
		 ->addMultiOption('ALMH' ,'ALMH')		 		 
		 ->addMultiOption('CMGH' ,'CMGH')		 
		 ->addMultiOption('PRES' ,'PRES')
		 ->addMultiOption('Approved' ,'Approval')
		 ->addMultiOption('Rejected' ,'Rejected')
		 ->addMultiOption('AO' ,'AO')
		 ->addMultiOption('LA' ,'LA')
 		 ->addMultiOption('LO' ,'LO')
		 ->setAttrib('style','width: 120px')
	     ->setValue('0');
	$this->addElement($acctProcess);
	
	$acctLevel = new Zend_Form_Element_Select('acctLevel');
	$acctLevel->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'All')
		 ->addMultiOption('-CO' ,'CO')
		 ->addMultiOption('-CSH' ,'CSH')
		 ->addMultiOption('-CMGH' ,'CMGH')
		 ->addMultiOption('-PRES' ,'PRES')
		 ->addMultiOption('ALMH-EN' ,'ENDORSE')
        	->addMultiOption('-SUBCRECOM' ,'SUBCRECOM')
			->addMultiOption('-CRECOM' ,'CRECOM')
			->addMultiOption('-EXE-BOD' ,'BOD')		 
		 ->setAttrib('style','width: 120px')
	     ->setValue('0');
	$this->addElement($acctLevel);

	$acctPending = new Zend_Form_Element_Select('acctPending');
	$acctPending->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('MA' ,'Marketing Assistant')
		 ->addMultiOption('CA' ,'Credit Analyst')
		 ->addMultiOption('CO' ,'Credit Officer')
		 ->addMultiOption('CSH' ,'Credit Services Head')
		 ->addMultiOption('ALMH' ,'Auto Loan Marketing Head')	
		 ->addMultiOption('CMGH' ,'Credit Management Group Head')
		 ->addMultiOption('PRES' ,'President')
		 ->addMultiOption('SUBCRECOM' ,'SUBCRECOM')		 	 		 
		 ->addMultiOption('CRECOM' ,'CRECOM')
		 ->addMultiOption('BOD' ,'BOARD')
		 ->addMultiOption('AO' ,'Account Officer')		 
		 ->addMultiOption('LA' ,'Loan Assistant')
		 ->addMultiOption('LO' ,'Loan Officer')
		 ->setAttrib('style','width: 120px');
	$this->addElement($acctPending);

}

}
?>
