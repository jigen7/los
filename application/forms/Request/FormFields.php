<?
class Form_Request_FormFields extends Zend_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);

	   
	$request_remarks = new Zend_Form_Element_Textarea('request_remarks');
	$request_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				->setAttrib('id','submit_remarks')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('rows','4')
				 ->setAttrib('style','width:370px');
				 //->setAttrib('onKeypress','return alphaNumSpaceOnly(event)');
	$this->addElement($request_remarks);
	
	$request_type = new Zend_Form_Element_Select('$request_type');
	$request_type->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		 ->setAttrib('style','width: 120px')
	     ->setValue('0')
		 ->addMultiOption('Request', 'Request')
		 ->addMultiOption('Error', 'Error')
 		 ->addMultiOption('Nice to Have', 'Nice to Have')
		 ->addMultiOption('Wish List', 'Wish List');
	 $this->addElement($request_type);
	
	$request_by = new Zend_Form_Element_Text('request_by');
	$request_by->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($request_by);
	
	$request_name = new Zend_Form_Element_Text('request_name');
	$request_name->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($request_name);
	
	$finished_remarks = new Zend_Form_Element_Textarea('finished_remarks');
	$finished_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				->setAttrib('id','finished_remarks')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('rows','4')
				 ->setAttrib('style','width:370px');
				 //->setAttrib('onKeypress','return alphaNumSpaceOnly(event)');
	$this->addElement($finished_remarks);
	
	$finished_by = new Zend_Form_Element_Text('finished_by');
	$finished_by->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($finished_by);
	
	$finished_date = new Zend_Form_element_Text('finished_date');
	$finished_date->removeDecorator('label')
		     ->removeDecorator('HtmlTag')
		     ->addFilter('StringTrim')
		     ->addFilter('StripTags')
		     ->setAttrib('value','MM/DD/YYYY')
		     ->setAttrib('size','15');
	$this->addElement($finished_date);
	
	$status = new Zend_Form_Element_Select('status');
	$status->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		 ->setAttrib('style','width: 120px')
	     ->setValue('')
		 ->addMultiOption('Pending', 'Pending')
		 ->addMultiOption('In-Progress', 'In-Progress')
		 ->addMultiOption('Done', 'Done');
	 $this->addElement($status);
	
	
	
	
	
	}
}