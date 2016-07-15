<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModelAbstract
 *
 * @author Viktor Ivanov
 */
abstract class Core_Domen_Model_Abstract implements Core_Domen_IModel {
    
    
    protected $_aliases = array();
    
    
    public function __construct(array $options = null) {        
        if ($options){
            $this->setOptions($options);
        }
    }
    
    
    public function setOptions(array $options = array())
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = $this->_getMethodName($key);
            if (in_array($method, $methods) ) {
                $this->$method($value);
            }
        }        
        return $this;
    }
    
    /**
     * получение сервиса
     * @param string $managerName
     * @return Core_Domen_Manager_Abstract
     */
    public function getManager($managerName)
    {
        return Core_Container::getManager($managerName);
    }
    
    
    public function toArray()
    {
        return $this->getOptions();
    }
    
    
    /**
     * получить параметры в виде массива
     * @return array
     */
    abstract public function getOptions();
    
    /**
     * получение имени метода
     * @param type $key
     * @return type
     */
    protected function _getMethodName($key)
    {
        $key = (isset($this->_aliases[$key]))?$this->_aliases[$key]:$key;
        return 'set' . ucfirst($key);
    }
    
}

