<?
class Form_Craw extends Zend_Dojo_Form
{
    public function __construct($options = null)
    {
	parent::__construct($options);
	// OLD Design
    $loan_purpose = new Zend_Form_Element_Text('loan_purpose');
	$loan_purpose->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($loan_purpose);
	
	$solicitation= new Zend_Form_Element_Text('solicitation');
	$solicitation->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($solicitation);
	
	$dealer_agent= new Zend_Form_Element_Text('dealer_agent');
	$dealer_agent->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($dealer_agent);
	
	$branch_referror= new Zend_Form_Element_Text('branch_referror');
	$branch_referror->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($branch_referror);
	
	$effective_yield= new Zend_Form_Element_Text('effective_yield');
	$effective_yield->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnlyHyphen(event)')
				 ->setAttrib('size','25');
	$this->addElement($effective_yield);
	
	$balloon_amount= new Zend_Form_Element_Text('balloon_amount');
	$balloon_amount->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnlyHyphen(event)')
				 ->setAttrib('size','25');
	$this->addElement($balloon_amount);
	
	$loan_remarks= new Zend_Form_Element_Text('loan_remarks');
	$loan_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','80');
	$this->addElement($loan_remarks);
	
	$applied_to_loanVal= new Zend_Form_Element_Text('applied_to_loanVal');
	$applied_to_loanVal->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnlyHyphen(event)')
				 ->setAttrib('size','25');
	$this->addElement($applied_to_loanVal);
	
	$loan_to_ValCap= new Zend_Form_Element_Text('loan_to_ValCap');
	$loan_to_ValCap->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnlyHyphen(event)')
				 ->setAttrib('size','25');
	$this->addElement($loan_to_ValCap);
	
	$financing_scheme= new Zend_Form_Element_Text('financing_scheme');
	$financing_scheme->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($financing_scheme);
	
	$source_info= new Zend_Form_Element_Text('source_info');
	$source_info->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','25');
	$this->addElement($source_info);
	
	$collateral_remarks= new Zend_Form_Element_Text('collateral_remarks');
	$collateral_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','80');
	$this->addElement($collateral_remarks);
	
	$dealer_incentive= new Zend_Form_Element_Text('dealer_incentive');
	$dealer_incentive->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				  ->setAttrib('onKeypress','return numOnlyHyphen(event)')
				 ->setAttrib('size','25');
	$this->addElement($dealer_incentive);
	
	$total_trust= new Zend_Form_Element_Text('total_trust');
	$total_trust->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnlyHyphen(event)')
				 ->setAttrib('size','25');
	$this->addElement($total_trust);
	
	$total_subsidiaries= new Zend_Form_Element_Text('total_subsidiaries');
	$total_subsidiaries->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnlyHyphen(event)')
				 ->setAttrib('size','25');
	$this->addElement($total_subsidiaries);
	
	$total_related_accounts= new Zend_Form_Element_Text('total_related_accounts');
	$total_related_accounts->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnlyHyphen(event)')
				 ->setAttrib('size','25');
	$this->addElement($total_related_accounts);
	
	$total_assets= new Zend_Form_Element_Text('total_assets');
	$total_assets->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnlyHyphen(event)')
				 ->setAttrib('size','25');
	$this->addElement($total_assets);
	
	$total_liabilities= new Zend_Form_Element_Text('total_liabilities');
	$total_liabilities->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnlyHyphen(event)')
				 ->setAttrib('size','25');
	$this->addElement($total_liabilities);
	
	$total_networth= new Zend_Form_Element_Text('total_networth');
	$total_networth->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnlyHyphen(event)')
				 ->setAttrib('size','25');
	$this->addElement($total_networth);
	
	$busfinancial_remarks= new Zend_Form_Element_Text('busfinancial_remarks');
	$busfinancial_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('size','80');
	$this->addElement($busfinancial_remarks);
	
	$total_gross_sales= new Zend_Form_Element_Text('total_gross_sales');
	$total_gross_sales->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnlyHyphen(event)')
				 ->setAttrib('size','25');
	$this->addElement($total_gross_sales);
	
	$total_netbefore_tax= new Zend_Form_Element_Text('total_netbefore_tax');
	$total_netbefore_tax->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				  ->setAttrib('onKeypress','return numOnlyHyphen(event)')
				 ->setAttrib('size','25');
	$this->addElement($total_netbefore_tax);
	
	$total_netafter_tax= new Zend_Form_Element_Text('total_netafter_tax');
	$total_netafter_tax->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('onKeypress','return numOnlyHyphen(event)')
				 ->setAttrib('size','25');
	$this->addElement($total_netafter_tax);
	
	$recommendation_remarks= new Zend_Form_Element_TextArea('recommendation_remarks');
	$recommendation_remarks->removeDecorator('label')
				->removeDecorator('HtmlTag')
				 ->addFilter('StringTrim')
				 ->addFilter('StripTags')
				 ->addFilter('StringToUpper')
				 ->setAttrib('rows','4');
	$this->addElement($recommendation_remarks);
	}
}


?>
