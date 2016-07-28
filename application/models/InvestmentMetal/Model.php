<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InvestmentMetal_model
 *
 * @author Viktor
 */
class InvestmentMetal_Model extends Core_Domen_Model_Abstract {
    
    const TYPE_SELL = 0;    // продажа
    const TYPE_BUY = 1;     // покупка
    
    private $id;
    private $type;
    private $count;
    private $metalCode;
    private $course;
    private $date;
    
    private $_metal;


    protected $_aliases = array('metal_code'=>'metalCode');

    
    public function getOptions() {
        return array('id'=>$this->getId(),
            'type'=>$this->getType(),
            'count'=>$this->getCount(),
            'metal_code'=>$this->getMetalCode(),
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

    public function getMetalCode() {
        return $this->metalCode;
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
    
    public function setMetalCode($metalCode) {
        $this->metalCode = $metalCode;
        return $this;
    }
    
    public function getMetal() {
        if (is_null($this->_metal)) {
            $this->_metal = $this->getManager('metal')->getByCode($this->getMetalCode());
        }
        return $this->_metal;
    }

    public function getMetalName() {
        $metal = $this->getMetal();
        if ($metal) {
            return $metal->getName();
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
