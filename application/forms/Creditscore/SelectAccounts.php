<?php

class Form_CreditScore_SelectAccounts extends Zend_Dojo_Form
{
	public function __construct($options=null)
	{
		parent::__construct($options);
		$this->setName('SELECT ACCOUNTS');
		
		$capNo = new Zend_Form_Element_Text('capNo');
		$capNo->setRequired(true)
			  ->addFilter('StripTags')
			  ->addFilter('StringTrim')
			  ->removeDecorator('label')
		      ->removeDecorator('HtmlTag');
		$this->addElement($capNo);
		
		$bLastName = new Zend_Form_Element_Text('bLastName');
		$bLastName->setRequired(true)
			  	  ->addFilter('StripTags')
			  	  ->addFilter('StringTrim')
				  ->removeDecorator('label')
		     	  ->removeDecorator('HtmlTag');
		$this->addElement($bLastName);
/*
		$dateFrom = new Zend_Form_Element_Text('dateFrom');
		$dateFrom->setRequired(true)
			  	 ->addFilter('StripTags')
			  	 ->addFilter('StringTrim')
				 ->setAttrib('size','10')
			  	 ->addValidator('NotEmpty')
				 ->removeDecorator('label')
		     	 ->removeDecorator('HtmlTag');				
		$this->addElement($dateFrom);
		
		$dateTo = new Zend_Form_Element_Text('dateTo');
		$dateTo->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->setRequired(true)
			   ->setAttrib('size','10')
			   ->addFilter('StripTags')
			   ->addFilter('StringTrim')
			   ->addValidator('NotEmpty');				
		$this->addElement($dateTo);
*/
		$this->addElement('DateTextBox','dateFrom',array('style' => 'width:100px',));
		$this->dateFrom->removeDecorator('HtmlTag', array('tag' => 'dd'));
		$this->dateFrom->removeDecorator('label');  
		$this->dateFrom->removeDecorator('DtDdWrapper'); 
    
		$this->addElement('DateTextBox','dateTo',array('style' => 'width:100px'));
		$this->dateTo->removeDecorator('HtmlTag', array('tag' => 'dd'));
		$this->dateTo->removeDecorator('DtDdWrapper'); 
		$this->dateTo->removeDecorator('label'); 
		$this->dateTo->setAttrib('readonly','');
		
		$status = new Zend_Form_Element_Select('status');
		$status->removeDecorator('label')
		       ->removeDecorator('HtmlTag')
			   ->addMultiOptions(array(
                    'APPROVED' 	=> 	'APPROVED',
                    'RTS' 		=> 	'RTS', 
					'CURRENT' 	=> 	'CURRENT',
					'CURWUPD' 	=>	'CURRENT WITH UPDATES',
					'EDIT'		=>	'FOR EDITING',
					'APPROVAL'	=>	'FOR APPROVAL',
					'EXPIRED'	=>	'EXPIRED'
               ));  
			   
		 $this->addElement($status);		   
		 $decision = new Zend_Form_Element_Select('decision');
		 $decision->removeDecorator('label')
		          ->removeDecorator('HtmlTag')
		          ->addFilter('StringTrim')
		          ->addMultiOption('' ,'')
		          ->addMultiOption('CO - OR' ,'Outright Reject')
		          ->addMultiOption('CO - NA' ,'No Action')
 		          ->addMultiOption('CO - Cancel' ,'Cancel')
		   	 	  ->addMultiOption('Approved' ,'Approved')
		 		  ->addMultiOption('Rejected','Rejected')
		 		  ->setAttrib('style','width: 120px')
	    		  ->setValue('0');
		$this->addElement($decision);			
	
		$businessCenter = new Zend_Form_Element_Select('businessCenter');
		$businessCenter->removeDecorator('label')
		     	 	   ->removeDecorator('HtmlTag')
					   ->addMultiOptions(array(
					        '' => '',
							'Metro Manila' => 'Metro Manila',
							'Bacolod' => 'Bacolod', 
							'Cebu' => 'Cebu'
						));  
		$this->addElement($businessCenter);
		
		$regularPromo = new Zend_Form_Element_Select('regularPromo');
		$regularPromo->removeDecorator('label')
		     	 	 ->removeDecorator('HtmlTag')
	 				 ->addMultiOptions(array(
					        '' => '',
							'Regular' => 'Regular',
							'Promo1' => 'Promo1' 
					));  
		$this->addElement($regularPromo);		
		
		$retrieve = new Zend_Form_Element_Submit('retrieve');
		$retrieve->setAttrib('id','submitbutton')
			     ->removeDecorator('DtDdWrapper');
		$this->addElement($retrieve);		

	}
}
?>