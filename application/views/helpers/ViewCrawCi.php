<?php
class Zend_View_Helper_ViewCrawCI extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewCrawCI($type,$capno){
	//Helper to distinguish the CI and CV for the craw
	//Favorable CI Report and SAtisfactory Experience on all and otustanding 
	//loan CV and CI Remarks is reflected
			$cv = new Model_BorrowerCv();
			$select = $cv->select();
			$select->where('capno like ?',$capno);
			$cvDetail = $cv->fetchRow($select);
			
			$ci = new Model_BorrowerCi();
			$select = $ci->select();
			$select->where('capno like ?',$capno);
			$ciDetail = $ci->fetchRow($select);
			
	
	if($type == 'emp')
	{
	//&& ($cvDetail->empver2 == 'Favorable' || $cvDetail->empver2 == 'Unfavorable') &&
	//($ciDetail->empver_ci2 == 'Favorable' || $ciDetail->empver_ci2 == 'Unfavorable')
	//)
			//if($cvDetail->empver2 != $ciDetail->empver_ci2){
				return true;
			//}
		
	}
	
	else if($type == 'bus')
	/*
	 && 
	($cvDetail->busver2 == 'Favorable' || $cvDetail->busver2 == 'Unfavorable') &&
	($ciDetail->busver_ci2 == 'Favorable' || $ciDetail->busver_ci2 == 'Unfavorable')
	 )*/
	{
			//if($cvDetail->busver2 != $ciDetail->busver_ci2){
				return true;
			//}
		
	}
	
	else if($type == 'trade')
	/* && 
	($cvDetail->trdchk2 == 'Favorable' || $cvDetail->trdchk2 == 'Unfavorable') &&
	($ciDetail->trdchk_ci2 == 'Favorable' || $ciDetail->trdchk_ci2 == 'Unfavorable')
	 )*/
	{
			//if($cvDetail->trdchk2 != $ciDetail->trdchk_ci2){
				return true;
			//}
	}
	
	else if($type == 'background')
	/*
	 && 
	($cvDetail->backgrd == 'Favorable' || $cvDetail->backgrd == 'Unfavorable') &&
	($ciDetail->backgrd_ci2 == 'Favorable' || $ciDetail->backgrd_ci2 == 'Unfavorable')
	 ) */
	{
			//if($cvDetail->backgrd != $ciDetail->backgrd_ci2){
				return true;
			//}
		
		
	}
	
	else if($type == 'income')
	/* && 
	($cvDetail->income == 'Favorable' || $cvDetail->income == 'Unfavorable') &&
	($ciDetail->income_ci == 'Favorable' || $ciDetail->income_ci == 'Unfavorable')
	 )*/
	{
			//if($cvDetail->income != $ciDetail->income_ci){
				return true;
			//}
		
	}
	else {
		
	return false;	
	}
		
	}
}
	

?>