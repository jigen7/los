<?
class Form_Recon extends Zend_Dojo_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
	
	
	
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
	
	$reason = new Zend_Form_Element_Text('reason');
	$reason ->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				->addFilter('StringToUpper')
				->setAttrib('size','45');
	$this->addElement($reason);
	
	$deviationfield= new Zend_Form_Element_Text('deviationfield');
	$deviationfield->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','30');
	$this->addElement($deviationfield);	
	
	}
}


?>
