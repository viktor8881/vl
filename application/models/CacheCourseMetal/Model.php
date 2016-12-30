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
    
    const TREND_UP = 1;
    const TREND_DOWN = 0;
    
    const OPERATION = 1;
    
    private $id;
    private $code;
    private $value_last;
    private $type_trend;
    private $data_value=array();
    private $date_last;   
    private $percent;
    private $operation;
    
    protected $_aliases = array('value_last'=>'LastValue',
        'type_trend'=>'TypeTrend',
        'data_value'=>'DataValue',
        'date_last'=>'LastDate',);

    
    public function getOptions() {
        return array('id'=> $this->getId(),
            'code'      => $this->getCode(),
            'value_last'=> $this->getLastValue(),
            'type_trend'=> $this->getTypeTrend(),
            'data_value'=> $this->getDataValueToDb(),
            'date_last' => $this->getLastDateToDb(),
            'percent'   => $this->getPercent(),
            'operation' => $this->getOperation(),
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

    public function isUpTrend() {
        return $this->getTypeTrend() == self::TREND_UP;
    }

    public function isDownTrend() {
        return $this->getTypeTrend() == self::TREND_DOWN;
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
    
    public function countDataValue() {
        return count($this->data_value);
    }

    public function getDataValueToDb() {
        return json_encode($this->data_value);
    }

    public function setDataValue($data_value) {
        $this->data_value = json_decode($data_value, true);
        return $this;
    }
    
    public function addDataValue(Core_Date $date,  $value) {
        $this->data_value[] = array('data'=>$date->formatDbDate(), 'value'=>$value);
        return $this;
    }
    
    public function addDataValueByCourse(CourseMetal_Model $model) {
        return $this->addDataValue($model->getDate(), $model->getValue());
    }
    
    /**
     * @return Core_Date
     */
    public function getFirstDate() {
        $first = reset($this->getDataValue());
        if ($first) {
            return new Core_Date($first['data']);
        }
        return null;
    }
    
    public function getFirstDateFormatDMY() {
        return $this->getFirstDate()->formatDMY();
    }

    public function getFirstValue() {
        $first = reset($this->getDataValue());
        if ($first) {
            return $first['value'];
        }
        return null;
    }
        
    /**     
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
        return $this->getLastDate()->format(Core_Date::DB_DATE);
    }
    
    public function getLastDateFormatDMY() {
        return $this->getLastDate()->format(Core_Date::DMY);
    }

    
    public function getOperation() {
        return $this->operation;
    }

    public function setOperation($operation) {
        $this->operation = $operation;
        return $this;
    }

    public function hasOperation() {
        return !is_null($this->operation);
    }
            
    public function getValueFigureByNum($num) {
        $first = Core_Math::round($this->getFirstValue(), 6);
        $last = Core_Math::round($this->getLastValue(),6);
        $part = Core_Math::round(abs(($first - $last))/($this->countDataValue()-1));
        if ($this->isUpTrend()) {
            $result = $first+($part*$num);
        }else{
            $result = $first-($part*$num);
        }
        return $result;
    }
    
}
