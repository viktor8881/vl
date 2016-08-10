<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AnalysisCurrency_Model_OverTime
 *
 * @author Viktor
 */
class AnalysisCurrency_Model_OverTime extends AnalysisCurrency_Model_Abstract {
    
    
    private $period;    
    // массив котировок ключ_дата => знач_котировки
    private $listData = array();
    
    
    public function getPeriod() {
        return $this->period;
    }
    
    public function getListData() {
        return $this->listData;
    }

    public function setPeriod($period) {
        $this->period = $period;
        return $this;
    }

    public function setListData($listData) {
        $this->listData = $listData;
        return $this;
    }
    
    public function addData($data, $course) {
        $this->listData[$data] = $course;
        return $this;
    }
    
    public function isQuotesGrowth() {
        $list = $this->getListData();
        return current($list) > end($list);
    }
    
    public function isQuotesFall() {
        $list = $this->getListData();
        return current($list) < end($list);
    }
    
    public function countData() {
        return count($this->listData);
    }

    
    // == abstract methods =
    
    public function getType() {
        return AnalysisMetal_Model_Abstract::TYPE_OVER_TIME;
    }
    
    public function getBody() {
        $body = array('period'=>$this->getPeriod(),
            'listData'=>$this->getListData());
        return json_encode($body);
    }

    public function setBody($body) {
        $options = json_decode($body, true);
        if (is_null($options)) {
            throw new RuntimeException('Body task can not be empty');
        }
        return $this->setOptions($options);
    }
    

}
