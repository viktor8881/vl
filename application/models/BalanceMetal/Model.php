<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BalanceMetal_model
 *
 * @author Viktor
 */
class BalanceMetal_Model extends Core_Domen_Model_Abstract {
    
    private $id;
    private $metalCode;
    private $balance;
    
    private $_metal;
    
    protected $_aliases = array('metal_code'=>'metalCode');

    
    public function getOptions() {
        return array('id'=>$this->getId(),
            'metal_code'=>$this->getMetalCode(),
            'balance'=>$this->getBalance());
    }
    
    public function getId() {
        return $this->id;
    }

    public function getMetalCode() {
        return $this->metalCode;
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

    public function getBalance() {
        return $this->balance;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setMetalCode($metalCode) {
        $this->metalCode = $metalCode;
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
