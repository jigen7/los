<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
 protected function _initAutoload()
    {
    //auto loader , load resources forms,models,plugins
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '', 
            'basePath'  => APPLICATION_PATH));
			
			$fc = Zend_Controller_Front::getInstance();
			$fc->registerPlugin(new Plugin_AccessCheck);
			
        return $moduleLoader;
    }
        public function _initDbRegistry()
    {
        $this->bootstrap('multidb');
        $multidb = $this->getPluginResource('multidb');
	
        Zend_Registry::set('db_local', $multidb->getDb('local'));
        Zend_Registry::set('db_edocs', $multidb->getDb('edocs'));
    }

 function _initViewHelpers()
    {
    // Layout Initialization
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
		$view = $layout->getView();
		
        $view->doctype('XHTML1_STRICT');
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
        $view->headTitle()->setSeparator(' - ');
        $view->headTitle('Loan Origination System');
   // End of Layout Initialization
   
   //Enable Dojo
   	Zend_Dojo::enableView($view); 
	$view->dojo()->setLocalPath('/js/dojo/dojo.js')
                 ->requireModule('dojo.data.ItemFileReadStore'); 
	$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
	$viewRenderer->setView($view);
   //End of Dojo 
   
       Zend_Controller_Action_HelperBroker::addPath(
        APPLICATION_PATH .'/controllers/helpers');
	
       Zend_Controller_Action_HelperBroker::addPath(
        APPLICATION_PATH .'/controllers/helpers/CreditScore');
		
       Zend_Controller_Action_HelperBroker::addPath(
        APPLICATION_PATH .'/controllers/helpers/Admin');
	
       Zend_Controller_Action_HelperBroker::addPath(
        APPLICATION_PATH .'/controllers/helpers/Edocs');	

       Zend_Controller_Action_HelperBroker::addPath(
        APPLICATION_PATH .'/controllers/helpers/AutoRouting');	
	
       Zend_Controller_Action_HelperBroker::addPath(
        APPLICATION_PATH .'/controllers/helpers/Report');
	
	/*
	$db = Zend_Db::factory('Pdo_PgSql', array(
	'host'        =>'localhost',
	'username'    => 'postgres',
	'password'    => '123456',
	'dbname'    => 'LoanSystemScore'
	));
	
	Zend_Db_Table_Abstract::setDefaultAdapter($db);
	$config = array(
	    'name'           => 'session',
	    'primary'        => 'session_id',
	    'modifiedColumn' => 'modified',
	    'dataColumn'     => 'data',
	    'lifetimeColumn' => 'lifetime'
	);
	Zend_Session::setSaveHandler(new Zend_Session_SaveHandler_DbTable($config));
	Zend_Session::start();
	*/
    }
/*
protected function _initZFDebug()
{
    $autoloader = Zend_Loader_Autoloader::getInstance();
    $autoloader->registerNamespace('ZFDebug');
    
    $options = array(
        'plugins' => array('Variables', 
                           'File' => array('base_path' => '/path/to/project'),
                           'Memory', 
                           'Time', 
                           'Registry', 
                           'Exception')
    );
    
    # Instantiate the database adapter and setup the plugin.
    # Alternatively just add the plugin like above and rely on the autodiscovery feature.
    if ($this->hasPluginResource('db')) {
        $this->bootstrap('db');
        $db = $this->getPluginResource('db')->getDbAdapter();
        $options['plugins']['Database']['adapter'] = $db;
    }

    # Setup the cache plugin
    if ($this->hasPluginResource('cache')) {
        $this->bootstrap('cache');
        $cache = $this-getPluginResource('cache')->getDbAdapter();
        $options['plugins']['Cache']['backend'] = $cache->getBackend();
    }

    $debug = new ZFDebug_Controller_Plugin_Debug($options);
    
    $this->bootstrap('frontController');
    $frontController = $this->getResource('frontController');
    $frontController->registerPlugin($debug);
}
*/

    
}

