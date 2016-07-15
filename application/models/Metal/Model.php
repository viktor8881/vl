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
    private $buy;
    private $sell;
    private $date;   

    
    public function getOptions() {
        return array('id'=>$this->getId(),
            'code'=>$this->getCode(),
            'buy'=>$this->getBuy(),
            'sell'=>$this->getSell(),
            'date'=>$this->getDateToDb());
    }

    
    public function getId() {
        return $this->id;
    }

    public function getCode() {
        return $this->code;
    }

    public function getBuy() {
        return $this->buy;
    }

    public function getSell() {
        return $this->sell;
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

    public function setBuy($bay) {
        $this->buy = $bay;
        return $this;
    }

    public function setSell($sell) {
        $this->sell = $sell;
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
