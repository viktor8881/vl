<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{


    protected function _initViewHelpers()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
	$view->headTitle()->setSeparator(' :: ')
			->append('TITLE OF SITE');
        $view->setHelperPath('Core/Helpers', 'Core_Helper');
        $view->addHelperPath(APPLICATION_PATH.'/views/helpers', 'View_Helper');
    }
    
    protected function _initViewActionHelpers()
    {
        Zend_Controller_Action_HelperBroker::addPath('Core/Controller/Action/Helper/', 'Core_Controller_Action_Helper');
    }
    
    
    protected function _initDbConnect()
    {
        $this->bootstrap('db');
        $db = $this->getResource('db');
        return $db;
    }
    
    
    protected function _initResources()
    {
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
            'basePath'        => APPLICATION_PATH.'/models',
            'namespace'   => ''
            ));
        $dir = APPLICATION_PATH.'/models';        
        if ($dh = opendir($dir)) {
           while (($file = readdir($dh)) !== false) {
               if ($file=='.' or $file=='..' or $file[0]=='_' or !is_dir($dir.'/'.$file)) {
                   continue;
               }
               $type = mb_strtolower($file);
               $resourceLoader->addResourceType($type, $file, $file);
           }
           closedir($dh);
        }
        // added service 
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
             'basePath'        => APPLICATION_PATH.'/services',
             'namespace'   => ''
             ));
        $resourceLoader->addResourceType('service', '', 'Service');
    }
    
    
    protected function _initLog()
    {
        $patch = $this->getOption('path');
        $logger = new Core_Log(new Zend_Log_Writer_Stream($patch['log']['system'].'error.log'));        
        return $logger;
    }
    
    
    protected function _initMail()
    {
        $options = $this->getOptions();
        $tr = new Zend_Mail_Transport_Smtp( $options['smtp']['host'], $options['smtp'] );
        Zend_Mail::setDefaultTransport($tr);
        Core_Mail::setAdminEmail($options['mail']['adminEmail']);
        Core_Mail::setSiteEmail($options['mail']['siteEmail']);
    }
    
    
    protected function _initZFDebug()
    {
         return ;
//        if (APPLICATION_ENV == 'production'){ return ; }
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('ZFDebug');

        $this->bootstrap('db');
        $db = $this->getResource('db');

        $options = array(
        'plugins' => array(
          'Variables',
          'Database' => array('adapter' => $db),
          'Memory',
          'Time',
          'Registry',
          //'Cache' => array('backend' => $cache->getBackend()),
          'Exception',
            ),
        );
        $debug = new ZFDebug_Controller_Plugin_Debug($options);
        $this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');
        $frontController->registerPlugin($debug);
        $frontController->throwExceptions(true);
    }
    
    
}

