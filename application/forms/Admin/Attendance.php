<?
class Form_Admin_Attendance extends Zend_Dojo_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);


	$approver = new Zend_Form_Element_Select('approver');
	$approver->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('0' ,'')
		 ->setAttrib('style','width: 160px')
	   	 ->setValue('0');
 	$table = new Model_Users();
	$sql = $table->select()->where("role_type = 'CO' or  role_type='CSH' or  role_type='CMGH' or  role_type='PRES' or  role_type='ALMH'")->order("id ASC");
	foreach ($table->fetchAll($sql,"id") as $c) {
    $approver->addMultiOption($c->username, $c->name);} 
		$this->addElement($approver);
		
	$status = new Zend_Form_Element_Select('status');
	$status->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('0' ,'')
		 ->setAttrib('style','width: 80px')
	   	 ->setValue('0');
    $status->addMultiOption(0,'');
	$status->addMultiOption('absent','Leave'); 
		$this->addElement($status);
		
	$alternate = new Zend_Form_Element_Select('alternate');
	$alternate->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('' ,'')
		 ->setAttrib('style','width: 160px')
	   	 ->setValue('0');
 	$table = new Model_Users();
	$sql = $table->select()->where("role_type = 'CO' or  role_type='CSH' or  role_type='CMGH' or  role_type='PRES' or  role_type='ALMH'")->order("id ASC");
	foreach ($table->fetchAll($sql,"id") as $c) {
    $alternate->addMultiOption($c->username, $c->name);} 
	$this->addElement($alternate);

	
	$remarks = new Zend_Form_Element_Text('remarks');
	$remarks->removeDecorator('label')
				 ->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				  ->setAttrib('onKeypress','return numOnlyHyphen(event)')
				 ->setAttrib('size','25');
	$this->addElement($remarks);
	
	}
}


?>
