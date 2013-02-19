<?php
class Zend_View_Helper_ChkCiDev extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function chkCiDev($capno){

			$cv = new Model_BorrowerCv();
			$select = $cv->select();
			$select->where('capno like ?',$capno);
			$cvdetail = $cv->fetchRow($select);
			$ci = new Model_BorrowerCi();
			$select = $ci->select();
			$select->where('capno like ?',$capno);
			$ciDetail = $ci->fetchRow($select);
			
			if($ciDetail->empver_ci2 == 'Unfavorable' || 
			$ciDetail->busver_ci2 == 'Unfavorable' || 
			$ciDetail->trdchk_ci2 == 'Unfavorable' ||
			$ciDetail->backgrd_ci2 == 'Unfavorable' ||
	   		$ciDetail->residence_ci2 == 'Unfavorable' || 
			$ciDetail->income_ci == 'Unfavorable' ||
			$ciDetail->ci_appraisal_report == 'Unfavorable' ||
			$cvdetail->empver2 == 'Unfavorable' ||
			$cvdetail->busver2 == 'Unfavorable' ||
			$cvdetail->trdchk2 == 'Unfavorable' ||
			$cvdetail->backgrd == 'Unfavorable' ||
			$cvdetail->bankref == 'Unfavorable' ||
			$cvdetail->creditchk == 'Unfavorable' ||
			$cvdetail->pastdealings == 'Unfavorable' ||
			$cvdetail->income == 'Unfavorable'
			){
				$dev_ci_favorable = "Unfavorable CI Report";
				$val_ci_favorable = 'Unfavorable';				
			}else {
				$val_ci_favorable = 'Favorable';			
			}					
			
			return $val_ci_favorable;
	}
}
	

?>