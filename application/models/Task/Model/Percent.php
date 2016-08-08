<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Task_Model_Percent
 *
 * @author Viktor
 */
class Task_Model_Percent extends Task_Model_Abstract {
    
    private $percent;
    private $period;
    private $currenciesCode=array();
    private $metalsCode=array();
    
    private $_currencies;
    private $_metals;
    

           
    public function getPercent() {
        return $this->percent;
    }

    public function getPeriod() {
        return $this->period;
    }

    public function getCurrenciesCode() {
        return $this->currenciesCode;
    }
    
    public function getCurrencies() {
        $listCode = $this->getCurrenciesCode();
        if (is_null($this->_currencies) && count($listCode)) {
            $this->_currencies = $this->getManager('currency')->fetchAllByCodes($listCode);
        }
        return $this->_currencies;
    }

    public function getMetalsCode() {
        return $this->metalsCode;
    }
    
    public function getMetals() {
        $listCode = $this->getMetalsCode();
        if (is_null($this->_metals) && count($listCode)) {
            $this->_metals = $this->getManager('metal')->fetchAllByCodes($listCode);
        }
        return $this->_metals;
    }

    public function setPercent($percent) {
        $this->percent = $percent;
        return $this;
    }

    public function setPeriod($period) {
        $this->period = $period;
        return $this;
    }

    public function setCurrenciesCode(array $currencies) {
        $this->currenciesCode = $currencies;
        return $this;
    }

    public function setMetalsCode(array $metals) {
        $this->metalsCode = $metals;
        return $this;
    }
    
    public function countCurrencies() {
        return count($this->currenciesCode);
    }
    
    public function countMetals() {
        return count($this->metalsCode);
    }

    // == abstract methods =
    
    public function getType() {
        return Task_Model_Abstract::TYPE_PERCENT;
    }
    
    public function getBody() {
        $body = array('percent'=>$this->getPercent(),
            'period'=>$this->getPeriod(),
            'currenciesCode'=>$this->getCurrenciesCode(),
            'metalsCode'=>$this->getMetalsCode());
        return json_encode($body);
    }

    public function setBody($body) {
        $options = json_decode($body, true);
        if (is_null($options)) {
            throw new RuntimeException('Body task can not be empty');
        }
        return $this->setOptions($options);
    }

}
