<?php

class Zend_Controller_Action_Helper_DefineTitleHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct($action, $place)
	{	
		if($action == "filters" || $action == "rules" || $action == "ocs"){
			if($place == 'addmodeldefinefields'){
				return "ADD";
			}else if($place == 'editmodeldefinefields'){
				return "EDIT";
			}else if($place == 'updatemodeldefinefields'){
				return "UPDATE";
			}
		}	
		if($action == 'weights'){
			if($place == 'addmodeldefinefields'){
				return "addmodeldefineweights";
			}else if($place == 'editmodeldefinefields'){
				return "editmodeldefineweights";
			}else if($place == 'updatemodeldefinefields'){
				return "updatemodeldefineweights";
			}		
		}	
			
    }

}

