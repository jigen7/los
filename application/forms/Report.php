<?
class Form_Report extends Zend_Dojo_Form
{
	

    public function __construct($options = null)
    {
     parent::__construct($options);


    
	$employment = new Zend_Form_Element_Select('employment');
	$employment->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption(0 ,'')
		 ->addMultiOption(1 ,'Employed')
		 ->addMultiOption(2 ,'Self-Employed')
		 ->setAttrib('style','width: 120px')
	         ->setValue('0');
	$this->addElement($employment);
	
	$this->addElement(
                'DateTextBox',
                'fromdate',
                array('style' => 'width:100px',)
            );
	$this->fromdate->removeDecorator('HtmlTag', array('tag' => 'dd')); 
	
	$this->addElement(
                'DateTextBox',
                'todate',
                array(
                 'style' => 'width:100px',
                )
            );
	$this->todate->removeDecorator('HtmlTag', array('tag' => 'dd')); 
	
	
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
	
	
	$actions = new Zend_Form_Element_Select('actions');
	$actions->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption(0 ,'')
		 ->addMultiOption(1 ,'Submit')
		 ->addMultiOption(2 ,'Recommend')
		 ->addMultiOption(3 ,'Request for CI')
		 ->addMultiOption(4 ,'RTS')
		 ->addMultiOption(5 ,'Recon')
		 ->addMultiOption(6 ,'Conditional Approve')
		 ->setAttrib('style','width: 120px')
	         ->setValue('0');
	$this->addElement($actions);
	
	$term = new Zend_Form_Element_Select('term');
	$term->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption(0 ,'')
		 ->addMultiOption(1 ,'01-12 months')
		 ->addMultiOption(2 ,'13-24 months')
		 ->addMultiOption(3 ,'25-36 months')
		 ->addMultiOption(4 ,'37-48 months')
		 ->addMultiOption(5 ,'49-60 months ')
		 ->addMultiOption(6 ,'61-72 months')
		 ->setAttrib('style','width: 120px')
	         ->setValue('0');
	$this->addElement($term);
	
	$score = new Zend_Form_Element_Select('score');
	$score->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption(0 ,'')
		 ->addMultiOption(1 ,'P1')
		 ->addMultiOption(2 ,'P2')
		 ->addMultiOption(3 ,'F1')
		 ->addMultiOption(4 ,'F2 ')
		 ->addMultiOption(5 ,'F3')
		 ->setAttrib('style','width: 70px')
	         ->setValue('0');
	$this->addElement($score);
	
	$user = new Zend_Form_Element_Select('user');
	$user->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption(0 ,'')
		 ->setAttrib('style','width: 120px')
	         ->setValue('0');
		 /* Add Marketing Assistant
		 $table = new Model_ListDealer();
		 foreach ($table->fetchAll(null,"id ASC") as $c) {
		$marketingofficer->addMultiOption($c->id, $c->name);} 
		*/
	$this->addElement($user);
	
	$decision = new Zend_Form_Element_Select('decision');
	$decision->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption(0 ,'')
		 ->addMultiOption(1 ,'Approved')
		 ->addMultiOption(2 ,'Rejected')
		 ->setAttrib('style','width: 120px')
	     ->setValue('0');
	$this->addElement($decision);
	
	
	$this->addElement(
		'SubmitButton',
		'submit',
		array(
		'ignore' => true,
		'label' => 'Search',
		'style' => 'width:200px',
			)
	);
	
	
	
		
}

}
?>
