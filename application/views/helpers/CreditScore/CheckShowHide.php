<?php
class Zend_View_Helper_CreditScore_CheckShowHide extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function checkShowHide($namefield, $hiddens){
		$isShow = true;
		$fsmodel = new Model_Creditscore_Fieldsselected();
		$arrFields = explode(",",$hiddens);
		$count = count($arrFields) - 1;
		for($i=0; $i!=$count; $i++){
			if($arrFields[$i] == $namefield){
				$isShow = false;
			}
		}
		if($isShow) echo "<td><a href=\"javascript:show('".$namefield."')\">Show</a></td>";
		else echo "<td><a href=\"javascript:hide('".$namefield."')\">Hide</a></td>";
	}
	
}
?>