<?php
class Zend_View_Helper_ApprovalLevel extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}

	function approvalLevel($routetag,$process){
		
			if(strpos($routetag,'-CO') !== false){
			$string = "CO";	
			$counter = 1;
			}
			else if(strpos($routetag,'-CSH') !== false){
			$string = "CSH";	
			$counter = 2;
			}
			else if(strpos($routetag,'-CMGH') !== false){
			$string = "PRES";	
			$counter = 3;
			}
			else if(strpos($routetag,'-PRES') !== false){
			$string = "PRES";	
			$counter = 4;
			}
			else if(strpos($routetag,'-EXE-BOD') !== false){
			$string = "BOD";	
			$counter = 6;
			}	
			else if(strpos($routetag,'-CRECOM') !== false){
			$string = "CRECOM";	
			$counter = 5;
			}
			else if(strpos($routetag,'-SUBCRECOM') !== false){
			$string = "SUBCRECOM";	
			$counter = 5;
			}
					
		
		if($process == 'level'){
			return $string;
		}
		if($process =='counter' ){
			return $counter;
			
		}
		
	}
	
}

?>