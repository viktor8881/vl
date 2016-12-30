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
    
    public function setOptions(array $options = array()) {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = $this->_getMethodName($key);
            if (in_array($method, $methods) ) {
                $this->$method($value);
            }
        }        
        return $this;
    }
    
    public function toArray() {
        return $this->getOptions();
    }
            
    protected function _getMethodName($key) {
        $key = (isset($this->_aliases[$key]))?$this->_aliases[$key]:$key;
        return 'set' . ucfirst($key);
    }
            
    public function getManager($managerName) {
        return Core_Container::getManager($managerName);
    }
        
    
    
    abstract public function getOptions();
    abstract public function setId($id);
    abstract public function getId();
    
}

