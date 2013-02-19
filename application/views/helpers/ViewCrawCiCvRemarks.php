<?php
class Zend_View_Helper_ViewCrawCiCvRemarks extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewCrawCiCvRemarks($type,$capno,$process){
	//returns the remarks of ci and cv base on the parameter
			$cv = new Model_BorrowerCv();
			$select = $cv->select();
			$select->where('capno like ?',$capno);
			$cvDetail = $cv->fetchRow($select);
			
			$ci = new Model_BorrowerCi();
			$select = $ci->select();
			$select->where('capno like ?',$capno);
			$ciDetail = $ci->fetchRow($select);
			
			if($process == 'cv'){
				
				switch($type){
					case 'emp':													
					return $cvDetail->empver2.' '.$cvDetail->remarks_empver; 							
					break;
					
					case 'bus':
					return $cvDetail->busver2.' '.$cvDetail->remarks_busver; 	
					break;
					
					case 'trade':
					return $cvDetail->trdchk2.' '.$cvDetail->remarks_trdchk;							
					break;
					
					case 'background':
					return $cvDetail->backgrd.' '.$cvDetail->remarks_backgrd;
					break;
					
					case 'income':
					return $cvDetail->income.' '.$cvDetail->remarks_income;	
					break;
				}
				
			}
	
			if($process == 'ci'){
							
							switch($type){
								case 'emp':
								return $ciDetail->empver_ci2.' '.$ciDetail->remarks_empver_ci;
								break;
								
								case 'bus':
								return $ciDetail->busver_ci2.' '.$ciDetail->remarks_busver_ci;
								break;
								
								case 'trade':
								return $ciDetail->trdchk_ci2.' '.$ciDetail->remarks_trdchk_ci;	
								break;
								
								case 'background':
								return $ciDetail->backgrd_ci.' '.$ciDetail->remarks_backgrd_ci;
								break;
								
								case 'income':
								return $ciDetail->income_ci.' '.$ciDetail->remarks_income_ci;
								break;		
								
								case 'appraisal':
								return $ciDetail->remarks_appraisal;
								break;							
								
							}
							
						}

	}
}
	

?>