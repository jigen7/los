<?php
class Zend_View_Helper_ViewDevWithin2 extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	// Paolo Marco Manarang Rendition of DevWithin
	function setView($view){
		$this->_view = $view;
		}
		
	function viewDevWithin2($column,$capno){
	// Check Deviation within BorrowerAccount 
	$table = new Model_BorrowerAccount();
	$select = $table->select();
	$select->where('capno like ?',$capno);
	$row = $table->fetchRow($select);
	
	$loanyr = $accntdetail->loanterm / 12;
	//Query Main Profile
	$select2 = $table->select();
	$select2->where('capno like ?',capnosep($capno).'_'.capnorecon($capno));
	$select2->where('relation like ?','Principal');
	$main = $table->fetchRow($select2);
	
	if($column == 'citizenship'){
		
		if($row->citizenship != 1){
			return "<b>NO</b>";
		}
		else {
			return "YES";
		}
	} // End of Citizenship Check
	
	if($column == 'age'){
		
		if($row->age  < 21 || (($row->age + ($main->loanterm/12)) > 65)){
			return "<b>NO</b>";
		}
		else if($row->age){
			return "YES";
		}
	} // End of Age Check
	
	if($column == 'residence'){
		$yr = $row->residence_yrs + ($row->residence_months / 12);
		if($yr < 2){
			return "<b>NO</b>";
		}
		else {
			return "YES";
		}
	} // End of Residence Check
	
	if($column == 'empyrs'){
	$yr = $this->_view->viewTotalEmpYrs($capno);
		if($yr < 2){
			return "<b>NO</b>";
		}
		else {
			return "YES";
		}
	} // End of Employment Years
	
	if($column == 'empstatus'){
		$table = Model_BorrowerEmployment();
		$select = $table->select();
		$select->where("id = ?",$capno);
		$emp = $table->fetchRow($select);
	
		if($this->_view->viewEmpStatus($emp->emp_status != "Permanent")){
			return "<b>NO</b>";
		}
		else {
			return "YES";
		}
	} // End of Employment Status
	

	
	
	


}
	
}
?>