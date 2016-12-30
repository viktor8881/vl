<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Validate_Abstract
 *
 * @author Viktor
 */
abstract class Core_Validate_Abstract extends Zend_Validate_Abstract {
    
    
    public function getFirstMessage() {
        $messages = $this->getMessages();
        if (count($messages)) {
            return current($messages);
        }
        return '';
    }
    
}
