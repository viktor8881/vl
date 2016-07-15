<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Course_model
 *
 * @author Viktor
 */
class Course_Model extends Core_Domen_Model_Abstract {
    
    private $id;
    private $code;
    private $nominal;
    private $value;
    private $date;   

    
    public function getOptions() {
        return array('id'=>$this->getId(),
            'code'=>$this->getCode(),
            'nominal'=>$this->getNominal(),
            'value'=>$this->getValue(),
            'date'=>$this->getDateToDb());
    }

    
    public function getId() {
        return $this->id;
    }

    public function getCode() {
        return $this->code;
    }

    public function getNominal() {
        return $this->nominal;
    }

    public function getValue() {
        return $this->value;
    }

    public function getValueForOne() {
        return $this->value/$this->getNominal();
    }

    /**
     * 
     * @return Core_Date
     */
    public function getDate() {
        return $this->date;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setCode($code) {
        $this->code = $code;
        return $this;
    }

    public function setNominal($nominal) {
        $this->nominal = $nominal;
        return $this;
    }

    public function setValue($value) {
        $this->value = $value;
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

    public function getDateToDb() {
        return $this->getDate()->format(Core_Date::DB);
    }
    
    public function getDateFormatDMY() {
        return $this->getDate()->format(Core_Date::DMY);
    }

}
