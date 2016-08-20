<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Account_model
 *
 * @author Viktor
 */
class Account_Model extends Core_Domen_Model_Abstract {
    
    private $id;
    private $balance;

    
    public function getOptions() {
        return array('id'=>$this->getId(),
            'balance'=>$this->getBalance());
    }
    
    public function getId() {
        return $this->id;
    }

    public function getBalance() {
        return $this->balance;
    }

    public function setId($id) {
        $this->id = $id;
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
