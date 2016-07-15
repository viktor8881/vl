<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Viktor Ivanov
 */
abstract class Core_Domen_Filter_Abstract implements Core_Domen_IFilter {        
    
    private $_separate = ',';
    
    protected $_value=array();
    
     public function __construct($values=false, $separate=null) 
    {
        if ($separate) {
            $this->_setSeparate($separate);
        }
        $this->add($values);
     }
     
     /**
      * 
      * @return array
      */
     public function getValue()
     {
         return $this->_value;
     }
     
     /**
      * получить первое значение
      * @return null
      */
     public function getFirstValue()
     {
         if (count($this->_value)) {
             return current($this->_value);
         }
         return null;
     }
     

     public function add($values) 
     {
         if (false===$values){ return $this; }
         if (!is_array($values)) {
			 if (is_object($values)) {
                 $values = array($values);
             }elseif (is_null($values)) {
                 $values = array(null);
             }else{
                $values = explode($this->_separate, $values);
             }
         }
         if (count($values)) {
             foreach ($values as $value) {                 
                 $value = $this->filter($value);
                 if (false===$value) {
                     throw new RuntimeException('Parameter invalid for filter '.  get_class($this));
                 }
                 if (!in_array($value, $this->_value)) {
                     $this->_value[] = $value;
                 }
             }
         }
         return $this;
     }
     
     private function _setSeparate($separate) {
         $this->_separate = $separate;
         return $this;
     }
     
     /**
      * очистить значения фильтра
      * @return \Core_Domen_Filter_Abstract
      */
     public function clear()
     {
         $this->_value=array();
         return $this;
     }
     
     abstract public function filter($value);

}

