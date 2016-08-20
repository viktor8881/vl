<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Task_Model_Abstract
 *
 * @author Viktor
 */
abstract class Task_Model_Abstract extends Core_Domen_Model_Abstract {
    
    const TYPE_PERCENT = 1;
    const TYPE_OVER_TIME = 2;
    
    private $id;
    private $_currencies;
    private $_metals;

    
    static public function listTypeCustom() {
        return array(self::TYPE_PERCENT, self::TYPE_OVER_TIME);
    }

    public function getOptions() {
        return array('id'=>$this->getId(),
            'type'=>$this->getType(),
            'body'=>$this->getBody());
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function isPercent() {
        return $this->getType()==self::TYPE_PERCENT;
    }
    
    public function isOvertime() {
        return $this->getType()==self::TYPE_OVER_TIME;
    }
        
    public function getMetals() {
        $listCode = $this->getMetalsCode();
        if (is_null($this->_metals) && count($listCode)) {
            $this->_metals = $this->getManager('metal')->fetchAllByCodes($listCode);
        }
        return $this->_metals;
    }
    
    public function listNameMetals() {
        $metals = $this->getMetals();
        return $metals?$metals->listName():array();
    }
    
    public function countMetals() {
        $metals = $this->getMetals();
        return $metals?$metals->count():0;
    }

    public function getCurrencies() {
        $listCode = $this->getCurrenciesCode();
        if (is_null($this->_currencies) && count($listCode)) {
            $this->_currencies = $this->getManager('currency')->fetchAllByCodes($listCode);
        }
        return $this->_currencies;
    }
        
    public function listNameCurrencies() {
        $currencies = $this->getCurrencies();
        return $currencies?$currencies->listName():array();
    }

    public function countCurrencies() {
        $currencies = $this->getCurrencies();
        return $currencies?$currencies->count():0;
    }
    
    
    abstract public function getType();
    
    abstract public function getBody();
    
    abstract public function setBody($body);
    
    abstract public function getCurrenciesCode();

    abstract public function getMetalsCode();

}
