<?php

class Zend_Controller_Action_Helper_GetHighest extends
                Zend_Controller_Action_Helper_Abstract
{
    function direct($capno)
    {
	
		return getHighest($capno);
		

	}
}


