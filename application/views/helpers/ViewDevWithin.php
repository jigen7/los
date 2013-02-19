<?php
class Zend_View_Helper_ViewDevWithin extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	//Created By: Melody Balason
	// if the remarks is not null return YES 
	function setView($view){
		$this->_view = $view;
		}
		
	function viewDevWithin($column,$capno){

	$table = new Model_BorrowerDeviation();
	$select = $table->select();
	$select->where('capno like ?',$capno);
	$row = $table->fetchRow($select);
	
	if($column == 'citizenship1'){
		if($row->remarks_citizenship1){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	else if($column == 'citizenship2'){
		if($row->remarks_citizenship2){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	
	else if($column == 'spocitizenship'){
		if($row->remarks_spocitizenship){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	
	else if($column == 'gmi'){
		if($row->remarks_gmi){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	
	else if($column == 'borrower_age'){
		if($row->remarks_borrower_age){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	else if($column == 'coborrower_age'){
		if($row->remarks_coborrower_age){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	else if($column == 'spouse_age'){
		if($row->remarks_spouse_age){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	else if($column == 'residence_yrs'){
		if($row->remarks_residence_yrs){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	else if($column == 'totalcombine'){
		if($row->remarks_totalcombine){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	else if($column == 'coemployment_yrs'){
		if($row->remarks_coemployment_yrs){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	
	else if($column == 'loan_amount'){
		if($row->remarks_loan_amount){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	else if($column == 'employment_status'){
		if($row->remarks_employment_status){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	else if($column == 'employment_yrs'){
		if($row->remarks_employment_yrs){
		return "<B>"."NO"."</B>" ;
		}else { 
		return "YES";}
	}
	else if($column == 'downpayment'){
		if($row->remarks_downpayment){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	
	else if($column == 'veh_age'){
		if($row->remarks_veh_age){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	else if($column == 'coemployment_status'){
		if($row->remarks_coemployment_status){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	
	
	else if($column == 'business_yrs'){
		if($row->remarks_business_yrs){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	
	else if($column == 'spoemployment_yrs'){
		if($row->remarks_spoemployment_yrs){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	
	else if($column == 'spoemployment_status'){
		if($row->remarks_spoemployment_status){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	
	else if($column == 'spobusiness_yrs'){
		if($row->remarks_spobusiness_yrs){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	
	else if($column == 'cobusiness_yrs'){
		if($row->remarks_cobusiness_yrs){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	
	else if($column == 'sporesidence_yrs'){
		if($row->remarks_sporesidence_yrs){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	
	else if($column == 'coresidence_yrs'){
		if($row->remarks_coresidence_yrs){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	
	
	//nfis checking positive
	else if($column == 'nfis'){
		if($row->remarks_nfis){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	
	else if($column == 'spnfis'){
		if($row->remarks_spnfis){
		return "<B>"."NO"."</B>" ;
		}else {
		return "";}
	}
	
	else if($column == 'confis'){
		if($row->remarks_confis){
		return "<B>"."NO"."</B>" ;
		}else {
		return "";}
	}
	
	else if($column == 'nfis_check'){
		if($row->remarks_nfis_check){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	
	else if($column == 'spnfis_check'){
		if($row->remarks_spnfis_check){
		return "<B>"."NO"."</B>" ;
		}else {
		return "";}
	}
	
	else if($column == 'confis_check'){
		if($row->remarks_confis_check){
		return "<B>"."NO"."</B>" ;
		}else {
		return "";}
	}
	
	else if($column == 'sell_lcp'){
		if($row->remarks_sell_lcp){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	
	else if($column == 'ci_favorable'){
		if($row->remarks_ci_favorable){
		return "<B>"."NO"."</B>" ;
		}else {
		return "YES";}
	}
	
	else if($column == 'coci_favorable'){
		if($row->remarks_ci_favorable){
		return "<B>"."NO"."</B>" ;
		}else {
		return "";}
	}
	
	else if($column == 'spci_favorable'){
		if($row->remarks_ci_favorable){
		return "<B>"."NO"."</B>" ;
		}else {
		return "";}
	}
	
	//

}
	
}
?>