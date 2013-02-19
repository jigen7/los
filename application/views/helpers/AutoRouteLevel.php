<?php
class Zend_View_Helper_AutoRouteLevel extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}

	function autoRouteLevel($capno,$score){
		// Use in CO Inbox Pending to know within his range will diplay * beside the name
		$gethigh = Zend_Controller_Action_HelperBroker::getStaticHelper('GetHighest');
		$highcap = $gethigh->direct($capno);
		
		$table = new Model_BorrowerAccount();
		$select = $table->select();
		$select->where('capno like ?',$capno);
		$detail = $table->fetchRow($select);
		
		if($detail->routetag){
			
		$routetag = $detail->routetag; 	
		}else {
		//$scoretag = $this->_view->viewScore($score,$highcap,'view');

		}
		//$user = Zend_Auth::getInstance()->getIdentity();
	   //$userRole = '-'.$user->role_type;
		
		
		
		if(strpos($routetag,'-CO') !== false){
		return "<b>CO</b>";	
		}
		else if(strpos($routetag,'-CSH') !== false){
		return "<b>CSH</b>";	
		}
		else if(strpos($routetag,'-CMGH') !== false){
		return "<b>CMGH</b>";	
		}
		else if(strpos($routetag,'-PRES') !== false){
		return "<b>PRES</b>";	
		}
		else if(strpos($routetag,'-CRECOM') !== false){
		return "<b>CRECOM</b>";	
		}
		else if(strpos($routetag,'-SUBCRECOM') !== false){
		return "<b>SUBCRECOM</b>";	
		}
		else if(strpos($routetag,'-EXE-BOD') !== false){
		return "<b>BOD</b>";	
		}						
		
	}
	
}

?>