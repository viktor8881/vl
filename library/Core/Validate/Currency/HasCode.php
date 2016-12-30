<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Validate_Currency_HasCode
 *
 * @author Viktor
 */
class Core_Validate_Currency_HasCode extends Core_Validate_Abstract {

    const NOT_FOUND = 'notFound';
    
    protected $_messageTemplates = array(        
        self::NOT_FOUND => "Валюты с кодом %value% нет в системе.",
    );
    
    private $manager;
    
    public function __construct(Currency_Manager $manager) {
        $this->manager = $manager;
    }

    public function isValid($value) {
        $currency = $this->manager->getByCode($value);
//        var_dump($currency); exit;
        if (!$currency) {
            $this->_error(self::NOT_FOUND);
            return false;
        }
        return true;
    }

}
