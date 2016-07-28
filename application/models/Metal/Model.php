<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Metal_model
 *
 * @author Viktor
 */
class Metal_Model extends Core_Domen_Model_Abstract {
    
    private $id;
    private $code;
    private $name;
    
    

    
    public function getOptions() {
        return array('id'=>$this->getId(),
            'code'=>$this->getCode(),
            'name'=>$this->getName());
    }
    
    public function getId() {
        return $this->id;
    }

    public function getCode() {
        return $this->code;
    }

    public function getName() {
        return $this->name;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setCode($code) {
        $this->code = $code;
        return $this;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }



}
