<?php
class Zend_View_Helper_AutoRouteCmgh extends Zend_Controller_Action_Helper_Abstract
{
	protected $_view;
	
	function setView($view){
		$this->_view = $view;
		}

	
	function autoRouteCmgh($routetag){
		$user = Zend_Auth::getInstance()->getIdentity();
	    $userRole = $user->role_type;
		if($userRole == 'CMGH'){		
		return 'ENCMGH-PRES';
		}else{
			return $routetag;
		}
	}
	
}

?>