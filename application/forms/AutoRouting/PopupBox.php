<?
class Form_AutoRouting_PopupBox extends Zend_Form
{
	//Form for teh Deviation Box
    public function __construct($options = null)
    {
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
		 ->setAttrib('style','width: 100px')
		 ->addMultiOption('' ,'')
	     ->setValue('');
         $decision->addMultiOption('Approved','Approved')
				  ->addMultiOption('Disapproved','Disapproved');
	$this->addElement($decision);
	}
	

}


?>
