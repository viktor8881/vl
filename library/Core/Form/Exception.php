<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_Exception
 *
 * @author Viktor Ivanov
 */
class Core_Form_Exception extends Zend_Exception {
    
    
    public function __construct($el, $msg = '', $code = 0, \Exception $previous = null) {
        $el->addError($msg);
        parent::__construct($msg, $code, $previous);
    }
    
}

