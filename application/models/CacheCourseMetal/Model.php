<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CacheCourseMetal_model
 *
 * @author Viktor
 */
class CacheCourseMetal_Model extends Core_Domen_Model_Abstract {
    
    private $id;
    private $code;
    private $value_last;
    private $type_trend;
    private $data_value;   
    private $date_last;   
    private $percent;   

    
    public function getOptions() {
        return array('id'=> $this->getId(),
            'code'      => $this->getCode(),
            'value_last'=> $this->getLastValue(),
            'type_trend'=> $this->getTypeTrend(),
            'data_value'=> $this->getDataValue(),
            'date_last' => $this->getLastDate(),
            'percent'   => $this->getPercent(),
            );
    }

    
    public function getId() {
        return $this->id;
    }

    public function getCode() {
        return $this->code;
    }
    
    public function getLastValue() {
        return $this->value_last;
    }

    public function getTypeTrend() {
        return $this->type_trend;
    }

    public function getPercent() {
        return $this->percent;
    }

    public function setLastValue($value_last) {
        $this->value_last = $value_last;
        return $this;
    }

    public function setTypeTrend($type_trend) {
        $this->type_trend = $type_trend;
        return $this;
    }

    public function setPercent($percent) {
        $this->percent = $percent;
        return $this;
    }

    public function getDataValue() {
        return $this->data_value;
    }

    public function setDataValue($data_value) {
        $this->data_value = $data_value;
        return $this;
    }

        
    /**
     * 
     * @return Core_Date
     */
    public function getLastDate() {
        return $this->date_last;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setCode($code) {
        $this->code = $code;
        return $this;
    }

    public function setLastDate($date) {
        if ($date instanceof DateTime ) {
            $this->date_last = $date;
        }else{
            $this->date_last = new Core_Date($date);
        }
        return $this;
    }

    public function getLastDateToDb() {
        return $this->getLastDate()->format(Core_Date::DB);
    }
    
    public function getLastDateFormatDMY() {
        return $this->getLastDate()->format(Core_Date::DMY);
    }

}
