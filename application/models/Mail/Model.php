<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mail_model
 *
 * @author Viktor
 */
class Mail_Model {
    
    private $day;
    private $percent;
    private $currencies=array();
    
    public function setDay($day) {
        $this->day = $day;
        return $this;
    }
    
    public function getDay() {
        return $this->day;
    }

    public function setPercent($percent) {
        $this->percent = $percent;
        return $this;
    }
    
    public function getPercent() {
        return $this->percent;
    }
        
    public function setCurrencies(array $currencies) {
        $this->currencies = $currencies;
        return $this;
    }
    
    public function getCurrencies() {
        return $this->currencies;
    }

    public function addCurrency(Mail_CurrencyModel $currency) {
        $this->currencies[] = $currency;
        return $this;
    }
    
    public function hasCurrencies() {
        return count($this->currencies)>0;
    }
    
}
