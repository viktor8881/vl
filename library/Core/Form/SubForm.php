<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_SubForm
 *
 * @author Viktor Ivanov
 */
class Core_Form_SubForm  extends Zend_Form_SubForm {
    
    protected $_checkValid=true;
    
    
    public function __construct($options = null) {
        parent::__construct($options);
        $this->removeDecorator('DtDdWrapper');
        $this->removeDecorator('HtmlTag');
    }
    
    public function setCheckValid($flag)
    {
        $this->_checkValid = (bool)$flag;
        return $this;
    }

    

    public function isValid($data) 
    {
        if (!$this->_checkValid) {
            return true;
        }
        return parent::isValid($data);
    }
    
}

