<?php

class Zend_Controller_Action_Helper_SwitchSsl extends Zend_Controller_Action_Helper_Abstract
    {
        public function direct()
        {
	if (!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS']) {
                $request    = $this->getRequest();
                $url        = 'https://'
                            . $_SERVER['HTTP_HOST']
                            . $request->getRequestUri();
                $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                $redirector->gotoUrl($url);
		
            }
        }
    } 