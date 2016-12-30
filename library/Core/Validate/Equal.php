<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Validate_Equal
 *
 * @author Viktor Ivanov
 */
class Core_Validate_Equal extends Core_Validate_Abstract {

    const NOT_EQUAL = 'notEqual';
    
    protected $_messageTemplates = array(        
        self::NOT_EQUAL => "Значение изменилось. Повторите попытку.",
    );
    
    protected $_value=null;
    
    public function __construct($value) {
        $this->setValue($value);
    }

    
    public function setValue($value)
    {   
        $this->_value = $value;
        return $this;
    }

    
    public function isValid($value) 
    {
        die('asdasd222');
        var_dump($value);
        var_dump($this->value); exit;
        if ($value != $this->_value) {
            $this->_error(self::NOT_EQUAL);
            return false;
        }
        return true;
    }
}

