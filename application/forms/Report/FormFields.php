<?
class Form_Report_FormFields extends Zend_Dojo_Form
{
	//Form for Booking Box
    public function __construct($options = null)
    {
    parent::__construct($options);
    
    
	$this->addElement('DateTextBox','startdate',array(
                 'style' => 'width:100px',
				 'required' => true,
            	 'invalidMessage' => 'Please type your name.',
                )
            );
	$this->startdate->removeDecorator('HtmlTag', array('tag' => 'dd')); 
	    
	$this->addElement(
                'DateTextBox',
                'enddate',
                array(
                 'style' => 'width:100px',
				  'required' => true,
            	 'invalidMessage' => 'Please type your name.',
                )
            );
	$this->enddate->removeDecorator('HtmlTag', array('tag' => 'dd')); 

    $approved_by = new Zend_Form_Element_Select('approved_by');
	$approved_by->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		 ->setAttrib('style','width: 120px')
	     ->setValue('');
 		$table = new Model_Admin_Fields();
		$sql = $table->select()
	    ->where('name LIKE ?', 'Approver')->order("seq ASC");
		 foreach ($table->fetchAll($sql,"seq ASC") as $c) {
         $approved_by->addMultiOption($c->values, $c->values);} 
	 $this->addElement($approved_by);
	 
	$approval_level = new Zend_Form_Element_Select('approval_level');
	$approval_level->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		 ->setAttrib('style','width: 120px')
	     ->setValue('');
 		$table = new Model_Admin_Fields();
		$sql = $table->select()
	    ->where('name LIKE ?', 'Approver')->order("seq ASC");
		 foreach ($table->fetchAll($sql,"seq ASC") as $c) {
         $approval_level->addMultiOption($c->values, $c->values);} 
	 $this->addElement($approval_level);

	$branch = new Zend_Form_Element_Select('branch');
	$branch->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('','')
		 ->setAttrib('style','width: 120px');
		 $table = new Model_ListSelectValues();
		 $sql = $table->select()
	     ->where('type LIKE ?', 'Branch')->order("seq ASC");
		 foreach ($table->fetchAll($sql,"seq ASC") as $c) {
         $branch->addMultiOption($c->value, $c->value);} 
	$this->addElement($branch);


	$veh_brand = new Zend_Form_Element_Select('veh_brand');
	$veh_brand->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		 ->setAttrib('style','width: 120px')
	     ->setValue('');
 		$table = new Model_Admin_Fields();
		$sql = $table->select()
	    ->where('name LIKE ?', 'VehBrand')->order("values ASC");
		 foreach ($table->fetchAll($sql,"seq ASC") as $c) {
         $veh_brand->addMultiOption($c->values, $c->values);} 
	 $this->addElement($veh_brand);	
	
	$csd_decision = new Zend_Form_Element_Select('csd_decision');
	$csd_decision->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		 ->setAttrib('style','width: 120px')
	     ->setValue('')
		 ->addMultiOption('1' ,'Recommended')
		 ->addMultiOption('0' ,'Not Recommended');
	 $this->addElement($csd_decision);	 
	 
	$dealer = new Zend_Dojo_Form_Element_FilteringSelect('dealer');
	$dealer->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 150px')
			->addMultiOption(0,'');
		$table = new Model_ListDealer();
		$sql = $table->select()->order("name ASC");
		foreach ($table->fetchAll($sql,"seq ASC") as $c) {
        $dealer->addMultiOption($c->name, $c->name);} 
	$this->addElement($dealer);
	
	$submitted_ao = new Zend_Form_Element_Select('submitted_ao');
	$submitted_ao->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		 ->setAttrib('style','width: 120px')
	     ->setValue('');
 		$table = new Model_Users();
		 foreach ($table->returnUsersbyRole("AO") as $c) {
         $submitted_ao->addMultiOption($c->username, $c->name);} 
	$this->addElement($submitted_ao);	
    
	$source_application = new Zend_Form_Element_Select('source_application');
	$source_application->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->setAttrib('style','width: 120px')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
         ->setValue('');
         $table = new Model_ListSelectValues();
	$sql = $table->select()->where('type like ?','SourceApplication')->
	where("value = 'Branch' or value = 'Dealer'")->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $source_application->addMultiOption($c->value, $c->value);}
	$this->addElement($source_application);
	
	$loanterm = new Zend_Form_Element_Select('loanterm');
	$loanterm->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		 ->addMultiOption(12 ,'12')
		 ->addMultiOption(18 ,'18')
		 ->addMultiOption(24 ,'24')
         ->addMultiOption(36 ,'36')
         ->addMultiOption(48 ,'48')
         ->addMultiOption(60 ,'60')
		 ->setValue('');
		
	$this->addElement($loanterm);

	
	$downpayment_from = new Zend_Form_Element_Text('downpayment_from');
	$downpayment_from->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('onKeypress','return numOnly(event)')
				 ->setAttrib('size','1');
	$this->addElement($downpayment_from);
	
	$downpayment_to = new Zend_Form_Element_Text('downpayment_to');
	$downpayment_to->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('onKeypress','return numOnly(event)')
				 ->setAttrib('size','1');
	$this->addElement($downpayment_to);
	
    }
	
}


?>
