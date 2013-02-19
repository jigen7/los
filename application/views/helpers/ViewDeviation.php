<?php
class Zend_View_Helper_ViewDeviation extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}
		
	function viewDeviation($capno){

		$accnt = new Model_BorrowerAccount();
		$select = $accnt->select();
		$select->where('capno like ?',$capno);
		$accntdetail = $accnt->fetchRow($select);

		if ($accntdetail->deviation == true){
			
			return "<b>WITH DEVIATION</b>";
			}
		}

	}
	

?>