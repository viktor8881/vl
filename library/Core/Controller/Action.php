<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControllerAction
 *
 * @author Viktor Ivanov
 */
class Core_Controller_Action extends Zend_Controller_Action 
{  
    
    public function init() {
        parent::init();
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
                'namespace' => '',
                'basePath'  => APPLICATION_PATH.'/modules/'.$this->getRequest()->getModuleName()));
    }
    
        
    /**
     * 
     * @param type $serviceName
     * @return Core_Layer_ServiceInterface
     */
    public function getManager($serviceName)
    {
        return Core_Container::getManager($serviceName);
    }
           
}
