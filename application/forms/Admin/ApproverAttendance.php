<?
/**
Alexis Angelo S. Sadie
*/


class Form_Admin_ApproverAttendance extends Zend_Dojo_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);

		$approver = new Zend_Form_Element_Select('approver');
		$approver->removeDecorator('label')
				 ->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addMultiOption('' ,'')
				 ->setValue('');	
		$users = new Model_Users();
		$table = $users->getApprovers();
		foreach($table as $row){
			$approver->addMultiOption($row->username, $row->name);
		}
		$this->addElement($approver);
		
		$this->addElement('DateTextBox','dateFrom',array('style' => 'width:100px',));
		$this->dateFrom->removeDecorator('HtmlTag', array('tag' => 'dd'));
		$this->dateFrom->removeDecorator('DtDdWrapper'); 
		$this->dateFrom->removeDecorator('label');  
		
		$this->addElement('DateTextBox','dateTo',array('style' => 'width:100px'));
		$this->dateTo->removeDecorator('HtmlTag', array('tag' => 'dd'));
		$this->dateTo->removeDecorator('DtDdWrapper'); 
		$this->dateTo->removeDecorator('label'); 	
		
		$remarks = new Zend_Form_Element_Text('remarks');
		$remarks->removeDecorator('label')
					 ->removeDecorator('HtmlTag')
					 ->addFilter('StringTrim')
					 ->addFilter('StripTags')
					 //->setAttrib('onKeypress','return numOnlyHyphen(event)')
					 ->setAttrib('size','25');
		$this->addElement($remarks);
	
	}
}


?>
