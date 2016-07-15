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
    
    protected static $_di=null;
    protected static $_managers=array();
    
    
    /**
     * получение сервиса по алиасу
     * @param string $managerName
     * @return Core_Domen_IManager
     * @throws RuntimeException
     */
    public static function getManager($managerName)
    {
        if (!isset(self::$_managers[$managerName])){
            $methodName = 'getManager'.ucfirst($managerName);
            if (method_exists('Core_Container', $methodName)){                
                self::$_managers[$managerName] = self::$methodName();
            }else{
                // попытка загрузить сервис по шаблону
                self::$_managers[$managerName] = self::managerTemplate($managerName);
            }            
        }
        return self::$_managers[$managerName];
    }
    
    /**
     * create to template
     * @param type $valueName
     * @return \managerName
     */
    public static function managerTemplate($valueName)
    {
        $valueName = ucfirst($valueName);
        $managerName    = $valueName.'_Manager';
        $repositoryName = $valueName.'_Repository';
        $mapperName     = $valueName.'_Mapper';
        $factoryName    = $valueName.'_Factory';
        $collectionName = $valueName.'_Collection';
        return new $managerName(new $repositoryName(new $mapperName(), new $factoryName(), new $collectionName()));
    }
    
}

