<?
class Form_EdocsPage extends Zend_Dojo_Form
{
	

    public function __construct($options = null)
    {
    parent::__construct($options);
	$this->setName('upload');
    $this->setAttrib('enctype', 'multipart/form-data');

		$file = new Zend_Form_Element_File('file');
        $file->removeDecorator('label')
			->removeDecorator('HtmlTag')
                 ->setRequired(true)
                 ->addValidator('NotEmpty')
		 ->setDestination('tmpfiles')
		 ->addValidator('Extension', false, array('jpg,pdf','messages'=>'- Not A Valid Extension JPG PDF Only'));
    	$this->addElement($file);
		
		$description = new Zend_Form_Element_Text('desc');
        $description->removeDecorator('label')
					->removeDecorator('HtmlTag');
               	    //->setRequired(true)
                	//->addValidator('NotEmpty');
		$this->addElement($description);
		
		$sel_doc_borrower = new Zend_Dojo_Form_Element_ComboBox('sel_doc_borrower');
		$sel_doc_borrower->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 120px')
			->setValue('0')
			->addMultiOption(0,'');
			$table = new Model_ListSelectValues();
		    $sql = $table->select()
	   		->where('type LIKE ?', 'EdocsBorrower')
			->order("seq ASC");
		foreach ($table->fetchAll($sql,"seq ASC") as $c) {
        $sel_doc_borrower->addMultiOption($c->seq, $c->value);} 
		$this->addElement($sel_doc_borrower);
		
		$sel_doc_collateral = new Zend_Dojo_Form_Element_ComboBox('sel_doc_collateral');
		$sel_doc_collateral->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 120px')
			->setValue('0')
			->addMultiOption(0,'');
			$table = new Model_ListSelectValues();
		    $sql = $table->select()
	   		->where('type LIKE ?', 'EdocsCollateral')
			->order("seq ASC");
		foreach ($table->fetchAll($sql,"seq ASC") as $c) {
        $sel_doc_collateral->addMultiOption($c->seq, $c->value);} 
		$this->addElement($sel_doc_collateral);
		
		$sel_doc_cv = new Zend_Dojo_Form_Element_ComboBox('sel_doc_cv');
		$sel_doc_cv->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 120px')
			->setValue('0')
			->addMultiOption(0,'');
			$table = new Model_ListSelectValues();
		    $sql = $table->select()
	   		->where('type LIKE ?', 'EdocsCollateral')
			->order("seq ASC");
		foreach ($table->fetchAll($sql,"seq ASC") as $c) {
        $sel_doc_cv->addMultiOption($c->seq, $c->value);} 
		$this->addElement($sel_doc_cv);
		
		$sel_doc_ci = new Zend_Dojo_Form_Element_ComboBox('sel_doc_ci');
		$sel_doc_ci->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 120px')
			->setValue('0')
			->addMultiOption(0,'');
			$table = new Model_ListSelectValues();
		    $sql = $table->select()
	   		->where('type LIKE ?', 'EdocsCollateral')
			->order("seq ASC");
		foreach ($table->fetchAll($sql,"seq ASC") as $c) {
        $sel_doc_ci->addMultiOption($c->seq, $c->value);} 
		$this->addElement($sel_doc_ci);
		
		$sel_doc_employ = new Zend_Dojo_Form_Element_ComboBox('sel_doc_employ');
		$sel_doc_employ->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 120px')
			->setValue('0')
			->addMultiOption(0,'');
			$table = new Model_ListSelectValues();
		    $sql = $table->select()
	   		->where('type LIKE ?', 'EdocsCollateral')
			->order("seq ASC");
		foreach ($table->fetchAll($sql,"seq ASC") as $c) {
        $sel_doc_employ->addMultiOption($c->seq, $c->value);} 
		$this->addElement($sel_doc_employ);
		
		$sel_doc_others = new Zend_Dojo_Form_Element_ComboBox('sel_doc_others');
		$sel_doc_others->removeDecorator('label')
			->removeDecorator('HtmlTag')
			->setAttrib('style','width: 120px')
			->setValue('0')
			->addMultiOption(0,'');
			$table = new Model_ListSelectValues();
		    $sql = $table->select()
	   		->where('type LIKE ?', 'EdocsCollateral')
			->order("seq ASC");
		foreach ($table->fetchAll($sql,"seq ASC") as $c) {
        $sel_doc_others->addMultiOption($c->seq, $c->value);} 
		$this->addElement($sel_doc_others);



}

}
?>
