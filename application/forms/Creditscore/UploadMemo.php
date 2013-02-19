<?php

class Form_Creditscore_UploadMemo extends Zend_Form
{
	public function __construct($options=null)
	{
		parent::__construct($options);
		$this->setName('UPLOAD MEMO');
		
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

		$uploadName = new Zend_Form_Element_Text('uploadName');
		$uploadName->setRequired(true)
			      ->removeDecorator('label')
		     	  ->removeDecorator('HtmlTag')
			   	  ->addFilter('StripTags')
			      ->addFilter('StringTrim')
				  ->setAttrib('onmouseover','Tip("File Name")')
				  ->setAttrib('onmouseout','UnTip()')
				  ->setAttrib('onKeypress','return alphaNumOnly(event)')
	 		      ->addValidator('NotEmpty');
		$this->addElement($uploadName);

		$browse = new Zend_Form_Element_Submit('browse');
		$browse->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->removeDecorator('DtDdWrapper')
			   ->setAttrib('id','browsebutton');
		$this->addElement($browse);		

		$upload = new Zend_Form_Element_Submit('upload');
		$upload->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->removeDecorator('DtDdWrapper')
		       ->setAttrib('id','uploadbutton');
		$this->addElement($upload);		

	}
}
?>