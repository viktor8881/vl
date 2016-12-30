<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Validate_Money
 *  валидот денежных величин
 * @author Viktor Ivanov
 */
class Core_Validate_Money extends Core_Validate_Abstract {
    
    
    private $_zero = null;


    public function __construct($zero = false) {
        $this->_zero = $zero;
    }

    public function isValid($value) 
    {
        $validate = new Core_Validate_FloatPositive($this->_zero);
        $validate->setMessage('Значение должно быть числом', Core_Validate_FloatPositive::NOT_FLOAT);
        if (!$validate->isValid($value)) {
            list($key, $message) = each($validate->getMessages());
            $this->_messageTemplates[$key] = $message;
            $this->_error($key);
            return false;
        } 
        
        $validate = new Core_Validate_CountNumFraction(array('countNum'=>Core_Container::getManager('setting')->getRoundMoney()));
        if (!$validate->isValid($value)) {
            list($key, $message) = each($validate->getMessages());
            $this->_messageTemplates[$key] = $message;
            $this->_error($key);
            return false;
        }
        return true;
    }
    
}

