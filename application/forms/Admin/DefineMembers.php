<?
/**
Alexis Angelo S. Sadie
*/


class Form_Admin_DefineMembers extends Zend_Dojo_Form
{
    public function __construct($options = null){
	parent::__construct($options);

		$ccmodel = new Model_AutoRouting_CrecomConfig();
		$cctable = $ccmodel->getAll();

		$ctype = new Zend_Form_Element_Select('ctype');
		$ctype->removeDecorator('label')
			  ->removeDecorator('HtmlTag')
			  ->setattrib('id','ctype')
			  ->setAttrib('width','15');
		$ctype->addMultiOption("","");	  
		foreach($cctable as $ccrow){
			$ctype->addMultiOption($ccrow->id, $ccrow->type);
		}	   		
		$this->addElement($ctype);	

		$croles = new Zend_Form_Element_Select('croles');
		$croles->removeDecorator('label')
			   ->removeDecorator('HtmlTag')
			   ->setattrib('id','croles')
			   ->setAttrib('width','15');
		$croles->addMultiOption("","");	
		foreach($cctable as $ccrow){
			$croles->addMultiOption($ccrow->id, $ccrow->role);
		}	   		
		$this->addElement($croles);		
		
		$scmodel = new Model_AutoRouting_SubCrecomConfig();
		$sctable = $scmodel->getAll();

		$stype = new Zend_Form_Element_Select('stype');
		$stype->removeDecorator('label')
			  ->removeDecorator('HtmlTag')
			  ->setattrib('id','stype')
			  ->setAttrib('width','15');
		$stype->addMultiOption("","");	  
		foreach($sctable as $scrow){
			$stype->addMultiOption($scrow->id, $scrow->type);
		}	   		
		$this->addElement($stype);	

		$sroles = new Zend_Form_Element_Select('sroles');
		$sroles->removeDecorator('label')
			   ->removeDecorator('HtmlTag')
			   ->setattrib('id','sroles')
			   ->setAttrib('width','15');
		$sroles->addMultiOption("","");		   
		foreach($sctable as $scrow){
			$sroles->addMultiOption($scrow->id, $scrow->role);
		}	   		
		$this->addElement($sroles);									
	
	}
}


?>
