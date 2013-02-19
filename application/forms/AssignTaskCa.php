<?
class Form_AssignTaskCa extends Zend_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
	
	$assigned_to= new Zend_Form_Element_Select('assigned_to');
	$assigned_to->removeDecorator('label')
		 ->removeDecorator('HtmlTag')
		 ->addFilter('StringTrim')
		 ->addMultiOption('0','')
		 ->setAttrib('style','width: 155px');

	 $this->addElement($assigned_to);
	 $table = new Model_Users();
		$sql = $table->select()
	    ->where('role_type LIKE ?', 'CA')->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $assigned_to->addMultiOption($c->username, $c->name);} 
		
         
	}
}


?>
