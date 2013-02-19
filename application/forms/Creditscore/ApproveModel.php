<?php

class Form_CreditScore_ApproveModel extends Zend_Form
{
	public function __construct($options=null)
	{
		parent::__construct($options);
		$this->setName('CREDIT - SCORING - AUTO - APPROVE MODEL');
		
		$modelName = new Zend_Form_Element_Text('modelName');
		$modelName->setRequired(true)
			      ->removeDecorator('label')
		     	  ->removeDecorator('HtmlTag')
			   	  ->addFilter('StripTags')
			      ->addFilter('StringTrim')
				  ->setAttrib('onmouseover','Tip("Model Name")')
				  ->setAttrib('onmouseout','UnTip()')
				  ->setAttrib('onKeypress','return alphaNumOnly(event)')
	 		      ->addValidator('NotEmpty');
		$this->addElement($modelName);

		$search = new Zend_Form_Element_Submit('search');
		$search->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->removeDecorator('DtDdWrapper')
		       ->setAttrib('id','searchbutton');
		$this->addElement($search);	
		
		$returnToCRA = new Zend_Form_Element_Submit('returnToCRA');
		$returnToCRA->removeDecorator('label')
		     	    ->removeDecorator('HtmlTag')
					->removeDecorator('DtDdWrapper')
		            ->setAttrib('id','returnToCRAbutton');
		$this->addElement($returnToCRA);
		
		$approve = new Zend_Form_Element_Submit('approve');
		$approve->removeDecorator('label')
		     	->removeDecorator('HtmlTag')
				->removeDecorator('DtDdWrapper')
		        ->setAttrib('id','approvebutton');
		$this->addElement($approve);					
	}
}
?>