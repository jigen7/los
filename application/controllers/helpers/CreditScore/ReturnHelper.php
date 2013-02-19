<?php

class Zend_Controller_Action_Helper_ReturnHelper extends Zend_Controller_Action_Helper_Abstract
{
    function direct($action, $place)
	{	
		if($action == "filters"){
			if($place == 'addmodeldefinefields'){
				return "addmodel";
			}else if($place == 'editmodeldefinefields'){
				return "editmodel";
			}else if($place == 'updatemodeldefinefields'){
				return "updatemodelspecific";
			}
			
		}
		
    }

}

