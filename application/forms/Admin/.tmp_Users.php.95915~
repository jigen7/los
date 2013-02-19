<?
class Form_Admin_Users extends Zend_Dojo_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
	

	$name = new Zend_Form_Element_Text('name');
	$name->removeDecorator('label')
				 ->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','40')
				 ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($name);
	
	$username = new Zend_Form_Element_Text('username');
	$username->removeDecorator('label')
				 ->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','40')
				 ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($username);

	$role = new Zend_Form_Element_Select('role');
	$role->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('onchange','displayOfficer(this.value)')
			->addMultiOption('','');			
		$table = new Model_UsersRoles();
		$select = $table->select()->order('desc');
		$detail = $table->fetchAll($select);
		foreach($detail as $x){
			$role->addMultiOption($x->roles,$x->desc);			
		}
	$this->addElement($role);
	
	$group = new Zend_Form_Element_Select('group');
	$group->removeDecorator('label')
			->removeDecorator('HtmlTag');
		$table = new Model_UsersRoles();
		$select = $table->select();
			$group->addMultiOption('','');			
			$group->addMultiOption('Marketing-Auto','Marketing-Auto');
			$group->addMultiOption('Marketing-Housing','Marketing-Housing');			
			$group->addMultiOption('Marketing-SME','Marketing-SME');			
			$group->addMultiOption('CMG-CSD','CMG-CSD');			
			$group->addMultiOption('CMG-CRM','CMG-CRM');			
			$group->addMultiOption('Operation-L&D','Operation-L&D');		
	$this->addElement($group);
	
	$birthdate = new Zend_Form_Element_Text('birthdate');
	$birthdate->removeDecorator('label')
				 ->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('size','10')
				 ->setAttrib('onKeyPress','return numOnlySlash(event)')
				 ->setAttrib('onclick','this.value=""');
	$this->addElement($birthdate);
	
	$emp_no = new Zend_Form_Element_Text('emp_no');
	$emp_no->removeDecorator('label')
				 ->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('onKeyPress','return numOnlyHyphen(event)')
				 ->setAttrib('size','20');
	$this->addElement($emp_no);
	
	$tin_no = new Zend_Form_Element_Text('tin_no');
	$tin_no->removeDecorator('label')
				 ->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->setAttrib('onKeyPress','return numOnlyHyphen(event)')
				 ->setAttrib('size','20');
	$this->addElement($tin_no);
	
	$subcrecom = new Zend_Form_Element_Select('subcrecom');
	$subcrecom->removeDecorator('label')
			->removeDecorator('HtmlTag');
		$table = new Model_UsersRoles();
		$select = $table->select();
			$group->addMultiOption('','');			
	$this->addElement($subcrecom);	
	
	$crecom = new Zend_Form_Element_Select('crecom');
	$crecom->removeDecorator('label')
			->removeDecorator('HtmlTag');
		$table = new Model_UsersRoles();
		$select = $table->select();
			$group->addMultiOption('','');			
	$this->addElement($crecom);	
	}
}


?>
