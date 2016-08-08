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
    
    protected static $_queue=null;
    protected static $_managers=array();
    protected static $_servises=array();
    
    
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
                // попытка загрузить сервис по шаблону
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
    
    /**
     * create for template
     * @param type $valueName
     * @return \managerName
     */
//    private static function managerTemplate($valueName)
//    {
//        $valueName = ucfirst($valueName);
//        $managerName    = $valueName.'_Manager';
//        $repositoryName = $valueName.'_Repository';
//        $mapperName     = $valueName.'_Mapper';
//        $factoryName    = $valueName.'_Factory';
//        $collectionName = $valueName.'_Collection';
//        return new $managerName(new $repositoryName(new $mapperName(), new $factoryName(), new $collectionName()));
//    }
    
    
    public static function getQueue() {
        if (is_null(self::$_queue)) {
            self::$_queue = new Core_Queue();
        }
        return self::$_queue;
    }
    
}

