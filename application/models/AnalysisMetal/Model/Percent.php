<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AnalysisMetal_Model_Percent
 *
 * @author Viktor
 */
class AnalysisMetal_Model_Percent extends AnalysisMetal_Model_Abstract {
    
    private $percent;
    private $period;
    private $startDate;
    private $startValue;
    private $endDate;
    private $endValue;
    
    public function getPercent() {
        return $this->percent;
    }

    public function getPeriod() {
        return $this->period;
    }

    public function getStartDate() {
        return $this->startDate;
    }

    public function getStartValue() {
        return $this->startValue;
    }

    public function getEndDate() {
        return $this->endDate;
    }

    public function getEndValue() {
        return $this->endValue;
    }

    public function setPercent($percent) {
        $this->percent = $percent;
        return $this;
    }

    public function setPeriod($period) {
        $this->period = $period;
        return $this;
    }

    public function setStartDate($date) {
        if ($date instanceof DateTime ) {
            $this->startDate = $date;
        }else{
            $this->startDate = new Core_Date($date);
        }
        return $this;
    }
    
    public function getStartDateFormatDMY() {
        return $this->getStartDate()->format(Core_Date::DMY);
    }

    public function setStartValue($startValue) {
        $this->startValue = $startValue;
        return $this;
    }

    public function setEndDate($date) {
        if ($date instanceof DateTime ) {
            $this->endDate = $date;
        }else{
            $this->endDate = new Core_Date($date);
        }	
        return $this;
    }
    
    public function getEndDateFormatDMY() {
        return $this->getEndDate()->format(Core_Date::DMY);
    }    

    public function setEndValue($endValue) {
        $this->endValue = $endValue;
        return $this;
    }
    
    public function isQuotesGrowth() {
        return $this->getStartValue() < $this->getEndValue();
    }
    
    public function isQuotesFall() {
        return $this->getStartValue() > $this->getEndValue();
    }

    public function getDiffMoneyValue() {
        return abs($this->getStartValue() - $this->getEndValue());
    }

    public function getDiffPercent() {
        return 100 - (abs($this->getStartValue()*100/$this->getEndValue()));
    }

    // == abstract methods =
    
    public function getType() {
        return AnalysisMetal_Model_Abstract::TYPE_PERCENT;
    }
    
    public function getBody() {
        $body = array('percent'=>$this->getPercent(),
            'period'    =>$this->getPeriod(),
            'startDate' =>$this->getStartDateFormatDMY(),
            'startValue'=>$this->getStartValue(),
            'endDate'   =>$this->getEndDateFormatDMY(),
            'endValue'  =>$this->getEndValue());
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
