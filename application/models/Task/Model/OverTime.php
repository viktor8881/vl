<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Task_Model_OverTime
 *
 * @author Viktor
 */
class Task_Model_OverTime extends Task_Model_Abstract {
    
    private $period;
    private $currenciesCode=array();
    private $metalsCode=array();
    
    
    
    
    public function getPeriod() {
        return $this->period;
    }

    public function getCurrenciesCode() {
        return $this->currenciesCode;
    }
    
    public function getMetalsCode() {
        return $this->metalsCode;
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
        return Task_Model_Abstract::TYPE_OVER_TIME;
    }
    
    public function getBody() {
        $body = array('period'=>$this->getPeriod(),
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
