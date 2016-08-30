<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AnalysisCurrency_Model_Abstract
 *
 * @author Viktor
 */
abstract class AnalysisCurrency_Model_Abstract extends Core_Domen_Model_Abstract {
    
    const TYPE_PERCENT = 1;
    const TYPE_OVER_TIME = 2;
    
    private $id;
    private $currencyCode;
    private $created;
    
    private $_currency;


    protected $_aliases = array('currency_code'=>'currencyCode');

    
    
    public function __construct(array $options = null) {
        $this->setCreated(new Core_Date());
        parent::__construct($options);
    }
    
    public function getOptions() {
        return array('id'=>$this->getId(),
            'type'=>$this->getType(),
            'currency_code'=>$this->getCurrencyCode(),
            'body'=>$this->getBody(),
            'created'=>$this->getCreatedToDb());
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
    
    public function getCurrencyCode() {
        return $this->currencyCode;
    }
    
    public function getCurrency() {
        if (is_null($this->_currency)) {
            $this->_currency = $this->getManager('currency')->getByCode($this->getCurrencyCode());
        }
        return $this->_currency;
    }

    public function getCreated() {
        return $this->created;
    }
    
    public function getCreatedToDb() {
        return $this->getCreated()->format(Core_Date::DB_DATE);
    }
    
    public function getCreatedFormatDMY() {
        return $this->getCreated()->format(Core_Date::DMY);
    }

    public function setCurrencyCode($code) {
        $this->currencyCode = $code;
        return $this;
    }

    public function setCreated($created) {
        if ($created instanceof DateTime ) {
            $this->created = $created;
        }else{
            $this->created = new Core_Date($created);
        }
        return $this;
    }

        
    abstract public function getType();
    
    abstract public function getBody();
    
    abstract public function setBody($body);

    abstract public function isQuotesGrowth();
    
    abstract public function isQuotesFall();

}
