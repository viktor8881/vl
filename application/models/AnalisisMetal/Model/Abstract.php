<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AnalisisMetal_Model_Abstract
 *
 * @author Viktor
 */
abstract class AnalisisMetal_Model_Abstract extends Core_Domen_Model_Abstract {
    
    const TYPE_PERCENT = 1;
    const TYPE_OVER_TIME = 2;
    
    private $id;
    private $metalCode;
    private $created;
    
    protected $_aliases = array('metal_code'=>'metalCode');

    
    

    public function getOptions() {
        return array('id'=>$this->getId(),
            'type'=>$this->getType(),
            'metal_code'=>$this->getMetalCode(),
            'body'=>$this->getBody(),
            'created'=>$this->getCreated());
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
    
    public function getMetalCode() {
        return $this->metalCode;
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

    public function setMetalCode($code) {
        $this->metalCode = $code;
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



}
