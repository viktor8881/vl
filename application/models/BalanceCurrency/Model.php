<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BalanceCurrency_model
 *
 * @author Viktor
 */
class BalanceCurrency_Model extends Core_Domen_Model_Abstract {
    
    private $id;
    private $currencyCode;
    private $balance;
    
    private $_currency;
    
    protected $_aliases = array('currency_code'=>'currencyCode');

    
    public function getOptions() {
        return array('id'=>$this->getId(),
            'currency_code'=>$this->getCurrencyCode(),
            'balance'=>$this->getBalance());
    }
    
    public function getId() {
        return $this->id;
    }

    public function getCurrencyCode() {
        return $this->currencyCode;
    }
    
    public function getCurrency() {
        if (is_null($this->_currency)) {
            $this->_currency = $this->getManager('currency')->getByCode($this->getCurrencyCode());
        }
        return $this->_currency;
    }

    public function getCurrencyName() {
        $currency = $this->getCurrency();
        if ($currency) {
            return $currency->getName();
        }
        return '';
    }
    
    public function getBalance() {
        return $this->balance;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setCurrencyCode($currencyCode) {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    public function setBalance($balance) {
        $this->balance = $balance;
        return $this;
    }
    
    public function addBalance($balance) {
        $this->balance += $balance;
        return $this;
    }
    
    public function subBalance($balance) {
        $this->balance -= $balance;
        return $this;
    }

}
