<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mail_model
 *
 * @author Viktor
 */
class Mail_CurrencyModel {
    
    
    private $name;
    private $startDate;
    private $startValue;
    private $endDate;
    private $endValue;
    
    
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setStartValue($startValue) {
        $this->startValue = $startValue;
        return $this;
    }

    public function getStartValue() {
        return $this->startValue;
    }
    
    public function getStartMoneyValue() { return $this->getStartValue(); }

    public function setEndValue($endValue) {
        $this->endValue = $endValue;
        return $this;
    }

    public function getEndValue() {
        return $this->endValue;
    }
    
    public function getEndMoneyValue() { return $this->getEndValue(); }

    /**
     * 
     * @return Core_Date
     */
    public function getEndDate() {
        return $this->endDate;
    }

    public function setEndDate(DateTime $date) {
        $this->endDate = $date;	
        return $this;
    }   
    
    public function getEndDateFormatDMY() {
        return $this->getEndDate()->format(Core_Date::DMY);
    }    

    /**
     * 
     * @return Core_Date
     */
    public function getStartDate() {
        return $this->startDate;
    }

    public function setStartDate(DateTime $date) {
        $this->startDate = $date;	
        return $this;
    }   
    
    public function getStartDateFormatDMY() {
        return $this->getStartDate()->format(Core_Date::DMY);
    }

    public function getDiffMoneyValue() {
        return abs($this->getStartValue() - $this->getEndValue());
    }

    public function getDiffPercent() {
        return 100 - (abs($this->getStartValue()*100/$this->getEndValue()));
    }
    
}
