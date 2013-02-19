<?php
class Form_Admin_Holiday extends Zend_Form
{
	public function __construct($options = null){
		parent::__construct($options);
		$this->setMethod('post'); 
		
		$select_year = new Zend_Form_Element_Select("select_year");
		$select_year->removeDecorator("label")
					->removeDecorator("HtmlTag");
		$this->addElement($select_year);
		
		$name = new Zend_Form_Element_Text("name");
		$name -> removeDecorator('label')
			  -> removeDecorator('HtmlTag');
		$this->addElement($name);
		
		$date_year = new Zend_Form_Element_Text("date_year");
		$date_year ->setAttrib("style","width:52px;text-align:right;")
			  -> removeDecorator('label')
			  -> removeDecorator('HtmlTag');
		$this->addElement($date_year);
		
		$date_month = new Zend_Form_Element_Text("date_month");
		$date_month ->setAttrib("style","width:36px;text-align:right;") 
			  -> removeDecorator('label')
			  -> removeDecorator('HtmlTag');
		$this->addElement($date_month);
		
		$date_day = new Zend_Form_Element_Text("date_day");
		$date_day ->setAttrib("style","width:36px;text-align:right;")
			  -> removeDecorator('label')
			  -> removeDecorator('HtmlTag');
		$this->addElement($date_day);
		
		$login = new Zend_Form_Element_Submit("submit");
		$login->removeDecorator('label')
			  ->removeDecorator('HtmlTag');
		$this->addElement($login);
		
		
	}
}