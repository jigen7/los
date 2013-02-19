<?
class Form_AdminUser extends Zend_Dojo_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
	
	
	$fullname = new Zend_Form_Element_Text('fullname');
	$fullname->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25')
				 ->setAttrib('onmouseover','Tip("Full Name")')
				 ->setAttrib('onmouseout','UnTip()')
				 ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($fullname);
	
	$department = new Zend_Form_Element_Text('department');
	$department->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25')
				 ->setAttrib('onmouseover','Tip("Department")')
				 ->setAttrib('onmouseout','UnTip()')
				 ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($department);
	
	$branch = new Zend_Form_Element_Text('$branch');
	$branch->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25')
				 ->setAttrib('onmouseover','Tip("Branch")')
				 ->setAttrib('onmouseout','UnTip()')
				 ->setAttrib('onKeypress','return alphaOnly(event)');
	$this->addElement($branch);
	
				$username = $this->createElement('text','username');
				$username->removeDecorator('label')
					     ->removeDecorator('HtmlTag')
						 ->addFilter('StringTrim')
						 ->addFilter('StripTags')
						 ->setRequired(true);
				$this->addElement($username);
			
				$password = $this->createElement('password','password');
				$password->removeDecorator('label')
					     ->removeDecorator('HtmlTag')
						 ->addFilter('StringTrim')
						 ->addFilter('StripTags')
						 ->setRequired(true);
				$this->addElement($password);
						
				$confirmPassword = $this->createElement('password','confirmPassword');
				$confirmPassword->removeDecorator('label')
					     ->removeDecorator('HtmlTag')
						 ->addFilter('StringTrim')
						 ->addFilter('StripTags')
						 ->setRequired(true);
				$this->addElement($confirmPassword);
				
		$roles = new Zend_Form_Element_Select('roles');
		$roles->removeDecorator('label')
			 ->removeDecorator('HtmlTag')
		 	->addFilter('StringTrim')
 	 		 ->setAttrib('onChange','displaySubs(this.options[this.selectedIndex].value)')
		 	->setAttrib('style','width: 150px')
		 	->addMultiOption(0 ,'')
	     	->setValue('0');
		 $table = new Model_UsersRoles();
		 $sql = $table->select()->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $roles->addMultiOption($c->roles, $c->desc);} 
	$this->addElement($roles);
	
		$almh = new Zend_Form_Element_Select('almh');
		$almh->removeDecorator('label')
			 ->removeDecorator('HtmlTag')
		 	->addFilter('StringTrim')
		 	->setAttrib('style','width: 150px')
		 	->addMultiOption(0 ,'')
	     	->setValue('0');
		 $table = new Model_Users();
		 $sql = $table->select()->where('role_type like ?','ALMH')
		 ->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $almh->addMultiOption($c->username, $c->name);} 
	$this->addElement($almh);
	
		$dh = new Zend_Form_Element_Select('dh');
		$dh->removeDecorator('label')
			 ->removeDecorator('HtmlTag')
		 	->addFilter('StringTrim')
		 	->setAttrib('style','width: 150px')
		 	->addMultiOption(0 ,'')
	     	->setValue('0');
		 $table = new Model_Users();
		 $sql = $table->select()->where('role_type like ?','DH')
		 ->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $dh->addMultiOption($c->username, $c->name);} 
	$this->addElement($dh);
	
		$ah = new Zend_Form_Element_Select('ah');
		$ah->removeDecorator('label')
			 ->removeDecorator('HtmlTag')
		 	->addFilter('StringTrim')
		 	->setAttrib('style','width: 150px')
		 	->addMultiOption(0 ,'')
	     	->setValue('0');
		 $table = new Model_Users();
		 $sql = $table->select()->where('role_type like ?','AH')
		 ->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $ah->addMultiOption($c->username, $c->name);} 
	$this->addElement($ah);
	
		$mo = new Zend_Form_Element_Select('mo');
		$mo->removeDecorator('label')
			 ->removeDecorator('HtmlTag')
		 	->addFilter('StringTrim')
		 	->setAttrib('style','width: 150px')
		 	->addMultiOption(0 ,'')
	     	->setValue('0');
		 $table = new Model_Users();
		 $sql = $table->select()->where('role_type like ?','MO')
		 ->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $mo->addMultiOption($c->username, $c->name);} 
	$this->addElement($mo);
	
		$csh = new Zend_Form_Element_Select('csh');
		$csh->removeDecorator('label')
			 ->removeDecorator('HtmlTag')
		 	->addFilter('StringTrim')
		 	->setAttrib('style','width: 150px')
		 	->addMultiOption(0 ,'')
	     	->setValue('0');
		 $table = new Model_Users();
		 $sql = $table->select()->where('role_type like ?','CSH')
		 ->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $csh->addMultiOption($c->username, $c->name);} 
	$this->addElement($csh);
	
		$co = new Zend_Form_Element_Select('co');
		$co->removeDecorator('label')
			 ->removeDecorator('HtmlTag')
		 	->addFilter('StringTrim')
		 	->setAttrib('style','width: 150px')
		 	->addMultiOption(0 ,'')
	     	->setValue('0');
		 $table = new Model_Users();
		 $sql = $table->select()->where('role_type like ?','CO')
		 ->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $co->addMultiOption($c->username, $c->name);} 
	$this->addElement($co);
	
		$lo = new Zend_Form_Element_Select('lo');
		$lo->removeDecorator('label')
			 ->removeDecorator('HtmlTag')
		 	->addFilter('StringTrim')
		 	->setAttrib('style','width: 150px')
		 	->addMultiOption(0 ,'')
	     	->setValue('0');
		 $table = new Model_Users();
		 $sql = $table->select()->where('role_type like ?','LO')
		 ->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $lo->addMultiOption($c->username, $c->name);} 
	$this->addElement($lo);
	
	
		$crah = new Zend_Form_Element_Select('crah');
		$crah->removeDecorator('label')
			 ->removeDecorator('HtmlTag')
		 	->addFilter('StringTrim')
		 	->setAttrib('style','width: 150px')
		 	->addMultiOption(0 ,'')
	     	->setValue('0');
		 $table = new Model_Users();
		 $sql = $table->select()->where('role_type like ?','CRAH')
		 ->order("id ASC");
		 foreach ($table->fetchAll($sql,"id ASC") as $c) {
         $crah->addMultiOption($c->username, $c->name);} 
	$this->addElement($crah);
	
	
	
	
	}
}


?>
