<?php
class Zend_View_Helper_BaseUrl
{
    function baseUrl()
    {
        $fc = Zend_Controller_Front::getInstance();
		//echo $fc->getRequest()->getRequestUri();
        return $fc->getBaseUrl();
    }
}