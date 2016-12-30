<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Abstract_Service
 *
 * @author Viktor Ivanov
 */
class Core_Container {
    
    protected static $_queue    = [];
    protected static $_managers = [];
    protected static $_servises = [];
    
    
    /**
     * 
     * @param type $managerName
     * @return Core_Domen_Manager_Abstract
     */
    public static function getManager($managerName)
    {
        if (!isset(self::$_managers[$managerName])){
            $methodName = 'getManager'.ucfirst($managerName);
            if (method_exists('Core_Container', $methodName)){                
                self::$_managers[$managerName] = self::$methodName();
            }else{
                // загрузка сервиса по шаблону
                $valueName = ucfirst($managerName);
                $classManager    = $valueName.'_Manager';
                $classRepository = $valueName.'_Repository';
                $classMapper     = $valueName.'_Mapper';
                $classFactory    = $valueName.'_Factory';
                $classCollection = $valueName.'_Collection';
                self::$_managers[$managerName] = new $classManager(new $classRepository(new $classMapper(), new $classFactory(), new $classCollection()));
            }            
        }
        return self::$_managers[$managerName];
    }
    
    /**
     * 
     * @param type $serviceName
     * @return Service_Interface
     */
    public static function getService($serviceName)
    {
        if (!isset(self::$_servises[$serviceName])){
            $methodName = 'getManager'.ucfirst($serviceName);
            if (method_exists('Core_Container', $methodName)){                
                self::$_servises[$serviceName] = self::$methodName();
            }else{
                // попытка загрузить сервис по шаблону
                $classService = 'Service_'.ucfirst($serviceName);
                self::$_servises[$serviceName] = new $classService();
            }            
        }
        return self::$_servises[$serviceName];
    }
    
    
    public static function getQueue($name='analysis') {
        $classQueue = 'Core_Queue_'.ucfirst($name);
        if (!isset(self::$_queue[$classQueue])){
            self::$_queue[$classQueue] = new $classQueue($name);
        }
        return self::$_queue[$classQueue];
    }
    
}

