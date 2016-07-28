<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InvestmentCurrency_model
 *
 * @author Viktor
 */
class InvestmentCurrency_Model extends Core_Domen_Model_Abstract {
    
    const TYPE_SELL = 0;    // продажа
    const TYPE_BUY = 1;     // покупка
    
    private $id;
    private $type;
    private $count;
    private $currencyCode;
    private $course;
    private $date;
    
    private $_currency;
    
    protected $_aliases = array('currency_code'=>'currencyCode');

    
    public function getOptions() {
        return array('id'=>$this->getId(),
            'type'=>$this->getType(),
            'count'=>$this->getCount(),
            'currency_code'=>$this->getCurrencyCode(),
            'course'=>$this->getCourse(),
            'date'=>$this->getDateToDb());
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function isBuy() {
        return $this->getType() == self::TYPE_BUY;
    }    
    
    public function isSell() {
        return $this->getType() == self::TYPE_SELL;
    }
    
    public function getSum() {
        return $this->getCount()*$this->getCourse();
    }

    public function getCount() {
        return $this->count;
    }

    public function getCurrencyCode() {
        return $this->currencyCode;
    }
    
    public function getCourse() {
        return $this->course;
    }
    
    public function getDate() {
        return $this->date;
    }
    
    public function getDateToDb() {
        return $this->getDate()->format(Core_Date::DB_DATE);
    }
    
    public function getDateFormatDMY() {
        return $this->getDate()->format(Core_Date::DMY);
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function setCount($count) {
        $this->count = $count;
        return $this;
    }
    
    public function setCurrencyCode($currencyCode) {
        $this->currencyCode = $currencyCode;
        return $this;
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
    
    public function setCourse($course) {
        $this->course = $course;
        return $this;
    }

    public function setDate($date) {
        if ($date instanceof DateTime ) {
            $this->date = $date;
        }else{
            $this->date = new Core_Date($date);
        }
        return $this;
    }



}
