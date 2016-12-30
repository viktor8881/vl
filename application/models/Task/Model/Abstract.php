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
    
    const MODE_ONLY_UP  = 1;
    const MODE_ONLY_DOWN= 2;
    const MODE_UP_DOWN  = 3;
    
    private $id;
    private $mode;
    private $_currencies;
    private $_metals;

    
    static public function listTypeCustom() {
        return array(self::TYPE_PERCENT, self::TYPE_OVER_TIME);
    }

    public function getOptions() {
        return array('id'=>$this->getId(),
            'mode'=>$this->getMode(),
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
        if (is_null($this->_metals)){
            $listCode = $this->getMetalsCode();
            if (is_null($this->_metals) && count($listCode)) {
                $this->_metals = $this->getManager('metal')->fetchAllByCodes($listCode);
            }else{
                $this->_metals = $this->getManager('metal')->createCollection();
            }
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
        if (is_null($this->_currencies)){
            $listCode = $this->getCurrenciesCode();
            if (count($listCode)) {
                $this->_currencies = $this->getManager('currency')->fetchAllByCodes($listCode);
            }else{
                $this->_currencies = $this->getManager('currency')->createCollection();
            }
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
    
    public function getMode() {
        return $this->mode;
    }

    public function setMode($mode) {
        $this->mode = $mode;
        return $this;
    }

    public function isModeOnlyUp() {
        return $this->getMode() == self::MODE_ONLY_UP;
    }
    
    public function isModeOnlyDown() {
        return $this->getMode() == self::MODE_ONLY_DOWN;
    }
    
    public function isModeUpDown() {
        return $this->getMode() == self::MODE_UP_DOWN;
    }

    

    abstract public function getType();
    
    abstract public function getBody();
    
    abstract public function setBody($body);
    
    abstract public function getCurrenciesCode();

    abstract public function getMetalsCode();

}
