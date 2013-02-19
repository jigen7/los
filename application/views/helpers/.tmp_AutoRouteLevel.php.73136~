<?php
class Zend_View_Helper_AutoRouteDecision extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}

	function autoRouteDecision($capno,$score){
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
		$scoretag = $detail->score_tag;
		$autoroute = Zend_Controller_Action_HelperBroker::getStaticHelper('AutoRoute');
		$routetag = $autoroute->direct($capno,$scoretag);
		}
		$user = Zend_Auth::getInstance()->getIdentity();
	    $userRole = '-'.$user->role_type;
		
		
		
		if(strpos($routetag,$userRole) === false){
		return "";	
		}else {
		return "*";
		}
	    
		
	}
	
}

?>