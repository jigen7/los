<?php
class Zend_View_Helper_CsdRecommend extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}

	function csdRecommend($capno){
/** Use in marketing report to determine
 *  if the account is recommended by the Credit Services Head
 **/
	$history = new Model_AccountHistory();
	
	$string =  $history->ifRecommend($capno);
		
		if($string == 'Recommended by CSD'){
			return "Recommended";
		}else if($string == 'Not Recommended by CSD'){
			return "Not Recommended";
		}
	}
	
}

?>