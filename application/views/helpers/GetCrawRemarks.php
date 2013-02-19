<?php
class Zend_View_Helper_GetCrawRemarks extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function getCrawRemarks($capno,$part){

	
		$cv = new Model_BorrowerCv();
		$ci = new Model_BorrowerCi();

		$select = $ci->select();
		$select->where('capno like ?',$capno);
		$ciDetail= $ci->fetchRow($select);
		
		$select = $cv->select();
		$select->where('capno like ?',$capno);
		$cvDetail= $cv->fetchRow($select);
		
		if($part == 'first'){
		return $ciDetail->remarks_residence_ci.' '.
		   $ciDetail->remarks_backgrd_ci.' '.
		   $cvDetail->remarks_backgrd.' '.	
		   $cvDetail->remarks_nfis.' '.
		   $cvDetail->remarks_cmap.'; ';
		}
		else if($part == 'second'){
		return $ciDetail->remarks_empver_ci.' '.
		   $ciDetail->remarks_busver_ci.' '.
		   $ciDetail->remarks_trdchk_ci.' '.	
		   $ciDetail->remarks_income_ci.' '.
		   $cvDetail->remarks_empver.'; '.
		   $cvDetail->remarks_busver.' '.
		   $cvDetail->remarks_trdchk.' '.	
		   $cvDetail->remarks_income.' ';	
		}
	
	}
}
	

?>